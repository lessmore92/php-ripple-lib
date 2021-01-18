<?php

namespace Lessmore92\Ripple\Foundation\Contracts;


interface HttpResponseInterface
{
    public function getBody();

    public function getHeaders();
}
