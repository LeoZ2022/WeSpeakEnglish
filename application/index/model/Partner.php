<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/9/28
 * Time: 21:51
 */

namespace app\index\model;

use app\cms\model\Classsn;
use app\index\service\Classin;
use app\index\service\ImageCode;
use app\user\validate\User;
use think\Db;
use think\helper\Hash;
use think\Model;
use think\Validate;

class Partner extends Model
{

    // 设置当前模型对应的完整数据表名称
    protected $name = 'cms_partner';

    // 自动写入时间戳
    protected $autoWriteTimestamp = true;

    //重发验证邮件
    public function resend($email)
    {
        $code = generate_rand_str(9, 3);
        $data['email_verify_code'] = md5($code);
        $info = Partner::where('email', $email)->find();
        Partner::where('email', $email)->setField('email_verify_code', $data['email_verify_code']);
        $res = $this->email_verify($info['id'], $email, $code, $info['username']);
        return $res;
    }

    //发送验证码
    public static function send_code($user_id)
    {
        $session_time = session('email_time');
        $time = time();
        if ($session_time && ($time - $session_time < 60)) {
            return ['code' => 202, 'msg' => 'Wait', 'times' => $time - $session_time ];
        }
        $code = generate_rand_str(9, 3);
        $email_verify_code = md5($code);
        $info = Partner::where('id', $user_id)->find();
        $email = $info['email'];
        $username = $info['username'];

        $content = file_get_contents("template/emailcode.html");
        $find = ['{code}', '{username}'];
        $replace = [$code, $username];
        $content = str_replace($find, $replace, $content);
        $res = send_email($email, 'Email Verification code', $content);
        session('email_time', $time);
        session('email_code', $code);

        return ['code'=>200, 'msg' => 'Send successfully'];
    }

    //机构注册
    public function register_parnter($data)
    {
        $lang = 'en';
        $txt = config('lang.'.$lang);
        $rule = [
            'email' => 'require|email|unique:cms_users',
            'username'  => 'require|max:25',
            'verify_code'   => 'require',
            'country_id'   => 'require|number',
            'province'   => 'require|number',
            'city'   => 'require|number',
            'new_pass'   => 'require|min:6|max:20',
            'new_pass2'   => 'require|confirm:new_pass',
        ];
        if ($data['user_type'] == 1) {
            $data['country_id'] = $data['country_id2'];
            unset($rule['province'], $rule['city']);
        }

        $msg = [
            'mobile.require'        => $txt['goals'].$txt['empty'],
            'verify_code.require'        => $txt['verify_code'].$txt['empty'],
            'email.require'        => $txt['email'].$txt['empty'],
            'email.email'        => $txt['email'].$txt['format'],
            'username.require' => $txt['username'].$txt['empty'],
            'username.max'     => $txt['username'].$txt['format'],
            'country_id.require'   => $txt['country'].$txt['empty'],
            'province.require'   => 'Province/State'.$txt['empty'],
            'city.require'   => 'City'.$txt['empty'],
            'province.number'   => 'Province/State'.$txt['format'],
            'new_pass.require'   => $txt['password'].$txt['empty'],
            'new_pass.min'   => $txt['password'].$txt['password_error'],
            'new_pass.max'   => $txt['password'].$txt['password_error'],
            'new_pass2.require'   => $txt['password2'].$txt['empty'],
            'new_pass2.confirm'   => $txt['password2_error'],
        ];

        $validate   = Validate::make($rule,$msg);
        $result = $validate->check($data);

        if(!$result) {
            return ['code' => 201, 'msg' => $validate->getError()];
        }
        $obj = new ImageCode();
        if (!$obj->check_code($data['verify_code'])) {
            return ['code' => 201, 'msg' => $txt['verify_code'].$txt['error'] ];
        }
        $data['is_partner'] = 1;
        $data['create_ip'] = get_client_ip();
        $data['password'] = Hash::make((string)$data['new_pass']);
        $data['invitation_code'] = get_invitation_code();
        $data['price_partner'] = $data['user_type'] == 2 ? config('cfg_commission_teacher') : config('cfg_commission_student');

        $code = generate_rand_str(9, 3);
        $data['email_verify_code'] = md5($code);
        unset($data['new_pass'], $data['new_pass2']);
//        $user_id = $this->insertGetId($data);
//        echo $user_id;exit;

        $data['classin_uid'] = $this->create_partner($data['country_id']);
        $data['create_time'] = time();
        if ($user_id = $this->insertGetId($data)) {
            $this->email_verify($user_id, $data['email'], $code, $data['username']);
            return ['code' => 200, 'msg' => $txt['register_success'] ];
        } else {
            return ['code' => 201, 'msg' => $txt['register_error'] ];
        }
    }

    //生成机构编号
    public function create_partner($country_id)
    {
        $country = Country::find($country_id);
        $ch = strtoupper(substr($country['country_name'], 0, 2));
        while(1) {
            $sn = $ch.generate_rand_str(4,3);
            if (!$this->where('class_sn', $sn)->find()) {
                return $sn;
            }
        }
    }

