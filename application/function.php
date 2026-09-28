<?php
// +----------------------------------------------------------------------
// +----------------------------------------------------------------------

// 为方便系统核心升级，二次开发中需要用到的公共函数请写在这个文件，不要去修改common.php文件

//snappay 支付 开始
function snappay_sign_post_data($post_data, $signKey)
{
    $sign_type = 'MD5';
    if (isset($post_data['sign_type'])) {
        $sign_type = $post_data['sign_type'];
    }

    $para_filter = snappay_paraFilter($post_data);

    $para_sort = snappay_argSort($para_filter);

    $prestr = snappay_createLinkstring($para_sort);

    $mysign = snappay_md5Sign($prestr, $signKey);

    $para_sort['sign'] = $mysign;
    $para_sort['sign_type'] = $sign_type;

    return $para_sort;
}

function snappay_sign_verify($post_data, $signKey)
{
    if (!isset($post_data) || !isset($post_data['sign'])) {
        return false;
    }

    $sign = $post_data['sign'];

    $para_filter = snappay_paraFilter($post_data);

    $para_sort = snappay_argSort($para_filter);

    $prestr = snappay_createLinkstring($para_sort);

    $mysign = snappay_md5Sign($prestr, $signKey);

    if ($sign === $mysign) {
        return true;
    } else {
        return false;
    }
}

function snappay_paraFilter($para)
{
    $para_filter = array();
    while (list ($key, $val) = snappay_myEach($para)) {
        if ($key == "sign" || $key == "sign_type" || $val == "") continue;
        else    $para_filter[$key] = $para[$key];
    }
    return $para_filter;
}

function snappay_argSort($para)
{
    ksort($para);
    reset($para);
    return $para;
}

function snappay_createLinkstring($para)
{
    $arg = "";
    while (list ($key, $val) = snappay_myEach($para)) {
        $arg .= $key . "=" . $val . "&";
    }
    $arg = substr($arg, 0, strlen($arg) - 1);

    if (get_magic_quotes_gpc()) {
        $arg = stripslashes($arg);
    }

    return $arg;
}

function snappay_md5Sign($prestr, $key)
{
    $prestr = $prestr . $key;
    return md5($prestr);
}

function snappay_myEach(&$arr)
{
    $key = key($arr);
    $result = ($key === null) ? false : [$key, current($arr), 'key' => $key, 'value' => current($arr)];
    next($arr);
    return $result;
}

//snappay 支付 结束

function get_language()
{
    $lang = session('?lang') ? session('lang') : '';
    if (!$lang) {
        $china = check_country();
        if ($china == 1) {
            $lang = 'cn';
        } else {
            $lang = 'en';
        }
    }
    session('lang', $lang);
}

//是否中国IP
function check_country()
{
    $ip = get_client_ip();
//        $ip = '223.155.4.1';
//        $ip = '110.54.155.237';
    $has = \think\Db::name('cms_ips')->where("INET_ATON('$ip') BETWEEN INET_ATON(`start`) AND INET_ATON(`end`)")->find();
    if ($has) {
        $china = 1;
    } else {
        $china = 0;
    }
    return $china;
}

//获取当前时间戳-0时区
function get_time_gmt()
{
    $gmdate = gmdate('Y-m-d H:i:s');
    $time = strtotime($gmdate);
//    echo '0时区：'.$gmdate."<br/>";
    return $time;
}

/**
 * 日期转服务器时区的时间戳
 * date：日期
 * location：时区
 */
function date_to_time($date, $location = [])
{
    $time = strtotime($date);
    if ($location['timezone'] == config('custom.cfg_default_timezone')) {
        return $time;
    }
    $time = strtotime($date.' '.$location['code']);
    return $time;
    date_default_timezone_set($location['code']);
    $xia = date('I', $time);
    date_default_timezone_set(config('custom.cfg_default_timezone_name'));
    $timezone_cfg = config('custom.server_imezone');
    $timezone = $location['timezone'];
    $time = strtotime($date) - $timezone * 3600 + 3600 * $timezone_cfg;
//    var_dump($xia);echo strtotime($date); var_dump($time); exit;
    if ($xia) {
        $time -= 3600;
    }
    return $time;
}

/**
 * 日期转0时区的时间戳
 * date：日期
 * location：时区
 */
function date_to_time0($date, $location = [])
{
    $time = date_to_time($date, $location);
    $timezone = config('custom.server_imezone');
    $time = $time - $timezone * 3600;
    return $time;
}

