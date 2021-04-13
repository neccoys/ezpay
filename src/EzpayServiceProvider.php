<?php


namespace Liyuu\Ezpay;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;
use Liyuu\Ezpay\Contracts\Factory;

class EzpayServiceProvider extends ServiceProvider implements DeferrableProvider
{
    public function register()
    {
        $this->app->singleton(Factory::class, function ($app) {
            return new EzpayManager($app);
        });
    }

    public function provides()
    {
        return [Factory::class];
    }

}
