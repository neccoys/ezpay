<?php


namespace Liyuu\Ezpay;


use InvalidArgumentException;
use Illuminate\Support\Manager;

use Liyuu\Ezpay\Drivers\Ecpay;

class EzpayManager extends Manager implements Contracts\Factory
{

    public function with($driver)
    {
        return $this->driver($driver);
    }

    protected function createEcpayDriver()
    {
        $config = $this->config->get('services.ecpay');

        return $this->buildProvider(
            Ecpay::class, $config
        );
    }

    public function buildProvider($provider, $config)
    {
        return new $provider(
            $this->container->make('request'), $config
        );
    }

    public function forgetDrivers()
    {
        $this->drivers = [];

        return $this;
    }

    public function setContainer($container)
    {
        $this->app = $container;
        $this->container = $container;

        return $this;
    }

    public function getDefaultDriver()
    {
        throw new InvalidArgumentException('No Ezpay driver was specified.');
    }
}
