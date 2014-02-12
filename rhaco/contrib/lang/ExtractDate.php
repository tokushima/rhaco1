<?php
/**
 * Created on 2007/06/23
 *
 * 日本語表記の年月日を半角変換→年、月、日の取り出し
 * 年が年号の場合に平成・昭和を西暦に変換
 * DateUtilで読める形に変換して取り出し
 * 入力文字列はUTF8の必要あり
 *
 * @author SHIGETA Takeshiro
 * @license New BSD License
 * @copyright Copyright 2008- rhaco project. All rights reserved.
 */
class ExtractDate {
	function parse ($string,$pattern) {
		/***
		 */
		$regexpattern = ExtractDate::getPattern($pattern);
		$dateorder = ExtractDate::getOrder($pattern);
		if(preg_match('@'.$regexpattern.'@usi',$string,$match)) {
			array_shift($match);
			foreach($match as $key=>$var) {
				$match[$key] = ExtractDate::toHalf($var);
			}
			$year = ExtractDate::getYear($match[$dateorder["year"]],$string);
			$month = ExtractDate::getMonth($match[$dateorder["month"]]);
			$day = ExtractDate::getDay($match[$dateorder["day"]]);
			if(isset($dateorder["hour"])){
			$hour = ExtractDate::getHour($match[$dateorder["hour"]]);
			$minute = (isset($dateorder["minute"]))?ExtractDate::getMinute($match[$dateorder["minute"]]):"00";
			$second = (isset($dateorder["second"]))?ExtractDate::getSecond($match[$dateorder["second"]]):"00";
			$date = "$year-$month-$day $hour:$minute:$second";
			}else{
			$date = "$year-$month-$day";
			}
			return $date;
		}
	}

	function getYear($year,$string){
		/***
		 * eq(DateUtil::parse("2009-01-01"),DateUtil::parse(ExtractDate::parse("2009年1月1日","%Y年%m月%d日")));
		 * eq(DateUtil::parse("2009-01-01"),DateUtil::parse(ExtractDate::parse("平成21年1月1日","%nY年%m月%d日")));
		 * eq(DateUtil::parse("2009-01-01"),DateUtil::parse(ExtractDate::parse("２００９年１月１日","%Y年%m月%d日")));
		 * eq(DateUtil::parse("2009-01-01"),DateUtil::parse(ExtractDate::parse("二〇〇九年一月一日","%kY年%km月%kd日")));
		 * eq(DateUtil::parse("2009-01-01"),DateUtil::parse(ExtractDate::parse("平成二十一年一月一日","平成%kY年%km月%kd日")));
		 */
		if(strstr($string,"平成")!==false) {
			$year = 2000 + $year-12;
		}elseif(strstr($string,"昭和")!==false) {
			$year = 1900 + $year+25;
		}elseif(strlen($year)<4) {
			$year = $year + 2000;
		}
		return $year;
	}

	function getMonth ($month) {
		return sprintf("%02s",$month);
	}

	function getDay ($day) {
		return sprintf("%02s",$day);
	}

	function getHour ($hour="00") {
		return sprintf("%02s",$hour);
	}

	function getMinute ($minute="00") {
		return sprintf("%02s",$minute);
	}

	function getSecond ($second="00") {
		return sprintf("%02s",$second);
	}

	function getPattern ($pattern) {
		$kanjipattern = "一二三四五六七八九〇十百千";
		$from = array("%Y","%m","%d","%H","%M","%S",
				"%kY","%km","%kd","%nY");
		$to = array("([0-9０-９]{4})","([0-9０-９]{1,2})","([0-9０-９]{1,2})","([0-9０-９]{1,2})","([0-9０-９]{1,2})","([0-9０-９]{1,2})",
				"([$kanjipattern]{1,7})","([$kanjipattern]{1,3})","([$kanjipattern]{1,3})","([0-9０－９]{1,2})");
		return str_replace($from,$to,$pattern);
	}

	function getOrder ($pattern) {
		$order = array();
		preg_match_all('@(%[a-zA-Z]*Y|%[a-zA-Z]*m|%[a-zA-Z]*d|%[a-zA-Z]*H|%[a-zA-Z]*M|%[a-zA-Z]*S)@',$pattern,$match);
		foreach($match[1] as $key=>$var) {
			switch (substr($var,-1)) {
				case "Y":
					$order["year"] = $key;
					break;
				case "m":
					$order["month"] = $key;
					break;
				case "d":
					$order["day"] = $key;
					break;
				case "H":
					$order["hour"] = $key;
					break;
				case "M":
					$order["minute"] = $key;
					break;
				case "S":
					$order["second"] = $key;
					break;

				default:
					break;
			}

		}
		return $order;
	}

	function toHalf ($string) {
		if(strstr($string,'十')!==false || strstr($string,'百')!==false || strstr($string,'千')!==false) {
			return ExtractDate::kanjiToHalf($string);
		}
		$numeric_all = array('１','２','３','４','５','６','７','８','９','０');
		$numeric_kanji = array('一','二','三','四','五','六','七','八','九','〇');
		$numeric_half = array('1','2','3','4','5','6','7','8','9','0');
		$string = str_replace($numeric_all,$numeric_half,$string);
		$string = str_replace($numeric_kanji,$numeric_half,$string);
		return $string;
	}

	/**
	 * パターン
	 * 五、十、二十、十五、二十五、千九百、二千、千九百八十五
	 */
	function kanjiToHalf ($string) {
		$return = 0;
		/**
		 * 千、十、百の配列を用意
		 * まずパーツ分け
		 * 上2文字取得
		 * 1文字しかない場合
		 *  数字の場合とそれ以外で場合わけして文字決定
		 * 1文字目が数字の場合
		 * 	2文字分で文字決定（配列に入れる）
		 * 	2文字分削除
		 * 数字でない場合
		 * 	前に一を付けてもう一度やり直す
		 *
		 * ここで○千○百○十○または○千○十○など配列が得られる
		 * トークンごとに数値化して、足し算
		 */
		while($string) {
			$outerdigit = array('万','億','兆');
			$digit = array('十'=>10,'百'=>100,'千'=>1000);
			$last = mb_substr($string,0,2,"UTF-8");
			if(in_array(mb_substr($last,0,1,"UTF-8"),array_keys($digit))) {
				$token[] = $digit[mb_substr($last,0,1,"UTF-8")];
				$string = mb_substr($string,1,mb_strlen($string,"UTF-8"),"UTF-8");
			}else{
				if(empty($digit[mb_substr($last,1,mb_strlen($last,"UTF-8"),"UTF-8")])) {
					$token[] = ExtractDate::toHalf(mb_substr($last,0,1,"UTF-8"));
					$string = mb_substr($string,1,mb_strlen($string,"UTF-8"),"UTF-8");
				}else{
					$token[] = ExtractDate::toHalf(mb_substr($last,0,1,"UTF-8"))*$digit[mb_substr($last,1,mb_strlen($last,"UTF-8"),"UTF-8")];
					$string = mb_substr($string,2,mb_strlen($string,"UTF-8"),"UTF-8");
				}
			}
		}
		foreach($token as $var) {
			$return = $return + $var;
		}
		return $return;
	}
}
?>
