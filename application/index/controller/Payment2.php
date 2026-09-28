<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/10/27
 * Time: 10:07
 */

namespace app\index\controller;
use app\index\model\Payment as PayModel;
use Pay\Pay;
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Exception\PayPalConnectionException;
use PayPal\Rest\ApiContext;
use think\Exception;
use think\facade\Log;

class Payment extends Home
{

    public function index($pay_id = 0)
    {
        $info = PayModel::find($pay_id);
        if (!$info) {
            $this->redirect('member/index');
        }
        $this->assign('info', $info);
        $oid = $info['order_sn'];
        if ($info['pay_type'] == 1) {
            $product = 'Payment';
            $description = 'Payment';
            //paypal
            $clientId = config('payment.paypal.clientId');
            $clientSecret = config('payment.paypal.clientSecret');
            $this->Currency = config('payment.paypal.Currency');
            $this->accept_url = config('payment.paypal.accept_url');
            $this->cancel_url = config('payment.paypal.cancel_url');
            $this->PayPal = new ApiContext(
                new OAuthTokenCredential(
                    $clientId,
                    $clientSecret
                )
            );
            $paypal = $this->PayPal;

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
            $transaction->setAmount($amount)->setItemList($itemList)->setDescription($description)->setInvoiceNumber($oid);

            $redirectUrls = new RedirectUrls();
            $redirectUrls->setReturnUrl($this->accept_url . '?success=true')->setCancelUrl($this->cancel_url . '?success=false');

            $payment = new \PayPal\Api\Payment();
            $payment->setIntent('sale')->setPayer($payer)->setRedirectUrls($redirectUrls)->setTransactions([$transaction]);
            try {
                $payment->create($paypal);
            } catch (PayPalConnectionException $e) {
                $fp = fopen('public/logs/paypal_'.date('Y-m-d').'.txt', 'a+');
                fwrite($fp, date('H:i:s') . " Pay error： \n" . $e->getMessage() . "\n");
                $this->error('System error', url('member/index'));
                echo $e->getData();
                exit;
            }
            $approvalUrl = $payment->getApprovalLink();
            $this->redirect($approvalUrl);
        } else if($info['pay_type'] == 2) {
            //微信
            $payOrder = [
                'out_trade_no' => $info['order_sn'].time(),
                'total_fee' => $info['money'] * 100, // **单位：分**
                'body' => 'Payment',
                'spbill_create_ip' => get_client_ip(),
                'product_id'       => '1', // 订单商品 ID
                'openid' => '',
            ];
            $cfg = ['wechat' => config('payment.wechat')];
            // 实例支付对象
            $pay = new \Pay\Pay($cfg);
            try {
                $options = $pay->driver('wechat')->gateway('scan')->apply($payOrder);
                var_dump($options);
            } catch (Exception $e) {
                $this->error($e->getMessage());
            }
        } else {
            //支付宝
            $out_trade_no = $info['order_sn'].time();
            $title = 'Payment';
            $total = $info['money'];
            $cfg = config('payment.alipay');
            $config = [
                // 支付宝支付参数
                'alipay' => $cfg
            ];
            $config['alipay']['return_url'] = config('custom.site_url'); // 支付通知URL
            // 实例支付对象
            $pay = new \Pay\Pay($config);
            $payOrder = [
                'out_trade_no' => $out_trade_no .'_'. mt_rand(10000,99999),
                'subject' => $title,
                'total_amount' => $total,
            ];
            try {
                $options = $pay->driver('alipay')->gateway('web')->apply($payOrder);
                echo $options;
            } catch (Exception $e) {
                $this->error($e->getMessage());
            }
        }
        echo $info['money'];
    }

    public function back_alipay()
    {
        $alipay = Pay::alipay($this->config);

        try{
            $data = $alipay->verify(); // 是的，验签就这么简单！

            // 请自行对 trade_status 进行判断及其它逻辑进行判断，在支付宝的业务通知中，只有交易通知状态为 TRADE_SUCCESS 或 TRADE_FINISHED 时，支付宝才会认定为买家付款成功。
            // 1、商户需要验证该通知数据中的out_trade_no是否为商户系统中创建的订单号；
            // 2、判断total_amount是否确实为该订单的实际金额（即商户订单创建时的金额）；
            // 3、校验通知中的seller_id（或者seller_email) 是否为out_trade_no这笔单据的对应的操作方（有的时候，一个商户可能有多个seller_id/seller_email）；
            // 4、验证app_id是否为该商户本身。
            // 5、其它业务逻辑情况

            Log::debug('Alipay notify', $data->all());
        } catch (\Exception $e) {
            // $e->getMessage();
        }

        return $alipay->success()->send();
    }

    public function back_wechat()
    {
        $pay = Pay::wechat($this->config);

        try{
            $data = $pay->verify(); // 是的，验签就这么简单！

            Log::debug('Wechat notify', $data->all());
        } catch (\Exception $e) {
            // $e->getMessage();
        }

        return $pay->success()->send();// laravel 框架中请直接 `return $pay->success()`
    }

}
