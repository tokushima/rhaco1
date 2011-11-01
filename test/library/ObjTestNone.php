<?php
class ObjTestNone{
	var $id;
	
	function ObjTestNone($id=0){
		$this->id = $id;
	}
	function getId(){
		return $this->id * -1;
	}
	
	function toto(){
		/***
		 * $obj = new ObjTestNone();
		 * eq(null,$obj->toto());
		 */
	}
}
?>