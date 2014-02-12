<?php
Rhaco::import("network.http.ServiceRestAPIBase");
Rhaco::import("lang.StringUtil");
Rhaco::import("lang.Variable");
/**
 * Yahoo!Japan Morphological Analysis
 * http://developer.yahoo.co.jp/jlp/MAService/V1/parse.html
 * 
 * @author  SHIGETA Takeshiro
 * @author Takuya Sato
 * @author Kazutaka Tokushima
 * @license New BSD License
 * @copyright Copyright 2007 rhaco project. All rights reserved.
 * @require rhaco 1.6.0
 */
class YahooDAService extends ServiceRestAPIBase {
	var $api_version = "V1";
	var $appid;
	var $url = "http://jlp.yahooapis.jp";
	var $service_name = "DAService";
	var $action_name;
	
	function YahooDAService($appid) {
	/**
		$s = new YahooDAService("");
		$res = $s->parse("こんな私の故郷は非常に良い場所なのです。");
	 */
		parent::ServiceRestAPIBase();
		$this->appid = $appid;
	}

	/**
	 * 解析結果の種類
	 * @param $response = ma,uniq
	 */
	function setResults($results) {
		$this->in_results = $results;
	}
	
	/**
	 * 返される形態素情報
	 * @param $response = surface, reading, pos, baseform, feature
	 */
	function setResponse($response) {
		$this->in_response = str_replace(" ","",$response);
	}
	
	function parse($sentence) {
		$this->action_name = 'parse';

		$pTag = new SimpleTag();
		$pTag->set(
			$this->get(
				array(
					"appid"=>$this->appid,
					"sentence"=>StringUtil::encode($sentence,StringUtil::UTF8())
				)
			)
		);
		return $pTag->toHash();
	}
	
	function buildUrl($hash = array()) {
		return parent::buildUrl($hash, array(), '/' . $this->service_name . '/' . $this->api_version . '/' . $this->action_name);
	}
	
}
