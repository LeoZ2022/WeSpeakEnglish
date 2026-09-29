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

class Users extends Model
{

    // 设置当前模型对应的完整数据表名称
    protected $name = 'cms_users';

    // 自动写入时间戳
    protected $autoWriteTimestamp = true;

    //学生注册
    public function register_studuent($data, $lang = 'cn')
    {
        $txt = config('lang.'.$lang);

        $rule = [
            'email' => 'require|email|unique:cms_users,is_partner=0&email='.$data['email'],
            'username'  => 'require|max:25',
            'native_language'   => 'require|number',
            'english_level'   => 'require',
            'learn_years'   => 'require',
//            'mobile'   => 'require|unique:cms_users',
//            'goals'   => 'require',
            'verify_code'   => 'require',
            'country_id'   => 'require|number',
            'location'   => 'require|number',
            'new_pass'   => 'require|min:6|max:20',
            'new_pass2'   => 'require|confirm:new_pass',
        ];

        $msg = [
            'english_level.require'        => $txt['english_level'].$txt['empty'],
            'learn_years.require'        => $txt['learn_years'].$txt['empty'],
            'goals.require'        => $txt['goals'].$txt['empty'],
            'mobile.require'        => $txt['mobile'].$txt['empty'],
            'mobile.unique'        => $txt['mobile'].$txt['already'],
            'verify_code.require'        => $txt['verify_code'].$txt['empty'],
            'email.unique'        => $txt['email'].$txt['already'],
            'email.require'        => $txt['email'].$txt['empty'],
            'email.email'        => $txt['email'].$txt['format'],
            'username.require' => $txt['username'].$txt['empty'],
            'username.max'     => $txt['username'].$txt['format'],
            'native_language.require'   => $txt['native_language'].$txt['empty'],
            'native_language.number'   => $txt['native_language'].$txt['format'],
            'country_id.require'   => $txt['country'].$txt['empty'],
            'location.require'   => $txt['location'].$txt['empty'],
            'location.number'   => $txt['location'].$txt['format'],
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
        $invitation_code = input('invitation_code');
        if ($invitation_code) {
            //推荐人
            $parent = Users::where('invitation_code', $invitation_code)->find();
            if ($parent) {
                $data['parent_id'] = $parent['id'];
            }
        }
        $partner = input('partner');
        //机构
        if ($partner) {
            $partner_user = Users::where(['classin_uid' => $partner, 'user_type' => 1, 'is_partner'=>1])->find();
            if ($partner_user) {
                $data['partner_id'] = $partner_user['id'];
            }
        }
        $data['user_type'] = 1;
        $data['create_ip'] = get_client_ip();
        $data['password'] = Hash::make((string)$data['new_pass']);
        $data['invitation_code'] = get_invitation_code();
        unset($data['new_pass'], $data['new_pass2'], $data['partner']);
     //   $data['price'] = 0; //default price 20221004 $18 changed on 20251224
        if ($invitation_code == 660327255)
		{$data['moneys']=18;}
		else
		{
        $data['moneys']=6;  //default $4 20220718 added 20251224 changed to 15,20260124 change to 6
        }
        $data['email_reminder']=1; //20220807, default on for learner
        $code = generate_rand_str(9, 3);
        $data['email_verify_code'] = md5($code);
//        $user_id = $this->insertGetId($data);

        $obj = new Users();
        $data['create_time'] = time();
        if ($user_id = $obj->insertGetId($data)) {
            $this->email_verify($user_id, $data['email'], $code, $data['username']);
            return ['code' => 200, 'msg' => $txt['register_success'] ];
        } else {
            return ['code' => 201, 'msg' => $txt['register_error'] ];
        }
    }

    //重发验证邮件
    public function resend($email)
    {
        $code = generate_rand_str(9, 3);
        $data['email_verify_code'] = md5($code);
        $is_parent = input('is_parent', 0);
        $info = Users::where(['email' => $email, 'is_partner' => $is_parent] )->find();
        Users::where('email', $email)->setField('email_verify_code', $data['email_verify_code']);
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
        $info = Users::where('id', $user_id)->find();
        $email = $info['email'];
        $username = $info['username'];

        $content = file_get_contents("template/emailcode.html");
        $find = ['{code}', '{username}'];
        $replace = [$code, $username];
        $content = str_replace($find, $replace, $content);
        $res = send_email_g($email, 'Email Verification code', $content);
        session('email_time', $time);
        session('email_code', $code);

        return ['code'=>200, 'msg' => 'Send success'];
    }

    //教师注册
    public function register_teacher($data)
    {
        $lang = 'en';
        $txt = config('lang.'.$lang);
      //  $data['price'] = round($data['price'], 2);
        $rule = [
            'email' => 'require|email|unique:cms_users,is_partner=0&email='.$data['email'],
            'username'  => 'require|max:25',
         //   'price'   => 'require',
            'verify_code'   => 'require',
            'country_id'   => 'require|number',
            'location'   => 'require|number',
            'new_pass'   => 'require|min:6|max:20',
            'new_pass2'   => 'require|confirm:new_pass',
        ];
        $reg_type = config('cfg_register_type');
        if ($reg_type == 1) {
            $rule['mobile'] = 'require|unique:cms_users';
            $sn = '';
        } else {
            $sn = Classsn::get_sn();
            if (!$sn) {
                return ['code' => 201, 'msg' => '' ];
            }
            $data['class_sn'] = $sn;
        }
        $msg = [
            'mobile.require'        => $txt['mobile'].$txt['empty'],
            'verify_code.require'        => $txt['verify_code'].$txt['empty'],
            'email.require'        => $txt['email'].$txt['empty'],
            'email.email'        => $txt['email'].$txt['format'],
            'username.require' => $txt['username'].$txt['empty'],
            'username.max'     => $txt['username'].$txt['format'],
            'descr.require'   => 'Introduce yourself'.$txt['empty'],
         //   'price.require'   => 'Class rate'.$txt['empty'],
            'country_id.require'   => $txt['country'].$txt['empty'],
            'location.require'   => $txt['location'].$txt['empty'],
            'location.number'   => $txt['location'].$txt['format'],
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

        $desc_type = input('desc_type');
        if ($desc_type == 1) {
            if (!input('mp3file')) {
                return ['code' => 201, 'msg' => 'Please upload mp3'];
            }
            $data['filesize'] = round(input('filesize'), 2);
            $data['duration'] = input('duration', 0, 'intval');
        } else {
            $data['descr'] = input('descr');
            if (!$data['descr']) {
                return ['code' => 201, 'msg' => 'Introduction can not be empty'];
            }
        }

        $price_class_max = config('price_class_max');
        $price_class_min = config('price_class_min');
    //    if ( $data['price'] > $price_class_max || $data['price'] < $price_class_min ) {
     //       return ['code' => 201, 'msg' => 'Chat rate must be between '.$price_class_min.'-'.$price_class_max];
     //   }
        $obj = new ImageCode();
        if (!$obj->check_code($data['verify_code'])) {
            return ['code' => 201, 'msg' => $txt['verify_code'].$txt['error'] ];
        }
        $invitation_code = input('invitation_code');
        if ($invitation_code) {
            //推荐人
            $parent = Users::where('invitation_code', $invitation_code)->find();
            if ($parent) {
                $data['parent_id'] = $parent['id'];
            }
        }
        $partner = input('partner');
        //机构
        if ($partner) {
            $partner_user = Users::where(['classin_uid' => $partner, 'user_type' => 2, 'is_partner'=>1])->find();
            if ($partner_user) {
                $data['partner_id'] = $partner_user['id'];
            }
        }
        $data['user_type'] = 2;
        $data['create_ip'] = get_client_ip();
        $data['password'] = Hash::make((string)$data['new_pass']);
        $data['invitation_code'] = get_invitation_code();
		$data['email_reminder']=1; //20220807, default on for tutor
		$data['price'] = 0; //default price 20221004
        $code = generate_rand_str(9, 3);
        $data['email_verify_code'] = md5($code);
        unset($data['new_pass'], $data['new_pass2']);

        $data['create_time'] = time();
        if ($user_id = $this->insertGetId($data)) {
            $this->email_verify($user_id, $data['email'], $code, $data['username'], $sn);
            return ['code' => 200, 'msg' => $txt['register_success'] ];
        } else {
            return ['code' => 201, 'msg' => $txt['register_error'] ];
        }
    }

    //机构注册
    public function register_parnter($data)
    {
        $lang = 'en';
        $txt = config('lang.'.$lang);
        $rule = [
            'email' => 'require|email|unique:cms_users,is_partner=1&email='.$data['email'],
            'username'  => 'require|max:25',
            'verify_code'   => 'require',
//            'mobile'   => 'require',
            'paypal_account'   => 'require',
            'country_id'   => 'require|number',
            'province'   => 'require|number',
            'city'   => 'require|number',
            'new_pass'   => 'require|min:6|max:20',
            'new_pass2'   => 'require|confirm:new_pass',
        ];
        if ($data['user_type'] == 1) {
            $data['country_id'] = $data['country_id2'];
            unset($rule['province'], $rule['city'], $rule['paypal_account']);
        }

        $msg = [
            'paypal_account.require'        => 'Paypal account'.$txt['empty'],
            'mobile.require'        => $txt['mobile'].$txt['empty'],
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
        $data['accept_email'] = input('accept_email', 0);
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

        if (session('country') == 'out') {
            $url = str_replace('.chat', '.chat', $url);
        }

        $content = file_get_contents("template/email_verify.html");
        $find = ['{email}', '{username}', '{verify_url}'];
        if ($sn) {
            $sn = "Your Account No is: ". $sn."<br/>";
        }
        $replace = [$email, $username, $url];
        $content = str_replace($find, $replace, $content);
        $res = send_email_g($email, 'Email Verification', $content);
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
            $result = send_email_g($user['email'], 'Forgot password', $content);
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
        $obj = new Users();
        $data['accept_email'] = input('accept_email', 0);
        $obj->allowField('country_id, province, city, paypal_account, mobile, username, accept_email')->where('id', $user['id'])->update($data);
        return ['code' => 200, 'msg' => 'Profile updates successfully!' ];
    }

    //修改个人信息
    public static function edit_profile($data, $user)
    {
        $validate = new \app\index\validate\Users();
        if ($user['user_type'] == 1) {
            $result = $validate->check($data, [], 'edit_profile');
            if ($result !== true) {
                return ['code' => 201, 'msg' => $validate->getError()];
            }
            $map = ['user_id' => $user['id']];
        } else {
            $desc_type = input('desc_type');
            if ($desc_type == 1) {
                if (!input('mp3file')) {
                    return ['code' => 201, 'msg' => 'Please upload mp3'];
                }
                $data['filesize'] = round(input('filesize'), 2);
                $data['duration'] = input('duration', 0, 'intval');
            }
            $map = ['teacher_id' => $user['id']];
        }
        $obj = new Users();
        $data['email_reminder'] = input('email_reminder', 0);
        if ($obj->allowField('native_language,location,goals,learn_years,english_level,descr, email_reminder,desc_type,mp3file,duration,filesize')->where('id', $user['id'])->update($data)) {
            //如果修改了地区，更新地区session
            $time = time();
            if ($user['location'] != $data['location']) {
                $location_old = Location::find($user['location']);
                $location = Location::find($data['location']);
                session('location', $data['location']);
                session('member_timezone', $location['timezone']);
                //新旧时区的时间相差
                $timezone = $location['timezone'] - $location_old['timezone'];
                //修改未上课的时间
                if ($user['user_type'] == 2) {
                    //如果是老师，修改之后的预约日期为新时间和预约课程的时间
                    $list = Orderitems::where($map)->where([ ['datetime', 'gt', $time], ['order_status', 'lt', 2]])->select();
                    foreach ($list as $vo) {
                        $new_date = time_to_date($vo['datetime'], $location, 'Y-m-d H:i');
                        $times = explode(' ', $new_date);
                        $update = ['book_date_teacher' => $times[0], 'time_begin_teacher' => $times[1] ];
                        $update['time_end_teacher'] = date('H:i', strtotime('+30 minute', strtotime($new_date) ) );
                        Orderitems::where('id', $vo['id'])->update($update);
                    }
                    $list = Daytime::where('user_id', $user['id'])->where('datetime', 'gt', $time)->select();
                    foreach ($list as $vo) {
                        $new_date = time_to_date($vo['datetime'], $location, 'Y-m-d H:i');
                        $times = explode(' ', $new_date);
                        $update = ['date' => $times[0], 'time' => $times[1] ];
                        $update['time_end'] = date('H:i', strtotime('+30 minute', strtotime($new_date) ));
                        Daytime::where('id', $vo['id'])->update($update);
                    }
                } else {
                    $list = Orderitems::where($map)->where([ ['datetime', 'gt', $time], ['order_status', 'lt', 2]])->select();
                    foreach ($list as $vo) {
                        $new_date = time_to_date($vo['datetime'], $location, 'Y-m-d H:i');
                        $times = explode(' ', $new_date);
                        $update = ['book_date' => $times[0], 'time_begin' => $times[1] ];
                        $update['time_end'] = date('H:i', strtotime('+30 minute', strtotime($new_date) ) );
                        Orderitems::where('id', $vo['id'])->update($update);
                    }
                }
            }
        }
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
        $map = ['email' => $data['email']];
        if ($partner) {
            $map['is_partner'] = 1;
        } else {
            $map['is_partner'] = 0;
        }
        $user = $this->where($map)->find();
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
        if (!$app) {
            if ($partner) {
                //机构登录
                if (!$user['is_partner']) {
                    return ['code' => 201, 'msg' => "Please log in to the Partner's account"];
                }
            } else {
                if ($user['user_type'] != $user_type) {
                    $sf = $user_type == 1 ? 'Learner' : 'Tutor';
                    return ['code' => 201, 'msg' => "Please log in to the {$sf}'s account"];
                }
            }
        }
        if ($app) {
            if ($data['password'] != md5($user['password']) && !Hash::check((string)$data['password'], $user['password'])) {
                return ['code' => 201, 'msg' => "Password error"];
            }
        } else {
            if (!Hash::check((string)$data['password'], $user['password'])) {
                return ['code' => 201, 'msg' => "Password error"];
            }
        }
        $location = Location::find($user['location']);
        $ret = ['code' => 200, 'msg' => "Sign in successful"];
        if ($app) {
            if ($user['token']) {
                cache('app_user_'.$user['token'], null);
            }
            unset($user['password']);
            $md5 = md5($user['email'].time());
            $user['token'] = $md5;
            $user['location'] = $location;
            $ret['data'] = $user;
            $this->where('id', $user['id'])->setField('token', $md5);
            cache('app_user_'.$md5, $user);
        }
        $update = ['login_ip' => get_client_ip(), 'login_time' => time(), 'last_login_time' => $user['login_time'], 'last_login_ip' => $user['login_ip'] ];
        $this->where('id', $user['id'])->update($update);
        session('user_id', $user['id']);
        session('username', $user['username']);
        session('user_type', $user['user_type']);
        session('location', $user['location']);
        session('is_partner', $user['is_partner']);
        cookie('user_type', $user['user_type']);
        cookie('is_partner', $user['is_partner']);
        session('member_timezone', $location['timezone']);

        return $ret;
    }

    //签发 App 端登录 token（供 App 内嵌网页社交登录成功后回传使用）
    //逻辑与 signin($app=1) 的 token 签发部分一致
    public function issueAppToken($userId)
    {
        $user = $this->where('id', $userId)->find();
        if (!$user) {
            return null;
        }
        if ($user['token']) {
            cache('app_user_'.$user['token'], null);
        }
        $md5 = md5($user['email'].time());
        $this->where('id', $user['id'])->setField('token', $md5);
        $location = Location::find($user['location']);
        $data = $user->toArray();
        unset($data['password']);
        $data['token'] = $md5;
        $data['location'] = $location ? $location->toArray() : null;
        cache('app_user_'.$md5, $data);
        $update = ['login_ip' => get_client_ip(), 'login_time' => time(), 'last_login_time' => $user['login_time'], 'last_login_ip' => $user['login_ip']];
        $this->where('id', $user['id'])->update($update);
        return $data;
    }

    //验证邮箱
    public static function check_email($uid, $code)
    {
        $pwd = md5($code);
        $user = self::where('id', $uid)->find();
        if (!$user) {
            return ['code' => 201, 'msg' => "User does not exist" ];
        }
        if($user['email_verify'] == 1) {
            return ['code' => 200, 'msg' => "Email verifed successfully", 'user_type' => $user['user_type'], 'is_partner' => $user['is_partner']];
        }
        if ($user['email_verify_code'] != $pwd) {
            return ['code' => 201, 'msg' => "Email verification failed", 'user_type'=>$user['user_type'], 'is_partner'=>$user['is_partner'] ];
        }
        $update = ['email_verify_code' => '', 'email_verify' => 1];
        if (self::where('id', $uid)->update($update)) {
            //发站内消息
            $msg_template = NoticeTemplate::find(10);
            $time = time();
            $msg = $msg_template['content'];
            $notice = ['user_id' => $user['id'], 'subject' => $msg_template['subject'], 'msg' => $msg, 'create_time' => $time];
            Db::name('cms_notice')->insert($notice);

            if ($user['is_partner'] == 1) {
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
                send_email_g(config('cfg_email'), 'New Partner from '.$country['country_name'], $content);

                $content = file_get_contents("template/partner_verifyed_" . $user['user_type'] . ".html");
            } else {
                if ($user['parent_id'] > 0 && $user['user_type'] == 2) {
                    //如果是老师通过了验证，奖励推荐人
                    $fee = config('cfg_elite_fee');
                    self::where('id', $user['parent_id'])->setInc('moneys', $fee);
                    $acc_data = ['user_id' => $user['parent_id'], 'type_id' => 6, 'obj_id' => $uid, 'money' => $fee, 'create_time' => $time];
                    //生成账户流水
                    Db::name('cms_account_log')->insertGetId($acc_data);

                    //站内信
                    $msg_template = NoticeTemplate::find(11);
                    $notice = ['user_id' => $user['parent_id'], 'subject' => $msg_template['subject'], 'msg' => $msg_template['content'], 'create_time' => $time];
                    Db::name('cms_notice')->insert($notice);
                }
                if ($user['parent_id'] > 0 && $user['user_type'] == 1) {
                    //如果是learner通过了验证，奖励推荐人
                    $fee = config('price_class_fee');
                    self::where('id', $user['parent_id'])->setInc('moneys', $fee);
                    $acc_data = ['user_id' => $user['parent_id'], 'type_id' => 8, 'obj_id' => $uid, 'money' => $fee, 'create_time' => $time];
                    //生成账户流水
                    Db::name('cms_account_log')->insertGetId($acc_data);

                    //站内信
                    $msg_template = NoticeTemplate::find(14);
                    $notice = ['user_id' => $user['parent_id'], 'subject' => $msg_template['subject'], 'msg' => $msg_template['content'], 'create_time' => $time];
                    Db::name('cms_notice')->insert($notice);
					$parent = Users::where('id', $user['parent_id'])->find();
					send_email_g($parent['email'], 'Reward_Invitation code was used', 'Your invitation code: '.$parent['invitation_code']. ' was used by '.$user['username'].'.'.'<br/>$ '.config('price_class_fee').' has been added to your account. <br/>Send this code to your friends who want to improve speaking English. Invite more, you will get more.<br/><br/>WeSpeakEnglish Team' );
                }
                if ($user['user_type'] == 2) {
                    $country = Country::find($user['country_id']);
                    send_email_g(config('cfg_email'), 'New Tutor registered from '.$country['country_name'], 'Name:'.$user['username'].'<br/>Email: '.$user['email'].'<br/>Self-intro: '.$user['descr']);
                }
               if ($user['user_type'] == 1) {
                    $country = Country::find($user['country_id']);
                    send_email_g(config('cfg_email'), 'New learner registered from '.$country['country_name'], '  Name:'.$user['username'].'<br/>Email: '.$user['email'].' <br/>Self-intro: '.$user['descr'].'<br/>Goal:'.$user['goals']);
					$money=$user['moneys'];
				}
                $content = file_get_contents("template/verifyed_" . $user['user_type'] . ".html");
            }
            $money=$user['moneys'];
            $find = ['{email}','{moneys}','{times}'];
            $replace = [$user['username'],$money, intval($money/1.5)];	
            $content = str_replace($find, $replace, $content);
            send_email_g($user['email'], 'Welcome to join WeSpeakEnglish_MUST READ', $content);
            if ($user['partner_id'] && !$user['is_partner']) {
                $partner = self::where('id', $user['partner_id'])->find();
                if ($partner) {
                    if ($user['user_type'] == 2) {
                        $str = 'A new tutor ';
                        $title = 'New tutor registered';
                    } else {
                        $str = 'A new learner ';
                        $title = 'New learner registered';
                    }
                    $str .= $user['username'] . ' just registered under your account. ';
                    send_email_g($partner['email'], $title, $str);
                }
            }

            return ['code' => 200, 'msg' => "Email verifed successfully", 'user_type'=>$user['user_type'], 'is_partner'=>$user['is_partner']];
        } else {
            return ['code' => 201, 'msg' => "Email verification failed", 'user_type'=>$user['user_type'], 'is_partner'=>$user['is_partner'] ];
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

    //上传mp3
    public static function uploadmp3($file)
    {
        if ($file->getMime() != 'audio/mpeg' && $file->getMime() != 'audio/x-m4a') {
            return ['code' => 201, 'msg' => "Mp3 format error." ];
        }
        if ($file->getMime() == 'audio/mpeg') {
            $ext = 'mp3';
        } else {
            $ext = 'm4a';
        }
//        $dir = '/voices/'.date('Ym').'/';
        $dir = 'voices';

        $file = time().rand(1000,9999).'.'. $ext;
        $info = $file->move(config('upload_path') . DIRECTORY_SEPARATOR . $dir);
        var_dump($info->getPathInfo()->getfileName(), $info->getFilename());
        if ($ext == 'm4a') {
            //要转换
            $cmd = "ffmpeg -i xxx.m4a -f aaa.mp3";
//            exec($cmd);
        }
    }

}