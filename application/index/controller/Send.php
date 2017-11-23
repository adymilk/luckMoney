<?php
namespace Home\Controller;
use Think\Controller;
class Send extends Controller {

    private $appId;
    private $appSecret;

    // 写死
    private $mch_id = "13512302";//商户号
    private $send_name = "山东统一银座";//红包发送者名称
    private $client_ip = "114.114.114.114"; //调用红包接口的主机的IP,服务端IP,写死，即脚本文件所在的IP
    private $apikey = "asdasdasdasdasdasdasdasdasdasda";//商户支付密钥Key
    private $min_value = 100;//最小红包金额，单位分
    private $max_value = 20000;//最大红包金额，单位分
    private $total_num = 1;//发放人数。固定值1，不可修改
    private $nick_name = "山东统一银座"; //红包商户名称，提供方名称
    private $wishing = "恭喜发财"; //  红包祝福语
    private $act_name = "山东统一银座抽奖"; //活动名称8个中文字
    private $remark = "山东统一银座祝您生活愉快";//备注信息

    //证书，绝对地址
    private $apiclient_cert = "/cert/apiclient_cert.pem";
    private $apiclient_key = "/cert/apiclient_key.pem";


    private $parameters; //cft 参数

    public function _initialize() {
        $this->appId =C('wxeae7cae254903553');
        $this->appSecret =C('895cfcc6ffde96014f12385182fe47e5');


    }


    // 发送红包
    public function sendHongBao($toOpenId,$amount){

        unset($setParameter);

        $this->setParameter ( "nonce_str", $this->great_rand() ); //随机字符串，丌长于 32 位
        $this->setParameter ( "mch_billno", $this->get_mch_billno() ); //订单号
        $this->setParameter ( "mch_id", $this->mch_id ); //商户号
        $this->setParameter ( "wxappid", C('APPID') );
        $this->setParameter ( "nick_name", $this->nick_name ); //红包商户名称，提供方名称
        $this->setParameter ( "send_name", $this->send_name ); //红包发送者名称
        $this->setParameter ( "re_openid", $toOpenId ); //相对于医脉互通的openid
        $this->setParameter ( "total_amount", $amount * 100 ); //付款金额，单位分
        $this->setParameter ( "min_value", $this->min_value ); //最小红包金额，单位分
        $this->setParameter ( "max_value", $this->max_value ); //最大红包金额，单位分
        $this->setParameter ( "total_num", $this->total_num ); //红包収放总人数
        $this->setParameter ( "wishing", $this->wishing ); //红包祝福诧
        $this->setParameter ( "client_ip", $this->client_ip ); //调用接口的机器 Ip 地址
        $this->setParameter ( "act_name", $this->act_name ); //活劢名称
        $this->setParameter ( "remark", $this->remark ); //备注信息




        //生成签名
        $this->get_sign($api_key);

        //生成xml
        $postXml = $this->create_hongbao_xml ( $this->apikey );
        //提交请求
        $url = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/sendredpack';
        $responseXml = $this->curl_post_ssl ( $url, $postXml );
        $responseObj = simplexml_load_string ( $responseXml, 'SimpleXMLElement', LIBXML_NOCDATA );
        //转换成数组
        $responseArr = ( array ) $responseObj;

        $return_code = $responseArr ['return_code'];
        $result_code = $responseArr ['result_code'];

        //判断是否红包是否发送成功
        if ($return_code == "SUCCESS" && $result_code == "SUCCESS") {
            return true;
        } else {
            return false;
        }


    }

    /**
     * WXHongBao::gen_mch_billno()
     * 商户订单号（每个订单号必须唯一）
    组成： mch_id+yyyymmdd+10位一天内不能重复的数字。
    接口根据商户订单号支持重入， 如出现超时可再调用。
     * @return void
     */
    private function get_mch_billno(){
        //生成一个长度10，的阿拉伯数字随机字符串
        $rnd_num = array('0','1','2','3','4','5','6','7','8','9');
        $rndstr = "";
        while(strlen($rndstr)<10){
            $rndstr .= $rnd_num[array_rand($rnd_num)];
        }

        return $this->mch_id . date("Ymd") . $rndstr;

    }
    /*
    <xml>
        <sign>![CDATA[E1EE61A9]]</sign>
        <mch_billno>![CDATA[00100]]</mch_billno>
        <mch_id>![CDATA[888]]</mch_id>
        <wxappid>![CDATA[wxcbda96de0b165486]]</wxappid>
        <nick_name>![CDATA[nick_name]]</nick_name>
        <send_name>![CDATA[send_name]]</send_name>
        <re_openid>![CDATA[onqOjjXXXXXXXXX]]</re_openid>
        <total_amount>![CDATA[100]]</total_amount>
        <min_value>![CDATA[100]]</min_value>
        <max_value>![CDATA[100]]</max_value>
        <total_num>![CDATA[1]]</total_num>
        <wishing>![CDATA[恭喜发财]]</wishing>
        <client_ip>![CDATA[127.0.0.1]]</client_ip>
        <act_name>![CDATA[新年红包]]</act_name>
        <act_id>![CDATA[act_id]]</act_id>
        <remark>![CDATA[新年红包]]</remark>
    </xml>
    */
    //生成红包接口XML信息
    private function create_hongbao_xml($api_key) {
        // $this->setParameter ( 'sign', $this->get_sign ( $api_key ) );

        return $this->arrayToXml ( $this->parameters );
    }

