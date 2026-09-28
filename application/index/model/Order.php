<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/9/28
 * Time: 21:51
 */

namespace app\index\model;

use app\index\service\Classin;
use think\Db;
use think\Exception;
use think\Model;

class Order extends Model
{

    // 设置当前模型对应的完整数据表名称
    protected $name = 'cms_order';

    //生成订单
    public static function create_order($data, $user_id)
    {
        $validate = new \app\index\validate\Order();
        $data['user_id'] = $user_id;
        $data['pay_type'] = $data['pay_type'] ?? 0;
        $result=  $validate->check($data);
        if ($result != true) {
            return ['code' => '201', 'msg' => $validate->getError()];
        }
        $data['dayids'] = explode(',', $data['dayids']);
        $data['dates'] = explode(',', $data['dates']);
        $data['times'] = explode(',', $data['times']);
        $teacher_id = $data['teacher_id'];
        $teacher = Users::find($teacher_id);
        $learner = Users::find($user_id);
        if ($teacher['user_type'] !=2) {
            return ['code' => '201', 'msg' => 'Please select Tutor'];
        }
        if ($learner['user_type'] !=1) {
            return ['code' => '201', 'msg' => 'Please login as a learner'];
        }
        $location_login = get_user_timezone();

//        $result = Daytime::get_cart($data['dates'], $data['times'], $data['dayids'], $teacher, $learner);
        $result = Daytime::get_cart($data['dayids'], $teacher, $learner, $location_login);
        if ($result['code'] != 200) {
            return $result;
        }
        $money = $result['money'];
        $pay_money = $result['pay_money'];
        $fees = 0;
        $items = [];
        $price = $teacher['price'];
        $fee = config('price_class_fee');
        $times = $data['times'];
        $dates = $data['dates'];
        $dayids = $data['dayids'];
        $invitation_money = 0;
        $invitation_code = '';

        if (isset($data['use_money'])) {
            $use_money = $result['use_money'];
            $pay_money = $pay_money - $use_money;
        } else {
            $use_money = 0;
        }
        if ($pay_money > 0 && !($data['pay_type'])) {
            return ['code' => '201', 'msg' => 'Please select Payment method'];
        }

        $days = Daytime::where('user_id', $teacher_id)->where('id', 'in', $dayids)->column('id,datetime, date,time');
        $dayid_others = [];
        foreach ($dayids as $k=>$id) {
            if (!$days[$id]) {
                continue;
            }
            $item = $days[$id];
            $item['id'] = $id;
            $time_prev = $item['datetime'] - 1800;
            $time_next = $item['datetime'] + 1800;
            $maps = [ [ 'order_status', 'between', [0,3]], ['datetime','in',[$time_prev, $item['datetime'], $time_next]] ];
            $has = Orderitems::where(['teacher_id'=>$teacher_id])->where($maps)->find();
            if ($has) {
                return ['code' => '201', 'msg' => "Tutor's ".$dates[$k]. ' '.$times[$k].' has been reserved.'];
            }
            $has = Orderitems::where(['user_id'=>$user_id])->where($maps)->find();
            if ($has) {
                return ['code' => '201', 'msg' => "Learner's ".$dates[$k]. ' '.$times[$k].' has been reserved.'];
            }

            //如果有预约的前后半小时的时间点，设置成不可用is_active:0，用于统计可用时间
            $dayid_others[] = $item['id'];
            $maps = [ ['datetime','in',[$time_prev, $time_next]], ['user_id', 'eq', $teacher_id] ];
            $tempids = Daytime::where($maps)->field('id')->select();
            foreach ($tempids as $vid) {
                $dayid_others[] = $vid['id'];
            }
            $ca_time=  $item['datetime']- (12 * 3600) ;//EST time
            $items[$k] = ['user_id' => $user_id, 'teacher_id' => $teacher_id, 'daytime_id' => $data['dayids'][$k], 'book_date' => $dates[$k], 'ca_time'=> $ca_time,'time_begin' => $times[$k], 'time_end' => date('H:i', strtotime('+30 minute', strtotime($times[$k]))), 'book_date_teacher' => $item['date'], 'time_begin_teacher' =>$item['time'], 'time_end_teacher'=> date('H:i', strtotime('+30 minute', strtotime($item['time']))), 'datetime' => $item['datetime'] ];
//            $items[$k] = ['user_id' => $user_id, 'teacher_id' => $teacher_id, 'daytime_id' => $data['dayids'][$k], 'book_date' => $dates[$k], 'time_begin' => $times[$k], 'time_end' => date('H:i', strtotime('+30 minute', strtotime($times[$k]))), 'book_date_teacher' => $item['date'], 'time_begin_teacher' =>$item['time'], 'time_end_teacher'=> date('H:i', strtotime('+30 minute', strtotime($item['time']))), 'datetime' => $item['datetime'] ];
            $items[$k]['teacher_price'] = $price;
            $items[$k]['fee'] = $fee;
            $items[$k]['money'] = $price + $fee;
            $fees += $fee;
            $items[$k]['invitation_fee'] = 0;
            if ($result['invitation_money_use'] > 0 && $k == 0) {
                $parent = Users::find($learner['parent_id']);
                $invitation_code = $parent['invitation_code'];
                $items[$k]['invitation_fee'] = $result['invitation_money_use'];
                $invitation_money = $items[$k]['invitation_fee'];
                $items[$k]['money'] -= $items[$k]['invitation_fee'];
            }
        }
        $num = count($days);
        $time = time();
        $order = ['user_id' => $user_id, 'teacher_id' => $teacher_id, 'order_amount' => $money, 'fees' => $fees, 'class_num' => $num, 'user_money' => $use_money, 'invitation_money' =>$invitation_money, 'pay_money' => $pay_money, 'pay_type' => $data['pay_type'], 'create_time' => $time, 'invitation_code' => $invitation_code ];
        if ($pay_money == 0) {
            $order['pay_status'] = 1;
            $order['order_status'] = 1;
            $order['pay_time'] = $time;
            $order_status = 1;
        } else {
            $order_status = 0;
        }
        $order['order_sn'] = create_order_sn();
        if (!$order['order_sn']) {
            return ['code' => 201, 'msg' => 'System error!' ];
        }

        Db::startTrans();
        try {
            if ($order_id = Db::name('cms_order')->insertGetId($order)) {
                foreach ($items as $item) {
                    $item['classin_id'] = Orderitems::create_sn();
                    $item['order_id'] = $order_id;
                    $item['create_time'] = $time;
                    $item['order_status'] = $order_status;
                    if (!Db::name('cms_orderitems')->insertGetId($item)) {
                        Db::rollback();
                        return ['code' => 201, 'msg' => 'System error'];
                    }
                    if (!Daytime::where(['id' => $item['daytime_id'], 'booked'=>0])->setField('booked', 1)) {
                        Db::rollback();
                        return ['code' => 201, 'msg' => 'Class error'];
                    }
                }
                //如果有前后半小时的时间点，设置成不可用，用于统计老师可用时间。取消订单或重新修改上课时间后需要重新设置成1
                Daytime::where('id', 'in', $dayid_others)->setField('is_active', 0);

                if ($use_money > 0) {
                    $new_money = $learner['moneys'] - $use_money;
                    $new_money = $new_money > 0 ? $new_money : 0;
                    Db::name('cms_users')->where('id', $user_id)->setField('moneys', $new_money);
                    $acc_data = ['user_id' => $user_id, 'type_id' => 1, 'obj_id' => $order_id, 'money' => $use_money * -1, 'create_time' => $time];
                    //生成账户流水
                    if (!(Db::name('cms_account_log')->insertGetId($acc_data))) {
                        Db::rollback();
                        return ['code' => 201, 'msg' => 'System error'];
                    }
                }
                if ($invitation_money > 0) {
                    //如果使用了推荐码，更新使用状态
                    Users::where('id', $user_id)->setField('invitation_code_buy', 1);
                }
                Users::where('id', $user_id)->setInc('buy_num', 1);
                Users::where('id', $teacher_id)->setInc('buy_num', 1);
                $ret = ['code' => 200, 'msg' => 'Book successfully!', 'order_id' => $order_id, 'pay_money' => $pay_money];
                if ($pay_money > 0) {
                    //生成支付数据
                    $pay_data = ['user_id' => $user_id, 'pay_type' => $data['pay_type'], 'type' => 1, 'obj_id' => $order_id, 'money' => $pay_money, 'create_time' => $time ];
                    $pay_data['order_sn'] = get_pay_sn();
                    $pay_id = Db::name('cms_payment')->insertGetId($pay_data);
                    if (!$pay_id) {
                        Db::rollback();
                        return ['code' => 201, 'msg' => 'System error.'];
                    }
                    $ret['pay_id'] = $pay_id;
                    $ret['pay_url'] = url('payment/index', ['pay_id' => $pay_id ]);
                } else {
                    $order['id'] = $order_id;
                    $res = self::update_payment($order);
                    if ($res['code'] != 200) {
                        Db::rollback();
                        return ['code' => 201, 'msg' => $res['msg']];
                    }
                }
                Db::commit();
                return $ret;
            } else {
                return ['code' => 201, 'msg' => 'Book failure'];
            }
        } catch (Exception $e) {
            Db::rollback();
            return ['code' => 201, 'msg' => $e->getMessage()];
            // die(); // 终止异常
        }
    }

