<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/9/28
 * Time: 21:51
 */

namespace app\index\model;

use think\Model;

class Daytime extends Model
{

    // 设置当前模型对应的完整数据表名称
    protected $name = 'cms_daytime';

    /**
     * 设置上课时间，一次是一天的
     * @param $date
     * @param $times
     */
    public function adds($date, $times, $days, $user_id)
    {
        $user = Users::find($user_id);
        $location = Location::find($user['location']);
        $obj = new Daytime();
        $time_sels = [];
        $time_ends = [];
        $toweek = input('toweek', 0);
        $reload = 0;
        $date = date('Y-m-d', strtotime($date));
        $cnt = 0;
        $inserts = [];
        foreach ($times as $k => $temp) {
            $t = trim($temp['time']);
            $time = date_to_time($date . ' ' . $t, $location);
            $data = ['user_id' => $user_id, 'datetime' => $time];
            $has = $obj->where($data)->find();
            $time_sels[] = $t;
            $time_ends[] = $temp['time_end'];
            if ($has) {
                continue;
            }
            $data = ['date' => $date, 'time' => $t, 'user_id' => $user_id, 'datetime' => $time];
            $data['time_end'] = trim($temp['time_end']);
//            $data['time_end'] = date('H:i', strtotime('+30 minute', $time));
            $inserts[] = $data;
            $cnt++;
        }
        if ($inserts) {
            $obj->insertAll($inserts);
        }
        if ($cnt) {
            Users::where('id', $user_id)->setField('has_active', 1);
        }
        $obj->where(['date' => $date, 'user_id' => $user_id])->where('time', 'not in', $time_sels)->where('booked',0)->delete();
        $inserts = [];
        if ($toweek) {
            //自动延续
            $reload = 1;
            $autotimes = [];
            foreach ($time_sels as $k=>$time) {
                $autotimes[] = ['time' => $time, 'time_end' => $time_ends[$k]];
            }
            //自动延续6天
            for($i=1;$i<7;$i++) {
                $date = date('Y-m-d', strtotime('+1 day', strtotime($date)));
                foreach ($times as $k => $temp) {
                    $t = trim($temp['time']);
                    $time_sels[] = $t;
                    $time = date_to_time($date . ' ' . $t, $location);
                    $data = ['user_id' => $user_id, 'datetime' => $time];
                    $has = $obj->where($data)->find();
                    if ($has) {
                        continue;
                    }
                    $data = ['date' => $date, 'time' => $t, 'user_id' => $user_id, 'datetime' => $time];
                    $data['time_end'] = trim($temp['time_end']);
                    $time_ends[] = $data['time_end'];
                    $inserts[] = $data;
//                    $obj->insert($data);
                }
            }
            if ($inserts) {
                $obj->insertAll($inserts);
            }
            $map = ['user_id' => $user_id];
            $timetxt = serialize($autotimes);
            $date = date('Y-m-d', strtotime('+1 day', strtotime($date)));
            $update = ['times' => $timetxt, 'nextdate' => $date];
            if($has = Autoweek::where($map)->find()) {
                Autoweek::where('id', $has['id'])->update($update);
            } else {
                $update['user_id'] = $user_id;
                Autoweek::insert($update);
            }
        } elseif ($days) {
            //选项其他日期
            foreach ($days as $k1=>$row) {
                if ($row[5] == 1) {
                    $reload = 1;
                    $date = $row[0].'-'.$row[1].'-'.$row[2];
                    $date = date('Y-m-d', strtotime($date));
                    foreach ($times as $k => $temp) {
                        $t = trim($temp['time']);
                        $time_sels[] = $t;
                        $time = date_to_time($date . ' ' . $t, $location);
                        $data = ['user_id' => $user_id, 'datetime' => $time];
                        $has = $obj->where($data)->find();
                        if ($has) {
                            continue;
                        }
                        $data = ['date' => $date, 'time' => $t, 'user_id' => $user_id, 'datetime' => $time];
//            $data['time_end'] = date('H:i', strtotime('+30 minute', $time));
                        $data['time_end'] = trim($temp['time_end']);
                        $time_ends[] = $data['time_end'];
                        $inserts[] = $data;
//                        $obj->insert($data);
                    }
                }
            }
            if ($inserts) {
                $obj->insertAll($inserts);
            }
        }
        $ret = ['code' => 200, 'msg' => 'success', 'reload' => $reload];
        if ($reload) {
            $location_teacher = $location;
            $now = time();
            $date = time_to_date($now, $location_teacher, 'Y-m-d');
            $datetimes = Daytime::get_teacher_time($user_id, $date);
            $ret['datetimes'] = $datetimes;
        }
        return $ret;
    }

