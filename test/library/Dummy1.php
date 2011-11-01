<?php
class Dummy1{
	var $id = 0;
	var $value = "";
	var $test = "TEST";
	
	function id(){
		/***
		 * $obj = new Dummy1();
		 * $obj->id = 20;
		 * eq("==>20",$obj->id());
		 */
		return "==>".$this->id;
	}
	function value(){
		/***
		 * $obj = new Dummy1();
		 * $obj->value = "hoge";
		 * eq("[hoge]",$obj->value());
		 */
		return "[".$this->value."]";
	}
	function getId(){
		return $this->id;
	}
	function setId($value){
		$this->id = $value * -1;
	}
	function getValue(){
		return $this->value."--";
	}
	function setValue($value){
		$this->value = $value;
	}
	function setTest($value){
		$this->test = $value;
	}
}
?>