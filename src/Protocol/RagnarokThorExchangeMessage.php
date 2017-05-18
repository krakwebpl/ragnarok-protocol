<?php
/**
 * Created by PhpStorm.
 * User: gmajta
 * Date: 18.05.17
 * Time: 08:54
 */

namespace Krakweb\RagnarokProtocol\Protocol;


class RagnarokThorExchangeMessage extends RagnarokBaseExchangeMessage
{
    /** @var  string */
    private $keyApp;
    
    /** @var  string */
    private $keyApi;

    public function __construct($sourceApp)
    {
        $this->type = self::TYPE_CLIENT;
        $this->sourceApp = $sourceApp;
    }

    /**
     * @return string
     */
    public function getKeyApp()
    {
        return $this->keyApp;
    }

    /**
     * @param string $keyApp
     */
    public function setKeyApp($keyApp)
    {
        $this->keyApp = $keyApp;
    }

    /**
     * @return string
     */
    public function getKeyApi()
    {
        return $this->keyApi;
    }

    /**
     * @param string $keyApi
     */
    public function setKeyApi($keyApi)
    {
        $this->keyApi = $keyApi;
    }

    public function serialize()
    {
        $data = parent::serialize();
        $data['keyApp'] = $this->keyApp;
        $data['keyApi'] = $this->keyApi;

        return json_encode($data);
    }

    public static function unserialize($serialized)
    {
        $data = json_decode($serialized, true);
        $message = new self($data['sourceApp']);
        $message->setKeyApp($data['keyApp']);
        $message->setKeyApi($data['keyApi']);
        $message->setRequestId($data['requestId']);
        $message->setLogMessage($data['logMessage']);
        $message->setLogContext($data['logContext']);
        $message->setTarget($data['target']);
        $message->setServiceKey($data['serviceKey']);
        $message->setData($data['data']);
        $message->setMethod($data['method']);
        $message->setUri($data['uri']);
        $message->setRoutingKey($data['routingKey']);
        $message->setCommandName($data['commandName']);
        $message->setEventName($data['eventName']);

        return $message;
    }


}