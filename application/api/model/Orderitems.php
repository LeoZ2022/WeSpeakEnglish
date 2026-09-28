<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2021/5/11
 * Time: 9:55
 */

namespace app\api\model;

use app\index\model\NoticeTemplate;
use app\index\service\Websocket;
use think\Db;
use think\Exception;
use think\Model;

class Orderitems extends Model
{

    protected $cancel_type = ['', '学生取消', '老师取消', '双方迟到', '老师迟到', '学生迟到', '老师离开超过5分钟', '学生离开超过5分钟'];

    // 设置当前模型对应的完整数据表名称
    protected $name = 'cms_orderitems';

    //进入房间，获取单个课程
    public static function get_one($user, $id)
    {
        if ($user['user_type'] == 1) {
            $mapkey = 'user_id';
//            $datecol = 'book_date';
        } else {
            $mapkey = 'teacher_id';
//            $datecol = 'book_date_teacher';
        }
        $map = [ [$mapkey, 'eq', $user['id']], ['id', 'eq', $id] ];
        $info = Orderitems::where($map)->find();
        if (!$info) {
            return ['code' => 201, 'msg' => 'Chat not found'];
        } else {
            $info['now_time'] = time();
            $info['timelen'] = 30;
            $teacher = Users::field('username')->where('id', $info['teacher_id'])->find();
            $student = Users::field('username')->where('id', $info['user_id'])->find();
            $info['teacher'] = $teacher;
            $info['student'] = $student;
            $info['cfg_meiyan'] = config('cfg_meiyan');
            return ['code' => 200, 'data'=>$info];
        }
    }

    /*
     * API进入房间
     * id：房间编号
     */
    public static function in_room($user, $id = 0)
    {
        $map = ['class_id' => $id];
        return ['code' => 200];
    }

    /*
     * API退出房间
     * id：房间编号
     * act：操作，1关闭房间，0普通退出
     */
    public static function out_room($user, $id = 0, $act = 0)
    {
        $map = ['class_id' => $id];
        return ['code' => 200];
    }

    /**
     * 课程列表
     * type：1今天待上课，2将要上课的，3已完成的
     */
    public static function get_list($user, $type)
    {
        $sn = input('sn');
        if ($user['user_type'] == 1) {
            $mapkey = 'user_id';
            $datecol = 'book_date';
        } else {
            $mapkey = 'teacher_id';
            $datecol = 'book_date_teacher';
        }
        $map = [ [$mapkey, 'eq', $user['id']] ];
        $time = time();
        $date2 = time_to_date($time, $user['location'], 'Y-m-d H:i:s');
        $dates = explode(' ', $date2);
        $pagesize = input('pagesize', 8);
        if ($sn) {
            $map[] = ['classin_id', 'like', "%$sn%"];
            $map[] = ['order_status', 'gt', 0];
            $map[] = ['datetime', 'egt', strtotime('-30 minute')];
            if ($user['user_type'] == 2) {
                $list = Orderitems::alias('i')->field('i.*, username')
                    ->join('cms_users u', 'u.id = i.user_id')
                    ->where($map)->order('datetime')->limit(24)->select();
            } else {
                $list = Orderitems::alias('i')->field('i.*, username')
                    ->join('cms_users u', 'u.id = i.teacher_id')
                    ->where($map)->order('datetime')->limit(24)->select();
            }
            $count = count($list);
        } else if ($type==1) {
            $map[] = [$datecol, 'eq', $dates[0]];
//            $map[] = [$datecol, 'elt', $dates[0]];
            $map[] = ['order_status', 'eq', 1];
            if ($user['user_type'] == 2) {
                $list = Orderitems::alias('i')->field('i.*, username')
                    ->join('cms_users u', 'u.id = i.user_id')
                    ->where($map)->order('datetime')->paginate($pagesize)->toArray();
            } else {
                $list = Orderitems::alias('i')->field('i.*, username')
                    ->join('cms_users u', 'u.id = i.teacher_id')
                    ->where($map)->order('datetime')->paginate($pagesize)->toArray();
            }
            $count = $list['total'];
            $list = $list['data'];
        } elseif ($type == 2) {
            $map[] = [$datecol, 'gt', $dates[0]];
            $map[] = ['order_status', 'eq', 1];
            $count = Orderitems::field(''.$datecol.' as date')->where($map)->count();
            $months = Orderitems::field(''.$datecol.' as date')->where($map)->order('datetime')->group(''.$datecol.'')->paginate($pagesize)->toArray();
            $months = $months['data'];
            foreach ($months as $k=>$row) {
                if ($user['user_type'] == 1) {
                    $lists = Orderitems::alias('i')->field('i.*, username')
                        ->join('cms_users u', 'u.id = i.teacher_id')
                        ->where($map)->where("".$datecol." = '" . $row['date'] . "'")->order('datetime')->select();
                } else {
                    $lists = Orderitems::alias('i')->field('i.*, username')
                        ->join('cms_users u', 'u.id = i.user_id')
                        ->where($map)->where("".$datecol." = '" . $row['date'] . "'")->order('datetime')->select();
                }
                $months[$k]['lists'] = $lists;
            }
            $list = $months;
        } else {
            $map[] = ['order_status', 'eq', 2];
            $count = Orderitems::field(''.$datecol.' as date')->where($map)->count();
            $months = Orderitems::field(''.$datecol.' as date')->where($map)->order('datetime')->group(''.$datecol.'')->paginate($pagesize)->toArray();
//            echo Orderitems::getlastsql();
            $months = $months['data'];
            foreach ($months as $k=>$row) {
                if ($user['user_type'] == 1) {
                    $list = Orderitems::alias('i')->field('i.*, username')
                        ->join('cms_users u', 'u.id = i.teacher_id', 'left')
                        ->where($map)->where("".$datecol." = '" . $row['date'] . "'")->order('datetime')->select();
                } else {
                    $list = Orderitems::alias('i')->field('i.*, username')
                        ->join('cms_users u', 'u.id = i.user_id', 'left')
                        ->where($map)->where("".$datecol." = '" . $row['date'] . "'")->order('datetime')->select();
                }
                $months[$k]['lists'] = $list;
            }
            $list = $months;
        }
//        echo Orderitems::getlastsql();
        return [$list, $count];
    }

