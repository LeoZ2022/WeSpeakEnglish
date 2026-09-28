<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/11/3
 * Time: 9:55
 */

namespace app\cms\admin;

use app\admin\controller\Admin;
use app\index\model\Device;
use app\index\model\Location;
use app\index\model\Orderitems;
use app\index\model\Payment;
use app\index\model\Users;

class Chart extends Admin
{

    //支付金额
    public function moneys()
    {
        return $this->fetch();
    }

    //支付金额数据
    public function ajax_moneys()
    {
        $date_start = input('_filter_time_from', date('Y-m-01'));
        $date_end = input('_filter_time_to', date('Y-m-d'));
        $type = input('type', 1);
        $year_num = input('year_num');
        $year = input('year');
        $map = [ ['pay_status', 'eq', 1]];
        if ($date_start && $date_end) {
            $map[] = ['create_time', 'between time', [$date_start, $date_end. '23:59:59']];
        } elseif ($date_start) {
            $map[] = ['create_time', 'egt', strtotime($date_start)];
        } elseif ($date_end) {
            $map[] = ['create_time', 'elt', strtotime($date_end.' 23:59:59')];
        }
        $dw = '';
        if ($type==3) {
            //按年
            $dw = '年';
            $user_list1 = Payment::where($map)->group('from_unixtime(create_time,"%Y")')->column('from_unixtime(create_time,"%Y") as txt, sum(money) as cnt');
        }elseif($type == 2) {
            //按月
            $dw = '月';
            $user_list1 = Payment::where($map)->group('from_unixtime(create_time,"%m")')->column('from_unixtime(create_time,"%m") as txt, sum(money) as cnt');
        } else {
            //按天
            $user_list1 = Payment::where($map)->group('from_unixtime(create_time,"%Y-%m-%d")')->column('from_unixtime(create_time,"%m-%d") as txt, sum(money) as cnt');
        }
        $titles = [date('Y', strtotime($date_start))];
        $datas = [];
        $cols = [];
        $datas[0] = [];
        foreach ($user_list1 as $k=>$val){
            $cols[] = $k;
            $datas[0][$k] = $val;
        }
        if ($year) {
            $map = [ ['pay_status', 'eq', 1]];
            //指定年
            if ($date_start && $date_end) {
                $date_start = $year.substr($date_start, 4);
                $date_end = $year.substr($date_end, 4);
                $map[] = ['create_time', 'between time', [$date_start, $date_end. ' 23:59:59']];
            } elseif ($date_start) {
                $date_start = $year.substr($date_start, 4);
                $map[] = ['create_time', 'egt', strtotime($date_start)];
            } elseif ($date_end) {
                $date_end = $year.substr($date_end, 4);
                $map[] = ['create_time', 'elt', strtotime($date_end.' 23:59:59')];
            }
            if ($type==3) {
                //按年
                $user_list2 = Payment::where($map)->group('from_unixtime(create_time,"%Y")')->column('from_unixtime(create_time,"%Y") as txt, sum(money) as cnt');
            }elseif($type == 2) {
                //按月
                $user_list2 = Payment::where($map)->group('from_unixtime(create_time,"%Y-%m")')->column('from_unixtime(create_time,"%m") as txt, sum(money) as cnt');
            } else {
                //按天
                $user_list2 = Payment::where($map)->group('from_unixtime(create_time,"%Y-%m-%d")')->column('from_unixtime(create_time,"%m-%d") as txt, sum(money) as cnt');
            }
            $titles[] = $year;
            $datas[1] = [];
            foreach ($user_list2 as $k=>$val){
                if (!in_array($k, $cols)) {
                    $cols[] = $k;
                }
                $datas[1][$k] = $val;
            }
        } elseif ($year_num) {
            //过往几年
            for($i=1; $i<=$year_num; $i++){
                $map = [ ['pay_status', 'eq', 1]];
                $year = date('Y', strtotime('-'.$i.' year'));

                if ($date_start && $date_end) {
                    $date_start = $year.substr($date_start, 4);
                    $date_end = $year.substr($date_end, 4);
                    $map[] = ['create_time', 'between time', [$date_start, $date_end. ' 23:59:59']];
                } elseif ($date_start) {
                    $date_start = $year.substr($date_start, 4);
                    $map[] = ['create_time', 'egt', strtotime($date_start)];
                } elseif ($date_end) {
                    $date_end = $year.substr($date_end, 4);
                    $map[] = ['create_time', 'elt', strtotime($date_end.' 23:59:59')];
                }
                if ($type==3) {
                    //按年
                    $user_list2 = Payment::where($map)->group('from_unixtime(create_time,"%Y")')->column('from_unixtime(create_time,"%Y") as txt, sum(money) as cnt');
                }elseif($type == 2) {
                    //按月
                    $user_list2 = Payment::where($map)->group('from_unixtime(create_time,"%Y-%m")')->column('from_unixtime(create_time,"%m") as txt, sum(money) as cnt');
                } else {
                    //按天
                    $user_list2 = Payment::where($map)->group('from_unixtime(create_time,"%Y-%m-%d")')->column('from_unixtime(create_time,"%m-%d") as txt, sum(money) as cnt');
                }
//                echo $year."<br/>";print_r($map);echo Users::getlastsql()."<br/>";
                $titles[] = $year;
                $datas[$i] = [];
                foreach ($user_list2 as $k=>$val){
                    if (!in_array($k, $cols)) {
                        $cols[] = $k;
                    }
                    $datas[$i][$k] = $val;
                }
            }
        }
        $ndatas = [];
        foreach ($datas as $k=>$arr) {
            $ndata = [];
            foreach ($cols as $date) {
                $ndata[] = $arr[$date] ?? 0;
            }
            $ndatas[$k] = $ndata;
        }
        $series = [];
        foreach ($ndatas as $k => $data) {
            $series[] = ['name' => $titles[$k], 'type' => 'bar', 'data' => $data ];
        }
        foreach ($cols as $k=>$v) {
            $cols[$k] = $v.$dw;
        }
        $result = ['titles' => $titles, 'cols' => $cols, 'data' => $ndatas, 'series' => $series];

        echo json_encode($result);
    }

