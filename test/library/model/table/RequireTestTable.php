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
class RequireTestTable extends TableObjectBase{
	/**  */
	var $id;
	/**  */
	var $name;
	/**  */
	var $zip;
	/**  */
	var $address;


	function RequireTestTable($id=null){
		$this->__init__($id);
	}
	function __init__($id=null){
		$this->id = null;
		$this->name = null;
		$this->zip = null;
		$this->address = null;
		$this->setId($id);
	}
	function connection(){
		if(!Rhaco::isVariable("_R_D_CON_","test")){
			Rhaco::addVariable("_R_D_CON_",new DbConnection("test"),"test");
		}
		return Rhaco::getVariable("_R_D_CON_",null,"test");
	}
	function table(){
		if(!Rhaco::isVariable("_R_D_T_","RequireTest")){
			Rhaco::addVariable("_R_D_T_",new Table(Rhaco::constant("DATABASE_test_PREFIX")."require_test",__CLASS__),"RequireTest");
		}
		return Rhaco::getVariable("_R_D_T_",null,"RequireTest");
	}


	/**
	 * 
	 * @return database.model.Column
	 */
	function columnId(){
		if(!Rhaco::isVariable("_R_D_C_","RequireTest::Id")){
			$column = new Column("column=id,variable=id,type=serial,size=22,primary=true,",__CLASS__);
			$column->label(Message::_("id"));
			Rhaco::addVariable("_R_D_C_",$column,"RequireTest::Id");
		}
		return Rhaco::getVariable("_R_D_C_",null,"RequireTest::Id");
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
		if(!Rhaco::isVariable("_R_D_C_","RequireTest::Name")){
			$column = new Column("column=name,variable=name,type=string,require=true,",__CLASS__);
			$column->label(Message::_("name"));
			Rhaco::addVariable("_R_D_C_",$column,"RequireTest::Name");
		}
		return Rhaco::getVariable("_R_D_C_",null,"RequireTest::Name");
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
	function columnZip(){
		if(!Rhaco::isVariable("_R_D_C_","RequireTest::Zip")){
			$column = new Column("column=zip,variable=zip,type=zip,size=8,",__CLASS__);
			$column->label(Message::_("zip"));
			Rhaco::addVariable("_R_D_C_",$column,"RequireTest::Zip");
		}
		return Rhaco::getVariable("_R_D_C_",null,"RequireTest::Zip");
	}
	/**
	 * 
	 * @return zip
	 */
	function setZip($value){
		$this->zip = TableObjectUtil::cast($value,"zip");
	}
	/**
	 * 
	 */
	function getZip(){
		return $this->zip;
	}
	/**
	 * 
	 * @return database.model.Column
	 */
	function columnAddress(){
		if(!Rhaco::isVariable("_R_D_C_","RequireTest::Address")){
			$column = new Column("column=address,variable=address,type=text,requireWith=RequireTest::Zip,",__CLASS__);
			$column->label(Message::_("address"));
			Rhaco::addVariable("_R_D_C_",$column,"RequireTest::Address");
		}
		return Rhaco::getVariable("_R_D_C_",null,"RequireTest::Address");
	}
	/**
	 * 
	 * @return text
	 */
	function setAddress($value){
		$this->address = TableObjectUtil::cast($value,"text");
	}
	/**
	 * 
	 */
	function getAddress(){
		return $this->address;
	}


}
?>