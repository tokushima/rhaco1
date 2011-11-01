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
class UniqueTestMapTable extends TableObjectBase{
	/**  */
	var $id;
	/**  */
	var $user;
	/**  */
	var $comp;
	var $factUser;
	var $factComp;


	function UniqueTestMapTable($id=null){
		$this->__init__($id);
	}
	function __init__($id=null){
		$this->id = null;
		$this->user = null;
		$this->comp = null;
		$this->setId($id);
	}
	function connection(){
		if(!Rhaco::isVariable("_R_D_CON_","test")){
			Rhaco::addVariable("_R_D_CON_",new DbConnection("test"),"test");
		}
		return Rhaco::getVariable("_R_D_CON_",null,"test");
	}
	function table(){
		if(!Rhaco::isVariable("_R_D_T_","UniqueTestMap")){
			Rhaco::addVariable("_R_D_T_",new Table(Rhaco::constant("DATABASE_test_PREFIX")."unique_test_map",__CLASS__),"UniqueTestMap");
		}
		return Rhaco::getVariable("_R_D_T_",null,"UniqueTestMap");
	}


	/**
	 * 
	 * @return database.model.Column
	 */
	function columnId(){
		if(!Rhaco::isVariable("_R_D_C_","UniqueTestMap::Id")){
			$column = new Column("column=id,variable=id,type=serial,size=22,primary=true,",__CLASS__);
			$column->label(Message::_("id"));
			Rhaco::addVariable("_R_D_C_",$column,"UniqueTestMap::Id");
		}
		return Rhaco::getVariable("_R_D_C_",null,"UniqueTestMap::Id");
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
	function columnUser(){
		if(!Rhaco::isVariable("_R_D_C_","UniqueTestMap::User")){
			$column = new Column("column=user_id,variable=user,type=integer,size=22,unique=true,reference=UniqueTest::Id,uniqueWith=UniqueTestMap::Comp,",__CLASS__);
			$column->label(Message::_("user_id"));
			Rhaco::addVariable("_R_D_C_",$column,"UniqueTestMap::User");
		}
		return Rhaco::getVariable("_R_D_C_",null,"UniqueTestMap::User");
	}
	/**
	 * 
	 * @return integer
	 */
	function setUser($value){
		$this->user = TableObjectUtil::cast($value,"integer");
	}
	/**
	 * 
	 */
	function getUser(){
		return $this->user;
	}
	/**
	 * 
	 * @return database.model.Column
	 */
	function columnComp(){
		if(!Rhaco::isVariable("_R_D_C_","UniqueTestMap::Comp")){
			$column = new Column("column=comp_id,variable=comp,type=integer,size=22,unique=true,reference=UniqueTestExt::Id,uniqueWith=UniqueTestMap::User,",__CLASS__);
			$column->label(Message::_("comp_id"));
			Rhaco::addVariable("_R_D_C_",$column,"UniqueTestMap::Comp");
		}
		return Rhaco::getVariable("_R_D_C_",null,"UniqueTestMap::Comp");
	}
	/**
	 * 
	 * @return integer
	 */
	function setComp($value){
		$this->comp = TableObjectUtil::cast($value,"integer");
	}
	/**
	 * 
	 */
	function getComp(){
		return $this->comp;
	}


	function getFactUser(){
		return $this->factUser;
	}
	function setFactUser($obj){
		$this->factUser = $obj;
	}
	function getFactComp(){
		return $this->factComp;
	}
	function setFactComp($obj){
		$this->factComp = $obj;
	}
}
?>