    //评论
    public static function to_comment($class_id, $score, $comment, $user)
    {
        $map = [];
        $time = time();

        if ($user['user_type'] == 1) {
            $map['user_id'] = $user['id'];
            $data = ['comment_student' => $comment, 'score_student' => $score, 'comment_student_time' => $time];
            $key = 'score_student';

        } else {
            $map['teacher_id'] = $user['id'];
            $data = ['comment_teacher' => $comment, 'score_teacher' => $score, 'comment_teacher_time' => $time];
            $key = 'score_teacher';
        }
        $map['id|classin_id'] = $class_id;
        $info = self::where($map)->find();
        if (!$info) {
            return ['code' => 201, 'msg' => 'Chat not found'. self::getlastsql()];
        }
        /*
        if ($info['order_status'] != 2) {
            return ['code' => 201, 'msg' => 'Course not completed'];
        }
        if ($info[$key]) {
            return ['code' => 201, 'msg' => 'Course already commented'];
        }
        */
        if (self::where($map)->update($data)) {



            if ($user['user_type'] == 1) {
                $score = self::where('teacher_id', $info['teacher_id'])->where('score_student', 'gt',0)->field('AVG(score_student) as score')->find();
                $learner = Users::find($info['user_id']);
                $teacher = Users::find($info['teacher_id']);
                $content = file_get_contents("template/comment2tutor.html");
                $finds = ['{comment}' ];
                $repls = [$comment];
                $content = str_replace($finds, $repls, $content);
                send_email($teacher['email'], 'Comment of Partner '.$learner['username'], $content);
                send_email('liyan.zhao@outlook.com', 'Comment of Partner '.$learner['username'], $content);
                $score = round($score['score'], 1);
                Users::where('id', $info['teacher_id'])->setField('star', $score);

            } else {
                $score = self::where('user_id', $info['user_id'])->where('score_teacher', 'gt', 0)->field('AVG(score_teacher) as score')->find();
                $score = round($score['score'], 1);
                Users::where('id', $info['user_id'])->setField('star', $score);
                $learner = Users::find($info['user_id']);
                $teacher = Users::find($info['teacher_id']);
                $content = file_get_contents("template/comment2learner.html");
                $finds = ['{comment}'];
                $repls = [$comment];
                $content = str_replace($finds, $repls, $content);
                send_email($learner['email'], 'Comment of Tutor '.$teacher['username'], $content);
                send_email('liyan.zhao@outlook.com', 'Comment of Tutor '.$teacher['username'], $content);
            }
            return ['code' => 200, 'msg' => 'Comment success'];
        } else {
            return ['code' => 201, 'msg' => 'Comment failure'];
        }

    }

