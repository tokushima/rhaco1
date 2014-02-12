<?php
Rhaco::import("lang.model.AssertResult");
/**
 * #ignore
 * 検証結果クラス
 *
 * @author Kazutaka Tokushima
 * @license New BSD License
 * @copyright Copyright rhaco project. All rights reserved.
 */
class SeleniumTestResult extends AssertResult{
	var $command;
	var $params;
	var $response;
	var $expectation;


	function SeleniumTestResult(){
		$args = func_get_args();
		if(func_num_args()===2){
			$this->path = $args[0];
			$this->line = intval($args[1]);
		}elseif(func_num_args()===3){
			$this->type = $args[0];
			$this->path = $args[1];
			$this->line = intval($args[2]);
		}

	}
	function setCommand($class,$method,$command,$params=array(),$response=null){
		$this->class = $class;
		$this->method = $method;
		$this->command = $command;
		$this->params = $params;
		$this->response = preg_replace("/^OK\,?/","",$response);
		$counts = Rhaco::addVariable("RHACO_ARBO_SELENIUM_COUNT",Rhaco::getVariable("RHACO_ARBO_SELENIUM_COUNT",0,"selenium") + 1,"selenium");
		Rhaco::addVariable("RHACO_ARBO_SELENIUM",$this);
	}
	function setAssert($class,$method,$comment="",$expectation="",$result=""){
		$this->class = $class;
		$this->method = $method;
		$this->expectation = $expectation;
		$this->result = $result;
		$this->comment = $comment;
		$this->command = "assert";
		$counts = Rhaco::addVariable("RHACO_ARBO_SELENIUM_COUNT",Rhaco::getVariable("RHACO_ARBO_SELENIUM_COUNT",0,"selenium") + 1,"selenium");
		Rhaco::addVariable("RHACO_ARBO_SELENIUM",$this);
	}
	function setComment($comment=""){
		if(!empty($comment)){
			$var = Rhaco::getVariable("RHACO_ARBO_SELENIUM",null,Rhaco::getVariable("RHACO_ARBO_SELENIUM_COUNT",1,"selenium")-1);
			if(V::istype("SeleniumTestResult",$var)){
				$var->comment = $comment;
				Rhaco::addVariable("RHACO_ARBO_SELENIUM",$var,Rhaco::getVariable("RHACO_ARBO_SELENIUM_COUNT",1,"selenium")-1);
			}
		}
	}


	/**
	 * @static
	 *
	 * @return unknown
	 */
	function results(){
		/*** #pass */
		return Rhaco::getVariable("RHACO_ARBO_SELENIUM",array());
	}

	/**
	 * @static
	 *
	 */
	function clear(){
		Rhaco::clearVariable("RHACO_ARBO_SELENIUM");
		Rhaco::clearVariable("RHACO_ARBO_SELENIUM_COUNT");
	}

	/**
	 * @static
	 *
	 * @param unknown_type $type
	 */
	function count(){
		return Rhaco::getVariable("RHACO_ARBO_SELENIUM_COUNT");
	}
	function getCommand(){
		return $this->command;
	}

	function getParams(){
		return $this->params;
	}

	function getResponse(){
		return $this->response;
	}

	function getExpectation(){
		return $this->expectation;
	}
}
?>