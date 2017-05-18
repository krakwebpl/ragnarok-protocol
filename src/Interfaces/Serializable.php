<?php
/**
 * Created by PhpStorm.
 * User: gmajta
 * Date: 18.05.17
 * Time: 09:04
 */

namespace Krakweb\RagnarokProtocol\Interfaces;


interface Serializable
{
    public function serialize();

    public static function unserialize($serialized);
}