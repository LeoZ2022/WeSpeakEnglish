<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2021/5/11
 * Time: 8:56
 */

namespace app\api\model;

use app\index\model\Location;
use think\Model;

class Users extends Model
{

    // 设置当前模型对应的完整数据表名称
    protected $name = 'cms_users';

    public static function getUser($token)
    {
        $user = cache('app_user_'.$token);
        if (!$user) {
            $user = self::where('token', $token)->find();
            if ($user) {
                $location = Location::find($user['location']);
                $user['location'] = $location;
                unset($user['password']);
                cache('app_user_' . $token, $user);
            }
        }
        return $user;
    }

}