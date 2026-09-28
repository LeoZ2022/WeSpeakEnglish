<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2021/1/28
 * Time: 11:25
 */

namespace app\index\controller;

use app\index\model\Autoweek;
use app\index\model\Location;
use app\index\model\Pushs;
use app\index\service\Push;
use app\index\service\Websocket;
use app\user\model\User;
use think\Controller;
use app\index\model\Daytime;
use app\index\model\Order;
use app\index\model\Orderitems;
use app\index\model\Payment;
use app\index\model\Users;
use think\Db;

class Crontab extends Controller
{

    public function date()
    {
        $date0 = date('Y-m-d', strtotime('-1 day'));
        //判断还有没有新的时间
        $lasts = Daytime::field("user_id, FROM_UNIXTIME(max(datetime), '%Y-%m-%d') as maxdate")->group('user_id')
            ->having("maxdate='$date0'")->select();
        $userids = [];
        foreach ($lasts as $row) {
            $userids[] = $row['user_id'];
        }
        $emails = Users::where('id', 'in', $userids)->where(['enddate_notice' => 1, 'is_locked' => 0, 'close_state' => 0])->field('email, id')->select();
        $content = file_get_contents("template/dateend.html");
        $weburl = substr(config('custom.site_url'), 0, -1);
        foreach ($emails as $row) {
            $userkey = md5($row['email']. config('custom.md5key'));
            $url = $weburl.url('index/cancel_date', ['user' => $row['id'], 'userkey' => $userkey]);
            $content2 = str_replace('{url}', $url, $content);
            send_email_g($row['email'], 'Your availability is empty', $content2);
        }

        $time0 = strtotime('-10 hour');
        Daytime::where('datetime', 'lt', $time0)->delete();
        error_reporting(0);
        $file = 'public/logs/date_' . date('Y-m-d') . '.txt';
        $fp = fopen($file, 'a+');
        fwrite($fp, date('Y-m-d H:i:s') . "\n");

        $date = date('Y-m-d', strtotime('-1 day'));
        $time1 = strtotime($date);
        $time2 = strtotime($date.' 23:59:59');
        $map = [];
        $map[] = ['create_time', 'between', [$time1, $time2]];
        $cnt = Users::where($map)->where(['user_type' => 1, 'is_partner' => 0] )->count();
        $cnt2 = Users::where($map)->where(['user_type' => 2, 'is_partner' => 0] )->count();
        $moneys = Payment::where('pay_status', 1)->where($map)->field('sum(money) as moneys')->find();
        $money = $moneys['moneys'] ?? 0;

//        $email = config('custom.CFG_SYSTEM_EMAIL');
        $email = config('cfg_email');
        $content = $date." 学生注册人数：".$cnt."，老师注册人数：".$cnt2."，支付金额：".$money;
        // $res = send_email3($email, 'Daily data', $content);
        $res = send_email_g($email, 'Daily data', $content);

        //自动续期7天
        $date0 = date('Y-m-d', strtotime('+2 day'));
        $autos = Autoweek::alias('a')->field('a.*, location, email, username')->join('cms_users u', 'user_id=u.id and is_locked=0 and close_state=0')->where('nextdate', $date0)->select();
        $obj = new Daytime();
        echo $date0."<br/>";
        $content = file_get_contents("template/autoweek.html");
        $date_begin = date('M d', strtotime($date0));
        foreach ($autos as $k=>$row) {
            $autotimes = unserialize($row['times']);
            $location = Location::find($row['location']);
            $user_id = $row['user_id'];
            $date = $date0;
            for($i=0;$i<7;$i++) {
                $date_end = date('M d', strtotime($date));
                foreach ($autotimes as $k => $temp) {
                    $t = trim($temp['time']);
                    $time_sels[] = $t;
                    $time = date_to_time($date . ' ' . $t, $location);
                    $data = ['date' => $date, 'time' => $t, 'user_id' => $user_id, 'datetime' => $time];
                    $has = $obj->where($data)->find();
                    if ($has) {
                        continue;
                    }
                    $data['time_end'] = trim($temp['time_end']);
                    $time_ends[] = $data['time_end'];
                    $obj->insert($data);
                }
                $date = date('Y-m-d', strtotime('+1 day', strtotime($date)));
            }
            Autoweek::where('id', $row['id'])->setField('nextdate', $date);
            $find = ['{email}', '{date_begin}', '{date_end}'];
            $replace = [$row['username'], $date_begin, $date_end];
            $content = str_replace($find, $replace, $content);
            send_email_g($row['email'], 'Your availability recurs next 7 days', $content);
        }

        //机构每日提醒
        \app\index\model\Partner::send_email_date();

        echo "success!<br/>";
    }

    public function minute()
    {
        $time_not_pay = config('custom.CFG_NOT_PAYMENT');
        error_reporting(0);
        $file = 'public/logs/minute_' . date('Y-m-d') . '.txt';
        $fp = fopen($file, 'a+');
        fwrite($fp, date('Y-m-d H:i:s') . " minute \n");

        //2小时前的未上课的订单设置成 未完成 状态
        $time = strtotime('-2 hour');
        Orderitems::where('order_status', 1)->where('datetime', 'lt', $time)->setField('order_status', 3);

        //课程定时处理
        \app\api\model\Orderitems::crontab();

        echo "success!<br/>";
    }

