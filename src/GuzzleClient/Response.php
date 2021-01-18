<?php

namespace Lessmore92\Ripple\GuzzleClient;


use Lessmore92\Ripple\Foundation\Contracts\HttpResponseInterface;
use Psr\Http\Message\ResponseInterface;

class Response implements HttpResponseInterface
{
    private $body;
    private $headers;
    private $response;

    public function __construct(ResponseInterface $response)
    {
        $this->response = $response;
    }

    public function getBody()
    {
        return $this->response->getBody()
                              ->getContents()
            ;
    }


    public function getHeaders()
    {
        return $this->response->getHeaders();
    }

    public function getResponse(): ResponseInterface
    {
        $this->response;
    }
}
