<?php

namespace Liyuu\Ezpay\Contracts;

interface Factory
{
    /**
     * @param null $driver
     * @return \Liyuu\Ezpay\Contracts\PayInterface
     */
    public function driver($driver = null);
}
