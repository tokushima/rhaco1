<?php
Rhaco::import("tag.model.SimpleTag");
 /**
  * Xmlpath
  *
  * @author SHIGETA Takeshiro
  *   * 使える形式
  * (/, //(無視), text(), @name, hoge[1], hoge[last()], hoge[huga], hoge[huga=a], hoge[@huga], hoge[@huga=a],)
  * SimpleTagの性質上「選択したタグの子」という形式のみ扱い、
  * 常にルートから検索する形とします。(/a/bとa/bは同じ意味になります。//が頭に付いている場合はネストしたタグ内も検索します)
  * hoge[huga=a],hoge[@huga=a]形式の場合は1番最初に見つかった階層の該当するタグを検索します。
  * <hoge><hoge huga=a>text1</hoge></hoge> <foo><hoge><hoge huga=a>text2</hoge></hoge></foo>
  * はtext1, text2どちらも検索します。
  * <hoge huga=a>text1</hoge> <foo><hoge><hoge huga=a>text2</hoge></hoge></foo>
  * の場合はtext1のみが検索されます。
  * 
  * 例：
  * $SimpleTag::setof($src,$tag);
  * $result = Xmlpath::parse('div/div[@id=content]',$tag);
  * この場合$resultは要素にSimpleTagオブジェクトを持つ配列になります。
  * xpath式に'div/div[@id=content]/text()'とすると
  * $resultは該当したテキストを要素にもつ配列となります。
  */

class Xmlpath {
	function parse($xpath,$tag){//,$sensitive=false) {
	/***
	 * $src1 = "<tag><a b='1'><a b='3'>abc</a><b>0</b><a b='3'>jkl</a></a><a b='2'><a b='3'>def</a></a></tag>";
	 * $xpath1 = "//a[@b=3]";
	 * $xpath2 = "/a[@b=3]";
	 * $xpath3 = "/a";
	 * 
	 * SimpleTag::setof($tag,$src1);
	 * $parsed = Xmlpath::parse($xpath1,$tag);
	 * eq(3,sizeof($parsed));
	 *  
	 * eq("abc",$parsed[0]->getValue());
	 * eq("jkl",$parsed[1]->getValue());
	 * eq("def",$parsed[2]->getValue());
	 * $parsed = Xmlpath::parse($xpath2,$tag);
	 * eq(array(),$parsed);
	 * $parsed = Xmlpath::parse($xpath3,$tag);
	 * eq(2,sizeof($parsed));
	 * 
	 */
		if(empty($xpath)) return $tag;
		$elements = explode('/',$xpath);
		if(!$elements[0]) {//will be removed
			array_shift($elements);
		}
		$parsed = Xmlpath::_parse($elements,$tag);//,$sensitive);
		return $parsed;
	}
	
	function _parse ($elements,$tag){//,$sensitive=false) {
		$element = array_shift($elements);
		$deep = false;
		if(empty($element)) {
			$element = array_shift($elements);
			if(empty($element)) return false;
			$deep = true;
		}
			$tags = Xmlpath::_analyze($element,$tag,$deep);//,$sensitive);
			if(is_array($tags) && !empty($elements)) {
				$result = array();
				foreach($tags as $t) {
					$addtags = Xmlpath::_parse($elements,$t);
					if(is_array($addtags)) {
						$result = $result + Xmlpath::_parse($elements,$t);//,$sensitive);
					}
				}
				return $result;
			}else{
				return $tags;
			}
	}
	
