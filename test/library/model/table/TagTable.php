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
class TagTable extends TableObjectBase{
	/**  */
	var $id;
	/** タグ名 */
	var $name;
	var $dependArticleTags;


	function TagTable($id=null){
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
		if(!Rhaco::isVariable("_R_D_T_","Tag")){
			Rhaco::addVariable("_R_D_T_",new Table(Rhaco::constant("DATABASE_test_PREFIX")."tag",__CLASS__),"Tag");
		}
		return Rhaco::getVariable("_R_D_T_",null,"Tag");
	}


	/**
	 * 
	 * @return database.model.Column
	 */
	function columnId(){
		if(!Rhaco::isVariable("_R_D_C_","Tag::Id")){
			$column = new Column("column=id,variable=id,type=serial,size=22,primary=true,",__CLASS__);
			$column->label(Message::_("id"));
			$column->depend("ArticleTag::TagId");
			Rhaco::addVariable("_R_D_C_",$column,"Tag::Id");
		}
		return Rhaco::getVariable("_R_D_C_",null,"Tag::Id");
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
	 * タグ名
	 * 
	 * @return database.model.Column
	 */
	function columnName(){
		if(!Rhaco::isVariable("_R_D_C_","Tag::Name")){
			$column = new Column("column=name,variable=name,type=string,size=100,unique=true,",__CLASS__);
			$column->label(Message::_("name"));
			Rhaco::addVariable("_R_D_C_",$column,"Tag::Name");
		}
		return Rhaco::getVariable("_R_D_C_",null,"Tag::Name");
	}
	/**
	 * タグ名
	 * 
	 * @return string
	 */
	function setName($value){
		$this->name = TableObjectUtil::cast($value,"string");
	}
	/**
	 * タグ名
	 * 
	 */
	function getName(){
		return $this->name;
	}


	function setDependArticleTags($value){
		$this->dependArticleTags = $value;
	}
	function getDependArticleTags(){
		return $this->dependArticleTags;
	}
}
?>