    //设置参数
    private function setParameter($parameter, $parameterValue) {
        $this->parameters [ $this->trimString ( $parameter )] = $this->trimString ( $parameterValue );
    }
    //获取参数
    private function getParameter($parameter) {
        return $this->parameters [$parameter];
    }

    /**
    例如：
    appid：    wxd111665abv58f4f
    mch_id：    10000100
    device_info：  1000
    Body：    test
    nonce_str：  ibuaiVcKdpRxkhJA
    第一步：对参数按照 key=value 的格式，并按照参数名 ASCII 字典序排序如下：
    stringA="appid=wxd930ea5d5a258f4f&body=test&device_info=1000&mch_i
    d=10000100&nonce_str=ibuaiVcKdpRxkhJA";
    第二步：拼接支付密钥：
    stringSignTemp="stringA&key=192006250b4c09247ec02edce69f6a2d"
    sign=MD5(stringSignTemp).toUpperCase()
    ="9A0A8659F005D6984697E2CA0A9CF3B7"
     */
    private function get_sign(){

        $param["act_name"]=$this->parameters["act_name"];
        if($this->total_num==1){ //这些是裂变红包用不上的参数，会导致签名错误
            $param["client_ip"]=$this->parameters["client_ip"];
            $param["max_value"]=$this->parameters["max_value"];
            $param["min_value"]=$this->parameters["min_value"];
            $param["nick_name"]=$this->parameters["nick_name"];
        }

        $param["mch_billno"] = $this->parameters["mch_billno"];
        $param["mch_id"]=$this->parameters["mch_id"];
        $param["nonce_str"]=$this->parameters["nonce_str"];
        $param["re_openid"]=$this->parameters["re_openid"];
        $param["remark"]=$this->parameters["remark"];
        $param["send_name"]=$this->parameters["send_name"];
        $param["total_amount"]=$this->parameters["total_amount"];
        $param["total_num"]=$this->parameters["total_num"];
        $param["wishing"]=$this->parameters["wishing"];
        $param["wxappid"]=$this->parameters["wxappid"];


        ksort($param);

        $signStr = "";
        foreach($param as $k => $v){
            $signStr .= $k."=".$v."&";
        }
        $signStr .= "key=".$this->apikey;

        $sign = strtoupper(md5($signStr));

        $this->setParameter ( 'sign', $sign );
        return $sign;
    }

    private function formatQueryParaMap($paraMap, $urlencode) {
        $buff = "";
        ksort ( $paraMap );
        foreach ( $paraMap as $k => $v ) {
            if (null != $v && "null" != $v && "sign" != $k) {
                if ($urlencode) {
                    $v = urlencode ( $v );
                }
                $buff .= $k . "=" . $v . "&";
            }
        }
        $reqPar = "";
        if (strlen ( $buff ) > 0) {
            $reqPar = substr ( $buff, 0, strlen ( $buff ) - 1 );
        }
        return $reqPar;
    }

    /**
     * trim
     *
     * @param value
     * @return
     */
    private  function trimString($value) {
        $ret = null;
        if (null != $value) {
            $ret = $value;
            if (strlen ( $ret ) == 0) {
                $ret = null;
            }
        }
        return $ret;
    }


    private function arrayToXml($arr) {
        $xml = "<xml>";
        foreach ( $arr as $key => $val ) {
            if (is_numeric ( $val )) {
                $xml .= "<" . $key . ">" . $val . "</" . $key . ">";

            } else {
                $xml .= "<" . $key . "><![CDATA[" . $val . "]]></" . $key . ">";
            }
        }
        $xml .= "</xml>";
        return $xml;
    }

    //获取随机数
    private function great_rand() {
        return strtoupper(md5(mt_rand().time()));//确保不重复而已
    }

    //提交请求
    private function curl_post_ssl($url, $vars) {
        $ch = curl_init ();
        //超时时间
        curl_setopt ( $ch, CURLOPT_TIMEOUT, 10);
        curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
        //这里设置代理，如果有的话
        curl_setopt ( $ch, CURLOPT_URL, $url );
        curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, false );
        curl_setopt ( $ch, CURLOPT_SSL_VERIFYHOST, false );

        //cert 与 key 分别属于两个.pem文件
        // curl_setopt($ch, CURLOPT_SSLCERT, $this->apiclient_cert);
        // curl_setopt($ch, CURLOPT_SSLKEY, $this->apiclient_key);
        // curl_setopt($ch, CURLOPT_CAINFO, $this->rootca);
        curl_setopt($ch,CURLOPT_SSLCERTTYPE,'PEM');
        curl_setopt($ch,CURLOPT_SSLCERT,$this->apiclient_cert);
        curl_setopt($ch,CURLOPT_SSLKEYTYPE,'PEM');
        curl_setopt($ch,CURLOPT_SSLKEY,$this->apiclient_key);

        // if (count ( $aHeader ) >= 1) {
        //     curl_setopt ( $ch, CURLOPT_HTTPHEADER, $aHeader );
        // }

        curl_setopt ( $ch, CURLOPT_POST, 1 );
        curl_setopt ( $ch, CURLOPT_POSTFIELDS, $vars );
        $data = curl_exec ( $ch );
        if ($data) {
            curl_close ( $ch );
            return $data;
        } else {
            $error = curl_errno ( $ch );
            curl_close ( $ch );
            return false;
        }
    }

}