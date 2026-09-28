<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/9/7
 * Time: 9:38
 */

namespace app\index\validate;

use think\Validate;

class Users extends Validate
{

    // 定义验证规则
    protected $rule = [
        'old_pass'   => 'require',
        'new_pass'   => 'require|length:6,20',
        'new_pass2'   => 'require|confirm:new_pass',

        'native_language'   => 'require',
        'english_level'   => 'require',
        'learn_years'   => 'require',
        'location'   => 'require',

        'email' => 'require|email',
        'password' => 'require',
        'verify_code' => 'require',

        'country_id' => 'require',
        'province' => 'require',
        'city' => 'require',
        'paypal_account' => 'require',
        'mobile' => 'require',
        'username' => 'require',
    ];

    protected $message = [
        'old_pass.require' => "Old Password can't be empty",
        'new_pass.require' => "New Password can't be empty",
        'new_pass.length' => "New Password size between 6-20",
        'new_pass2.require' => "Re-enter Password can't be empty",
        'new_pass2.confirm' => "Re-enter Password not match",

        'native_language.require' => "First language can't be empty",
        'location.require' => "Reside in can't be empty",
        'english_level.require' => "English proficiency level can't be empty",
        'learn_years.require' => "Years of learning English can't be empty",
        'goals.require' => "Goals of speaking English can't be empty",

        'country_id.require' => "Country can't be empty",
        'province.require' => "Province/State can't be empty",
        'city.require' => "City can't be empty",
        'paypal_account.require' => "Paypal account can't be empty",
        'mobile.require' => "Mobile can't be empty",
        'username.require' => "Cell phone can't be empty",

        'email.require' => "Email can't be empty",
        'password.require' => "Password can't be empty",
        'verify_code.require' => "Verification code can't be empty",
        'email.email' => "Email error. Ensure no typo or trailing whitespace",
    ];

    // 定义验证场景
    protected $scene = [
        //修改密码
        'edit_pass'  =>  ['old_pass', 'new_pass2', 'new_pass'],
        //登录
        'signin'  =>  ['email', 'password', 'verify_code'],
        'signinapp'  =>  ['email', 'password'],
        //找回密码
        'forgot'  =>  ['email', 'verify_code'],
        //学生修改个人信息
        'edit_profile'  => ['native_language', 'english_level', 'learn_years', 'goals'],

        'edit_profile_tutor'  => ['country_id', 'province', 'city', 'paypal_account', 'username'],
        'edit_profile_learner'  => ['country_id', 'paypal_account', 'username'],
    ];

}