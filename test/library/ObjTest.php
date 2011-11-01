<?php
class ObjTest{
	var $id;
	
	function ObjTest($id=0){
		$this->id = $id;
	}
	function getId(){
		return $this->id * -1;
	}
	
	function to($arg1,$arg2){
		/***
		 * $obj = new ObjTest();
		 * eq("hoge+",$obj->to("abc","hoge"));
		 */
		return $arg2."+";
	}
}
?>