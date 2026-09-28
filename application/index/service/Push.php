<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2021/5/11
 * Time: 20:10
 */

namespace app\index\service;

use app\common\model\Getui;
use app\index\model\Users;
use JPush\Client;

class Push
{

    //还有多少分钟要开始
    public $class_will_begin = 1;
    //2人进来了，开始上课
    public $class_begin = 2;
    //后台推送
    public $admin_push = 3;

    //提前30分钟或5分钟
    public static function send_front($ids, $minute)
    {
//        $title = 'Your chat will begin in ' . $minute . ' mins';
        $message = 'A scheduled chat will start in ' . $minute . ' minutes';

        $title = 'Are you ready?';
        $obj = new Push();
        $paras = ['extras' => ['act' => $obj->class_will_begin], 'title' => $title];

        $ret = Push::send($message, ['title' => $title], 0, $ids, $paras);
        return $ret;
    }

    //2人都进来了，开始上课
    public static function class_begin($class, $time, $toids)
    {
        $title = 'Chat starts';
        $message = 'Chat starts';
        $obj = new Push();
        $paras = ['extras' => ['act' => $obj->class_begin, 'class_id' => $class['id'], 'time' => $time], 'title' => $title];

        $ret = Push::send($message, ['title' => $title], 0, $toids, $paras);
        return $ret;
    }

    //管理员发消息
    public static function admin_send($title, $message)
    {
        $type_id = input('type_id');
        $obj = new Push();
        $paras = ['extras' => ['act' => $obj->admin_push,], 'title' => $title];

        if ($type_id == 1) {
            $ret = Websocket::class_admin($message);
            $ret = Push::send($message, ['title' => $title], 1, [], $paras);
        } else {
            $uids = Users::alias('u')->join('cms_orderitems i', "u.id = user_id and order_status = 1 and (last_in_time_student > out_time_student OR in_time_student > out_time_student)")->where('push_id is not null')->group('u.id')->column('u.id');
            $uids2 = Users::alias('u')->join('cms_orderitems i', "u.id = teacher_id and order_status = 1 and (last_in_time_teacher > out_time_teacher OR in_time_teacher > out_time_teacher)")->where('push_id is not null')->group('u.id')->column('u.id');
            if ($uids && $uids2) {
                $uids = array_merge($uids, $uids2);
            } elseif ($uids2) {
                $uids = $uids2;
            }
            $ret = Websocket::class_admin($message, $uids);
            $ret = Push::send($message, ['title' => $title], 0, $uids, $paras);
        }
        return $ret;
    }