    //进入房间，通知回调
    // 进房    1：正常进房，2：切换网络，3：超时重试，4：跨房连麦进房
    public static function intime($data, $EventType)
    {
//        $map = ['classin_id' => $data['RoomId']];
        $map = ['id' => $data['RoomId']];
        $user_type = 1;
        if (isset($data['UserId'])) {
            //有用户id
            $user = Users::find($data['UserId']);
            if ($user['user_type'] == 2) {
                $key = 'teacher_id';
                $col = 'teacher';
                $key_df = 'user_id';
                $user_type = 2;
            } else {
                $key = 'user_id';
                $key_df = 'teacher_id';
                $col = 'student';
            }
        } elseif (isset($data['Role'])) {
            //有角色
            if ($data['Role'] == 20) {
                $key = 'teacher_id';
                $col = 'teacher';
                $user_type = 2;
            } else {
                $key = 'user_id';
                $col = 'student';
            }
        }
        $user_id = $data['UserId'];
        $map[$key] = $user_id;
        $class = self::where($map)->find();
        $time = time();
        try {
            if ($class) {
                $logs = ['class_id' => $class['id'], 'user_id' => $user_id, 'user_type' => $user_type];
                $has = Roomlogs::where($logs)->order('id desc')->find();
                $logs['create_time'] = $time;
                if ($has && $has['EventType'] == 1 && !$has['out_time']) {
                    Roomlogs::where('id', $has['id'])->update($logs);
                } else {
                    Roomlogs::create($logs);
                }
                if ($class['out_time_' . $col]) {
                    //计算离开合计时间
                    $subs = $time - $class['out_time_' . $col];
                    self::where($map)->setInc('leave_time_' . $col, $subs);
                }
                $update = [];
                $update['last_in_time_'.$col] = $time;
                $class['last_in_time_'.$col] = $time;
                if (!$class['in_time_' . $col]) {
                    //记录第一次进入时间
                    $update['in_time_' . $col] = $time;
                    $class['in_time_' . $col] = $time;
                }
                self::where($map)->update($update);
                //判断是否2个人同时在线
                $class = self::where('id', $class['id'])->find();
                $time = time();
                if ($class['in_time_teacher'] && $class['in_time_student']) {
                    $toids = [$class['teacher_id'], $class['user_id']];

                    //到了上课时间后才发通知
                    //判断对方是不是在线
                    $last = Roomlogs::where(['class_id' => $class['id'], 'user_id' => $class[$key_df]])->order('create_time desc')->find();
                    if ($last && $last['EventType'] == 1) {
                        if (!$class['class_time_begin']) {
                            //如果没有退出的记录，2人都在线，推送课程开始了
                            if ($class['datetime'] <= $time && Orderitems::where('id', $class['id'])->where('class_time_begin', 0)->setField('class_time_begin', $time)) {
                                $class['class_time_begin'] = $time;
                            }
                            Websocket::class_begin($class, $time, $toids);
                        } else {
                            Websocket::class_continue($class, $time, [$user_id], 1);
                            //原来进来的另外发一个消息
                            if ($user_id == $class['teacher_id']) {
                                $uid = $class['user_id'];
                            } else {
                                $uid = $class['teacher_id'];
                            }
                            Websocket::class_continue($class, $time, [$uid]);
                        }
                    } else {
                        Websocket::class_wait($class, [$user_id]);
                    }
                } else {
                    Websocket::class_wait($class, [$user_id]);
                }
            }
        } catch (Exception $e) {
//            echo $e->getMessage();
        }
    }

