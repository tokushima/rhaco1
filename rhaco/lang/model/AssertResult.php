<?php
/**
 * #ignore
 * 検証結果クラス
 * 
 * @author Kazutaka Tokushima
 * @license New BSD License
 * @copyright Copyright rhaco project. All rights reserved.
 */
class AssertResult{
	var $type;
	var $path;
	var $line;
	var $class;
	var $method;
	var $comment;
	var $result;
	
	/**
	 * 成功値
	 * @static
	 * @return int
	 */
	function typeSuccess(){
		return 1;
	}
	/**
	 * 失敗値
	 * @static
	 * @return int
	 */
	function typeFail(){
		return 2;
	}
	/**
	 * 未定義値
	 * @static
	 * @return int
	 */
	function typeNone(){
		return 3;
	}
	/**
	 * パス値
	 * @static
	 * @return int
	 */
	function typePass(){
		return 4;
	}
	/**
	 * 目視確認値
	 * @static
	 * @return int
	 */	
	function typeViewing(){
		return 5;
	}
	/**
	 * コンストラクタ
	 * @param integer $type
	 * @param string $path
	 * @param integer $line
	 */
	function AssertResult($type,$path,$line){
		$this->type = intval($type);
		$this->path = $path;
		$this->line = intval($line);
	}
	/**
	 * 結果を設定する
	 * @param string $class
	 * @param string $method
	 * @param string $comment
	 * @param integer $result
	 */
	function set($class,$method,$comment,$result){
		$this->comment = $comment;
		$this->class = $class;
		$this->method = $method;
		$this->result = $result;

		$counts = Rhaco::addVariable("RHACO_CORE_ASSERT_COUNT",Rhaco::getVariable("RHACO_CORE_ASSERT_COUNT",0,$this->type) + 1,$this->type);
		if($this->type != AssertResult::typeSuccess()) Rhaco::addVariable("RHACO_CORE_ASSERT",$this);
	}
	/**
	 * テスト結果を取得
	 * @static
	 * @return unknown
	 */
	function results(){
		/*** #pass */
		return Rhaco::getVariable("RHACO_CORE_ASSERT",array());
	}
	/**
	 * テスト結果をクリア
	 * @static
	 */
	function clear(){
		Rhaco::clearVariable("RHACO_CORE_ASSERT");
		Rhaco::clearVariable("RHACO_CORE_ASSERT_COUNT");
	}
	/**
	 * テスト数を取得
	 * @static
	 * @param integer $type
	 */
	function count($type=0){
		$counts = Rhaco::getVariable("RHACO_CORE_ASSERT_COUNT");
		if(empty($counts)) $counts = array();

		if($type == 0){
			$count = 0;
			foreach($counts as $c) $count += $c;
			return $count;
		}
		if(!isset($counts[$type])) return 0;
		return $counts[$type];
	}
	/**
	 * テストタイプを取得
	 */
	function getType(){
		return $this->type;
	}
	/**
	 * テスト対象ファイルパスを取得
	 * @return string
	 */
	function getPath(){
		return $this->path;
	}
	/**
	 * テスト対象行を取得
	 * @return integer
	 */
	function getLine(){
		return $this->line;
	}
	/**
	 * テスト対象メソッドを取得
	 */
	function getMethod(){
		return $this->method;
	}
	/**
	 * テスト対象クラスを取得
	 * @return string
	 */
	function getClass(){
		return $this->class;
	}
	/**
	 * テストコメントを取得
	 * @return string
	 */
	function getComment(){
		return $this->comment;
	}
	/**
	 * 結果を取得
	 * @return string
	 */
	function getResult(){
		return $this->result;
	}
	/**
	 * テスト結果タイプの文字列表現を取得
	 * @return string
	 */
	function getTypeString(){
		switch($this->type){
			case AssertResult::typeSuccess(): return "SUCCESS";
			case AssertResult::typeFail(): return "FAIL";
			case AssertResult::typePass(): return "PASS";;
			case AssertResult::typeNone(): return "NONE";
			case AssertResult::typeViewing(): return "VIEWING";
			default:
				return strval($this->type);
		}
	}
}
?>