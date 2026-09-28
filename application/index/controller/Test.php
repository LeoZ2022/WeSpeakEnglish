<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/9/28
 * Time: 21:48
 */

namespace app\index\controller;

use app\api\model\Orderitems;
use app\index\model\Country;
use app\index\model\Daytime;
use app\index\model\Location;
use app\index\model\Users;
use app\index\service\ImageCode;
use app\index\service\Push;
use app\index\service\Websocket;
use qrcode\QRcode;
use Tencent\TLSSigAPIv2;
use think\Db;

class Test extends Home
{

    public function fmp3()
    {
        $cmd = 'ffmpeg -i /www/wwwroot/school.wespeakenglish.chat/Test0803.m4a -f mp3 /www/wwwroot/school.wespeakenglish.chat/public/uploads/'.time().'.mp3';
        $result = exec($cmd);
        var_dump($result);
    }

    public function time2()
    {
        $date = '2022-03-13 ';
        $t = '14:00';
        $location = Location::find(90);
        $time = strtotime($date.$t.' '.$location['code']);
        echo $date.$t."<br/>";
        echo $time."<br/>";
        echo date('Y-m-d H:i:s', $time)."<br/>";
        echo time_to_date($time, $location)."<br/>"."<br/>";
        $t = '01:00';
        $location = Location::find(90);
        $time = strtotime($date.$t.' '.$location['code']);
        echo $date.$t."<br/>";
        echo $time."<br/>";
        echo date('Y-m-d H:i:s', $time)."<br/>";
        echo time_to_date($time, $location)."<br/>";
        exit;

        echo $date.' '. $t."<br/>";
        $time = date_to_time($date . ' ' . $t, $location);
        echo $time; var_dump(date('Y-m-d H:i', $time)); echo "<br/>";
        $date = '2022-03-13';
        $t = '15:00';
        $location = Location::find(90);
        echo $date.' '. $t."<br/>";
        $time = date_to_time($date . ' ' . $t, $location);
        echo $time; var_dump(date('Y-m-d H:i', $time));
    }

	public function send_email3($email = '1035609228@qq.com')
	{
        set_time_limit(3000);
		$res = send_email2($email, '有新的留言', date('Y-m-d H:i:s'));
		var_dump($res);
//		$res = send_email($email, '有新的留言', date('Y-m-d H:i:s'));
//		var_dump($res);
	}

	public function send_email($email = '1035609228@qq.com')
	{
        set_time_limit(3000);
		$res = send_email($email, '有新的留言', date('Y-m-d H:i:s'));
		var_dump($res);
	}

    public function send_partner()
    {
        \app\index\model\Partner::send_email_date();
        echo 'success';
    }

    public function sendgt()
    {
        $res = Push::admin_send('这是测试标题：'.date('Y-m-d'), '现在的时间是：'.date('Y-m-d H:i:s'));
        print_r($res);
    }

    public function send_front()
    {
        $res = Push::send_front([14, 17], 5);
        print_r($res);
    }

    public function pdf()
    {
        $url = 'https://wespeakenglish.chat/index/pdf/index?name=yexiao&uid=8&date=2021-07-12';
        $fname = $_SERVER['DOCUMENT_ROOT'].'/public/pdf/'.time().'.pdf';
        $cmd = 'wkhtmltopdf -q "'.$url.'" '.$fname;
        echo $cmd."<br/>";
        $res = shell_exec($cmd);
        var_dump($res);
    }

    public function test_date()
    {
        //机构每日提醒
        \app\index\model\Partner::send_email_date();
    }

    public function clearTime()
    {
        $sql = "UPDATE `dp_cms_orderitems` SET `in_time_teacher` = NULL, `in_time_student` = NULL, `out_time_teacher` = NULL, `out_time_student` = NULL, `learner_time_len` = NULL, `teacher_money` = '0', `order_status` = '1', `class_time_begin` = '0', `class_time_end` = '0', `class_time_len` = '0' WHERE `user_id` = 15 and teacher_id=12";
        Db::name('cms_order')->query($sql);
        Db::name('cms_roomlogs')->where('user_id=15 or user_id=12')->delete();
        echo 'success';
    }

