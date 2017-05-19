<?php
/**
 * Created by PhpStorm.
 * User: gmajta
 * Date: 18.05.17
 * Time: 15:40
 */

namespace Krakweb\RagnarokProtocol\RagnarokExchangeSender;


use Krakweb\RagnarokProtocol\Interfaces\ExchangeSenderContract;
use Krakweb\RagnarokProtocol\Interfaces\MSConnectorFactoryContract;
use Krakweb\RagnarokProtocol\Interfaces\ServiceDiscoveryContract;
use Krakweb\RagnarokProtocol\Protocol\RagnarokBaseExchangeMessage;
use Symfony\Component\HttpKernel\Exception\HttpException;

class RagnarokExchangeSender implements ExchangeSenderContract
{
    private $serviceDiscovery;

    private $producer;

    private $connectorFactory;

    function send(RagnarokBaseExchangeMessage $message)
    {
        throw new HttpException(500, 'exc');
    }

    function extractHeaders(RagnarokBaseExchangeMessage $message, $authorizationHeader)
    {
        $headers = $message->prepareHeaders();
        $headers['Authorization'] = $authorizationHeader;

        return $headers;
    }


    function setServiceDiscovery(ServiceDiscoveryContract $serviceDiscovery)
    {
        $this->serviceDiscovery = $serviceDiscovery;
    }

    function setProducer($producer)
    {
        $this->producer = $producer;
    }

    function setMSConnectorFactory(MSConnectorFactoryContract $connectorFactory)
    {
        $this->connectorFactory = $connectorFactory;
    }

}