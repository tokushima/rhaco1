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
class MapTestTable extends TableObjectBase{
	/**  */
	var $id;
	/**  */
	var $factTestId;
	/**  */
	var $factTestExtId;
	var $factFactTestId;
	var $factFactTestExtId;


	function MapTestTable($id=null){
		$this->__init__($id);
	}
	function __init__($id=null){
		$this->id = null;
		$this->factTestId = null;
		$this->factTestExtId = null;
		$this->setId($id);
	}
	function connection(){
		if(!Rhaco::isVariable("_R_D_CON_","test")){
			Rhaco::addVariable("_R_D_CON_",new DbConnection("test"),"test");
		}
		return Rhaco::getVariable("_R_D_CON_",null,"test");
	}
	function table(){
		if(!Rhaco::isVariable("_R_D_T_","MapTest")){
			Rhaco::addVariable("_R_D_T_",new Table(Rhaco::constant("DATABASE_test_PREFIX")."map_test",__CLASS__),"MapTest");
		}
		return Rhaco::getVariable("_R_D_T_",null,"MapTest");
	}


	/**
	 * 
	 * @return database.model.Column
	 */
	function columnId(){
		if(!Rhaco::isVariable("_R_D_C_","MapTest::Id")){
			$column = new Column("column=id,variable=id,type=serial,size=22,primary=true,",__CLASS__);
			$column->label(Message::_("id"));
			Rhaco::addVariable("_R_D_C_",$column,"MapTest::Id");
		}
		return Rhaco::getVariable("_R_D_C_",null,"MapTest::Id");
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
	function columnFactTestId(){
		if(!Rhaco::isVariable("_R_D_C_","MapTest::FactTestId")){
			$column = new Column("column=fact_test_id,variable=factTestId,type=integer,size=22,reference=FactTest::Id,",__CLASS__);
			$column->label(Message::_("fact_test_id"));
			Rhaco::addVariable("_R_D_C_",$column,"MapTest::FactTestId");
		}
		return Rhaco::getVariable("_R_D_C_",null,"MapTest::FactTestId");
	}
	/**
	 * 
	 * @return integer
	 */
	function setFactTestId($value){
		$this->factTestId = TableObjectUtil::cast($value,"integer");
	}
	/**
	 * 
	 */
	function getFactTestId(){
		return $this->factTestId;
	}
	/**
	 * 
	 * @return database.model.Column
	 */
	function columnFactTestExtId(){
		if(!Rhaco::isVariable("_R_D_C_","MapTest::FactTestExtId")){
			$column = new Column("column=fact_test_ext_id,variable=factTestExtId,type=integer,size=22,reference=FactTestExt::Id,",__CLASS__);
			$column->label(Message::_("fact_test_ext_id"));
			Rhaco::addVariable("_R_D_C_",$column,"MapTest::FactTestExtId");
		}
		return Rhaco::getVariable("_R_D_C_",null,"MapTest::FactTestExtId");
	}
	/**
	 * 
	 * @return integer
	 */
	function setFactTestExtId($value){
		$this->factTestExtId = TableObjectUtil::cast($value,"integer");
	}
	/**
	 * 
	 */
	function getFactTestExtId(){
		return $this->factTestExtId;
	}


	function getFactFactTestId(){
		return $this->factFactTestId;
	}
	function setFactFactTestId($obj){
		$this->factFactTestId = $obj;
	}
	function getFactFactTestExtId(){
		return $this->factFactTestExtId;
	}
	function setFactFactTestExtId($obj){
		$this->factFactTestExtId = $obj;
	}
}
?>