    public function qrcode()
    {
        include 'extend/qrcode/phpqrcode.php';

        $value = 'aasdfasdf://www.helloweba.com'; //二维码内容
        $errorCorrectionLevel = 'L';//容错级别
        $matrixPointSize = 6;//生成图片大小
        //生成二维码图片
        QRcode::png($value, 'qrcode.png', $errorCorrectionLevel, $matrixPointSize, 2);
        echo '<img src="/qrcode.png" />'; exit;
        $logo = 'logo.png';//准备好的logo图片
        $QR = 'qrcode.png';//已经生成的原始二维码图
        if ($logo !== FALSE) {
            $QR = imagecreatefromstring(file_get_contents($QR));
            $logo = imagecreatefromstring(file_get_contents($logo));
            $QR_width = imagesx($QR);//二维码图片宽度
            $QR_height = imagesy($QR);//二维码图片高度
            $logo_width = imagesx($logo);//logo图片宽度
            $logo_height = imagesy($logo);//logo图片高度
            $logo_qr_width = $QR_width / 5;
            $scale = $logo_width/$logo_qr_width;
            $logo_qr_height = $logo_height/$scale;
            $from_width = ($QR_width - $logo_qr_width) / 2;
            //重新组合图片并调整大小
            imagecopyresampled($QR, $logo, $from_width, $from_width, 0, 0, $logo_qr_width, $logo_qr_height, $logo_width, $logo_height);
        }

//输出图片

        imagepng($QR, 'helloweba.png');

        echo '<img src="helloweba.png" />';
    }

    public function socket()
    {
        $time1 =time();
        $time2 = strtotime('+30 minute');

        echo $time1.'---'.$time2.'=='.($time2-$time1)."<br/>";
        echo date('Y-m-d H:i:s')."<br/>";
        if ($this->request->isPost()) {
            $class_id = input('class_id');
            $act = input('act');
            $class = Orderitems::find($class_id);
            if (!$class) {
                echo 'no class';exit;
            }
            $toids = $class['user_id'].','.$class['teacher_id'];
            $time = time();
            if ($act == 1) {
                $res = Websocket::class_begin($class, $time, $toids);
            } elseif ($act == 2) {
                $res = Websocket::class_pause($class, $toids);
            } elseif ($act == 3) {
                $res = Websocket::class_continue($class, $time, $toids);
            } elseif ($act == 4) {
                $res = Websocket::class_exit($class, $toids);
            } elseif ($act== 5) {
                $res = Websocket::class_success($class, $time, $toids);
            } else {
                $res = '';
            }
            var_dump($toids);
            var_dump($act);
            var_dump($res);
        }
        return $this->fetch();
    }

    public function chk_email($email)
    {
        if(!filter_var($email, FILTER_VALIDATE_EMAIL))
        {
            echo "E-mail is not valid";
        }
        else
        {
            echo "E-mail is valid";
        }
    }

    public function create_region()
    {
        set_time_limit(0);
        $country_name = Db::name('cms_fivecountry')->group('country')->column('country');
        $countrys = Country::where('country_name', 'in', $country_name)->select();
        foreach ($countrys as $k=>$row) {
            $list = Db::name('cms_fivecountry')->where('country', $row['country_name'])->select();
            foreach ($list as $kk=>$area) {
                $data = ['region_name' =>$area['province']];
                $data['country_id'] = $row['id'];
                $has = Db::name('cms_regions')->where($data)->find();
                if (!$has) {
                    $parent_id = Db::name('cms_regions')->insertGetId($data);
                } else {
                    $parent_id = $has['id'];
                }
                $data['region_name'] = $area['City'];
                $has = Db::name('cms_regions')->where($data)->find();
                if (!$has) {
                    $data['parent_id'] = $parent_id;
                    Db::name('cms_regions')->insertGetId($data);
                }
            }
        }
        echo 'success';
    }

    public function push($id)
    {
        $res = Push::send_front($id, 30);
        var_dump($res);
    }

    public function push_begin($classid, $uid)
    {
//        $res = Push::class_begin(['id'=>$classid], time(), $uid);
        $res = Push::send_front($uid, 5);
        var_dump($res);
    }
    
    public function trcd()
    {
        $api = new TLSSigAPIv2(config('tencentyun.appid'), config('tencentyun.key'));
        $sig = $api->genUserSig('xiaojun');
        echo $sig . "\n";
    }

    public function showtime()
    {
        $date = '2021-03-15 09:30';
        $location = Location::find(62);
        print_r($location);
        $time = date_to_time($date, $location);
        echo $time;
        var_dump(date('Y-m-d H:i:s', $time));
    }

    public function showerr()
    {
        $this->success('错误提示！！！！','','',1000000);
    }

