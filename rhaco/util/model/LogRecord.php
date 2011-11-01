<?php
Rhaco::import("io.FileUtil");
/**
 * ログのデータモデル
 * 
 * @author Kazutaka Tokushima
 * @license New BSD License
 * @copyright Copyright 2008- rhaco project. All rights reserved.
 */
class LogRecord{
	var $level;
	var $time;
	var $file;
	var $line;
	var $value;
	
	/**
	 * コンストラクタ
	 * @param integer $level
	 * @param string $value
	 * @param string $file
	 * @param integer $line
	 * @param integer $time
	 */
	function LogRecord($level,$value,$file=null,$line=null,$time=null){
		if($file === null){
			$debugs = debug_backtrace();
			if(sizeof($debugs) > 2){
				list($dumy,$debug,$op) = debug_backtrace();
			}else{
				list($dumy,$debug) = debug_backtrace();
			}
			$file = FileUtil::parseFilename(isset($debug["file"]) ? $debug["file"] : $dumy["file"]);
			$line = (isset($debug["line"]) ? $debug["line"] : $dumy["line"]);
			$class = (isset($op["class"]) ? $op["class"] : $dumy["class"]);			
			if(!Rhaco::constant("RHACO_CORE_LOGGER_FULLPATH",false)) $file = str_replace(array(Rhaco::lib(),Rhaco::rhacopath()),array("",""),$file);
		}
		$this->level = $level;
		$this->value = $value;
		$this->file = (strpos($file,"eval()'d") !== false) ? $class : $file;
		$this->line = intval($line);
		$this->time = ($time === null) ? time() : $time;
		$this->class = $class;
	}
	/**
	 * ログレベルを取得
	 * @return integer
	 */
	function getLevel(){
		return $this->level;
	}
	/**
	 * ログを取得
	 * @param boolean $format
	 * @return string
	 */
	function getValue($format=true){
		if($format){
			if(!is_string($this->value)){
				ob_start();
					var_dump($this->value);
				return ob_get_clean();
			}
		}
		return $this->value;
	}
	/**
	 * ファイルを取得
	 * @return string
	 */
	function getFile(){
		return $this->file;
	}
	/**
	 * 行数を取得
	 * @return integer
	 */
	function getLine(){
		return $this->line;
	}
	/**
	 * 時刻を取得
	 * @param boolean $format
	 * @return mixed
	 */
	function getTime($format="Y/m/d H:i:s"){
		return (empty($format)) ? $this->time : date($format,$this->time);
	}
}
?>