//时间戳转对应时区的日期
function time_to_date($time, $location = [], $format = 'Y-m-d H:i:s')
{
    $default_timezone = config('custom.cfg_default_timezone');
    if ($location['timezone'] == $default_timezone) {
        return date($format, $time);
    }
    date_default_timezone_set($location['code']);
    $date = date($format, $time);
    date_default_timezone_set(config('custom.cfg_default_timezone_name'));
    return $date;
}

/**
 * 24小时内 GMT到本地时间的转换
 * @param string $time
 * @param int $timezone 时区
 * @param bool $dst 夏令时
 */
function gmt_to_local($time = '', $timezone = 0, $dst = FALSE)
{
    //JavaScript设置Cookie,PHP取值
    if ($time == '') {
        return time();
    }
    //时间处理
    $time += $timezone * 3600;

    //是否为夏令时
    if (isset($_COOKIE["inDaylightTime"]) && $_COOKIE["inDaylightTime"] == 1) {
        $dst = TRUE;
    }
    if ($dst == TRUE) {
        $time += 3600;
    }
    return $time;
//    return date("H:i",$time);
}

//获取会员所在的时区
function get_user_timezone()
{
    $user_id = session('user_id') ?? 0;
    if ($user_id) {
        $user = \app\index\model\Users::find($user_id);
        $location = \app\index\model\Location::where('id', $user['location'])->find();
    }
    if (!isset($location) || !$location) {
        $location = \app\index\model\Location::where('id', config('cfg_default_location'))->find();
    }
    return $location->toArray();
}

//curl POST请求
function curl($url, $post_data = '')
{
    //发送post请求
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_URL, $url);
    if ($post_data) {
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
    }
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $result = curl_exec($ch);
    return $result;
}

/**
 * 获取配置参数值
 * @param $key  系统参数KEY
 * @param $no   第几个
 */
function get_sys_para($key, $no)
{
    $arr = config($key);
    return $arr[$no] ?? '-';
}

//生成邀请码
function get_invitation_code()
{
    for ($i = 0; $i < 10; $i++) {
        $code = generate_rand_str(9, 3);
        $has = \app\index\model\Users::where('invitation_code', $code)->find();
        if (!$has) {
            return $code;
        }
    }
    return time();
}

if (!function_exists('generate_rand_str')) {
    /**
     * 生成随机字符串
     * @param int $length 生成长度
     * @param int $type 生成类型：0-小写字母+数字，1-小写字母，2-大写字母，3-数字，4-小写+大写字母，5-小写+大写+数字
     * @return string
     */
    function generate_rand_str($length = 8, $type = 0)
    {
        $a = 'abcdefghijkmnpqrstuvwxyz';
        $A = 'ABCDEFGHJKLMNPQRSTUVWXYZ';
        $n = '0123456789';

        switch ($type) {
            case 1:
                $chars = $a;
                break;
            case 2:
                $chars = $A;
                break;
            case 3:
                $chars = $n;
                break;
            case 4:
                $chars = $a . $A;
                break;
            case 5:
                $chars = $a . $A . $n;
                break;
            default:
                $chars = $a . $n;
        }

        $str = '';
        for ($i = 0; $i < $length; $i++) {
            $str .= $chars[mt_rand(0, strlen($chars) - 1)];
        }
        return $str;
    }
}

//获取订单编号
function create_order_sn()
{
    $i = 0;
    while ($i < 10) {
        $sn = date('YmdHis') . generate_rand_str(4, 3);
        $has = \app\index\model\Order::where('order_sn', $sn)->find();
        if (!$has) {
            return $sn;
        }
        $i++;
    }
}

//获取支付订单编号
function get_pay_sn()
{
    while (1) {
        $sn = date('YmdHis') . generate_rand_str(4, 3);
        $has = \app\index\model\Payment::where('order_sn', $sn)->find();
        if (!$has) {
            return $sn;
        }
    }
}