    //注册成功，发送邮箱验证码
    public function email_verify($user_id, $email, $code, $username, $sn = '')
    {
        $url = config('web_site_url').url('index/index/email_verify', ['uid' => $user_id, 'code' => $code ]);

        $content = file_get_contents("template/email_verify.html");
        $find = ['{email}', '{username}', '{verify_url}', '{sn}'];
        if ($sn) {
            $sn = "Your Account No is: ". $sn."<br/>";
        }
        $replace = [$email, $username, $url, $sn];
        $content = str_replace($find, $replace, $content);
        $res = send_email($email, 'Email Verification', $content);
        return $res;
    }

    //找回密码
    public static function forgot($data)
    {
        $validate = new \app\index\validate\Users();
        $result = $validate->check($data, [], 'forgot');
        if ($result !== true) {
            return ['code' => 201, 'msg' => $validate->getError() ];
        }
        $lang = 'en';
        $txt = config('lang.'.$lang);
        if (isset($data['sessionid'])) {
            if (cache($data['sessionid']) != $data['verify_code']) {
                return ['code' => 201, 'msg' => $txt['verify_code'] . $txt['error']];
            }
        } else {
            $obj = new ImageCode();
            if (!$obj->check_code($data['verify_code'])) {
                return ['code' => 201, 'msg' => $txt['verify_code'] . $txt['error']];
            }
        }
        $user = self::where(['email' => $data['email'], 'status' => 1])->find();
        if ($user) {
            $code = generate_rand_str(9, 3);
            $password = Hash::make((string)$code);
            self::where(['email' => $data['email'], 'status' => 1])->setField('password', $password);

            if ($user['user_type'] == 1) {
                $url = config('web_site_url') . url('index/sign/learner');
            } else {
                $url = config('web_site_url') . url('index/sign/tutor');
            }
            $content = file_get_contents("template/forgot.html");
            $find = ['{email}', '{password}', '{url}'];
            $replace = [$user['username'], $code, $url];
            $content = str_replace($find, $replace, $content);
            $result = send_email($user['email'], 'Forgot password', $content);
            return ['code' => 200, 'msg' => 'Email send successfully!' ];
        } else {
            return ['code' => 201, 'msg' => 'Email does not exist!' ];
        }
    }

    //修改密码
    public static function edit_pass($data, $user)
    {
        $validate = new \app\index\validate\Users();
        $result = $validate->check($data, [], 'edit_pass');
        if ($result !== true) {
            return ['code' => 201, 'msg' => $validate->getError() ];
        }
        //判断原密码是否匹配
        if (! Hash::check((string)$data['old_pass'], $user['password'])) {
            return ['code' => 201, 'msg' => 'Old Password error' ];
        }
        $newpass = Hash::make((string)$data['new_pass']);
        self::where('id', $user['id'])->setField('password', $newpass);
        return ['code' => 200, 'msg' => 'Change password successfully!' ];
    }

    //修改个人信息
    public static function edit_profile_partner($data, $user)
    {
        $validate = new \app\index\validate\Users();
        if ($user['user_type'] == 1) {
            $result = $validate->check($data, [], 'edit_profile_learner');
            if ($result !== true) {
                return ['code' => 201, 'msg' => $validate->getError()];
            }
        } else {
            $result = $validate->check($data, [], 'edit_profile_tutor');
            if ($result !== true) {
                return ['code' => 201, 'msg' => $validate->getError()];
            }
        }
        $obj = new Partner();
        $data['accept_email'] = input('accept_email', 0);
        $obj->allowField('country_id, province, city, paypal_account, mobile, username, accept_email')->where('id', $user['id'])->update($data);
        return ['code' => 200, 'msg' => 'Profile updates successfully!' ];
    }

    //登录
    public function signin($data, $user_type = 1, $app = 0, $partner = 0)
    {
        $validate = new \app\index\validate\Users();
        if ($app==1) {
            $result = $validate->check($data, [], 'signinapp');
            if ($result !== true) {
                return ['code' => 201, 'msg' => $validate->getError()];
            }
        } else {
            $result = $validate->check($data, [], 'signin');
            if ($result !== true) {
                return ['code' => 201, 'msg' => $validate->getError()];
            }
            $obj = new ImageCode();
            if (!$obj->check_code($data['verify_code'])) {
                return ['code' => 201, 'msg' => 'Verification code error'];
            }
        }
        $user = $this->where(['email' => $data['email']])->find();
        if (!$user) {
            return ['code' => 201, 'msg' => 'Email does not exist!' ];
        }
        if (!$user['email_verify']) {
            return ['code' => 202, 'msg' => 'Your email has not been verified. Please check your email. If you did not receive a verification email, please click "Send again".' ];
        }
        if (!$user['status']) {
            return ['code' => 201, 'msg' => 'Account is disabled!' ];
        }
        if ($user['is_locked']) {
            return ['code' => 201, 'msg' => 'Your Account is locked. Please contact the web administrator!' ];
        }
        if ( !Hash::check((string)$data['password'], $user['password']) ){
            return ['code' => 201, 'msg' => "Password error" ];
        }
        $ret = ['code' => 200, 'msg' => "Sign in successful"];
        session('user_id', $user['id']);
        session('username', $user['username']);
        session('user_type', $user['user_type']);
        session('location', $user['location']);
        session('is_partner', 1);
        cookie('user_type', $user['user_type']);
        cookie('is_partner', 1);

        return $ret;
    }

