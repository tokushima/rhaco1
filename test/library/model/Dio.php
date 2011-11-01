<?php
Rhaco::import("model.table.DioTable");
class Dio extends DioTable{

	function beforeInsert(&$db){
		/*** unit("database.DbUtilTest"); */
		$this->setStand("隠者の紫insert");
	}
	function beforeUpdate(&$db,$criteria=null){
		/*** unit("database.DbUtilTest"); */		
		$this->setStand("隠者の紫update");
	}
	function beforeDelete(&$db,$criteria=null){
		/*** unit("database.DbUtilTest"); */		
		$this->setStand("隠者の紫delete");
	}
	
}

?>