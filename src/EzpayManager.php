<?php


namespace Liyuu\Ezpay;


use InvalidArgumentException;
use Illuminate\Support\Manager;
use Liyuu\Ezpay\Contracts\ProviderInterface;
use Liyuu\Ezpay\Providers\Ecpay;
use Liyuu\Ezpay\Providers\PaypalProvider;

class EzpayManager extends Manager implements Contracts\Factory
{

    /**
     * @return ProviderInterface
     */
    protected function createEcpayDriver()
    {
        $config = $this->config->get('ezpay.ecpay');

        return $this->buildProvider(
            Ecpay::class, $config
        );
    }

    /**
     * @return ProviderInterface
     */
    protected function createPaypalDriver()
    {
        $config = $this->config->get('ezpay.paypal');

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
