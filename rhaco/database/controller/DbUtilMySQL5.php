<?php
Rhaco::import("database.DbUtilBase");
Rhaco::import("lang.ArrayUtil");
Rhaco::import("lang.StringUtil");
Rhaco::import("lang.Variable");
/**
 * #ignore
 * MySQL5用 databse操作クラス
 * 
 * @author Kazutaka Tokushima
 * @license New BSD License
 * @copyright Copyright 2005- rhaco project. All rights reserved.
 * @see database.DbUtilBase
 */
class DbUtilMySQL5 extends DbUtilBase{
	var $name = "MySQL5";
	
	function _valid(){
		return extension_loaded("mysql");
	}
	function _open($dbConnection){
		if($this->valid()){
			$host = sprintf("%s:3306",$dbConnection->host);

			if($dbConnection->port != "") $host = sprintf("%s:%d",$dbConnection->host,$dbConnection->port);
			$this->connection = @mysql_connect($host,$dbConnection->user,$dbConnection->password,$dbConnection->new);
			if($this->connection != false){
				if(!mysql_select_db($dbConnection->name)){
					$this->connection = false;
					return false;
				}
				$this->dbConnection = $dbConnection;
				$this->_debug_open();
				if(!empty($dbConnection->encode)) $this->query("SET NAMES ".$dbConnection->encode);
				$this->query("SET AUTOCOMMIT=0");
				$this->trans(true);
				return true;
			}
		}
		return false;
	}
	function _close(){
		if($this->connection){
			$this->_commit();
			@mysql_close($this->connection);
			$this->_debug_close();
		}
		$this->connection = false;
	}
	function _analyze($tableObjectList){
		$tables = "";
		
		foreach(ArrayUtil::arrays($tableObjectList) as $tableObject){
			if(Variable::istype("TableObjectBase",$tableObject)){
				foreach($tableObject->columns() as $columnObject){
					$tables .= ",".$columnObject->sqltablename();
					break;
				}
			}
			if(!empty($tables)){
				$this->query("analyze table ".substr($tables,1));
			}
		}
	}
	function _optimize($tableObjectList){
		$tables = "";
		
		foreach(ArrayUtil::arrays($tableObjectList) as $tableObject){
			if(Variable::istype("TableObjectBase",$tableObject)){
				foreach($tableObject->columns() as $columnObject){
					$tables .= ",".$columnObject->sqltablename();
					break;
				}
			}
			if(!empty($tables)){
				$this->query("optimize table ".substr($tables,1));
			}
		} 
	}
	function _droptable($tableObject){
		if(Variable::istype("TableObjectBase",$tableObject)){
			foreach($tableObject->columns() as $columnObject){
				$this->query("drop table ".$columnObject->sqltablename());
				break;
			}
		}
	}
	function _resultset(){
		if($this->resourceId != false){
			return mysql_fetch_array($this->resourceId,MYSQL_ASSOC);
		}
		return false;
	}
	function _free(){
		if($this->resourceId != false){
			mysql_free_result($this->resourceId);
		}
	}

	function _query($sql){
		$this->resourceId	= @mysql_query($sql,$this->connection);
		$errono				= @mysql_errno($this->connection);
		$error				= @mysql_error($this->connection);
		return array($errono,$error);
	}
	function _criteriaToSelectInWhere($columnObject,$criteria){
		return parent::_criteriaToSelectInWhere($columnObject,$criteria);
	}	
	function _selectColumnDateFormat($column){
		return "DATE_FORMAT(%s,'%%Y/%%m/%%d')";
	}
	function _selectColumnTimestampFormat($column){
		return "DATE_FORMAT(%s,'%%Y/%%m/%%d %%H:%%i:%%S')";
	}
	function _selectColumnBirthdayFormat($column){
		return ($column->dbtype) ? $this->_selectColumnDateFormat($column) : "%s";
	}
	function _whereILikePattern(){
		if(!empty($this->dbConnection->encode)){
			return " AND UPPER(CONVERT(%s using ".$this->dbConnection->encode.")) %s LIKE('%s') "; 
		}
		return " AND UPPER(%s) %s LIKE('%s') ";
	}
	function _insertId($tableObject){
		unset($tableObject);
		return mysql_insert_id($this->connection);
	}
	function _escape($value){
		if(extension_loaded("mysql")){
			return mysql_real_escape_string($value);
		}
		return addslashes($value);
	}
	function _generateSelectToTime($value,$column){
		return (empty($value) && $value !== "0") ? "NULL" : 
					(($column->dbtype) ? sprintf("'%s'",DateUtil::formatTime($value)) : intval($value));
	}
	function _generateSelectToBirthday($value,$column){
		return (empty($value)) ? "NULL" : 
					(($column->dbtype) ? sprintf("'%s'",DateUtil::formatDate($value)) : intval($value));
	}
}
?>