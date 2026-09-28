<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/12/10
 * Time: 22:58
 */

return [
    'snappay'   => [
        'url'   =>  'https://open.snappay.ca/api/gateway',
//        'signKey' => '7e2083699dd510575faa1c72f9e35d43',
//        'app_id' => '9f00cd9a873c511e',
//        'merchant_no'   => '901800000116',
//        'trans_currency'    => 'CAD',

        'signKey' => '102a1b08eaf30967b170aaec3e6ab2cd',
        'app_id' => 'a4ada47de56beb6e',
        'merchant_no'   => '902100002297',
        'trans_currency'    => 'USD',
    ],
    'paypal' => [
        // live 生产环境
        'clientId'  =>  'AfZYl4HOISKEt0SHglCBD77hzz1MMmVbovO1xNSiWTaqNsKklJCkKAI9CfCxzVZNCPvwrIETlMLemWVz',
        'clientSecret' => 'EMnQESXoyI8T7XsJFpQ_G028glgMr9tjVYKphZOMgdE1H8e23olEcoFGOmfyRGEONcqwzdOHH1EVfP5d',
        'ipnpb_url' =>  'https://ipnpb.paypal.com/cgi-bin/webscr',

        // sandbox 开发环境
//        'clientId'  =>  'AeJIsEjbNnaLmonho09MOMuX5sTIWTJZpNqxy8VbU-oH-v0HRX2IVyGjbVoEU-536_s8IqM_ammKwh53',
//        'clientSecret' => 'EFaHPboWKNEcw8z2bY4tIrKUfJhQQHB1Y39Puor5VHPWXuaaHL7yeAWT-A9TZgq2NryE5yB3G6skqy5f',
//        'clientId'  =>  'ASCok4-aoJAceBkjJKMcB4NmQP6CLzugRYDNFh3l23A4qSy9peP0CGVQx1sr5MHcYXwQCxmSPUM9WwHH',
//        'clientSecret' => 'EKNmNRSWHlu3h237nJp2jw8X9ZG-3elz8ikOZdX-JzAyaYJR_xLDLYdOq1AU8UG5hdaTHpnvqPMuZMuk',
//        'ipnpb_url' =>  'https://ipnpb.sandbox.paypal.com/cgi-bin/webscr',
        //返回地址
        'accept_url' => 'https://www.wespeakenglish.chat/index/payment/callback',
        'notify_url' => 'https://www.wespeakenglish.chat/index/payment/notify',
        'cancel_url' => 'https://www.wespeakenglish.chat/index/payment/cancel',

        //币种 美元
        'Currency' => 'USD',
    ],

    'wechat' => [
        'debug'      => false, // 沙箱模式
        'app_id'     => 'wxe058c06720d43323', // 应用ID
        'AppSecret' => 'qwertyuiopQWERTYUIOPasdfghjkl890',
        'mch_id'     => '1605747830', // 微信支付商户号
        'mch_key'    => 'qwertyuiopQWERTYUIOPasdfghjkl890', // 微信支付密钥
        'ssl_cer'    => '', // 微信证书 cert 文件
        'ssl_key'    => '', // 微信证书 key 文件
        'notify_url' => 'https://www.wespeakenglish.chat/index/payment/notify_wechat', // 支付通知URL
        'cache_path' => '',// 缓存目录配置（沙箱模式需要用到）
    ],
    // 支付宝支付参数
    'alipay' => [
        'debug'       => false, // 沙箱模式
        'app_id'      => '', // 应用ID
        'public_key'  => '', // 支付宝公钥(1行填写)
        'private_key' => '', // 支付宝私钥(1行填写)
        'notify_url'  => 'https://www.wespeakenglish.chat/index/payment2/notify_alipay', // 支付通知URL
    ]

];
