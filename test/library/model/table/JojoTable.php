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
class JojoTable extends TableObjectBase{
	/**  */
	var $id;
	/**  */
	var $name;
	/**  */
	var $stand;


	function JojoTable($id=null){
		$this->__init__($id);
	}
	function __init__($id=null){
		$this->id = null;
		$this->name = null;
		$this->stand = null;
		$this->setId($id);
	}
	function connection(){
		if(!Rhaco::isVariable("_R_D_CON_","test")){
			Rhaco::addVariable("_R_D_CON_",new DbConnection("test"),"test");
		}
		return Rhaco::getVariable("_R_D_CON_",null,"test");
	}
	function table(){
		if(!Rhaco::isVariable("_R_D_T_","Jojo")){
			Rhaco::addVariable("_R_D_T_",new Table(Rhaco::constant("DATABASE_test_PREFIX")."jojo",__CLASS__),"Jojo");
		}
		return Rhaco::getVariable("_R_D_T_",null,"Jojo");
	}


	/**
	 * 
	 * @return database.model.Column
	 */
	function columnId(){
		if(!Rhaco::isVariable("_R_D_C_","Jojo::Id")){
			$column = new Column("column=id,variable=id,type=serial,size=22,primary=true,",__CLASS__);
			$column->label(Message::_("id"));
			Rhaco::addVariable("_R_D_C_",$column,"Jojo::Id");
		}
		return Rhaco::getVariable("_R_D_C_",null,"Jojo::Id");
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
		if(!Rhaco::isVariable("_R_D_C_","Jojo::Name")){
			$column = new Column("column=name,variable=name,type=string,require=true,unique=true,",__CLASS__);
			$column->label(Message::_("name"));
			Rhaco::addVariable("_R_D_C_",$column,"Jojo::Name");
		}
		return Rhaco::getVariable("_R_D_C_",null,"Jojo::Name");
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
	function columnStand(){
		if(!Rhaco::isVariable("_R_D_C_","Jojo::Stand")){
			$column = new Column("column=stand,variable=stand,type=string,",__CLASS__);
			$column->label(Message::_("stand"));
			Rhaco::addVariable("_R_D_C_",$column,"Jojo::Stand");
		}
		return Rhaco::getVariable("_R_D_C_",null,"Jojo::Stand");
	}
	/**
	 * 
	 * @return string
	 */
	function setStand($value){
		$this->stand = TableObjectUtil::cast($value,"string");
	}
	/**
	 * 
	 */
	function getStand(){
		return $this->stand;
	}


}
?>