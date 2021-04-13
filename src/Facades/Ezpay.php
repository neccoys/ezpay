<?php

namespace Liyuu\Ezpay\Facades;

use Illuminate\Support\Facades\Facade;
use Liyuu\Ezpay\Contracts\Factory;

class Ezpay extends Facade
{

    protected static function getFacadeAccessor()
    {
        return Factory::class;
    }

}
