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
    public function send(RagnarokBaseExchangeMessage $message);

    public function extractHeaders(RagnarokBaseExchangeMessage $message, $authorizationHeader);

    public function setServiceDiscovery(ServiceDiscoveryContract $serviceDiscovery);

    public function setProducer($producer);

    public function setMSConnectorFactory(MSConnectorFactoryContract $connectorFactory);


}