    //验证邮箱
    public static function check_email($uid, $code)
    {
        $pwd = md5($code);
        $user = self::where('id', $uid)->find();
        if (!$user) {
            return ['code' => 201, 'msg' => "User does not exist" ];
        }
        if ($user['email_verify_code'] != $pwd) {
            return ['code' => 201, 'msg' => "Email verification failed" ];
        }
        $update = ['email_verify_code' => '', 'email_verify' => 1];
        if (self::where('id', $uid)->update($update)) {
            //发站内消息
            $msg_template = NoticeTemplate::find(10);
            $time = time();
            $msg = $msg_template['content'];
            $notice = ['user_id' => $user['id'], 'subject' => $msg_template['subject'], 'msg' => $msg, 'create_time' => $time];
            Db::name('cms_notice')->insert($notice);

            $content = file_get_contents("template/partner_admin"  . ".html");
            $country = Country::find($user['country_id']);
            $region = $country['country_name'];
            if ($user['user_type'] == 2) {
                $province = Regions::find($user['province']);
                $city = Regions::find($user['city']);
                $region .= ' '. $province['region_name']. ' '. $city['region_name'];
            }
            $find = ['{name}', '{region}'];
            $replace = [$user['username'], $region];
            $content = str_replace($find, $replace, $content);
            send_email3(config('cfg_email'), 'New Partner from '.$country['country_name'], $content);

            $content = file_get_contents("template/partner_verifyed_" . $user['user_type'] . ".html");
            $find = ['{email}'];
            $replace = [$user['username']];
            $content = str_replace($find, $replace, $content);
            send_email($user['email'], 'Welcome to join WeSpeakEnglish!', $content);

            return ['code' => 200, 'msg' => "Email verifed successfully", 'user_type'=>$user['user_type'], 'is_partner'=>$user['is_partner']];
        } else {
            return ['code' => 201, 'msg' => "Email verification failed" ];
        }
    }

    //修改提现账号
    public static function edit_pay_account($user_id)
    {
        $paypal_account = input('paypal_account');
        if ($paypal_account) {
            self::where('id', $user_id)->setField('paypal_account', $paypal_account);
            return ['code' => 200, 'msg' => "Update successfully"];
        } else {
            return ['code' => 201, 'msg' => "Paypal account can't be empty." ];
        }
    }

    //每日提醒
    public static function send_email_date()
    {
        $time0 = strtotime(date('Y-m-d', strtotime('-1 day')));
        $date = date('Y-m-d', $time0);
        $lists = Users::where(['accept_email' => 1, 'is_partner' => 1, 'status' => 1])->column('id, email, username, moneys, user_type');

        $user_cnt_total = Users::where('partner_id', 'gt', 0)->where(['status' => 1])->group('partner_id')->field('partner_id, count(*) as cnt')->select();
        $user_cnt_active = Users::where('partner_id', 'gt', 0)->where('class_num|has_active', 'gt', 0)->where(['status' => 1])->group('partner_id')->field('partner_id, count(*) as cnt')->select();

        $user_cnt_date = Users::where('partner_id', 'gt', 0)->where('create_time','gt',$time0)->where(['status' => 1])->group('partner_id')->field('partner_id, count(*) as cnt')->select();
        foreach ($user_cnt_total as $row) {
            if ( $lists[$row['partner_id']] ) {
                $lists[$row['partner_id']]['cnt_total'] = $row['cnt'];
            }
        }
        foreach ($user_cnt_active as $row) {
            if ( $lists[$row['partner_id']] ) {
                $lists[$row['partner_id']]['cnt_active'] = $row['cnt'];
            }
        }
        foreach ($user_cnt_date as $row) {
            if ( $lists[$row['partner_id']] ) {
                $lists[$row['partner_id']]['cnt_date'] = $row['cnt'];
            }
        }
        $content = file_get_contents("template/partner_count.html");
        foreach ($lists as $uid => $u) {
            $content_new = $content;
            $content_new = str_replace(['{date}', '{cnt_total}', '{cnt_active}', '{cnt_date}', '{moneys}'], [$date,($u['cnt_total'] ?? 0),($u['cnt_active'] ?? 0),($u['cnt_date'] ?? 0),$u['moneys']  ], $content_new);
//            $content = $date." 总推荐数：".($u['cnt_total'] ?? 0)."，活跃数：".($u['cnt_active'] ?? 0)."，当日新增人数：".($u['cnt_date'] ?? 0).'，账户余额：'.$u['moneys'];
            $res = send_email($u['email'], 'Daily updated data', $content_new);
//            echo $content_new; var_dump($res);exit;
        }

    }

}