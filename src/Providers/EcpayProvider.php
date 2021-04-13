<?php


namespace Liyuu\Ezpay\Providers;


use Liyuu\Ezpay\Contracts\Factory;

class EcpayProvider implements Factory
{

    public function driver($driver = null)
    {
       return new Ecpay();
    }
}
