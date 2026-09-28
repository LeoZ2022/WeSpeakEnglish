<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/9/7
 * Time: 9:38
 */

namespace app\index\validate;

use think\Validate;

class Feedback extends Validate
{

    // 定义验证规则
    protected $rule = [
        'name'   => 'require',
        'email'   => 'require|email',
        'body'   => 'require',
        'verify_code'   => 'require',
    ];

    protected $message = [
        'name.require'  => "Name cannot be empty",
        'email.require'  => "Email cannot be empty",
        'email.email'  => "Email format error",
        'body.require'  => "Message cannot be empty",
        'verify_code.require'  => "Verification code cannot be empty",
    ];

}