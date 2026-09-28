<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/10/24
 * Time: 11:49
 */

namespace app\cms\admin;

use app\admin\controller\Admin;
use app\index\model\Withdrawal as DoModel;

class Withdrawal extends Admin
{

    public function index()
    {
        $map = $this->getMap();

        $order = input('order', 'o.id desc');
        $state_id = input('state_id');
        $is_partner = input('is_partner');
        if ($state_id) {
            $map[] = ['o.state', 'eq', $state_id - 1];
        }
        $is_partner = input('is_partner', 0);
        if ($is_partner) {
            $map[] = ['is_partner', 'eq', $is_partner -1];
        }
        $list = DoModel::alias('o')->field('o.*, username, is_partner')->join('cms_users u', 'user_id = u.id')->where($map)->order($order)->paginate();
        $this->assign('data_list', $list);
        $this->assign('state_id', $state_id);
        $this->assign('is_partner', $is_partner);

        $status_list = config('custom.withdraw_status_cn');
        $this->assign('status_list',$status_list);

        $res = DoModel::alias('o')->join('cms_users u', 'user_id = u.id')->where($map)->field('sum(money) as moneys')->find();
        $count = $res['moneys'];
        $this->assign('moneys', $count);

        return $this->fetch();
    }

    public function check($id = 0)
    {
        $info = DoModel::find($id);
        if (!$info) {
            $this->error('找不到提现信息');
        }
        $act = input('act', 'yes');
        if ($act == 'yes') {
            $state = 1;
        } else {
            $state = 2;
        }
        $ret = (new DoModel())->confirm($info, $state);
        echo json_encode($ret);
    }

}