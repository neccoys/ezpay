<?php

namespace Liyuu\Ezpay\Providers;

use Liyuu\Ezpay\Contracts\ProviderInterface;
use Liyuu\Ezpay\Orders\EzOrder;
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;

class Paypal implements ProviderInterface
{

    public function __construct()
    {
    }

    public function checkout(EzOrder $payment)
    {
        $shippingPrice = 2;
        $taxPrice = 0;
        $subTotal = 26;

        $itemList = new ItemList();

        $item1 = new Item();
        $item1->setName("產品2")->setCurrency("USD")->setQuantity(1)->setPrice(10);
        $itemList->addItem($item1);
        $item2 = new Item();
        $item2->setName("產品1")->setCurrency("USD")->setQuantity(2)->setPrice(8);
        $itemList->addItem($item2);


        // Set payment details
        $details = new Details();
        $details->setShipping($shippingPrice)->setTax($taxPrice)->setSubtotal($subTotal);

        // Set payment amount
        //注意，此處的subtotal，必須是產品數*產品價格，所有值必須是正確的，否則會報錯
        $total = $shippingPrice + $subTotal + $taxPrice;
        $amount = new Amount();
        $amount->setCurrency("USD")->setTotal($total)->setDetails($details);

        // Set transaction object
        $transaction = new Transaction();
        $transaction->setAmount($amount)->setItemList($itemList)->setDescription("這是交易描述")
            ->setInvoiceNumber(uniqid());//setInvoiceNumber為支付唯一識別符號,在使用時建議改成訂單號

        $payer = new Payer();
        $payer->setPaymentMethod('paypal');//["credit_card", "paypal"]
        $redirectUrls = new RedirectUrls();
        $redirectUrl = "http://127.0.0.1:8000/success";//支付成功跳轉的回撥
        $cancelUrl = "http://127.0.0.1:8000/cancel";//取消支付的回撥
        $redirectUrls->setReturnUrl($redirectUrl)->setCancelUrl($cancelUrl);

        // Create the full payment object
        $payment = new Payment();
        $payment->setIntent("sale")->setPayer($payer)->setRedirectUrls($redirectUrls)->addTransaction($transaction);

        try {
            $clientId = "ATkaURGi3G4ZsKE9iQmhpc3PCMUe2PO-qsz6g4wuV0ZAsE7EEc52VlOZ9L-5fUwpwwFUHaj4o1lfKkbm";//上面應用的clientId和secret
            $secret = "EG8YRktuhK6iAQrcl7PK5NRecFqg584cUoo7udYBkIhvzCs6-77XhTuP8ADG8LlFtY5k59hMUIH6ctc3";
            $oAuth = new \PayPal\Auth\OAuthTokenCredential($clientId, $secret);
            $apiContext =  new \PayPal\Rest\ApiContext($oAuth);
            if(env('APP_DEBUG') === false ){
                $apiContext->setConfig(['mode' => 'live']);//設定線上環境,預設是sandbox
            }
            $payment->create($apiContext);
            $approvalUrl = $payment->getApprovalLink();
            dd($approvalUrl);//這個是請求支付的連結，在瀏覽器中請求此連結就會跳轉到支付頁面
        } catch (\Exception $e) {
            dd($e->getMessage());//錯誤提示
        }
    }

}