    /**
     * 获得某个老师的30内上课时间
     * @param $teacher_id  老师ID
     */
    public static function get_teacher_time($teacher_id, $date)
    {
        $map = [
            ['user_id', 'eq', $teacher_id],
            ['date', 'egt', $date]
        ];
        $list = self::where($map)->order('datetime')->select();
        return $list;
    }

    /**
     * 获得某个老师的30内上课时间，转换成学生所在日期
     * @param $teacher_id  老师ID
     * @param $timezone  老师所在时区ID
     */
    public static function get_day_30($teacher_id, $location_teacher, $location_member, $user_id)
    {
        $time = strtotime('+12 hour');
//        $date = time_to_date($time, $location_member, 'Y-m-d H:i');
//        $time = strtotime($date);
        $time30 = strtotime('+1 month', $time);
        $time30 = strtotime('+7 day', $time30);

        $map = [
            ['user_id', 'eq', $teacher_id],
            ['booked', 'eq', 0],
            ['datetime', 'between', [$time, $time30]]
        ];
        $map_teacher = [
            ['teacher_id', 'eq', $teacher_id],
        ];
        $map_learner = [
            ['user_id', 'eq', $user_id],
        ];
        $map_user = "teacher_id = '$teacher_id'";
        if ($user_id) {
            $map_user .= " OR user_id = '$user_id'";
        }
        $booked_times = Orderitems::where('datetime', 'between', [$time, $time30])->where($map_user)->where('order_status', 'lt', 4)->column('datetime');
        $list = self::where($map)->order('datetime')->select();
//        echo self::getLastSql();
        foreach ($list as $k=>$row) {
            //前半小时，后半小时
            $daytime0 = $row['datetime'] - 1800;
            $daytime2 = $row['datetime'] + 1800;
            if ( in_array($daytime0, $booked_times) || in_array($daytime2, $booked_times) ) {
                unset($list[$k]);
                continue;
            }
//            $map = "datetime = $daytime0 OR datetime = $daytime2 OR datetime = ".$row['datetime'];
//            $has = Orderitems::where($map_user)->where($map)->where('order_status', 'lt', 4)->find();
//            if ($has) {
//                unset($list[$k]);
//                continue;
//            }
//不需要单独判断学生，上面一起判断了
//            if ($user_id) {
//                $has = Orderitems::where($map_learner)->where($map)->where('order_status', 'between', [0,3])->find();
//                if ($has) {
//                    unset($list[$k]);
//                    continue;
//                }
//            }
            $date_temp = time_to_date($row['datetime'], $location_member, 'Y-m-d H:i');
            $arr = explode(' ', $date_temp);
            $list[$k]['date'] = $arr[0];
            $list[$k]['time'] = $arr[1];
            $list[$k]['datatime2'] = $row['datetime'];
            $list[$k]['datetime'] = strtotime($arr[0]. ' '.$arr[1]);
            $list[$k]['time_end'] = date('H:i', strtotime('+30 minute', strtotime($date_temp)));
        }
        return $list;

        $dates = [];
        $start = $date;
        $i = 1;
        while ($start <= $date_end) {
            $day = date('d', strtotime($start));
            $day = $day < 10 ? '0' . $day : $day;
            $dates[] = ['date' => $start, 'day' => $day];
            $start = date('Y-m-d', strtotime('+' . $i . ' day', $time));
            $i++;
        }
        return $dates;
    }

