<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2021/5/14
 * Time: 16:00
 */

namespace app\index\service;

use think\Db;

class Websocket
{
    //发送格式
    // {act: 1, msg: '提示消息', class_id: '课程序号', classin_id: '课程编号', 'class_time' => '已上课时间' }

    //课程开始
    public static $act_class_begin = 1;
    //课程暂停
    public static $act_class_pause = 2;
    //课程继续
    public static $act_class_continue = 3;
    //课程退出，离开5分钟
    public static $act_class_exit = 4;
    //课程退出，超过10分钟未进来
    public static $act_class_overtime = 6;
    //正常退出
    public static $act_class_leave = 7;
    //课程完成
    public static $act_class_success = 5;
    //只有1个人进来时
    public static $act_class_wait = 8;
    //后台消息
    public static $act_admin = 9;

    public static $msg = [
        'class_begin' => 'Chat starts',
        'class_pause' => 'Chat paused',
        'class_overtime' => 'If your partner does not enter the chatroom within 5 minutes, the chatroom will be closed',
        'class_exit'   => 'Your partner left the room. Please wait up to 5 minutes. if he/she does not come back, the chatroom will be closed.',
        'class_continue' => 'Your partner is back.',
        'class_success' => 'Chat successful.',
        'class_leave' => 'Your partner left.',
        'class_back' => 'Welcome back.',
        'class_comming' => 'Your partner entered this room.',
        'class_wait' => "If your partner does not enter the chatroom within 5 minutes, please exit the chatroom. It is likely that they will not show up. Your rating will not be affected.",
        'class_one_out' => 'Your partner is offline and not back in time. The chat will be closed.'
    ];

    //2人上线了，开始上课
    public static function class_begin($class, $time, $toids)
    {
        $msg = ['act' => self::$act_class_begin, 'msg' => self::$msg['class_begin'], 'class_id' => $class['id'], 'classin_id' => $class['classin_id'], 'class_time' => 0, 'now_time' => time() ];
        if ($class['datetime'] > $msg['now_time']) {
            $msg['msg'] = self::$msg['class_comming'];
        }
        return self::push($msg, $toids);
    }

    //再次进入，继续上课
    public static function class_continue($class, $time, $toids, $type = 0)
    {
        $m = self::$msg['class_continue'];
        if ($type) {
            $m = self::$msg['class_back'];
        }
        $msg = ['act' => self::$act_class_continue, 'msg' => $m, 'class_id' => $class['id'], 'classin_id' => $class['classin_id'], 'class_time' => $class['class_time_len'], 'now_time' => time() ];
        return self::push($msg, $toids);
    }

    //课程完成
    public static function class_success($class, $time, $toids)
    {
        $msg = ['act' => self::$act_class_success, 'msg' => self::$msg['class_success'], 'class_id' => $class['id'], 'classin_id' => $class['classin_id'], 'class_time' => $class['class_time_len'] ];
        return self::push($msg, $toids);
    }

    //课程暂停
    public static function class_pause($class, $toid)
    {
        $msg = ['act' => self::$act_class_pause, 'msg' => self::$msg['class_pause'], 'class_id' => $class['id'], 'classin_id' => $class['classin_id']];
        return self::push($msg, $toid);
    }

    //等待另一个人进来
    public static function class_wait($class, $toid)
    {
        $msg = ['act' => self::$act_class_wait, 'msg' => self::$msg['class_wait'], 'class_id' => $class['id'], 'classin_id' => $class['classin_id']];
        return self::push($msg, $toid);
    }

    //管理员消息
    public static function class_admin($msg, $uids = [])
    {
        $msg = ['act' => self::$act_admin, 'msg' => $msg];
        return self::push($msg, $uids);
    }

    //课程关闭退出
    public static function class_exit($class, $toid, $message = '')
    {
        $message = $message ? self::$msg[$message] : self::$msg['class_exit'];
        $msg = ['act' => self::$act_class_exit, 'msg' => $message, 'class_id' => $class['id'], 'classin_id' => $class['classin_id']];
        return self::push($msg, $toid);
    }

    //课程关闭退出,10分钟未进来
    public static function class_overtime($class, $toid, $message = '')
    {
        $message = $message ? self::$msg[$message] : self::$msg['class_overtime'];
        $msg = ['act' => self::$act_class_overtime, 'msg' => $message, 'class_id' => $class['id'], 'classin_id' => $class['classin_id']];
        return self::push($msg, $toid);
    }

    //正常退出
    public static function class_leave($class, $toid, $message = '')
    {
        $message = $message ? self::$msg[$message] : self::$msg['class_leave'];
        $msg = ['act' => self::$act_class_leave, 'msg' => $message, 'class_id' => $class['id'], 'classin_id' => $class['classin_id']];
        return self::push($msg, $toid);
    }