// 发送邮件
//hostinger .net  in funciton.php
function send_email($receive_email, $mail_subject, $mail_content, $receive_name = '', $reply_email = '', $reply_name = '')
{
    $mail = new \PHPMailer\PHPMailer\PHPMailer();
    $mail->IsSMTP();
    $mail->Host = config('custom.MAIL_HOST_ADDRESS');
    $mail->SMTPAuth = TRUE;
    $mail->Username = config('custom.MAIL_USERNAME');
    $mail->Password = config('custom.MAIL_PASSWORD');

    $mail->From = config('custom.MAIL_FROM_EMAIL');
    $mail->FromName = config('custom.MAIL_FROM_NAME');

    $mail->AddAddress($receive_email, $receive_name);
    $mail->AddReplyTo($reply_email, $reply_name);
    $mail->CharSet = "utf-8";
    $mail->Encoding = "base64";
    $mail->IsHTML(TRUE);
    $mail->Subject = "=?UTF-8?B?" . base64_encode($mail_subject) . "?=";
    $mail->Body = $mail_content;
    $mail->SMTPDebug = 0;
    $mail->SMTPSecure = "SSL/TLS";
    // $mail->Port = 465; // for QQ 
     $mail->Port = 587; //for outlook gmail net speakingsite.com

    $mail->SMTPOptions = array(
       'ssl' => array(
           'verify_peer' => false,
           'verify_peer_name' => false,
           'allow_self_signed' => true
       )
    );

    $send_status = array();

    if (!$mail->Send()) {
        $send_status['code'] = 1;
        $send_status['info'] = $mail->ErrorInfo;
    } else {
        $send_status['code'] = 0;
        $send_status['info'] = 'success';

        \app\index\model\Email::addsend();
    }

    return $send_status;
}
function send_email2($receive_email, $mail_subject, $mail_content, $receive_name = '', $reply_email = '', $reply_name = '')
{
    $mail = new \PHPMailer\PHPMailer\PHPMailer();
    $mail->IsSMTP();
    $mail->Host = config('custom.MAIL_HOST_ADDRESS2');
    $mail->SMTPAuth = TRUE;
    $mail->Username = config('custom.MAIL_USERNAME2');
    $mail->Password = config('custom.MAIL_PASSWORD2');

    $mail->From = config('custom.MAIL_FROM_EMAIL2');
    $mail->FromName = 'WeSpeakEnglish';
//    $mail->FromName = config('custom.MAIL_FROM_NAME2');
    $mail->AddAddress($receive_email, $receive_name);
    //$mail->AddAddress("ellen@example.com");
    $mail->AddReplyTo($reply_email, $reply_name);
    $mail->CharSet = "utf-8";
    $mail->Encoding = "base64";
    $mail->IsHTML(TRUE);
    $mail->Subject = "=?UTF-8?B?" . base64_encode($mail_subject) . "?=";
    $mail->Body = $mail_content;
    $mail->SMTPDebug = 0;
    $mail->SMTPSecure = "STARTTLS";
    // $mail->Port = 465; // for QQ 
     $mail->Port = 587; //for outlook gmail net

    $mail->SMTPOptions = array(
       'ssl' => array(
           'verify_peer' => false,
           'verify_peer_name' => false,
           'allow_self_signed' => true
       )
    );
//      if ($mail->Host == 'smtp.163.com') {
//          $mail->SMTPSecure = "TLS/STARTTLS";
//          $mail->Port = 25;
//         $mail->SMTPAuth = true;
//    }
//    if ($mail->Host == 'smtp.gmail.com') {
 //       $mail->SMTPSecure = "STARTTLS";
//        $mail->Port = 587;
//        $mail->SMTPAuth = true;
    $send_status = array();

    if (!$mail->Send()) {
        $send_status['code'] = 1;
        $send_status['info'] = $mail->ErrorInfo;
    } else {
        $send_status['code'] = 0;
        $send_status['info'] = 'success';

        \app\index\model\Email::addsend();
    }

    return $send_status;
}

