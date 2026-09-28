<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/12/6
 * Time: 10:03
 */

namespace app\index\controller;

class Classin extends Home
{

    public function callback()
    {
        $obj = new \app\index\service\Classin();
        $ret = $obj->callback();
        echo json_encode($ret);
    }

}