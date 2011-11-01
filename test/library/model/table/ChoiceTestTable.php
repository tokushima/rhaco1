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
class ChoiceTestTable extends TableObjectBase{
	/**  */
	var $id;
	/**  */
	var $choiceType;
	/**  */
	var $choiceBoolType;
	var $extaChoiceBoolType;


	function ChoiceTestTable($id=null){
		$this->__init__($id);
	}
	function __init__($id=null){
		$this->id = null;
		$this->choiceType = null;
		$this->choiceBoolType = 0;
		$this->extaChoiceBoolType = 0;
		$this->setId($id);
	}
	function connection(){
		if(!Rhaco::isVariable("_R_D_CON_","test")){
			Rhaco::addVariable("_R_D_CON_",new DbConnection("test"),"test");
		}
		return Rhaco::getVariable("_R_D_CON_",null,"test");
	}
	function table(){
		if(!Rhaco::isVariable("_R_D_T_","ChoiceTest")){
			Rhaco::addVariable("_R_D_T_",new Table(Rhaco::constant("DATABASE_test_PREFIX")."choice_test",__CLASS__),"ChoiceTest");
		}
		return Rhaco::getVariable("_R_D_T_",null,"ChoiceTest");
	}


	/**
	 * 
	 * @return database.model.Column
	 */
	function columnId(){
		if(!Rhaco::isVariable("_R_D_C_","ChoiceTest::Id")){
			$column = new Column("column=id,variable=id,type=serial,size=22,primary=true,",__CLASS__);
			$column->label(Message::_("id"));
			Rhaco::addVariable("_R_D_C_",$column,"ChoiceTest::Id");
		}
		return Rhaco::getVariable("_R_D_C_",null,"ChoiceTest::Id");
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
	 * Choices
	 * 	1: タイプ1 
	 * 	2: タイプ2 
	 * 	3: タイプ3 
	 * 
	 * @return database.model.Column
	 */
	function columnChoiceType(){
		if(!Rhaco::isVariable("_R_D_C_","ChoiceTest::ChoiceType")){
			$column = new Column("column=choice_type,variable=choiceType,type=integer,size=22,",__CLASS__);
			$column->label(Message::_("choice_type"));
			$column->choices(array("1"=>Message::_("タイプ1"),"2"=>Message::_("タイプ2"),"3"=>Message::_("タイプ3"),));
			Rhaco::addVariable("_R_D_C_",$column,"ChoiceTest::ChoiceType");
		}
		return Rhaco::getVariable("_R_D_C_",null,"ChoiceTest::ChoiceType");
	}
	/**
	 * Choices
	 * 	1: タイプ1 
	 * 	2: タイプ2 
	 * 	3: タイプ3 
	 * 
	 * @return integer
	 */
	function setChoiceType($value){
		$this->choiceType = TableObjectUtil::cast($value,"integer");
	}
	/**
	 * Choices
	 * 	1: タイプ1 
	 * 	2: タイプ2 
	 * 	3: タイプ3 
	 * 
	 */
	function getChoiceType(){
		return $this->choiceType;
	}
	function captionChoiceType(){
		return TableObjectUtil::caption($this,ChoiceTest::columnChoiceType());
	}
	/**
	 * Choices
	 * 	true: 真 
	 * 	false: 偽 
	 * 
	 * @return database.model.Column
	 */
	function columnChoiceBoolType(){
		if(!Rhaco::isVariable("_R_D_C_","ChoiceTest::ChoiceBoolType")){
			$column = new Column("column=choice_bool_type,variable=choiceBoolType,type=boolean,",__CLASS__);
			$column->label(Message::_("choice_bool_type"));
			$column->choices(array("true"=>Message::_("真"),"false"=>Message::_("偽"),));
			Rhaco::addVariable("_R_D_C_",$column,"ChoiceTest::ChoiceBoolType");
		}
		return Rhaco::getVariable("_R_D_C_",null,"ChoiceTest::ChoiceBoolType");
	}
	/**
	 * Choices
	 * 	true: 真 
	 * 	false: 偽 
	 * 
	 * @return boolean
	 */
	function setChoiceBoolType($value){
		$this->choiceBoolType = TableObjectUtil::cast($value,"boolean");
	}
	/**
	 * Choices
	 * 	true: 真 
	 * 	false: 偽 
	 * 
	 */
	function getChoiceBoolType(){
		return $this->choiceBoolType;
	}
	function captionChoiceBoolType(){
		return TableObjectUtil::caption($this,ChoiceTest::columnChoiceBoolType());
	}
	/**  */
	function isChoiceBoolType(){
		return Variable::bool($this->choiceBoolType);
	}
	/**
	 * Choices
	 * 	true: 真 
	 * 	false: 偽 
	 * 
	 * @return database.model.Column
	 */
	function extraExtaChoiceBoolType(){
		if(!Rhaco::isVariable("_R_D_C_","ChoiceTest::ExtaChoiceBoolType")){
			$column = new Column("column=exta_choice_bool_type,variable=extaChoiceBoolType,type=boolean,",__CLASS__);
			$column->label(Message::_("exta_choice_bool_type"));
			$column->choices(array("true"=>Message::_("真"),"false"=>Message::_("偽"),));
			Rhaco::addVariable("_R_D_C_",$column,"ChoiceTest::ExtaChoiceBoolType");
		}
		return Rhaco::getVariable("_R_D_C_",null,"ChoiceTest::ExtaChoiceBoolType");
	}
	/**
	 * Choices
	 * 	true: 真 
	 * 	false: 偽 
	 * 
	 * @return boolean
	 */
	function setExtaChoiceBoolType($value){
		$this->extaChoiceBoolType = TableObjectUtil::cast($value,"boolean");
	}
	/**
	 * Choices
	 * 	true: 真 
	 * 	false: 偽 
	 * 
	 */
	function getExtaChoiceBoolType(){
		return $this->extaChoiceBoolType;
	}
	function captionExtaChoiceBoolType(){
		return TableObjectUtil::caption($this,ChoiceTest::extraExtaChoiceBoolType());
	}
	/**  */
	function isExtaChoiceBoolType(){
		return Variable::bool($this->extaChoiceBoolType);
	}


}
?>