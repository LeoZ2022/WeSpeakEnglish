<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/9/28
 * Time: 21:51
 */
// this file is obsoleted and replaced by /api/model/* 20250925
namespace app\index\model;

use app\index\service\Classin;
use qrcode\QRcode;
use think\Db;
use think\Exception;
use think\Model;

class Orderitems extends Model
{

    // 设置当前模型对应的完整数据表名称
    protected $name = 'cms_orderitems';

    public function OrderStatus()
    {
        return $this->hasOne('OrderStatus', 'order_status', 'id');
    }

    public static function get_list($map, $all = 0)
    {
        if ($all) {
            $list = self::alias('i')->field('i.*, t.username as teacher_username,t.mobile as teacher_mobile,t.email as teacher_email,s.username,s.mobile,s.email,s.native_language,s.english_level,s.location as location_id_s,s.descr as descr')
//                ->join('cms_order o', 'i.order_id = o.id')
                ->join('cms_users t', 'i.teacher_id = t.id')
                ->join('cms_users s', 'i.user_id = s.id')
                ->where($map)->order('i.order_id desc')->select();
        } else {
            $list = self::alias('i')->field('i.*, t.username as teacher_username,t.mobile as teacher_mobile,t.email as teacher_email,s.username,s.mobile,s.email,s.native_language,s.english_level,s.location as location_id_s,s.descr as descr')
//                ->join('cms_order o', 'i.order_id = o.id')
                ->join('cms_users t', 'i.teacher_id = t.id')
                ->join('cms_users s', 'i.user_id = s.id')
                ->where($map)->order('i.order_id desc')->paginate();
        }
//        echo self::getLastSql();
        return $list;
    }

    //生成30天内的日期列表
    public static function get_day_30($user_id, $type_id)
    {
        //获取会员所在时区的日期
        $dates = Daytime::get_day_30();

        $start = $dates[0];

        $i=1;
        if ($type_id == 1) {
            //学生
            $map = ['user_id' => $user_id];
            $col = 'book_date';
        } else {
            //老师
            $map = ['teacher_id' => $user_id];
            $col = 'book_date_teacher';
        }
        foreach ($dates as $k=>$row) {
            $start = $row['date'];
            $day = date('d', strtotime($start));
            $day = $day < 10 ? '0'.$day : $day;
            $has = self::where($map)->where($col, $start)->count();
            $dates[$k]['has'] = $has;
        }
        return $dates;
    }

    /**
     * 生成一个月的日历
     * @param int $time：0--订单，1--上课时间
     * @return mixed
     */
    public static function get_month($user_id = 0, $type_id =0, $year = 0, $month = 0)
    {
        $timezone = get_user_timezone();
        $time = gmt_to_local(get_time_gmt(), $timezone);
        $now = date('Y-m-d', $time);
        $now30 = date('Y-m-d', strtotime('+30 day', $time));

        if (!$year || !$month) {
            //获取会员所在时区的日期
            $year = date('Y', $time);
            $month = date('m', $time);
//            echo '服务器:'.date('Y-m-d H:i')."<br/>";
//            echo '当前时区:'.date('Y-m-d H:i', $time)."<br/>";
        }
        $daysInMonth = date("t",mktime(0,0,0,$month,1,$year));
        $firstDay = date("w", mktime(0,0,0,$month,1,$year));
        $tempDays = $firstDay + $daysInMonth;
        $weeksInMonth = ceil($tempDays/7);

        $counter = 0;
        if ($type_id == 1) {
            //学生
            $map = ['user_id' => $user_id];
            $col = 'book_date';
        } else {
            //老师
            $map = ['teacher_id' => $user_id];
            $col = 'book_date_teacher';
        }

        for($j=0;$j<$weeksInMonth;$j++)
        {
            for($i=0;$i<7;$i++)
            {
                $has = 0;
                if (!($j==0 && $i<$firstDay-1)) {
                    $counter++;
                }
                if($counter > $daysInMonth) {
                    $newtime = strtotime('+1 month', mktime(0,0,0,$month,1,$year));
                    $month = date('m', $newtime );
                    $year = date('Y', $newtime );
                    $counter = 1;
                }
                $in30 = 0;
                if ($counter < 1) {
                    $txt = "";
                    $date = '';
                } else {
                    if ($counter > 0 && $counter < 10) {
                        $txt = '0'.$counter;
                    } else {
                        $txt = $counter;
                    }
                    $date = $year.'-'.$month.'-'.$txt;
                    if ($date >= $now && $date <= $now30) {
                        //30天内，看是否已经预约了
                        $has = self::where($map)->where($col, $date)->count();
                        $in30 = 1;
                    }
                }
                $week[$j][$i] = ['day' => $txt, 'has' => $has, 'date' => $date, 'in30'=>$in30];
            }
        }
        return $week;
    }

