<?php
Rhaco::import("lang.StringUtil");
Rhaco::import("tag.model.TemplateFormatter");
/**
 * BBCode
 * 
 * 記法一覧
 * 
 * 強調
 * 記法 [b]文字列[/b]
 * 結果 <b>文字列</b>
 * 
 * 斜体
 * 記法 [i]文字列[/i]
 * 結果 <i>文字列</i>
 * 
 * 下線
 * 記法 [u]文字列[/u]
 * 結果 <u>文字列</u>
 * 
 * 文字サイズ
 * 記法 [size=18]文字列[/size]
 * 結果 <span style="font-size: 18px">文字列</span>
 * 
 * 文字色
 * 記法 [color=red]文字列[/color]
 * 結果 <span style="color: red">文字列</span>
 * 
 * コード
 * 記法 [code]文字列[/code]
 * 結果 <code>文字列</code>
 * 
 * 画像
 * 記法 [img]url[/img]
 * 結果 <img src="url" border="0"/>
 * 
 * リンク
 * 記法 [url=url]文字列[/url]
 * 結果 <a href="url">文字列</a>
 * 
 * メール
 * 記法 [email]e-mail[/email]
 * 結果 <a href="mailto:e-mail">e-mail</a>
 * 
 * 引用
 * 記法 [quote]文字列[/quote]
 * 結果 <blockquote>文字列</blockquote>
 * 
 * リスト
 * 記法：[list]
 *	   [*]アイテム1
 *	   [*]アイテム2
 *	   [/list]
 * 結果：<ol>
 *		 <li>アイテム1</li>
 *		 <li>アイテム2</li>
 *	   </ol>
 * 
 * 記法：[list=type]
 *	   [*]アイテム1
 *	   [*]アイテム2
 *	   [/list]
 * 結果：<ul type="type">
 *		 <li>アイテム1</li>
 *		 <li>アイテム2</li>
 *	   </ul>
 * 
 * @author Kentaro YABE
 * @license New BSD License
 * @copyright Copyright 2008- rhaco project. All rights reserved.
 */
class BBCode{
	/**
	 * 変換
	 * @param string $src
	 * @param boolean $escape
	 * @const
	 */
	function f($src,$escape=true){
		/***
		 * $bb = new BBCode();
		 * eq("<b>文字列</b>",$bb->f("[b]文字列[/b]"));
		 * eq("<i>文字列</i>",$bb->f("[i]文字列[/i]"));
		 * eq("<u>文字列</u>",$bb->f("[u]文字列[/u]"));
		 * eq("<span style=\"font-size: 18px\">文字列</span>",$bb->f("[size=18]文字列[/size]"));
		 * eq("<span style=\"color: red\">文字列</span>",$bb->f("[color=red]文字列[/color]"));
		 * eq("<img src=\"url\" border=\"0\"/>",$bb->f("[img]url[/img]"));
		 * eq("<a href=\"url\">文字列</a>",$bb->f("[url=url]文字列[/url]"));
		 * eq("<a href=\"mailto:e-mail\">e-mail</a>",$bb->f("[email]e-mail[/email]"));
		 * eq("<blockquote>文字列</blockquote>",$bb->f("[quote]文字列[/quote]"));
		 * eq("<ul type=\"a\">\n<li>アイテム1</li>\n<li>アイテム2</li>\n</ul>",$bb->f("[list=a]\n[*]アイテム1\n[*]アイテム2\n[/list]"));
		 */
		$src = $escape ? TemplateFormatter::escape(StringUtil::toULD($src)) : $src;
		//non-nest tags
		$src = preg_replace("/\[b\](.*?)\[\/b\]/is","<b>\\1</b>",$src);
		$src = preg_replace("/\[i\](.*?)\[\/i\]/is","<i>\\1</i>",$src);
		$src = preg_replace("/\[u\](.*?)\[\/u\]/is","<u>\\1</u>",$src);
		$src = preg_replace("/\[size=(\d+?)\](.*?)\[\/size\]/is","<span style=\"font-size: \\1px\">\\2</span>",$src);
		$src = preg_replace("/\[color=(.+?)\](.*?)\[\/color\]/is","<span style=\"color: \\1\">\\2</span>",$src);
		$src = preg_replace("/\[code\](.*?)\[\/code\]/is","<code>\\1</code>",$src);
		$src = preg_replace("/\[img\](.*?)\[\/img\]/is","<img src=\"\\1\" border=\"0\"/>",$src);
		$src = preg_replace("/\[url\](.*?)\[\/url\]/is","<a href=\"\\1\">\\1</a>",$src);
		$src = preg_replace("/\[url=(.+?)\](.*?)\[\/url\]/is","<a href=\"\\1\">\\2</a>",$src);
		$src = preg_replace("/\[email\](.*?)\[\/email\]/is","<a href=\"mailto:\\1\">\\1</a>",$src);
		//blockquote
		$src = BBCode::_toQuote($src);
		//list
		$src = BBCode::_toList($src);
		return $src;
	}
	function _toQuote($src){
		/*** #pass */
		preg_match_all("/\[quote\]/i",$src,$opentags);
		preg_match_all("/\[\/quote\]/i",$src,$closetags);
		
		for($i=0;$i<count($opentags[0])-count($closetags[0]);$i++){
			$src .= "</blockquote>";
		}
		
		$src = str_replace("[quote]","<blockquote>",$src);
		$src = str_replace("[/quote]","</blockquote>",$src);
		
		return $src;
	}
	function _toList($src){
		/*** #pass */
		preg_match_all("/\[list(?:=(.+?))?\]\n((?:\[\*\].+?\n)+)\[\/list\]/is",$src,$matches);
		
		foreach($matches[0] as $key=>$search){
			$type = $matches[1][$key];
			$openTag = ($type=="") ? "<ol>" : "<ul type=\"".$type."\">";
			$closeTag = ($type=="") ? "</ol>" : "</ul>";
			
			$replace = $openTag."\n";
			preg_match_all("/^\[\*\](.+?)$/m",$matches[2][$key],$items);
			foreach($items[1] as $item){
				$replace .= "<li>".$item."</li>\n";
			}
			$replace .= $closeTag;
			
			$src = str_replace($search, $replace, $src);
		}
		return $src;
	}
}
?>