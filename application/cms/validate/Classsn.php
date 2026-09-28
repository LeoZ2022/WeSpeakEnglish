<?php

namespace app\cms\validate;

use think\Validate;

/**
 * @package app\cms\validate*/
class Classsn extends Validate
{
    // 定义验证规则
    protected $rule = [
        'sn_min|最小号码'   => 'require|integer',
        'sn_max|最大号码'   => 'require|integer',
    ];
}
