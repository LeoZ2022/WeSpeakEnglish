<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2021/5/10
 * Time: 21:27
 */

return [
    //腾讯视频配置
 #   'appid' => '1400496555',
 #   'key'   => 'ed687cd32b7b9972e6c47882190ec4cbfb729078d2090a21953d00dfac53c7ea',
    'appid' => '1400625111',
    'key'   => 'b8dc2ab6131c023e89b7c6ba7b048917ba07e8e92be18578f0322547a8b9923c',
    'scert' => 'WeSpeakEnglish2021',
    'video_url' => '160605.livepush.myqcloud.com',
    'video_url2' => 'yyy.wespeakenglish.chat',

    //极光推送配置
    'push_appkey'   => '38b9a3183894c26058ba8945',
    'push_secret'   => '070cc191bb47e550a9e06eed',

    //轮询间隔
    'poll_time' => 5,
    //websocket连接地址
    'websocket_connect_url' => 'http://app.wespeakenglish.chat:2120',

    //websocket发送消息地址
    'websocket_send_url' => 'http://app.wespeakenglish.chat:2121?type=publish&to={$to}&content={$content}',

    //个推
    'getui' => [
        'app_id' => '8a2L2mcB3V8Ww1a33qTmv6',
        'app_secret' => 'z4XIeq17d9AdrED9czhqe9',
        'app_key' => 'LQ2GLQdeBc7akD6W894zk1',
        'master_secret' => 'Sk8h1Xpatw5JAhNaHuhm7A'
    ],
    //demo
//    'getui' => [
//        'app_id' => 'ITwqXpkbfXAGwPKDCRFgI',
//        'app_secret' => 'Tb7NQ24koG7TtLs78keibA',
//        'app_key' => 'zQYMkkuypM9rGe4HfeVQA5',
//        'master_secret' => 'sS6LUYXymZ5PB9UO5dYT78'
//    ]
];