//hostinger speakingsite  in funciton.php
function send_email3($receive_email, $mail_subject, $mail_content, $receive_name = '', $reply_email = '', $reply_name = '')
{
    $mail = new \PHPMailer\PHPMailer\PHPMailer();
    $mail->IsSMTP();
    $mail->Host = config('custom.MAIL_HOST_ADDRESS3');
    $mail->SMTPAuth = TRUE;
    $mail->Username = config('custom.MAIL_USERNAME3');
    $mail->Password = config('custom.MAIL_PASSWORD3');

    $mail->From = config('custom.MAIL_FROM_EMAIL3');
    $mail->FromName = 'Speakingsite notification';

    $mail->AddAddress($receive_email, $receive_name);
    $mail->AddReplyTo($reply_email, $reply_name);
    $mail->CharSet = "utf-8";
    $mail->Encoding = "base64";
    $mail->IsHTML(TRUE);
    $mail->Subject = "=?UTF-8?B?" . base64_encode($mail_subject) . "?=";
    $mail->Body = $mail_content;
    $mail->SMTPDebug = 0;
    $mail->SMTPSecure = "SSL/TLS";
    // $mail->Port = 465; // for QQ 
     $mail->Port = 587; //for outlook gmail net speakingsite.com

    $mail->SMTPOptions = array(
       'ssl' => array(
           'verify_peer' => false,
           'verify_peer_name' => false,
           'allow_self_signed' => true
       )
    );

    $send_status = array();

    if (!$mail->Send()) {
        $send_status['code'] = 1;
        $send_status['info'] = $mail->ErrorInfo;
    } else {
        $send_status['code'] = 0;
        $send_status['info'] = 'success';

        \app\index\model\Email::addsend();
    }

    return $send_status;
}

//hostinger noreply@wespeakenglish.net  in funciton.php 
function send_email4($receive_email, $mail_subject, $mail_content, $receive_name = '', $reply_email = '', $reply_name = '')
{
    $mail = new \PHPMailer\PHPMailer\PHPMailer();
    $mail->IsSMTP();
    $mail->Host = config('custom.MAIL_HOST_ADDRESS4');
    $mail->SMTPAuth = TRUE;
    $mail->Username = config('custom.MAIL_USERNAME4');
    $mail->Password = config('custom.MAIL_PASSWORD4');

    $mail->From = config('custom.MAIL_FROM_EMAIL4');
    $mail->FromName = config('custom.MAIL_FROM_NAME');

    $mail->AddAddress($receive_email, $receive_name);
    $mail->AddReplyTo($reply_email, $reply_name);
    $mail->CharSet = "utf-8";
    $mail->Encoding = "base64";
    $mail->IsHTML(TRUE);
    $mail->Subject = "=?UTF-8?B?" . base64_encode($mail_subject) . "?=";
    $mail->Body = $mail_content;
    $mail->SMTPDebug = 0;
    $mail->SMTPSecure = "SSL/TLS";
    // $mail->Port = 465; // for QQ 
     $mail->Port = 587; //for outlook gmail net speakingsite.com

    $mail->SMTPOptions = array(
       'ssl' => array(
           'verify_peer' => false,
           'verify_peer_name' => false,
           'allow_self_signed' => true
       )
    );

    $send_status = array();

    if (!$mail->Send()) {
        $send_status['code'] = 1;
        $send_status['info'] = $mail->ErrorInfo;
    } else {
        $send_status['code'] = 0;
        $send_status['info'] = 'success';

        \app\index\model\Email::addsend();
    }

    return $send_status;
}

//hostinger leo@wespeakenglish.net  in funciton.php 
function send_email_leo($receive_email, $mail_subject, $mail_content, $receive_name = '', $reply_email = '', $reply_name = '')
{
    $mail = new \PHPMailer\PHPMailer\PHPMailer();
    $mail->IsSMTP();
    $mail->Host = config('custom.MAIL_HOST_ADDRESS5');
    $mail->SMTPAuth = TRUE;
    $mail->Username = config('custom.MAIL_USERNAME5');
    $mail->Password = config('custom.MAIL_PASSWORD5');

    $mail->From = config('custom.MAIL_FROM_EMAIL5');
    $mail->FromName = config('custom.MAIL_FROM_NAME');

    $mail->AddAddress($receive_email, $receive_name);
    $mail->AddReplyTo($reply_email, $reply_name);
    $mail->CharSet = "utf-8";
    $mail->Encoding = "base64";
    $mail->IsHTML(TRUE);
    $mail->Subject = "=?UTF-8?B?" . base64_encode($mail_subject) . "?=";
    $mail->Body = $mail_content;
    $mail->SMTPDebug = 0;
    $mail->SMTPSecure = "SSL/TLS";
    // $mail->Port = 465; // for QQ 
     $mail->Port = 587; //for outlook gmail net speakingsite.com

    $mail->SMTPOptions = array(
       'ssl' => array(
           'verify_peer' => false,
           'verify_peer_name' => false,
           'allow_self_signed' => true
       )
    );

    $send_status = array();

    if (!$mail->Send()) {
        $send_status['code'] = 1;
        $send_status['info'] = $mail->ErrorInfo;
    } else {
        $send_status['code'] = 0;
        $send_status['info'] = 'success';

        \app\index\model\Email::addsend();
    }

    return $send_status;
}

