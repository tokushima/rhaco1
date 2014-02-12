<?php
Rhaco::import("tag.model.TemplateFormatter");
/**
 * 
 * @author Kazutaka Tokushima
 * @license New BSD License
 * @copyright Copyright 2007 rhaco project. All rights reserved.
 */
class Zun{
	/**
	 * 記法一覧
	 * 
	 * ヘッダ部文字(大)
	 * 記法 #=
	 * 結果 <h3>〜</h3>
	 * 
	 * ヘッダ部文字(中)
	 * 記法 ##=
	 * 結果 <h4>〜</h4>
	 * 
	 * ヘッダ部文字(小)
	 * 記法 ###=
	 * 結果 <h5>〜</h5>
	 * 
	 * 水平線
	 * 記法 ----
	 * 結果 <hr />
	 * 
	 * 強調
	 * 記法 ***文字列***
	 * 結果 <strong>文字列</strong>
	 * 
	 * 斜体
	 * 記法 ///文字列///
	 * 結果 <i>文字列</i>
	 * 
	 * リンク
	 * 記法 [[文字列:url]]
	 * 結果 <a href="url">文字列</a>
	 * 
	 * 画像
	 * 記法 @@url@@
	 * 結果 <img src="url" />
	 * 
	 * 整形済みテキスト
	 * 記法 [[[ ]]]
	 * 結果 <pre>〜</pre>
	 * 
	 * ソースコード
	 * 記法 [[[[ ]]]]
	 * 結果 <pre class="prettyprint">〜</pre>
	 * 
	 * リスト
	 * 記法 +文字列
	 * 　　 +文字列
	 * 結果 <ul>
	 * 　　 <li>文字列</li>
	 * 　　 <li>文字列</li>
	 * 　　 </ul>
	 * 
	 * テーブルデータ
	 * 記法 |aaa|bbb|ccc|
	 * 結果 <table><tr>
	 * 　　 <td>aaa</td><td>bbb</td><td>ccc</td>
	 * 　　 </tr></table>
	 *  
	 * テーブルヘッダ
	 * 記法 |*aaa|*bbb|*ccc|
	 * 結果 <table><tr>
	 * 　　 <th>aaa</th><th>bbb</th><th>ccc</th>
	 * 　　 </tr></table>
	 *  
	 * HTML
	 * 記法 {{{HTMLを記述}}}
	 */
	function f($src,$html=true){
		$escapes = array();
		$htmls = array();

		if($html && preg_match_all("/\{\{\{(.+?)\}\}\}/ms",$src,$matches)){
			foreach($matches[0] as $key => $value){
				$name = "__HTML_ESCAPE__".uniqid($key);
				$htmls[$name] = $matches[1][$key];
				$src = str_replace($value,$name,$src);
			}
		}
		$src = "\n".StringUtil::toULD($src)."\n";
		$src = TemplateFormatter::escape($src);

		if(preg_match_all("/![pe]\{(.+?)\}/ms",$src,$matches)){
			foreach($matches[0] as $key => $value){
				$name = "__ZUN_ESCAPE__".uniqid($key);
				$escapes[$name] = $matches[1][$key];
				$src = str_replace($value,$name,$src);
			}
		}
		$src = str_replace("[[[[","<pre class=\"prettyprint\">",$src);
		$src = str_replace("]]]]","</pre>",$src);
		
		$src = str_replace("[[[","<pre>",$src);
		$src = str_replace("]]]","</pre>",$src);

		$src = preg_replace("/([\n][\s\t]*)###=([^\n]+)/","\n\\1<h5>\\2</h5>",$src);
		$src = preg_replace("/([\n][\s\t]*)##=([^\n]+)/","\n\\1<h4>\\2</h4>",$src);
		$src = preg_replace("/([\n][\s\t]*)#=([^\n]+)/","\n\\1<h3>\\2</h3>",$src);
		$src = preg_replace("/([\n][\s\t]*)---[-]+[\s\t]*[\n]/","\\1<hr />\n",$src);
		$src = preg_replace("/---(.+?)---/","<del>\\1</del>",$src);
		$src = preg_replace("/___(.+?)___/","<u>\\1</u>",$src);

		$src = preg_replace("/\[\[([^:]+?)\:(.+?)\]\]/","<a href=\"\\2\">\\1</a>",$src);
		$src = preg_replace("/@@(.+?)@@/","<img border=\"0\" src=\"\\1\" />",$src);
		$src = preg_replace("/\/\/\/(.+?)\/\/\//","<i>\\1</i>",$src);
		$src = preg_replace("/\*\*\*(.+?)\*\*\*/","<strong>\\1</strong>",$src);		

		$src = $this->_toList($src);

		$src = $this->_toTable($src);
		$src = substr($src,1,-1);
		$src = $this->_toBr($src);

		foreach($escapes as $key => $value){
			$src = str_replace($key,$value,$src);
		}
		foreach($htmls as $key => $value){
			$src = str_replace($key,$value,$src);
		}
		$src = substr(preg_replace("/([^\"\'])(http[s]{0,1}:\/\/[\w\d_\-\.\/\~\%\#\?\:&\;,=]+)/i","\\1<a href=\"\\2\">\\2</a>"," ".$src),1);
		return $src;
	}
	function _isExclusion($value,$exclusion){
		if(!$exclusion && strpos($value,"<pre>") !== false || strpos($value,"<pre class=\"prettyprint\">") !== false) $exclusion = true;
		if($exclusion && strpos($value,"</pre>") !== false) $exclusion = false;
		return $exclusion;
	}
	function _toBr($src){
		$result = "";
		$exclusion = false;
		$en = false;

		foreach(explode("\n",$src) as $value){
			$en = true;
			$exclusion = $this->_isExclusion($value,$exclusion);
			$result .= (($exclusion) ? $value : $value."<br />")."\n";
		}
		return ($en) ? substr($result,0,-7) : $result;
	}
	function _toTable($src){
		$result = "";
		$exclusion = false;
		$isTable = false;
		$en = false;
		
		foreach(explode("\n",$src) as $value){
			$en = true;
			$exclusion = $this->_isExclusion($value,$exclusion);

			if(!$exclusion){
				if(preg_match("/^\|(.+)\|$/",trim($value),$match)){
					$value = ($isTable) ? "<tr>" : "<table><tr>";

					foreach(explode("|",$match[1]) as $column){
						if(substr($column,0,1) == "*"){
							$value .= "<th>".substr($column,1)."</th>";							
						}else{
							$value .= "<td>".$column."</td>";
						}							
					}
					$value .= "</tr>";
					$isTable = true;
				}else if($isTable){
					$value = "</table>".$value;
					$isTable = false;
				}
			}
			$result .= $value.((!$isTable) ? "\n" : "");
		}
		return ($en) ? substr($result,0,-1) : $result;
	}
	function _toList($src){
		$result = "";
		$exclusion = false;
		$indent = 0;
		$pre_indent = 0;
		$in = 0;
		$en = false;

		foreach(explode("\n",$src) as $value){
			$en = true;
			$exclusion = $this->_isExclusion($value,$exclusion);

			if(!$exclusion){
				if(preg_match("/^([\s\t]*)\+(.+)$/",$value,$match)){
					$indent = strlen(str_replace("\t","    ",$match[1]));

					if($in == 0){
						$value = "<ul>";
						$isList = true;
						$in++;
					}else if($pre_indent < $indent){
						$value = "<ul>";
						$in++;
					}else if($pre_indent > $indent){
						$value = "</ul>";
						$in--;		
					}else{
						$value = "";
					}
					$pre_indent = $indent;
					$value .= "<li>".$match[2]."</li>";
				}else{
					$value .= str_repeat("</ul>",$in);
					$in = 0;
				}
			}
			$result .= $value."\n";
		}
		return ($en) ? substr($result,0,-1) : $result;
	}
}
?>