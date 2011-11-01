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
class GcFrogTable extends TableObjectBase{
	/**  */
	var $id;
	/**  */
	var $name;
	/**  */
	var $color;
	/**  */
	var $home;
	var $dependCFrogs;


	function GcFrogTable($id=null){
		$this->__init__($id);
	}
	function __init__($id=null){
		$this->id = null;
		$this->name = null;
		$this->color = null;
		$this->home = null;
		$this->setId($id);
	}
	function connection(){
		if(!Rhaco::isVariable("_R_D_CON_","test")){
			Rhaco::addVariable("_R_D_CON_",new DbConnection("test"),"test");
		}
		return Rhaco::getVariable("_R_D_CON_",null,"test");
	}
	function table(){
		if(!Rhaco::isVariable("_R_D_T_","GcFrog")){
			Rhaco::addVariable("_R_D_T_",new Table(Rhaco::constant("DATABASE_test_PREFIX")."gc_frog",__CLASS__),"GcFrog");
		}
		return Rhaco::getVariable("_R_D_T_",null,"GcFrog");
	}


	/**
	 * 
	 * @return database.model.Column
	 */
	function columnId(){
		if(!Rhaco::isVariable("_R_D_C_","GcFrog::Id")){
			$column = new Column("column=id,variable=id,type=serial,size=22,primary=true,",__CLASS__);
			$column->label(Message::_("id"));
			$column->depend("CFrog::GcFrogId");
			Rhaco::addVariable("_R_D_C_",$column,"GcFrog::Id");
		}
		return Rhaco::getVariable("_R_D_C_",null,"GcFrog::Id");
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
		if(!Rhaco::isVariable("_R_D_C_","GcFrog::Name")){
			$column = new Column("column=name,variable=name,type=string,require=true,unique=true,",__CLASS__);
			$column->label(Message::_("name"));
			Rhaco::addVariable("_R_D_C_",$column,"GcFrog::Name");
		}
		return Rhaco::getVariable("_R_D_C_",null,"GcFrog::Name");
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
	function columnColor(){
		if(!Rhaco::isVariable("_R_D_C_","GcFrog::Color")){
			$column = new Column("column=color,variable=color,type=string,require=true,",__CLASS__);
			$column->label(Message::_("color"));
			Rhaco::addVariable("_R_D_C_",$column,"GcFrog::Color");
		}
		return Rhaco::getVariable("_R_D_C_",null,"GcFrog::Color");
	}
	/**
	 * 
	 * @return string
	 */
	function setColor($value){
		$this->color = TableObjectUtil::cast($value,"string");
	}
	/**
	 * 
	 */
	function getColor(){
		return $this->color;
	}
	/**
	 * 
	 * @return database.model.Column
	 */
	function columnHome(){
		if(!Rhaco::isVariable("_R_D_C_","GcFrog::Home")){
			$column = new Column("column=home,variable=home,type=string,",__CLASS__);
			$column->label(Message::_("home"));
			Rhaco::addVariable("_R_D_C_",$column,"GcFrog::Home");
		}
		return Rhaco::getVariable("_R_D_C_",null,"GcFrog::Home");
	}
	/**
	 * 
	 * @return string
	 */
	function setHome($value){
		$this->home = TableObjectUtil::cast($value,"string");
	}
	/**
	 * 
	 */
	function getHome(){
		return $this->home;
	}


	function setDependCFrogs($value){
		$this->dependCFrogs = $value;
	}
	function getDependCFrogs(){
		return $this->dependCFrogs;
	}
}
?>