    /**
     * 发送推送
     * @param $data：推送内容，json
     * @param $toid：接收用户id，可多个
     */
    public static function send($msg_content, $data = [], $all = 1, $user_ids = [], $paras = [])
    {
        require_once('./getui/GTClient.php');

        $data['title'] = substr($data['title'], 0, 64);
        $msg_content = substr($msg_content, 0, 64); // 极光推送手机显示内容的不多。

        if (!$all) {
            $push_ids = Users::where('id', 'in', $user_ids)->where('push_id is not null')->group('push_id')->column('push_id');
            if (!$push_ids) {
                return ['code' => -1, 'msg' => '个体推送时没有指定用户！'];
            }
//            $push_ids = ['7ca8a58d1b4f685948e0a05e434d9602'];
        }
//        print_r($push_ids);exit;

        define('APPKEY', config('tencentyun.getui.app_key'));
        define('APPID', config('tencentyun.getui.app_id'));
        define('MS', config('tencentyun.getui.master_secret'));
        define("URL","https://restapi.getui.com");
        $token = null;
        $taskId = null;
        $api = new \GTClient(URL,APPKEY,APPID,MS);
        $push = new \GTPushRequest();
        $push->setRequestId(micro_time());
        //设置setting
        $set = new \GTSettings();
        $set->setTtl(3600000);
//    $set->setSpeed(1000);
//    $set->setScheduleTime(1591794372930);
        $strategy = new \GTStrategy();
        $strategy->setDefault(\GTStrategy::STRATEGY_THIRD_FIRST);
//    $strategy->setIos(GTStrategy::STRATEGY_GT_ONLY);
//    $strategy->setOp(GTStrategy::STRATEGY_THIRD_FIRST);
//    $strategy->setHw(GTStrategy::STRATEGY_THIRD_ONLY);
        $set->setStrategy($strategy);
        $push->setSettings($set);
        //设置PushMessage，
        $message = new \GTPushMessage();
        //通知
        $notify = new \GTNotification();
        $notify->setTitle($data['title']);
        $notify->setBody($msg_content);
        $notify->setBigText($msg_content);
        //与big_text二选一
//    $notify->setBigImage("BigImage");

//    $notify->setLogo("push.png");
//    $notify->setLogoUrl("LogoUrl");
        $notify->setChannelId("Default");
        $notify->setChannelName("Default");
        $notify->setChannelLevel(2);

        $notify->setClickType("none");
        $notify->setPayload("Payload");
        $notify->setNotifyId(22334455);
        $notify->setRingName("ring_name");
        $notify->setBadgeAddNum(1);
        $message->setNotification($notify);
        //透传 ，与通知、撤回三选一
//    $message->setTransmission("试试透传");

        $push->setPushMessage($message);

//    $message->setDuration("1590547347000-1590633747000");

        //厂商推送消息参数
        $pushChannel = new \GTPushChannel();
        //ios
        $ios = new \GTIos();
        $ios->setType("notify");
        $ios->setAutoBadge("+1");
        $ios->setPayload("ios_payload");
        $ios->setApnsCollapseId("apnsCollapseId");
        //aps设置
        $aps = new \GTAps();
        $aps->setContentAvailable(0);
//        $aps->setSound("com.gexin.ios.silenc");
        $aps->setSound("pushsound.caf");
        $aps->setCategory("category");
        $aps->setThreadId("threadId");

        $alert = new \GTAlert();
        $alert->setTitle($data['title']);
        $alert->setBody($msg_content);
        $aps->setAlert($alert);
        $ios->setAps($aps);
        $pushChannel->setIos($ios);

        $push->setPushChannel($pushChannel);

        $android = new \GTAndroid();
        $ups = new \GTUps();
//    $ups->setTransmission("ups Transmission");
        $thirdNotification = new \GTThirdNotification();
        $thirdNotification->setTitle($data['title']);
        $thirdNotification->setBody($msg_content);
        $thirdNotification->setClickType(\GTThirdNotification::CLICK_TYPE_STAERAPP);
//        $thirdNotification->setIntent("intent:#Intent;component=你的包名/你要打开的 activity 全路径;S.parm1=value1;S.parm2=value2;end");
//        $thirdNotification->setUrl("http://docs.getui.com/getui/server/rest_v2/push/");
//        $thirdNotification->setPayload("payload");
//        $thirdNotification->setNotifyId(456666);
        $ups->addOption("HW","badgeAddNum",1);
        $ups->addOption("OP","channel","Default");
        $ups->addOption("OP","aaa","bbb");
        $ups->addOption(null,"a","b");

        $ups->setNotification($thirdNotification);
        $android->setUps($ups);
        $pushChannel->setAndroid($android);

        $push->setGroupName("test");

        $fp = fopen('public/push/log_'.date('Ymd').'.txt', 'a+');

        if ($all) {
            $pids = 'all';
            $res = json_encode($api->pushApi()->pushAll($push));
            $ret = ['code' => 1, 'msg' => '发送成功'];
        } else {
            $pids = implode(',', $push_ids);
            if (count($push_ids) > 1) {
                $task = $api->pushApi()->createListMsg($push);
                if ($task['code'] == 0) {
                    $task_id = $task['data']['taskid'];

                    $user = new \GTAudienceRequest();
                    $user->setIsAsync(true);
                    $user->setTaskid($task_id);
                    $user->setCidList($push_ids);
                    $res = json_encode($api->pushApi()->pushListByCid($user));
                    $ret = ['code' => 1, 'msg' => '发送成功', 'res' => $res];
                } else {
                    fwrite($fp, date('Y-m-d H:i:s').' 发送失败：'.$data['title'].', '.$pids."\r\n". $task['msg']);
                    $ret = ['code' => 0, 'msg' => '发送失败'];
                    return $ret;
                }
            } else {
                $push->setCid($push_ids[0]);
                $res = json_encode($api->pushApi()->pushToSingleByCid($push));
                $ret = ['code' => 1, 'msg' => '发送成功', 'res' => $res];
            }
        }
        fwrite($fp, date('Y-m-d H:i:s').' '.$data['title'].', '.$pids."\r\n");
        return $ret;
        exit;

        define('APPKEY', config('tencentyun.getui.app_key'));
        define('APPID', config('tencentyun.getui.app_id'));
        define('MASTERSECRET', config('tencentyun.getui.master_secret'));
        define('HOST', "http://sdk.open.api.igexin.com/apiex.htm");

        if (!$all) {
            $push_ids = Users::where('id', 'in', $user_ids)->column('push_id');
            if ($push_ids && is_array($push_ids)) {
                foreach ($push_ids as $k => $p) {
                    if (empty($p)) {
                        unset($push_ids[$k]);
                    }
                }
                if (!$push_ids) {
                    return ['code' => -1, 'msg' => '个体推送时没有指定用户！'];
                }
            }
//            $push_ids = ['7ca8a58d1b4f685948e0a05e434d9602'];
        }
        $igt = new \IGeTui(HOST, APPKEY, MASTERSECRET);

        $data['title'] = substr($data['title'], 0, 64);
        $msg_content = substr($msg_content, 0, 64); // 极光推送手机显示内容的不多。

        $template =  new \IGtNotificationTemplate();
        $template->set_appId(APPID);//应用appid
        $template->set_appkey(APPKEY);//应用appkey
        $template->set_transmissionType(2);//透传消息类型
        $template->set_transmissionContent($data['title']);//透传内容
        $template->set_title($data['title']);//通知栏标题
        $template->set_text($msg_content);//通知栏内容
    $template->set_isRing(true);//是否响铃
    $template->set_isVibrate(true);//是否震动
        $template->set_isClearable(true);//通知栏是否可清除
        $template->set_pushInfo('ActionLockey', 7, $msg_content, '', null, null, null, null);

//        $template = new \IGtTransmissionTemplate();
//        $template->set_appId(APPID);
//        $template->set_appkey(APPKEY);
//        $template->set_transmissionType(2);
//        $template->set_transmissionContent($msg_content);
/*
        //  APN高级推送
        $apn = new \IGtAPNPayload();
        $alertmsg = new \DictionaryAlertMsg();
        $alertmsg->body = $msg_content;
        $alertmsg->actionLocKey = "ActionLockey";
        $alertmsg->locKey = "LocKey";
        $alertmsg->locArgs = array("locargs");
        $alertmsg->launchImage = "launchimage";
        //  IOS8.2 支持
        $alertmsg->title = $data['title'];
        $alertmsg->titleLocKey = "TitleLocKey";
        $alertmsg->titleLocArgs = array("TitleLocArg");

        $apn->alertMsg = $alertmsg;
        $apn->badge = 7;
        $apn->sound = "";
        $apn->add_customMsg("payload", "payload");
        //设置语音播报类型，int类型，0.不可用 1.播放body 2.播放自定义文本
        $apn->voicePlayType = 2;
        //设置语音播报内容，String类型，非必须参数，用户自定义播放内容，仅在voicePlayMessage=2时生效
        //注：当"定义类型"=2, "定义内容"为空时则忽略不播放
        $apn->voicePlayMessage = $msg_content;
        $apn->contentAvailable = 0;
        $apn->category = "ACTIONABLE";
        $template->set_apnInfo($apn);
*/
        try {
            if ($all) {
                $message = new \IGtAppMessage();
                $message->set_isOffline(true);
                $message->set_offlineExpireTime(10 * 60 * 1000);//离线时间单位为毫秒，例，两个小时离线为3600*1000*2
                $message->set_data($template);
                $appIdList = array(APPID);
                $message->set_appIdList($appIdList);
                $rep = $igt->pushMessageToApp($message);
            } else {
                $message = new \IGtSingleMessage();

                $message->set_isOffline(true);//是否离线
                $message->set_offlineExpireTime(3600 * 12 * 1000);//离线时间
                $message->set_data($template);//设置推送消息类型
//	$message->set_PushNetWorkType(0);//设置是否根据WIFI推送消息，1为wifi推送，0为不限制推送
                //接收方
                $target1 = new IGtTarget();
                $target1->set_appId(APPID);
                $target1->set_clientId($push_ids[0]);
                $targetList[] = $target1;
                if (count($push_ids) == 2) {
                    $target2 = new IGtTarget();
                    $target2->set_appId(APPID);
                    $target2->set_clientId($push_ids[1]);
                    $targetList[] = $target2;
                }

                $contentId = $igt->getContentId($message, "toList任务别名功能");    //根据TaskId设置组名，支持下划线，中文，英文，数字
                $rep = $igt->pushMessageToList($contentId, $targetList);
            }
            if ($rep['result'] == 'ok') {
                $ret = ['code' => 1, 'msg' => '发送成功'];
            } else {
                $ret = ['code' => 0, 'msg' => '发送失败：' . $rep['result']];
            }
        } catch (RequestException $e) {
            $requstId = $e->getRequestId();
            $ret = ['code' => 0, 'msg' => '发送失败'];
        }
        echo json_encode($ret);
        exit;

        $obj = new Getui();
        $transmission_content = [
            'test' => [
                'title' => 'asd',
                'time' => time()
            ]
        ];
        $transmission_content = json_encode($transmission_content);
        // $res=$get->sendToClient('fd98882bef6f1bade6bffc85574436db','title','text',$transmission_content);
        // $res=$get->sendToAllTransmission($transmission_content);
        $res = $obj->sendToAllNotification('title', 'text', $transmission_content);
        dump($res);
        exit;

        include 'extend/getui/GTClient.php';
        $api = new GTClient("https://restapi.getui.com", config('tencentyun.getui.app_key'), config('tencentyun.getui.app_id'), config('tencentyun.getui.master_secret'));
        $push_ids = Users::where('id', 'in', $user_ids)->column('push_id');
        if ($push_ids && is_array($push_ids)) {
            foreach ($push_ids as $k => $p) {
                if (empty($p)) {
                    unset($push_ids[$k]);
                }
            }
            if (!$push_ids) {
                return ['code' => -1, 'msg' => '个体推送时没有指定用户！'];
            }
        }
        $push = new \GTPushRequest();
        if (!$all) {
            if (count($push_ids) > 1) {
                $push->setCid($push_ids);
            } else {
                $push->setRequestId($push_ids[0]);
            }
        }
        $data['title'] = substr($data['title'], 0, 64);
        $msg_content = substr($msg_content, 0, 64); // 极光推送手机显示内容的不多。
        $message = new \GTPushMessage();
        $notify = new \GTNotification();
        $notify->setTitle($data['title']);
        $notify->setBody($msg_content);
        //点击通知后续动作，目前支持以下后续动作:
        //1、intent：打开应用内特定页面url：打开网页地址。2、payload：自定义消息内容启动应用。3、payload_custom：自定义消息内容不启动应用。4、startapp：打开应用首页。5、none：纯通知，无后续动作
        $notify->setClickType("none");
        $message->setNotification($notify);
        $push->setPushMessage($message);
        //处理返回结果
        $result = $api->pushApi()->pushToSingleByCid($push);
        return ['code' => 1, 'msg' => '已推送', 'result' => $result];

        if (!$jpush) {
            return ['code' => -1, 'msg' => '推送服务配置有误！'];
        } elseif (!$all && !$push_ids) {
            return ['code' => -1, 'msg' => '个体推送时没有指定用户！'];
        }
        if (!is_array($data) || empty($data['title'])) {
            $data['title'] = 'wespeakenglish';
        }
        $data['title'] = substr($data['title'], 0, 64);
        $msg_content = substr($msg_content, 0, 64); // 极光推送手机显示内容的不多。

        $push = $jpush->push()
            ->setPlatform('all')
            ->setNotificationAlert($msg_content)
            ->message($msg_content, $data); // 改为加个标题
        if ($paras) {
            $paras['title'] = $data['title'];
            $paras["sound"] = "push.mp3";
            $push->androidNotification($msg_content, $paras);
            unset($paras['title']);
//            $paras["sound"] = "sound.caf";
            $push->iosNotification($msg_content, $paras);
        }
        if ($all) {
            $push = $push->addAllAudience();
        } else {
            $push = $push->addRegistrationId($push_ids);
        }

        try {
            $response = $push->send();
            if ($response['http_code'] != 200) {
                return ['code' => -1, 'msg' => "http错误码:{$response['http_code']}", 'result' => $response];
            }
            return ['code' => 1, 'msg' => '已推送', 'result' => $response];
        } catch (\JPush\Exceptions\APIConnectionException $e) {
            return ['code' => -1, 'msg' => $e->getMessage()];
        } catch (\JPush\Exceptions\APIRequestException $e) {
            return ['code' => -1, 'msg' => $e->getMessage()];
        } catch (\Exception $e) {
            return ['code' => -1, 'msg' => $e->getMessage()];
        }
    }

