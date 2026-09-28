<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/10/27
 * Time: 10:07
 */

namespace app\index\controller;
use app\index\model\Payment as PayModel;
use app\index\service\Paypal;
use app\index\service\Snappay;
use Pay\Pay;
use PayPal\Api\PaymentExecution;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;
use think\Exception;
use think\Log;

class Paymentc extends Home
{

    public function index($pay_id = 0)
    {
        $info = PayModel::find($pay_id);
        if (!$info) {
            $this->redirect('member/index');
        }
        $this->assign('info', $info);
        $ajax = input('ajax');
        if ($info['pay_type'] == 1) {
            $this->redirect(url('payment/index',['pay_id'=>$pay_id]));
        } else if($info['pay_type'] == 2) {
            //微信
            if ($ajax) {
                $payOrder = [
                    'out_trade_no' => $info['order_sn'],
                    'total_fee' => $info['money'] * 100, // **单位：分**
                    'body' => 'Payment',
                    'spbill_create_ip' => get_client_ip(),
                    'product_id' => '1', // 订单商品 ID
                    'openid' => '',
                ];
                $cfg = ['wechat' => config('payment.wechat')];
                // 实例支付对象
                $device_type = get_device_type();
                if ($device_type > 1) {
                    //手机
                    $gateway = 'wap';
                } else {
                    $gateway = 'scan';
                }
                $pay = new \Pay\Pay($cfg);
                try {
                    $options = $pay->driver('wechat')->gateway($gateway)->apply($payOrder);
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
            $out_trade_no = $info['order_sn'];
            $title = 'Payment';
            $total = $info['money'];
            $cfg = config('payment.alipay');
            $config = [
                // 支付宝支付参数
                'alipay' => $cfg
            ];
            if ($info['type'] == 1) {
                $url = 'https://'.$_SERVER['SERVER_NAME'].url('tutors/succeed', ['order_id' => $info['obj_id']]);
            } else {
                $url = 'https://'.$_SERVER['SERVER_NAME'].url('member/accounts');
            }
            $config['alipay']['return_url'] = $url; // 支付通知URL
            // 实例支付对象
            $pay = new \Pay\Pay($config);
            $payOrder = [
                'out_trade_no' => $out_trade_no,
                'subject' => $title,
                'total_amount' => $total,
            ];
            $device_type = get_device_type();
            if ($device_type > 1) {
                //手机
                $gateway = 'wap';
            } else {
                $gateway = 'web';
            }
            try {
                $options = $pay->driver('alipay')->gateway($gateway)->apply($payOrder);
                echo $options;
            } catch (Exception $e) {
                $this->error($e->getMessage());
            }
        }
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

    //检查支付结果
    public function chkpay($pay_id = 0)
    {
        $info = PayModel::field('pay_status')->find($pay_id);
        echo json_encode($info);
    }

    public function notify_alipay()
    {
        $config = config('payment.alipay');
        // 实例支付对象
        $pay = new \Pay\Pay($config);

        file_put_contents('notify.txt', var_export($_POST)."\r\n", FILE_APPEND);
        if ($result = $pay->driver('alipay')->gateway()->verify($_POST)) {
            file_put_contents('notify.txt', "收到来自支付宝的异步通知\r\n", FILE_APPEND);
            file_put_contents('notify.txt', "订单单号：{$_POST['out_trade_no']}\r\n", FILE_APPEND);
            file_put_contents('notify.txt', "订单金额：{$_POST['total_amount']}\r\n\r\n", FILE_APPEND);
            echo "success";
        } else {
            file_put_contents('notify.txt', "失败 ".var_dump($result)."\r\n", FILE_APPEND);
            echo "fail";
        }

    }

    public function notify_wechat()
    {
        $config = config('payment.wechat');
        $pay = new \Pay\Pay($config);
        $verify = $pay->driver('wechat')->gateway('mp')->verify(file_get_contents('php://input'));

        file_put_contents('notify.txt', var_export(file_get_contents('php://input'))."\r\n", FILE_APPEND);
        if ($verify) {
            file_put_contents('notify.txt', "收到来自微信的异步通知\r\n", FILE_APPEND);
            file_put_contents('notify.txt', "订单单号：{$verify['out_trade_no']}\r\n", FILE_APPEND);
            file_put_contents('notify.txt', "订单金额：{$verify['total_fee']}\r\n\r\n", FILE_APPEND);

            $json_obj = [];
            $json_obj['out_order_no'] = $verify['out_trade_no'];
            $json_obj['trans_currency'] = config('payment.paypal.Currency');
            $json_obj['trans_no'] = $payid;
            $json_obj['trans_amount'] = $money;
            $json_obj['trans_end_time'] = date('Y-m-d H:i:s');
            $ret = PayModel::notify_snappay($json_obj);
        } else {
            file_put_contents('notify.txt', "收到异步通知 ".var_export($verify)."\r\n", FILE_APPEND);
        }

        echo '<xml><return_code>SUCCESS</return_code><return_msg>OK</return_msg></xml>';
    }

}