    public function testdate()
    {
        $date = '2021-2-7';
        echo $date."<br/>";
        echo date('Y-m-d', strtotime($date));
    }

    public function time01($code = '')
    {
        $time = time();
        $date0 = date('Y-m-d H:i:s ', $time);
        echo $date0;
        echo date('Z', $time) / 3600;
        echo "<br/>";
        date_default_timezone_set($code);
        $date0 = date('Y-m-d H:i:s ', $time);
        echo $date0;
        echo date('Z', $time) / 3600;
        exit;
    }

    public function date1()
    {
        $date = '2020-05-01 12:00:00';
        $time = strtotime($date);
        echo date(' Y-m-d H:i:s I', $time)."<br/>";
        echo date_default_timezone_get(), date(' Y-m-d H:i:s I', $time), "<br/>";
        date_default_timezone_set('America/Toronto'); //加拿大/多伦多
        echo date_default_timezone_get(), date(' Y-m-d H:i:s I', $time), "<br/>";
    }

    public function date2()
    {
        echo md5('ziye123')."<br/>";
        $date = '2020-05-01 00:00:00';
        $location = Location::find(7);
        echo $location['location_name'].":".$date."<br/>";
        date_default_timezone_set($location['code']);
        $time = strtotime($date);
        $xia = date('I', $time);
        echo '夏令时：'.$xia."<br/>";
        date_default_timezone_set(config('custom.cfg_default_timezone_name'));
        $timezone_cfg = config('custom.server_imezone');
        $timezone = $location['timezone'];
        $time = strtotime($date) - $timezone * 3600 + 3600 * $timezone_cfg;
        if ($xia) {
            $time -= 3600;
        }
        echo '北京：'.date('Y-m-d H:i:s', $time)."<br/>";

        $date = '2020-02-01 00:00:00';
        $location = Location::find(7);
        echo $location['location_name'].":".$date."<br/>";
        date_default_timezone_set($location['code']);
        $time = strtotime($date);
        $xia = date('I', $time);
        echo '夏令时：'.$xia."<br/>";
        date_default_timezone_set(config('custom.cfg_default_timezone_name'));
        $timezone_cfg = config('custom.server_imezone');
        $timezone = $location['timezone'];
        $time = strtotime($date) - $timezone * 3600 + 3600 * $timezone_cfg;
        if ($xia) {
            $time -= 3600;
        }
        echo '北京：'.date('Y-m-d H:i:s', $time);
    }

    public function date3()
    {
        //当地时区转成北京时间戳
        $date = '2020-05-01 00:00:00';
        $location = Location::find(7);
        echo $location['location_name'].":".$date."<br/>";
        $time = date_to_time($date, $location);
        echo '北京：'.date('Y-m-d H:i:s', $time)."<br/>";

        $date = '2020-02-01 00:00:00';
        $location = Location::find(7);
        echo $location['location_name'].":".$date."<br/>";
        $time = date_to_time($date, $location);
        echo '北京：'.date('Y-m-d H:i:s', $time)."<br/>";
    }

    public function date4()
    {
        //当地时区转成北京日期和老师所在时区日期
        $date = '2020-05-01 06:00:00';
        $location_user = Location::find(5);
        echo $location_user['location_name'].'：'.$date."<br/>";
        $time = date_to_time($date, $location_user);
        $location = Location::find(7);
        echo '北京：'.date('Y-m-d H:i:s', $time)."<br/>";
        $ntime = time_to_date($time, $location);
        echo $location['location_name'].'：'.$ntime."<br/>";

        $date = '2020-02-01 06:00:00';
        echo $location_user['location_name'].'：'.$date."<br/>";
        $time = date_to_time($date, $location_user);
        echo '北京：'.date('Y-m-d H:i:s', $time)."<br/>";
        $ntime = time_to_date($time, $location);
        echo $location['location_name'].'：'.$ntime."<br/>";

        $location_user = Location::find(1);
        $time = 1605795410;
        echo '北京：'.date('Y-m-d H:i:s', $time)."<br/>";
        $ntime = time_to_date($time, $location_user);
        echo $location_user['location_name'].'：'.$ntime."<br/>";


    }

    public function sendemail($email = '1035609228@qq.com')
    {
        $web_url = $_SERVER['REQUEST_SCHEME'].':'.$_SERVER['SERVER_NAME'];
//        echo $web_url;print_r($_SERVER);exit;
        $res = send_email($email, '每日数据', date('Y-m-d H:i:s'));
        var_dump($res);
    }

