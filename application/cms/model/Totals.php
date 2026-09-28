<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2021/5/4
 * Time: 15:23
 */

namespace app\cms\model;

use app\index\model\Country;
use app\index\model\Daytime;
use app\index\model\Order;
use app\index\model\Orderitems;
use app\index\model\Users as UserModel;

class Totals extends Model
{

    public static function member_cnt()
    {
        $addmap = [];
        $date_start = input('date_start');
        $date_end = input('date_end');
        if ($date_start && $date_end) {
            $date_end .= ' 23:59:59';

            $time_start = strtotime($date_start);
            $time_end = strtotime($date_end);
            $addmap = [['u.create_time', 'between', [$time_start, $time_end]]];
        }

        $datas1 = [];
        $datas2 = [];

        //老师
        $map = ['u.user_type' => 2, 'u.status'=>1, 'u.email_verify' => 1, 'u.close_state' => 0];
        $cnt = UserModel::alias('u')->where($map)->where($addmap)->count();
        $cnt1 = UserModel::alias('u')->where($map)->where($addmap)->where('has_active=1')->count();
        $cnt2 = $cnt-$cnt1;
        if ($cnt && $cnt1) {
            $per1 = round($cnt1 / $cnt * 100, 2);
        } else {
            $per1 = 0;
        }
        $per2 = 100 - $per1;
        $ret = ['cnt_teacher_all' => $cnt, 'cnt_teacher_has' => $cnt1, 'cnt_teacher_per_has' => $per1.'%', 'cnt_teacher_nohas' => $cnt2, 'cnt_teacher_noper_has'=>$per2.'%'];
        $datas = [];
        $datas[] = ['name' => '设置人数', 'value'=>$cnt1];
        $datas[] = ['name' => '未设置人数', 'value'=>$cnt2];
        $ret['seriesData'] = $datas;

        $datas1[] = $cnt1;
        $datas2[] = $cnt2;

        //学生
        $map = ['u.user_type' => 1, 'u.status'=>1, 'u.email_verify' => 1];
        $cnt = UserModel::alias('u')->where($map)->where($addmap)->where($addmap)->count();
        //买过课的学生
        $cnt1 = UserModel::alias('u')->where($map)->where($addmap)->where('paid_num > 0')->count();
        //有课没上的学生
        $res2 = UserModel::alias('u')->field('u.id')->where($map)->where($addmap)->where('paid_num > 0')
//            ->join('cms_orderitems i', 'user_id = u.id and in_time_student > 0 and (in_time_student-datetime) > 600 ')
            ->join('cms_orderitems i', 'user_id = u.id and order_status=3 ')
            ->group('u.id')->select();
        $cnt2 = count($res2);
        if ($cnt && $cnt1) {
            $per1 = round($cnt1 / $cnt * 100, 2);
        } else {
            $per1 = 0;
        }
        if ($cnt && $cnt2) {
            $per2 = round($cnt2 / $cnt * 100, 2);
        } else {
            $per2 = 0;
        }
        $ret['cnt_student_all'] = $cnt;
        $ret['cnt_student_has'] = $cnt1;
        $ret['cnt_student_per_has'] = $per1.'%';
        $ret['cnt_student_nohas'] = $cnt2;
        $ret['cnt_student_noper_has'] = $per2.'%';

        $datas1[] = $cnt1;
        $datas2[] = $cnt - $cnt1;

        $datas = [];
        $datas[] = ['name' => '买课人数', 'value'=>$cnt1];
        $datas[] = ['name' => '有课没上人数', 'value'=>$cnt2];
        $ret['seriesData_student'] = $datas;

        $ret['line_data1'] = $datas1;
        $ret['line_data2'] = $datas2;
        $ret['cols'] = ['老师', '学生'];
        return $ret;
    }

    public static function worktimes()
    {
        $time30 = strtotime('-1 month');
        $map = [];
        $list = Daytime::where($map)->where(['booked'=>0, 'is_active'=>1])->group('FROM_UNIXTIME(datetime, "%m-%d")')
            ->order('date')->column('FROM_UNIXTIME(datetime, "%m-%d") as date,count( id) as cnt');

        $list2 = Daytime::where($map)->where(['booked'=>1])->group('FROM_UNIXTIME(datetime, "%m-%d")')
            ->order('date')->column('FROM_UNIXTIME(datetime, "%m-%d") as date,count( id) as cnt');
        arsort($list);
        arsort($list2);
        if ($list2 && $list) {
            $all = array($list, $list2);
            $all = $all[0];
        } elseif ($list) {
            $all = $list;
        } elseif ($list2) {
            $all = $list2;
        } else {
            $all = [];
        }
//        print_r($all);print_r($list);print_r($list2);

        $datas1 = [];
        $datas2 = [];
        $cols = [];
        foreach ($all as $k=>$row) {
            $cols[] = $k;
            $datas2[] = isset($list[$k]) ? $list[$k] : 0;
            $datas1[] = isset($list2[$k]) ? $list2[$k] : 0;
        }
        $data = ['cols'=>$cols, 'data1'=>$datas1, 'data2'=>$datas2];
        return $data;
    }

