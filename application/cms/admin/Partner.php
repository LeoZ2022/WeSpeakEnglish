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
use app\index\model\Regions;
use app\index\model\Users as UserModel;
use think\helper\Hash;

class Partner extends Admin
{

    //管理
    public function index()
    {
        $countrys = \app\index\model\Country::where('status', 1)->column('id, country_name');

        $map = $this->getMap();
        $map[] = ['is_partner', 'eq', 1];
        $country_id = input('country_id');
        $user_type = input('user_type');
        if ($country_id) {
            $map[] = ['country_id', 'eq', $country_id];
        }
        if ($user_type) {
            $map[] = ['user_type', 'eq', $user_type];
        }
        $list = UserModel::where($map)->order('id desc')->paginate();

        $this->assign('countrys', $countrys);
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
            $this->error('找不到机构数据');
        }

        $countrys = \app\index\model\Country::where('status', 1)->column('id, country_name');
        $info['create_time'] = date('Y-m-d H:i:s', $info['create_time']);
        $info['password'] = '';
        $info['country'] = $countrys[$info['country_id']];
        if ($info['user_type'] == 1) {
            // 学生
            $info['type'] = '学生';
            return ZBuilder::make('form')
                ->addFormItems([
                    ['hidden', 'id'],
                    ['static', 'type', '机构类型'],
                    ['static', 'country', '国家'],
                    ['static', 'username', '昵称'],
                    ['static', 'email', 'Email'],
                    ['static', 'price_partner', '费率'],
                    ['radio', 'is_locked', '状态', '', ['正常','禁用']],
                    ['radio', 'email_verify', '邮箱验证', '', ['未验证','通过']],
                    ['static', 'create_time', '注册时间'],
                    ['text', 'password', '登录密码', '不修改请留空'],
                ])->setFormData($info)->fetch();
        } else {
            $province = Regions::where(['id'=>$info['province']])->find();
            $info['province_txt'] = $province['region_name'];
            $province = Regions::where(['id'=>$info['city']])->find();
            $info['city_txt'] = $province['region_name'];
            $info['region'] = $info['country'].', '.$info['province_txt'].', '.$info['city_txt'];
            //老师
            $info['type'] = '老师';
            return ZBuilder::make('form')
                ->addFormItems([
                    ['hidden', 'id'],
                    ['static', 'type', '机构类型'],
                    ['static', 'region', '国家'],
                    ['static', 'username', '昵称'],
                    ['static', 'email', 'Email'],
                    ['static', 'price_partner', '费率'],
                    ['radio', 'is_locked', '状态', '', ['正常','禁用']],
                    ['radio', 'email_verify', '邮箱验证', '', ['未验证','通过']],
                    ['static', 'create_time', '注册时间'],
                    ['text', 'password', '登录密码', '不修改请留空'],
                ])->setFormData($info)->fetch();
        }
    }

}