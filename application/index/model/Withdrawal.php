<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/9/28
 * Time: 21:51
 */

namespace app\index\model;

use think\Db;
use think\Exception;
use think\Model;
use think\Validate;

class Withdrawal extends Model
{

    // 设置当前模型对应的完整数据表名称
    protected $name = 'cms_withdrawal';

    /**
     * 提现
     * @param $user
     * @param $money
     * @param $account
     */
    public function cashout($user_id, $money)
    {
        $user = Users::find($user_id);
        $account = $user['paypal_account'];
        $validate = new \app\index\validate\Withdrawal();
        $data = ['user_id' => $user['id'], 'money' => $money, 'account' => $account];
        $result = $validate->check($data);
        if ($result !== true) {
            return ['code' => 201, 'msg' => $validate->getError()];
        }
        if (!($money > 0)){
            return ['code' => 201, 'msg' => 'The withdrawal amount must be greater than 0'];
        }
        if ($money > $user['moneys']) {
            return ['code' => 201, 'msg' => 'The withdrawal amount cannot exceed the account balance'];
        }
        if ($user['is_partner'] == 1) {
            //如果是机构
            $code = input('code');
            $data['note'] = input('note');
            if ($code != session('email_code')) {
                return ['code' => 201, 'msg' => 'Email Verification code error.'];
            }
            if ($money < 50) {
                return ['code' => 201, 'msg' => 'Withdrawal cannot be less then $50.'];
            }
            if ($user['user_type'] == 1) {
                //学生机构必须在注册2周后
                $time14 = strtotime('-14 day');
                $last = self::where('user_id', $user_id)->where('state', 'lt', 2)->order('id desc')->find();
                if ($last && $last['create_time'] > $time14) {
                    return ['code' => 201, 'msg' => 'The interval between two redemptions must be greater than 2 weeks.'];
                }
            }
        }

        $time = time();
        Db::startTrans();
        try {
            $data['create_time'] = $time;
            $moneys = $user['moneys'] - $money;
            $moneys = $moneys >=0 ? $moneys : 0;
            $data['user_money'] = $moneys;
            if ($log_id = Db::name('cms_withdrawal')->insertGetId($data)) {
                $acc_data = ['user_id' => $user['id'], 'type_id' => 4, 'obj_id' => $log_id, 'money' => $money * -1, 'create_time' => $time ];
                if (Db::name('cms_account_log')->insertGetId($acc_data)) {
                    Db::name('cms_users')->where('id', $user['id'])->setField('moneys', $moneys);

                    //发送站内消息和邮件
                    $msg_template = NoticeTemplate::find(9);
                    $content = file_get_contents("template/withdrawal.html");
                    $content = str_replace('{money}', $money, $content);
                    send_email($user['email'], $msg_template['subject'], $content);

                    $msg = str_replace('{money}', $money, $msg_template['content']);
                    $notice = ['user_id' => $user['id'], 'subject' => $msg_template['subject'], 'msg' => $msg, 'create_time' => $time];
                    Db::name('cms_notice')->insert($notice);

                    Db::commit();
                    session('email_code', null);
                    return ['code' => 200, 'msg' => 'Your withdrawal request submitted. We have sent a confirmation email to you.'];
                }
            }
            Db::rollback();
            return ['code' => 201, 'msg' => 'System error'];
        } catch (Exception $e) {
            Db::rollback();
            return ['code' => 201, 'msg' => $e->getMessage()];
            // die(); // 终止异常
        }
    }

    /**
     * 审核提现
     * @param $info
     * @param $state：1通过，2失败
     */
    public function confirm($info, $state)
    {
        $time = time();
        $update = ['state' => $state, 'success_time' => $time ];
        Db::startTrans();
        try {
            if ($this->where('id', $info['id'])->update($update)) {
                $user = Users::find($info['user_id']);
                if ($state == 1) {
                    //通过
                    $txt = '审核通过';
                } else {
                    $txt = '审核失败';
                    $acc_data = ['user_id' => $user['id'], 'type_id' => 4, 'obj_id' => $info['id'], 'money' => $info['money'], 'create_time' => $time, 'descr' => 'Withdrawal failure', 'descr_admin' => '提现失败' ];
                    if (Db::name('cms_account_log')->insertGetId($acc_data)) {
                        $moneys = $user['moneys'] + $info['money'];
                        Db::name('cms_users')->where('id', $user['id'])->setField('moneys', $moneys);
                    } else {
                        Db::rollback();
                        $ret = ['code' => 201, 'msg' => '更新失败'];
                        return $ret;
                    }
                }
                action_log('withdraw_check', 'cms_withdrawal', $info['id'], UID, $txt . ':' . $user['username'] . '提现' . $info['money']);
                Db::commit();
                $ret = ['code' => 200, 'msg' => '操作成功'];
            } else {
                Db::rollback();
                $ret = ['code' => 201, 'msg' => '更新失败'];
            }
        } catch (Exception $e) {
            Db::rollback();
            $ret = ['code' => 201, 'msg' => $e->getMessage()];
            // die(); // 终止异常
        }
        return $ret;
    }

}