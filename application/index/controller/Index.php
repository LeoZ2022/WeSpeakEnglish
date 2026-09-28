<?php
// +----------------------------------------------------------------------
// +----------------------------------------------------------------------

namespace app\index\controller;

use app\admin\model\Attachment;
use app\cms\model\Advert;
use app\index\model\Feedback;
use app\index\model\Teacher;
use app\index\model\Topic;
use app\index\model\Users;
use app\index\service\ImageCode;
use ipip\db\City;
use think\Db;

/**
 * 前台首页控制器
 * @package app\index\controller
 */
class Index extends Home
{

    //首页
    public function index()
    {
        if (!isset($_SERVER['HTTP_REFERER']) && !cookie('is_jump')) {
            $city = new City('./ipipfree.ipdb');
            $ip = get_client_ip();
            $areas = $city->find($ip, 'CN');
            //if ($areas[0] == '中国' || $areas[0] == '本机地址') {
             //   $url = url('index/learner', ['lang'=>'zh']);
            cookie('is_jump', 1, 86400*365);
            /*
             if ($areas[0] == '中国' || $areas[0] == '本机地址') {
                $url = url('index/index');
            } elseif (in_array($areas[0], ['英国','美国','加拿大','澳大利亚', '新西兰'])) {
                $url = url('index/index');
                 $this->redirect($url);
            } else {
                $url = url('index/index');
            }
            */
//            var_dump($city->find('216.154.255.255', 'CN'));
        }
        $ads_top = Advert::where(['typeid' => 3, 'status' => 1])->order('id desc')->find();

        $ads_tutor = Advert::where(['typeid' => 4, 'status' => 1])->order('id desc')->find();
        $ads_learner = Advert::where(['typeid' => 5, 'status' => 1])->order('id desc')->find();

        $this->assign('ads_top', $ads_top);
        $this->assign('ads_tutor', $ads_tutor);
        $this->assign('ads_learner', $ads_learner);
        $this->assign('show_lang', 0);
        return $this->fetch();
    }

    //老师介绍页
    public function tutor()
    {
        $ads_top = Advert::where(['typeid' => 2, 'status' => 1])->order('id desc')->find();
        $this->assign('ads_top', $ads_top);

        $topics = Topic::where('status', 1)->order('sort')->select();
        $this->assign('topics', $topics);
        $this->assign('show_lang', 0);
        return $this->fetch();
    }

    //学生介绍页
    public function learner()
    {
        $teachers = Teacher::where('status', 1)->order('sort')->select();
        $this->assign('teachers', $teachers);
        if (session('country') == 'china') {
            $this->assign('show_lang', 1);
        }
        $lang = input('lang');
        if ($lang) {
            $template = 'learner_cn';
            $this->assign('show_lang', 2);
            $ads_top = Advert::where(['typeid' => 8, 'status' => 1])->order('id desc')->find();
        } else {
            $template = '';
            $ads_top = Advert::where(['typeid' => 1, 'status' => 1])->order('id desc')->find();
        }
        $this->assign('ads_top', $ads_top);
        return $this->fetch($template);
    }

    //找回密码
    public function forgot()
    {
        $type = input('type', 1);
        if ($type == 3) {
            $this->assign('is_partner', 1);
        }
        $this->assign('user_type', $type);
        if ($this->request->isAjax()) {
            $data = ['email' => input('email'), 'verify_code' => input('verify_code') ];
            $ret = Users::forgot($data);
            echo json_encode($ret);exit;
        }
        return $this->fetch();
    }

    //验证码
    public function verify($width = 95, $height = 35)
    {
        $ImageCode = new ImageCode();
        $ImageCode->Show($width, $height);
    }

    //提交留言
    public function feedback()
    {
        $data = input('post.');
        $data['user_id'] = $this->user_id;
        //$ret = Feedback::add_msg($data);
        echo json_encode($ret);
    }

