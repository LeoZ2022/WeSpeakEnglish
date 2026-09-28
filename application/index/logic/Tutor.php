<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/10/30
 * Time: 15:42
 */

namespace app\index\logic;

use app\index\model\Location;
use app\index\model\Users;

class Tutor
{

    /**
     * 获取老师列表
     * @param int $timezone 会员所在的时区
     * @throws \think\exception\DbException
     */
    public function get_list($location_current)
    {
        $location = input('location');
        $date_start = input('date_start');
        $date_end = input('date_end');
        $times = input('times');
        $money_min = input('money_min', 0, 'intval');
        $money_max = input('money_max', 0, 'intval');
        $orderby = input('order');
        $name = input('keyword');

        $paras = [];

        $order = 'id desc';
        if ($orderby == 'desc') {
            $paras['order'] = $orderby;
            $order = 'star asc';
        } elseif ($orderby == 'asc') {
            $paras['order'] = $orderby;
            $order = 'star desc';
        }
        $map = [];
        $map[] = ['is_partner', 'eq', 0];
        $map[] = ['is_locked', 'eq', 0];
        $map[] = ['close_state', 'eq', 0];
        $map[] = ['user_type', 'eq', 2];
        $map[] = ['u.status', 'eq', 1];
        $map[] = ['email_verify', 'eq', 1];
        $model = new Users();
        $dialTable = $model->order($order)->buildSql();//先排序
        $obj = $model->table($dialTable .'as u')->field('u.*, c.ico_file, country_name,location_name,timezone')
            ->join('cms_location l', 'location = l.id')
            ->join('cms_country c', 'country_id = c.id', 'left')
            ->join('cms_daytime d', 'user_id = u.id and datetime>'.time())->where($map);
        // and booked=0
        if ($location) {
            foreach ($location as $k=>$v) {
                $location[$k] = intval($v);
            }
            $paras['location'] = $location;
            $lo = implode(',', $location);
            $obj->whereRaw("FIND_IN_SET(country_id,'$lo')");
        }
        if ($date_start && $date_end) {
            $paras['date_start'] = $date_start;
            $paras['date_end'] = $date_end;
            $time_start = date_to_time($date_start, $location_current);
            $time_end = date_to_time($date_end, $location_current);
            $obj->where('datetime', 'between', [$time_start, $time_end]);
        } elseif ($date_start) {
            $paras['date_start'] = $date_start;
            $time_start = date_to_time($date_start, $location_current);
            $obj->where('datetime', 'egt', $time_start);
        } elseif ($date_end) {
            $paras['date_end'] = $date_end;
            $time_end = date_to_time($date_end, $location_current);
            $obj->where('datetime', 'elt', $time_end);
        }
        if ($times) {
            $arr = explode(',', $times);
            $temep = [];
            foreach ($arr as $t) {
                $hour = intval($t * 0.5);
                if ($hour < 10) {
                    $hour = '0'.$hour;
                }
                if ($t%2 == 0) {
                    $hour .= ':00';
                } else {
                    $hour .= ':30';
                }
                $temep[] = $hour;
            }
            $times = implode(',', $temep);
            $paras['times'] = $times;
            $location_default = config('custom.server_imezone');
            if ($location_default == $location_current['timezone']) {
                $obj->where("FIND_IN_SET(FROM_UNIXTIME(datetime, '%H:%i'), '$times')");
            } else {
                $time_sub = ($location_current['timezone'] - $location_default) * 3600;
                $obj->where("FIND_IN_SET(FROM_UNIXTIME(datetime + ".$time_sub.", '%H:%i'), '$times')");
            }
        }
        $fee = config('price_class_fee');
        if ($money_min && $money_max) {
            $paras['money_max'] = $money_max;
            $paras['money_min'] = $money_min;
            $money1 = $money_min - $fee;
            $money2 = $money_max - $fee;
            $obj->where('price', 'between', [$money1, $money2]);
        } elseif ($money_min) {
            $paras['money_min'] = $money_min;
            $money1 = $money_min - $fee;
            $obj->where('price', 'egt', $money1);
        } elseif ($money_max) {
            $paras['money_max'] = $money_max;
            $money2 = $money_max - $fee;
            $obj->where('price', 'elt', $money2);
        }
        if ($name) {
            $paras['keyword'] = $name;
            $obj->where('username', 'like', "%$name%");
        }
        $list = $obj->group('u.id')->order($order)->paginate(21, false, [
            'query' => $paras
        ]);
//        print_r($list);
//        echo $obj->getLastSql();
        return $list;
    }

}