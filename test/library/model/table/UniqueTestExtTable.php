<?php
Rhaco::import("resources.Message");
Rhaco::import("database.model.TableObjectBase");
Rhaco::import("database.model.DbConnection");
Rhaco::import("model.UniqueTest");
Rhaco::import("database.TableObjectUtil");
Rhaco::import("database.model.Table");
Rhaco::import("database.model.Column");
/**
 * #ignore
 * 
 */
class UniqueTestExtTable extends UniqueTest{
	var $dependUniqueTestMaps;
	var $uniqueTests;

	function table(){
		if(!Rhaco::isVariable("_R_D_T_","UniqueTestExt")){
			Rhaco::addVariable("_R_D_T_",new Table(parent::table(),__CLASS__),"UniqueTestExt");
		}
		return Rhaco::getVariable("_R_D_T_",null,"UniqueTestExt");
	}
	function columns(){
		return array(UniqueTestExt::columnId(),UniqueTestExt::columnName(),);
	}
	function columnId(){
		if(!Rhaco::isVariable("_R_D_C_","UniqueTestExt::Id")){
			Rhaco::addVariable("_R_D_C_",new Column(parent::columnId(),__CLASS__),"UniqueTestExt::Id");
		}
		return Rhaco::getVariable("_R_D_C_",null,"UniqueTestExt::Id");			
	}
	function columnName(){
		if(!Rhaco::isVariable("_R_D_C_","UniqueTestExt::Name")){
			Rhaco::addVariable("_R_D_C_",new Column(parent::columnName(),__CLASS__),"UniqueTestExt::Name");
		}
		return Rhaco::getVariable("_R_D_C_",null,"UniqueTestExt::Name");			
	}
	function setDependUniqueTestMaps($value){
		$this->dependUniqueTestMaps = $value;
	}
	function getDependUniqueTestMaps(){
		return $this->dependUniqueTestMaps;
	}
	function setUniqueTests($value){
		$this->uniqueTests = $value;
	}
	function getUniqueTests(){
		return $this->uniqueTests;
	}
}
?>