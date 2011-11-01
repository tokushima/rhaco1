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
class MinmaxTestTable extends TableObjectBase{
	/**  */
	var $id;
	/**  */
	var $val1;
	/**  */
	var $val2;
	/**  */
	var $val3;


	function MinmaxTestTable($id=null){
		$this->__init__($id);
	}
	function __init__($id=null){
		$this->id = null;
		$this->val1 = null;
		$this->val2 = null;
		$this->val3 = null;
		$this->setId($id);
	}
	function connection(){
		if(!Rhaco::isVariable("_R_D_CON_","test")){
			Rhaco::addVariable("_R_D_CON_",new DbConnection("test"),"test");
		}
		return Rhaco::getVariable("_R_D_CON_",null,"test");
	}
	function table(){
		if(!Rhaco::isVariable("_R_D_T_","MinmaxTest")){
			Rhaco::addVariable("_R_D_T_",new Table(Rhaco::constant("DATABASE_test_PREFIX")."minmax_test",__CLASS__),"MinmaxTest");
		}
		return Rhaco::getVariable("_R_D_T_",null,"MinmaxTest");
	}


	/**
	 * 
	 * @return database.model.Column
	 */
	function columnId(){
		if(!Rhaco::isVariable("_R_D_C_","MinmaxTest::Id")){
			$column = new Column("column=id,variable=id,type=serial,size=22,primary=true,",__CLASS__);
			$column->label(Message::_("id"));
			Rhaco::addVariable("_R_D_C_",$column,"MinmaxTest::Id");
		}
		return Rhaco::getVariable("_R_D_C_",null,"MinmaxTest::Id");
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
	function columnVal1(){
		if(!Rhaco::isVariable("_R_D_C_","MinmaxTest::Val1")){
			$column = new Column("column=val1,variable=val1,type=integer,size=22,max=10,min=0,",__CLASS__);
			$column->label(Message::_("val1"));
			Rhaco::addVariable("_R_D_C_",$column,"MinmaxTest::Val1");
		}
		return Rhaco::getVariable("_R_D_C_",null,"MinmaxTest::Val1");
	}
	/**
	 * 
	 * @return integer
	 */
	function setVal1($value){
		$this->val1 = TableObjectUtil::cast($value,"integer");
	}
	/**
	 * 
	 */
	function getVal1(){
		return $this->val1;
	}
	/**
	 * 
	 * @return database.model.Column
	 */
	function columnVal2(){
		if(!Rhaco::isVariable("_R_D_C_","MinmaxTest::Val2")){
			$column = new Column("column=val2,variable=val2,type=integer,size=22,max=0,min=-10,",__CLASS__);
			$column->label(Message::_("val2"));
			Rhaco::addVariable("_R_D_C_",$column,"MinmaxTest::Val2");
		}
		return Rhaco::getVariable("_R_D_C_",null,"MinmaxTest::Val2");
	}
	/**
	 * 
	 * @return integer
	 */
	function setVal2($value){
		$this->val2 = TableObjectUtil::cast($value,"integer");
	}
	/**
	 * 
	 */
	function getVal2(){
		return $this->val2;
	}
	/**
	 * 
	 * @return database.model.Column
	 */
	function columnVal3(){
		if(!Rhaco::isVariable("_R_D_C_","MinmaxTest::Val3")){
			$column = new Column("column=val3,variable=val3,type=string,max=10,min=0,",__CLASS__);
			$column->label(Message::_("val3"));
			Rhaco::addVariable("_R_D_C_",$column,"MinmaxTest::Val3");
		}
		return Rhaco::getVariable("_R_D_C_",null,"MinmaxTest::Val3");
	}
	/**
	 * 
	 * @return string
	 */
	function setVal3($value){
		$this->val3 = TableObjectUtil::cast($value,"string");
	}
	/**
	 * 
	 */
	function getVal3(){
		return $this->val3;
	}


}
?>