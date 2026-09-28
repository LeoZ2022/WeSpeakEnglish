<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/10/27
 * Time: 10:07
 */

namespace app\index\controller;
use app\index\model\Order;
use app\index\model\Payment as PayModel;
use app\index\service\Paypal;
use app\index\service\Snappay;
use PayPal\Api\PaymentExecution;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;
use think\Exception;

class Payment extends Home
{

    public function index($pay_id = 0)
    {
        $info = PayModel::where('id', $pay_id)->find();
        if (!$info) {
            $this->redirect('member/index');
        }
        $this->assign('info', $info);
        $device_type = get_device_type();
        $this->assign('device_type', $device_type);
        $ajax = input('ajax');
        if ($info['type'] == 1) {
            $url = 'https://'.$_SERVER['HTTP_HOST'].url('tutors/succeed', ['order_id' => $info['obj_id']]);
        } else {
            $url = 'https://'.$_SERVER['HTTP_HOST'].url('member/accounts');
        }
        if ($info['pay_type'] == 1) {
            $obj = new Paypal();
            $ret = $obj->create($info);
            if ($ret['code'] == 1) {
                $this->redirect($ret['approvalUrl']);
            } else {
                echo $ret['msg'];exit;
                $this->error($ret['msg']);
            }
        } else if($info['pay_type'] == 2) {
            //微信
            if ($ajax){
                $data = array(
                    'payment_method' => 'WECHATPAY',
                    'out_order_no' => $info['order_sn'],
                    'trans_amount' => $info['money'],
                    'description' => 'Payment',
                    'method' => 'pay.qrcodepay'
                );
                $obj = new Snappay();
                $ret = $obj->webpay($data);
                echo json_encode($ret);
            } else {
                return $this->fetch();
            }
        } else if($info['pay_type'] == 4) {
            //微信，国内

            if ($device_type > 1) {
                //手机
                $gateway = 'wap';
                if (is_wechat_browser()) {
//                    $gateway = 'mp';
                }
            } else {
                $gateway = 'scan';
            }
            if ($ajax || $gateway == 'wap' ) {
                $total = round($info['money'] * config('cfg_rmb_rate'), 2) * 100;
                $payOrder = [
                    'out_trade_no' => $info['order_sn'].rand(1000,9999),
                    'total_fee' => $total, // **单位：分**
                    'body' => 'Payment',
                    'spbill_create_ip' => get_client_ip(),
                    'product_id' => '1', // 订单商品 ID
                    'openid' => '',
                ];
                $cfg = ['wechat' => config('payment.wechat')];
                if ($gateway == 'wap') {
                    $cfg['wechat']['return_url'] = $url;
                }
                // 实例支付对象
                $pay = new \Pay\Pay($cfg);
                try {
                    $options = $pay->driver('wechat')->gateway($gateway)->apply($payOrder);
                    if ($gateway == 'wap') {
//                        echo $options."<br/><br/><br/>";echo '<a href="'.$options.'">点击</a>"';                        exit;
                        echo "<script>location.href='".$options."';</script>";exit;
                        header("location:".$options);
                    }
                    $ret = ['code' => 1, 'codeStr' => $options];
                } catch (Exception $e) {
                    $ret = ['code'=>0, 'msg'=>$e->getMessage()];
                }
                echo json_encode($ret);
                exit;
            } else {
                return $this->fetch();
            }
        } else {
            //支付宝
            if (1 || $ajax){
                $data = array(
                    'payment_method' => 'ALIPAY',
                    'out_order_no' => $info['order_sn'],
                    'trans_amount' => $info['money'],
                    'description' => 'Payment',
                    'method' => 'pay.webpay'
                );
                $data['return_url'] = $url;
                $obj = new Snappay();
                $ret = $obj->webpay($data);
//                echo str_replace('&amp;', '&', $ret['h5pay_url']); exit;
                $this->redirect(str_replace('&amp;', '&', $ret['h5pay_url']));
                echo json_encode($ret);
            } else {
                return $this->fetch();
            }
        }
    }

