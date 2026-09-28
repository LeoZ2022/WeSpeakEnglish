<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2021/8/5
 * Time: 17:36
 */


define("APPKEY","zQYMkkuypM9rGe4HfeVQA5");
define("APPID","ITwqXpkbfXAGwPKDCRFgI");
define("MS","sS6LUYXymZ5PB9UO5dYT78");
define("URL","*");
define("CID1","*");
define("CID2","*");
define("CID3","*");

list($usec, $sec) = explode(" ", microtime());
$timeStamp = ($sec . substr($usec, 2, 3));
$sign = hash("sha256", APPKEY . $timeStamp . MS);



function curl_post_json($url, $header, $data = NULL, $json = false)
{
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    if (!empty($data)) {
        if ($json && is_array($data)) {
            $data = json_encode($data);
        }
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        if ($json) { //发送JSON数据
            curl_setopt($curl, CURLOPT_HEADER, 0);
            curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        }
    }

    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $res = curl_exec($curl);
    $errorno = curl_errno($curl);

    if ($errorno) {
        return array('errorno' => false, 'errmsg' => $errorno);
    }
    curl_close($curl);
    return json_decode($res, true);
}