//Google support@ in funciton.php 
function send_email_g($receive_email, $mail_subject, $mail_content, $receive_name = '', $reply_email = '', $reply_name = '')
{
    $mail = new \PHPMailer\PHPMailer\PHPMailer();
    $mail->IsSMTP();
    $mail->Host = config('custom.MAIL_HOST_ADDRESS_g');
    $mail->SMTPAuth = TRUE;
    $mail->Username = config('custom.MAIL_USERNAME_g');
    $mail->Password = config('custom.MAIL_PASSWORD_g');

    $mail->From = config('custom.MAIL_FROM_EMAIL_g');
    $mail->FromName = config('custom.MAIL_FROM_NAME');

    $mail->AddAddress($receive_email, $receive_name);
    $mail->AddReplyTo($reply_email, $reply_name);
    $mail->CharSet = "utf-8";
    $mail->Encoding = "base64";
    $mail->IsHTML(TRUE);
    $mail->Subject = "=?UTF-8?B?" . base64_encode($mail_subject) . "?=";
    $mail->Body = $mail_content;
    $mail->SMTPDebug = 0;
    $mail->SMTPSecure = "SSL/TLS";
    // $mail->Port = 465; // for QQ 
     $mail->Port = 587; //for outlook gmail net speakingsite.com

    $mail->SMTPOptions = array(
       'ssl' => array(
           'verify_peer' => false,
           'verify_peer_name' => false,
           'allow_self_signed' => true
       )
    );

    $send_status = array();

    if (!$mail->Send()) {
        $send_status['code'] = 1;
        $send_status['info'] = $mail->ErrorInfo;
    } else {
        $send_status['code'] = 0;
        $send_status['info'] = 'success';

        \app\index\model\Email::addsend();
    }

    return $send_status;
}

//Google notification@ in funciton.php 
function send_email_gn($receive_email, $mail_subject, $mail_content, $receive_name = '', $reply_email = '', $reply_name = '')
{
    $mail = new \PHPMailer\PHPMailer\PHPMailer();
    $mail->IsSMTP();
    $mail->Host = config('custom.MAIL_HOST_ADDRESS_gn');
    $mail->SMTPAuth = TRUE;
    $mail->Username = config('custom.MAIL_USERNAME_gn');
    $mail->Password = config('custom.MAIL_PASSWORD_gn');

    $mail->From = config('custom.MAIL_FROM_EMAIL_gn');
    $mail->FromName = config('custom.MAIL_FROM_NAME');

    $mail->AddAddress($receive_email, $receive_name);
    $mail->AddReplyTo($reply_email, $reply_name);
    $mail->CharSet = "utf-8";
    $mail->Encoding = "base64";
    $mail->IsHTML(TRUE);
    $mail->Subject = "=?UTF-8?B?" . base64_encode($mail_subject) . "?=";
    $mail->Body = $mail_content;
    $mail->SMTPDebug = 0;
    $mail->SMTPSecure = "SSL/TLS";
    // $mail->Port = 465; // for QQ 
     $mail->Port = 587; //for outlook gmail net speakingsite.com

    $mail->SMTPOptions = array(
       'ssl' => array(
           'verify_peer' => false,
           'verify_peer_name' => false,
           'allow_self_signed' => true
       )
    );

    $send_status = array();

    if (!$mail->Send()) {
        $send_status['code'] = 1;
        $send_status['info'] = $mail->ErrorInfo;
    } else {
        $send_status['code'] = 0;
        $send_status['info'] = 'success';

        \app\index\model\Email::addsend();
    }

    return $send_status;
}


function get_week($time)
{
    $w = date('w', $time);
    $weeks = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
    return $weeks[$w];
}

function change_time($num)
{
    if ($num > 0) {
        $str = '+';
    } else {
        $str = '-';
        $num = abs($num);
    }
    if (intval($num) == $num) {
        $str .= sprintf('%02d', $num / 1) . ':00';
    } else {
        $str .= sprintf('%02d', $num / 1) . ':';
        $minite = ($num - intval($num)) * 60;
        $str .= $minite;
    }
    return $str;
}