    public function sendemail2($email = '1035609228@qq.com')
    {
        $mail_subject = '每日数据';
        $mail_content = date('Y-m-d H:i:s');
        $receive_email = $email;
        $receive_name = 'English';
        $mail = new \PHPMailer\PHPMailer\PHPMailer();
        $mail->IsSMTP();
        $mail->Host = 'smtp.163.com';
        $mail->SMTPAuth = TRUE;
        $mail->Username = 'myhouse1688@163.com';
        $mail->Password = 'XIHWSVAWEDLKRYQI';

        $mail->From = 'myhouse1688@163.com';
        $mail->FromName = 'myhouse1688';
        $mail->AddAddress($receive_email, $receive_name);
        //$mail->AddAddress("ellen@example.com");
        $mail->AddReplyTo('', '');
        $mail->CharSet = "utf-8";
        $mail->Encoding = "base64";
        $mail->IsHTML(TRUE);
        $mail->Subject = "=?UTF-8?B?" . base64_encode($mail_subject) . "?=";
        $mail->Body = $mail_content;
        $mail->SMTPDebug = 2;

        $send_status = array();
        if (!$mail->Send()) {
            $send_status['code'] = 1;
            $send_status['info'] = $mail->ErrorInfo;
        } else {
            $send_status['code'] = 0;
            $send_status['info'] = 'success';
        }
        var_dump($send_status);
    }

    public function regstu()
    {
        $english_level = config('english_level');
        $native_language = config('native_language');

        $this->assign('english_level', $english_level);
        $this->assign('native_language', $native_language);

        $location = Location::where('status',1)->select();
        $this->assign('location', $location);

        return $this->fetch();
    }

    public function verify()
    {
        $ImageCode = new ImageCode();
        $ImageCode->Show(130, 35);
    }

    public function index()
    {
        $time = get_time_gmt();
        echo $time;
        return $this->fetch();
    }

    public function check_code()
    {
        $shu = input('shu');
        $ImageCode = new ImageCode();
        if ($ImageCode->check_code($shu)) {
            echo 'success';
        } else {
            echo 'error:'.session('verify_code');
        }
    }

    public function time()
    {
        $stime = get_time_gmt();
        echo '0time时间戳：'.$stime.'<br/>';
        echo '0Stime日期：'.date('Y-m-d H:i:s', $stime).'<br/>';
//        date_default_timezone_set('America/New_York');
        $time = time();
        echo '设置时区后的<br/>';
        echo 'Time时间戳：'.$time.'<br/>';
        $stime = get_time_gmt();
        echo 'Stime时间戳：'.$stime.'<br/>';
        echo 'Stime日期：'.date('Y-m-d H:i:s', $stime).'<br/>';
        echo '当前时区：'.date('Y-m-d H:i:s', $time)."<br/>";
        echo '-4时区：'.date('Y-m-d H:i:s', gmt_to_local($stime, -4))."<br/>";
        echo '+8时区：'.date('Y-m-d H:i:s', gmt_to_local($stime, 8))."<br/>";
        echo '当前：'.date('Y-m-d H:i:s')."<br/>";
    }

    public function set_time()
    {
        $user_id = input('user_id');
        $date = input('date');
        $time = input('time');

        $user = Users::find($user_id);
        $location = Location::find($user['location']);
        $stime = strtotime($date.' '.$time);
        $etime = $stime - 3600 * $location['timezone'];
        $data = ['user_id' => $user_id, 'datetime' => $etime, 'date' => $date, 'time' => $time];
        $result = Daytime::insert($data);
        echo $result;
    }

    public function get_time_gmt()
    {
        $user_id = input('user_id');
        $user = Users::find($user_id);
        $location = Location::find($user['location']);
        echo $location['code']." ".$location['timezone']."<br/>";

        $daytimes = Daytime::alias('d')->field('d.*, timezone, code')
            ->join('cms_users u', 'd.user_id = u.id')->join('cms_location l', 'location = l.id')
            ->where("FROM_UNIXTIME(datetime + 3600 * {$location['timezone']}) > '2020-10-10'")
            ->select();
        print_r($daytimes);echo "<br/>";

        $daytimes = Daytime::alias('d')->field('d.*, timezone, code')
            ->join('cms_users u', 'd.user_id = u.id')->join('cms_location l', 'location = l.id')
            ->select();
        foreach ($daytimes as $k=>$row) {
            $time = time_to_user($row['datetime'], $location['timezone']);
            $date = date('Y-m-d H:i', $time);
            echo $row['timezone'].','.$row['code'].'，Date:'.$row['date'].' '.$row['time'].'，0：'.date('Y-m-d H:i', $row['datetime'])." User：".$date."<br/>";
        }
    }

