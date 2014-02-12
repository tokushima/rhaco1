<?php
Rhaco::import("network.http.ServiceRestAPIBase");
Rhaco::import("lang.StringUtil");
Rhaco::import("lang.Variable");
/**
 * Yahoo!Japan Morphological Analysis
 * http://developer.yahoo.co.jp/jlp/MAService/V1/parse.html
 * 
 * @author Takuya Sato
 * @author Kazutaka Tokushima
 * @license New BSD License
 * @copyright Copyright 2007 rhaco project. All rights reserved.
 * @require rhaco 1.6.0
 */
class YahooMAService extends ServiceRestAPIBase {
	var $api_version = "V1";
	var $appid;
	var $url = "http://jlp.yahooapis.jp";
	var $service_name = "MAService";
	var $action_name;
	
	var $in_results = "ma";
	var $in_response = "surface,reading,pos";
	var $in_filter = "9|10";

	var $in_ma_response = "";
	var $in_ma_filter = "";
	var $in_uniq_response = "";
	var $in_uniq_filter = "";
	var $in_uniq_by_baseform = false;
	
	function YahooMAService($appid) {
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
	
	/**
	 * filterに指定可能な品詞番号
	 * @param int 
	 * 1 : 形容詞
	 * 2 : 形容動詞
	 * 3 : 感動詞
	 * 4 : 副詞
	 * 5 : 連体詞
	 * 6 : 接続詞
	 * 7 : 接頭辞
	 * 8 : 接尾辞
	 * 9 : 名詞
	 * 10 : 動詞
	 * 11 : 助詞
	 * 12 : 助動詞
	 * 13 : 特殊（句読点、カッコ、記号など）
	 * 
	 */	
	function setFilter() {
		$filters = func_get_args();
		if(sizeof($filters) == 0) $filters = range(1,13);
		$this->in_filter = implode("|",$filters);
	}

	function setMaResponse($ma_response) {
		$in_ma_response = $ma_response;
	}
	
	function setMaFilter($ma_filter) {
		$in_ma_filter = $ma_filter;
	}
	
	function setUniqResponse($uniq_response) {
		$in_uniq_response = $uniq_response;
	}
	
	function setUniqFilter($uniq_filter) {
		$in_uniq_filter = $uniq_filter;
	}

	/**
	 * このパラメータが true ならば、基本形の同一性により、uniq_result の結果を求めます。
	 *
	 * @param boolean $uniq_by_baseform
	 */
	function setUniqByBaseform($uniq_by_baseform) {
		$this->in_uniq_by_baseform = Variable::bool($uniq_by_baseform);
	}
	
	function parse($sentence) {
		$this->action_name = 'parse';

		$pTag = new SimpleTag();
		$pTag->set(
			$this->get(
				array(
					"appid"=>$this->appid,
					"sentence"=>StringUtil::encode($sentence,StringUtil::UTF8()),
					"results"=>$this->in_results,
					"response"=>$this->in_response,
					"filter"=>$this->in_filter,
					"ma_response"=>(empty($this->in_ma_response) ? $this->in_response : $this->in_ma_response),
					"ma_filter"=>(empty($this->in_ma_filter) ? $this->in_filter : $this->in_ma_filter),
					"uniq_response"=>(empty($this->in_uniq_response) ? $this->in_response : $this->in_uniq_response),
					"uniq_filter"=>(empty($this->in_uniq_filter) ? $this->in_filter : $this->in_uniq_filter),
					"uniq_by_baseform"=>Variable::bool($this->in_uniq_by_baseform,true)
				)
			)
		);
		return $pTag->toHash();
	}
	
	function buildUrl($hash = array()) {
		return parent::buildUrl($hash, array(), '/' . $this->service_name . '/' . $this->api_version . '/' . $this->action_name);
	}
	
	/**
	 * かな読みを返す
	 */
	function kana($sentence){
		$result = "";
		$revert = array($this->in_results,$this->in_response,$this->in_filter,$this->in_ma_response,
						$this->in_ma_filter,$this->in_uniq_response,$this->in_uniq_filter,$this->in_uniq_by_baseform);
		$this->setFilter();
		$this->setResults("ma");
		$this->setResponse("reading,pos");
		$hash = $this->parse(StringUtil::encode($sentence,StringUtil::UTF8()));

		if(isset($hash["ma_result"]["word_list"]["word"])){
			foreach($hash["ma_result"]["word_list"] as $words){
				if(!isset($words[0])) $words = array($words);
				foreach($words as $word){
					if($word["pos"] != "特殊") $result .= StringUtil::encode($word["reading"],StringUtil::UTF8());
				}
			}
		}
		list($this->in_results,$this->in_response,$this->in_filter,$this->in_ma_response,
				$this->in_ma_filter,$this->in_uniq_response,$this->in_uniq_filter,$this->in_uniq_by_baseform) = $revert;

		$result = $this->alphaToKana($this->romanToKana($this->noToKana($result)));
		return extension_loaded("mbstring") ? mb_convert_kana($result,"c",StringUtil::UTF8()) : $result;
	}
	
	function noToKana($sentence){
		/***
		 * eq("ひゃくにじゅうさん",YahooMAService::noToKana(123));
		 * eq("せんにひゃくさんじゅうよん",YahooMAService::noToKana(1234));
		 * eq("いちまんにせんさんびゃくよんじゅうご",YahooMAService::noToKana(12345));
		 * eq("じゅうにまんさんぜんよんひゃくごじゅうろく",YahooMAService::noToKana(123456));
		 * eq("ひゃくにじゅうさんまんよんせんごひゃくろくじゅうなな",YahooMAService::noToKana(1234567));
		 * eq("せんにひゃくさんじゅうよんまんごせんろっぴゃくななじゅうはち",YahooMAService::noToKana(12345678));
		 * eq("いちおくにせんさんびゃくよんじゅうごまんろくせんななひゃくはちじゅうきゅう",YahooMAService::noToKana(123456789));
		 * eq("せんひゃくじゅういち",YahooMAService::noToKana(1111));
		 * eq("さんぜんよんひゃくごじゅうろく",YahooMAService::noToKana(3456));
		 */
		if(preg_match_all("/[\d]+/",$sentence,$matches)){
			$nos = array("","いち","に","さん","よん","ご","ろく","なな","はち","きゅう");
			$digitAs = array("","じゅう","ひゃく","せん");
			$digitBs = array("","じゅう","びゃく","ぜん");
			$digitCs = array("","じゅう","ぴゃく","せん");
			$digits = array("","まん","おく","ちょう","まんちょう","おくちょう","まんおくちょう","けい");

			foreach($matches[0] as $value){
				$reading = "";

				if(intval(str_repeat("9",8*4)) < intval($value)){
					$nos[0] = "ぜろ";

					for($i=strlen($value)-1,$y=0;$i>=0;$i--,$y++){
						$reading = $nos[intval($value[$i])].$reading;
					}
				}else{
					for($i=strlen($value)-1,$y=0;$i>=0;$i--,$y++){
						if($value[$i] != 0){
							switch($value[$i]){
								case 1:
									$reading = (($y%4 != 0) ? "" : $nos[intval($value[$i])]).$digitAs[$y%4].(($y%4 == 0) ? $digits[floor($y/4)] : "").$reading;
									break;
								case 3:
									$reading = $nos[intval($value[$i])].$digitBs[$y%4].(($y%4 == 0) ? $digits[floor($y/4)] : "").$reading;
									break;
								case 6:
									$reading = (($y%4 == 2) ? "ろっ" : $nos[intval($value[$i])]).$digitCs[$y%4].(($y%4 == 0) ? $digits[floor($y/4)] : "").$reading;
									break;
								case 8:
									$reading = (($y%4 > 1) ? "はっ" : $nos[intval($value[$i])]).$digitCs[$y%4].(($y%4 == 0) ? $digits[floor($y/4)] : "").$reading;
									break;
								default:
									$reading = $nos[intval($value[$i])].$digitAs[$y%4].(($y%4 == 0) ? $digits[floor($y/4)] : "").$reading;
							}
						}
					}
				}
				if(!empty($reading)) $sentence = str_replace($value,$reading,$sentence);
			}
			$sentence = str_replace("0","ぜろ",$sentence);
		}
		return $sentence;		
	}
	function romanToKana($sentence){
		$from = array(
			"pa","pi","pu","pe","po","pya","pyu","pyo",
			"ba","bi","bu","be","bo","bya","byu","byo",
			"va","vi","vu","ve","vo","vya","vyu","vyo",
			"da","di","du","de","do","dya","dyu","dyo",
			"za","zi","zu","ze","zo","zya","zyu","zyo",
			"ja","ji","ju","je","jo","jya","jyu","jyo",
			"ga","gi","gu","ge","go","gya","gyu","gyo",
			"ka","ki","ku","ke","ko","kya","kyu","kyo",
			"sa","shi","si","su","se","so","sya","syu","syo",
			"ta","ti","tsu","tu","te","to","tya","tyu","tyo",
			"ca","ci","csu","ce","co","cya","cyu","cyo",
			"na","ni","nu","ne","no","nya","nyu","nyo",
			"ha","hi","hu","he","ho","hya","hyu","hyo",
			"ma","mi","mu","me","mo","mya","myu","myo",
			"ya","yu","yo",
			"ra","ri","ru","re","ro","rya","ryu","ryo",
			"wa","wi","we","wo",
			"a","i","u","e","o",
			"nn","n"
		);
		
		$to = array(
			"ぱ","ぴ","ぷ","ぺ","ぽ","ぴゃ","ぴゅ","ぴょ",
			"ば","び","ぶ","べ","ぼ","びゃ","びゅ","びょ",
			"う゛ぁ","う゛ぃ","う゛","う゛ぇ","う゛ぉ","びう゛ゃ","う゛ゅ","う゛ょ",
			"だ","ぢ","づ","で","ど","ぢゃ","ぢゅ","ぢょ",
			"ざ","じ","ず","ぜ","ぞ","じゃ","じゅ","じょ",
			"じゃ","じ","じゅ","じぇ","じょ","じゃ","じゅ","じょ",			
			"が","ぎ","ぐ","げ","ご","ぎゃ","ぎゅ","ぎょ",
			"か","き","く","け","こ","きゃ","きゅ","きょ",
			"さ","し","し","す","せ","そ","しゃ","しゅ","しょ",
			"た","ち","つ","つ","て","と","ちゃ","ちゅ","ちょ",
			"か","し","く","せ","こ","ちゃ","ちゅ","ちょ",
			"な","に","ぬ","ね","の","にゃ","にゅ","にょ",
			"は","ひ","ふ","へ","ほ","ひゃ","ひゅ","ひょ",
			"ま","み","む","め","も","みゃ","みゅ","みょ",
			"や","ゆ","よ",
			"ら","り","る","れ","ろ","りゃ","りゅ","りょ",
			"わ","うぃ","うぇ","を",
			"あ","い","う","え","お",
			"ん","ん"
		);
		return str_replace($from,$to,strtolower($sentence));
	}
	function alphaToKana($sentence){
		$from = array("a","b","c","d","e","f","g","h","i","j","k","l","m","n","o","p","q","r","s","t","u","v","w","x","y","z");
		$to = array("えー","びー","しー","でぃー","いー","えふ","じー","えっち","あい","じぇい","けー","える","えむ","えぬ","おー","ぴー","きゅー","あーる","えす","てぃー","ゆー","ぶい","だぶりゅう","えっくす","わい","ぜっと");
		return str_replace($from,$to,strtolower($sentence));
	}
}
