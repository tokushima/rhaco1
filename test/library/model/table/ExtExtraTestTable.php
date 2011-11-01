<?php
Rhaco::import("resources.Message");
Rhaco::import("database.model.TableObjectBase");
Rhaco::import("database.model.DbConnection");
Rhaco::import("model.ExtExtraBaseTest");
Rhaco::import("database.TableObjectUtil");
Rhaco::import("database.model.Table");
Rhaco::import("database.model.Column");
/**
 * #ignore
 * 
 */
class ExtExtraTestTable extends ExtExtraBaseTest{
	var $extra1;

	function table(){
		if(!Rhaco::isVariable("_R_D_T_","ExtExtraTest")){
			Rhaco::addVariable("_R_D_T_",new Table(parent::table(),__CLASS__),"ExtExtraTest");
		}
		return Rhaco::getVariable("_R_D_T_",null,"ExtExtraTest");
	}
	function columns(){
		return array(ExtExtraTest::columnId(),ExtExtraTest::columnValue1(),);
	}
	function columnId(){
		if(!Rhaco::isVariable("_R_D_C_","ExtExtraTest::Id")){
			Rhaco::addVariable("_R_D_C_",new Column(parent::columnId(),__CLASS__),"ExtExtraTest::Id");
		}
		return Rhaco::getVariable("_R_D_C_",null,"ExtExtraTest::Id");			
	}
	function columnValue1(){
		if(!Rhaco::isVariable("_R_D_C_","ExtExtraTest::Value1")){
			Rhaco::addVariable("_R_D_C_",new Column(parent::columnValue1(),__CLASS__),"ExtExtraTest::Value1");
		}
		return Rhaco::getVariable("_R_D_C_",null,"ExtExtraTest::Value1");			
	}
	function __init__(){
		parent::__init__();
		$this->id = null;
		$this->value1 = null;
		$this->extra1 = "extra1";
	}
	/**
	 * 
	 * @return database.model.Column
	 */
	function extraExtra1(){
		if(!Rhaco::isVariable("_R_D_C_","ExtExtraTest::Extra1")){
			$column = new Column("column=extra1,variable=extra1,type=string,",__CLASS__);
			$column->label(Message::_("エキストラ１"));
			Rhaco::addVariable("_R_D_C_",$column,"ExtExtraTest::Extra1");
		}
		return Rhaco::getVariable("_R_D_C_",null,"ExtExtraTest::Extra1");
	}
	/**
	 * 
	 * @return string
	 */
	function setExtra1($value){
		$this->extra1 = TableObjectUtil::cast($value,"string");
	}
	/**
	 * 
	 */
	function getExtra1(){
		return $this->extra1;
	}
}
?>