<?php
Rhaco::import("resources.Message");
Rhaco::import("database.model.TableObjectBase");
Rhaco::import("database.model.DbConnection");
Rhaco::import("model.FactTest");
Rhaco::import("database.TableObjectUtil");
Rhaco::import("database.model.Table");
Rhaco::import("database.model.Column");
/**
 * #ignore
 * 
 */
class FactTestExtTable extends FactTest{
	var $dependMapTests;
	var $factRefId;
	var $factTests;

	function table(){
		if(!Rhaco::isVariable("_R_D_T_","FactTestExt")){
			Rhaco::addVariable("_R_D_T_",new Table(parent::table(),__CLASS__),"FactTestExt");
		}
		return Rhaco::getVariable("_R_D_T_",null,"FactTestExt");
	}
	function columns(){
		return array(FactTestExt::columnId(),FactTestExt::columnName(),FactTestExt::columnRefId(),);
	}
	function columnId(){
		if(!Rhaco::isVariable("_R_D_C_","FactTestExt::Id")){
			Rhaco::addVariable("_R_D_C_",new Column(parent::columnId(),__CLASS__),"FactTestExt::Id");
		}
		return Rhaco::getVariable("_R_D_C_",null,"FactTestExt::Id");			
	}
	function columnName(){
		if(!Rhaco::isVariable("_R_D_C_","FactTestExt::Name")){
			Rhaco::addVariable("_R_D_C_",new Column(parent::columnName(),__CLASS__),"FactTestExt::Name");
		}
		return Rhaco::getVariable("_R_D_C_",null,"FactTestExt::Name");			
	}
	function columnRefId(){
		if(!Rhaco::isVariable("_R_D_C_","FactTestExt::RefId")){
			Rhaco::addVariable("_R_D_C_",new Column(parent::columnRefId(),__CLASS__),"FactTestExt::RefId");
		}
		return Rhaco::getVariable("_R_D_C_",null,"FactTestExt::RefId");			
	}
	function setDependMapTests($value){
		$this->dependMapTests = $value;
	}
	function getDependMapTests(){
		return $this->dependMapTests;
	}
	function setFactTests($value){
		$this->factTests = $value;
	}
	function getFactTests(){
		return $this->factTests;
	}
}
?>