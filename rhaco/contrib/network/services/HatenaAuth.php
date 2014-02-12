<?php
Rhaco::import('network.http.ServiceRestAPIBase');
Rhaco::import('tag.model.TemplateFormatter');
Rhaco::import('tag.model.SimpleTag');
/**
 * はてな認証用ライブラリ
 *
 * @author  riaf <riafweb@gmail.com>
 * @license New BSD License
 * @version $Id: HatenaAuth.php 31 2008-01-24 14:07:21Z riafweb $
 */
class HatenaAuth extends ServiceRestAPIBase
{
	var $api_key;
	var $secret;
	var $url = 'http://auth.hatena.ne.jp';
	var $login_path = '/auth';
	var $json_path = '/api/auth.json';
	var $xml_path = '/api/auth.xml';
	var $auth_type = 'xml';

	function HatenaAuth($api_key, $secret){
		parent::ServiceRestAPIBase();
		$this->api_key = $api_key;
		$this->secret = $secret;
	}

	/**
	 * ログイン処理を行う
	 *
	 * @param   string $cert	ユーザーから受け取ったcert値
	 */
	function login($cert){
		$result = $this->get(array('cert' => $cert));
		if($result == false || Variable::bool($result['has_error'])){
			return false;
		}
		return $result['user'];
	}

	/**
	 * ログインするためのURLを取得（ユーザー用）
	 */
	function getLoginUrl($hash=array()){
		$params = $hash;
		$params['api_key'] = $this->api_key;
		$params['api_sig'] = $this->getApiSig($params);
		return $this->url . $this->login_path . '?' . TemplateFormatter::httpBuildQuery($params);
	}

	function getApiSig($params){
		$sig = $this->secret;
		$keys = array_keys($params);
		sort($keys);
		foreach($keys as $key) $sig .= $key . $params[$key];
		return md5($sig);
	}

	function buildUrl($hash){
		$params = array(
			'api_key' => $this->api_key,
		);
		$params['api_sig'] = $this->getApiSig(array_merge($params, $hash));
		$path = strtolower($this->auth_type) . '_path';
		return parent::buildUrl($hash, $params, $this->{$path});
	}

	function get($hash=array(), $iscache=false){
		$ret = parent::get($hash, $iscache);
		switch(strtolower($this->auth_type)){
			case 'json': // json_decode is needed.
				return json_decode($ret, true);

			case 'xml':
			default:
				$tag = new SimpleTag();
				if($tag->set($ret, 'response')){
					return $tag->toHash();
				}
		}
		return false;
	}
}

