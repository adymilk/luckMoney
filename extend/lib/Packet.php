<?php
namespace lib;
class Packet{
	private $wxapi;

    /**
     * @param $openid
     * @param $money
     * @param $wishing
     * @return SimpleXMLElement
     */
    function _route($openid, $money){
		@require_once "oauth2.php";
		$this->wxapi = new Wxapi();
        return $this->wxapi->pay($openid, $money);
    }
}
