<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2021/5/10
 * Time: 20:40
 */

namespace app\api\home;

use app\api\model\Users;
use think\Controller;
use think\Exception;
use think\Request;

class Base extends Controller
{

    /**
     * 初始化方法*/
    protected function initialize()
    {
        $fp = fopen('public/api/log_'.date('Ymd').'.txt', 'a+');
        $str = ($this->request->controller()).'/'.parse_name($this->request->action())."\r\n";
        $str .= json_encode(input());
        fwrite($fp, date('Y-m-d H:i:s').' '.$str."\r\n");
    }

    public function returnJson($ret)
    {
        if (!isset($ret['msg'])) {
            $ret['msg'] = 'Success';
        }
        echo json_encode($ret);
        exit;
    }

    public function check_token()
    {
        $ret = ['code'=>401,'msg'=> 'Invalid authorization credentials'];
        //获取头部信息
        try {
            $authorization = $this->request->header('authentication');
            if (!$authorization) {
                return $this->returnJson($ret);
            }
            $user = Users::getUser($authorization);
            if ($user) {
                return $user;
            } else {
                $this->returnJson($ret);
            }
        } catch (Exception $e) {
            return $this->returnJson($ret);
        }
    }

}