    //离开房间，通知回调
    // Reason
    // 退房    1：正常退房，2：超时离开，3：房间用户被移出，4：取消连麦退房，5：强杀
    public static function outtime($data, $EventType)
    {
        $Reason = $data['Reason'];
//        $map = ['classin_id' => $data['RoomId']];
        $map = ['id' => $data['RoomId']];
        $user_type = 1;
        $user_type_df = 2;
        $user = Users::find($data['UserId']);
        if ($user['user_type'] == 2) {
            $key = 'teacher_id';
            $col = 'teacher';
            $user_type = 2;
            $user_type_df = 1;
            $key_df = 'user_id';
        } else {
            $key = 'user_id';
            $col = 'student';
            $key_df = 'teacher_id';
        }
        $user_id = $data['UserId'];
        $map[$key] = $user_id;
        $class = self::where($map)->find();
        $time = time();
        try {
            if ($class) {
                $obj = new Roomlogs();

                //最后一次进入的记录，如果也是退出不处理
                $last = Roomlogs::where(['class_id' => $class['id'], 'user_id' => $user_id])->order('create_time desc')->find();
                if ($last && $last['EventType'] == 2) {
                    return true;
                }
                $logs = ['class_id' => $class['id'], 'user_id' => $user_id, 'user_type' => $user_type, 'EventType' => 2, 'create_time' => $time, 'Reason' => $data['Reason']];
                $obj->insertGetId($logs);
                $update = ['out_time_' . $col => $time, 'out_reason_'. $col => $Reason];
                if ($class['datetime'] <= $time) {
                    $update['class_time_end'] = $time;
                }
                self::where($map)->update($update);
                $class['class_time_end'] = $time;
                $class['out_time_' . $col] = $time;

                //如果最后一个事件是进入房间，计算累计上课时长
                if ($last && $last['EventType'] == 1) {
                    if ( $class['datetime'] <= $time) {
                        if ($last['create_time'] < $class['class_time_begin']) {
                            $time_begin = $class['class_time_begin'];
                        } else {
                            $time_begin = $last['create_time'];
                        }
                        $subs = $time - $time_begin;
                        Roomlogs::where('id', $last['id'])->setField('out_time', $time);

                        $col = str_replace('student', 'learner', $col);
                        self::where($map)->setInc($col . '_time_len', $subs);
                    }

                    if ($class['order_status'] == 1) {
                        //判断是不是先离开的，如果是第2个离开的不计算上课时长。最后一次记录是进入还是离开
                        $last_df = Roomlogs::where(['class_id' => $class['id'], 'user_type' => $user_type_df])->order('create_time desc')->find();
                        if ($last_df && $last_df['EventType'] == 1) {
                            if ($class['datetime'] <= $time) {
                                //如果对方没有离开，需要增加上课时长
                                if ($last['create_time'] < $last_df['create_time']) {
                                    $last['create_time'] = $last_df['create_time'];
                                }
                                if ($last['create_time'] < $class['class_time_begin']) {
                                    $time_begin = $class['class_time_begin'];
                                } else {
                                    $time_begin = $last['create_time'];
                                }
                                $subs = $time - $time_begin;
                                self::where($map)->setInc('class_time_len', $subs);
                                $class['class_time_len'] += $subs;
                            }
                            //发socket通话对方要暂停了。非法退出
                            if ($Reason == 2 || $Reason == 5) {
                                Websocket::class_pause($class, $class[$key_df]);
                            } elseif ($Reason == 1) {
                                Websocket::class_leave($class, $class[$key_df]);
                            }
                        }

                        if ($class['class_time_len'] >= 1770) {
                            //上课超过29.5分钟，课程结束20230325 change from 1800 to 1770 to solve last min miscounted
                            $update = ['order_status' => 2, 'clearing_money' => $class['teacher_price'], 'teacher_money' => 1];
                            self::where('id', $class['id'])->update($update);
                            $teacher = Users::find($class['teacher_id']);
                            $learner = Users::find($class['user_id']);
                            self::class_ok($class, $teacher, $learner, $time);
                        }
                    }
                }

            }
        } catch (Exception $e) {
//            echo $e->getMessage();
        }
    }

