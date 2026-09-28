<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2021/7/13
 * Time: 10:53
 */

namespace app\index\controller;

use app\index\model\Orderitems;
use app\index\model\Users;

class Pdf extends Home
{

    public function index()
    {
        $name = url_base64_decode(input('name'));
        $uid = input('uid');
        $date = input('date');
        $start = input('start');
        $end = input('end');
        $date = date('M d - Y', strtotime($date) );
        $name = str_replace('+', '', $name);

        $learner_num = count(Orderitems::where(['teacher_id'=>$uid])->where('book_date_teacher','between',[$start, $end])->where('order_status', 'in',[2,3])->group('user_id')->select());
        $class_num = Orderitems::where(['teacher_id'=>$uid])->where('book_date_teacher','between',[$start, $end])->where('order_status', 2)->count();
        $user = Users::field('class_num')->find($uid);
        $this->assign('name', $name ?? $user['username']);
        $this->assign('learner_num', $learner_num);
        $this->assign('date', $date);
        $start = date('F Y', strtotime($start));
        $end = date('F Y', strtotime($end));
        $this->assign('start', $start);
        $this->assign('end', $end);
        $this->assign('class_num', round($class_num / 2, 1));
        return $this->fetch();
    }

    //机构协议
    public function agree()
    {
        $uid = input('uid');
        $date = str_replace('-', ' / ', input('date'));
        $user = Users::find($uid);
        if ($user['user_type'] == 1) {
            $template = 'learner';
        } else {
            $template = 'tutor';
        }
        $this->assign('date', $date);
        $this->assign('user', $user);

        return $this->fetch($template);
    }

}
