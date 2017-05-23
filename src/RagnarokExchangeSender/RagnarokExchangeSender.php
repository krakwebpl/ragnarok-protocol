<?php
/**
 * Created by PhpStorm.
 * User: gmajta
 * Date: 18.05.17
 * Time: 15:40
 */

namespace Krakweb\RagnarokProtocol\RagnarokExchangeSender;


use Krakweb\RagnarokProtocol\Interfaces\BaseConnector;
use Krakweb\RagnarokProtocol\Interfaces\ExchangeSenderContract;
use Krakweb\RagnarokProtocol\Interfaces\MSConnectorFactoryContract;
use Krakweb\RagnarokProtocol\Interfaces\ServiceDiscoveryContract;
use Krakweb\RagnarokProtocol\Microservice\MicroserviceInfo;
use Krakweb\RagnarokProtocol\Protocol\RagnarokBaseExchangeMessage;
use Symfony\Component\HttpKernel\Exception\HttpException;

class RagnarokExchangeSender implements ExchangeSenderContract
{
    /** @var  ServiceDiscoveryContract */
    private $serviceDiscovery;

    private $producer;

    /** @var  MSConnectorFactoryContract */
    private $connectorFactory;


    public function send(RagnarokBaseExchangeMessage $message)
    {
        $this->checkConfigurationErrors($message);

        $target = $message->getTarget();
        if ($target == RagnarokBaseExchangeMessage::TARGET_API) {
            return $this->sendByAPI($message);
        } elseif ($target == RagnarokBaseExchangeMessage::TARGET_QUEUE) {
            return $this->sendByQueue($message);
        } else {
            throw new HttpException(500, 'wrong target or target is empty');
        }
    }

    private function checkConfigurationErrors(RagnarokBaseExchangeMessage $message)
    {
        $target = $message->getTarget();
        $serviceKey = $message->getServiceKey();
        $commandName = $message->getCommandName();
        $eventName = $message->getEventName();

        if ($target == RagnarokBaseExchangeMessage::TARGET_API) {
            if ($serviceKey == RagnarokBaseExchangeMessage::SERVICE_KEY_ALL) {
                throw new HttpException(500, 'cant use target set to api with serviceName set to all');
            }
        } elseif ($target == RagnarokBaseExchangeMessage::TARGET_QUEUE) {
            if (empty($commandName) && empty($eventName)) {
                throw new HttpException(500, 'cant use target set to queue with empty commandName and eventName');
            }
        }
    }

    private function  sendByQueue(RagnarokBaseExchangeMessage $message) {
        $data = $message->getData();
        $routingKey = $this->generateRoutingKey($message);
        $headers = array_merge($this->extractHeaders($message, false), ['X-Routing-Key' => $routingKey]);

        try {
            $this->producer->publish($data, $routingKey, $headers);
        } catch (\Exception $e) {
            return [500, ["message" => "Nie udało się zakolejkować akcji. Powód: ". $e->getMessage()]];
        }

        $logMessage = $message->getLogMessage();
        $logContext = $message->getLogContext();
        if (!empty($logMessage)) {
            $this->producer->log($logMessage, $logContext, $headers);
        }

        return [200, ["message" => "Akcja została zakolejkowana"]];
    }


    private function sendByAPI(RagnarokBaseExchangeMessage $message) {
        /** @var MicroserviceInfo $service */
        $service = $this->serviceDiscovery->getServiceByKey($message->getServiceKey());

        /** @var BaseConnector $connector */
        $connector = $this->connectorFactory->makeConnector($service, $this->extractHeaders($message, $service->getAuthorization()));

        $logMessage = $message->getLogMessage();
        $logContext = $message->getLogContext();
        if (!empty($logMessage)) {
            $connector->log($logMessage, $logContext);
        }

        $method = $message->getMethod();
        $uri = $message->getUri();
        $data = $message->getData();

        switch ($method) {
            case 'POST':
                $response = $connector->postRequest($uri, $data);
                break;
            case 'PATCH':
                $response = $connector->patchRequest($uri, $data);
                break;
            case 'DELETE':
                $response = $connector->deleteRequest($uri);
                break;
            case 'GET':
            default:
                $response = $connector->getRequest($uri, $data);
        }

        return $response;

    }

    public function extractHeaders(RagnarokBaseExchangeMessage $message, $authorizationHeader)
    {
        $headers = $message->prepareHeaders();
        if (! empty($authorizationHeader)) {
            $headers['Authorization'] = $authorizationHeader;
        }

        return $headers;
    }


    public function setServiceDiscovery(ServiceDiscoveryContract $serviceDiscovery)
    {
        $this->serviceDiscovery = $serviceDiscovery;
    }

    public function setProducer($producer)
    {
        $this->producer = $producer;
    }

    public function setMSConnectorFactory(MSConnectorFactoryContract $connectorFactory)
    {
        $this->connectorFactory = $connectorFactory;
    }

    private function generateRoutingKey(RagnarokBaseExchangeMessage $message)
    {
        $routingKeyPart = $message->getRoutingKey();
        $commandName = $message->getCommandName();
        $eventName = $message->getEventName();
        $serviceName = "";
        if (
            $routingKeyPart == RagnarokBaseExchangeMessage::ROUTING_KEY_DIRECT
            || $routingKeyPart == RagnarokBaseExchangeMessage::ROUTING_KEY_EXCEPT
        ) {
            /** @var MicroserviceInfo $service */
            $service = $this->serviceDiscovery->getServiceByKey($message->getServiceKey());
            $serviceName = $service->getName();
        }

        return $routingKeyPart
        . (!empty($serviceName) ? '.' . $serviceName : '')
        . (!empty($commandName) ? '.command.' . $commandName : '')
        . (!empty($eventName) ? '.event.' . $eventName : '');
    }
}