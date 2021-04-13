<?php

namespace Liyuu\Ezpay\Facades;

use Illuminate\Support\Facades\Facade;
use Liyuu\Ezpay\Contracts\Factory;

/**
 * @method static \Liyuu\Ezpay\Contracts\ProviderInterface driver(string $driver = null)
 * @see \Liyuu\Ezpay\EzpayManager
 */
class Ezpay extends Facade
{

    protected static function getFacadeAccessor()
    {
        return Factory::class;
    }

}
