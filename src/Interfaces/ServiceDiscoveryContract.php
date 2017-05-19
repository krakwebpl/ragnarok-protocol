<?php
/**
 * Created by PhpStorm.
 * User: gmajta
 * Date: 18.05.17
 * Time: 15:30
 */

namespace Krakweb\RagnarokProtocol\Interfaces;


interface ServiceDiscoveryContract
{
    function loadServices();

    function getServiceByKey($key);
}