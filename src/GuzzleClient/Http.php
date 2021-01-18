<?php

namespace Lessmore92\Ripple\GuzzleClient;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Lessmore92\Ripple\Foundation\Contracts\HttpClientInterface;
use Lessmore92\Ripple\Foundation\Contracts\HttpRequestInterface;
use Lessmore92\Ripple\Foundation\Contracts\HttpResponseInterface;

class Http implements HttpClientInterface
{
    /**
     * @var Client
     */
    protected $client;

    protected $timeout = 5;
    protected $baseUri = '';


    public function __construct(string $baseUri = '', float $timeout = 3)
    {
        $this->timeout = $timeout;
        $this->baseUri = $baseUri;
        $this->client  = new Client([
            'base_uri' => $this->baseUri,
            'timeout'  => $this->timeout,
        ]);
    }

    /**
     * @param HttpRequestInterface|Request $request
     * @return HttpResponseInterface
     * @throws GuzzleException
     */
    public function send(HttpRequestInterface $request): HttpResponseInterface
    {
        $response = $this->client->request('POST', $request->getUrl(), [
            'json' => $request->getBody()
        ]);

        return new Response($response);
    }

    public function setTimeOut(float $timeout): self
    {
        $this->timeout = $timeout;
        return $this;
    }
}