    public static function counts1()
    {
        $time0 = strtotime(date('Y-m-d'));
        //查询没买课人数
        $alls[1] = UserModel::where([ ['search_cnt', 'gt',0], ['paid_num', 'eq', 0] ])->count();
        $cnts[1] = UserModel::where([ ['search_cnt', 'gt',0], ['paid_num', 'eq', 0], ['search_time', 'gt', $time0] ])->count();

        //提交未付款人数
        $alls[2] = UserModel::where([ ['buy_num', 'gt', 0], ['paid_num', 'eq', 0] ])->count();
        $cnts[2] = UserModel::alias('u')->field('DISTINCT u.id')->join('cms_order o', "o.user_id = u.id and o.create_time >= $time0 and pay_status = 0")
            ->where([ ['buy_num', 'gt', 0], ['paid_num', 'eq', 0] ])->count();

        //提交2次没成功
        $alls[3] = UserModel::where([ ['buy_num', 'gt', 2], ['paid_num', 'eq', 0] ])->count();
        $cnts[3] = UserModel::alias('u')->field('DISTINCT u.id')->join('cms_order o', "o.user_id = u.id and o.create_time >= $time0 and pay_status = 0")
            ->where([ ['buy_num', 'gt', 2], ['paid_num', 'eq', 0] ])->count();

        //老师缺勤
        $leave_minute = config('custom.leave_minute') * 60;
        $res = Orderitems::field('DISTINCT teacher_id')->where('learner_time_len', 'egt', $leave_minute)
            ->where('teacher_time_len', 'eq', 0)->select();
        $alls[4] = count($res);
        $res = Orderitems::field('DISTINCT teacher_id')->where('datetime', 'egt', $time0)
            ->where('learner_time_len', 'egt', $leave_minute)->where('teacher_time_len', 'eq', 0)->select();
        $cnts[4] = count($res);
        //缺勤率
        if ($alls[4]) {
            $res = Orderitems::field('DISTINCT teacher_id')->where('order_status>0 and order_status<4')->select();
            $all = count($res);
            $alls[5] = isset($all) ? round($alls[4] / $all * 100, 2) : 0;
        } else {
            $alls[5] = '0';
        }
        $alls[5] .= '%';
        if ($cnts[4]) {
            $res = Orderitems::field('DISTINCT teacher_id')->where('datetime', 'egt', $time0)->where('order_status>0 and order_status<4')->select();
            $all = count($res);
            $cnts[5] = isset($all) ? round($cnts[4] / $all * 100, 2) : 0;
        } else {
            $cnts[5] = 0;
        }
        $cnts[5] .= '%';

        //学生缺勤
        $leave_minute = config('custom.leave_minute') * 60;
        $res = Orderitems::field('DISTINCT user_id')->where('learner_time_len', 'egt', $leave_minute)
            ->where('teacher_time_len', 'eq', 0)->select();
        $alls[6] = count($res);
        $res = Orderitems::field('DISTINCT user_id')->where('datetime', 'egt', $time0)
            ->where('learner_time_len', 'egt', $leave_minute)->where('teacher_time_len', 'eq', 0)->select();
        $cnts[6] = count($res);
        //缺勤率
        if ($alls[6]) {
            $res = Orderitems::field('DISTINCT user_id')->where('order_status>0 and order_status<4')->select();
            $all = count($res);
            $alls[7] = isset($all) ? round($alls[6] / $all * 100, 2) : 0;
        } else {
            $alls[7] = '0';
        }
        $alls[7] .= '%';
        if ($cnts[6]) {
            $res = Orderitems::field('DISTINCT user_id')->where('datetime', 'egt', $time0)->where('order_status>0 and order_status<4')->select();
            $all = count($res);
            $cnts[7] = isset($all) ? round($cnts[6] / $all * 100, 2) : 0;
        } else {
            $cnts[7] = 0;
        }
        $cnts[7] .= '%';

        //老师推荐码
        $map = ['u.status'=>1, 'u.email_verify' => 1];
        $res = UserModel::alias('u')->where($map)
            ->join('cms_users p', 'u.parent_id =p.id and p.user_type=2')->select();
        $alls[8] = count($res);
        $res = UserModel::alias('u')->where($map)->where('u.create_time', 'gt', $time0)
            ->join('cms_users p', 'u.parent_id =p.id and p.user_type=2')->select();
        $cnts[8] = count($res);

        //学生推荐码
        $map = ['u.status'=>1, 'u.email_verify' => 1];
        $res = UserModel::alias('u')->where($map)
            ->join('cms_users p', 'u.parent_id =p.id and p.user_type=1')->select();
        $alls[9] = count($res);
        $res = UserModel::alias('u')->where($map)->where('u.create_time', 'gt', $time0)
            ->join('cms_users p', 'u.parent_id =p.id and p.user_type=1')->select();
        $cnts[9] = count($res);

        //聊天数，进入房间就算
        $alls[10] = Orderitems::where([ ['in_time_student','gt',0], ['in_time_teacher', 'gt', 0] ])->count();
        $cnts[10] = Orderitems::where([ ['in_time_student','gt',0], ['in_time_teacher', 'gt', 0] ])->where('datetime', 'egt', $time0)->count();

        //成功完成
        $alls[11] = Orderitems::where('order_status', 2)->count();
        $cnts[11] = Orderitems::where('order_status', 2)->where('datetime', 'egt', $time0)->count();

        return ['alls' => $alls, 'cnts' => $cnts];
    }