	function _analyze ($element,&$tag,$deep = false){//,$sensitive = false) {
		$element = trim($element);
		if($element=='text()') {
			return $tag->getValue();
		}elseif($element[0]=='@') {
			$name = substr($element,1);
			return $tag->getParameter($name);
		}elseif(preg_match('/(.*?)\[(.*?)\]/',$element,$params)) {
			$params = array_map('strtolower',array_map('trim',$params));
			$tags = $tag->getIn($params[1]);
			if($params[2]=='last()') {
				$size = count($tags);
				return $tags[$size];
			}elseif(preg_match('/^[0-9]+$/',$params[2])) {
				return $tags[$params[2]];
			}elseif(strstr($params[2],'=')===false) {
				$result = array();
				foreach($tags as $dummy) {
					$childs = $dummy->getIn($params[2]);
					if(!empty($childs)) {
						$result[] = $dummy;
					}
				}
				return $result;
			}else{
				$result = array();
				list($key,$var) = array_map('trim',explode('=',$params[2]));
				$var = str_replace('"','',$var);
				if($key[0]=='@') {
					$key=substr($key,1);
					if($deep) {
						$result = Xmlpath::getInDeep($tag,new SimpleTag($params[1],null,array($key=>$var)));//,$sensitive);//echo 'deep';var_dump($result);
					}else{
						foreach($tags as $dummy) {
							if(strtolower($dummy->getParameter($key)) == $var) {
								$result[] = $dummy;
								break;
							}
						}
					}
				}else{
					if($deep) {
						$result = Xmlpath::getInDeep($tag,new SimpleTag($params[1],$var));//,$sensitive);
					}else{
						foreach($tags as $dummy) {
							$childs = $dummy->getIn($params[1]);
							if(!empty($childs)) {
								foreach($childs as $child) {
									if($child->getValue() == $var) {
										$result[] = $dummy;
										break;
									}
								}
							}
						}
					}
				}
				return $result;
			}
		}else{
			return $tag->getIn($element);
		}
		
		function _parseFunction ($str) {
			if(preg_match_all('/([a-zA-Z]+?)\s*\((.*?)\)/',$str,$match)) {
				return call_user_func(array('Xmlpath',$match[1]),$match[2]);
			}
		}
				
	}

	/**
	 * タグを深部まで探る
	 *
	 * @param unknown_type $tag
	 * @param unknown_type $tagcase
	 * @param unknown_type $sensitive
	 * @return unknown
	 */
	function getInDeep(&$tag, &$tagcase){//, $sensitive=false) {
				Xmlpath::_getInDeep($tag,$tagcase,$result);//,$sensitive);
		return $result;
	}
	
	function _getInDeep (&$tag, &$tagcase, &$result){//, $sensitive=false) {
//		if(!$sensitive){
		if($foundtags = $tag->getIn($tagcase->getName())){
			foreach($tagcase->getParameter() as $key=>$param){
				foreach($foundtags as $foundtag){
					if($foundtag->getParameter($key)===$param->getValue()){
						$result[] = $foundtag;
					}
//					if(preg_match_all('@<'.$tagcase->getName().'[^>]*?('.$key.')\s*=\s*(?:\"|\')('.$tagcase->getParameter($key).')(?:\"|\')@i',$foundtag->getPlain(),$match)){
//						foreach($match[0] as $mkey=>$mvar){
//							$tagcase->setParameter($match[1][$mkey],$match[2][$mkey]);
//							if($foundtags =& $tag->f($tagcase->getName())) {
//								foreach($foundtags as $foundtag){
//									$result[] = $foundtag;
//								}
//							}
//						}
//						break;
//					}
				}
			}
		}
//		}else{
//			if($foundtags =& $tag->find($tagcase)) {
//			if($foundtags =& $tag->getIn($tagcase->getName())) {
//			foreach($tagcase->getParameter() as $key=>$param){
//				foreach($foundtags as $foundtag){
//					if($foundtag->getParameter($key)===$param->getValue()){
//						$result[] = $foundtag;
//					}
//	   			}
//			}
//			}
//		}
		if (empty($result)) {
			foreach($tag->getIn($tagcase->getName()) as $eachtag){
				Xmlpath::_getInDeep($eachtag,$tagcase,$result);//,$sensitive);
			}
		}
	}
}
?>
