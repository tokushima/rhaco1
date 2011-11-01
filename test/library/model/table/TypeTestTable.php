<?php
Rhaco::import("resources.Message");
Rhaco::import("database.model.TableObjectBase");
Rhaco::import("database.model.DbConnection");
Rhaco::import("database.TableObjectUtil");
Rhaco::import("database.model.Table");
Rhaco::import("database.model.Column");
/**
 * #ignore
 *  				setup.database.model.ColumnModel::_parseType 			
 */
class TypeTestTable extends TableObjectBase{
	/**  */
	var $id;
	/**  */
	var $booleValue;
	/**  */
	var $timeValue;
	/**  */
	var $timestampValue;
	/**  */
	var $dateValue;
	/**  */
	var $floatValue;
	/**  */
	var $integerValue;
	/**  */
	var $zipValue;
	/**  */
	var $telValue;
	/**  */
	var $emailValue;
	/**  */
	var $textValue;
	/**  */
	var $stringValue;


	function TypeTestTable($id=null){
		$this->__init__($id);
	}
	function __init__($id=null){
		$this->id = null;
		$this->booleValue = 0;
		$this->timeValue = null;
		$this->timestampValue = null;
		$this->dateValue = null;
		$this->floatValue = null;
		$this->integerValue = null;
		$this->zipValue = null;
		$this->telValue = null;
		$this->emailValue = null;
		$this->textValue = null;
		$this->stringValue = null;
		$this->setId($id);
	}
	function connection(){
		if(!Rhaco::isVariable("_R_D_CON_","test")){
			Rhaco::addVariable("_R_D_CON_",new DbConnection("test"),"test");
		}
		return Rhaco::getVariable("_R_D_CON_",null,"test");
	}
	function table(){
		if(!Rhaco::isVariable("_R_D_T_","TypeTest")){
			Rhaco::addVariable("_R_D_T_",new Table(Rhaco::constant("DATABASE_test_PREFIX")."type_test",__CLASS__),"TypeTest");
		}
		return Rhaco::getVariable("_R_D_T_",null,"TypeTest");
	}


