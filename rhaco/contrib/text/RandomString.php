<?php
/**
 * ランダムな文字列を生成するクラス
 * 
 * @author Kazutaka Tokushima
 * @license New BSD License
 * @copyright Copyright 2007 rhaco project. All rights reserved.
 */
class RandomString{
	/**
	 * ランダム文字列を取得(英数字)
	 *
	 * @param int $digit
	 * @return string
	 */
	function ascii($digit){
		$str	= "";
		$list	= array(58,59,60,61,62,63,64,90,91,92,93,94,95,96);

		for($i=0,$rand=48;$i<$digit;$i++){
			while(true){
				$rand = rand(48,122);
				if(!in_array($rand,$list)) break;
			}
			$str .= chr($rand);
		}
		return $str;
	}
	/**
	 * ランダム文字列を取得(英字)
	 *
	 * @param int $digit
	 * @return string
	 */
	function alphabet($digit){
		$str	= "";
		$list	= array(90,91,92,93,94,95,96);

		for($i=0,$rand=65;$i<$digit;$i++){
			while(true){
				$rand = rand(65,122);
				if(!in_array($rand,$list)) break;
			}
			$str .= chr($rand);
		}
		return $str;
	}
	/**
	 * ランダム文字列を取得(数字)
	 *
	 * @param int $digit
	 * @return string
	 */
	function number($digit){
		$str	= "";
		for($i=0;$i<$digit;$i++){
			$str .= rand(0,9);
		}
		return $str;
	}	
}
?>