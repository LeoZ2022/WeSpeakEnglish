<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/12/29
 * Time: 11:12
 */

namespace app\index\service;

class Snappay
{

    public function webpay($data)
    {
        $date = date_create('',timezone_open("UTC"));
        $timestamp = date_format($date, 'Y-m-d H:i:s');
        $app_id = config('payment.snappay.app_id');
        $api_data = array(
            'merchant_no' => config('payment.snappay.merchant_no'),
            'trans_currency' => config('payment.snappay.trans_currency'),
            'app_id' => $app_id,
            'format' => 'JSON',
            'charset' => 'UTF-8',
            'sign_type' => 'MD5',
            'version' => '1.0',
            'timestamp' => $timestamp,
            'notify_url' => 'https://'.$_SERVER['SERVER_NAME'].url('payment/notify_snappay'),
        );
        if ($api_data['trans_currency'] == 'CAD') {
            $data['trans_amount'] = round($data['trans_amount']*1.3, 2);
        }
        $device_type = get_device_type();
        if ($device_type > 1) {
            if ($data['payment_method'] == 'ALIPAY') {
                $data["browser_type"] = "WAP";
            } else {
//                $data['method'] = 'pay.h5pay';
            }
        }
        $data['out_order_no'] .= rand(1000,9999);
        $post_data = array_merge($data, $api_data);
        $signKey = config('payment.snappay.signKey');
        $post_data_sign = snappay_sign_post_data($post_data, $signKey);
        error_log('支付: '.json_encode($post_data_sign)."\n", 3, 'public/logs/snappay_'.date('Y-m-d').'.txt');
        $url = 'https://open.snappay.ca/api/gateway';

        $options = array(
            'http' => array(
                'method'  => 'POST',
                'header'  =>  "Content-Type: application/json\r\n"."Accept: application/json\r\n",
                'content' => json_encode($post_data_sign)
            )
        );
        $context  = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        if ($result === FALSE) {
            return ['code' => 0, 'msg' => 'Payment error'];
        }

        $result = preg_replace('#&(?=[a-z_0-9]+=)#', '&amp;', $result);
        $result_json = json_decode($result, true);
        if($result_json['code'] === '0'){
            $ret = ['code' => 1, 'msg' => 'Payment successfully'];
            if ($post_data['payment_method'] == 'ALIPAY') {
                $ret['h5pay_url'] = $result_json['data'][0]['webpay_url'];
            } else {
                if ($device_type > 1) {
//                    $ret['h5pay_url'] = $result_json['data'][0]['h5pay_url'];
                    $qrcode_url = $result_json['data'][0]['qrcode_url'];
                    $codeStr = $qrcode_url;
                    $ret['codeStr'] = $codeStr;
                } else {
                    $qrcode_url = $result_json['data'][0]['qrcode_url'];
                    $codeStr = $qrcode_url;
                    $ret['codeStr'] = $codeStr;
                }
            }
            return $ret;
        } else {
            return ['code' => 0, 'msg' => $result_json['msg']];
        }
    }

}