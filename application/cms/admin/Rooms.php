<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2021/7/15
 * Time: 16:56
 */

namespace app\cms\admin;

use app\admin\controller\Admin;
use app\api\model\Orderitems;
use app\common\builder\ZBuilder;
use app\index\service\Push;

class Rooms extends Admin
{

    public function index()
    {
        $map = $this->getMap();
        $addmap = "in_time_teacher > 0 and in_time_student > 0 and last_in_time_teacher > out_time_teacher and last_in_time_student > out_time_student";
        $list = Orderitems::alias('i')->field('i.classin_id, i.id, datetime, teacher_price, fee, i.create_time, u.username as username, t.username as teacher_username, class_time_begin')
            ->join('cms_users u', 'user_id = u.id')
            ->join('cms_users t', 'teacher_id = t.id')
            ->where($addmap)->where($map)->order('id desc')->paginate();
        $this->assign('data_list', $list);

        return $this->fetch();
    }

    //房间发消息
    public function msg()
    {
        // 保存数据
        if ($this->request->isPost()) {
            $title = input('title');
            $msg = input('msg');
            $msg = 'Admin:'.$msg;
            $ret = Push::admin_send($title, $msg);
            if ($ret['code'] == 1) {
                $this->success('发送成功');
            } else {
                $this->error($ret['msg']);
            }
        }
        return ZBuilder::make('form')
            ->addFormItems([
                ['select', 'type_id', '类型', '', [1=>'全部会员', '在线的'], 1],
                ['text', 'title', '消息标题'],
                ['text', 'msg', '消息内容'],
            ])
            ->fetch();
    }

    //会话统计
    public function total()
    {
        $map = $this->getMap();
        $total = Orderitems::where($map)->where('order_status', 2)->count();
        //免费退出，10分钟内
        $total_free = Orderitems::where($map)->where('refund_money=money')->count();
        //提前退出，10分钟后、30分钟内
        $total_tiqian = Orderitems::where($map)->where('class_time_len > 600 and class_time_len < 1800')->count();
        //断线未回来
        $total_noback = Orderitems::where($map)->where('out_reason_teacher|out_reason_student', '2')->count();
        $this->assign('total', $total);
        $this->assign('total_free', $total_free);
        $this->assign('total_tiqian', $total_tiqian);
        $this->assign('total_noback', $total_noback);

        return $this->fetch();
    }

    public function view()
    {
        $id = input('id');
        $info = Orderitems::find($id);
        $this->assign('info', $info);

        $video_url = config('tencentyun.video_url2');
        $this->assign('video_url', $video_url);

        $streamId = config('tencentyun.appid').'_'.$info['id'].'_'.$info['teacher_id'].'_main';
        $WebRTC = 'webrtc://'.$video_url.'/live/'.$streamId;
        $HLS = 'http://'.$video_url.'/live/'.$streamId.'.m3u8';
        $this->assign('streamId', $streamId);
        $this->assign('WebRTC', $WebRTC);
        $this->assign('HLS', $HLS);

        $streamId = config('tencentyun.appid').'_'.$info['id'].'_'.$info['user_id'].'_main';
        $WebRTC = 'webrtc://'.$video_url.'/live/'.$streamId;
        $HLS = 'http://'.$video_url.'/live/'.$streamId.'.m3u8';
        $this->assign('streamId2', $streamId);
        $this->assign('WebRTC2', $WebRTC);
        $this->assign('HLS2', $HLS);

        return $this->fetch();
    }

}