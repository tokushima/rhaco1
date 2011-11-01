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
class UniqueTestTable extends TableObjectBase{
	/**  */
	var $id;
	/**  */
	var $name;
	var $dependUniqueTestMaps;
	var $uniqueTestExts;


	function UniqueTestTable($id=null){
		$this->__init__($id);
	}
	function __init__($id=null){
		$this->id = null;
		$this->name = null;
		$this->setId($id);
	}
	function connection(){
		if(!Rhaco::isVariable("_R_D_CON_","test")){
			Rhaco::addVariable("_R_D_CON_",new DbConnection("test"),"test");
		}
		return Rhaco::getVariable("_R_D_CON_",null,"test");
	}
	function table(){
		if(!Rhaco::isVariable("_R_D_T_","UniqueTest")){
			Rhaco::addVariable("_R_D_T_",new Table(Rhaco::constant("DATABASE_test_PREFIX")."unique_test",__CLASS__),"UniqueTest");
		}
		return Rhaco::getVariable("_R_D_T_",null,"UniqueTest");
	}


	/**
	 * 
	 * @return database.model.Column
	 */
	function columnId(){
		if(!Rhaco::isVariable("_R_D_C_","UniqueTest::Id")){
			$column = new Column("column=id,variable=id,type=serial,size=22,primary=true,",__CLASS__);
			$column->label(Message::_("id"));
			$column->depend("UniqueTestMap::User");
			Rhaco::addVariable("_R_D_C_",$column,"UniqueTest::Id");
		}
		return Rhaco::getVariable("_R_D_C_",null,"UniqueTest::Id");
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
		if(!Rhaco::isVariable("_R_D_C_","UniqueTest::Name")){
			$column = new Column("column=name,variable=name,type=string,size=20,",__CLASS__);
			$column->label(Message::_("name"));
			Rhaco::addVariable("_R_D_C_",$column,"UniqueTest::Name");
		}
		return Rhaco::getVariable("_R_D_C_",null,"UniqueTest::Name");
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


	function setDependUniqueTestMaps($value){
		$this->dependUniqueTestMaps = $value;
	}
	function getDependUniqueTestMaps(){
		return $this->dependUniqueTestMaps;
	}
	function setUniqueTestExts($value){
		$this->uniqueTestExts = $value;
	}
	function getUniqueTestExts(){
		return $this->uniqueTestExts;
	}
}
?>