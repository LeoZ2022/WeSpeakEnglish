<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2021/5/11
 * Time: 15:57
 */

namespace app\api\model;

use app\index\service\Push;
use app\index\service\Websocket;
use think\Model;

class Roomlogs extends Model
{

    // 设置当前模型对应的完整数据表名称
    protected $name = 'cms_roomlogs';

    public static function checkintime($class)
    {
        $time = time();
        $toids = [$class['teacher_id'], $class['user_id'] ];
        if (!$class['class_time_begin']) {
            //如果没有退出的记录，2人都在线，推送课程开始了
            if (Orderitems::where('id', $class['id'])->where('class_time_begin', 0)->setField('class_time_begin', $time)) {
                $class['class_time_begin'] = $time;
                Websocket::class_begin($class, $time, $toids);
            }
        } else {
            Websocket::class_continue($class, $time, $toids);
        }
    }

}