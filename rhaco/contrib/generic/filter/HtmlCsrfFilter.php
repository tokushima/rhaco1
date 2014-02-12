<?php
Rhaco::import("exception.model.IllegalStateException");
Rhaco::import("lang.Variable");
/**
 * CSRF対策フィルター
 * 
 * @author Kazutaka Tokushima
 * @license New BSD License
 * @copyright Copyright 2008 rhaco project. All rights reserved.
 */
class HtmlCsrfFilter{
	var $_csrfid;
	function HtmlCsrfFilter(){
		if(isset($_SESSION["_csrfid"])){
			$this->_csrfid = $_SESSION["_csrfid"];
		}else{
			$this->_csrfid = Variable::uniqid("csrfid");
		}
	}
	/**
	 * reset
	 * @param Request $request
	 */
	function reset(&$request){
		if($request->isState()){
			$name = isset($_POST["_rsid"]) ? $_POST["_rsid"] : $_GET["_rsid"];
			if(isset($_SESSION[$name]["_csrfid"])) unset($_SESSION[$name]["_csrfid"]);
		}
		if(!isset($_SESSION["_csrfid"])){
			$request->setSession("_csrfid",$this->_csrfid);
		}
	}
	/**
	 * validate
	 * @param Request $request
	 */
	function validate(&$request){
		if($request->isPost()){
			if($request->isSession("_csrfid") && $request->getVariable("_csrfid") != $request->getSession("_csrfid")){
				$request->raise(new IllegalStateException("CSRF ID"));
			}
		}
		$request->clearVariable("_csrfid");
	}
	/**
	 * after
	 * @param string $src
	 * @param HtmlParser $parser
	 */
	function after($src,&$parser){
		if(preg_match("/form /i",$src) && SimpleTag::setof($tag,"<html_parser>".$src."</html_parser>","html_parser")){
			foreach($tag->getIn("form") as $obj){
				if(strtolower($obj->getParameter("method")) == "post"){
					$csrf = sprintf("<input type=\"hidden\" name=\"_csrfid\" value=\"%s\" />",$this->_csrfid);
					$obj->setValue($csrf.$obj->getValue());
					$src = str_replace($obj->getPlain(),$obj->get(),$src);
				}
			}
		}
		return $src;
	}
}
?>