<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/9/7
 * Time: 9:38
 */

namespace app\index\validate;

use think\Validate;

class Topup extends Validate
{

    // 定义验证规则
    protected $rule = [
        'order_amount'   => 'require',
//        'pay_type'   => 'require',
    ];

    protected $message = [
        'order_amount.require' => "Amount can't be empty",
        'pay_type.require'   => "Payment method can't be empty"
    ];

}