    public static function active_nums()
    {
        $time0 = strtotime(date('Y-m-d'));

        //买课数
        $map = 'order_status >0 and order_status<4';
        $sql = "select cnt, count(*) as num from (select COUNT(*) as cnt from dp_cms_orderitems where $map group by user_id) a group by cnt";
        $res = Orderitems::query($sql);
        $buy_alls = [0,0,0,0,0,0,0,0,0,0,0];
        foreach ($res as $row) {
            if ($row['cnt'] > 9) {
                $buy_alls[10] = $buy_alls[10]+$row['num'];
            } else {
                $buy_alls[$row['cnt']] = $buy_alls[$row['cnt']]+$row['num'];
            }
        }
        $mapU = ['u.status'=>1, 'u.email_verify' => 1, 'paid_num'=>0,'user_type'=>1];
        $buy_alls[0] = UserModel::alias('u')->where($mapU)->count();

        $map = 'order_status >0 and order_status<4 and create_time >= '.$time0;
        $sql = "select cnt, count(*) as num from (select COUNT(*) as cnt from dp_cms_orderitems where $map group by user_id) a group by cnt";
        $res = Orderitems::query($sql);
        $buy_cnts = [0,0,0,0,0,0,0,0,0,0,0];
        foreach ($res as $row) {
            if ($row['cnt'] > 9) {
                $buy_cnts[10] = $buy_cnts[10]+$row['num'];
            } else {
                $buy_cnts[$row['cnt']] = $buy_cnts[$row['cnt']]+$row['num'];
            }
        }
        $mapU = ['u.status'=>1, 'u.email_verify' => 1, 'paid_num'=>0,'user_type'=>1];
        $buy_cnts[0] = UserModel::alias('u')->where($mapU)->where('create_time', 'egt', $time0)->count();

        //老师设置天数
        $sql = "select cnt, count(*) as num from (select COUNT(*) as cnt from dp_cms_daytime group by user_id, date) a group by cnt;";
        $res = Orderitems::query($sql);
        $class_alls = [0,0,0,0,0,0,0,0,0,0,0];
        foreach ($res as $row) {
            if ($row['cnt'] > 9) {
                $class_alls[10] = $class_alls[10]+$row['num'];
            } else {
                $class_alls[$row['cnt']] = $class_alls[$row['cnt']]+$row['num'];
            }
        }
        $mapU = ['u.status'=>1, 'u.email_verify' => 1, 'has_active'=>0,'user_type'=>2];
        $class_alls[0] = UserModel::alias('u')->where($mapU)->count();

        $map = 'datetime >= '.$time0;
        $sql = "select cnt, count(*) as num from (select COUNT(*) as cnt from dp_cms_daytime where $map group by user_id, date) a group by cnt;";
        $res = Orderitems::query($sql);
        $class_cnts = [0,0,0,0,0,0,0,0,0,0,0];
        foreach ($res as $row) {
            if ($row['cnt'] > 9) {
                $class_cnts[10] = $class_cnts[10]+$row['num'];
            } else {
                $class_cnts[$row['cnt']] = $class_cnts[$row['cnt']]+$row['num'];
            }
        }
        $mapU = ['u.status'=>1, 'u.email_verify' => 1, 'has_active'=>0,'user_type'=>2];
        $class_cnts[0] = UserModel::alias('u')->where($mapU)->where('create_time', 'egt', $time0)->count();

        return ['buy_alls' => $buy_alls, 'buy_cnts' => $buy_cnts, 'class_alls' => $class_alls, 'class_cnts' => $class_cnts ];
    }

