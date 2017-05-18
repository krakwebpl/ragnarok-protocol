<?php
/**
 * Created by PhpStorm.
 * User: gmajta
 * Date: 18.05.17
 * Time: 08:56
 */

namespace Krakweb\RagnarokProtocol\Protocol;


class RagnarokOdynExchangeMessage extends RagnarokBaseExchangeMessage
{
    /** @var  string */
    private $token;

    public function __construct($sourceApp)
    {
        $this->type = self::TYPE_SUPERVISOR;
        $this->sourceApp = $sourceApp;
    }

    /**
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param string $token
     */
    public function setToken($token)
    {
        $this->token = $token;
    }

    public function serialize()
    {
        $data = parent::serialize();
        $data['token'] = $this->token;
        
        return json_encode($data);
    }

    public static function unserialize($serialized)
    {
        $data = json_decode($serialized, true);
        $message = new self($data['sourceApp']);
        $message->setToken($data['token']);
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