    //注册人数，按国家--饼图
    public function ajax_register()
    {
        $map = $this->getMap();
        $list = Users::alias('o')->where('status = 1')->where($map)->group('country_id')
            ->column('country_id, count(if(o.user_type=1,1,null)) as cnt_1, count(if(o.user_type=2,1,null)) as cnt_2');
        $locations = \app\index\model\Country::where(['status' => 1])->column('id,country_name');
        $cols = [];
        $datas = [];
        foreach ($locations as $id => $name)
        {
            if (!isset($list[$id])) {
                continue;
            }
            $cols[] = $name;
            $datas[0][] = ['name' => $name, 'value'=>isset($list[$id]) ? $list[$id]['cnt_2'] : 0];
            $datas[1][] = ['name' => $name, 'value'=>isset($list[$id]) ? $list[$id]['cnt_1'] : 0];
        }
        $result = [$cols, $datas[0], $datas[1]];
        echo json_encode($result);
    }

    //上课终端
    public function terminal()
    {
        $status_id = config('custom.status_completed');
        $list = Device::alias('d')->field('d.*, count(*) as cnt')
            ->join('cms_orderitems i', "teacher_device = d.sn and order_status='$status_id'")
            ->group('teacher_device')->select()->toArray();
        $this->assign('list', $list);
//        echo Device::getlastsql();print_r($list);exit;

        return $this->fetch();
    }

    //上课终端--饼形
    public function ajax_terminal()
    {
        $col_list = Device::column('sn, device');
        $list = Orderitems::where('teacher_device', 'not null')->group('teacher_device')->column('teacher_device, count(*) as cnt');
        $cols = [];
        $datas = [];
        foreach ($col_list as $id => $name)
        {
            $cols[] = $name;
            $datas[] = ['name' => $name, 'value'=>isset($list[$id]) ? $list[$id] : 0];
        }
        $result = ['cols' => $cols, 'seriesData' => $datas];
        echo json_encode($result);
    }

    //新注册数和付款金额
    public function rens()
    {
        return $this->fetch();
    }