    //发送SOCKET
    public static function push($msg, $toids)
    {
        $url = config('tencentyun.websocket_send_url');
//        $msg = is_array($msg) ? json_encode($msg) : $msg;
        $class_id = $msg['class_id'] ?? 0;
        $now_time = isset($msg['now_time']) && $msg['now_time'] ? date('Y-m-d H:i:s', $msg['now_time']) : 0;
        $class_time = ($msg['class_time'] ?? 0);
        $msg = is_array($msg) ? implode('##', $msg) : $msg;
        $msg = urlencode($msg);
        if (is_array($toids)) {
            $ids = $toids;
        } else {
            $ids = explode(',', $toids);
        }
        $res = [];
        $fp = fopen('public/socket/log_'.date('Ymd').'.txt', 'a+');
        if (!$ids) {
            $url2 = str_replace(['&to={$to}', '{$content}'], ['', $msg], $url);
            $result = self::httpRequest($url2);
            fwrite($fp, date('Y-m-d H:i:s') . ' class id:' . $class_id . ',' . ' class time:' . $class_time . ',' . ' now time:' . $now_time . ',' . $url2 . "\r\n" . $result . "\r\n");
            $res[] = $result;
        } else {
            foreach ($ids as $id) {
                $url2 = str_replace(['{$to}', '{$content}'], [$id, $msg], $url);
//            echo $url2;
                $result = self::httpRequest($url2);
                if ($result == 'offline') {
                    //没发成功,加到失败记录，后面每分钟补发
                    $error = ['user_id' => $id, 'msg' => serialize(urldecode($msg)), 'class_id' => $class_id];
                    Db::name('cms_sockets')->insert($error);
                }
                fwrite($fp, date('Y-m-d H:i:s') . ' class id:' . $class_id . ',' . ' class time:' . $class_time . ',' . ' now time:' . $now_time . ',' . $url2 . "\r\n" . $result . "\r\n");
                $res[] = $result;
            }
        }
        return implode(',', $res);
    }


    /**
     * CURL请求
     * @param $url string 请求url地址
     * @param $method string 请求方法 get post
     * @param null $postfields post数据数组
     * @param array $headers 请求header信息
     * @param bool|false $debug  调试开启 默认false
     * @return mixed
     */
    public static function httpRequest($url, $method="GET", $postfields = null, $headers = array(), $debug = false, $timeout=60)
    {
        $method = strtoupper($method);
        $ci = curl_init();
        /* Curl settings */
        curl_setopt($ci, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
        curl_setopt($ci, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.2; WOW64; rv:34.0) Gecko/20100101 Firefox/34.0");
        curl_setopt($ci, CURLOPT_CONNECTTIMEOUT,$timeout); /* 在发起连接前等待的时间，如果设置为0，则无限等待 */
        curl_setopt($ci, CURLOPT_TIMEOUT, 7); /* 设置cURL允许执行的最长秒数 */
        curl_setopt($ci, CURLOPT_RETURNTRANSFER, true);
        switch ($method) {
            case "POST":
                curl_setopt($ci, CURLOPT_POST, true);
                if (!empty($postfields)) {
                    $tmpdatastr = is_array($postfields) ? http_build_query($postfields) : $postfields;
                    curl_setopt($ci, CURLOPT_POSTFIELDS, $tmpdatastr);
                }
                break;
            default:
                curl_setopt($ci, CURLOPT_CUSTOMREQUEST, $method); /* //设置请求方式 */
                break;
        }
        $ssl = preg_match('/^https:\/\//i',$url) ? TRUE : FALSE;
        curl_setopt($ci, CURLOPT_URL, $url);
        if($ssl){
            curl_setopt($ci, CURLOPT_SSL_VERIFYPEER, FALSE); // https请求 不验证证书和hosts
            curl_setopt($ci, CURLOPT_SSL_VERIFYHOST, FALSE); // 不从证书中检查SSL加密算法是否存在
        }
        //curl_setopt($ci, CURLOPT_HEADER, true); /*启用时会将头文件的信息作为数据流输出*/
        if (ini_get('open_basedir') == '' && ini_get('safe_mode' == 'Off')) {
            curl_setopt($ci, CURLOPT_FOLLOWLOCATION, 1);
        }
        curl_setopt($ci, CURLOPT_MAXREDIRS, 2);/*指定最多的HTTP重定向的数量，这个选项是和CURLOPT_FOLLOWLOCATION一起使用的*/
        curl_setopt($ci, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ci, CURLINFO_HEADER_OUT, true);
        /*curl_setopt($ci, CURLOPT_COOKIE, $Cookiestr); * *COOKIE带过去** */
        $response = curl_exec($ci);
        $requestinfo = curl_getinfo($ci);
        $http_code = curl_getinfo($ci, CURLINFO_HTTP_CODE);
        if ($debug) {
            echo "=====post data======\r\n";
            var_dump($postfields);
            echo "=====info===== \r\n";
            print_r($requestinfo);
            echo "=====response=====\r\n";
            print_r($response);
        }
        curl_close($ci);
        return $response;
        //return array($http_code, $response,$requestinfo);
    }


}