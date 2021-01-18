<?php

namespace Lessmore92\Ripple\Model\Base;

use Lessmore92\Ripple\Utils;

/**
 * User: Lessmore92
 * Date: 1/14/2021
 * Time: 3:31 PM
 */
class Balance
{
    protected $drops = '';

    public function __construct(string $drops = '')
    {
        $this->drops = $drops;
    }

    public static function fromDrops($drops)
    {
        return new self($drops);
    }

    public function toXrp()
    {
        return Utils::dropsToXrp($this->drops);
    }

    public function __debugInfo()
    {
        return [
            'drops' => $this->drops,
            'xrp'   => Utils::dropsToXrp($this->drops),
        ];
    }
}