    //支付后，更新订单状态
    public static function update_payment($order, $pay_note = '')
    {
        $time = time();
        $order_id = $order['id'];
        $teacher = Users::find($order['teacher_id']);
        $student = Users::find($order['user_id']);
        $location_student = Location::find($student['location']);
        $location_teacher = Location::find($teacher['location']);
        $country_student = Country::find($student['country_id']);
        if ($student['english_level']==1)
        { $level='Beginner';}
        elseif($student['english_level']==2)
        { $level='Intermediate';}
        elseif($student['english_level']==3)
        { $level='Proficient';}
        //学生信息：name, country, years learning English, goal, introduction
        $learner = "  <br/><b>　Name</b>: ". $student['username']."<br/><b>　Country/Area</b>: ".$country_student['country_name']."<br/><b>　Years of learning English</b>: ".$student['learn_years'].' Year(s)'."<br/><b>　Speaking English level</b>: ".$level."<br/><b>　Goals of speaking English</b>: ".$student['goals']."<br/><b>　Self-introduction</b>: ".$student['descr'];
        $update = ['order_status' =>2, 'pay_note' => $pay_note, 'pay_time' => $time, 'update_time' => $time];
        /* 不需要通过classin了
        $data_course = ['teacher_id' => $order['teacher_id'], 'user_id' => $order['user_id']];
        $has = Courses::where($data_course)->find();
        if (!$has) {
            $obj = new Classin();
            //生成课程
            $course = $obj->addCourse($student['username'], $student['classin_uid'], $teacher);
            if ($course['code'] == 200) {
                $data_course['course_id'] = $course['id'];
                $model = new Courses();
                $model->insertGetId($data_course);
                $student['course_id'] = $course['id'];
            }
        } else {
            $student['course_id'] = $has['course_id'];
        }
        */
        if (self::where('id', $order_id)->update($update)) {
            $items = Orderitems::where('order_id', $order_id)->order('datetime')->select();
            $obj_classin = new Classin();
            $date_teachers = [];
            $date_learners = [];
            $ids = [];
            foreach ($items as $k=>$row) {
                $res = Orderitems::create_class($student, $teacher, $row);
                if ($res['code'] == 200) {
                    $date_teachers[] = $row['book_date_teacher'].' '.$row['time_begin_teacher'].'-'.$row['time_end_teacher'];
                    $date_learners[] = $row['book_date'].' '.$row['time_begin'].'-'.$row['time_end'];
                    $ids[] = $row['id'];
                } else {
                    //生成课节错误
                    Orderitems::where('id', $row['id'])->setField('pay_error', 1);
                }
            }
            if (!$ids) {
                self::where('id', $order_id)->setField('pay_error', 1);
                return ['code'=>201, 'msg' => 'System error.'];
            }

            $msg_teacher = NoticeTemplate::find(1);
            $msg_learner = NoticeTemplate::find(2);
            if (count($ids) > 1) {
//                $id_txt = implode(',', $ids);
                $id_txt = '(s)';
                $date_teacher_txt = implode($date_teachers, '<br/>').'<br/>';
                $date_learner_txt = implode($date_learners, '<br/>').'<br/>';
            } else {
                $id_txt = '('.$ids[0].') is';
                $date_teacher_txt = $date_teachers[0];
                $date_learner_txt = $date_learners[0];
            }
            //发送给老师
            $content = file_get_contents("template/create_teacher.html");
            $find = ['{id}', '{location}', '{datetime}', '{learner}'];
            $repl = [$id_txt, $location_teacher['location_name'], $date_teacher_txt, $learner];
            $content = str_replace($find, $repl, $content);
            if (count($ids) > 1) {
                $subject = 'New '.count($ids).' chats created ';
            } else {
                $subject = str_replace($find, $repl, $msg_teacher['subject']);
            }
            send_email_g($teacher['email'], $subject, $content);
            send_email_g('liyan.zhao@outlook.com', $teacher['username'].'T'.$teacher['id'].' '.$teacher['email'].' '. $student['username'].$student['id'].' '.$student['email'].' '.'New chat', $content);
            $msg = str_replace($find, $repl, $msg_teacher['content']);
            $notice = ['user_id' => $teacher['id'], 'subject' => $subject, 'msg' => $msg, 'create_time' => $time];
            Db::name('cms_notice')->insert($notice);

            //发送给学生
            $content = file_get_contents("template/create_learner.html");
            $find = ['{id}', '{location}','{datetime}', '{teacher_name}'];
            $repl = [$id_txt, $location_student['location_name'], $date_learner_txt, $teacher['username']];
            $content = str_replace($find, $repl, $content);
            if (count($ids) > 1) {
                $subject = 'New '.count($ids).' chats created ';
            } else {
                $subject = str_replace($find, $repl, $msg_learner['subject']);
            }
            send_email_g($student['email'], $subject, $content);
            $msg = str_replace($find, $repl, $msg_learner['content']);
            $notice = ['user_id' => $student['id'], 'subject' => $subject, 'msg' => $msg, 'create_time' => $time];
            Db::name('cms_notice')->insert($notice);

            Users::where('id', $student['id'])->setInc('paid_num', 1);
            Users::where('id', $teacher['id'])->setInc('paid_num', 1);

            return ['code'=>200, 'msg' => 'Success'];
        } else {
            self::where('id', $order_id)->setField('pay_error', 1);
            return ['code'=>201, 'msg' => 'Create error'];
        }
    }

}