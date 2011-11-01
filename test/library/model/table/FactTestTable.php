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
class FactTestTable extends TableObjectBase{
	/**  */
	var $id;
	/**  */
	var $name;
	/**  */
	var $refId;
	var $dependMapTests;
	var $factRefId;
	var $factTestExts;


	function FactTestTable($id=null){
		$this->__init__($id);
	}
	function __init__($id=null){
		$this->id = null;
		$this->name = null;
		$this->refId = null;
		$this->setId($id);
	}
	function connection(){
		if(!Rhaco::isVariable("_R_D_CON_","test")){
			Rhaco::addVariable("_R_D_CON_",new DbConnection("test"),"test");
		}
		return Rhaco::getVariable("_R_D_CON_",null,"test");
	}
	function table(){
		if(!Rhaco::isVariable("_R_D_T_","FactTest")){
			Rhaco::addVariable("_R_D_T_",new Table(Rhaco::constant("DATABASE_test_PREFIX")."fact_test",__CLASS__),"FactTest");
		}
		return Rhaco::getVariable("_R_D_T_",null,"FactTest");
	}


	/**
	 * 
	 * @return database.model.Column
	 */
	function columnId(){
		if(!Rhaco::isVariable("_R_D_C_","FactTest::Id")){
			$column = new Column("column=id,variable=id,type=serial,size=22,primary=true,",__CLASS__);
			$column->label(Message::_("id"));
			$column->depend("MapTest::FactTestId");
			Rhaco::addVariable("_R_D_C_",$column,"FactTest::Id");
		}
		return Rhaco::getVariable("_R_D_C_",null,"FactTest::Id");
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
	function columnName(){
		if(!Rhaco::isVariable("_R_D_C_","FactTest::Name")){
			$column = new Column("column=name,variable=name,type=string,",__CLASS__);
			$column->label(Message::_("name"));
			Rhaco::addVariable("_R_D_C_",$column,"FactTest::Name");
		}
		return Rhaco::getVariable("_R_D_C_",null,"FactTest::Name");
	}
	/**
	 * 
	 * @return string
	 */
	function setName($value){
		$this->name = TableObjectUtil::cast($value,"string");
	}
	/**
	 * 
	 */
	function getName(){
		return $this->name;
	}
	/**
	 * 
	 * @return database.model.Column
	 */
	function columnRefId(){
		if(!Rhaco::isVariable("_R_D_C_","FactTest::RefId")){
			$column = new Column("column=ref_id,variable=refId,type=integer,size=22,reference=FactTestRef::Id,",__CLASS__);
			$column->label(Message::_("ref_id"));
			Rhaco::addVariable("_R_D_C_",$column,"FactTest::RefId");
		}
		return Rhaco::getVariable("_R_D_C_",null,"FactTest::RefId");
	}
	/**
	 * 
	 * @return integer
	 */
	function setRefId($value){
		$this->refId = TableObjectUtil::cast($value,"integer");
	}
	/**
	 * 
	 */
	function getRefId(){
		return $this->refId;
	}


	function getFactRefId(){
		return $this->factRefId;
	}
	function setFactRefId($obj){
		$this->factRefId = $obj;
	}
	function setDependMapTests($value){
		$this->dependMapTests = $value;
	}
	function getDependMapTests(){
		return $this->dependMapTests;
	}
	function setFactTestExts($value){
		$this->factTestExts = $value;
	}
	function getFactTestExts(){
		return $this->factTestExts;
	}
}
?>