    /**
     * 发送推送
     * @param $data：推送内容，json
     * @param $toid：接收用户id，可多个
     */
    public static function sendJg($msg_content, $data = [], $all = 1, $user_ids = [], $paras = [])
    {
        $jpush = new Client(config('tencentyun.push_appkey'), config('tencentyun.push_secret'));

        $push_ids = Users::where('id', 'in', $user_ids)->column('push_id');

        if ($push_ids && is_array($push_ids)) {
            foreach ($push_ids as $k => $p) {
                if (empty($p)) {
                    unset($push_ids[$k]);
                }
            }
            if (!$push_ids) {
                return ['code' => -1, 'msg' => '个体推送时没有指定用户！'];
            }
        }

        if (!$jpush) {
            return ['code' => -1, 'msg' => '推送服务配置有误！'];
        } elseif (!$all && !$push_ids) {
            return ['code' => -1, 'msg' => '个体推送时没有指定用户！'];
        }
        if (!is_array($data) || empty($data['title'])) {
            $data['title'] = 'wespeakenglish';
        }
        $data['title'] = substr($data['title'], 0, 64);
        $msg_content = substr($msg_content, 0, 64); // 极光推送手机显示内容的不多。

        $push = $jpush->push()
            ->setPlatform('all')
            ->setNotificationAlert($msg_content)
            ->message($msg_content, $data); // 改为加个标题
        if ($paras) {
            $paras['title'] = $data['title'];
            $paras["sound"] = "push.mp3";
            $push->androidNotification($msg_content, $paras);
            unset($paras['title']);
//            $paras["sound"] = "sound.caf";
            $push->iosNotification($msg_content, $paras);
        }
        if ($all) {
            $push = $push->addAllAudience();
        } else {
            $push = $push->addRegistrationId($push_ids);
        }

        try {
            $response = $push->send();
            if ($response['http_code'] != 200) {
                return ['code' => -1, 'msg' => "http错误码:{$response['http_code']}", 'result' => $response];
            }
            return ['code' => 1, 'msg' => '已推送', 'result' => $response];
        } catch (\JPush\Exceptions\APIConnectionException $e) {
            return ['code' => -1, 'msg' => $e->getMessage()];
        } catch (\JPush\Exceptions\APIRequestException $e) {
            return ['code' => -1, 'msg' => $e->getMessage()];
        } catch (\Exception $e) {
            return ['code' => -1, 'msg' => $e->getMessage()];
        }
    }

    //发送30分钟提醒邮件
    public static function send_emails($emails)
    {
        $content = file_get_contents("template/notice30.html");
        $title = 'The chat scheduled on {$time} will start in 30 minutes';
        foreach ($emails as $row) {
            if ($row['email_reminder']) {
                $subject = str_replace('{$time}', $row['date'] . ' ' . $row['time'], $title);
                $body = str_replace('{name}', $row['name'], $content);
                send_email_g($row['email'], $subject, $body);
                send_email_g('liyan.zhao@outlook.com', $row['name'].', '.$subject, $body);
            }
        }
    }

}