    //邮箱验证
    public function email_verify()
    {
        $uid = input('uid');
        $code = input('code');
        $ret = Users::check_email($uid, $code);
        $this->assign('user_type', $ret['user_type'] ?? 1);
        if ($ret['code'] == 200) {
            if ($ret['is_partner'] == 1) {
                $url = url('sign/partner');
            } elseif ($ret['user_type'] == 1) {
                $url = url('sign/learner');
            } else {
                $url = url('sign/tutor');
            }
            $this->assign('code', 1);
            $this->success($ret['msg'], $url);
        } else {
            $this->assign('msg', $ret['msg']);
            $this->assign('url', url('/'));
            $this->assign('wait', 5);
            $this->assign('code', 0);
            return $this->fetch();
//            $this->error($ret['msg'], url('/'));
        }
    }

    //app 下载页面
    public function qrcode()
    {
        $cfg = [
            'version' => config('app_android_version'),
            'descr' => config('app_android_descr'),
        ];
        $app = config('app_android_apk');
        if ($app) {
            $apk = Attachment::find($app);
            $apk = '/public/'.$apk['path'];
        } else {
            $apk = '';
        }
        $cfg['apk'] = $apk;
        $this->assign('app_cfg', $cfg);

        $city = new City('./ipipfree.ipdb');
        $ip = get_client_ip();
//            $ip = '67.220.90.13';
        $areas = $city->find($ip, 'CN');

        if ($areas[0] == '中国' || $areas[0] == '本机地址') {
            $country = 'china';
        } else {
            $country = 'out';
        }
        $this->assign('country', $country);

        return $this->fetch();
    }

    public function download()
    {
//      $apk = Attachment::find($app);
//      $apk = '/public/'.$apk['path'];
        $apk = 'https://wespeakenglish.net/EnChat.apk';
//		$apk = 'https://45.82.73.101/EnChat.apk';
        $data = ['addtime' => time()];
        Db::name('cms_download')->insert($data);
        $this->redirect($apk);
    }

    public function unsubscribe()
    {
        $body = config('app_zhuxiao');
        $this->assign('xy', $body);

        return $this->fetch();
    }

    //取消可用时间没有的提醒
    public function cancel_date()
    {
        $uid = input('user');
        $userkey = input('userkey');
        $user = Users::find($uid);
        $mdkey = md5($user['email']. config('custom.md5key'));
        if ($userkey == $mdkey) {
            Users::where('id', $user['id'])->update(['enddate_notice' => 0]);
        }
        $this->success('Cancellation success', '/');
    }

    //上传MP3
    public function uploadmp3()
    {
        $file =  $this->request->file('mp3');
        // , 'video/mp4'
        if (!in_array($file->getMime(), ['audio/mpeg', 'audio/x-m4a' , 'video/mp4'])) {
            echo json_encode(['code' => 201, 'msg' => "Mp3 format error.", 'error' => $file->getMime() ]);
            exit;
        }
        if ($file->getMime() == 'audio/mpeg') {
            $ext = 'mp3';
        } else {
            $ext = 'm4a';
        }
        $dir = 'voices';
        $info = $file->move(config('upload_path') . DIRECTORY_SEPARATOR . $dir);
//        $ret = Users::uploadmp3($file);
        $filiename = '/public/uploads/' . $dir.'/'.$info->getPathInfo()->getfileName().'/'.$info->getFilename();
        if ($ext == 'm4a') {
            $mp3file = str_replace('m4a', 'mp3', $filiename);
            $cmd = 'ffmpeg -i /www/wwwroot/school.wespeakenglish.chat'.$filiename.' -f mp3 /www/wwwroot/school.wespeakenglish.chat'.$mp3file;
//            $cmd = 'ffmpeg -i /www/wwwroot/test.wespeakenglish.chat'.$filiename.' -f mp3 /www/wwwroot/test.wespeakenglish.chat'.$mp3file;
            $result = exec($cmd);
            $filiename = $mp3file;
        }
        echo json_encode(['code' => 1, 'msg' => 'success', 'file' => $filiename]);
    }

}