	/**
	 * 
	 * @return database.model.Column
	 */
	function columnId(){
		if(!Rhaco::isVariable("_R_D_C_","TypeTest::Id")){
			$column = new Column("column=id,variable=id,type=serial,size=22,primary=true,",__CLASS__);
			$column->label(Message::_("id"));
			Rhaco::addVariable("_R_D_C_",$column,"TypeTest::Id");
		}
		return Rhaco::getVariable("_R_D_C_",null,"TypeTest::Id");
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
	function columnBooleValue(){
		if(!Rhaco::isVariable("_R_D_C_","TypeTest::BooleValue")){
			$column = new Column("column=boole_value,variable=booleValue,type=boolean,",__CLASS__);
			$column->label(Message::_("boole_value"));
			Rhaco::addVariable("_R_D_C_",$column,"TypeTest::BooleValue");
		}
		return Rhaco::getVariable("_R_D_C_",null,"TypeTest::BooleValue");
	}
	/**
	 * 
	 * @return boolean
	 */
	function setBooleValue($value){
		$this->booleValue = TableObjectUtil::cast($value,"boolean");
	}
	/**
	 * 
	 */
	function getBooleValue(){
		return $this->booleValue;
	}
	/**  */
	function isBooleValue(){
		return Variable::bool($this->booleValue);
	}
	/**
	 * 
	 * @return database.model.Column
	 */
	function columnTimeValue(){
		if(!Rhaco::isVariable("_R_D_C_","TypeTest::TimeValue")){
			$column = new Column("column=time_value,variable=timeValue,type=time,size=22,",__CLASS__);
			$column->label(Message::_("time_value"));
			Rhaco::addVariable("_R_D_C_",$column,"TypeTest::TimeValue");
		}
		return Rhaco::getVariable("_R_D_C_",null,"TypeTest::TimeValue");
	}
	/**
	 * 
	 * @return time
	 */
	function setTimeValue($value){
		$this->timeValue = TableObjectUtil::cast($value,"time");
	}
	/**
	 * 
	 */
	function getTimeValue(){
		return $this->timeValue;
	}
	/**  */
	function formatTimeValue(){
		return DateUtil::formatTime($this->timeValue);
	}
	/**
	 * 
	 * @return database.model.Column
	 */
	function columnTimestampValue(){
		if(!Rhaco::isVariable("_R_D_C_","TypeTest::TimestampValue")){
			$column = new Column("column=timestamp_value,variable=timestampValue,type=timestamp,",__CLASS__);
			$column->label(Message::_("timestamp_value"));
			Rhaco::addVariable("_R_D_C_",$column,"TypeTest::TimestampValue");
		}
		return Rhaco::getVariable("_R_D_C_",null,"TypeTest::TimestampValue");
	}
	/**
	 * 
	 * @return timestamp
	 */
	function setTimestampValue($value){
		$this->timestampValue = TableObjectUtil::cast($value,"timestamp");
	}
	/**
	 * 
	 */
	function getTimestampValue(){
		return $this->timestampValue;
	}
	/**  */
	function formatTimestampValue($format="Y/m/d H:i:s"){
		return DateUtil::format($this->timestampValue,$format);
	}
	/**
	 * 
	 * @return database.model.Column
	 */
	function columnDateValue(){
		if(!Rhaco::isVariable("_R_D_C_","TypeTest::DateValue")){
			$column = new Column("column=date_value,variable=dateValue,type=date,",__CLASS__);
			$column->label(Message::_("date_value"));
			Rhaco::addVariable("_R_D_C_",$column,"TypeTest::DateValue");
		}
		return Rhaco::getVariable("_R_D_C_",null,"TypeTest::DateValue");
	}
	/**
	 * 
	 * @return date
	 */
	function setDateValue($value){
		$this->dateValue = TableObjectUtil::cast($value,"date");
	}
	/**
	 * 
	 */
	function getDateValue(){
		return $this->dateValue;
	}
	/**  */
	function formatDateValue($format="Y/m/d"){
		return DateUtil::format($this->dateValue,$format);
	}
	/**
	 * 
	 * @return database.model.Column
	 */
	function columnFloatValue(){
		if(!Rhaco::isVariable("_R_D_C_","TypeTest::FloatValue")){
			$column = new Column("column=float_value,variable=floatValue,type=float,",__CLASS__);
			$column->label(Message::_("float_value"));
			Rhaco::addVariable("_R_D_C_",$column,"TypeTest::FloatValue");
		}
		return Rhaco::getVariable("_R_D_C_",null,"TypeTest::FloatValue");
	}
	/**
	 * 
	 * @return float
	 */
	function setFloatValue($value){
		$this->floatValue = TableObjectUtil::cast($value,"float");
	}
	/**
	 * 
	 */
	function getFloatValue(){
		return $this->floatValue;
	}
	/**
	 * 
	 * @return database.model.Column
	 */
	function columnIntegerValue(){
		if(!Rhaco::isVariable("_R_D_C_","TypeTest::IntegerValue")){
			$column = new Column("column=integer_value,variable=integerValue,type=integer,size=22,",__CLASS__);
			$column->label(Message::_("integer_value"));
			Rhaco::addVariable("_R_D_C_",$column,"TypeTest::IntegerValue");
		}
		return Rhaco::getVariable("_R_D_C_",null,"TypeTest::IntegerValue");
	}
	/**
	 * 
	 * @return integer
	 */
	function setIntegerValue($value){
		$this->integerValue = TableObjectUtil::cast($value,"integer");
	}
	/**
	 * 
	 */
	function getIntegerValue(){
		return $this->integerValue;
	}
	/**
	 * 
	 * @return database.model.Column
	 */
	function columnZipValue(){
		if(!Rhaco::isVariable("_R_D_C_","TypeTest::ZipValue")){
			$column = new Column("column=zip_value,variable=zipValue,type=zip,size=8,",__CLASS__);
			$column->label(Message::_("zip_value"));
			Rhaco::addVariable("_R_D_C_",$column,"TypeTest::ZipValue");
		}
		return Rhaco::getVariable("_R_D_C_",null,"TypeTest::ZipValue");
	}
	/**
	 * 
	 * @return zip
	 */
	function setZipValue($value){
		$this->zipValue = TableObjectUtil::cast($value,"zip");
	}
	/**
	 * 
	 */
	function getZipValue(){
		return $this->zipValue;
	}
	/**
	 * 
	 * @return database.model.Column
	 */
	function columnTelValue(){
		if(!Rhaco::isVariable("_R_D_C_","TypeTest::TelValue")){
			$column = new Column("column=tel_value,variable=telValue,type=tel,size=13,",__CLASS__);
			$column->label(Message::_("tel_value"));
			Rhaco::addVariable("_R_D_C_",$column,"TypeTest::TelValue");
		}
		return Rhaco::getVariable("_R_D_C_",null,"TypeTest::TelValue");
	}
	/**
	 * 
	 * @return tel
	 */
	function setTelValue($value){
		$this->telValue = TableObjectUtil::cast($value,"tel");
	}
	/**
	 * 
	 */
	function getTelValue(){
		return $this->telValue;
	}
	/**
	 * 
	 * @return database.model.Column
	 */
	function columnEmailValue(){
		if(!Rhaco::isVariable("_R_D_C_","TypeTest::EmailValue")){
			$column = new Column("column=email_value,variable=emailValue,type=email,size=255,",__CLASS__);
			$column->label(Message::_("email_value"));
			Rhaco::addVariable("_R_D_C_",$column,"TypeTest::EmailValue");
		}
		return Rhaco::getVariable("_R_D_C_",null,"TypeTest::EmailValue");
	}
	/**
	 * 
	 * @return email
	 */
	function setEmailValue($value){
		$this->emailValue = TableObjectUtil::cast($value,"email");
	}
	/**
	 * 
	 */
	function getEmailValue(){
		return $this->emailValue;
	}
	/**
	 * 
	 * @return database.model.Column
	 */
	function columnTextValue(){
		if(!Rhaco::isVariable("_R_D_C_","TypeTest::TextValue")){
			$column = new Column("column=text_value,variable=textValue,type=text,",__CLASS__);
			$column->label(Message::_("text_value"));
			Rhaco::addVariable("_R_D_C_",$column,"TypeTest::TextValue");
		}
		return Rhaco::getVariable("_R_D_C_",null,"TypeTest::TextValue");
	}
	/**
	 * 
	 * @return text
	 */
	function setTextValue($value){
		$this->textValue = TableObjectUtil::cast($value,"text");
	}
	/**
	 * 
	 */
	function getTextValue(){
		return $this->textValue;
	}
	/**
	 * 
	 * @return database.model.Column
	 */
	function columnStringValue(){
		if(!Rhaco::isVariable("_R_D_C_","TypeTest::StringValue")){
			$column = new Column("column=string_value,variable=stringValue,type=string,",__CLASS__);
			$column->label(Message::_("string_value"));
			Rhaco::addVariable("_R_D_C_",$column,"TypeTest::StringValue");
		}
		return Rhaco::getVariable("_R_D_C_",null,"TypeTest::StringValue");
	}
	/**
	 * 
	 * @return string
	 */
	function setStringValue($value){
		$this->stringValue = TableObjectUtil::cast($value,"string");
	}
	/**
	 * 
	 */
	function getStringValue(){
		return $this->stringValue;
	}


}
?>