    //活跃查询
    public static function get_actived($map, $user_type)
    {
        if ($user_type == 1) {
            $col = 'user_id';
        } else {
            $col = 'teacher_id';
        }
        $status_id = config('custom.status_completed');
        $obj = Users::alias('u')->field('u.id,username,mobile,email,user_type,count(o.id) as cnt,u.create_time')
            ->join('cms_orderitems o', "$col = u.id and order_status='$status_id'")
            ->where($map);
        $_filter_time_from = input('_filter_time_from');
        $_filter_time_to = input('_filter_time_to');
        if ($_filter_time_from && $_filter_time_to) {
            $obj->where('o.create_time', 'between', [strtotime($_filter_time_from), strtotime($_filter_time_to)]);
        } elseif ($_filter_time_from) {
            $obj->where('o.create_time', 'egt', strtotime($_filter_time_from));
        } elseif ($_filter_time_to) {
            $obj->where('o.create_time', 'elt', strtotime($_filter_time_to.' 23:59:59'));
        }
        $list = $obj->group($col)->order('cnt desc')->paginate();
        return $list;
    }

    //confirm the chat
    public static function confirm($id, $user_id)
    {

		//inform learner the confirmation
		$info = self::find($id);
        if (!$info || ($info['user_id'] != $user_id && $info['teacher_id'] != $user_id)) {
            return ['code' => 201, 'msg' => 'Chat not found!'];
        }
		$learner = Users::find($info['user_id']);
		$teacher = Users::find($info['teacher_id']);
		$msg_template = NoticeTemplate::find(13);
		$time = time();
		$state =1;
		$update = ['confirmed' => $state, 'confirmed_time' => $time];
        $daytime = Daytime::find($info['daytime_id']);
		Db::startTrans();
        try {
            if (Db::name('cms_orderitems')->where('id', $id)->update($update)) {

        		$content = file_get_contents("template/confirm.html");
        		$finds = ['{id}', '{datetime}','{teacher_name}'];
        		$repls = [$id, $info['book_date'].' '.$info['time_begin'].'-'.$info['time_end'], $teacher['username']];
        		$content = str_replace($finds, $repls, $content);
        		$subject = str_replace($finds, $repls, $msg_template['subject']);
        		send_email_g($learner['email'], $subject, $content);
        		send_email_g('liyan.zhao@outlook.com', $teacher['username'].$info['teacher_id'].' '.$learner['username'].$info['user_id'].', '.$subject, $content);
        		$msg = str_replace($finds, $repls, $msg_template['content']);
        		$notice = ['user_id' => $info['user_id'], 'subject' => $subject, 'msg' => $msg, 'create_time' => $time];
        		Db::name('cms_notice')->insert($notice);

                        Db::commit();
        		                return ['code' => 200, 'msg' => 'Confirm successfully!'];

                }else {
                        return ['code' => 201, 'msg' => 'Confirmation failed!'];
                    }
        }
		catch (Exception $e) {
            Db::rollback();
            return ['code' => 201, 'msg' => $e->getMessage()];
    }
    }