    //获取某一天的上课时间
    public static function get_times($teacher_id, $user_id, $member_timezone, $date)
    {
        $map = ['user_id' => $teacher_id];
        $map_book = ['teacher_id' => $teacher_id, 'order_status' => 1];
        $map_book_user = ['user_id' => $user_id, 'order_status' => 1];
        $time_add = $member_timezone * 3600;
        $time_start = '00:00';
        $time_end = '23:30';
        $times = [];
        while ($time_start <= $time_end) {
            $can_book = 0;
            $booked = 0;
            $has = self::where($map)->where("from_unixtime(datetime + $time_add, '%Y-%m-%d %H:%i') = '$date $time_start'")->find();
            if ($has) {
                $can_book = 1;
                $booked = Orderitems::where($map_book)->where('datetime', $has['datetime'])->find();
                if ($booked) {
                    $booked = 1;
                } else {
                    $has = Orderitems::where($map_book_user)->where("from_unixtime(datetime + $time_add, '%Y-%m-%d %H:%i') = '$date $time_start'")->find();
                    if ($has) {
                        $booked = 2;
                    } else {
                        $booked = 0;
                    }
                }
            }
            $times[] = ['time' => $time_start, 'can_book' => $can_book, 'booked' => $booked];
            $time_start = date('H:i', strtotime('+30 minute', strtotime($date . ' ' . $time_start)));
//                echo $time_start.'=='.$time_end;exit;
        }
        return $times;
    }

