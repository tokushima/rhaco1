<?php
Rhaco::import("network.http.ServiceRestAPIBase");
/**
 * Livedoor auth
 *
 * Livedoor Auth(http://auth.livedoor.com/)を利用する
 *
 * @author Takuya Sato <nazo at highfreq dot net>
 * @license New BSD License
 */
class LivedoorAuth extends ServiceRestAPIBase
{
	var $protocol_version = "1.0";
	var $app_key;
	var $secret;
	var $format = "xml";
	var $hosturl = "http://auth.livedoor.com";
	var $loginpath = "/login/";
	var $rpcpath = "/rpc/auth";
	var $lasterror = "";
	
	function LivedoorAuth($app_key, $secret) {
		parent::ServiceRestAPIBase();
		
		$this->app_key = $app_key;
		$this->secret = $secret;
	}
	
	function getLoginURL($perms, $userdata = "") {
		$time = time();
		
		$sig = $this->getLoginSignature($time, $perms, $userdata);
		
		$ver = urlencode($this->protocol_version);
		$perms = urlencode($perms);
		$userdata = urlencode($userdata);
		
		$url = "{$this->hosturl}{$this->loginpath}?app_key={$this->app_key}&perms={$perms}&t={$time}&v={$ver}&sig={$sig}&userdata={$userdata}";
		
		return $url;
	}
	
	function getId($token) {
		$time = time();
		
		$params = array(
						"app_key"=>$this->app_key,
						"t"=>$time,
						"v"=>$this->protocol_version,
						"format"=>$this->format,
						"token"=>$token
					);
		
		$sig = $this->makeSignature($params);
		$params["sig"] = $sig;
		
		$this->url = $this->hosturl . $this->rpcpath;
		
		$pTag = new SimpleTag();
		$pTag->set($this->post($params), "response");
		
		$error = $pTag->getInValue("error");
		if ($error != "0") {
			$this->lasterror = $pTag->getInValue("message");
			return false;
		}
		
		$userTag = $pTag->getIn("user");
		return $userTag[0]->getInValue("livedoor_id");
	}
	
	function getLastError() {
		return $this->lasterror;
	}
	
	function makeSignature($array) {
		$param = "";
		if (isset($array["sig"])) {
			unset($array["sig"]);
		}
		ksort($array);
		foreach ($array as $key=>$value) {
			$param .= "{$key}{$value}";
		}
		
		return $this->hmacsha1($this->secret, $param);
	}
	
	function getLoginSignature($time, $perms, $userdata) {
		return $this->makeSignature(
					array(
						"app_key"=>$this->app_key,
						"t"=>$time,
						"v"=>$this->protocol_version,
						"perms"=>$perms,
						"userdata"=>$userdata
					));
	}

	/**
	 * http://www.php.net/manual/en/function.sha1.php#39492
	 * @author mark at dot BANSPAM dot pronexus dot nl
	 * @license Creative Commons Attribution License
	 */
	//Calculate HMAC-SHA1 according to RFC2104
	// http://www.ietf.org/rfc/rfc2104.txt
	function hmacsha1($key,$data) {
		$blocksize=64;
		$hashfunc='sha1';
		if (strlen($key)>$blocksize)
			$key=pack('H*', $hashfunc($key));
		$key=str_pad($key,$blocksize,chr(0x00));
		$ipad=str_repeat(chr(0x36),$blocksize);
		$opad=str_repeat(chr(0x5c),$blocksize);
		$hmac = pack(
			'H*',$hashfunc(
				($key^$opad).pack(
					'H*',$hashfunc(
						($key^$ipad).$data
					)
				)
			)
		);
		return bin2hex($hmac);
	}

}

