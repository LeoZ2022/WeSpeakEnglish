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
use think\Validate;

class Donation extends Model
{

    // 设置当前模型对应的完整数据表名称
    protected $name = 'cms_donation';

    /**
     * 捐赠
     * @param $user
     * @param $money
     * @param int $user_money
     * @param int $pay_type
     * @return array
     */
    public function to_send($user, $money, $pay_type = 1)
    {
        $validate = new \app\index\validate\Donation();
        $data = ['user_id' => $user['id'], 'order_amount' => $money, 'pay_type' => $pay_type];
        $user_money = $user['moneys'] > $money ? $money : $user['moneys'];
        $withdraw = input('withdraw');
        if (!$withdraw) {
            $user_money = 0;
        }
        $data['user_money'] = $user_money;
        $data['pay_money'] = $money - $user_money;
        $result = $validate->check($data);
        if ($result !== true) {
            return ['code' => 201, 'msg' => $validate->getError()];
        }
        if ($data['pay_money'] > 0 && !$data['pay_type']) {
            return ['code' => 201, 'msg' => 'Please select Payment method'];
        }
        if (!($money > 0)) {
            return ['code' => 201, 'msg' => 'The Donation amount must be greater than 0'];
        }
        $time = time();
        $ret = ['code' => 200, 'msg' => 'Submit successfully!'];
        Db::startTrans();
        try {
            $data['create_time'] = $time;
            if ($log_id = Db::name('cms_donation')->insertGetId($data)) {
                if ($user_money && $user['moneys'] > 0) {
                    //如果使用账户余额
                    $moneys = $user['moneys'] - $user_money;
                    $moneys = $moneys >= 0 ? $moneys : 0;
                    Db::name('cms_users')->where('id', $user['id'])->setField('moneys', $moneys);
                    $acc_data = ['user_id' => $user['id'], 'type_id' => 2, 'obj_id' => $log_id, 'money' => $money * -1, 'create_time' => $time];
                    //生成账户流水
                    if (!(Db::name('cms_account_log')->insertGetId($acc_data))) {
                        Db::rollback();
                        return ['code' => 201, 'msg' => 'System error'];
                    }
                }
                $ret['pay_money'] = $data['pay_money'];
                if ($data['pay_money'] > 0) {
                    //生成支付数据记录
                    $pdata = ['user_id' => $user['id'], 'pay_type' => $pay_type, 'type' => 2, 'obj_id' => $log_id, 'money' => $data['pay_money'], 'create_time' => $time];
                    $pdata['order_sn'] = get_pay_sn();
                    $pay_id = Db::name('cms_payment')->insertGetId($pdata);
                    if (!$pay_id) {
                        Db::rollback();
                        return ['code' => 201, 'msg' => 'System error'];
                    }
                    $ret['pay_id'] = $pay_id;
                    $ret['pay_url'] = url('payment/index', ['pay_id' => $pay_id ]);
                }
            }
            Db::commit();
            return $ret;
        } catch (Exception $e) {
            Db::rollback();
            return ['code' => 201, 'msg' => $e->getMessage()];
            // die(); // 终止异常
        }
    }


}