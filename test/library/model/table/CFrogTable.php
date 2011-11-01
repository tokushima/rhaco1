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
class CFrogTable extends TableObjectBase{
	/**  */
	var $id;
	/**  */
	var $name;
	/**  */
	var $color;
	/**  */
	var $home;
	/**  */
	var $gcFrogId;
	var $dependFrogs;
	var $factGcFrogId;


	function CFrogTable($id=null){
		$this->__init__($id);
	}
	function __init__($id=null){
		$this->id = null;
		$this->name = null;
		$this->color = null;
		$this->home = null;
		$this->gcFrogId = null;
		$this->setId($id);
	}
	function connection(){
		if(!Rhaco::isVariable("_R_D_CON_","test")){
			Rhaco::addVariable("_R_D_CON_",new DbConnection("test"),"test");
		}
		return Rhaco::getVariable("_R_D_CON_",null,"test");
	}
	function table(){
		if(!Rhaco::isVariable("_R_D_T_","CFrog")){
			Rhaco::addVariable("_R_D_T_",new Table(Rhaco::constant("DATABASE_test_PREFIX")."c_frog",__CLASS__),"CFrog");
		}
		return Rhaco::getVariable("_R_D_T_",null,"CFrog");
	}


	/**
	 * 
	 * @return database.model.Column
	 */
	function columnId(){
		if(!Rhaco::isVariable("_R_D_C_","CFrog::Id")){
			$column = new Column("column=id,variable=id,type=serial,size=22,primary=true,",__CLASS__);
			$column->label(Message::_("id"));
			$column->depend("Frog::CFrogId");
			Rhaco::addVariable("_R_D_C_",$column,"CFrog::Id");
		}
		return Rhaco::getVariable("_R_D_C_",null,"CFrog::Id");
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
		if(!Rhaco::isVariable("_R_D_C_","CFrog::Name")){
			$column = new Column("column=name,variable=name,type=string,require=true,unique=true,",__CLASS__);
			$column->label(Message::_("name"));
			Rhaco::addVariable("_R_D_C_",$column,"CFrog::Name");
		}
		return Rhaco::getVariable("_R_D_C_",null,"CFrog::Name");
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
		if(!Rhaco::isVariable("_R_D_C_","CFrog::Color")){
			$column = new Column("column=color,variable=color,type=string,require=true,",__CLASS__);
			$column->label(Message::_("color"));
			Rhaco::addVariable("_R_D_C_",$column,"CFrog::Color");
		}
		return Rhaco::getVariable("_R_D_C_",null,"CFrog::Color");
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
		if(!Rhaco::isVariable("_R_D_C_","CFrog::Home")){
			$column = new Column("column=home,variable=home,type=string,",__CLASS__);
			$column->label(Message::_("home"));
			Rhaco::addVariable("_R_D_C_",$column,"CFrog::Home");
		}
		return Rhaco::getVariable("_R_D_C_",null,"CFrog::Home");
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
	/**
	 * 
	 * @return database.model.Column
	 */
	function columnGcFrogId(){
		if(!Rhaco::isVariable("_R_D_C_","CFrog::GcFrogId")){
			$column = new Column("column=gc_frog_id,variable=gcFrogId,type=integer,size=22,require=true,reference=GcFrog::Id,",__CLASS__);
			$column->label(Message::_("gc_frog_id"));
			Rhaco::addVariable("_R_D_C_",$column,"CFrog::GcFrogId");
		}
		return Rhaco::getVariable("_R_D_C_",null,"CFrog::GcFrogId");
	}
	/**
	 * 
	 * @return integer
	 */
	function setGcFrogId($value){
		$this->gcFrogId = TableObjectUtil::cast($value,"integer");
	}
	/**
	 * 
	 */
	function getGcFrogId(){
		return $this->gcFrogId;
	}


	function getFactGcFrogId(){
		return $this->factGcFrogId;
	}
	function setFactGcFrogId($obj){
		$this->factGcFrogId = $obj;
	}
	function setDependFrogs($value){
		$this->dependFrogs = $value;
	}
	function getDependFrogs(){
		return $this->dependFrogs;
	}
}
?>