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
class ItemTable extends TableObjectBase{
	/**  */
	var $id;
	/**  */
	var $name;
	/**  */
	var $price;


	function ItemTable($id=null){
		$this->__init__($id);
	}
	function __init__($id=null){
		$this->id = null;
		$this->name = null;
		$this->price = 0.000000;
		$this->setId($id);
	}
	function connection(){
		if(!Rhaco::isVariable("_R_D_CON_","test")){
			Rhaco::addVariable("_R_D_CON_",new DbConnection("test"),"test");
		}
		return Rhaco::getVariable("_R_D_CON_",null,"test");
	}
	function table(){
		if(!Rhaco::isVariable("_R_D_T_","Item")){
			Rhaco::addVariable("_R_D_T_",new Table(Rhaco::constant("DATABASE_test_PREFIX")."item",__CLASS__),"Item");
		}
		return Rhaco::getVariable("_R_D_T_",null,"Item");
	}


	/**
	 * 
	 * @return database.model.Column
	 */
	function columnId(){
		if(!Rhaco::isVariable("_R_D_C_","Item::Id")){
			$column = new Column("column=id,variable=id,type=serial,size=22,primary=true,",__CLASS__);
			$column->label(Message::_("id"));
			Rhaco::addVariable("_R_D_C_",$column,"Item::Id");
		}
		return Rhaco::getVariable("_R_D_C_",null,"Item::Id");
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
		if(!Rhaco::isVariable("_R_D_C_","Item::Name")){
			$column = new Column("column=name,variable=name,type=string,require=true,unique=true,",__CLASS__);
			$column->label(Message::_("name"));
			Rhaco::addVariable("_R_D_C_",$column,"Item::Name");
		}
		return Rhaco::getVariable("_R_D_C_",null,"Item::Name");
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
	function columnPrice(){
		if(!Rhaco::isVariable("_R_D_C_","Item::Price")){
			$column = new Column("column=price,variable=price,type=float,",__CLASS__);
			$column->label(Message::_("price"));
			Rhaco::addVariable("_R_D_C_",$column,"Item::Price");
		}
		return Rhaco::getVariable("_R_D_C_",null,"Item::Price");
	}
	/**
	 * 
	 * @return float
	 */
	function setPrice($value){
		$this->price = TableObjectUtil::cast($value,"float");
	}
	/**
	 * 
	 */
	function getPrice(){
		return $this->price;
	}


}
?>