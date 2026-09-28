<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/9/7
 * Time: 9:38
 */

namespace app\index\validate;

use think\Validate;

class Withdrawal extends Validate
{

    // 定义验证规则
    protected $rule = [
        'account'   => 'require',
    ];

    protected $message = [
        'money.require' => "Amount can't be empty",
        'account.require'   => "Paypal account can't be empty"
    ];

}