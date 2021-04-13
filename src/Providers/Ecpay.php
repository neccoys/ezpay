<?php

namespace Liyuu\Ezpay\Providers;


use Ecpay\Sdk\Exceptions\RtnException;
use Ecpay\Sdk\Factories\Factory;
use Ecpay\Sdk\Response\VerifiedArrayResponse;
use Illuminate\Http\Request;
use Liyuu\Ezpay\Contracts\ProviderInterface;
use Liyuu\Ezpay\Orders\EzOrder;

class Ecpay extends AbstractProvider implements ProviderInterface
{
    private $apiUrl;
    private $merchantID;
    private $hashKey;
    private $hashIv;
    private $paymentType = 'aio';

    public function __construct(Request $request, $config)
    {
        $this->apiUrl = $config['apiUrl'];
        $this->merchantID = $config['merchantID'];
        $this->hashKey = $config['hashKey'];
        $this->hashIv =  $config['hashIv'];
    }

    public function checkout(EzOrder $ezorder)
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

            echo $autoSubmitFormService->generate($input, $this->apiUrl);

        } catch (RtnException $e) {
            echo '(' . $e->getCode() . ')' . $e->getMessage() . PHP_EOL;
        }
    }

    public function callback(Request $request, string $orderNo)
    {
        try {
            $factory = new Factory;
            $checkoutResponse = $factory->createWithHash(VerifiedArrayResponse::class, $this->hashKey, $this->hashIv);

            $_POST = [
                'MerchantID' => $this->merchantID,
                'MerchantTradeNo' => $orderNo,
                'PaymentDate' => '2019/05/09 00:01:21',
                'PaymentType' => 'Credit_CreditCard',
                'PaymentTypeChargeFee' => '1',
                'RtnCode' => '1',
                'RtnMsg' => '交易成功',
                'SimulatePaid' => '0',
                'TradeAmt' => '500',
                'TradeDate' => '2019/05/09 00:00:18',
                'TradeNo' => '1905090000188278',
                'CheckMacValue' => '59B085BAEC4269DC1182D48DEF106B431055D95622EB285DECD400337144C698',
            ];

            var_dump($checkoutResponse->get($_POST));
        } catch (RtnException $e) {
            echo '(' . $e->getCode() . ')' . $e->getMessage() . PHP_EOL;
        }
    }

    /**
     * 設定綠界付款方法
     * @param $method
     * @return string
     */
    public function setPaymentMethod($method)
    {
        $method = array_intersect([
            'ALL', 'Credit', 'WebATM', 'ATM', 'CVS', 'BARCODE'
        ] ,$method);
        return implode('#', $method);
    }

    /**
     * 綠界商品組合#
     * @param $items
     * @return string
     */
    public function setItems($items)
    {
        $itemsList = [];
        foreach ($items as $item) {
            array_push($itemsList, sprintf("%s $%s x %s", $item->name, number_format($item->price), $item->quantity));
        }

        return implode('#', $itemsList);
    }
}
