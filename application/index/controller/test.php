<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/22
 * Time: 13:17
 */
header("Content-type: text/html; charset=utf-8");

$Packet = new Packet();
$res = $Packet ->_route(okxe3wmrVnQYIYJOGC_k3REh7_1U,100);
$return_code = '发放状态：';
$return_msg = '返回信息：';
echo $return_code.$res['return_code'].'</br>'.$return_msg.$res['return_msg'];
var_dump($res);


https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxeae7cae254903553&redirect_uri=http://www.0551shengteng.com/luckMoney/public/index.php/index/index/getUser&response_type=code&scope=snsapi_base&state=123wechat_redirect
