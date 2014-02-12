<?php
Rhaco::import("network.http.Http");
/**
 * XmlrpcClient
 * @author SHIGETA Takeshiro
 * 
 * @license New BSD License
 * @copyright Copyright 2006- rhaco.org All rights reserved.
 */
class XmlrpcClient {
 /**
  * 基本的に値の設定はpushメソッド、リクエストの発信はrequestメソッドで行います
  * 例：
  * $rpc = new XmlrpcClient("http://rhaco.org/");
  * $rpc->setMethod("helloWorld");
  * $rpc->push(1);
  * $rpc->push("hoge");
  * $rpc->push(array(0,1,2));
  * $rpc->request();
  * またbase64エンコードしたバイナリデータを入れたい場合は
  * push前に
  * $rpc->defineAdBinary(エンコードしたデータ);
  * とすることで指定できます。
  */
	
	var $url;
	var $methodName;
	var $params;
	var $binary = array();
	
	function XmlrpcClient ($url="") {
	/***
	* $xmlrpc = new XmlrpcClient();
	* $xmlrpc->setMethod("bookmark.getCount");
	* //$struct = array(0=>array("name"=>"hoge","value"=>array("int",80)));
	* //$struct = array(0=>array("name"=>"hoge","value"=>array("struct",array(0=>array("name"=>"huga","value"=>array("string","xml"))))));
	* $struct = new stdClass();
	* $struct->a = "b";
	* $struct->i = 1;
	* $struct->b = true;
	* $struct->o->f = "first";
	* $struct->o->s = "second";
	* $struct->o->t = "third";
	* //$xmlrpc->setParameter("string","http://b.hatena.ne.jp/");
	* //$xmlrpc->setParameter("struct",$struct);
	* $xmlrpc->setStructure($struct);
	* $xmlrpc->request();
	* $tag = new SimpleTag();
	* $tag->set($xmlrpc->request());
	* var_dump($tag);
	* foreach($tag->getIn("member") as $param) {
	* 	var_dump($param->getInValue("name"));
	* 	var_dump($param->getInValue("value"));
	*/
				$this->url = $url;
	}
	
	function setMethod($methodname) {
		$this->methodName = $methodname;
	}
		
	function push ($param,$name="") {
		if(is_object($param) || (is_array($param) && !array_flip($param))) {
			$results = array("struct",array());
			$this->_setStructure($param,$results);
			$this->params[] = $results;
		}else{
			if(is_array($param)) {
				foreach($param as $key=>$var) {
					$array = $this->prepare($var);
					$this->params[] = array("array",$array);
				}
			}else{
				$this->params[] = $this->prepare($param,$name);
			}
		}
	}
	
	function prepare ($param,$name="") {
		if(is_bool($param)) {
			$type = "bool";
		}elseif(is_int($param)) {
			$type = "int";
		}elseif(is_double($param)) {
			$type = "double";
		}elseif($this->isBinary($param)) {
			$type = "base64";					
		}elseif (is_string($param)) {
			$type = "string";
		}
		return array($type,$param);
	}
	
	function isBinary ($param) {
		return in_array($param,$this->binary);
	}
	
	function defineAsBinary ($param) {
		$this->binary[] = $param;
	}

	function clear () {
		$this->params = array();
	}
	
	function request ($url = "") {
		if(empty($url)) {
			if(empty($this->url)) {
				return false;
			}else{
				$url = $this->url;
			}
		}
		if(get_class($this)) {
			$methodName = $this->methodName;
			$values = "";
			foreach($this->params as $param) {
				$values = $values."<param>\n".$this->getParameter($param)."</param>\n";
			}
			$values = "<params>\n$values</params>\n";
		}
		$post_data = "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n<methodCall>\n<methodName>".$methodName."</methodName>\n".$values."\n</methodCall>";
		return Http::body($url,"POST",array("type"=>"text/xml","rawdata"=>$post_data));
	}
	
	function getParameter(&$param) {
		switch ($param[0]) {
			case "bool":
				return "<value><bool>${param[1]}</bool></value>\n";
			case "int":
				return "<value><int>${param[1]}</int></value>\n";
			case "string":
				return "<value><string>${param[1]}</string></value>\n";
			case "base64":
				return "<value><base64>${param[1]}</base64></value>\n";
			case "array":
				$str = "<array><data>\n";
				foreach($param[1] as $data) {
					$str = $str."<value><${data[type]}>${data[value]}</${data[type]}></value>\n";
				}
				return $str."</data></array>\n";
			case "struct":
				$str = "<value><struct>";
				foreach($param[1] as $data) {
					$str = $str."<member>\n";
					if($data[0] === "struct") {
					$str = $str."<struct>\n".$this->getParameter($data[1])."</struct>\n";
						
					}else{
					$str = $str."<name>${data[0]}</name>\n".$this->getParameter($data[1]);
					}
					$str = $str."</member>\n";
				}
				return $str."</struct></value>\n";
		
			default:
				break;
		}
	}


	function _setStructure (&$params,&$results) {
		foreach($params as $key=>$var) {
			if(is_object($var) || (is_array($var) && !array_flip($var))) {
				$this->_setStructure ($var, $dummy);
				$results[1][]=array($key,array("struct",array_pop($dummy)));
			}elseif(is_array($var)) {
				foreach($var as $akey=>$avar) {
					$array[] = array($akey,$this->prepare($avar));
				}
				$results[1][]=array($key,array("key",$array));
			}else{
				$results[1][]=array($key,$this->prepare($var));
			}
		}
	}
}

class XmlrpcServer {

	function set() {
		
	}
	
	function getMethod () {
		
	}
	
	function getParameters () {
		
	}
	
	function sendResponse () {
		
	}
	
	function sendFault ($code,$string) {
		
	}
}
?>