    //获取预约的时间段，生成费用
    public static function get_cart($times, $teacher, $user, $location_login, $weekauto = 0)
    {
        $dayids = [];
        $teacher_id = $teacher['id'];
        $user_id = $user['id'];
        $map_book_teacher = ['teacher_id' => $teacher_id, 'order_status' => 1];
        $map_book_user = ['user_id' => $user_id, 'order_status' => 1];
        $days = Daytime::where('user_id', $teacher_id)->where('id', 'in', $times)->select();
        $datas = [];
        $dates = [];
        $times = [];
        if ($weekauto == 1) {
            $date_sels = [];
            foreach ($days as $k => $day) {
                $date_user = time_to_date($day['datetime'], $location_login, 'Y-m-d H:i');
                $date_arr = explode(' ', $date_user);
                $date_sels[] = $date_arr[0];
            }
        }
        $nowdate = time_to_date(time(), $location_login, 'Y-m-d');
        $nowtime = strtotime('-1 hour');
        foreach ($days as $k => $day) {
            $dayids[] = $day['id'];
            $date_user = time_to_date($day['datetime'], $location_login, 'Y-m-d H:i');
            $date_arr = explode(' ', $date_user);
            //前半小时，后半小时
            $daytime0 = $day['datetime'] - 1800;
            $daytime2 = $day['datetime'] + 1800;
            $map = [ ['datetime', 'in',[$daytime0,$day['datetime'],$daytime2]]];
            $has = Orderitems::where($map_book_teacher)->where($map)->where('order_status', 'between', [0,3])->find();
            if ($has) {
                return ['code' => 201, 'msg' => $day['date'] . ' ' . $day['time'] . ', The tutor is unavailable for that time. Please refresh the availability or choose other time!'];
            }
            $has = Orderitems::where($map_book_user)->where($map)->where('order_status', 'between', [0,3])->find();
            if ($has) {
                return ['code' => 201, 'msg' => $date_arr[0] . ' ' . $date_arr[1] . ' Has an appointment!'];
            }
            if ($nowtime > $day['datetime']) {
                return ['code' => 201, 'msg' => $date_arr[0] . ' ' . $date_arr[1] . ',Please make an appointment at least one hour before that chat start time!'];
            }
            $dates[] = $date_arr[0];
            $time = strtotime($date_user);
            $times[] = $date_arr[1];
            $time_end = strtotime('+30 minute', $time);
            $time_begin = date('H:i',$time);
            $datas[] = date('d M ', strtotime($date_arr[0])) . get_week($time) . ' ' . $time_begin . '-' . date('H:i', $time_end);
            if ($weekauto == 1) {
                //选一周
                $weeks = get_weeks(strtotime($date_arr[0]));
                foreach ($weeks as $date2) {
                    $date = $date2['date'];
                    if (!in_array($date, $date_sels) && $date > $nowdate ) {
                        //不是上面选择的，并且是当天之后的日期
                        $time_new = date_to_time($date.' '.$date_arr[1], $location_login);
                        $has = self::where(['user_id' => $teacher_id, 'datetime' => $time_new])->find();
                        if ($has && $has['booked'] == 0) {
                            $daytime0 = $time_new - 1800;
                            $daytime2 = $time_new + 1800;
                            $book = Orderitems::where([ ['user_id', 'eq', $user['id']], ['datetime', 'in', [$daytime0, $time_new, $daytime2]], ['order_status', 'between', [0,3]] ])->find();
                            if (!$book) {
                                $dates[] = $date;
                                $times[] = $date_arr[1];
                                $datas[] = date('d M ', strtotime($date)) . get_week(strtotime($date)) . ' ' . $time_begin . '-' . date('H:i', $time_end);
                                $dayids[] = $has['id'];
                            }
                        }
                    }
                }
            }
        }
        $cnt = count($dates);
        $money = $cnt * $teacher['price'] + $cnt * config('price_class_fee');
        if ($user['invitation_code_buy'] == 0 && $user['parent_id'] > 0) {
            $invitation_money = config('cfg_invitation_fee');
            if ($invitation_money > $teacher['price'] + config('price_class_fee')) {
                $invitation_money_use = $teacher['price'] + config('price_class_fee');
            } else {
                $invitation_money_use = $invitation_money;
            }
        } else {
            $invitation_money = 0;
            $invitation_money_use = 0;
        }
        $pay_money = $money - $invitation_money_use;
        if ($user['moneys'] > $pay_money) {
            $use_money = $pay_money;
        } else {
            $use_money = $user['moneys'];
        }
        return ['code' => 200, 'msg' => 'success', 'datas' => $datas, 'money' => $money, 'invitation_money' => $invitation_money, 'use_money' => $use_money, 'pay_money' => $pay_money, 'invitation_money_use' => $invitation_money_use, 'dayids' => $dayids, 'dates' => $dates, 'times' => $times];
    }

    //获取老师一周内设置了上课的时间
    public static function get_week_times($teacher_id, $date, $location)
    {
        $time = date_to_time($date, $location);
        $time7 = strtotime('+7 day', $time);
//        echo date('Y-m-d H:i:s', $time)."<br/>";echo date('Y-m-d H:i:s', $time7)."<br/>";
        $times = self::field('datetime, booked')->where('user_id', $teacher_id)->where('datetime', 'between', [$time, $time7])->order('date,time')->select()->toArray();
        foreach ($times as $k=>$row) {
            $date = time_to_date($row['datetime'], $location, 'Y-m-d H:i');
            $arr = explode(' ', $date);
            $times[$k]['date'] = $arr[0];
            $times[$k]['time'] = $arr[1];
            $times[$k]['time_end'] = date('H:i', strtotime('+30 minute', strtotime($date) ));
        }
        return $times;
    }

    public static function clearar($uid)
    {
        self::where('user_id', $uid)->where('booked', 0)->delete();
        Autoweek::where('user_id', $uid)->delete();
    }

}