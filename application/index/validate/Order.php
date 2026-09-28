<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/9/7
 * Time: 9:38
 */

namespace app\index\validate;

use think\Validate;

class Order extends Validate
{

    // 定义验证规则
    protected $rule = [
        'dates'   => 'require',
        'times'   => 'require',
        'dayids'   => 'require',

        'teacher_id'   => 'require',
        'user_id'   => 'require',
    ];

    protected $message = [
        'dates.require' => "Please select meeting time",
        'times.require' => "Please select meeting time",
        'dayids.require' => "Please select meeting time",
        'teacher_id.require' => "Please select Tutor",
        'user_id.require' => "Please login as a learner",
    ];

    // 定义验证场景
    protected $scene = [
    ];

}