    //每分钟定时处理
    public static function crontab()
    {
        $time = time();
        $caoshi = 300;
        $time0 = strtotime('-'. $caoshi. ' second');
        //老师离开的时间（多次累积）超过5分钟，房间解散。
        $list = self::where("({$time} - out_time_teacher) > {$caoshi} and out_time_teacher >0 and out_time_teacher > last_in_time_teacher and order_status = 1 and datetime < $time0 and in_time_student>0")->select();
        echo '教师离开5分钟：'.self::getLastSql()."<br/>";print_r($list);echo "<br/>";
        foreach ($list as $row) {
            if ($row['class_time_len'] < 900) {
                //小于15分钟，退款
                $update = ['order_status' => 3, 'cancel_type' => 6, 'refund_money' => $row['money']];
                self::where('id', $row['id'])->update($update);

                Db::name('cms_users')->where('id', $row['user_id'])->setInc('moneys', $row['money']);
                $acc_data = ['user_id' => $row['user_id'], 'type_id' => 3, 'obj_id' => $row['id'], 'money' => $row['money'], 'create_time' => $time];
                $acc_data['descr'] = $row['book_date']. ' '.$row['time_begin'].' - '.$row['time_end']. ' Refund fee.';
                //生成账户流水
                Db::name('cms_account_log')->insertGetId($acc_data);

                //发socket通知
                Websocket::class_exit($row, $row['user_id'], 'class_one_out');
            } elseif ($row['class_time_len'] < 1500) {
                //大于15分钟，小于30分钟，退一半，老师得一半 1800
                $update = ['order_status' => 3, 'cancel_type' => 6, 'refund_money' => $row['money'] /2];
                self::where('id', $row['id'])->update($update);

                Db::name('cms_users')->where('id', $row['user_id'])->setInc('moneys', $row['money'] /2);
                $acc_data = ['user_id' => $row['user_id'], 'type_id' => 3, 'obj_id' => $row['id'], 'money' => $row['money'] /2, 'create_time' => $time];
                $acc_data['descr'] = $row['book_date']. ' '.$row['time_begin'].' - '.$row['time_end']. ' Refund fee.';
                Db::name('cms_account_log')->insertGetId($acc_data);

                Db::name('cms_users')->where('id', $row['teacher_id'])->setInc('moneys', $row['teacher_price'] /2);
                $acc_data = ['user_id' => $row['teacher_id'], 'type_id' => 5, 'obj_id' => $row['id'], 'money' => $row['teacher_price'] /2, 'create_time' => $time];
                Db::name('cms_account_log')->insertGetId($acc_data);

                //发socket通知
                Websocket::class_exit($row, $row['user_id'], 'class_one_out');
            } else {
                //超过30分钟了，正常结束
                $update = ['order_status' => 2, 'clearing_money' => $row['teacher_price'], 'teacher_money' => 1];
                self::where('id', $row['id'])->update($update);
                $teacher = Users::find($row['teacher_id']);
                $learner = Users::find($row['user_id']);
                self::class_ok($row, $teacher, $learner, $time);

                //发socket通知
                Websocket::class_success($row, $row['user_id']);
            }
        }

        //学生离开超过5分钟
        $list = self::where("({$time} - out_time_student) > {$caoshi} and out_time_student >0 and out_time_student > last_in_time_student and order_status = 1 and datetime < $time0 and in_time_teacher>0")->select();
        echo '学生离开5分钟：'.self::getLastSql()."<br/>";print_r($list);echo "<br/>";
        foreach ($list as $row) {
            if ($row['class_time_len'] < 600) {
                //10分钟以内退款
                $update = ['order_status' => 3, 'cancel_type' => 7, 'refund_money' => $row['money']];
                self::where('id', $row['id'])->update($update);

                Db::name('cms_users')->where('id', $row['user_id'])->setInc('moneys', $row['money']);
                $acc_data = ['user_id' => $row['user_id'], 'type_id' => 3, 'obj_id' => $row['id'], 'money' => $row['money'], 'create_time' => $time];
                $acc_data['descr'] = $row['book_date']. ' '.$row['time_begin'].' - '.$row['time_end']. ' Refund fee.';
                //生成账户流水
                Db::name('cms_account_log')->insertGetId($acc_data);

                //发socket通知
                Websocket::class_exit($row, $row['teacher_id']);
            } elseif ($row['class_time_len'] < 900) {
                //15分钟内，不退款，老师没收入
                $update = ['order_status' => 3, 'cancel_type' => 7];
                self::where('id', $row['id'])->update($update);

                //发socket通知
                Websocket::class_exit($row, $row['teacher_id']);
            } elseif ($row['class_time_len'] < 1500) {
                //25分钟内，不退款，老师得一半
                $update = ['order_status' => 3, 'cancel_type' => 6, 'clearing_money' => $row['teacher_price'] /2, 'teacher_money' => 1];
                self::where('id', $row['id'])->update($update);

                Db::name('cms_users')->where('id', $row['teacher_id'])->setInc('moneys', $row['teacher_price'] /2);
                $acc_data = ['user_id' => $row['teacher_id'], 'type_id' => 5, 'obj_id' => $row['id'], 'money' => $row['teacher_price'] /2, 'create_time' => $time];
                Db::name('cms_account_log')->insertGetId($acc_data);

                //发socket通知
                Websocket::class_exit($row, $row['teacher_id']);
            } else {
                //超过30分钟了，正常结束
                $update = ['order_status' => 2, 'clearing_money' => $row['teacher_price'], 'teacher_money' => 1];
                self::where('id', $row['id'])->update($update);
                $teacher = Users::find($row['teacher_id']);
                $learner = Users::find($row['user_id']);
                self::class_ok($row, $teacher, $learner, $time);

                //发socket通知
                Websocket::class_success($row, $row['teacher_id']);
            }
        }

        $time10 = strtotime('-6 minute');//20240713 change from 10 to 6
        //双方没到,开课10分钟后关闭
        $update = ['order_status' => 4, 'cancel_type' => 3];
        self::where([ ['datetime', 'lt', $time10], ['order_status','eq', 1]])->where('in_time_teacher =0 and in_time_student =0')->update($update);
        echo '双方没到,开课10分钟后关闭：'.self::getLastSql()."<br/>";

        //一方未到，开课后10分钟的
        //双方都迟到，一方10：35到，那就等到10：45关闭房间
        $list = self::where([ ['datetime', 'lt', $time10], ['order_status','eq', 1]])
            ->where('in_time_teacher  =0 or in_time_student  =0')
            ->where('(last_in_time_teacher > out_time_teacher or out_time_teacher  =0) or (last_in_time_student > out_time_student or out_time_student  =0)')
            ->select();
        echo '一方未到，开课后10分钟的:'.self::getLastSql()."<br/>";print_r($list);echo "<br/>";
        $teacher = Users::find($row['teacher_id']);
        $learner = Users::find($row['user_id']);        
        foreach ($list as $row) {
            if ($row['in_time_teacher']) {
                //老师进了，学生未进，不退款
                if ($row['in_time_teacher'] < $time10) {
                    $update = ['order_status' => 3, 'cancel_type' => 5];
                    self::where('id', $row['id'])->update($update);
                    //learner absent, send email to learner
                    $teacher = Users::find($row['teacher_id']);
                    $learner = Users::find($row['user_id']); 
    				$content = file_get_contents("template/learner_abs.html");
    				$finds = ['{learner_name}','{tutor_name}'];
                    $repls = [$learner['username']];
    
                    $content = str_replace($finds, $repls, $content);
                    $subject = 'Missed a meeting';
                    send_email_leo($learner['email'], $subject, $content);
                    // send_email_leo('liyan.zhao@outlook.com', $subject.' learner- '.$learner['username'].$learner['email'], $content);
                    //email tutor
                	$content1 = file_get_contents("template/learner_abs2t.html");
    				$finds1 = ['{tutor_name}','{learner_name}'];
                    $repls1 = [$teacher['username'],$learner['username']];//added learner name 20250924
    
                    $content1 = str_replace($finds1, $repls1, $content1);
                    send_email_leo($teacher['email'], 'Learner absence', $content1);
                    send_email_leo('liyan.zhao@outlook.com', 'Learner absence'. $learner['username']. $learner['email'], $content1);
                    //发socket通知
                    Websocket::class_overtime($row, $row['teacher_id']);
                }
            } elseif ($row['in_time_student']) {
                //学生进了，老师没进，退款

                if ($row['in_time_student'] < $time10) {
                    $update = ['order_status' => 3, 'cancel_type' => 4, 'refund_money' => $row['money']];
                    self::where('id', $row['id'])->update($update);

                    Db::name('cms_users')->where('id', $row['user_id'])->setInc('moneys', $row['money']);
                    $acc_data = ['user_id' => $row['user_id'], 'type_id' => 3, 'obj_id' => $row['id'], 'money' => $row['money'], 'create_time' => $time];
                    $acc_data['descr'] = $row['book_date']. ' '.$row['time_begin'].' - '.$row['time_end']. ' Refund fee.';
                    Db::name('cms_account_log')->insertGetId($acc_data);
                    //tutor absent, send email to tutor
                    $teacher = Users::find($row['teacher_id']);
                    $learner = Users::find($row['user_id']); 
					$content = file_get_contents("template/tutor_abs.html");
					$finds = ['{tutor_name}'];
					$repls = [$teacher['username']];
					$content = str_replace($finds, $repls, $content);
					$subject = 'Missed a meeting';
					send_email_leo($teacher['email'], $subject, $content);
                    // send_email_leo('liyan.zhao@outlook.com', $subject.' tutor- '.$teacher['username'], $content);
                    //tutor absent, send email to learner
    				$content1 = file_get_contents("template/tutor_abs2l.html");
    				$finds1 = ['{learner_name}','{tutor_name}'];
                    $repls1 = [$learner['username'],$teacher['username']];// added tutor name 20250924
    
                    $content1 = str_replace($finds1, $repls1, $content1);
                    send_email_leo($learner['email'], 'Tutor absence', $content1);
                    send_email_leo('liyan.zhao@outlook.com', 'Tutor absence'. $teacher['username']. $teacher['email'], $content1);
                    //发socket通知
                    Websocket::class_overtime($row, $row['user_id']);
                }
            }
        }

        //40分钟前的课程
        $time40 = strtotime('-50 minute');
        $list = self::where([['datetime', 'lt', $time40], ['order_status','eq', 1]])->where("out_time_student > in_time_student and out_time_teacher > in_time_teacher")->select();
        foreach ($list as $row) {
            $class_time_len = self::calu_time_len($row);
            if ($class_time_len < 600) {
                //任一方上课时长在10分钟内退出,课程状态为取消退费用，老师没收入
                $update = ['order_status' => 3, 'cancel_type' => 3];
                self::where('id', $row['id'])->update($update);

                Db::name('cms_users')->where('id', $row['user_id'])->setInc('moneys', $row['money']);
                $acc_data = ['user_id' => $row['user_id'], 'type_id' => 3, 'obj_id' => $row['id'], 'money' => $row['money'], 'create_time' => $time];
                $acc_data['descr'] = $row['book_date']. ' '.$row['time_begin'].' - '.$row['time_end']. ' Refund fee.';
                //生成账户流水
                Db::name('cms_account_log')->insertGetId($acc_data);
                //发socket通知
                Websocket::class_exit($row, [$row['user_id'], $row['teacher_id']]);
            }  elseif ($row['class_time_len'] < 1500) {
                //30分钟内，不退款，老师没收入 1800->1500
                $update = ['order_status' => 3, 'cancel_type' => 7];
                self::where('id', $row['id'])->update($update);

                //发socket通知
                Websocket::class_exit($row, [$row['user_id'], $row['teacher_id']]);
            }else {
                //超过10分钟了，正常结束
                $update = ['order_status' => 2, 'clearing_money' => $row['teacher_price'], 'teacher_money' => 1];
                self::where('id', $row['id'])->update($update);
                $teacher = Users::find($row['teacher_id']);
                $learner = Users::find($row['user_id']);
                self::class_ok($row, $teacher, $learner, $time);

                //发socket通知
                Websocket::class_success($row, [$row['user_id'], $row['teacher_id']]);
            }
        }
    }

