<?php
/**
 * 汎用Verifyユーティリティ
 * よく使うけど、実装がめんどい検証をお手軽に
 * 
 * @author Kentaro YABE
 * @license New BSD License
 * @copyright Copyright 2009- rhaco project. All rights reserved.
 */
class VerifyUtil{
	/**
	 * すべて入力されている
	 *
	 * @param TableObjectBase $table
	 * @param string/array $columns
	 * @return boolean
	 */
	function all(&$table,$columns){
		if(is_string($columns)){
			return VerifyUtil::all($table,explode(",",$columns));
		}
		foreach($columns as $column){
			$value = $table->value($column);
			if(is_null($value) || ($value === "") || ($value === array())){
				return false;
			}
		}
		return true;
	}
	
	/**
	 * すべて入力されていない
	 *
	 * @param TableObjectBase $table
	 * @param string/array $columns
	 * @return boolean
	 */
	function nothing(&$table,$columns){
		if(is_string($columns)){
			return VerifyUtil::nothing($table,explode(",",$columns));
		}
		foreach($columns as $column){
			$value = $table->value($column);
			if(is_null($value) || ($value === "") || ($value === array())){
				continue;
			}else{
				return false;
			}
		}
		return true;
	}
	
	/**
	 * すべて入力されているか、すべて入力されていない
	 *
	 * @param TableObjectBase $table
	 * @param string/array
	 * @return boolean
	 */
	function allOrNothing(&$table,$columns){
		if(is_string($columns)){
			return VerifyUtil::allOrNothing($table,explode(",",$columns));
		}
		$all = true;
		$nothing = true;
		foreach($columns as $column){
			$value = $table->value($column);
			$none = is_null($value) || ($value === "") || ($value === array());
			$all &= !$none;
			$nothing &= $none;
		}
		return $all || $nothing;
	}
	
	/**
	 * 少なくともどれか一つが入力されている
	 *
	 * @param TableObjectBase $table
	 * @param string/array $columns
	 * @return boolean
	 */
	function oneOf(&$table,$columns){
		return VerifyUtil::xOf($table,$columns,1);
	}
	
	/**
	 * 少なくともどれかX個が入力されている
	 *
	 * @param TableObjectBase $table
	 * @param integer $num
	 * @param string/array $columns
	 * @return boolean
	 */
	function xOf(&$table,$columns,$num){
		if(is_string($columns)){
			return VerifyUtil::xOf($table,explode(",",$columns));
		}
		$cnt = 0;
		foreach($columns as $column){
			$value = $table->value($column);
			if(is_null($value) || ($value === "") || ($value === array())){
				continue;
			}
			if(++$cnt >= $num){
				return true;
			}
		}
		return false;
	}
}
?>