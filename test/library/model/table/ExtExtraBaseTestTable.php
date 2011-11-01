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
class ExtExtraBaseTestTable extends TableObjectBase{
	/**  */
	var $id;
	/**  */
	var $value1;
	var $extraValue;


	function ExtExtraBaseTestTable($id=null){
		$this->__init__($id);
	}
	function __init__($id=null){
		$this->id = null;
		$this->value1 = null;
		$this->extraValue = "extra_value";
		$this->setId($id);
	}
	function connection(){
		if(!Rhaco::isVariable("_R_D_CON_","test")){
			Rhaco::addVariable("_R_D_CON_",new DbConnection("test"),"test");
		}
		return Rhaco::getVariable("_R_D_CON_",null,"test");
	}
	function table(){
		if(!Rhaco::isVariable("_R_D_T_","ExtExtraBaseTest")){
			Rhaco::addVariable("_R_D_T_",new Table(Rhaco::constant("DATABASE_test_PREFIX")."ext_extra_base_test",__CLASS__),"ExtExtraBaseTest");
		}
		return Rhaco::getVariable("_R_D_T_",null,"ExtExtraBaseTest");
	}


	/**
	 * 
	 * @return database.model.Column
	 */
	function columnId(){
		if(!Rhaco::isVariable("_R_D_C_","ExtExtraBaseTest::Id")){
			$column = new Column("column=id,variable=id,type=serial,size=22,primary=true,",__CLASS__);
			$column->label(Message::_("id"));
			Rhaco::addVariable("_R_D_C_",$column,"ExtExtraBaseTest::Id");
		}
		return Rhaco::getVariable("_R_D_C_",null,"ExtExtraBaseTest::Id");
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
	function columnValue1(){
		if(!Rhaco::isVariable("_R_D_C_","ExtExtraBaseTest::Value1")){
			$column = new Column("column=value1,variable=value1,type=string,",__CLASS__);
			$column->label(Message::_("ばりゅー"));
			Rhaco::addVariable("_R_D_C_",$column,"ExtExtraBaseTest::Value1");
		}
		return Rhaco::getVariable("_R_D_C_",null,"ExtExtraBaseTest::Value1");
	}
	/**
	 * 
	 * @return string
	 */
	function setValue1($value){
		$this->value1 = TableObjectUtil::cast($value,"string");
	}
	/**
	 * 
	 */
	function getValue1(){
		return $this->value1;
	}
	/**
	 * 
	 * @return database.model.Column
	 */
	function extraExtraValue(){
		if(!Rhaco::isVariable("_R_D_C_","ExtExtraBaseTest::ExtraValue")){
			$column = new Column("column=extra_value,variable=extraValue,type=string,",__CLASS__);
			$column->label(Message::_("えきすとら"));
			Rhaco::addVariable("_R_D_C_",$column,"ExtExtraBaseTest::ExtraValue");
		}
		return Rhaco::getVariable("_R_D_C_",null,"ExtExtraBaseTest::ExtraValue");
	}
	/**
	 * 
	 * @return string
	 */
	function setExtraValue($value){
		$this->extraValue = TableObjectUtil::cast($value,"string");
	}
	/**
	 * 
	 */
	function getExtraValue(){
		return $this->extraValue;
	}


}
?>