<?php
/**
 * Created by PhpStorm.
 * User: gmajta
 * Date: 18.05.17
 * Time: 15:40
 */

namespace Krakweb\RagnarokProtocol\RagnarokExchangeSender;


use Interfaces\BaseConnector;
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

        if ($target == RagnarokBaseExchangeMessage::TARGET_API) {
            if ($serviceKey == RagnarokBaseExchangeMessage::SERVICE_KEY_ALL) {
                throw new HttpException(500, 'cant use target set to api with serviceName set to all');
            }
        }
    }

    private function  sendByQueue(RagnarokBaseExchangeMessage $message) {

    }

    private function sendByAPI(RagnarokBaseExchangeMessage $message) {
        /** @var MicroserviceInfo $service */
        $service = $this->serviceDiscovery->getServiceByKey($message->getServiceKey());

        /** @var BaseConnector $connector */
        $connector = $this->connectorFactory->makeConnector($service, $this->extractHeaders($message, $service->getAuthorization()));
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
}