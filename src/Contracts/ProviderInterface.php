<?php

namespace Liyuu\Ezpay\Contracts;

use Illuminate\Http\Request;
use Liyuu\Ezpay\Orders\EzOrder;

interface ProviderInterface
{
    public function checkout(EzOrder $payment);

    public function callback(Request $request);
}
