<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/9/28
 * Time: 21:51
 */

namespace app\index\model;

use think\Model;

class Country extends Model
{

    // 设置当前模型对应的完整数据表名称
    protected $name = 'cms_country';

    public static function get_list_col()
    {
        $list = self::cache(true)->column('id, ico_file, country_name');
        return $list;
    }

}