//获取使用的设备
function get_device_type()
{
    $Agent = strtolower($_SERVER['HTTP_USER_AGENT']);
    $deviceType = 1;
    if (strpos($Agent, 'android') || strpos($Agent, 'unix')) {
        $deviceType = 3;
    } elseif (strpos($Agent, 'iphone') || strpos($Agent, 'ipad')) {
        $deviceType = 2;
    }
    return $deviceType;
}

//转换url
function url_base64_encode($str)
{
    $code = base64_encode($str);//$code='dHQ=';
    $code = str_replace('+', "!", $code);//把所用"+"替换成"!"
    $code = str_replace('/', "*", $code);//把所用"/"替换成"*"
    $code = str_replace('=', "", $code);//把所用"="删除掉
    return $code;
}

//转换url
function url_base64_decode($code)
{
    $code = str_replace("!", '+', $code);//把所用"+"替换成"!"
    $code = str_replace("*", '/', $code);//把所用"/"替换成"*"
    $str = base64_decode($code);
    return $str;
}

//获取一周的日期
function get_weeks($time)
{
    $week = date('w', $time);
    $format = 'Y-m-d';
    $date = [];
    for ($i = 1; $i < 7; $i++) {
        $date_time = date($format, strtotime('+' . $i . ' days', $time));
//        $date_time = date($format ,strtotime( '+' . $i-$week .' days', $time));
        $date[$i]['date'] = $date_time;
        $date[$i]['time'] = strtotime($date_time);
    }
    return $date;
}

//是否微信浏览器
function is_wechat_browser()
{
    $user_agent = $_SERVER['HTTP_USER_AGENT'];
    if (strpos($user_agent, 'MicroMessenger') === false) {
        return false;
    } else {
        return true;
    }
}

//拼接手机号，前面加区号
function get_mobile($mobile, $country_id)
{
    $country = \app\index\model\Country::find($country_id);
    if ($country['quhao'] != '86') {
        $mobile = '00' . intval($country['quhao']) . '-' . $mobile;
    }
    return $mobile;
}

/*
 * 判断是否为手机端
 */
function check_wap()
{
    // 如果有HTTP_X_WAP_PROFILE则一定是移动设备
    if (isset ($_SERVER['HTTP_X_WAP_PROFILE'])) {
        return true;
    }
    //如果via信息含有wap则一定是移动设备,部分服务商会屏蔽该信息
    if (isset ($_SERVER['HTTP_VIA'])) {
        //找不到为flase,否则为true
        return stristr($_SERVER['HTTP_VIA'], "wap") ? true : false;
    }
    //判断手机发送的客户端标志,兼容性有待提高
    if (isset ($_SERVER['HTTP_USER_AGENT'])) {
        $clientkeywords = array('nokia', 'sony', 'ericsson', 'mot', 'samsung', 'htc', 'sgh', 'lg', 'sharp', 'sie-',
            'philips', 'panasonic', 'alcatel', 'lenovo', 'iphone', 'ipod', 'blackberry', 'meizu', 'android', 'netfront', 'symbian',
            'ucweb', 'windowsce', 'palm', 'operamini', 'operamobi', 'openwave', 'nexusone', 'cldc', 'midp', 'wap', 'mobile'
        );
        // 从HTTP_USER_AGENT中查找手机浏览器的关键字
        if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT']))) {
            return true;
        }
    }
    //协议法，因为有可能不准确，放到最后判断
    if (isset ($_SERVER['HTTP_ACCEPT'])) {
        // 如果只支持wml并且不支持html那一定是移动设备
        // 如果支持wml和html但是wml在html之前则是移动设备
        if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false)
            && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false ||
                (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') <
                    strpos($_SERVER['HTTP_ACCEPT'], 'text/html')))) {
            return true;
        }
    }
    return false;
}

function curl_post_json($url, $header, $data = NULL, $json = false)
{
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    if (!empty($data)) {
        if ($json && is_array($data)) {
            $data = json_encode($data);
        }
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        if ($json) { //发送JSON数据
            curl_setopt($curl, CURLOPT_HEADER, 0);
            curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        }
    }

    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $res = curl_exec($curl);
    $errorno = curl_errno($curl);

    if ($errorno) {
        return array('errorno' => false, 'errmsg' => $errorno);
    }
    curl_close($curl);
    return json_decode($res, true);
}

function micro_time()
{
    list($usec, $sec) = explode(" ", microtime());
    $time = ($sec . substr($usec, 2, 3));
    return $time;
}