    public function index2()
    {
        echo '当前时区：'.time()."<br/>";
        $gmdate = gmdate('Y-m-d H:i:s');
        $gtime = strtotime($gmdate);
        echo date('Y-m-d H:i:s')."<br/>";
        echo '0时区：'.$gtime."<br/>";
        echo $gmdate."<br/>";
        echo date('Z')."<br/>";
        echo '加上当前时区差：'.date('Y-m-d H:i:s',$gtime + date('Z'))."<br/>";
        date_default_timezone_set('America/New_York');
        echo '设置时区后的：'.date('Y-m-d H:i:s')."<br/>";
        $gmdate = gmdate('Y-m-d H:i:s');
        echo '0时区：'.$gmdate."<br/>";
        echo date('Z')."<br/>";
        echo date('Y-m-d H:i:s',$gtime - date('Z'))."<br/>";
    }

    public function times()
    {
        $time = time();
        //当前时区的
        $gmmktime = gmmktime(0,0,0,10,3,1975);
        //0时区的
        $mktime = mktime(0,0,0,10,3,1975);
        echo 'Time:'.$time."<br/>";
        echo 'Gmmktime:'.$gmmktime."<br/>";
        echo 'Mktime:'.$mktime."<br/>";

        echo 'Time:'.date('Y-m-d H:i:s',$time)."<br/>";
        echo 'Gmmktime:'.date('Y-m-d H:i:s',$gmmktime)."<br/>";
        echo 'Mktime:'.date('Y-m-d H:i:s',$mktime)."<br/><br/><br/>";

        $gmdate = gmdate('Y-m-d H:i:s');
        $time = strtotime($gmdate);
        echo '0时区：'.date('Y-m-d H:i:s', $time)."<br/>";
        echo '-4时区：'.date('Y-m-d H:i:s', $time - 4* 3600)."<br/>";
        echo '+8时区：'.date('Y-m-d H:i:s', $time + 8* 3600)."<br/>";
        echo '当前：'.date('Y-m-d H:i:s')."<br/>"."<br/>"."<br/>";

        date_default_timezone_set('America/New_York');
        echo '设置时区后的<br/>';
        echo '0时区：'.date('Y-m-d H:i:s', $time)."<br/>";
        echo '-4时区：'.date('Y-m-d H:i:s', $time - 4* 3600)."<br/>";
        echo '+8时区：'.date('Y-m-d H:i:s', $time + 8* 3600)."<br/>";
        echo '当前：'.date('Y-m-d H:i:s')."<br/>";
    }

    public function get_time()
    {
        $date = input('date');
        $timezone = input('timezone');
        $time = date_to_time($date, $timezone);
        echo "Date:".$date."<br/>";
        echo "Time:".$time."<br/>";
        echo "8 Date:".date('Y-m-d H:i', $time)."<br/>";
    }

    public function view()
    {
        $id = input('id');
        $info = Orderitems::find($id);
        $this->assign('info', $info);

        $video_url = config('tencentyun.video_url2');
        $this->assign('video_url', $video_url);

        $streamId = config('tencentyun.appid').'_'.$info['id'].'_'.$info['teacher_id'].'_main';
        $WebRTC = 'webrtc://'.$video_url.'/live/'.$streamId;
        $HLS = 'http://'.$video_url.'/live/'.$streamId.'.m3u8';
        $this->assign('streamId', $streamId);
        $this->assign('WebRTC', $WebRTC);
        $this->assign('HLS', $HLS);

        return $this->fetch();
    }

    public function update_studentnum()
    {
        $list = \app\index\model\Orderitems::where('order_status', 2)->group('teacher_id')->field('teacher_id, count(DISTINCT user_id) as cnt')->select();
        foreach ($list as $row) {
            Users::where('id', $row['teacher_id'])->setField('learner_num', $row['cnt']);
        }
    }

    public function get_app()
    {
        $url = 'wespeakenglish://pages/room/hub?id=7&email=1035609228@qq.com&password=d548e0141340d3cd72c3adaa0bc63cd1&username=yexiao';
        $res = file_get_contents($url);
        var_dump($res);
    }

    public function pushall()
    {
        Push::send('推送通知', ['title' => '通知']);
    }

}