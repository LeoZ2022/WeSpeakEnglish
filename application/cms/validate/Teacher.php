<?php
// +----------------------------------------------------------------------
// | 海豚PHP框架 [ DolphinPHP ]
// +----------------------------------------------------------------------
// | 版权所有 2016~2017 河源市卓锐科技有限公司 [ http://www.zrthink.com ]
// +----------------------------------------------------------------------
// | 官方网站: http://dolphinphp.com
// +----------------------------------------------------------------------
// | 开源协议 ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------

namespace app\cms\validate;

use think\Validate;

/**
 * 验证器
 * @package app\cms\validate*/
class Teacher extends Validate
{
    // 定义验证规则
    protected $rule = [
        'name|名称' => 'require',
        'headpic|头像'    => 'require',
        'country_ico|国家'    => 'require',
        'star|评分'    => 'require',
        'price|课时费'    => 'require',
    ];

    // 定义验证提示
    protected $message = [
    ];

    // 定义验证场景
    protected $scene = [
        'name' => ['name']
    ];
}
