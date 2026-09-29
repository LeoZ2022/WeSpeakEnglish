<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2021/5/10
 * Time: 20:41
 */

namespace app\api\home;

use app\admin\model\Attachment;
use app\api\model\Orderitems;
use app\index\model\Users;
use app\index\service\ImageCode;

class Index extends Base
{

    public function login()
    {
        $data = input('post.');
        $obj = new Users();
        $user_type = input('user_type');
        $ret = $obj->signin($data, $user_type, 1);
        $this->returnJson($ret);
    }

    public function findpass()
    {
        $sessionid = input('sessionid');
        $data = ['email' => input('email'), 'verify_code' => input('verify_code'), 'sessionid' => $sessionid ];
        $ret = Users::forgot($data);
        $this->returnJson($ret);
    }

    public function get_code($sessionid =0, $width = 95, $height = 35)
    {
        $sessionid = $sessionid ?? time().rand(10000, 99999);
        $act = new \app\index\controller\Index();
        $ImageCode = new ImageCode();
        $ImageCode->Show($width, $height, $sessionid);
    }

    public function get_config()
    {
        $cfg = config('tencentyun.');
        $urls = [];
        $urls[] = ['txt' => config('app.app_link_txt1'), 'url' => config('app.app_link_url1')];
        $urls[] = ['txt' => config('app.app_link_txt2'), 'url' => config('app.app_link_url2')];
        $cfg['index_urls'] = $urls;
        $this->returnJson(['code'=>200, 'data'=>$cfg]);
    }

    /**
     * 社交登录配置（供 App 内嵌网页使用）
     * 返回已启用的登录渠道及其授权入口，以及带 inapp=1 的注册页/首页地址
     */
    public function social_config()
    {
        $cfg = config('social_auth.');
        $names = ['google' => 'Google', 'apple' => 'Apple', 'microsoft' => 'Outlook', 'facebook' => 'Facebook'];
        $providers = [];
        foreach (['google', 'apple', 'microsoft', 'facebook'] as $p) {
            if (!empty($cfg[$p]['enabled']) && !empty($cfg[$p]['client_id'])) {
                $providers[] = [
                    'provider' => $p,
                    'name' => $names[$p],
                    'start_url' => url('index/social_auth/start', ['provider' => $p, 'inapp' => 1], '', true),
                ];
            }
        }
        $data = [
            'providers' => $providers,
            'register_url' => url('index/register/learner', ['inapp' => 1], '', true),
            'home_url' => url('index/index/index', [], '', true),
        ];
        $this->returnJson(['code'=>200, 'data'=>$data]);
    }

    /**
     * APP最新安装包
     */
    public function get_upgrade()
    {
        $cfg = [];
        $app = config('app_android_apk');
        if ($app) {
            $apk = Attachment::find($app);
            $apk = $apk['path'];
        } else {
            $apk = '';
        }
        $apk = 'EnChat.apk';
        $cfg['android'] = [
            'version' => config('app_android_version'),
            'descr' => config('app_android_descr'),
            'apk' => $apk,
        ];
        $cfg['ios'] = [
            'version' => config('app_ios_version'),
            'descr' => config('app_ios_descr'),
            'appleId' => config('app_ios_appid'),
        ];
        $this->returnJson(['code'=>1, 'data'=>$cfg]);
    }

    //app协议和隐私政策
    public function get_article()
    {
        $act = input('act', 1);
        $body = $act == 1 ? config('app_xieli') : config('app_yingsi');
        $body_en = $act == 1 ? config('app_xieli_en') : config('app_yingsi_en');
        $this->returnJson(['code'=>200, 'data'=>['cn'=>$body, 'en'=>$body_en]]);
    }

    //投诉
    public function tousu()
    {
        $title = input('title');
        $body = input('body');
        $roomid = input('roomId');
        $email = config('cfg_email');
        //$email = 'support@wespeakenglish.net';

        $room = Orderitems::where('id', $roomid)->find();
        $content = '举报类别：'.$title."<br/>举报内容：".$body;
//        $content .= "<br/>".json_encode(input('post.'));
        if ($room) {
            $user = $this->check_token();
            $this->user = $user;

            $user = \app\api\model\Users::where('id', $this->user['id'])->find();
            $content .= "<br/>投诉人：".$user['username'];
            $content .= "<br/>投诉人邮箱：".$user['email'];

            if ($room['user_id'] == $this->user['id']) {
                $uid = $room['teacher_id'];
            } else {
                $uid = $room['user_id'];
            }
            $user = \app\api\model\Users::where('id', $uid)->find();
            $content .= "<br/>被投诉人：".$user['username']."<br/>";
            $content .= "<br/>被投诉人邮箱：".$user['email']."<br/>";
        }
        send_email3($email, '有新的举报', $content);
        $this->returnJson(['code'=>200, 'data'=>true, 'msg' => '提交成功']);
    }

    //获取注销协议
    public function get_cancel_article()
    {
        $body = config('app_zhuxiao');
        $this->returnJson(['code'=>200, 'data'=>$body]);
    }

}