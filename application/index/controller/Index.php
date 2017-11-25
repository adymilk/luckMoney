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

    public function index(){
        $curUser = Session::get('curUser');
        //没有获取到微信用户的openid，可能是因为在非微信客户端内访问的!
        if ($curUser != null){

            //查询用户表，判断此人openid是否已经存在
            $count  = Db::table('stjz_user')->where('openid',$curUser)->count();
            if ($count != 0){
                //此人openid已存在数据库，则定向到已参加过活动页面
                $this->redirect('Index/oldUser');
            }else{
                /*
                $total 红包总额
                $num 发几个
                $min  最小红包
                */
                function get_hongbao($total, $num,$min=1)
                {
                    $money_arr = array();
                    $return_arr = array();
                    for ($i = 1; $i <$num; ++$i) {
                        $max =round($total, 2)/($num-$i);
                        $random =  0.01+ mt_rand() / mt_getrandmax() * (0.99- 0.01);
                        $money = $random*$max;
                        $money = $money<=$min?$min:$money;
                        $money =floor($money*100)/100;
                        $total = $total - $money;
                        $money_arr[$i] = round($money, 2);
                    }
                    $money_arr[$i] = round($total, 2);
                    shuffle($money_arr);
                    $return_arr['money'] = $money_arr;
                    $return_arr['total'] = array_sum($money_arr);
                    return $return_arr;
                }

                //本次活动参数配置
                $list = Db::table('stjz_activity')->select();
                $balance = $list[0]['balance'];//剩余红包金额
                $remaining_num = $list[0]['remaining_num'];//剩余红包数量
                $min = $list[0]['min'];//最小红包金额
                $data = get_hongbao($balance, $remaining_num,$min);
                //打印所有红包金额
                //dump($data);
                //取得随机红包数组的第一条数据
                $luck_money = $data['money'][0];
                if ($luck_money<=1){
                    $luck_money = 1;
                }
                $this->assign('luck_money',$luck_money);

                return $this->fetch();
            }

        }else{
            echo '请在微信客户端打开！';
            return ;
        }


    }

    //获取用户openid

    public function getUser(){
        /**
         *获取用户openid
         */

//        $appid = "wxeae7cae254903553";
//        $secret = "895cfcc6ffde96014f12385182fe47e5";
//
//        $code = $_GET["code"];
//        $get_token_url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$appid.'&secret='.$secret.'&code='.$code.'&grant_type=authorization_code';
//
//        $res = file_get_contents($get_token_url);
//        $res = json_decode($res);
//        $curUser = $res->openid;

        //测试用例， 上线前必须注释这一行！
        $curUser = 'wxeae7cae254903553';

        // 判断账户余额是否为零
        $list = Db::table('stjz_activity')->where('id',1)->select();
        $balance = $list[0]['balance'];
        if ($balance <= 0){
            $this->redirect('Index/activityIsOver');
        }else{
            //存储当前用户到session
            Session::set('curUser',$curUser);
            $list  = Db::table('stjz_user')->where('openid',$curUser)->count();
            if ($list != 0){
                $this->redirect('Index/oldUser');
            }else{
                $this->redirect('Index/index');
            }
        }


    }

    //随机红包

    /**
     * @return \think\response\Json
     */
    public function saveUserToDb(){
            $name = $_POST['name'];
            $tel = $_POST['tel'];
            $luck_money = $_POST['luck_money'];
            $openid = $_POST['openid'];
            $data = ['openid'=>$openid,'luck_money'=>$luck_money,'name'=>$name,'tel'=>$tel];
            $rs = Db::table('stjz_user')->insert($data);

            if ($rs !=0){
//                $Packet = new Packet();
//                $Packet ->_route($openid,$luck_money * 100);

                return json(['status'=>200,'msg'=>'红包已发送！请到公众号内领取！']);
            }else{
//                dump($rs);
                return json(['status'=>500,'msg'=>'太多人啦，请稍后再试！']);
            }
            //查询本次活动金额数量配置表
            $list = Db::table('stjz_activity')->where('id',1)->select();
            $total = $list[0]['balance'];
            $remaining_num = $list[0]['remaining_num'];
            // 剩余红包总额
            $balance = $total - $luck_money;
            // 剩余红包数量
            $remaining_num = $remaining_num - 1;
            Db::table('stjz_activity')->where('id',1)->update(['balance'=>$balance,'remaining_num'=>$remaining_num]);
            Session::clear('curUser');
    }

    /**
     * 已经参与过领红包活动，再次进入的用户
     */

    function oldUser(){

        return $this->fetch();
    }

    // 账户余额 为0 自动结束活动
    function activityIsOver(){
        return $this->fetch();
    }

}
