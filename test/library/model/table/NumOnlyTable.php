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
class NumOnlyTable extends TableObjectBase{
	/**  */
	var $id;
	/**  */
	var $num;


	function NumOnlyTable($id=null){
		$this->__init__($id);
	}
	function __init__($id=null){
		$this->id = null;
		$this->num = null;
		$this->setId($id);
	}
	function connection(){
		if(!Rhaco::isVariable("_R_D_CON_","test")){
			Rhaco::addVariable("_R_D_CON_",new DbConnection("test"),"test");
		}
		return Rhaco::getVariable("_R_D_CON_",null,"test");
	}
	function table(){
		if(!Rhaco::isVariable("_R_D_T_","NumOnly")){
			Rhaco::addVariable("_R_D_T_",new Table(Rhaco::constant("DATABASE_test_PREFIX")."num_only",__CLASS__),"NumOnly");
		}
		return Rhaco::getVariable("_R_D_T_",null,"NumOnly");
	}


	/**
	 * 
	 * @return database.model.Column
	 */
	function columnId(){
		if(!Rhaco::isVariable("_R_D_C_","NumOnly::Id")){
			$column = new Column("column=id,variable=id,type=serial,size=22,primary=true,",__CLASS__);
			$column->label(Message::_("id"));
			Rhaco::addVariable("_R_D_C_",$column,"NumOnly::Id");
		}
		return Rhaco::getVariable("_R_D_C_",null,"NumOnly::Id");
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
	function columnNum(){
		if(!Rhaco::isVariable("_R_D_C_","NumOnly::Num")){
			$column = new Column("column=num,variable=num,type=integer,size=22,require=true,",__CLASS__);
			$column->label(Message::_("num"));
			Rhaco::addVariable("_R_D_C_",$column,"NumOnly::Num");
		}
		return Rhaco::getVariable("_R_D_C_",null,"NumOnly::Num");
	}
	/**
	 * 
	 * @return integer
	 */
	function setNum($value){
		$this->num = TableObjectUtil::cast($value,"integer");
	}
	/**
	 * 
	 */
	function getNum(){
		return $this->num;
	}


}
?>