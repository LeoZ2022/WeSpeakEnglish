<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

Route::get('think', function () {
    return 'hello!';
});

Route::get('hello/:name', 'index/hello');
Route::get('learner', 'index/learner');
Route::get('tutor', 'index/tutor');
Route::get('partner', 'sign/partner');
Route::get('app', 'index/qrcode');
Route::get('unsubscribe', 'index/unsubscribe');

return [

];
