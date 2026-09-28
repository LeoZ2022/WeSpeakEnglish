<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/9/7
 * Time: 10:41
 */

return [
    'site_url' => 'https://www.wespeakenglish.chat/',
    //每日统计接收邮箱
    'CFG_SYSTEM_EMAIL' => 'liyan.zhao@outlook.com',
    //30分钟未支付自动取消
    'CFG_NOT_PAYMENT' => 30,
    'cfg_default_timezone' => 8,
    'cfg_default_timezone_name' => 'Asia/Shanghai',

    'MAIL_FROM_NAME' => 'WeSpeakEnglish',

//    'MAIL_FROM_EMAIL' => '2522842983@qq.com',
//    'MAIL_HOST_ADDRESS' => 'smtp.qq.com',
//    'MAIL_PASSWORD' => 'whoknowme',
//    'MAIL_USERNAME' => '2522842983@qq.com',

// custom.php hostinger
    'MAIL_FROM_EMAIL' => 'notification@wespeakenglish.net',
    'MAIL_HOST_ADDRESS' => 'smtp.hostinger.com',
    'MAIL_PASSWORD' => '*',
    'MAIL_USERNAME' => 'notification@wespeakenglish.net',
    
   'MAIL_FROM_EMAIL3' => 'notification@speakingsite.com',
   'MAIL_HOST_ADDRESS3' => 'smtp.hostinger.com',
   'MAIL_PASSWORD3' => '*',
   'MAIL_USERNAME3' => 'notification@speakingsite.com',
   
   'MAIL_FROM_EMAIL4' => 'noreply@wespeakenglish.net',
   'MAIL_HOST_ADDRESS4' => 'smtp.hostinger.com',
   'MAIL_PASSWORD4' => '*',
   'MAIL_USERNAME4' => 'noreply@wespeakenglish.net',   
 //obsoleted mail.wespeakenglish.net'
   'MAIL_FROM_EMAIL2' => 'notification@wespeakenglish.net',
   'MAIL_HOST_ADDRESS2' => 'mail.wespeakenglish.net',
   'MAIL_PASSWORD2' => '*',
   'MAIL_USERNAME2' => 'notification@wespeakenglish.net',
   
    'MAIL_FROM_EMAIL5' => 'leo@wespeakenglish.net',
    'MAIL_HOST_ADDRESS5' => 'smtp.hostinger.com',
    'MAIL_PASSWORD5' => '*',
    'MAIL_USERNAME5' => 'leo@wespeakenglish.net',
    
    'MAIL_FROM_EMAIL_g' => 'support@wespeakenglish.chat',
    'MAIL_HOST_ADDRESS_g' => 'smtp.gmail.com',
    'MAIL_PASSWORD_g' => '*',
    'MAIL_USERNAME_g' => 'support@wespeakenglish.chat',
    
    'MAIL_FROM_EMAIL_gn' => 'notification@wespeakenglish.chat',
    'MAIL_HOST_ADDRESS_gn' => 'smtp.gmail.com',
    'MAIL_PASSWORD_gn' => '*',
    'MAIL_USERNAME_gn' => 'notification@wespeakenglish.chat',
   
    'pay_types' =>  [1=>'Paypal', 'Wechat', 'Alipay', 'China Wechat'],

    'withdraw_status_cn'   =>  ['提交申请', '已完成', '失败'],
    'withdraw_status'   =>  ['Submitted', 'Success', 'Fail'],

    //系统所在时区，用来计算0时区和其他时区时间
    'server_imezone'    =>  '8',
    //上课完成状态id
    'status_completed'  => 2,
    //取消订单状态id
    'status_cancelled'  => 4,
    //未完成订单状态id
    'status_nosucced'  => 3,
    //10分钟内退出，取消课程
    'leave_minute'  => 10,

    'classin' => [
        'classin_sid'   =>  '25408598',
        'classin_secret'    =>  'Y6m7nU5R'
    ],

    //是否使用支付宝
    'alipay_state' => 0,
    //是否使用snap wechat
    'wechat_snap_state' => 0,
    //是否使用国内微信支付
    'wechat_china_state' => 1,

];
