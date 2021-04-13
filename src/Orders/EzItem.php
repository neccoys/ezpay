<?php


namespace Liyuu\Ezpay\Orders;


class EzItem
{
    /**
     * 名稱
     * @var string
     */
    public $name;

    /**
     * 單價
     * @var float
     */
    public $price;

    /**
     * 數量
     * @var int
     */
    public $quantity;

    /**
     * 其他補充
     * @var array
     */
    public $attributes;

    public function toArray()
    {
        return [
            'name' => $this->name,
            'price' => $this->price,
            'quantity' => $this->quantity,
            'attributes' => $this->attributess ?? []
        ];
    }
}
