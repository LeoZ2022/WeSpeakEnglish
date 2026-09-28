<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 20http://121.5.55.186:8888/site20/11/4
 * Time: 10:20
 */

namespace app\common\command;

use app\index\model\Daytime;
use app\index\model\Order;
use app\index\model\Orderitems;
use app\index\model\Payment;
use app\index\model\Users;
use think\console\Command;
use think\console\Input;
use think\console\Output;
use think\Db;

class Minute extends Command
{
    protected function configure()
    {
        $this->setName('Minute');
    }

    protected function execute(Input $input, Output $output)
    {
        $time_not_pay = config('custom.CFG_NOT_PAYMENT');
        error_reporting(0);
        $time30 = strtotime('-'.$time_not_pay.' minute');
        $file = 'public/logs/time_not_pay_' . date('Y-m-d') . '.txt';
        $fp = fopen($file, 'a+');
        fwrite($fp, date('Y-m-d H:i:s') . "\n");

        $time = time();
        $orders = Order::where('pay_status', 'eq', 0)->where('create_time', 'lt', $time30)->select();
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
            Order::where('id', $row['id'])->update(['order_status' => 4, 'order_note' => '超时未支付，自动取消']);
            $items = Orderitems::where('order_id', $row['id'])->setField('order_status', 10);
            $dayids = Orderitems::where('order_id', $row['id'])->column('daytime_id');
            Daytime::where('id', 'in', $dayids)->where('user_id', $row['teacher_id'])->setField('booked', 0);
        }

        //2小时前的未上课的订单设置成 未完成 状态
        $time = strtotime('-2 hour');
        Orderitems::where('order_status', 1)->where('datetime', 'lt', $time)->setField('order_status', 3);

        echo "success!<br/>";
    }

}