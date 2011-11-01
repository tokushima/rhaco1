<?php
class Dummy2{
	var $id = 0;
	var $type = "";
	
	function id(){
		/***
		 * $obj = new Dummy2();
		 * $obj->id = 20;
		 * eq(">>>>20",$obj->id());
		 */
		return ">>>>".$this->id;
	}
	function type(){
		/***
		 * $obj = new Dummy2();
		 * $obj->type = "hoge";
		 * eq("<hoge>",$obj->type());
		 */
		return "<".$this->type.">";
	}
	function setId($value){
		$this->id = $value + 100;
	}
	function getId(){
		return $this->id - 100;
	}
}
?>