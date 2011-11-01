<?php
Rhaco::import("model.table.FactTestExtTable");
class FactTestExt extends FactTestExtTable{
	function toString(){
		/***
		 * $obj = new FactTestExt();
		 * $obj->setName("hoge");
		 * eq("hoge",$obj->toString());
		 */
		return $this->getName();
	}
}

?>