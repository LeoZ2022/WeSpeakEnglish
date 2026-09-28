<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2021/5/12
 * Time: 10:26
 */

//之前的数据处理
namespace app\index\controller;

use app\index\model\Daytime;
use app\index\model\Orderitems;
use app\index\model\Users;

class Datas extends Home
{

    public function class_num()
    {
        //更新上完课的数量
        $list = Orderitems::field('user_id,count(*) as cnt')->where('order_status =2')->group('user_id')->select();
        foreach ($list as $row) {
            Users::where('id', $row['user_id'])->setField('class_num', $row['cnt']);
        }
        $list = Orderitems::field('teacher_id,count(*) as cnt')->where('order_status =2')->group('teacher_id')->select();
        foreach ($list as $row) {
            Users::where('id', $row['teacher_id'])->setField('class_num', $row['cnt']);
        }
    }

    public function index()
    {
        //有设置时间的老师，设置为has_active:1
        $sql = "update dp_cms_users set has_active = 1 where id in(SELECT user_id from dp_cms_daytime)";
        Daytime::query($sql);

        //买课数量
        $list = Orderitems::field('user_id,count(*) as cnt')->group('user_id')->select();
        foreach ($list as $row) {
            Users::where('id', $row['user_id'])->setField('buy_num', $row['cnt']);
        }
        $list = Orderitems::field('teacher_id,count(*) as cnt')->group('teacher_id')->select();
        foreach ($list as $row) {
            Users::where('id', $row['teacher_id'])->setField('buy_num', $row['cnt']);
        }

        //更新付款数量
        $list = Orderitems::field('user_id,count(*) as cnt')->where('order_status >0 and order_status < 10')->group('user_id')->select();
        foreach ($list as $row) {
            Users::where('id', $row['user_id'])->setField('paid_num', $row['cnt']);
        }
        $list = Orderitems::field('teacher_id,count(*) as cnt')->where('order_status >0 and order_status < 10')->group('teacher_id')->select();
        foreach ($list as $row) {
            Users::where('id', $row['teacher_id'])->setField('paid_num', $row['cnt']);
        }

        //更新上完课的数量
        $list = Orderitems::field('user_id,count(*) as cnt')->where('order_status =2')->group('user_id')->select();
        foreach ($list as $row) {
            Users::where('id', $row['user_id'])->setField('class_num', $row['cnt']);
        }
        $list = Orderitems::field('teacher_id,count(*) as cnt')->where('order_status =2')->group('teacher_id')->select();
        foreach ($list as $row) {
            Users::where('id', $row['teacher_id'])->setField('class_num', $row['cnt']);
        }

        //预约时间的前后半小时设置成is_active：0
        $time = time();
        $list = Daytime::where('datetime', 'gt', $time)->where('booked', 1)->select();
        foreach ($list as $row){
            $time1 = $row['datetime'] - 1800;
            $time2 = $row['datetime'] + 1800;
            Daytime::where('user_id', $row['user_id'])->where('datetime', 'in',[$time1, $time2])->setField('is_active', 0);
        }
    }

}