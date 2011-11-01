<?php
class Exe1{
	function get(){
		/***
		 * $obj = new Exe1();
		 * eq("hogehoge",$obj->get());
		 */
		return "hogehoge";
	}
	function tora($value){
		/***
		 * $obj = new Exe1();
		 * eq("hogetora",$obj->tora("hoge"));
		 */
		return $value."tora";
	}
	function neko($value){
		/***
		 * $obj = new Exe1();
		 * eq("hogeneko",$obj->neko("hoge"));
		 */
		return $value."neko";
	}
}
?>