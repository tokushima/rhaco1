<?php
Rhaco::import("resources.Message");
Rhaco::import("database.model.TableObjectBase");
Rhaco::import("database.model.DbConnection");
Rhaco::import("database.TableObjectUtil");
Rhaco::import("database.model.Table");
Rhaco::import("database.model.Column");
/**
 * #ignore
 * 
 */
class FactTestRefTable extends TableObjectBase{
	/**  */
	var $id;
	/**  */
	var $refName;
	var $dependFactTests;
	var $dependFactTestExts;


	function FactTestRefTable($id=null){
		$this->__init__($id);
	}
	function __init__($id=null){
		$this->id = null;
		$this->refName = null;
		$this->setId($id);
	}
	function connection(){
		if(!Rhaco::isVariable("_R_D_CON_","test")){
			Rhaco::addVariable("_R_D_CON_",new DbConnection("test"),"test");
		}
		return Rhaco::getVariable("_R_D_CON_",null,"test");
	}
	function table(){
		if(!Rhaco::isVariable("_R_D_T_","FactTestRef")){
			Rhaco::addVariable("_R_D_T_",new Table(Rhaco::constant("DATABASE_test_PREFIX")."fact_test_ref",__CLASS__),"FactTestRef");
		}
		return Rhaco::getVariable("_R_D_T_",null,"FactTestRef");
	}


	/**
	 * 
	 * @return database.model.Column
	 */
	function columnId(){
		if(!Rhaco::isVariable("_R_D_C_","FactTestRef::Id")){
			$column = new Column("column=id,variable=id,type=serial,size=22,primary=true,",__CLASS__);
			$column->label(Message::_("id"));
			$column->depend("FactTest::RefId","FactTestExt::RefId");
			Rhaco::addVariable("_R_D_C_",$column,"FactTestRef::Id");
		}
		return Rhaco::getVariable("_R_D_C_",null,"FactTestRef::Id");
	}
	/**
	 * 
	 * @return serial
	 */
	function setId($value){
		$this->id = TableObjectUtil::cast($value,"serial");
	}
	/**
	 * 
	 */
	function getId(){
		return $this->id;
	}
	/**
	 * 
	 * @return database.model.Column
	 */
	function columnRefName(){
		if(!Rhaco::isVariable("_R_D_C_","FactTestRef::RefName")){
			$column = new Column("column=name,variable=refName,type=string,",__CLASS__);
			$column->label(Message::_("name"));
			Rhaco::addVariable("_R_D_C_",$column,"FactTestRef::RefName");
		}
		return Rhaco::getVariable("_R_D_C_",null,"FactTestRef::RefName");
	}
	/**
	 * 
	 * @return string
	 */
	function setRefName($value){
		$this->refName = TableObjectUtil::cast($value,"string");
	}
	/**
	 * 
	 */
	function getRefName(){
		return $this->refName;
	}


	function setDependFactTests($value){
		$this->dependFactTests = $value;
	}
	function getDependFactTests(){
		return $this->dependFactTests;
	}
	function setDependFactTestExts($value){
		$this->dependFactTestExts = $value;
	}
	function getDependFactTestExts(){
		return $this->dependFactTestExts;
	}
}
?>