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
class DependTestRefferedTable extends TableObjectBase{
	/**  */
	var $id;
	/**  */
	var $name;
	/**  */
	var $refId;
	var $factRefId;


	function DependTestRefferedTable($id=null){
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
		if(!Rhaco::isVariable("_R_D_T_","DependTestReffered")){
			Rhaco::addVariable("_R_D_T_",new Table(Rhaco::constant("DATABASE_test_PREFIX")."depend_test_reffered",__CLASS__),"DependTestReffered");
		}
		return Rhaco::getVariable("_R_D_T_",null,"DependTestReffered");
	}


	/**
	 * 
	 * @return database.model.Column
	 */
	function columnId(){
		if(!Rhaco::isVariable("_R_D_C_","DependTestReffered::Id")){
			$column = new Column("column=id,variable=id,type=serial,size=22,primary=true,",__CLASS__);
			$column->label(Message::_("id"));
			Rhaco::addVariable("_R_D_C_",$column,"DependTestReffered::Id");
		}
		return Rhaco::getVariable("_R_D_C_",null,"DependTestReffered::Id");
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
		if(!Rhaco::isVariable("_R_D_C_","DependTestReffered::Name")){
			$column = new Column("column=name,variable=name,type=string,",__CLASS__);
			$column->label(Message::_("name"));
			Rhaco::addVariable("_R_D_C_",$column,"DependTestReffered::Name");
		}
		return Rhaco::getVariable("_R_D_C_",null,"DependTestReffered::Name");
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
		if(!Rhaco::isVariable("_R_D_C_","DependTestReffered::RefId")){
			$column = new Column("column=ref_id,variable=refId,type=integer,size=22,reference=DependTest::Id,",__CLASS__);
			$column->label(Message::_("ref_id"));
			Rhaco::addVariable("_R_D_C_",$column,"DependTestReffered::RefId");
		}
		return Rhaco::getVariable("_R_D_C_",null,"DependTestReffered::RefId");
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
}
?>