    //买课历史记录
    public static function buy_history()
    {
        $addmap = [];
        $addmap2 = '';
        $date_start = input('date_start');
        $date_end = input('date_end');
        if ($date_start && $date_end) {
            $date_end .= ' 23:59:59';

            $time_start = strtotime($date_start);
            $time_end = strtotime($date_end);
            $addmap = [['create_time', 'between', [$time_start, $time_end]]];
            $addmap2 = " and create_time >= $time_start and create_time <= $time_end";
        }

        //买课数量排名
        $numbers = input('numbers', 100);
        $nums_list = Orderitems::field('user_id,count(id) as cnt')->where($addmap)->where('order_status>0 and order_status<4')
            ->group('user_id')->order('cnt desc')->limit($numbers)->select();
//        echo Orderitems::getlastsql();
        //连续买课
        $sql = "SELECT user_id, days, COUNT(*) AS num
FROM (SELECT user_id,
@cont_day :=
(CASE
WHEN (@last_user_id = user_id AND DATEDIFF(login_dt, @last_dt) = 1) THEN
(@cont_day + 1)
ELSE
1
END) AS days,
(@cont_ix := (@cont_ix + IF(@cont_day = 1, 1, 0))) AS cont_ix,
@last_user_id := user_id,
@last_dt := login_dt
FROM (SELECT user_id, book_date AS login_dt
FROM dp_cms_orderitems where order_status>0 and order_status<4 
ORDER BY user_id, book_date) AS t,
(SELECT @last_user_id := '',
@last_dt := '',
@cont_ix := 0,
@cont_day := 0) AS t1) AS t2 where days>1 and days<7
GROUP BY user_id, days;";
        $res2 = Orderitems::query($sql);
        $num_lianxus = [2=>0,0,0,0,0,0];
        foreach ($res2 as $row) {
            if ($row['days'] > 6) {
                $num_lianxus[7]++;
            } else {
                $num_lianxus[$row['days']]++;
            }
        }
        $sql = "SELECT user_id, days, COUNT(*) AS num
FROM (SELECT user_id,
@cont_day :=
(CASE
WHEN (@last_user_id = user_id AND DATEDIFF(login_dt, @last_dt) = 1) THEN
(@cont_day + 1)
ELSE
1
END) AS days,
(@cont_ix := (@cont_ix + IF(@cont_day = 1, 1, 0))) AS cont_ix,
@last_user_id := user_id,
@last_dt := login_dt
FROM (SELECT user_id, book_date AS login_dt
FROM dp_cms_orderitems where order_status>0 and order_status<4 
ORDER BY user_id, book_date) AS t,
(SELECT @last_user_id := '',
@last_dt := '',
@cont_ix := 0,
@cont_day := 0) AS t1) AS t2 where days>6
GROUP BY user_id;";
        $res2 = Orderitems::query($sql);
        $num_lianxus[7] = count($res2);

        //单日买课人数
        $res = Orderitems::field('user_id')->where($addmap)->where('order_status>0 and order_status<4')->group('user_id')->select();
        $all_cnt = count($res);

        $map = "order_status>0 and order_status<4";
//        $map .= $addmap2;
        $sql = "select cnt,count(DISTINCT user_id) as num from (select user_id,count(*) as cnt from dp_cms_orderitems where $map GROUP BY user_id,book_date having cnt < 5) form1 group by cnt";
        $res = Orderitems::query($sql);
        $sql = "select count(DISTINCT user_id) as num from (select user_id,count(*) as cnt from dp_cms_orderitems where $map GROUP BY user_id,book_date having cnt >4) form1";
        $res5 = Orderitems::query($sql);
//        $res5 = Orderitems::field('user_id,count(*) as cnt')->where($map)->group('user_id,book_date')->having('cnt>4')->select();

//        $nums_all = count( Orderitems::field('user_id')->where($map)->group('user_id')->select() );
        $nums_all = $all_cnt;
        $nums = [1=>['num'=>0],['num'=>0],['num'=>0],['num'=>0],['num'=>0]];
        foreach ($res as $row) {
            $per = $nums_all && $row['num'] ? $row['num'] / $nums_all : 0;
            $nums[$row['cnt']] = ['num' => $row['num'], 'per' => round($per *100, 2) ];
        }
//        $nums[5] = ['num' => count($res5)];
        $nums[5] = ['num' => $res5[0]['num']];
        $per = $nums_all && $nums[5]['num'] ? $nums[5]['num'] / $nums_all : 0;
        $nums[5]['per'] = round($per *100, 2);

        //买课价格人数排序
        $prices = [];
        for ($price = 2; $price < 10; $price = $price+0.5) {
            $res = Orderitems::field('user_id')->where($map)->where('money', $price)->group('user_id')->select();
            $prices[] = ['price'=>$price, 'num' => count($res)];
        }
        $res = Orderitems::field('user_id')->where($map)->where('money', 'egt', 10)->group('user_id')->select();
        $prices[] = ['price'=>10, 'num' => count($res)];

        //注册学生国籍比率，买课学生国籍占该国注册生比率和占在注册人数比率
        $map = "user_type = 1 and email_verify=1 and status = 1 and country_id>0";
//        $map .= $addmap2;
        $reg_all = UserModel::where($map)->count();
        $country_list = Country::cache(true)->column('id, country_name');
        $res = UserModel::field('country_id, count(*) as cnt')->where($map)->group('country_id')->order('cnt desc')->select();
        if ($res) {
            $countrys = $res->toArray();
            foreach ($countrys as $k=>$row) {
                $countrys[$k]['country_name'] = $country_list[$row['country_id']] ?? '';
                $res = UserModel::alias('u')->field('DISTINCT u.id')->join('cms_orderitems i', 'user_id = u.id')->where('country_id', $row['country_id'])->select();
                $cnt = count($res);
                $countrys[$k]['buy_users'] = $cnt;
                $per = $row['cnt'] && $cnt ? round($cnt / $row['cnt'] * 100, 2) : 0;
                $countrys[$k]['per_country'] = $per;
                $per = $cnt && $reg_all ? round($cnt / $reg_all * 100, 2) : 0;
                $countrys[$k]['per_all'] = $per;
            }
        } else {
            $countrys = [];
        }
        $ret = ['nums_list' => $nums_list, 'num_lianxus' => $num_lianxus, 'day_nums' => $nums, 'day_all_cnt' => $all_cnt, 'prices' => $prices, 'countrys' => $countrys];
        echo $reg_all;
        return $ret;
    }

