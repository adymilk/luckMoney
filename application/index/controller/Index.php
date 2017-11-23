<?php
namespace app\index\controller;
use think\Loader;
use think\Controller;
use think\Db;
use think\Session;
use lib\Packet;

class Index extends Controller
{

    public function index(){

        $curUser = Session::get('curUser');
        $count  = Db::table('stjz_user')->where('openid',$curUser)->count();
        if ($count != 0){
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
            $total = $list[0]['total'];
            $num = $list[0]['num'];
            $min = $list[0]['min'];
            $data = get_hongbao($total, $num,$min);

            //取得数组的第一条数据
            $luck_money = $data['money'][0];
            $this->assign('luck_money',$luck_money);

            return $this->fetch();
        }

    }

    //获取用户openid

    public function getUser(){
        /**
         *获取用户openid
         */

        $appid = "wxeae7cae254903553";
        $secret = "895cfcc6ffde96014f12385182fe47e5";

        $code = $_GET["code"];
        $get_token_url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$appid.'&secret='.$secret.'&code='.$code.'&grant_type=authorization_code';

        $res = file_get_contents($get_token_url);
        $res = json_decode($res);
        $curUser = $res->openid;

        //测试用例， 上线前必须注释这一行！
//        $curUser = 'wxeae7cae254903553';
        //存储当前用户到session
        Session::set('curUser',$curUser);
        $count  = Db::table('stjz_user')->where('openid',$curUser)->count();
       if ($count != 0){
           $this->redirect('Index/oldUser');
       }else{
            $this->redirect('Index/index');
       }

    }

    //随机红包
    public function saveUserToDb(){
            $name = $_POST['name'];
            $tel = $_POST['tel'];
            $luck_money = $_POST['luck_money'];
            $openid = $_POST['openid'];
            $data = ['openid'=>$openid,'luck_money'=>$luck_money,'name'=>$name,'tel'=>$tel];
            $rs = Db::table('stjz_user')->insert($data);
            if ($rs !=0){
                $Packet = new Packet();
                $res = $Packet ->_route($openid,$luck_money);
                $return_code = '发放状态：';
                $return_msg = '返回信息：';
                echo $return_code.$res['return_code'].'</br>'.$return_msg.$res['return_msg'];
                var_dump($res);
                return json(['status'=>200,'msg'=>'红包已发送！请到公众号内领取！']);
            }else{
                return json(['status'=>291,'msg'=>'太多人啦，请稍后再试！']);
            }
    }

    /**
     * 已经参与过领红包活动，再次进入的用户
     */

    function oldUser(){

        return $this->fetch();
    }

    function test(){
        Loader::import('lib\pay',EXTEND_PATH);
        $Packet = new Packet();
        $res = $Packet ->_route('okxe3wmrVnQYIYJOGC_k3REh7_1U',100);
        $return_code = '发放状态：';
        $return_msg = '返回信息：';
        echo $return_code.$res['return_code'].'</br>'.$return_msg.$res['return_msg'];
        var_dump($res);
    }
}
