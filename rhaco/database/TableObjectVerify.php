<?php
Rhaco::import("lang.Validate");
Rhaco::import("lang.StringUtil");
Rhaco::import("lang.Variable");
Rhaco::import("database.TableObjectUtil");
Rhaco::import("exception.ExceptionTrigger");
Rhaco::import("exception.model.MaxLengthException");
Rhaco::import("exception.model.MinLengthException");
Rhaco::import("exception.model.MaxSizeException");
Rhaco::import("exception.model.MinSizeException");
Rhaco::import("exception.model.RequireException");
Rhaco::import("exception.model.DataTypeException");
Rhaco::import("resources.Message");
/**
 * TableObjectの検証クラス
 * @author Kazutaka Tokushima
 * @license New BSD License
 * @copyright Copyright 2010- rhaco project. All rights reserved.
 */
class TableObjectVerify{
	/**
	 * テーブルオブジェクトの検証を行う
	 * @param DbUtil $db
	 * @param TableObjectBase $tableObject
	 * @return boolean
	 */
	function verify(&$db,&$tableObject){
		$bool = true;

		foreach($tableObject->columns() as $column){
			TableObjectVerify::check($db,$tableObject,$column,$bool);
		}
		foreach($tableObject->extra() as $column){
			TableObjectVerify::check($db,$tableObject,$column,$bool);
		}
		foreach(get_class_methods($tableObject) as $method){
			if(strpos($method,"verify") === 0){
				$bool = Variable::is($tableObject->$method($db),$bool);
			}
		}
		return $bool;
	}	
	/**
	 * 列の検証を行う
	 * @param DbUtil $db
	 * @param TableObjectBase $tableObject
	 * @param Column $column
	 * @param boolean $bool
	 */
	function check(&$db,&$tableObject,&$column,&$bool){
		$value = TableObjectUtil::getter($tableObject,$column);
		$valid = $tableObject->validName($column);
		$type = $column->type();

		if($column->isRequire() && $column->type() != "serial"){
			$bool = Variable::is(TableObjectVerify::isRequire($tableObject,$column,$value),$bool);
		}
		if($type == "text" || $type == "string" || $type == "email" || $type == "tel" || $type == "zip"){
			if($column->isChartype()){
				$bool = Variable::is(TableObjectVerify::isChartype($tableObject,$column,$value),$bool);
			}
			if($column->max() !== null){
				$bool = Variable::is(TableObjectVerify::isStringMax($tableObject,$column,$value),$bool);
			}
			if($column->min() !== null){
				$bool = Variable::is(TableObjectVerify::isStringMin($tableObject,$column,$value),$bool);
			}
			if($type == "email"){
				$bool = Variable::is(TableObjectVerify::isEmail($tableObject,$column,$value),$bool);
			}else if($type == "tel"){
				$bool = Variable::is(TableObjectVerify::isTel($tableObject,$column,$value),$bool);
			}else if($type == "zip"){
				$bool = Variable::is(TableObjectVerify::isZip($tableObject,$column,$value),$bool);
			}
		}else if($type == "integer" || $type == "serial"){
			$bool = Variable::is(TableObjectVerify::isIntLength($tableObject,$column,$value),$bool);

			if($column->min() !== null){
				$bool = Variable::is(TableObjectVerify::isIntMin($tableObject,$column,$value),$bool);
			}
			if($column->max() !== null){
				$bool = Variable::is(TableObjectVerify::isIntMax($tableObject,$column,$value),$bool);
			}
		}else if($type == "timestamp"){
			$bool = Variable::is(TableObjectVerify::isTimestamp($tableObject,$column,$value),$bool);
		}else if($type == "date"){
			$bool = Variable::is(TableObjectVerify::isDate($tableObject,$column,$value),$bool);
		}else if($type == "birthday"){
			$bool = Variable::is(TableObjectVerify::isDay($tableObject,$column,$value),$bool);
		}else if($type == "time"){
		}
		if($column->isRequireWith()){
			$bool = Variable::is(TableObjectVerify::isRequireWith($tableObject,$column,$value),$bool);
		}
		if(sizeof($column->choices()) > 0){
			$bool = Variable::is(TableObjectVerify::isChoices($tableObject,$column,$value),$bool);
		}
	}
	/**
	 * 値が入力されているかどうか
	 * @param TableObjectBase $tableObject
	 * @param Column $column
	 * @param mixed $value
	 * @return boolean
	 */
	function isRequire(&$tableObject,$column,$value){
		if($value === "" || $value === null){
			return ExceptionTrigger::raise(new RequireException(array($column->label())),$tableObject->validName($column));
		}
		return true;
	}
	/**
	 * 最大文字列を超えていないかどうか
	 * @param TableObjectBase $tableObject
	 * @param Column $column
	 * @param mixed $value
	 * @return boolean
	 */
	function isStringMax(&$tableObject,$column,$value){
		/*** unit("database.TableObjectVerifyTest"); */		
		if(!empty($value) && !Validate::isString($value,0,$column->max())){
			return ExceptionTrigger::raise(new MaxLengthException(array($column->label(),$column->max())),$tableObject->validName($column));
		}
		return true;
	}
	/**
	 * 最小文字列を超えいるかどうか
	 * @param TableObjectBase $tableObject
	 * @param Column $column
	 * @param mixed $value
	 * @return boolean
	 */
	function isStringMin(&$tableObject,$column,$value){
		/*** unit("database.TableObjectVerifyTest"); */		
		if(!empty($value) && StringUtil::strlen($value) < $column->min()){
			return ExceptionTrigger::raise(new MinLengthException(array($column->label(),$column->min())),$tableObject->validName($column));
		}
		return true;
	}
	/**
	 * 列に定義した正規表現に合っているかどうか
	 * @param TableObjectBase $tableObject
	 * @param Column $column
	 * @param mixed $value
	 * @return boolean
	 */
	function isChartype(&$tableObject,$column,$value){
		if(!preg_match($column->chartype,StringUtil::encode($value))){
			return ExceptionTrigger::raise(new DataTypeException(array($column->label())),$tableObject->validName($column));
		}
		return true;
	}
	/**
	 * メールアドレスかどうか
	 * @param TableObjectBase $tableObject
	 * @param Column $column
	 * @param mixed $value
	 * @return boolean
	 */
	function isEmail(&$tableObject,$column,$value){
		if(!empty($value) && !Validate::isEmail($value)){
			return ExceptionTrigger::raise(new DataTypeException(array($column->label())),$tableObject->validName($column));
		}
		return true;
	}
	/**
	 * 日本国内の電話番号かどうか
	 * @param TableObjectBase $tableObject
	 * @param Column $column
	 * @param mixed $value
	 * @return boolean
	 */
	function isTel(&$tableObject,$column,$value){
		if(!empty($value)){
			$value = str_replace("-","",$value);
		 	if(!Validate::isTel(substr($value,0,3),substr($value,3,4),substr($value,7))){
				return ExceptionTrigger::raise(new DataTypeException(array($column->label())),$tableObject->validName($column));
			}
		}
		return true;
	}
	/**
	 * 日本国内の郵便番号かどうか
	 * @param TableObjectBase $tableObject
	 * @param Column $column
	 * @param mixed $value
	 * @return boolean
	 */
	function isZip(&$tableObject,$column,$value){
		if(!empty($value)){
			$value = preg_replace("/[^\d]/","",$value);
		 	if(!Validate::isZip(substr($value,0,3),substr($value,3))){
				return ExceptionTrigger::raise(new DataTypeException(array($column->label())),$tableObject->validName($column));
			}
		}
		return true;
	}
	/**
	 * 最小値を超えているかどうか
	 * @param TableObjectBase $tableObject
	 * @param Column $column
	 * @param mixed $value
	 * @return boolean
	 */
	function isIntMin(&$tableObject,$column,$value){
		/*** unit("database.TableObjectVerifyTest"); */		
		if($value !== null && $value < $column->min()){
			return ExceptionTrigger::raise(new MinSizeException(array($column->label(),$column->min())),$tableObject->validName($column));
		}
		return true;
	}
	/**
	 * 最大値を超えていないかどうか
	 * @param TableObjectBase $tableObject
	 * @param Column $column
	 * @param mixed $value
	 * @return boolean
	 */
	function isIntMax(&$tableObject,$column,$value){
		/*** unit("database.TableObjectVerifyTest"); */		
		if($value !== null && $value > $column->max()){
			return ExceptionTrigger::raise(new MaxSizeException(array($column->label(),$column->max())),$tableObject->validName($column));
		}
		return true;
	}
	/**
	 * 数値を文字列として評価し最大文字数を超えていないかどうか
	 * @param TableObjectBase $tableObject
	 * @param Column $column
	 * @param mixed $value
	 * @return boolean
	 */
	function isIntLength(&$tableObject,$column,$value){
		/*** unit("database.TableObjectVerifyTest"); */
		if(!empty($value) && !Validate::isIntegerLength($value,$column->size())){
			return ExceptionTrigger::raise(new MaxLengthException(array($column->label(),$column->size())),$tableObject->validName($column));
		}
		return true;
	}
	/**
	 * タイムスタンプかどうか
	 * @param TableObjectBase $tableObject
	 * @param Column $column
	 * @param mixed $value
	 * @return boolean
	 */
	function isTimestamp(&$tableObject,$column,$value){
		if(!empty($value) && !Validate::isTimestamp($value)){
			return ExceptionTrigger::raise(new DataTypeException(array($column->label())),$tableObject->validName($column));
		}
		return true;
	}
	/**
	 * 日付かどうか
	 * @param TableObjectBase $tableObject
	 * @param Column $column
	 * @param mixed $value
	 * @return boolean
	 */
	function isDate(&$tableObject,$column,$value){
		if(!empty($value) && !Validate::isDate(date("Y",$value),date("m",$value),date("d",$value))){
			return ExceptionTrigger::raise(new DataTypeException(array($column->label())),$tableObject->validName($column));
		}
		return true;
	}
	/**
	 * 日にちかどうか
	 * @param TableObjectBase $tableObject
	 * @param Column $column
	 * @param mixed $value
	 * @return boolean
	 */
	function isDay(&$tableObject,$column,$value){
		if(!empty($value) && !Validate::isDay($value)){
			return ExceptionTrigger::raise(new DataTypeException(array($column->label())),$tableObject->validName($column));
		}
		return true;
	}
	/**
	 * 同時必須項目が入力されているかどうか
	 * @param TableObjectBase $tableObject
	 * @param Column $column
	 * @param mixed $value
	 * @return boolean
	 */
	function isRequireWith(&$tableObject,$column,$value){
		/*** unit("database.TableObjectVerifyTest"); */
		$with = TableObjectUtil::getter($tableObject,$column->requireWith());

		if(empty($value) && !empty($with)){
			return ExceptionTrigger::raise(new RequireException(array($column->label())),$tableObject->validName($column));
		}
		return true;
	}
	/**
	 * 選択項目に合うかどうか
	 * @param TableObjectBase $tableObject
	 * @param Column $column
	 * @param mixed $value
	 * @return boolean
	 */
	function isChoices(&$tableObject,$column,$value){
		if(!empty($value) && !array_key_exists($value,$column->choices())){
			return ExceptionTrigger::raise(new DataTypeException(array($column->label())),$tableObject->validName($column));		
		}
		return true;
	}
}
?>