    /**
     * paypal回调
     */
    public function callback()
    {
        $clientId = config('payment.paypal.clientId');
        $clientSecret = config('payment.paypal.clientSecret');
        $this->Currency = config('payment.paypal.Currency');
        $this->accept_url = config('payment.paypal.accept_url');
        $this->cancel_url = config('payment.paypal.cancel_url');
        $this->notify_url = config('payment.paypal.notify_url');
        $apiContext  = new ApiContext(
            new OAuthTokenCredential(
                $clientId,
                $clientSecret
            )
        );

        $apiContext ->setConfig(
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

        // 修改订单状态
        $success = input('success','','trim,htmlspecialchars');

        $paymentId = input('paymentId','','trim,htmlspecialchars');
        $PayerID = input('PayerID','','trim,htmlspecialchars');

        $fp = fopen('public/logs/log_'.date('Y-m-d').'.txt', 'a+');
        if (!isset($success, $paymentId, $PayerID)) {
            $this->redirect('member/index');
//            echo 'Failure to pay。';
            exit();
        }

        if ($success == 'false') {
            $this->redirect('member/index');
//            echo('Paypal cancel');
        }

        $payment = \PayPal\Api\Payment::get($paymentId, $apiContext);

        $execute = new PaymentExecution();
        $execute->setPayerId($PayerID);
        try {
            $payment->execute($execute, $apiContext);
        } catch (Exception $e) {
            fwrite($fp, date('H:i:s') . " Error \n" . 'Pay ID【' . $paymentId . '】,Payer ID【' . $PayerID . '】' . "\n");
            fwrite($fp, date('H:i:s') . " Error \n" . $e->getMessage() . "\n");
            $this->redirect('member/index');
//            exit();
        }

        // 到这里就支付成功了，可以修改订单状态，需要自己传参数，可以在成功回调地址后面加
        // code....
//        print_r($_GET);echo "<br/>";
        $trans = $payment->getTransactions();
        $oid = $trans[0]->getInvoiceNumber();
        $amount = $trans[0]->getAmount();
        $money = $amount->total;

        $transBase = $trans[0]->getRelatedResources();
        $payid = $transBase[0]->getSale()->getId();

        $json_obj = [];
        $json_obj['out_order_no'] = $oid;
        $json_obj['trans_currency'] = config('payment.paypal.Currency');
        $json_obj['trans_no'] = $payid;
        $json_obj['trans_amount'] = $money;
        $json_obj['trans_end_time'] = date('Y-m-d H:i:s');
        $ret = PayModel::notify_snappay($json_obj);
        if ($ret) {
            echo 'success';
            $this->redirect($ret);
        } else {
//            $this->error($ret['msg'], 'member/index');
            echo 'error';
        }
    }

    /**
     * paypal 通知
     */
    // public function notify()
//     {
        
// 		$fp = fopen('public/logs/log_'.date('Y-m-d').'.txt', 'a+');

//         //记录支付回调信息
//         if(!empty($_POST)){
//             $notify_str = "支付回调信息:\r\n";
//             $notify_str .= http_build_query($_POST);
//             $this->log_result($notify_str,"paypal");
//         } else {
//             echo 'error';exit;
//         }

//         //ipn验证
//         $data = $_POST;
//         $url = config('payment.paypal.ipnpb_url');//支付异步验证地址

//         $res = $this->verified($data, $url);
//         //记录支付ipn验证回调信息
//         $this->log_result($res,'paypal');

//         if (strcmp ($res, "VERIFIED") == 0) {
//             if ($data['payment_status'] == 'Completed' || $data['payment_status'] == 'Pending') {
//                 //付款完成，这里修改订单状态
//                 $json_obj = [];
//                 $json_obj['out_order_no'] = $data['invoice'];
//                 $json_obj['trans_currency'] = $data['mc_currency'];
//                 $json_obj['trans_no'] = $data['txn_id'];
//                 $json_obj['trans_amount'] = $data['payment_gross'];
//                 $ret = PayModel::notify_snappay($json_obj);
//                 if($ret){
//                     $this->log_result('update order result successfully !','paypal');
//                 } else {
//                     $this->log_result('update order result fail !','paypal');
//                 }
//                 echo 'success';exit;
//             }
//         } elseif (strcmp($res, "INVALID") == 0) {
//             //未通过认证，有可能是编码错误或非法的 POST 信息
//             echo 'fail';exit;
//         }
//         echo 'fail';
// 		echo 'OK';
// exit;
//     }
public function notify()
{
    // Always acknowledge PayPal
    http_response_code(200);

    // Ensure POST only
    if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST)) {
        echo 'OK';
        exit;
    }

