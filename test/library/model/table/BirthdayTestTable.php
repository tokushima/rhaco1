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
class BirthdayTestTable extends TableObjectBase{
	/**  */
	var $id;
	/**  */
	var $name;
	/**  */
	var $birthday;


	function BirthdayTestTable($id=null){
		$this->__init__($id);
	}
	function __init__($id=null){
		$this->id = null;
		$this->name = null;
		$this->birthday = null;
		$this->setId($id);
	}
	function connection(){
		if(!Rhaco::isVariable("_R_D_CON_","test")){
			Rhaco::addVariable("_R_D_CON_",new DbConnection("test"),"test");
		}
		return Rhaco::getVariable("_R_D_CON_",null,"test");
	}
	function table(){
		if(!Rhaco::isVariable("_R_D_T_","BirthdayTest")){
			Rhaco::addVariable("_R_D_T_",new Table(Rhaco::constant("DATABASE_test_PREFIX")."birthday_test",__CLASS__),"BirthdayTest");
		}
		return Rhaco::getVariable("_R_D_T_",null,"BirthdayTest");
	}


	/**
	 * 
	 * @return database.model.Column
	 */
	function columnId(){
		if(!Rhaco::isVariable("_R_D_C_","BirthdayTest::Id")){
			$column = new Column("column=id,variable=id,type=serial,size=22,primary=true,",__CLASS__);
			$column->label(Message::_("id"));
			Rhaco::addVariable("_R_D_C_",$column,"BirthdayTest::Id");
		}
		return Rhaco::getVariable("_R_D_C_",null,"BirthdayTest::Id");
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
		if(!Rhaco::isVariable("_R_D_C_","BirthdayTest::Name")){
			$column = new Column("column=name,variable=name,type=string,",__CLASS__);
			$column->label(Message::_("name"));
			Rhaco::addVariable("_R_D_C_",$column,"BirthdayTest::Name");
		}
		return Rhaco::getVariable("_R_D_C_",null,"BirthdayTest::Name");
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
	function columnBirthday(){
		if(!Rhaco::isVariable("_R_D_C_","BirthdayTest::Birthday")){
			$column = new Column("column=birthday,variable=birthday,type=birthday,size=8,",__CLASS__);
			$column->label(Message::_("birthday"));
			Rhaco::addVariable("_R_D_C_",$column,"BirthdayTest::Birthday");
		}
		return Rhaco::getVariable("_R_D_C_",null,"BirthdayTest::Birthday");
	}
	/**
	 * 
	 * @return birthday
	 */
	function setBirthday($value){
		$this->birthday = TableObjectUtil::cast($value,"birthday");
	}
	/**
	 * 
	 */
	function getBirthday(){
		return $this->birthday;
	}
	/**  */
	function formatBirthday(){
		return DateUtil::formatDate($this->birthday);
	}


}
?>