<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/10/23
 * Time: 17:37
 */

namespace app\cms\admin;

use app\admin\controller\Admin;
use app\index\model\Order as OrderModel;
use app\index\model\Orderitems as OrderitemModel;
use app\index\model\Orderitems;
use app\index\model\OrderStatus;
use app\index\model\Users as UserModel;

class Order extends Admin
{

    public function index()
    {
        $map = [];

        $status_list = OrderStatus::column('id, status_name_cn');
        $status_list[10] = '已完成-学生缺席';

        $status = input('state_id', 0);
        $partner_id = input('partner_id', 0);
        if ($partner_id) {
            $map[] = ['s.partner_id|t.partner_id', 'eq', $partner_id];
        }
        if ($status) {
            if ($status == 10) {
                $map[] = ['i.order_status', 'eq', 2];
                $map[] = ['teacher_money', 'eq', 0];
            } else if ($status == 2) {
                $map[] = ['i.order_status', 'eq', $status];
                $map[] = ['teacher_money', 'eq', 1];
            } else {
                $map[] = ['i.order_status', 'eq', $status];
            }

            $map[] = ['i.order_status', 'eq', $status];
        } else {
            $map[] = ['i.order_status', 'between', [1,9]];
        }
        $search_field = input('search_field');
        $keyword = input('keyword');
        $_filter_time_from = input('_filter_time_from');
        $_filter_time_to = input('_filter_time_to');
        if ($search_field && $keyword) {
            $map[] = [$search_field, 'like', "%$keyword%"];
        }
        if ($_filter_time_from && $_filter_time_to) {
            $map[] = ['datetime', 'between', [strtotime($_filter_time_from), strtotime($_filter_time_to.' 23:59')]];
        } elseif ($_filter_time_from) {
            $map[] = ['datetime', 'egt', strtotime($_filter_time_from)];
        } elseif ($_filter_time_to) {
            $map[] = ['datetime', 'elt', strtotime($_filter_time_to)];
        }

        $list = OrderitemModel::get_list($map);
        $this->assign('data_list', $list);
        $this->assign('status_list', $status_list);
        $this->assign('state_id', $status);

        $count = OrderitemModel::alias('i')->join('cms_users t', 'i.teacher_id = t.id')
            ->join('cms_users s', 'i.user_id = s.id')->where($map)->where('order_status', 'in', [1,2])->field('sum(money) as moneys, sum(fee) as fees')->find();
        $this->assign('count', $count);

        return $this->fetch();
    }

    //订单详细
    public function detail($id = 0)
    {
        $info = Orderitems::find($id);
        if (!$info) {
            $this->error('找不到记录');
        }
        $this->assign('info', $info);

        $status_list = OrderStatus::column('id, status_name_cn');
        $this->assign('status_list', $status_list);

        $student = UserModel::find($info['user_id']);
        $teacher = UserModel::find($info['teacher_id']);
        $this->assign('student', $student);
        $this->assign('teacher', $teacher);
        $location_teacher = \app\index\model\Location::find($teacher['location']);
        $location_student = \app\index\model\Location::find($student['location']);
        $this->assign('location_student', $location_student);
        $this->assign('location_teacher', $location_teacher);

        $order = \app\index\model\Order::find($info['order_id']);
        $this->assign('order', $order);

        return $this->fetch();
    }

    //退款管理
    public function refund()
    {
        $map = $this->getMap();
        $map[] = ['i.order_status', 'eq', 4];

        $list = Orderitems::get_list($map);
        $this->assign('data_list', $list);

        $res = Orderitems::alias('i')->field('sum(money) as moneys')->where($map)
        ->join('cms_order o', 'i.order_id = o.id')
        ->join('cms_users t', 'i.teacher_id = t.id')
        ->join('cms_users s', 'i.user_id = s.id')->find();
        $count = $res['moneys'];
        $this->assign('moneys', $count);

        return $this->fetch();
    }

    //订单支付记录
    public function pays()
    {
        $pay_types = config('custom.pay_types');
        $pay_types[0] = 'Balance';
        $this->assign('pay_types', $pay_types);
        $map = $this->getMap();
        $map[] = ['pay_status', 'eq', 1];

        $list = OrderModel::alias('o')->field('o.*,username')
            ->join('cms_users u', 'user_id = u.id')->where($map)->order('o.id desc')->paginate();
        $this->assign('data_list', $list);

        $count = OrderModel::alias('o')->join('cms_users u', 'user_id = u.id')->where($map)->field('sum(order_amount) as moneys, sum(user_money) as user_moneys')->find();
        $this->assign('count', $count);

        return $this->fetch();
    }

}