    // Ensure log directory exists
    $logDir = ROOT_PATH . 'public/logs/paypal/';
    if (!is_dir($logDir)) {
        mkdir($logDir, 0777, true);
    }

    // Log raw IPN
    file_put_contents(
        $logDir . date('Y-m-d') . '.log',
        date('Y-m-d H:i:s') . " RAW:\n" . http_build_query($_POST) . "\n\n",
        FILE_APPEND
    );

    // Build verification request
    $req = 'cmd=_notify-validate';
    foreach ($_POST as $key => $value) {
        $req .= '&' . $key . '=' . urlencode($value);
    }

    // Send back to PayPal for verification
    $ch = curl_init('https://ipnpb.paypal.com/cgi-bin/webscr');
    curl_setopt_array($ch, [
        CURLOPT_POST            => true,
        CURLOPT_POSTFIELDS      => $req,
        CURLOPT_RETURNTRANSFER  => true,
        CURLOPT_SSL_VERIFYPEER  => true,
        CURLOPT_SSL_VERIFYHOST  => 2,
        CURLOPT_HTTPHEADER      => ['Connection: Close']
    ]);

    $res = curl_exec($ch);
    curl_close($ch);

    // Log verification result
    file_put_contents(
        $logDir . date('Y-m-d') . '.log',
        "VERIFY RESPONSE: {$res}\n-------------------------------------------------\n",
        FILE_APPEND
    );

    if ($res === 'VERIFIED') {
        // Process successful payment
        if (
            $_POST['payment_status'] === 'Completed' ||
            $_POST['payment_status'] === 'Pending'
        ) {
            $data = [
                'out_order_no'   => $_POST['invoice'] ?? '',
                'trans_currency'=> $_POST['mc_currency'] ?? '',
                'trans_no'       => $_POST['txn_id'] ?? '',
                'trans_amount'  => $_POST['payment_gross'] ?? 0,
            ];

            PayModel::notify_snappay($data);
        }
    }

    echo 'OK';
    exit;
}

/**
 * PayPal Webhook endpoint
 * Receives events like PAYMENT.SALE.COMPLETED
 */
