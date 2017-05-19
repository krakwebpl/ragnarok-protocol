<?php
/**
 * Created by PhpStorm.
 * User: gmajta
 * Date: 18.05.17
 * Time: 15:51
 */

namespace Krakweb\RagnarokProtocol\Interfaces;


use Interfaces\BaseConnector;
use Krakweb\RagnarokProtocol\Microservice\MicroserviceInfo;

interface MSConnectorFactoryContract
{
    /**
     * @param \Krakweb\RagnarokProtocol\Microservice\MicroserviceInfo $info
     * @param array $headers
     * @return BaseConnector
     */
    function makeConnector(MicroserviceInfo $info, array $headers);
}