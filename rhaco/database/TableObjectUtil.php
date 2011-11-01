<?php
Rhaco::import("lang.Variable");
Rhaco::import("lang.ArrayUtil");
Rhaco::import("lang.ObjectUtil");
Rhaco::import("lang.StringUtil");
Rhaco::import("tag.model.TemplateFormatter");
/**
 * @author Kazutaka Tokushima
 * @license New BSD License
 * @copyright Copyright 2006- rhaco project. All rights reserved.
 */
class TableObjectUtil{	
	/**
	 * TableObjectのcolumnを利用できるように定義する
	 * @static 
	 * @param TableObjectBase $tableObject
	 * @return boolean
	 */
	function setaccessor($tableObject){
		return ObjectUtil::setaccessor($tableObject,array("TableObjectUtil","cast"));
	}
	/**
	 * TableObjectの指定のカラムの値を取り出す
	 * @static 
	 * @param TableObjectBase $tableObject
	 * @param Column $column
	 * @param boolean $formatter
	 * @return mixed
	 */
	function getter(&$tableObject,$column,$formatter=false){
		if(TableObjectUtil::setaccessor($tableObject)) return ObjectUtil::getter($tableObject,$column->variable(),$formatter,$column->type());
		return null;
	}
	/**
	 * TableObjectの指定のカラムに値をセットする
	 * @static 
	 * @param TableObjectBase $tableObject
	 * @param Column $column
	 * @param mixed $value
	 * @return mixed 成功したら $value が帰る
	 */
	function setter(&$tableObject,$column,$value){
		if(TableObjectUtil::setaccessor($tableObject)) return ObjectUtil::setter($tableObject,$column->variable(),$value,$column->type());
		return null;
	}
	/**
	 * 指定の型に適した値を返す
	 * @static 
	 * @param mixed $value
	 * @param string $type
	 * @return mixed
	 */	
	function cast($value,$type=null){
		if(Variable::istype("Column",$type)) $type = $type->type;
		switch($type){
			case "timestamp":
			case "date":
				return DateUtil::parseString(StringUtil::convertZenhan($value));
			case "boolean":
				return (Variable::bool($value)) ? 1 : 0;
			case "email":
			case "tel":
			case "zip":
				return StringUtil::convertZenhan($value);
			case "integer":
			case "serial":
				$value = StringUtil::convertZenhan($value);
				return (is_null($value) || !is_numeric($value)) ? null : intval(sprintf("%d",StringUtil::convertZenhan($value)));
			case "float":
				$value = StringUtil::convertZenhan($value);
				return (is_null($value) || !is_numeric($value)) ? null : floatval(sprintf("%f",StringUtil::convertZenhan($value)));
			case "time":
				return DateUtil::parseTime(StringUtil::convertZenhan($value));
			case "birthday":
				return DateUtil::parseIntDate(StringUtil::convertZenhan($value));
			default:
				return $value;
		}
	}
	/**
	 * 有効な型を返す
	 * @static 
	 * @param string $type
	 * @return string
	 */
	function type($type){
		$type = strtolower($type);
		if($type == "email" || $type == "text" || $type == "tel" || $type == "zip" || $type == "integer" || $type == "serial"
			|| $type == "time" || $type == "float" || $type == "date" || $type == "timestamp" || $type == "boolean"
			|| $type == "birthday"
		){
			return $type;
		}
		return "string";
	}
	/**
	 * 文字列型か
	 * @static 
	 * @param Column $column
	 * @return boolean
	 */
	function isTypeString($column){
		return ($column->type == "email" || $column->type == "string" || $column->type == "text" || $column->type == "tel" || $column->type == "zip");
	}
	/**
	 * 数値型か
	 * @static 
	 * @param Column $column
	 * @return boolean
	 */
	function isTypeInt($column){
		return ($column->type == "integer" || $column->type == "serial");
	}
	/**
	 * float型か
	 * @static
	 * @param Column $column
	 * @return boolean
	 */
	function isTypeFloat($column){
		return ($column->type == "float");
	}
	/**
	 * 時間型か
	 * @static 
	 * @param Column $column
	 * @return boolean
	 */
	function isTypeTime($column){
		return ($column->type == "time");
	}
	/**
	 * 日付型か
	 * @static 
	 * @param Column $column
	 * @return boolean
	 */
	function isTypeDate($column){
		return ($column->type == "date" || $column->type == "timestamp");
	}
	/**
	 * boolean型か
	 * @static 
	 * @param Column $column
	 * @return boolean
	 */
	function isTypeBool($column){
		return ($column->type == "boolean");
	}
	/**
	 * birthday型か
	 * @static 
	 * @param Column $column
	 * @return boolean
	 */
	function isTypeBirthday($column){
		return ($column->type == "birthday");		
	}
	/**
	 * TableObjectのリストを<select>に適した形式に変換する
	 * ex.
	 *  ::options(choiceをもつColumn)
	 *  ::options($tableObjectList,value値に使用するColumn,caption値に使用するColumn)
	 * @static
	 * @param Column/TableObjectBase[] $arg1
	 * @param null/Column $valueColumn
	 * @param null/Column $captionColumn
	 * @return array
	 */
	function options(){
		$result = array();
		
		switch(func_num_args()){
			case 3:
				list($tableObjectList,$valueColumn,$captionColumn) = func_get_args();
				foreach(ArrayUtil::arrays($tableObjectList) as $object){
					$result[TableObjectUtil::getter($object,$valueColumn)] = TableObjectUtil::getter($object,$captionColumn);
				}
				break;
			case 1:
				list($column) = func_get_args();
				if(Variable::istype("Column",$column)){
					$result = $column->choices();
				}
				break;
		}
		return $result;
	}
	/**
	 * TableObjectの指定のColumnの値に対応したchoiceのcaptionを返す
	 * @param TableObjectBase $tableObject
	 * @param Column $column
	 * @return string
	 */
	function caption($tableObject,$column){
		$choices = $column->choices();
		$value = TableObjectUtil::getter($tableObject,$column);
		return (isset($choices[$value])) ? $choices[$value] : null;
	}
}
?>