public function webhook() //add on 20260113 for future paypal webhook notification.
{
    // 1️⃣ Get raw POST body from PayPal
    $body = file_get_contents('php://input');

    // Log incoming webhook for debugging
    $logFile = ROOT_PATH . 'public/logs/paypal_webhook_' . date('Y-m-d') . '.log';
    file_put_contents($logFile, date('Y-m-d H:i:s') . " Incoming webhook: " . $body . "\n", FILE_APPEND);

    // 2️⃣ Verify webhook signature
    $headers = getallheaders(); // PayPal sends signature in headers
    $paypalWebhookId = config('payment.paypal.webhook_id'); // Your Webhook ID from PayPal dashboard

    $apiContext = new \PayPal\Rest\ApiContext(
        new \PayPal\Auth\OAuthTokenCredential(
            config('payment.paypal.clientId'),
            config('payment.paypal.clientSecret')
        )
    );

    // Validate webhook
    $signatureVerification = new \PayPal\Api\WebhookEvent();
    try {
        $event = $signatureVerification->fromJson($body);

        $verification = \PayPal\Api\VerifyWebhookSignature::create(
            [
                'auth_algo'         => $headers['PAYPAL-AUTH-ALGO'] ?? '',
                'cert_url'          => $headers['PAYPAL-CERT-URL'] ?? '',
                'transmission_id'   => $headers['PAYPAL-TRANSMISSION-ID'] ?? '',
                'transmission_sig'  => $headers['PAYPAL-TRANSMISSION-SIG'] ?? '',
                'transmission_time' => $headers['PAYPAL-TRANSMISSION-TIME'] ?? '',
                'webhook_id'        => $paypalWebhookId,
                'webhook_event'     => $event->toJSON(),
            ],
            $apiContext
        );

        if ($verification->getVerificationStatus() !== 'SUCCESS') {
            file_put_contents($logFile, date('Y-m-d H:i:s') . " Webhook signature invalid.\n", FILE_APPEND);
            http_response_code(400);
            echo 'Invalid signature';
            return;
        }
    } catch (\Exception $e) {
        file_put_contents($logFile, date('Y-m-d H:i:s') . " Exception: " . $e->getMessage() . "\n", FILE_APPEND);
        http_response_code(500);
        echo 'Webhook verification failed';
        return;
    }

    // 3️⃣ Process the event
    $eventData = json_decode($body, true);
    if (isset($eventData['event_type'])) {
        switch ($eventData['event_type']) {
            case 'PAYMENT.SALE.COMPLETED':
                $sale = $eventData['resource'];

                // Map data to your order table
                $json_obj = [];
                $json_obj['out_order_no']   = $sale['invoice_number'] ?? '';
                $json_obj['trans_currency'] = $sale['amount']['currency'] ?? 'USD';
                $json_obj['trans_no']       = $sale['id'] ?? '';
                $json_obj['trans_amount']   = $sale['amount']['total'] ?? 0;
                $json_obj['trans_end_time'] = $sale['create_time'] ?? date('Y-m-d H:i:s');

                // Call your existing order update method (idempotent)
                $ret = \app\index\model\Payment::notify_snappay($json_obj);

                if ($ret) {
                    file_put_contents($logFile, date('Y-m-d H:i:s') . " Order updated successfully: " . $json_obj['out_order_no'] . "\n", FILE_APPEND);
                } else {
                    file_put_contents($logFile, date('Y-m-d H:i:s') . " Failed to update order: " . $json_obj['out_order_no'] . "\n", FILE_APPEND);
                }
                break;

            default:
                file_put_contents($logFile, date('Y-m-d H:i:s') . " Unhandled event: " . $eventData['event_type'] . "\n", FILE_APPEND);
                break;
        }
    }

    // 4️⃣ Respond with 200 OK to PayPal
    http_response_code(200);
    echo 'OK';
}

    public function log_result($msg='',$type='normal')
    {
        $dir = "./public/logs/".$type."/";
        if(!is_dir($dir)){
            mkdir($dir,0777);
        }
        $dir .= date('Ym')."/";
        $file = $dir.date('d').".log";
        if(!is_dir($dir)){
            mkdir($dir,0777);
        }
        file_put_contents($file,date('Y-m-d H:i:s')."\r\n".$msg."\r\n--------------------------------------------------------------\r\n", FILE_APPEND);
    }

    public function cancel()
    {
        $this->redirect('/');
    }

    //snappay支付回调
    public function notify_snappay()
    {
        $signKey = config('payment.snappay.signKey');
        $json_str = file_get_contents('php://input');
        error_log('json_str: '.$json_str."\n", 3, 'public/logs/snappay_'.date('Y-m-d').'.txt');

        $json_obj = json_decode($json_str, true);

        # Merge json object into request object
//        $_REQUEST = array_merge($_REQUEST, $json_obj);
        $_REQUEST = $json_obj;

        if(snappay_sign_verify($_REQUEST, $signKey)){
            //do business logic here, such as notify business system the transaction has finished.
            if($json_obj['trans_status'] === 'SUCCESS'){
                $url = PayModel::notify_snappay($json_obj);
                //must only show 'SUCCESS', so that SNAPPAY server will no longer re-send notification
                $post_data = array(
                    'code' => '0',
                    'msg' => 'SUCCESS',
                    'sign_type' => 'MD5'
                );
                $post_data_sign = snappay_sign_post_data($post_data, $signKey);
                echo json_encode( $post_data_sign );
//                $this->redirect($url);
                error_log('notify_snappay: successfully'."\n", 3, 'public/logs/snappay_'.date('Y-m-d').'.txt');
            }
        } else {
            echo 'illegal sign';
            error_log('notify_snappay: illegal sign'."\n", 3, 'public/logs/snappay_'.date('Y-m-d').'.txt');
//            $this->error('illegal sign');
        }
    }

    //检查支付结果
    public function chkpay($pay_id = 0)
    {
        $info = PayModel::field('pay_status')->find($pay_id);
        echo json_encode($info);
    }

    public function verified($data, $url = ''){
        $req = 'cmd=_notify-validate';
        if(function_exists('get_magic_quotes_gpc')) $get_magic_quotes_exists = true;
        foreach ($data as $key => $value) {
            if($get_magic_quotes_exists == true && get_magic_quotes_gpc() == 1){
                $value = urlencode(stripslashes($value));
            }else{
                $value = urlencode($value);
            }
            $req.= "&$key=$value";
        }
        $ch = curl_init();
//        curl_setopt($ch, CURLOPT_URL, $url);
//        curl_setopt($ch, CURLOPT_POST, true);
//        curl_setopt($ch, CURLOPT_POSTFIELDS, $req);

        curl_setopt($ch, CURLOPT_URL, $url.'?'.$req);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials");
        $res = curl_exec($ch);
        curl_close($ch);
//        echo $url.'?'.$req; var_dump($res);

        return $res;
    }

    public function notify_wechat()
    {
        $cfg = ['wechat' => config('payment.wechat')];
        $pay = new \Pay\Pay($cfg);
        file_put_contents('notify.txt', date('Y-m-d H:i:s').' '.file_get_contents('php://input')."\r\n", FILE_APPEND);
        $verify = $pay->driver('wechat')->gateway('mp')->verify(file_get_contents('php://input'));
        if ($verify) {
            file_put_contents('notify.txt', "收到来自微信的异步通知\r\n", FILE_APPEND);
            file_put_contents('notify.txt', "订单单号：{$verify['out_trade_no']}\r\n", FILE_APPEND);
            file_put_contents('notify.txt', "订单金额：{$verify['total_fee']}\r\n", FILE_APPEND);
            file_put_contents('notify.txt', "流水号：{$verify['transaction_id']}\r\n", FILE_APPEND);

            $json_obj = [];
            $json_obj['out_order_no'] = $verify['out_trade_no'];
            $json_obj['trans_currency'] = config('payment.paypal.Currency');
            $json_obj['trans_no'] = $verify['transaction_id'];
            $json_obj['trans_amount'] = round($verify['total_fee'] / config('cfg_rmb_rate') / 100, 2);
            $json_obj['trans_end_time'] = date('Y-m-d H:i:s');
            $ret = PayModel::notify_snappay($json_obj);
        } else {
            file_put_contents('notify.txt', "收到异步通知 ".var_export($verify)."\r\n", FILE_APPEND);
        }

        echo '<xml><return_code>SUCCESS</return_code><return_msg>OK</return_msg></xml>';
    }

    public function testnotify()
    {
        http_response_code(200);
		$verify = ['out_trade_no' => '2021013011190711554284', 'total_fee' => 1, 'transaction_id' => 12341234];
        $json_obj = [];
        $json_obj['out_order_no'] = $verify['out_trade_no'];
        $json_obj['trans_currency'] = config('payment.paypal.Currency');
        $json_obj['trans_no'] = $verify['transaction_id'];
        $json_obj['trans_amount'] = $verify['total_fee'] / 100;
        $json_obj['trans_end_time'] = date('Y-m-d H:i:s');
        print_r($json_obj);
        $ret = PayModel::notify_snappay($json_obj);
        var_dump($ret);
		echo 'OK';
		exit;
    }

    //没支付显示
    public function payagain($order_id = 0)
    {
        $order = Order::find($order_id);
        if (!$order) {
            $this->redirect('/');
        }
        if ($order['pay_time']) {
            $this->redirect('member/index');
        }
        $this->assign('order', $order);
        $payment = PayModel::where(['obj_id' => $order_id, 'type' => 1])->find();
        $this->assign('pay_id', $payment['id']);
        $this->assign('pay_info', $payment);
        return $this->fetch();
    }



}