    //取消课程
    public static function cancel($id, $user_id, $user_type)
    {
        $cancel_message = $_POST['cancel_message'];
        $hour_num = config('cfg_cancel_time');
        $info = self::find($id);
        if (!$info || ($info['user_id'] != $user_id && $info['teacher_id'] != $user_id)) {
            return ['code' => 201, 'msg' => 'Chat not found!'];
        }
        if ($info['order_status'] != 1) {
            return ['code' => 201, 'msg' => 'Chat cannot be cancelled!'];
        }
        $time3 = strtotime('+'.$hour_num.' hour');
        if ($info['datetime'] < $time3) {
            return ['code' => 201, 'msg' => 'Chats can only be cancelled '.$hour_num.' hours in advance!'];
        }
        $state_cancel = config('custom.status_cancelled');
        $time = time();
        $update = ['order_status' => $state_cancel, 'cancel_time' => $time, 'cancel_type' => $user_type];
        $daytime = Daytime::find($info['daytime_id']);
        Db::startTrans();
        try {
            if (Db::name('cms_orderitems')->where('id', $id)->update($update)) {
                //重置为无预约的状态
                Daytime::where(['id' => $info['daytime_id']])->setField('booked', 0);
                //取消成功，退款
                $moneys = $info['money'];
                if ($moneys > 0) {
                    Db::name('cms_users')->where('id', $info['user_id'])->setInc('moneys', $moneys);
                    $acc_data = ['user_id' => $info['user_id'], 'type_id' => 3, 'obj_id' => $id, 'money' => $moneys, 'create_time' => $time];
                    //生成账户流水
                    if (!(Db::name('cms_account_log')->insertGetId($acc_data))) {
                        Db::rollback();
                        return ['status' => 0, 'msg' => 'System error'];
                    }
                }

                //将前后半小时的设置成is_active：1
                $time_prev = $daytime['datetime'] - 1800;
                $time_next = $daytime['datetime'] + 1800;
                $maps = [ ['datetime','in',[$time_prev, $time_next]], ['user_id', 'eq', $daytime['user_id']] ];
                Daytime::where($maps)->setField('is_active', 0);

                //发送站内消息和邮件
                //老师
                $teacher = Users::find($info['teacher_id']);
				$student = Users::find($info['user_id']);
                $msg_template = NoticeTemplate::find(3);

                if ($user_type==1){
					$content = file_get_contents("template/cancel_teacher1.html");
					
				}else if($user_type==2){
					$content = file_get_contents("template/cancel_teacher.html");
				}

                $finds = ['{id}', '{datetime}','{student_name}','{teacher_name}','{cancel_message}'];
                $repls = [$id, $info['book_date_teacher'].' '.$info['time_begin_teacher'].'-'.$info['time_end_teacher'], $student['username'],$teacher['username'],$cancel_message];

                $content = str_replace($finds, $repls, $content);
                $subject = str_replace($finds, $repls, $msg_template['subject']);
                send_email_g($teacher['email'], $subject, $content);
                send_email_g('liyan.zhao@outlook.com', $teacher['username'].$info['teacher_id'].$student['username'].$info['user_id'].', '.$subject, $content);
                $msg = str_replace($finds, $repls, $msg_template['content']);
                $notice = ['user_id' => $info['teacher_id'], 'subject' => $subject, 'msg' => $msg, 'create_time' => $time];
                Db::name('cms_notice')->insert($notice);

                //学生
                $learner = Users::find($info['user_id']);
                $msg_template = NoticeTemplate::find(4);

				if ($user_type==2){
					$content = file_get_contents("template/cancel_learner2.html");
				}else if($user_type==1){
					$content = file_get_contents("template/cancel_learner.html");
				}

                $finds = ['{id}', '{datetime}', '{teacher_name}','{student_name}','{cancel_message}'];
                $repls = [$id, $info['book_date'].' '.$info['time_begin'].'-'.$info['time_end'], $teacher['username'], $student['username'],$cancel_message];

                $content = str_replace($finds, $repls, $content);
                $subject = str_replace($finds, $repls, $msg_template['subject']);
                send_email_g($learner['email'], $subject, $content);
                send_email_g('liyan.zhao@outlook.com', $teacher['username'].$info['teacher_id'].$student['username'].$info['user_id'].', '.$subject, $content);
                $msg = str_replace($finds, $repls, $msg_template['content']);
                $notice = ['user_id' => $info['user_id'], 'subject' => $subject, 'msg' => $msg, 'create_time' => $time];
                Db::name('cms_notice')->insert($notice);

                $course = Courses::where(['user_id' => $info['user_id'], 'teacher_id' => $info['teacher_id']])->find();

                $srv = new Classin();
                $res = $srv->delCourseClass($course['course_id'], $info['classin_id']);
                if ($res['code'] == 200) {
                }
                Db::commit();
                return ['code' => 200, 'msg' => 'Cancellation successfully!'];
            }else {
                return ['code' => 201, 'msg' => 'Cancellation failed!'];
            }
         }catch (Exception $e) {
            Db::rollback();
            return ['code' => 201, 'msg' => $e->getMessage()];
            // die(); // 终止异常

         }
    }

