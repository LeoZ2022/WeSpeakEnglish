<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2021/5/10
 * Time: 21:52
 */

namespace app\index\controller;

use app\index\service\Trtc;

class Callback extends Home
{

    public function room()
    {
        $obj = new Trtc();
        $sign = $this->request->header('Sign');
        $obj->callback($sign);
        $ret = ['code' => 0];
        echo json_encode($ret);
    }

    public function video()
    {
        $ret = ['code' => 0, 'type'=>'video'];
        echo json_encode($ret);
    }

}