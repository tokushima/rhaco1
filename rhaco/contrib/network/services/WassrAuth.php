<?php
Rhaco::import("network.http.ServiceRestAPIBase");
/**
 * Wassr auth
 *
 * Wassr Auth(http://wassr.jp/help/auth)を利用する
 *
 * @author Takuya Sato <nazo at highfreq dot net>
 * @license New BSD License
 */
class WassrAuth extends ServiceRestAPIBase
{
	var $app_key;
	var $secret;
	var $hosturl = "http://wassr.jp";
	var $loginpath = "/auth/";
	
	function WassrAuth($app_key, $secret) {
		parent::ServiceRestAPIBase();
		
		$this->app_key = $app_key;
		$this->secret = $secret;
	}
	
	function getLoginURL($perms = "token") {
		$time = time();
		
		$sig = $this->getLoginSignature($perms);
		
		$perms = urlencode($perms);
		
		$url = "{$this->hosturl}{$this->loginpath}?app_key={$this->app_key}&perms={$perms}&sig={$sig}";
		
		return $url;
	}

	function isValidSignature($requests) {
		$hmac = null;
		if (isset($requests['hmac'])) {
			$hmac = $requests['hmac'];
			unset($requests['hmac']);
		}
		return ($hmac === $this->makeSignature($requests));
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
	
	function getLoginSignature($perms) {
		$params = array(
						"app_key"=>$this->app_key,
						"perms"=>$perms);
		return $this->makeSignature($params);
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

