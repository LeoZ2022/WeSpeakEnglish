<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/10/24
 * Time: 11:49
 */

namespace app\cms\admin;

use app\admin\controller\Admin;
use app\common\builder\ZBuilder;
use app\index\model\Donation as DoModel;

class Donation extends Admin
{

    public function index()
    {
        $map = $this->getMap();
        $state_id = input('state_id', 0);
        $is_partner = input('is_partner', 0);
        if ($is_partner) {
            $map[] = ['is_partner', 'eq', $is_partner -1];
        }
        $list = DoModel::alias('o')->field('o.*, username')->join('cms_users u', 'user_id = u.id')->where($map)->order('o.id desc')->paginate();

        $status_list = [0 => '待支付', '已完成'];
        $pay_types = config('custom.pay_types');
        $pay_types[0] = '余额';
        $this->assign('pay_types', $pay_types);
        $this->assign('data_list', $list);
        $this->assign('status_list', $status_list);
        $this->assign('state_id', $state_id);
        $this->assign('is_partner', $is_partner);

        $res = DoModel::alias('o')->join('cms_users u', 'user_id = u.id')->field('sum(user_money) as user_moneys, sum(pay_money) as pay_moneys')->where($map)->find();
        $this->assign('money_count', $res);

        return $this->fetch();
    }

}