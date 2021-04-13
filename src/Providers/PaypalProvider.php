<?php


namespace Liyuu\Ezpay\Providers;


use Liyuu\Ezpay\Contracts\Factory;

class PaypalProvider implements Factory
{
    public function driver($driver = null)
    {
        return new Paypal();
    }
}
