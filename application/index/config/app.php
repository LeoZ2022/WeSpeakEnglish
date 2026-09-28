<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/11/15
 * Time: 23:46
 */

use think\facade\Env;

return [
    // 默认跳转页面对应的模板文件
    'dispatch_success_tmpl'  => Env::get('app_path') . 'index/view/dispatch_jump.tpl',
    'dispatch_error_tmpl'    => Env::get('app_path') . 'index/view/dispatch_jump.tpl',
];