    //重新计算上课时长
    public static function calu_time_len($row)
    {
        $intime = $row['last_in_time_teacher'] > $row['last_in_time_student'] ? $row['last_in_time_teacher'] : $row['last_in_time_student'];
        $outtime = $row['out_time_teacher'] < $row['out_time_student'] ? $row['out_time_teacher'] : $row['out_time_student'];
        $timelen = $row['class_time_len'] + $outtime - $intime;
        self::where('id', $row['id'])->setField('class_time_len', $timelen);
        return $timelen;
    }

    //完成课程
    public static function success_class($id)
    {
        $ClassID = $id;
        $info = self::where('classin_id', $ClassID)->find();
        if (!$info || $info['order_status'] == 10 || $info['order_status'] != 1) {
            return ['code' => 201, 'msg' => 'Chat error!'];
        }
        $id = $info['id'];
        $teacher_id = $info['teacher_id'];
        $teacher = Users::find($info['teacher_id']);
        $learner = Users::find($info['user_id']);

        Db::startTrans();
        try {
            $time = time();
            $update = ['order_status' => config('custom.status_completed')];
            $teacher_time_len = $info['teacher_time_len'];
            $learner_time_len = $info['learner_time_len'];

            //是否给老师结算
            $teacher_money = 0;
            $student_money = 0;
            $order_status = 2;
            $leave_minute = config('custom.leave_minute') * 60;
            $msg_id = 0;
            if ($learner_time_len == 0 && $teacher_time_len > 0 ) {
                //学生缺席，老师没缺席，已完成，不结算
                $order_status = 2;
                //20230428 added. Automatically send email to the absentee
				$content = file_get_contents("template/learner_abs.html");
				$finds = ['{learner_name}','{tutor_name}'];
                $repls = [$learner['username'],$teacher['username']];//added $teacher['username'] 20250924
                $content = str_replace($finds, $repls, $content);
                $subject = 'Missed a meeting';
                send_email_leo($learner['email'], $subject.' learner- '.$learner['username'], $content);    
                send_email_leo('liyan.zhao@outlook.com', $subject, $content); 
            } elseif ($learner_time_len > 0 && $teacher_time_len == 0 ) {
                //学生没缺席，老师缺席，未完成，退款
                
                $order_status = 3;
                $student_money = 1;
				$content = file_get_contents("template/tutor_abs.html");
				$finds = ['{tutor_name}'];
                $repls = [$teacher['username']];
                $content = str_replace($finds, $repls, $content);
                $subject = 'Missed a meeting';
                send_email_leo($teacher['email'], $subject.' tutor- '.$learner['username'], $content);
                send_email_leo('liyan.zhao@outlook.com', $subject, $content); 
            } elseif ($learner_time_len == 0 && $teacher_time_len == 0 ) {
                //老师和学生都缺席，取消并退款
                $student_money = 1;
                $order_status = 4;
            } elseif ($learner_time_len >= $leave_minute &&  $teacher_time_len >= $leave_minute) {
                //老师和学生都超过10分钟，完成，结算
                $order_status = 2;
                $teacher_money = 1;
            } elseif ($learner_time_len < $leave_minute || $teacher_time_len < $leave_minute ) {
                //老师或学生时间在10分钟内，未完成，退款
                $student_money = 1;
                $order_status = 3;
                $msg_id = 12;
            }
            $update['teacher_money'] = $teacher_money;
            $update['order_status'] = $order_status;
            if ($teacher_money == 1) {
                $update['clearing_money'] = $info['teacher_price'];
            } elseif ($student_money == 1) {
                $update['refund_money'] = $info['money'];
            }

            if (Db::name('cms_orderitems')->where('id', $id)->update($update)) {
                if ($update['order_status'] == config('custom.status_completed')) {
                    if ($teacher_money == 1) {
                        //完成课程，教师加钱
                        self::class_ok($info, $teacher, $learner, $time);
                    }
                } else {
                    //学生退款
                    if ($student_money == 1) {
                        Db::name('cms_users')->where('id', $info['user_id'])->setInc('moneys', $info['money']);
                        $acc_data = ['user_id' => $info['user_id'], 'type_id' => 3, 'obj_id' => $id, 'money' => $info['money'], 'create_time' => $time];
                        //生成账户流水
                        if (!(Db::name('cms_account_log')->insertGetId($acc_data))) {
                            Db::rollback();
                            return ['status' => 0, 'msg' => 'System error'];
                        }
                    }
                }

                if ($msg_id >0) {
                    //10分钟内退出，退款提醒
                    $msg = NoticeTemplate::find($msg_id);
                    $content = str_replace('{date}', $info['book_date']. ' '. $info['time_begin'].'-'.$info['time_end'], $msg['content']);
                    $notice = ['user_id' => $info['user_id'], 'subject' => $msg['subject'], 'msg' => $content, 'create_time' => $time];
                    Db::name('cms_notice')->insert($notice);
                }
                Db::commit();
                return ['code' => 200, 'msg' => 'Update successfully!'];
            } else {
                return ['code' => 201, 'msg' => 'Update failed!'];
            }
        } catch (Exception $e) {
            Db::rollback();
            return ['code' => 201, 'msg' => $e->getMessage()];
            // die(); // 终止异常
        }
    }

