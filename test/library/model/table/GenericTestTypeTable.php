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
class GenericTestTypeTable extends TableObjectBase{
	/**  */
	var $id;
	/**  */
	var $name;
	/**  */
	var $description;
	/**  */
	var $sortOrder;
	var $dependGenericTests;


	function GenericTestTypeTable($id=null){
		$this->__init__($id);
	}
	function __init__($id=null){
		$this->id = null;
		$this->name = null;
		$this->description = null;
		$this->sortOrder = null;
		$this->setId($id);
	}
	function connection(){
		if(!Rhaco::isVariable("_R_D_CON_","test")){
			Rhaco::addVariable("_R_D_CON_",new DbConnection("test"),"test");
		}
		return Rhaco::getVariable("_R_D_CON_",null,"test");
	}
	function table(){
		if(!Rhaco::isVariable("_R_D_T_","GenericTestType")){
			Rhaco::addVariable("_R_D_T_",new Table(Rhaco::constant("DATABASE_test_PREFIX")."generic_test_type",__CLASS__),"GenericTestType");
		}
		return Rhaco::getVariable("_R_D_T_",null,"GenericTestType");
	}


	/**
	 * 
	 * @return database.model.Column
	 */
	function columnId(){
		if(!Rhaco::isVariable("_R_D_C_","GenericTestType::Id")){
			$column = new Column("column=id,variable=id,type=serial,size=22,primary=true,",__CLASS__);
			$column->label(Message::_("id"));
			$column->depend("GenericTest::GenericTestType");
			Rhaco::addVariable("_R_D_C_",$column,"GenericTestType::Id");
		}
		return Rhaco::getVariable("_R_D_C_",null,"GenericTestType::Id");
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
		if(!Rhaco::isVariable("_R_D_C_","GenericTestType::Name")){
			$column = new Column("column=name,variable=name,type=string,size=10,",__CLASS__);
			$column->label(Message::_("name"));
			Rhaco::addVariable("_R_D_C_",$column,"GenericTestType::Name");
		}
		return Rhaco::getVariable("_R_D_C_",null,"GenericTestType::Name");
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
	function columnDescription(){
		if(!Rhaco::isVariable("_R_D_C_","GenericTestType::Description")){
			$column = new Column("column=description,variable=description,type=text,size=1000,",__CLASS__);
			$column->label(Message::_("description"));
			Rhaco::addVariable("_R_D_C_",$column,"GenericTestType::Description");
		}
		return Rhaco::getVariable("_R_D_C_",null,"GenericTestType::Description");
	}
	/**
	 * 
	 * @return text
	 */
	function setDescription($value){
		$this->description = TableObjectUtil::cast($value,"text");
	}
	/**
	 * 
	 */
	function getDescription(){
		return $this->description;
	}
	/**
	 * 
	 * @return database.model.Column
	 */
	function columnSortOrder(){
		if(!Rhaco::isVariable("_R_D_C_","GenericTestType::SortOrder")){
			$column = new Column("column=sort_order,variable=sortOrder,type=integer,size=1,",__CLASS__);
			$column->label(Message::_("sort_order"));
			Rhaco::addVariable("_R_D_C_",$column,"GenericTestType::SortOrder");
		}
		return Rhaco::getVariable("_R_D_C_",null,"GenericTestType::SortOrder");
	}
	/**
	 * 
	 * @return integer
	 */
	function setSortOrder($value){
		$this->sortOrder = TableObjectUtil::cast($value,"integer");
	}
	/**
	 * 
	 */
	function getSortOrder(){
		return $this->sortOrder;
	}


	function setDependGenericTests($value){
		$this->dependGenericTests = $value;
	}
	function getDependGenericTests(){
		return $this->dependGenericTests;
	}
}
?>