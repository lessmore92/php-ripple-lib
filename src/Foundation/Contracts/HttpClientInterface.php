<?php

namespace Lessmore92\Ripple\Foundation\Contracts;


interface HttpClientInterface
{
    public function send(HttpRequestInterface $request): HttpResponseInterface;
}
