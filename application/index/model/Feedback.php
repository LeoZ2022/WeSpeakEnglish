<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/9/7
 * Time: 9:35
 */

namespace app\index\model;

use app\index\service\ImageCode;
use think\Model;
use think\Validate;

/**
 * 插件公共模型
 * @package app\admin\model
 */
class Feedback extends Model
{

    // 设置当前模型对应的完整数据表名称
    protected $name = 'cms_feedback';

    // 自动写入时间戳
    protected $autoWriteTimestamp = true;

    /**
     * 发布留言
     * @param $data
     * @return array
     */
    public static function add_msg($data)
    {
        $validate = new \app\index\validate\Feedback();
        $result = $validate->check($data, [], 'signin');
        if ($result !== true) {
            return ['code' => 201, 'msg' => $validate->getError()];
        }
        $obj = new ImageCode();
        if (!$obj->check_code($data['verify_code'])) {
            return ['code' => 201, 'msg' => 'Verification code error' ];
        }
        $data['create_ip'] = get_client_ip();
        if (self::create($data)) {
            //$email = 'support@wespeakenglish.net';
//            $email = '1035609228@qq.com';
            $email = '2522842983@qq.com';
            $content = "Name：".$data['name']."<br/>";
            $content .= "Email：".$data['email']."<br/>";
            $content .= "Message：".$data['body']."<br/>";
            $content .= "IP：".$data['create_ip']."<br/>";
            send_email2($email, '有新的留言', $content);
            $ret = ['code' => 200, 'msg' => 'Send successfully'];
        } else {
            $ret = ['code' => 201, 'msg' => 'Send error'];
        }
        return $ret;
    }

}