<?php
Rhaco::import("resources.Message");
Rhaco::import("database.model.TableObjectBase");
Rhaco::import("database.model.DbConnection");
Rhaco::import("model.DependTest");
Rhaco::import("database.TableObjectUtil");
Rhaco::import("database.model.Table");
Rhaco::import("database.model.Column");
/**
 * #ignore
 * 
 */
class DependTestExtTable extends DependTest{

	function table(){
		if(!Rhaco::isVariable("_R_D_T_","DependTestExt")){
			Rhaco::addVariable("_R_D_T_",new Table(parent::table(),__CLASS__),"DependTestExt");
		}
		return Rhaco::getVariable("_R_D_T_",null,"DependTestExt");
	}
	function columns(){
		return array(DependTestExt::columnId(),DependTestExt::columnName(),);
	}
	function columnId(){
		if(!Rhaco::isVariable("_R_D_C_","DependTestExt::Id")){
			Rhaco::addVariable("_R_D_C_",new Column(parent::columnId(),__CLASS__),"DependTestExt::Id");
		}
		return Rhaco::getVariable("_R_D_C_",null,"DependTestExt::Id");			
	}
	function columnName(){
		if(!Rhaco::isVariable("_R_D_C_","DependTestExt::Name")){
			Rhaco::addVariable("_R_D_C_",new Column(parent::columnName(),__CLASS__),"DependTestExt::Name");
		}
		return Rhaco::getVariable("_R_D_C_",null,"DependTestExt::Name");			
	}
}
?>