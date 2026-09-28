<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2021/3/21
 * Time: 22:34
 */

namespace app\cms\model;

use think\Model as ThinkModel;

//虚拟号
class Classsn extends ThinkModel
{

    // 设置当前模型对应的完整数据表名称
    protected $name = 'cms_classsn';

    public static function get_sn()
    {
        $last = self::where('user_id', 'eq', 0)->order('class_sn')->find();
        if ($last) {
            return $last['class_sn'];
        } else {
            return false;
        }
    }

    public static function create_sn($data)
    {
        $min = $data['sn_min'];
        while($min <= $data['sn_max']) {
            $map = ['class_sn' => $min];
            $has = self::where($map)->find();
            if (!$has) {
                self::create($map);
            }
            $min++;
        }
        return 1;
    }

}