    //老师上课历史记录
    public static function teacher_history()
    {
        $addmap = [];
        $addmap2 = '';
        $date_start = input('date_start');
        $date_end = input('date_end');
        if ($date_start && $date_end) {
            $date_end .= ' 23:59:59';

            $time_start = strtotime($date_start);
            $time_end = strtotime($date_end);
            $addmap = [['create_time', 'between', [$time_start, $time_end]]];
            $addmap2 = " and create_time >= $time_start and create_time <= $time_end";
        }

        //买课数量排名
        $numbers = input('numbers', 100);
        $nums_list = Orderitems::field('teacher_id,count(id) as cnt')->where($addmap)->where('order_status>0 and order_status<4')
            ->group('teacher_id')->order('cnt desc')->limit($numbers)->select();
//        echo Orderitems::getlastsql();

        //单日买课人数
        $res = Orderitems::field('teacher_id')->where($addmap)->where('order_status>0 and order_status<4')->group('teacher_id')->select();
        $all_cnt = count($res);

        $map = "order_status>0 and order_status<4";
//        $map .= $addmap2;
        $sql = "select cnt,count(DISTINCT teacher_id) as num from (select teacher_id,count(*) as cnt from dp_cms_orderitems where $map GROUP BY teacher_id,book_date having cnt < 5) form1 group by cnt";
        $res = Orderitems::query($sql);
//        $sql = "select  from dp_cms_orderitems where $map GROUP BY  having cnt >4";
        $res5 = Orderitems::field('teacher_id,count(*) as cnt')->where($map)->group('teacher_id,book_date')->having('cnt>4')->select();
        $nums_all = count( Orderitems::field('teacher_id')->where($map)->group('teacher_id')->select() );
        $nums = [1=>['num'=>0],['num'=>0],['num'=>0],['num'=>0],['num'=>0]];
        foreach ($res as $row) {
            $per = $nums_all && $row['num'] ? $row['num'] / $nums_all : 0;
            $nums[$row['cnt']] = ['num' => $row['num'], 'per' => round($per *100, 2) ];
        }
        $nums[5] = ['num' => count($res5)];
        $per = $nums_all && $nums[5]['num'] ? $nums[5]['num'] / $nums_all : 0;
        $nums[5]['per'] = round($per *100, 2);

        //买课价格人数排序
        $prices = [];
        for ($price = 2; $price < 10; $price = $price+0.5) {
            $res = Orderitems::field('teacher_id')->where($map)->where('money', $price)->group('teacher_id')->select();
            $prices[] = ['price'=>$price, 'num' => count($res)];
        }
        $res = Orderitems::field('teacher_id')->where($map)->where('money', 'egt', 10)->group('teacher_id')->select();
        $prices[] = ['price'=>10, 'num' => count($res)];

        //注册学生国籍比率，买课学生国籍占该国注册生比率和占在注册人数比率
        $map = "user_type = 2 and email_verify=1 and status = 1 and country_id>0";
//        $map .= $addmap2;
        $reg_all = UserModel::where($map)->count();
        $country_list = Country::cache(true)->column('id, country_name');
        $res = UserModel::field('country_id, count(*) as cnt')->where($map)->group('country_id')->order('cnt desc')->select();
        if ($res) {
            $countrys = $res->toArray();
            foreach ($countrys as $k=>$row) {
                $countrys[$k]['country_name'] = $country_list[$row['country_id']] ?? '';
                $res = UserModel::alias('u')->field('DISTINCT u.id')->join('cms_orderitems i', 'teacher_id = u.id')->where('country_id', $row['country_id'])->select();
                $cnt = count($res);
                $countrys[$k]['buy_users'] = $cnt;
                $per = $row['cnt'] && $cnt ? round($cnt / $row['cnt'] * 100, 2) : 0;
                $countrys[$k]['per_country'] = $per;
                $per = $cnt && $reg_all ? round($cnt / $reg_all * 100, 2) : 0;
                $countrys[$k]['per_all'] = $per;
            }
        } else {
            $countrys = [];
        }
        return ['nums_list' => $nums_list,'day_nums' => $nums, 'day_all_cnt' => $all_cnt, 'prices' => $prices, 'countrys' => $countrys];
    }

