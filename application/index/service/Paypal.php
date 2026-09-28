<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/12/30
 * Time: 10:12
 */

namespace app\index\service;

use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\MerchantPreferences;
use PayPal\Api\Payer;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Exception\PayPalConnectionException;
use PayPal\Rest\ApiContext;

class Paypal
{

    public function create($info)
    {
        $oid = $info['order_sn'];
        $product = 'Payment';
        $description = 'Payment';
        //paypal
        $clientId = config('payment.paypal.clientId');
        $clientSecret = config('payment.paypal.clientSecret');
        $this->Currency = config('payment.paypal.Currency');
        $this->accept_url = config('payment.paypal.accept_url');
        $this->cancel_url = config('payment.paypal.cancel_url');
        $this->notify_url = config('payment.paypal.notify_url');
        $this->PayPal = new ApiContext(
            new OAuthTokenCredential(
                $clientId,
                $clientSecret
            )
        );
        $paypal = $this->PayPal;
        $paypal ->setConfig(
            array(
//                'mode' => 'sandbox',
                'mode' => 'live',
                'log.LogEnabled' => true,
                'log.FileName' => 'PayPal.log',
                'log.LogLevel' => 'DEBUG', // PLEASE USE FINE LEVEL FOR LOGGING IN LIVE ENVIRONMENTS
                'cache.enabled' => true,
                // 'http.CURLOPT_CONNECTTIMEOUT' => 30
                // 'http.headers.PayPal-Partner-Attribution-Id' => '123123123'
            )
        );

        $total = $info['money'];//总价
        $shipping = 0;
        $price = $total;

        $payer = new Payer();
        $payer->setPaymentMethod('paypal');

        $item = new Item();
        $item->setName($product)->setCurrency($this->Currency)->setQuantity(1)->setPrice($price);

        $itemList = new ItemList();
        $itemList->setItems([$item]);

        $details = new Details();
        $details->setShipping($shipping)->setSubtotal($price);

        $amount = new Amount();
        $amount->setCurrency($this->Currency)->setTotal($total)->setDetails($details);

        $transaction = new Transaction();
        $oid .= rand(1000,9999);
        $transaction->setAmount($amount)->setItemList($itemList)->setDescription($description)->setInvoiceNumber($oid);

        $redirectUrls = new RedirectUrls();
        $redirectUrls->setReturnUrl($this->accept_url . '?success=true')->setCancelUrl($this->cancel_url . '?success=false');

        $notifyUrls = new MerchantPreferences();
        $notifyUrls->setNotifyUrl($this->notify_url . '?success=true');

        $payment = new \PayPal\Api\Payment();
        $payment->setIntent('sale')->setPayer($payer)->setRedirectUrls($redirectUrls)->setTransactions([$transaction]);
        try {
            $payment->create($paypal);
        } catch (PayPalConnectionException $e) {
            $fp = fopen('public/logs/paypal_'.date('Y-m-d').'.txt', 'a+');
            fwrite($fp, date('H:i:s') . " Pay error： \n" . $e->getMessage() . "\n");
//                $this->error('System error', url('member/index'));
            return ['code'=>0, 'msg' => $e->getMessage()];
        }
        $approvalUrl = $payment->getApprovalLink();
        return ['code' => 1, 'msg' => 'success', 'approvalUrl' => $approvalUrl];
    }

}
