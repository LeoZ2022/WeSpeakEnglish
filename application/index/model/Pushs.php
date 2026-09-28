<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2021/5/11
 * Time: 22:29
 */

namespace app\index\model;

use app\index\service\Push;
use think\Model;

class Pushs extends Model
{

    // 设置当前模型对应的完整数据表名称
    protected $name = 'cms_pushs';

    //定时推送
    public static function autopush()
    {
        $time0 = date('Y-m-d H:i');
        $list = self::where('push_time', $time0)->select();
        foreach ($list as $row) {
            self::push($row);
        }
    }

    public static function push($row)
    {
        $title = $row['title'];
        $message = $row['message'];
        $obj = new Push();
        $paras = [ 'extras' => ['act' => $obj->admin_push, 'class_id' => 0], 'title' => $title ];

        Push::send($message, ['title'=>$title],1,[], $paras);
    }

}