    //财务统计
    public static function moneys($datemap)
    {
        $map = ['user_type' => 2, 'status' =>1, 'email_verify' => 1 ];
        $avg = UserModel::field('avg(moneys) as avg')->where($datemap)->where($map)->find();

        $data = ['avg' => $avg];
        $teachers = UserModel::field('id, moneys')->where($datemap)->where($map)->where('moneys', 'gt', 0)->order('moneys desc')->select();
        $data['teachers'] = $teachers;

        $levels = UserModel::field('FLOOR(moneys/5) as money, count(*) as cnt')->where($datemap)->where($map)->order('money desc')->group('money')->select();
        $data['levels'] = $levels;

        //付款方式
        $pay_cnt_1 = Order::where('pay_status', 1)->where($datemap)->where('pay_type', 1)->count();
        $pay_cnt_2 = Order::where('pay_status', 1)->where($datemap)->where('pay_type', 'in', [2,4])->count();
        $pay_cnt_3 = Order::where('pay_status', 1)->where($datemap)->where('pay_type', 3)->count();

        $pay_cnts = $pay_cnt_1 + $pay_cnt_2 + $pay_cnt_3;
        $pay_per_1 = $pay_cnts && $pay_cnt_1 ? round($pay_cnt_1 / $pay_cnts * 100,1) : 0;
        $pay_per_1 .= '%';
        $pay_per_2 = $pay_cnts && $pay_cnt_2 ? round($pay_cnt_2 / $pay_cnts * 100,1) : 0;
        $pay_per_2 .= '%';
        $pay_per_3 = $pay_cnts && $pay_cnt_3 ? round($pay_cnt_3 / $pay_cnts * 100,1) : 0;
        $pay_per_3 .= '%';

        $data['pay_types'] = ['pay_cnt_1' => $pay_cnt_1, 'pay_cnt_2' => $pay_cnt_2, 'pay_cnt_3' => $pay_cnt_3, 'pay_per_1' => $pay_per_1, 'pay_per_2' => $pay_per_2, 'pay_per_3' => $pay_per_3 ];

        return $data;
    }

}