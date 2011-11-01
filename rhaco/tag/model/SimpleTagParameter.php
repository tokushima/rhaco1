<?php
/**
 * SimpleTag で利用するパラメータクラス
 * 
 * @author Kazutaka Tokushima
 * @license New BSD License
 * @copyright Copyright 2005- rhaco project. All rights reserved.
 */
class SimpleTagParameter{
	var $id;
	var $value;
	var $name;

	/**
	 * コンストラクタ
	 * @param string $id
	 * @param string $value
	 */
	function SimpleTagParameter($id="",$value=""){
		$this->setId($id);
		$this->setValue($value);
	}
	/**
	 * IDを取得
	 * @return string
	 */
	function getId(){
		return $this->id;
	}
	/**
	 * IDを設定
	 * @param string $value
	 */
	function setId($value){
		$this->name	= $value;
		$this->id	= strtolower($value);
	}
	/**
	 * 名前を取得
	 * @return string
	 */
	function getName(){
		return $this->name;
	}
	/**
	 * 値を取得
	 * @return string
	 */
	function getValue(){
		return $this->value;
	}
	/**
	 * 値を設定
	 * @param string $value
	 */
	function setValue($value){
		if(is_bool($value)){
			$value = $value?"true":"false";
		}
		$this->value = $value;
	}
}
?>