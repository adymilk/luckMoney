<?php

namespace app\index\controller;

use think\Controller;
use think\Db;
use think\Session;
use lib\CommonUtil;
use lib\MD5SignUtil;
use lib\WxHongBaoHelper;
use lib\Packet;

class Index extends Controller
{

    public function index()
    {
        $curUser = Session::get('curUser');
        //没有获取到微信用户的openid，可能是因为在非微信客户端内访问的!
        if ($curUser != null) {
            //查询用户表，判断此人openid是否已经存在
            $count = Db::table('stjz_user')->where('openid', $curUser)->count();
            if ($count != 0) {
                //此人openid已存在数据库，则定向到已参加过活动页面
                $this->redirect('Index/oldUser');
            } else {
                //本次活动参数配置
                $list = Db::table('stjz_activity')->select();
                $balance = $list[0]['balance'];//剩余红包金额
                $remaining_num = $list[0]['remaining_num'];//剩余红包数量
                $min = $list[0]['min'];//最小红包金额
                $data = get_hongbao($balance, $remaining_num, $min);
//打印所有红包金额
dump($data);
                /**
                 * 取得随机红包数组的第一条数据
                 * 当红包金额 <= 1 时， 则令其等于 1
                 * 当红包金额 >= 5时， 则令其等于 5
                 */

                $luck_money = $data['money'][0];
                if ($luck_money <= 1) {
                    $luck_money = 1;
                }elseif ($luck_money >= 5){
                    $luck_money = 5;
                }
                $this->assign('luck_money', $luck_money);
                return $this->fetch();
            }

        } else {
            echo '<h1 align="center">请在微信客户端打开！</h1>';
        }


    }

    //获取用户openid

    public function getUser()
    {


        // 判断账户余额是 <= 零
        $list = Db::table('stjz_activity')->where('id', 1)->select();
        $balance = $list[0]['balance'];
        if ($balance <= 0) {
            $this->redirect('Index/activityIsOver');
        } else {
            /**
             *获取用户openid
             */
//            $appid = "wxeae7cae254903553";
//            $secret = "895cfcc6ffde96014f12385182fe47e5";
//            $code = $_GET["code"];
//            $get_token_url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid=' . $appid . '&secret=' . $secret . '&code=' . $code . '&grant_type=authorization_code';
//
//            $res = file_get_contents($get_token_url);
//            $res = json_decode($res);
//            $curUser = $res->openid;

//TODO 测试用例， 上线前必须注释这一行！
 $curUser = 'okxe3wmrVnQYIYJOGC_k3REh7_1U';
            //存储当前用户到session
            Session::set('curUser', $curUser);
            $list = Db::table('stjz_user')->where('openid', $curUser)->count();
            if ($list != 0) {
                $this->redirect('Index/oldUser');
            } else {
                $this->redirect('Index/index');
            }
        }


    }

    //随机红包

    /**
     * @return \think\response\Json
     */
    public function saveUserToDb()
    {
        $name = $_POST['name'];
        $tel = $_POST['tel'];
        $luck_money = $_POST['luck_money'];
        $openid = $_POST['openid'];
        $data = ['openid' => $openid, 'luck_money' => $luck_money, 'name' => $name, 'tel' => $tel];
        $rs = Db::table('stjz_user')->insert($data);

        if ($rs != 0) {
            $Packet = new Packet();
            $wechatRt = $Packet->_route($openid, $luck_money * 100);
//打印微信支付返回结果
//dump($wechatRt);
            //查询本次活动金额数量配置表
            $list = Db::table('stjz_activity')->where('id', 1)->select();
            $total = $list[0]['balance'];
            $remaining_num = $list[0]['remaining_num'];
            // 剩余红包总额
            $balance = $total - $luck_money;
            // 剩余红包数量
            $remaining_num = $remaining_num - 1;
            Db::table('stjz_activity')->where('id', 1)->update(['balance' => $balance, 'remaining_num' => $remaining_num]);
            Session::clear('curUser');
            return json('红包已发送！请到公众号内领取！');

        } else {
// dump($rs);
            return json('太多人啦，请稍后再试！');
        }
    }

    /**
     * 已经参与过领红包活动，再次进入的用户
     */

    function oldUser()
    {

        return $this->fetch();
    }

    // 账户余额 为0 自动结束活动
    function activityIsOver()
    {
        return $this->fetch();
    }

}
