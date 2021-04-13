<?php

namespace Liyuu\Ezpay\Providers;


use Ecpay\Sdk\Exceptions\RtnException;
use Ecpay\Sdk\Factories\Factory;
use Liyuu\Ezpay\Contracts\PayInterface;

class Ecpay extends AbstractProvider implements PayInterface
{
    private $merchantID;
    private $hashKey;
    private $hashIv;
    private $paymentType = 'aio';

    private $returnURL = 'https://www.ecpay.com.tw/example/receive';


    public function __construct()
    {
        $this->merchantID = config('ezpay.ecpay.merchantID');
        $this->hashKey = config('ezpay.ecpay.hashKey');
        $this->hashIv =  config('ezpay.ecpay.hashIv');
    }

    public function pay($ezorder)
    {
        try {
            $factory = new Factory();
            $autoSubmitFormService = $factory->createWithHash('AutoSubmitFormWithCmvService', $this->hashKey, $this->hashIv);

            $input = [
                'MerchantID' => $this->merchantID,
                'MerchantTradeNo' => self::GenOrderNo(6),
                'MerchantTradeDate' => date('Y/m/d H:i:s'),
                'PaymentType' => $this->paymentType,
                'TotalAmount' => $ezorder->amount,
                'TradeDesc' => empty($ezorder->remark) ? $ezorder->shopName : $ezorder->remark,
                'ItemName' => $this->setItems($ezorder->getItems()),
                'ReturnURL' => $ezorder->callbackUrl,
                'ChoosePayment' => $this->setPaymentMethod($ezorder->method),
                'EncryptType' => 1,
            ];

            $action = config('ezpay.ecpay.apiUrl');

            echo $autoSubmitFormService->generate($input, $action);
        } catch (RtnException $e) {
            dd($e->getMessage());
            echo '(' . $e->getCode() . ')' . $e->getMessage() . PHP_EOL;
        }
    }

    public function setPaymentMethod($method)
    {
        $method = array_intersect([
            'ALL', 'Credit', 'WebATM', 'ATM', 'CVS', 'BARCODE'
        ] ,$method);
        return implode('#', $method);
    }

    public function setItems($items)
    {
        $itemsList = [];
        foreach ($items as $item) {
            array_push($itemsList, sprintf("%s $%s x %s", $item->name, number_format($item->price), $item->quantity));
        }

        return implode('#', $itemsList);
    }
}
