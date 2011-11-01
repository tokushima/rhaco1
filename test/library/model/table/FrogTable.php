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
class FrogTable extends TableObjectBase{
	/**  */
	var $id;
	/**  */
	var $name;
	/**  */
	var $color;
	/**  */
	var $home;
	/**  */
	var $cFrogId;
	var $factCFrogId;


	function FrogTable($id=null){
		$this->__init__($id);
	}
	function __init__($id=null){
		$this->id = null;
		$this->name = null;
		$this->color = null;
		$this->home = null;
		$this->cFrogId = null;
		$this->setId($id);
	}
	function connection(){
		if(!Rhaco::isVariable("_R_D_CON_","test")){
			Rhaco::addVariable("_R_D_CON_",new DbConnection("test"),"test");
		}
		return Rhaco::getVariable("_R_D_CON_",null,"test");
	}
	function table(){
		if(!Rhaco::isVariable("_R_D_T_","Frog")){
			Rhaco::addVariable("_R_D_T_",new Table(Rhaco::constant("DATABASE_test_PREFIX")."frog",__CLASS__),"Frog");
		}
		return Rhaco::getVariable("_R_D_T_",null,"Frog");
	}


	/**
	 * 
	 * @return database.model.Column
	 */
	function columnId(){
		if(!Rhaco::isVariable("_R_D_C_","Frog::Id")){
			$column = new Column("column=id,variable=id,type=serial,size=22,primary=true,",__CLASS__);
			$column->label(Message::_("id"));
			Rhaco::addVariable("_R_D_C_",$column,"Frog::Id");
		}
		return Rhaco::getVariable("_R_D_C_",null,"Frog::Id");
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
		if(!Rhaco::isVariable("_R_D_C_","Frog::Name")){
			$column = new Column("column=name,variable=name,type=string,require=true,unique=true,",__CLASS__);
			$column->label(Message::_("name"));
			Rhaco::addVariable("_R_D_C_",$column,"Frog::Name");
		}
		return Rhaco::getVariable("_R_D_C_",null,"Frog::Name");
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
		if(!Rhaco::isVariable("_R_D_C_","Frog::Color")){
			$column = new Column("column=color,variable=color,type=string,require=true,",__CLASS__);
			$column->label(Message::_("color"));
			Rhaco::addVariable("_R_D_C_",$column,"Frog::Color");
		}
		return Rhaco::getVariable("_R_D_C_",null,"Frog::Color");
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
		if(!Rhaco::isVariable("_R_D_C_","Frog::Home")){
			$column = new Column("column=home,variable=home,type=string,",__CLASS__);
			$column->label(Message::_("home"));
			Rhaco::addVariable("_R_D_C_",$column,"Frog::Home");
		}
		return Rhaco::getVariable("_R_D_C_",null,"Frog::Home");
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
	function columnCFrogId(){
		if(!Rhaco::isVariable("_R_D_C_","Frog::CFrogId")){
			$column = new Column("column=c_frog_id,variable=cFrogId,type=integer,size=22,require=true,reference=CFrog::Id,",__CLASS__);
			$column->label(Message::_("c_frog_id"));
			Rhaco::addVariable("_R_D_C_",$column,"Frog::CFrogId");
		}
		return Rhaco::getVariable("_R_D_C_",null,"Frog::CFrogId");
	}
	/**
	 * 
	 * @return integer
	 */
	function setCFrogId($value){
		$this->cFrogId = TableObjectUtil::cast($value,"integer");
	}
	/**
	 * 
	 */
	function getCFrogId(){
		return $this->cFrogId;
	}


	function getFactCFrogId(){
		return $this->factCFrogId;
	}
	function setFactCFrogId($obj){
		$this->factCFrogId = $obj;
	}
}
?>