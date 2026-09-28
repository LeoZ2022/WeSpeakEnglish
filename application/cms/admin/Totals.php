<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2021/5/3
 * Time: 16:55
 */

namespace app\cms\admin;

use app\admin\controller\Admin;
use app\cms\model\Totals as TotalsModel;
use app\index\model\Orderitems;
use app\index\model\Users;

class Totals extends Admin
{

    public function index()
    {
        $date_start = input('date_start', date('Y-m-d', strtotime('-7 day')));
        $date_end = input('date_end', date('Y-m-d'));
        $this->assign('date_start', $date_start);
        $this->assign('date_end', $date_end);
        $date_end .= ' 23:59:59';

        $time_start = strtotime($date_start);
        $time_end = strtotime($date_end);
        $this->assign('time_start', $time_start);
        $this->assign('time_end', $time_end);

        return $this->fetch();
    }

    //人数统计
    public function ajax_member_cnt()
    {
       $ret = TotalsModel::member_cnt();

        echo json_encode($ret);
    }

    //老师可用时间+学生买课
    public function worktimes()
    {
        $data = TotalsModel::worktimes();
        echo json_encode($data);
    }

    //查询没买课人数	提交未付款人数	提交2次没成功	老师缺勤	缺勤率	学生缺勤	缺勤率	老师邀请码	学生邀请码	聊天数，进入房间就算	成功完成30分钟数	IM使用数
    public function counts1()
    {
        $ret = TotalsModel::counts1();
        echo json_encode($ret);
    }

    //新生买课数，新老师设置天数
    public function active_nums()
    {
        $ret = TotalsModel::active_nums();

        $this->assign('buy_alls', $ret['buy_alls']);
        $this->assign('buy_cnts', $ret['buy_cnts']);
        $this->assign('class_alls', $ret['class_alls']);
        $this->assign('class_cnts', $ret['class_cnts']);
        return $this->fetch();
    }

    //买课历史记录
    public function buy_history()
    {
        if ($this->request->isAjax()) {
            $ret = TotalsModel::buy_history();
            $this->assign('datas', $ret);
//            print_r($ret);
            return $this->fetch('buy_history_ajax');
        }
        $addmap = [];
        $date_start = input('date_start');
        $date_end = input('date_end');
        if ($date_start && $date_end) {
            $date_end .= ' 23:59:59';

            $time_start = strtotime($date_start);
            $time_end = strtotime($date_end);
            $addmap = [['create_time', 'between', [$time_start, $time_end]]];
        }
        $all = Orderitems::where($addmap)->where('order_status', 'between', [1,3])->count();
        $ren = Orderitems::field('user_id')->where($addmap)->where('order_status', 'between', [1,3])->group('user_id')->select();
        $avg = 0;
        if ($all && $ren) {
            $avg = round($all / count($ren),1);
        }
        $this->assign('avg', $avg);
        $this->assign('all', $all);

        return $this->fetch();
    }

    //老师上课历史记录
    public function teacher_history()
    {
        if ($this->request->isAjax()) {
            $ret = TotalsModel::teacher_history();
            $this->assign('datas', $ret);
//            print_r($ret);
            return $this->fetch('teacher_history_ajax');
        }
        $addmap = [];
        $date_start = input('date_start');
        $date_end = input('date_end');
        if ($date_start && $date_end) {
            $date_end .= ' 23:59:59';

            $time_start = strtotime($date_start);
            $time_end = strtotime($date_end);
            $addmap = [['create_time', 'between', [$time_start, $time_end]]];
        }
        $all = Orderitems::where($addmap)->where('order_status', 'between', [1,3])->count();
        $ren = Orderitems::field('teacher_id')->where($addmap)->where('order_status', 'eq', 2)->group('teacher_id')->select();

        $ren2 = Orderitems::field('teacher_id')->where($addmap)->where('order_status', 'between', [1,3])->group('teacher_id')->select();
        $avg = 0;
        if ($all && $ren) {
            $avg = round($all / count($ren),1);
        }
        $this->assign('avg', $avg);

        $avg = 0;
        if ($all && $ren2) {
            $avg = round($all / count($ren2),1);
        }
        $this->assign('avg2', $avg);
        $this->assign('all', $all);

        return $this->fetch();
    }

    //财务统计
    public function moneys()
    {
        if ($this->request->isAjax()) {
            $map = $this->getMap();
            $ret = TotalsModel::moneys($map);
            $this->assign('datas', $ret);
//            print_r($ret);
            return $this->fetch('moneys_ajax');
        }
        return $this->fetch();
    }

    //机构统计
    public function partner()
    {
        $type_id = input('user_type', 1);
        $map = $this->getMap();
        $map[] = ['status', 'eq', 1];
        $map[] = ['is_partner', 'eq', 1];
        $map[] = ['user_type', 'eq', $type_id];
        $total = Users::field('sum(commission_frozen) as moneys,sum(commission_cashout) as cashout')->where($map)->find();
        $list = Users::field('id, username, email, user_type,price,price_partner,commission_frozen,commission_cashout')->where($map)->order('id desc')->paginate();
        $nlist = $list;
        $list = $list->toArray();
        $list = $list['data'];
        foreach ($list as $k => $row) {
            $list[$k]['childs'] = Users::where(['partner_id' => $row['id'], 'status' => 1])->count();
            $list[$k]['actived'] = Users::where(['partner_id' => $row['id'], 'status' => 1])->where('partner_id', 'gt', 0)->count();
        }
        $this->assign('datalist', $list);
        $this->assign('list', $nlist);
        $this->assign('user_type', $type_id);
        $this->assign('total', $total);
        return $this->fetch();
    }

}