    //完成课程 inactive. See application\api\model
    public static function success_notify($data)
    {
        $ClassID = $data['ClassID'];
        $info = self::where('classin_id', $ClassID)->find();
        if (!$info || $info['order_status'] == 10) {
            return ['code' => 201, 'msg' => 'Chat error!'];
        }
        $id = $info['id'];
        $teacher_id = $info['teacher_id'];
        $teacher = Users::find($info['teacher_id']);
        $learner = Users::find($info['user_id']);

        Db::startTrans();
        try {
            $teacher_times = isset($data['Data']['inoutEnd'][$teacher['classin_uid']]) ? $data['Data']['inoutEnd'][$teacher['classin_uid']]['Details'] : [];
            $learner_times = isset($data['Data']['inoutEnd'][$learner['classin_uid']]) ? $data['Data']['inoutEnd'][$learner['classin_uid']]['Details'] : [];
            $teacher_cnt = count($teacher_times);
            $learner_cnt = count($learner_times);
            $time = time();
            $update = ['order_status' => config('custom.status_completed')];
            $teacher_time_len = $data['Data']['inoutEnd'][$teacher['classin_uid']]['Total'] ?? 0;
            $learner_time_len = $data['Data']['inoutEnd'][$learner['classin_uid']]['Total'] ?? 0;
            $update['learner_time_len'] = $learner_time_len;
            $update['teacher_time_len'] = $teacher_time_len;
            $update['in_time_teacher'] = $teacher_times[0]['Time'] ?? 0;
            $update['in_time_student'] = $learner_times[0]['Time'] ?? 0;
            $update['out_time_teacher'] = $teacher_times[$teacher_cnt-1]['Time'] ?? 0;
            $update['out_time_student'] = $learner_times[$learner_cnt-1]['Time'] ?? 0;
            $update['teacher_device'] = $teacher_times[0]['Device'] ?? 0;
            $update['student_device'] = $learner_times[0]['Device'] ?? 0;
            $update['success_time'] = $update['out_time_teacher'] > $update['out_time_student'] ? $update['out_time_teacher'] : $update['out_time_student'];
            $minute = ($update['out_time_student'] - $info['datetime']) / 60;
            $minute2 = ($update['out_time_teacher'] - $info['datetime']) / 60;
            //是否给老师结算
            $teacher_money = 0;
            $student_money = 0;
            $order_status = 2;
            $leave_minute = config('custom.leave_minute') * 60;
            $msg_id = 0;
            if ($learner_time_len == 0 && $teacher_time_len > 0 ) {
                //学生缺席，老师没缺席，已完成，不结算
                send_email_leo('liyan.zhao@outlook.com', 'from Leo', 'test'); 
                send_email4('liyan.zhao@outlook.com', 'from email4', 'test');
                $order_status = 2;
                //added on 20230427. send absence email to learner
				$content = file_get_contents("template/learner_abs.html");
				$finds = ['{learner_name}'];
                $repls = [$learner['username']];
                $content = str_replace($finds, $repls, $content);
                $subject = 'Missed a meeting';
                send_email_leo($learner['email'], $subject, $content); 
                send_email('liyan.zhao@outlook.com', $teacher['username'].' '.$learner['username'].', '.$subject, $content); 
            } elseif ($learner_time_len > 0 && $teacher_time_len == 0 ) {
                send_email_leo('liyan.zhao@outlook.com', 'from Leo', 'test'); 
                send_email4('liyan.zhao@outlook.com', 'from email4', 'test'); 
                //学生没缺席，老师缺席，未完成，退款
                $order_status = 3;
                $student_money = 1;
                //added on 20230427. send absence email to tutor
                send_email_leo('liyan.zhao@outlook.com', 'from Leo', 'test'); 
                send_email4('liyan.zhao@outlook.com', 'from email4', 'test'); 	
				$content = file_get_contents("template/tutor_abs.html");
				send_email4('liyan.zhao@outlook.com', $teacher['username'].' '.$learner['username'].', '.$subject, $content); 
				send_email_leo('liyan.zhao@outlook.com', 'from Leo', 'test'); 				
			//	$finds = ['{tutor_name}'];
            //    $repls = [$teacher['username']];
            //    $content = str_replace($finds, $repls, $content);
            //    $subject = 'Missed a meeting';
            //    send_email_leo($teacher['email'], $subject, $content); 
                
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
            if (Db::name('cms_orderitems')->where('id', $id)->update($update)) {
                if ($update['order_status'] == config('custom.status_completed')) {
                    if ($teacher_money == 1) {
                        //完成课程，教师加钱
                        $moneys = $info['teacher_price'];
                        if ($moneys > 0) {
                            Db::name('cms_users')->where('id', $teacher_id)->setInc('moneys', $moneys);
                            $acc_data = ['user_id' => $teacher_id, 'type_id' => 5, 'obj_id' => $id, 'money' => $moneys, 'create_time' => $time];
                            //生成账户流水
                            if (!(Db::name('cms_account_log')->insertGetId($acc_data))) {
                                Db::rollback();
                                return ['status' => 0, 'msg' => 'System error'];
                            }
                        }
                    }
                    $class_num = self::where(['teacher_id' => $teacher_id, 'order_status' => 2])->count();
                    Db::name('cms_users')->where('id', $teacher_id)->setField('class_num', $class_num);
                    $class_num = self::where(['user_id' => $info['user_id'], 'order_status' => 2])->count();
                    Db::name('cms_users')->where('id', $info['user_id'])->setField('class_num', $class_num);
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

    //评价回调
    public static function comment_notify($data)
    {
        $ClassID = $data['ClassID'];
        $info = self::where('classin_id', $ClassID)->find();

		//This function is no use. See api/
        if ($info) {
            foreach ($data['Comments'] as $comment) {
                if (isset($comment['T2S'])) {
                    //老师评价
                    $update = ['comment_teacher' => $comment['T2S']['Comment'], 'score_teacher' => $comment['T2S']['Score'], 'comment_teacher_time' => $data['ActionTime'] ];
                    self::where('id', $info['id'])->update($update);
                    $score = self::where('teacher_id', $info['teacher_id'])->where('score_teacher', 'gt',0)->field('AVG(score_teacher) as score')->find();
                    $score = round($score['score'], 1);
                    Users::where('id', $info['teacher_id'])->setField('star', $score);

                } elseif (isset($comment['S2T'])) {
                    //学生评价

                    $update = ['comment_student' => $comment['S2T']['Comment'], 'score_student' => $comment['S2T']['Score'], 'comment_student_time' => $data['ActionTime'] ];
                    self::where('id', $info['id'])->update($update);
                    $score = self::where('user_id', $info['user_id'])->where('score_student', 'gt',0)->field('AVG(score_student) as score')->find();
                    $score = round($score['score'], 1);
                    Users::where('id', $info['user_id'])->setField('star', $score);
                }
            }
            $ret = ['error_info' => ['errno' =>1, 'error' => 'success']];
        } else {
            $ret = ['error_info' => ['errno' =>1, 'error' => 'ClassID not found!']];
        }
        return $ret;
    }
    //设备回调
    public static function device_notify($data)
    {
        $ClassID = $data['ClassID'];
        $info = self::where('classin_id', $ClassID)->find();
        if ($info) {
            $DeviceType = $data['DeviceType'];
        } else {
            $ret = ['error_info' => ['errno' =>1, 'error' => 'ClassID not found!']];
        }
        return $ret;
    }

    //修改上课时间
    public static function edit_item($id, $user_id, $daytime_id, $location_login)
    {
        $map = ['id' => $id, 'user_id' => $user_id];
        $info = self::where($map)->find();
        if (!$info || $info['order_status'] != 1) {
            return ['code' => 201, 'msg' => 'Chat error!'];
        }

        $dayinfo = Daytime::find($daytime_id);
        if (!$dayinfo) {
            return ['code' => 201, 'msg' => 'Class time error!'];
        }
        $olddayinfo = Daytime::find($info['daytime_id']);

        $date = time_to_date($dayinfo['datetime'], $location_login, 'Y-m-d H:i');
        $temp = explode(' ', $date);
        $newdate = $temp[0];
        $newtime = $temp[1];
        //判断教师这个时间点是否已经预约
        $has = Orderitems::where(['daytime_id' => $daytime_id, 'order_status' => 1] )->find();
        if ($has) {
            return ['code' => 201, 'msg' => $newdate.' '.$newtime. ', The timeslot is unavailable!' ];
        }
        $learner = Users::find($user_id);
//        $teacher = Users::find($info['teacher_id']);
        $time = time();
        $course = Courses::where(['user_id' => $user_id, 'teacher_id' => $info['teacher_id']])->find();
        $time_end_teacher = $dayinfo['time_end'];
        $time_end_learner = date('H:i', strtotime('+30 minute', strtotime($newdate. ' '.$newtime)));
        $ca_time=  $dayinfo['datetime']- (13 * 3600) ;//EST time
        $state=0;
//        $datetime = date_to_time($newdate.' '.$newtime, get_user_timezone() );
        Db::startTrans();
        try {
            $obj = new Classin();
            $update = ['datetime' => $dayinfo['datetime'], 'book_date' => $newdate, 'ca_time'=> $ca_time, 'confirmed' => $state,'time_begin' => $newtime, 'time_end' => $time_end_learner, 'book_date_teacher' => $dayinfo['date'], 'time_begin_teacher' => $dayinfo['time'], 'time_end_teacher' => $time_end_teacher, 'daytime_id' => $daytime_id];
            if (!self::where($map)->update($update)) {
                Db::rollback();
                return ['code' => 201, 'msg' => 'Edit error'];
            }
            $ret = $obj->editCourseClass($course['course_id'], $info['classin_id'], $dayinfo['datetime'], $dayinfo['datetime'] + 1800);
            if ($ret['code'] == 201) {
                Db::rollback();
                return ['code' => 201, 'msg' => 'Edit error.'];
            }
            
            Daytime::where('id', $daytime_id)->setField('booked', 1);
            Daytime::where('id', $info['daytime_id'])->setField('booked', 0);

            //将新时间前后半小时的设置成is_active：0，不可用，用于统计可用时间
            $time_prev = $dayinfo['datetime'] - 1800;
            $time_next = $dayinfo['datetime'] + 1800;
            $maps = [ ['datetime','in',[$time_prev, $time_next, $dayinfo['datetime']]], ['user_id', 'eq', $dayinfo['user_id']] ];
            Daytime::where($maps)->setField('is_active', 0);
            //将新时间前后半小时的设置成is_active：1，可用，用于统计可用时间
            $time_prev = $olddayinfo['datetime'] - 1800;
            $time_next = $olddayinfo['datetime'] + 1800;
            $maps = [ ['datetime','in',[$time_prev, $time_next, $olddayinfo['datetime']]], ['user_id', 'eq', $dayinfo['user_id']] ];
            Daytime::where($maps)->setField('is_active', 1);

            //发送站内消息和邮件
            //老师
            $teacher = Users::find($info['teacher_id']);
            $msg_template = NoticeTemplate::find(5);
            $content = file_get_contents("template/edit_teacher.html");
            $finds = ['{id}', '{datetime}', '{datetime_new}', '{learner}'];

            $repls = [$id, $info['book_date_teacher'] . ' ' . $info['time_begin_teacher'] . '-' . $info['time_end_teacher'], $dayinfo['date'] . ' ' . $dayinfo['time'] . '-' . $time_end_teacher, $learner['username']];
            $content = str_replace($finds, $repls, $content);
            $subject = $msg_template['subject'];
            $subject = str_replace($finds, $repls, $subject);
            send_email_g($teacher['email'], $subject, $content);
            send_email_g(config('cfg_email'), $subject.$teacher['username'], $content);
            $msg = str_replace($finds, $repls, $msg_template['content']);
            $notice = ['user_id' => $info['teacher_id'], 'subject' => $subject, 'msg' => $msg, 'create_time' => $time];
            Db::name('cms_notice')->insert($notice);

            //学生
            $student = Users::find($info['user_id']);
            $msg_template = NoticeTemplate::find(6);
            $content = file_get_contents("template/edit_learner.html");
            $finds = ['{id}', '{datetime}', '{datetime_new}'];
            $repls = [$id, $info['book_date'] . ' ' . $info['time_begin'] . '-' . $info['time_end'], $newdate . ' ' . $newtime . '-' . $time_end_learner];
            $content = str_replace($finds, $repls, $content);
            $subject = $msg_template['subject'];
            $subject = str_replace($finds, $repls, $subject);
            send_email_g($student['email'], $subject, $content);
            $msg = str_replace($finds, $repls, $msg_template['content']);
            $notice = ['user_id' => $info['user_id'], 'subject' => $subject, 'msg' => $msg, 'create_time' => $time];
            Db::name('cms_notice')->insert($notice);
            Db::commit();
            return ['code' => 200, 'msg' => 'Edit successfully!'];
        } catch (Exception $e) {
            Db::rollback();
            return ['code' => 201, 'msg' => $e->getMessage()];
            // die(); // 终止异常
        }
    }

    //老师页面，获取学生评论记录
    public static function get_comment_list($map)
    {
        $list = self::alias('c')->field('c.*, location_name,t.ico_file,username,country_name')
            ->join('cms_users u', 'user_id = u.id')
            ->join('cms_location l', 'location = l.id', 'left')
            ->join('cms_country t', 'country_id = t.id', 'left')
            ->where($map)->where('comment_student_time', 'gt', 0)->order('c.id desc')->paginate(10);
//        echo self::getLastSql();
        return $list;
    }

    //获取学生上过课的老师
    public static function get_teacher_list($map)
    {
        $list = self::alias('c')->field('teacher_id,username, star, ico_file, country_name')
            ->join('cms_users u', 'teacher_id = u.id')
            ->join('cms_country l', 'country_id = l.id')
            ->where($map)->where('order_status', 'eq', 2)->group('teacher_id')->order('c.id desc')->select();
        return $list;
    }

    //获取classin进入课程的链接
    public static function get_link($user_id, $info)
    {
        $user = Users::find($user_id);
        $student = Users::find($info['user_id']);
        $teacher = Users::find($info['teacher_id']);
        $classin_id = $info['classin_id'];
        //如果没有课程
        $course = Courses::where(['user_id' => $info['user_id'], 'teacher_id' => $info['teacher_id'] ])->find();
        $obj = new Classin();
        if (!$course) {
            //生成课程
            $course = $obj->addCourse($student['username'], $student['classin_uid'], $teacher);
        }
        //如果没有classinid，先添加课节
        if (!$info['classin_id']) {
            $res = self::create_class($student, $teacher, $info);
            if ($res['code'] == 200) {
                $classin_id = $res['data']['classin_id'];
            }
        }

        if (!$classin_id) {
            return ['code' =>201, 'msg' => 'Class error'];
        } else {
            $obj = new Classin();
            $result = $obj->getLoginLinked($user['classin_uid'], $course['course_id'], $classin_id);
            if ($result['code'] == 200) {
                $link = $result['data'];
                return ['code' =>200, 'msg' => 'success', 'link' => $link];
            } else {
                return ['code' =>201, 'msg' => 'Class error'];
            }
        }
    }

    //生成课节
    public static function create_class($student, $teacher, $info)
    {
        $update = ['order_status' => 1 ];
        Orderitems::where('id', $info['id'])->update($update);
        return ['code' =>200, 'data' => $update];

        $data = ['courseId' => $student['course_id'], 'teacherUid' => $teacher['classin_uid'], 'className' => $student['username']. ' - '.$info['id'], 'beginTime' => $info['datetime'] ];
        $data['endTime'] = $data['beginTime'] + 1800;
        $obj_classin = new Classin();
        $res = $obj_classin->createClass($data);
//            $class = ['code' =>200];
        if ($res['code'] == '200') {
            $class = $res['data'];
            $update = ['order_status' => 1, 'classin_id' => $class['data'] ];
            //, 'live_url' => $class['more_data']['live_url'], 'live_rtmp' => $class['more_data']['live_info']['RTMP'], 'live_hls' => $class['more_data']['live_info']['HLS'], 'live_flv' => $class['more_data']['live_info']['FLV']
            Orderitems::where('id', $info['id'])->update($update);
            return ['code' =>200, 'data' => $update];
        } else {
            return ['code' =>201, 'msg' => $res['msg']];
        }
    }

    //生成编号
    public static function create_sn()
    {
        $sn = generate_rand_str(12, 3);
        while(1) {
            $has = self::where('classin_id', $sn)->find();
            if (!$has) {
                return $sn;
            }
        }
    }

    /**
     * 获取机构下面的课程
     * page：是否分页
     */
    public static function get_list_partner($partner_id, $user_type, $ordet_status=0, $limit = 10, $page=0, $addmap = '' )
    {
        $map = [ ['partner_id', 'eq', $partner_id] ];
        if ($ordet_status) {
            $map[] = ['order_status', 'eq', $ordet_status];
        }
        if ($user_type == 1) {
            $col = 'user_id';
            $field = 'username,book_date, time_begin, time_end, teacher_id, native_language, english_level, country_id,descr, goals';
        } else {
            $col = 'teacher_id';
            $field = 'username,book_date_teacher as book_date, time_begin_teacher as time_begin, time_end_teacher as time_end, teacher_id';
        }
        $obj = self::alias('o')->field($field)->where($map)->where($addmap)
            ->join('cms_users u', $col.'=u.id')->order('o.id desc');
        if ($page) {
            $list = $obj->paginate($limit);
        } else {
            $list = $obj->limit($limit)->select();
        }
//        echo self::getLastSql();
        return $list;
    }

    //获取课程信息，生成二维码
    //包含用户账号、密码、classin_id，APP安装包等
    public static function get_room($user_id, $class_id)
    {
        $user = Users::field('id,email, password, username')->where('id', $user_id)->find();
        include 'extend/qrcode/phpqrcode.php';
        $class = self::find($class_id);

        $data = [];
        $data['user'] = $user->toArray();
        $data['user']['password'] = md5($data['user']['password']);
        $data['class'] = ['class_id' => $class_id];
//        $cfg = [
//            'version' => config('app_android_version'),
//            'descr' => config('app_android_descr'),
//            'apk' => config('app_android_apk'),
//        ];
//        $data['app'] = $cfg;
        $newdata = array_merge($data['user'], $data['class']);
//        $newdata = array_merge($newdata, $data['app']);

        $str = substr(config('custom.site_url'), 0, -1).url('index/qrcode').'?';
        $paras = [];
        foreach ($newdata as $k=>$val) {
            $paras[] = "$k=$val";
        }
        $str .= implode("&", $paras);
//        echo $str."<br/>";exit;
//        $str = json_encode($data);
        QRcode::png($str);
    }

}
