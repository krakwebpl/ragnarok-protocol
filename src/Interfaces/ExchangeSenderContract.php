<?php
/**
 * Created by PhpStorm.
 * User: gmajta
 * Date: 18.05.17
 * Time: 15:41
 */

namespace Krakweb\RagnarokProtocol\Interfaces;


use Krakweb\RagnarokProtocol\Protocol\RagnarokBaseExchangeMessage;

interface ExchangeSenderContract
{
    function send(RagnarokBaseExchangeMessage $message);

    function extractHeaders(RagnarokBaseExchangeMessage $message, $authorizationHeader);

    function setServiceDiscovery(ServiceDiscoveryContract $serviceDiscovery);

    function setProducer($producer);

    function setMSConnectorFactory(MSConnectorFactoryContract $connectorFactory);
}