    public function socket()
    {
        $now_time = date('Y-m-d H:i');

        //补发sockets
        $sockets = Db::name('cms_sockets')->order('id')->limit(60)->select();
        $fp = fopen('public/socket/log_'.date('Ymd').'.txt', 'a+');
        $url = config('tencentyun.websocket_send_url');
        foreach ($sockets as $k=>$row) {
            $msg = unserialize($row['msg']);
            $msg = is_array($msg) ? implode('##', $msg) : $msg;
            $msg = urlencode($msg);
            $url2 = str_replace(['{$to}', '{$content}'], [$row['user_id'], $msg], $url);
//            echo $url2;
            $result = Websocket::httpRequest($url2);
            if ($result == 'offline') {
                if ($row['error'] == 10) {
                    Db::name('cms_sockets')->where('id', $row['id'])->delete();
                } else {
                    Db::name('cms_sockets')->where('id', $row['id'])->setInc('error', 1);
                }
            } else {
                Db::name('cms_sockets')->where('id', $row['id'])->delete();
            }
            fwrite($fp, date('Y-m-d H:i:s').' class id:'.$row['class_id'].', now time:'.$now_time.','.$url2."\r\n".$result."\r\n");
        }
    }

    public function notice()
    {
        $time_not_pay = config('custom.CFG_NOT_PAYMENT');
        error_reporting(0);
        $time30 = strtotime('-'.$time_not_pay.' minute');
        $file = 'public/logs/minute_' . date('Y-m-d') . '.txt';
        $fp = fopen($file, 'a+');
        fwrite($fp, date('Y-m-d H:i:s') . " notice \n");

        $time = time();
        $orders = Order::where('pay_status', 'eq', 0)->where('order_status', 'lt', 4)->where('create_time', 'lt', $time30)->select();
        //取消30分钟未支付的订单，释放老师的上课时间
        foreach ($orders as $k=>$row) {
            if ($row['user_money'] > 0) {
                //如果有余额支付，退款
                Db::name('cms_users')->where('id', $row['user_id'])->setInc('moneys', $row['user_money']);
                $acc_data = ['user_id' => $row['user_id'], 'type_id' => 3, 'obj_id' => $row['id'],'is_order'=>1, 'money' => $row['user_money'], 'create_time' => $time];
                //生成账户流水
                if (!(Db::name('cms_account_log')->insertGetId($acc_data))) {
                    continue;
                }
            }
            Order::where('id', $row['id'])->update(['order_status' => 10, 'order_note' => '超时未支付，自动取消']);
            $items = Orderitems::where('order_id', $row['id'])->setField('order_status', 10);
            $dayids = Orderitems::where('order_id', $row['id'])->column('daytime_id');
            Daytime::where('id', 'in', $dayids)->where('user_id', $row['teacher_id'])->setField('booked', 0);
        }

        //5分钟开课提醒
        $time30 = date('Y-m-d H:i', strtotime('+5 minute'));
        $orders = Orderitems::where('order_status', 1)->where("FROM_UNIXTIME(datetime, '%Y-%m-%d %H:%i') = '$time30'")->select();
        $ids = [];
        foreach ($orders as $row) {
            $ids[] = $row['user_id'];
            $ids[] = $row['teacher_id'];
        }
        Push::send_front($ids, 5);

        //30分钟开课提醒
        $time30 = date('Y-m-d H:i', strtotime('+30 minute'));
        $orders = Orderitems::alias('i')->field('i.user_id, i.teacher_id, book_date, time_begin, book_date_teacher, time_begin_teacher, s.email as email_student, t.email as email_teacher, s.username as name_student, t.username as name_teacher, t.email_reminder as email_reminder_teacher, s.email_reminder as email_reminder_student')
            ->join('cms_users s', 's.id = i.user_id')
            ->join('cms_users t', 't.id = i.teacher_id')
            ->where('order_status', 1)
//            ->select();
            ->where("FROM_UNIXTIME(datetime, '%Y-%m-%d %H:%i') = '$time30'")->select();
        echo '30分钟开课提醒：'.Orderitems::getlastsql()."<br/>";
        $ids = [];
        $emails = [];
        foreach ($orders as $row) {
            $ids[] = $row['user_id'];
            $ids[] = $row['teacher_id'];
            $emails[] = ['email' => $row['email_student'], 'date' => $row['book_date'], 'time' => $row['time_begin'], 'name' => $row['name_student'], 'email_reminder' => $row['email_reminder_student'] ];
            $emails[] = ['email' => $row['email_teacher'], 'date' => $row['book_date_teacher'], 'time' => $row['time_begin_teacher'], 'name' => $row['name_teacher'], 'email_reminder' => $row['email_reminder_teacher'] ];
        }
        Push::send_front($ids, 30);
        Push::send_emails($emails);

        //定时推送
        Pushs::autopush();

        echo "success!<br/>";
    }

}