<?php
/**
 * Created by PhpStorm.
 * User: gmajta
 * Date: 18.05.17
 * Time: 08:54
 */

namespace Krakweb\RagnarokProtocol\Protocol;


use Krakweb\RagnarokProtocol\Interfaces\Serializable;

abstract class RagnarokBaseExchangeMessage implements Serializable
{
    const TYPE_SUPERVISOR = 'supervisor';
    const TYPE_CLIENT = 'client';

    const SERVICE_KEY_ALL = 'all';

    const TARGET_QUEUE = 'queue';
    const TARGET_API = 'api';

    const ROUTING_KEY_DIRECT = 'direct';
    const ROUTING_KEY_EXCEPT = 'except';
    const ROUTING_KEY_ALL = 'all';

    /** @var  string supervisor|client typ aplikacji z której pochodzi wiadomość */
    protected $type;
    
    /** @var  string nazwa aplikacji z której pochodzi wiadomość */
    protected $sourceApp;

    /** @var  string uniklany identyfikator żądania */
    protected $requestId;

    /** @var  string wiadomość, która powinna zostać dodana do logów na etapie bramy */
    protected $logMessage;

    /** @var  array kontekst, który powinien zostać do logów na etapie bramy */
    protected $logContext;

    /** @var  string api|queue  określa w jaki sposób ma zostać wiadomość obsłużona (bezposrednie wywołanie do api lub dodanie do kolejki) */
    protected $target;

    /** @var  string klucz identyfikujący mikroserwis do którego chcemy coś wysłać lub "all" gdy chcemy rozgłosić wiadomość do wszystkich */
    protected $serviceKey; // all wyłącznie w przypadku target = queue

    /** @var  array dane które chcemy wysłać do mikroserwisów */
    protected $data;

    // ----------------------- dla target ustawionego na api ------------------- //

    /** @var  string */
    protected $method;

    /** @var  string */
    protected $uri;


    // ---------------------- dla target ustawionego na queue ------------------- //

    /** @var  string direct|except|all */
    protected $routingKey;

    /** @var  string */
    protected $commandName;

    /** @var  string */
    protected $eventName;

    /**
     * @return string
     */
    public function getSourceApp()
    {
        return $this->sourceApp;
    }

    /**
     * @param string $sourceApp
     */
    public function setSourceApp($sourceApp)
    {
        $this->sourceApp = $sourceApp;
    }

    /**
     * @return string
     */
    public function getRequestId()
    {
        return $this->requestId;
    }

    /**
     * @param string $requestId
     */
    public function setRequestId($requestId)
    {
        $this->requestId = $requestId;
    }

    /**
     * @return string
     */
    public function getLogMessage()
    {
        return $this->logMessage;
    }

    /**
     * @param string $logMessage
     */
    public function setLogMessage($logMessage)
    {
        $this->logMessage = $logMessage;
    }

    /**
     * @return array
     */
    public function getLogContext()
    {
        return $this->logContext;
    }

    /**
     * @param array $logContext
     */
    public function setLogContext($logContext)
    {
        $this->logContext = $logContext;
    }

    /**
     * @return string
     */
    public function getTarget()
    {
        return $this->target;
    }

    /**
     * @param string $target
     */
    public function setTarget($target)
    {
        $this->target = $target;
    }

    /**
     * @return string
     */
    public function getServiceKey()
    {
        return $this->serviceKey;
    }

    /**
     * @param string $serviceKey
     */
    public function setServiceKey($serviceKey)
    {
        $this->serviceKey = $serviceKey;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param array $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @param string $method
     */
    public function setMethod($method)
    {
        $this->method = $method;
    }

    /**
     * @return string
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * @param string $uri
     */
    public function setUri($uri)
    {
        $this->uri = $uri;
    }

    /**
     * @return string
     */
    public function getRoutingKey()
    {
        return $this->routingKey;
    }

    /**
     * @param string $routingKey
     */
    public function setRoutingKey($routingKey)
    {
        $this->routingKey = $routingKey;
    }

    /**
     * @return string
     */
    public function getCommandName()
    {
        return $this->commandName;
    }

    /**
     * @param string $commandName
     */
    public function setCommandName($commandName)
    {
        $this->commandName = $commandName;
    }

    /**
     * @return string
     */
    public function getEventName()
    {
        return $this->eventName;
    }

    /**
     * @param string $eventName
     */
    public function setEventName($eventName)
    {
        $this->eventName = $eventName;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    public function serialize()
    {
        $data = [
            'type' => $this->type,
            'sourceApp' => $this->sourceApp,
            'requestId' => $this->requestId,
            'logMessage' => $this->logMessage,
            'logContext' => $this->logContext,
            'target' => $this->target,
            'serviceKey' => $this->serviceKey,
            'data' => $this->data,
            'method' => $this->method,
            'uri' => $this->uri,
            'routingKey' => $this->routingKey,
            'commandName' => $this->commandName,
            'eventName' => $this->eventName
        ];

        return $data;
    }
}