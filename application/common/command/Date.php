<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/11/4
 * Time: 10:20
 */

namespace app\common\command;

use app\admin\model\Config;
use app\index\model\Autoweek;
use app\index\model\Daytime;
use app\index\model\Location;
use app\index\model\Payment;
use app\index\model\Users;
use think\console\Command;
use think\console\Input;
use think\console\Output;

class Date extends Command
{
    protected function configure()
    {
        $this->setName('date');
    }

    protected function execute(Input $input, Output $output)
    {
        error_reporting(0);
//        $file = 'public/logs/date_' . date('Y-m-d') . '.txt';
 //       $fp = fopen($file, 'a+');
  //      fwrite($fp, date('Y-m-d H:i:s') . "\n");

        $date = date('Y-m-d');
        $time1 = strtotime($date);
        $time2 = strtotime($date.' 23:59:59');
        $map = [];
        $map[] = ['create_time', 'between', [$time1, $time2]];
        $cnt = Users::where($map)->count();
        $moneys = Payment::where('pay_status', 1)->where($map)->field('sum(money) as moneys')->find();
        $money = $moneys['moneys'] ?? 0;

        $email = config('custom.CFG_SYSTEM_EMAIL');
        $content = $date." 注册人数：".$cnt."，支付金额：".$money;
        $res = send_email_g($email, 'Daily data', $content);

        //自动续期7天
        $date = date(strtotime('+2 day'));
        $autos = Autoweek::alias('a')->field('a.*, location')->join('cms_users u', 'user_id=u.id and is_locked=0 and close_state=0')->where('nextdate', $date)->select();
        $obj = new Daytime();
        echo $date."<br/>";
        foreach ($autos as $k=>$row) {
            $autotimes = unserialize($row['times']);
            $location = Location::find($row['location']);
            $user_id = $row['user_id'];
            for($i=0;$i<7;$i++) {
                foreach ($autotimes as $k => $temp) {
                    $date = strtotime('+'.$i.' day', strtotime($date));
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
            }
        }
        echo "success!<br/>";
    }

}