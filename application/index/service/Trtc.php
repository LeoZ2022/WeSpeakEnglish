<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2021/5/10
 * Time: 22:04
 */

namespace app\index\service;

use app\api\model\Orderitems;

class Trtc
{

    public function callback($sign_chk)
    {
        $cfg = config('tencentyun.');
        $res = @file_get_contents('php://input');
        $s = hash_hmac('sha256', $res, $cfg['scert'], true);
        $sign = base64_encode($s);
//        var_dump($cfg['scert']); var_dump($res);echo $s."<br/>"; echo $sign."<br/>"; echo $sign_chk;exit;
        $this->logs('callback', $res, '');
        //逻辑处理
        if (1 || $sign_chk == $sign) {
            $data = json_decode($res, true);
            if ($data['EventGroupId'] == 1) {
                // 101 建立房间，102 解散房间，103 进入房间， 104 退出房间
                if ($data['EventType'] == 103) {
                    //创建或进入房间
                    Orderitems::intime($data['EventInfo'], $data['EventType']);
                } else if($data['EventType'] == 104) {
                    //解散或退出房间
                    Orderitems::outtime($data['EventInfo'], $data['EventType']);
                }
            }
        } else {
            $this->logs('callback', $sign."\n".$sign_chk, 'sign error');
        }
    }

    public function logs($act, $data, $result)
    {
//        var_dump($result);
        $fp = fopen('public/trtc/log_'.date('Ymd').'.txt', 'a+');
        fwrite($fp, date('Y-m-d H:i:s').' '.$act."\r\n");
        if (is_array($data)) {
            fwrite($fp, json_encode($data) . "\r\n");
        } else {
            fwrite($fp, $data . "\r\n");
        }
        fwrite($fp, $result."\r\n");
        fclose($fp);
    }


}