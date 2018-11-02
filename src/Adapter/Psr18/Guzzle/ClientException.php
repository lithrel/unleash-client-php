<?php
namespace C5A\Unleash\Adapter\Psr18\Guzzle;

use Psr\Http\Client\ClientExceptionInterface;

class ClientException extends \GuzzleHttp\Exception\ClientException implements ClientExceptionInterface
{
}