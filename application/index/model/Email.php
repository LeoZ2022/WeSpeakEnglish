<?php

namespace app\index\model;

use think\Model;

class Email extends Model
{

    // 设置当前模型对应的完整数据表名称
    protected $name = 'cms_email';

    public static function addsend()
    {
        $date = date('Y-m-d');
        $map = ['date' => $date];
        if (!self::where($map)->setInc('cnt')) {
            self::insertGetId($map);
        }
    }

}