    public static function class_ok($info, $teacher, $learner, $time)
    {
        $moneys = $info['teacher_price'];
        if ($moneys > 0) {
            Db::name('cms_users')->where('id', $teacher['id'])->setInc('moneys', $moneys);
            $acc_data = ['user_id' => $teacher['id'], 'type_id' => 5, 'obj_id' => $info['id'], 'money' => $moneys, 'create_time' => $time];
            //生成账户流水
            if (!(Db::name('cms_account_log')->insertGetId($acc_data))) {
                Db::rollback();
                return ['status' => 0, 'msg' => 'System error'];
            }
        }
        //发佣金
        if ($teacher['partner_id']) {
            $member = Users::find($teacher['partner_id']);
            $money = $member['price_partner'];
            Db::name('cms_users')->where('id', $member['id'])->setInc('moneys', $money);
            Db::name('cms_users')->where('id', $member['id'])->setInc('commission_frozen', $money);
            $acc_data = ['user_id' => $member['id'], 'type_id' => 7, 'obj_id' => $info['id'], 'money' => $money, 'create_time' => $time];
            Db::name('cms_account_log')->insertGetId($acc_data);
        }
        if ($learner['partner_id']) {
            $member = Users::find($learner['partner_id']);
            $money = $member['price_partner'];
            Db::name('cms_users')->where('id', $member['id'])->setInc('moneys', $money);
            Db::name('cms_users')->where('id', $member['id'])->setInc('commission_frozen', $money);
            $acc_data = ['user_id' => $member['id'], 'type_id' => 7, 'obj_id' => $info['id'], 'money' => $money, 'create_time' => $time];
            Db::name('cms_account_log')->insertGetId($acc_data);
        }

        $class_num = self::where(['teacher_id' => $teacher['id'], 'order_status' => 2])->count();
        $cnts = self::where(['teacher_id' => $teacher['id'], 'order_status' => 2])->group('user_id')->select();
        Db::name('cms_users')->where('id', $teacher['id'])->update(['class_num' => $class_num, 'learner_num' => count($cnts)]);
        $class_num = self::where(['user_id' => $info['user_id'], 'order_status' => 2])->count();
        Db::name('cms_users')->where('id', $info['user_id'])->setField('class_num', $class_num);
    }

    //在上课之前进入的，开始上课了
    public static function class_begin($class_id, $user)
    {
        $map = [];
        $map[] = ['classin_id', 'eq', $class_id];
        $map[] = ['teacher_id|user_id', 'eq', $user['id']];
        $has = self::where($map)->where('class_time_begin', 'eq', 0)->find();
        if ($has) {
            $time = time();
            $has['class_time_begin'] = $time;
            self::where('id', $has['id'])->setField('class_time_begin', $time);
            $toids = [$has['teacher_id'], $has['user_id']];
            Websocket::class_begin($has, $time, $toids);
            return ['code' => 200, 'msg' => 'ok'];
        } else{
            return ['code' => 200, 'msg' => 'Not found class'];
        }
    }

}