<?php
Rhaco::import("model.table.JojoTable");
class Jojo extends JojoTable{
	
	function afterInsert(&$db){
		/*** unit("database.DbUtilTest"); */
		$this->setStand("スタープラチナ・ザ・ワールドinsert");
	}
	function afterGet(&$db){
		/*** unit("database.DbUtilTest"); */		
		$this->setName("承り太郎");
	}
	function afterUpdate(&$db,$criteria=null){
		/*** unit("database.DbUtilTest"); */		
		$this->setStand("スタープラチナ・ザ・ワールドupdate");
	}
	function afterDelete(&$db,$criteria=null){
		/*** unit("database.DbUtilTest"); */		
		$this->setStand("スタープラチナ・ザ・ワールドdelete");
	}
}

?>