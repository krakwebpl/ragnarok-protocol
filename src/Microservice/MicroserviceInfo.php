<?php
/**
 * Created by PhpStorm.
 * User: gmajta
 * Date: 18.05.17
 * Time: 15:33
 */

namespace Krakweb\RagnarokProtocol\Microservice;


class MicroserviceInfo
{
    private $key;
    
    private $name;
    
    private $url;
    
    private $description;

    /**
     * MicroserviceInfo constructor.
     * @param $key
     * @param $name
     * @param $url
     * @param $description
     */
    public function __construct($key, $name, $url, $description)
    {
        $this->key = $key;
        $this->name = $name;
        $this->url = $url;
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }
    
    


}