<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/9/28
 * Time: 21:51
 */

namespace app\index\model;

use think\Db;
use think\Model;

class Payment extends Model
{

    // 设置当前模型对应的完整数据表名称
    protected $name = 'cms_payment';

    //支付回调
    public static function notify_snappay($json_obj)
    {
        $out_order_no = substr($json_obj['out_order_no'], 0, -4);
        $trans_currency = $json_obj['trans_currency'];
        $trans_amount = $json_obj['trans_amount'];
        $json_obj['trans_end_time'] = $json_obj['trans_end_time'] ?? date('Y-m-d H:i:s');
        $info = self::where('order_sn', $out_order_no)->find();
        $url = url('member/accounts');
        if ($info['type'] == 1) {
            $url = url('tutors/succeed', ['order_id' => $info['obj_id']]);
        }
        if ($info['pay_status'] == 0) {
            $money = $info['money'];
            if ($trans_currency == 'CAD') {
                $money = round($money * 1.3, 2);
            }
            if ($trans_amount == $money || $trans_amount = 0) {
                $update = ['pay_time' => strtotime($json_obj['trans_end_time']), 'pay_status' => 1, 'pay_note' => $json_obj['trans_no']];
                if ($info['type'] == 1) {
                    //订单
                    $oinfo = Order::find($info['obj_id']);
                    self::where('id', $info['id'])->update($update);
                    Order::where('id', $info['obj_id'])->update($update);
                    $res = Order::update_payment($oinfo);
                    if ($res['code'] != 200) {
                        error_log('支付后更新订单失败: '.$oinfo['order_sn']."\n", 3, 'public/logs/snappay_'.date('Y-m-d').'.txt');
                    } else {
                        error_log('支付后更新订单: '.$info['obj_id']."\n", 3, 'public/logs/snappay_'.date('Y-m-d').'.txt');
                    }
                } 
                
                //topup
                if ($info['type'] == 3) {
                    self::where('id', $info['id'])->update($update);//成功

                }
                
                if ($info['type'] == 2) {
                    //捐款
                    self::where('id', $info['id'])->update($update);
                    Donation::where('id', $info['obj_id'])->update($update);
                    // $acc_data = ['user_id' => $info['user_id'], 'type_id' => 11, 'obj_id' => $info['obj_id'], 'money' => $info['money']* -1, 'create_time' => time(), 'pay_type' => 2];
                    
                    // //生成账户流水
                    // Db::name('cms_account_log')->insertGetId($acc_data);
                }                
            } else {
                error_log('支付后更新订单失败: '.$trans_amount.'=='.$money."\n", 3, 'public/logs/snappay_'.date('Y-m-d').'.txt');
            }
        }
		$info = self::where('order_sn', $out_order_no)->find();
		if ($info['pay_status'] == 1 && $info['type'] == 3) {
				$update = ['pay_time' => strtotime($json_obj['trans_end_time']), 'pay_status' => 1, 'pay_note' => $json_obj['trans_no']];
				Topup::where('id', $info['obj_id'])->update($update);
				$user = Users::find($info['user_id']);
				$acc_data = ['user_id' => $info['user_id'], 'type_id' => 11, 'obj_id' => $info['obj_id'], 'money' => $money, 'create_time' => time(), 'pay_type' => 2];//type_id=11 topup paytype=2 onlne payment 
	//                     //生成账户流水
				if (!(Db::name('cms_account_log')->insertGetId($acc_data))) {
					Db::rollback();
					return ['code' => 201, 'msg' => 'System error'];
				}
//                     //topup bonus log
                $bonus = 0;
				// if ($info['money'] >= 0.05 && $info['money'] <= 0.1) {
				// 	$bonus_data = ['user_id' => $info['user_id'], 'type_id' => 12, 'obj_id' => $info['obj_id'], 'money' => $money*0.15, 'create_time' => time(), 'pay_type' => 2];// type_id=12 bonus
				//     $bonus = $money*0.15;
				    
				// }
				
				if ($info['money'] >= 10 && $info['money'] <= 19.99) {
					$bonus_data = ['user_id' => $info['user_id'], 'type_id' => 12, 'obj_id' =>  $info['obj_id'], 'money' => $money*0.15, 'create_time' => time(), 'pay_type' => 2];// type_id=12 bonus
				
				    $bonus = $money*0.15;
				}
				if ($info['money'] >= 20 && $info['money'] <= 49.99) {
					$bonus_data = ['user_id' => $info['user_id'], 'type_id' => 12, 'obj_id' =>  $info['obj_id'], 'money' => $money*0.25, 'create_time' => time(), 'pay_type' => 2];
				
				    $bonus = $money*0.25;
				}   
				if ($info['money'] >= 50 && $info['money'] <= 99.99) {
					$bonus_data = ['user_id' => $info['user_id'], 'type_id' => 12, 'obj_id' =>  $info['obj_id'], 'money' => $money*0.3, 'create_time' => time(), 'pay_type' => 2];
				
				    $bonus = $money*0.3;
				}   
				if ($info['money'] >= 100) {
					$bonus_data = ['user_id' => $info['user_id'], 'type_id' => 12, 'obj_id' =>  $info['obj_id'], 'money' => $money*0.5, 'create_time' => time(), 'pay_type' => 2];
				
				    $bonus = $money*0.5;
				}
				$moneys = $user['moneys'] + $money + $bonus;
				Db::name('cms_users')->where('id', $user['id'])->setField('moneys', $moneys);
        		$content = file_get_contents("template/topup.html");
        		$finds = ['{username}','{id}', '{amount}','{pay_method}'];
        		$repls = [$user['username'], $user['id'], $money, $info['pay_type']];
        		$content = str_replace($finds, $repls, $content);
        		$subject = 'Topup '.$money.' by '.$user['username'];
        		send_email3('liyan.zhao@outlook.com', $subject, $content);
                        
				// Db::name('cms_account_log')->insertGetId($bonus_data);                        
				if (!(Db::name('cms_account_log')->insertGetId($bonus_data))) {
					Db::rollback();
					return ['code' => 201, 'msg' => 'System error'];
				}

			}
        return $url;
    }

}