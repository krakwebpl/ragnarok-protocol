<?php
/**
 * Created by PhpStorm.
 * User: gmajta
 * Date: 19.05.17
 * Time: 08:56
 */

namespace Interfaces;


use GuzzleHttp\ClientInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;

interface BaseConnector
{
    function encodeMessage(array $data);
    function prepareHeaders(array $headers = []);
    function setLogger(LoggerInterface $logger);
    function log($message, array $context = []);
    function setClient(ClientInterface $client);
    function getRequest($uri, array $query = [], array $headers = []);
    function postRequest($uri, array $params = [], array $headers = []);
    function patchRequest($uri, array $params = [], array $headers = []);
    function deleteRequest($uri,  array $headers = []);
    function decodeResponse(ResponseInterface $response);
    function checkSecurity($statusCode, $responseData);
}