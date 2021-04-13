<?php


namespace Liyuu\Ezpay\Orders;


class EzOrder
{

    public $shopName;

    /**
     * 總價
     * @var float
     */
    public $amount;

    /**
     * 所有品項
     * @var array
     */
    public $items;

    /**
     * 運費
     * @var float
     */
    public $shipping;

    /**
     * 付款方法
     * @var string
     */
    public $method;

    /**
     * 交易說明
     * @var string
     */
    public $remark;

    /**
     * 背景回傳交易結果網址
     * @var string
     */
    public $callbackUrl;

    /**
     * 交易完成導向網址
     * @var string
     */
    public $redirectUrl;

    public function __construct()
    {
        $this->items = collect();
    }

    public function getItems()
    {
        return $this->items->toArray();
    }
}
