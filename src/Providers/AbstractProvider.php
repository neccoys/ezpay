<?php


namespace Liyuu\Ezpay\Providers;


abstract class AbstractProvider
{
    protected $totalAmounts;

    public static function GenOrderNo($num = 8)
    {
        $charid = strtoupper ( md5 ( uniqid ( rand (), true ) ) );
        return date("Ymdhis").substr ( $charid, 0, $num );
    }

}
