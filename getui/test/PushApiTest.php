<?php
require_once(dirname(__FILE__) . '/' . '../GTClient.php');

define("APPKEY","zQYMkkuypM9rGe4HfeVQA5");
define("APPID","ITwqXpkbfXAGwPKDCRFgI");
define("MS","sS6LUYXymZ5PB9UO5dYT78");
define("URL","https://restapi.getui.com");
define("CID1","7ca8a58d1b4f685948e0a05e434d9602");
define("CID2","*");
define("CID3","*");


$token = null;
$taskId = null;
$api = new GTClient(URL,APPKEY,APPID,MS);
//pushToSingleByCid();
echo "<br/>";
//pushBatchByCid();
echo "<br/>";
pushAll();


function pushToSingleByCid(){
    $push = getParam();
    $push->setCid(CID1);
    global $api;
    echo json_encode($api->pushApi()->pushToSingleByCid($push));
}

function pushBatchByCid(){
    $batch = new GTPushBatchRequest();
    $push = getParam();
    $push->setCid(CID3);
//    $push1 = getParam();
//    $push1->setCid(CID1);
    $batch->setMsgList(array($push));
//    $batch->addMsgList($push1);
    $batch->setIsAsync(false);

    global $api;
    echo json_encode($api->pushApi()->pushBatchByCid($batch));
}

function pushAll(){
    $push = getParam();
    $push->setGroupName("test");
    global $api;
    echo json_encode($api->pushApi()->pushAll($push));
}

function stoppushApi(){
    global $api;
    echo json_encode($api->pushApi()->stopPush("taskid"));
}

function queryScheduleTask(){
    global $api;
    echo json_encode($api->pushApi()->queryScheduleTask("taskid"));
}

function deleteScheduleTask(){
    global $api,$tasId;
    echo json_encode($api->pushApi()->deleteScheduleTask("taskid"));
}

function getParam(){
    $push = new GTPushRequest();
    $push->setRequestId(micro_time());
    //设置setting
    $set = new GTSettings();
    $set->setTtl(3600000);
//    $set->setSpeed(1000);
//    $set->setScheduleTime(1591794372930);
    $strategy = new GTStrategy();
    $strategy->setDefault(GTStrategy::STRATEGY_THIRD_FIRST);
//    $strategy->setIos(GTStrategy::STRATEGY_GT_ONLY);
//    $strategy->setOp(GTStrategy::STRATEGY_THIRD_FIRST);
//    $strategy->setHw(GTStrategy::STRATEGY_THIRD_ONLY);
    $set->setStrategy($strategy);
    $push->setSettings($set);
    //设置PushMessage，
    $message = new GTPushMessage();
    //通知
    $notify = new GTNotification();
    $notify->setTitle("notdif yddd");
    $notify->setBody("notify bdoddy");
    $notify->setBigText("bigTdext");
    //与big_text二选一
//    $notify->setBigImage("BigImage");

//    $notify->setLogo("push.png");
//    $notify->setLogoUrl("LogoUrl");
    $notify->setChannelId("Default");
    $notify->setChannelName("Default");
    $notify->setChannelLevel(2);

    $notify->setClickType("none");
    $notify->setIntent("intent:#Intent;component=你的包名/你要打开的 activity 全路径;S.parm1=value1;S.parm2=value2;end");
    $notify->setUrl("url");
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
    $pushChannel = new GTPushChannel();
    //ios
    $ios = new GTIos();
    $ios->setType("notify");
    $ios->setAutoBadge("1");
    $ios->setPayload("ios_payload");
    $ios->setApnsCollapseId("apnsCollapseId");
    //aps设置
    $aps = new GTAps();
    $aps->setContentAvailable(0);
    $aps->setSound("com.gexin.ios.silenc");
    $aps->setCategory("category");
    $aps->setThreadId("threadId");

    $alert = new GTAlert();
    $alert->setTitle("alert title");
    $alert->setBody("alert body");
    $alert->setActionLocKey("ActionLocKey");
    $alert->setLocKey("LocKey");
    $alert->setLocArgs(array("LocArgs1","LocArgs2"));
    $alert->setLaunchImage("LaunchImage");
    $alert->setTitleLocKey("TitleLocKey");
    $alert->setTitleLocArgs(array("TitleLocArgs1","TitleLocArgs2"));
    $alert->setSubtitle("Subtitle");
    $alert->setSubtitleLocKey("SubtitleLocKey");
    $alert->setSubtitleLocArgs(array("subtitleLocArgs1","subtitleLocArgs2"));
    $aps->setAlert($alert);
    $ios->setAps($aps);
    return $push;

    $multimedia = new GTMultimedia();
    $multimedia->setUrl("url");
    $multimedia->setType(1);
    $multimedia->setOnlyWifi(false);
    $multimedia2 = new GTMultimedia();
    $multimedia2->setUrl("url2");
    $multimedia2->setType(2);
    $multimedia2->setOnlyWifi(true);
    $ios->setMultimedia(array($multimedia));
    $ios->addMultimedia($multimedia2);
    $pushChannel->setIos($ios);
    return $push;
    /*
    //安卓
    $android = new GTAndroid();
    $ups = new GTUps();
//    $ups->setTransmission("ups Transmission");
    $thirdNotification = new GTThirdNotification();
    $thirdNotification->setTitle("title".micro_time());
    $thirdNotification->setBody("body".micro_time());
    $thirdNotification->setClickType(GTThirdNotification::CLICK_TYPE_URL);
    $thirdNotification->setIntent("intent:#Intent;component=你的包名/你要打开的 activity 全路径;S.parm1=value1;S.parm2=value2;end");
    $thirdNotification->setUrl("http://docs.getui.com/getui/server/rest_v2/push/");
    $thirdNotification->setPayload("payload");
    $thirdNotification->setNotifyId(456666);
    $ups->addOption("HW","badgeAddNum",1);
    $ups->addOption("OP","channel","Default");
    $ups->addOption("OP","aaa","bbb");
    $ups->addOption(null,"a","b");

    $ups->setNotification($thirdNotification);
    $android->setUps($ups);
    $pushChannel->setAndroid($android);
    $push->setPushChannel($pushChannel);
    */

    return $push;
}

function micro_time()
{
    list($usec, $sec) = explode(" ", microtime());
    $time = ($sec . substr($usec, 2, 3));
    return $time;
}