    //注册数---柱形
    public function rens_ajax($user_type = 0)
    {
        $date_start = input('_filter_time_from', date('Y-m-01'));
        $date_end = input('_filter_time_to', date('Y-m-d'));
        $type = input('type', 1);
        $year_num = input('year_num');
        $year = input('year');
        $map = [];
        if ($user_type) {
            $map[] = ['user_type', 'eq', $user_type];
        }
        if ($date_start && $date_end) {
            $map[] = ['create_time', 'between time', [$date_start, $date_end. '23:59:59']];
        } elseif ($date_start) {
            $map[] = ['create_time', 'egt', strtotime($date_start)];
        } elseif ($date_end) {
            $map[] = ['create_time', 'elt', strtotime($date_end.' 23:59:59')];
        }
        $dw = '';
        if ($type==3) {
            //按年
            $dw = '年';
            $user_list1 = Users::where($map)->group('from_unixtime(create_time,"%Y")')->column('from_unixtime(create_time,"%Y") as txt, count(*) as cnt');
        }elseif($type == 2) {
            //按月
            $dw = '月';
            $user_list1 = Users::where($map)->group('from_unixtime(create_time,"%m")')->column('from_unixtime(create_time,"%m") as txt, count(*) as cnt');
        } else {
            //按天
            $user_list1 = Users::where($map)->group('from_unixtime(create_time,"%Y-%m-%d")')->column('from_unixtime(create_time,"%m-%d") as txt, count(*) as cnt');
        }
//        echo Users::getlastsql();
        $titles = [date('Y')];
        $datas = [];
        $cols = [];
        $datas[0] = [];
        foreach ($user_list1 as $k=>$val){
            $cols[] = $k;
            $datas[0][$k] = $val;
        }
        if ($year) {
            $map = [];
            if ($user_type) {
                $map[] = ['user_type', 'eq', $user_type];
            }
            //指定年
            if ($date_start && $date_end) {
                $date_start = $year.substr($date_start, 4);
                $date_end = $year.substr($date_end, 4);
                $map[] = ['create_time', 'between time', [$date_start, $date_end. ' 23:59:59']];
            } elseif ($date_start) {
                $date_start = $year.substr($date_start, 4);
                $map[] = ['create_time', 'egt', strtotime($date_start)];
            } elseif ($date_end) {
                $date_end = $year.substr($date_end, 4);
                $map[] = ['create_time', 'elt', strtotime($date_end.' 23:59:59')];
            }
            if ($type==3) {
                //按年
                $user_list2 = Users::where($map)->group('from_unixtime(create_time,"%Y")')->column('from_unixtime(create_time,"%Y") as txt, count(*) as cnt');
            }elseif($type == 2) {
                //按月
                $user_list2 = Users::where($map)->group('from_unixtime(create_time,"%Y-%m")')->column('from_unixtime(create_time,"%m") as txt, count(*) as cnt');
            } else {
                //按天
                $user_list2 = Users::where($map)->group('from_unixtime(create_time,"%Y-%m-%d")')->column('from_unixtime(create_time,"%m-%d") as txt, count(*) as cnt');
            }
            $titles[] = $year;
            $datas[1] = [];
            foreach ($user_list2 as $k=>$val){
                if (!in_array($k, $cols)) {
                    $cols[] = $k;
                }
                $datas[1][$k] = $val;
            }
        } elseif ($year_num) {
            //过往几年
            for($i=1; $i<=$year_num; $i++){
                $map = [];
                if ($user_type) {
                    $map[] = ['user_type', 'eq', $user_type];
                }
                $year = date('Y', strtotime('-'.$i.' year'));

                if ($date_start && $date_end) {
                    $date_start = $year.substr($date_start, 4);
                    $date_end = $year.substr($date_end, 4);
                    $map[] = ['create_time', 'between time', [$date_start, $date_end. ' 23:59:59']];
                } elseif ($date_start) {
                    $date_start = $year.substr($date_start, 4);
                    $map[] = ['create_time', 'egt', strtotime($date_start)];
                } elseif ($date_end) {
                    $date_end = $year.substr($date_end, 4);
                    $map[] = ['create_time', 'elt', strtotime($date_end.' 23:59:59')];
                }
                if ($type==3) {
                    //按年
                    $user_list2 = Users::where($map)->group('from_unixtime(create_time,"%Y")')->column('from_unixtime(create_time,"%Y") as txt, count(*) as cnt');
                }elseif($type == 2) {
                    //按月
                    $user_list2 = Users::where($map)->group('from_unixtime(create_time,"%Y-%m")')->column('from_unixtime(create_time,"%m") as txt, count(*) as cnt');
                } else {
                    //按天
                    $user_list2 = Users::where($map)->group('from_unixtime(create_time,"%Y-%m-%d")')->column('from_unixtime(create_time,"%m-%d") as txt, count(*) as cnt');
                }
//                echo $year."<br/>";print_r($map);echo Users::getlastsql()."<br/>";
                $titles[] = $year;
                $datas[$i] = [];
                foreach ($user_list2 as $k=>$val){
                    if (!in_array($k, $cols)) {
                        $cols[] = $k;
                    }
                    $datas[$i][$k] = $val;
                }
            }
        }
        $ndatas = [];
        foreach ($datas as $k=>$arr) {
            $ndata = [];
            foreach ($cols as $date) {
                $ndata[] = $arr[$date] ?? 0;
            }
            $ndatas[$k] = $ndata;
        }
        $series = [];
        foreach ($ndatas as $k => $data) {
            $series[] = ['name' => $titles[$k], 'type' => 'bar', 'data' => $data ];
        }
        foreach ($cols as $k=>$v) {
            $cols[$k] = $v.$dw;
        }
        $result = ['titles' => $titles, 'cols' => $cols, 'data' => $ndatas, 'series' => $series];

       echo json_encode($result);
    }

    //活跃查询
    public function actived()
    {
        $map = [ ['o.order_status', 'in', [1,2,3]] ];
        $user_type = input('user_type', 1);
        $keyword = input('keyword');
        if ($keyword) {
            $map[] = ['username', 'like', "%$keyword%"];
        }
        if ($user_type) {
            $map[] = ['user_type', 'eq', $user_type];
        }
        $list = Orderitems::get_actived($map, $user_type);
        $this->assign('list', $list);

        return $this->fetch();
    }

}