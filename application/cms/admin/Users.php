<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/10/23
 * Time: 9:23
 */

namespace app\cms\admin;

use app\admin\controller\Admin;
use app\common\builder\ZBuilder;
use app\index\model\Users as UserModel;
use app\index\model\Daytime; 
use think\helper\Hash;
use think\Db;
use think\Exception;
use think\Model;

class Users extends Admin
{

    //学生管理
    public function index()
    {
        $english_level = config('english_level');
        $native_language = config('native_language');
        $locations = \app\index\model\Location::where('status', 1)->column('id, location_name');
        $zone = \app\index\model\Location::where('status', 1)->column('id, timezone');
        $map = $this->getMap();
        $map[] = ['user_type', 'eq', 1];
        $map[] = ['is_partner', 'eq', 0];
        $location = input('location');
        $partner_id = input('partner_id');
        if ($location) {
            $map[] = ['location', 'eq', $location];
        }
        if ($partner_id) {
            $map[] = ['partner_id', 'eq', $partner_id];
        }
        $list = UserModel::where($map)->order('id desc')->paginate();

        $this->assign('locations', $locations);
        $this->assign('zone', $zone);
        $this->assign('native_language', $native_language);
        $this->assign('english_level', $english_level);
        $this->assign('list', $list);
        return $this->fetch();
    }

    //老师
    public function teacher()
    {
        $map = $this->getMap();
        $partner_id = input('partner_id');
        $map[] = ['user_type', 'eq', 2];
        $map[] = ['is_partner', 'eq', 0];
        $location = input('location');
        if ($location) {
            $map[] = ['location', 'eq', $location];
        }
        if ($partner_id) {
            $map[] = ['partner_id', 'eq', $partner_id];
        }
        $list = UserModel::where($map)->order('id desc')->paginate();

        $locations = \app\index\model\Location::where('status', 1)->column('id, location_name');
        $this->assign('locations', $locations);
        $this->assign('list', $list);
        return $this->fetch();
    }

    public function edit($id=0)
    {
        $info = UserModel::find($id);
        if ($this->request->isAjax()) {
            $password = input('password');
            $close_state = input('close_state', 0);
            $status = input('is_locked', 0);
            $email_verify = input('email_verify', 0);
            $add_money = input('add_money'); // Retrieve the "Add Money" amount

        // Additional logic to handle the "Add Money" functionality
        // Example: Add the specified amount of money to the user's account
        if ($add_money) {
            // Perform the necessary operations to add money
            // Example: Update the user's account balance with the provided amount
            $info['moneys'] += $add_money;
            // Save the updated user information
            $info->save();
            // Generate account log for adding money
            $moneys = $add_money;
            $time = time();
            Db::startTrans();
            try {
                // Update the 'moneys' field in 'cms_users' table
               // Db::name('cms_users')->where('id', $info['id'])->setInc('moneys', $moneys);
        
                // Insert a new record in 'cms_account_log' table
                $acc_data = [
                    'user_id' => $info['id'],
                    'type_id' => 9, // Type ID for adding money (adjust if necessary)
                    'obj_id' => $id,
                    'money' => $moneys,
                    'create_time' => $time
                ];
                // Insert the account log record
                $logId = Db::name('cms_account_log')->insertGetId($acc_data);
        
                if (!$logId) {
                    Db::rollback();
                    return ['status' => 0, 'msg' => 'System error'];
                }
        
                Db::commit();
            } catch (\Exception $e) {
                Db::rollback();
                return ['status' => 0, 'msg' => 'Error: ' . $e->getMessage()];
            }            

         
    }   

            $update = ['close_state' =>$close_state, 'is_locked' => $status, 'email_verify' => $email_verify];
            if ($password) {
                $pwd = Hash::make((string)$password);
                $update['password'] = $pwd;
            }
            UserModel::where('id', $id)->update($update);

            $user = UserModel::find($id);
            action_log('edit_user', 'cms_users', $id, UID, $user['username']);
            $this->success('修改成功');
        }
        if (!$info) {
            $this->error('找不到会员数据');
        }

        $locations = \app\index\model\Location::where('status', 1)->column('id, location_name');
        $info['location'] = $locations[$info['location']] ?? '-';
        $info['create_time'] = date('Y-m-d H:i:s', $info['create_time']);
        $info['password'] = '';
        if ($info['user_type'] == 1) {
            $english_level = config('english_level');
            $native_language = config('native_language');
            $info['native_language'] = $native_language[$info['native_language']] ?? '-';
            $info['english_level'] = $english_level[$info['english_level']] ?? '-';

            // 学生
            return ZBuilder::make('form')
                ->addFormItems([
                    ['hidden', 'id'],
                    ['static', 'username', 'Name'],
                    ['static', 'email', 'Email'],
                    ['static', 'moneys', 'Money'],
                    ['number', 'add_money', 'Add Money'],
                    ['static', 'location', 'Time Zone'],
                    ['static', 'native_language', 'First Language'],
                    ['static', 'english_level', 'English level'],
                    ['static', 'learn_years', 'Years of learning'],
                    ['static', 'goals', 'Goal'],
                    ['static', 'descr', 'Self-introduction'],
                    ['radio', 'is_locked', 'Status', '', ['Normal','Forbidden']],
                    ['radio', 'email_verify', 'Email Verification', '', ['Unverified','Verified']],
                    ['static', 'create_time', 'Sign up time'],
                    ['text', 'password', 'Password', 'Leave blank if no change'],
                ])->setFormData($info)->fetch();
        } else {
            //老师
            return ZBuilder::make('form')
                ->addFormItems([
                    ['hidden', 'id'],
                    ['static', 'username', '昵称'],
                    ['static', 'email', 'Email'],
                    ['static', 'mobile', '手机'],
                    ['static', 'location', '时区'],
                    ['static', 'price', '课程价格'],
                    ['static', 'descr', '个人介绍'],
                    ['radio', 'close_state', '关闭', '', ['否', '是'], 0],
                    ['radio', 'is_locked', '状态', '', ['正常','禁用']],
                    ['radio', 'email_verify', '邮箱验证', '', ['未验证','通过']],
                    ['static', 'create_time', '注册时间'],
                    ['text', 'password', '登录密码', '不修改请留空'],
                ])->setFormData($info)->fetch();
        }
    }
}
