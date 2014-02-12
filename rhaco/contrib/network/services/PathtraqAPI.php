<?php
Rhaco::import('network.http.ServiceRestAPIBase');
Rhaco::import('tag.model.SimpleTag');
/**
 * PathtraqAPI
 *
 * @author  riaf <riafweb@gmail.com>
 * @package arbo <http://code.google.com/p/arbo/>
 * @license New BSD License
 * @version $Id: PathtraqAPI.php 24 2008-07-27 03:24:29Z riafweb $
 */
class PathtraqAPI extends ServiceRestAPIBase
{
	var $url = 'http://api.pathtraq.com/';
	var $method;

	/**
	 * キーワード・URL検索API
	 * http://pathtraq.com/developer/#help_search
	 *
	 * @param   string $url 指定したいキーワード、URLを半角スペース区切りで指定
	 * @param   string $m   (任意) スコープ (popular: 定番, hot: 人気, upcoming: 注目) を指定します。省略すると hot
	 * @return  array/false
	 */
	function search($url, $m='hot'){
		$this->method = 'pages';
		return $this->_request(compact('url', 'm'));
	}

	/**
	 * ニュースランキング取得API
	 * http://pathtraq.com/developer/#help_news
	 *
	 * @param   array $param genreとmが指定できる
	 * @return  array/false
	 */
	function getNewsRanking($param=array()){
		$this->method = 'news_ja';
		return $this->_request($param);
	}

	/**
	 * カテゴリランキング取得API
	 * http://pathtraq.com/developer/#help_category_ranking
	 *
	 * @param   array $param genreとmが指定できる
	 * @return  array/false
	 */
	function getCategoryRanking($param=array()){
		$this->method = 'popular';
		return $this->_request($param);
	}

	/**
	 * ページカウンタAPI
	 * http://pathtraq.com/developer/#help_page_counter
	 *
	 * @param   string $url ヒット数のカウントを取得したい URL を指定
	 * @param   string $m   (任意) スコープ (popular: 定番, hot: 人気, upcoming: 注目) を指定します。省略すると hot
	 * @return  array/false
	 */
	function getPageCounter($url, $m='hot'){
		$this->method = 'page_counter';
		$api = 'xml';
		if(SimpleTag::setof($tag, $this->get(compact('url', 'm', 'api')), 'result')){
			return $tag->toHash();
		}
		return false;
	}

	/**
	 * ページチャートAPI
	 * http://pathtraq.com/developer/#help_page_chart
	 *
	 * @param   string $url   アクセスチャートデータを取得したい URL を指定
	 * @param   string $scale 24h (24時間)、1w (1週間)、1m (1ヶ月)、3m (3ヶ月)のいずれかを指定します。省略した場合は 24h となります。
	 * @return  array/false
	 */
	function getPageChart($url, $scale='24h'){
		$this->method = 'page_chart';
		$api = 'xml';
		if(SimpleTag::setof($tag, $this->get(compact('url', 'scale', 'api')), 'result')){
			return $tag->toHash();
		}
		return false;
	}

	/**
	 * URL正規化API
	 * http://pathtraq.com/developer/#help_normalize_url
	 *
	 * @param   string $url 正規化を行いたい URL を指定します。
	 * @return  string/false 正規化されたURL
	 */
	function getNormalizedUrl($url){
		$this->method = 'normalize_url2';
		$api = 'xml';
		if(SimpleTag::setof($tag, $this->get(compact('url', 'api')), 'url')){
			return $tag->getValue();
		}
		return false;
	}

	function _request($param=array()){
		$param['api'] = 'rss';
		if(SimpleTag::setof($tag, $this->get($param), 'channel')){
			return $tag->toHash();
		}
		return false;
	}

	function buildUrl($hash=array()){
		return parent::buildUrl($hash, array(), $this->method);
	}
}

