<?php
/**
 * リロード対策フィルター
 * 
 * @author Kazutaka Tokushima
 * @license New BSD License
 * @copyright Copyright 2008 rhaco project. All rights reserved.
 */
class HtmlOneTimeTicketFilter{
	function reset(&$request){
	}
	function validate(&$request){
		if($request->isPost() && $request->isSession("_onetimeticket") && $request->getVariable("_onetimeticket") != $request->getSession("_onetimeticket")){
			$request->raise(new IllegalStateException("one time ticket"));
		}
		$request->clearSession("_onetimeticket");
		$request->setVariable("_onetimeticket",uniqid("").mt_rand());
		$request->setSession("_onetimeticket",$request->getVariable("_onetimeticket"));
	}
	function after($src,&$parser){		
		if(preg_match("/form /i",$src) && SimpleTag::setof($tag,"<html_parser>".$src."</html_parser>","html_parser")){
			foreach($tag->getIn("form") as $obj){
				if(strtolower($obj->getParameter("method")) == "post"){
					$obj->setValue("<input type=\"hidden\" name=\"_onetimeticket\" value=\"{\$_onetimeticket}\" />".$obj->getValue());
					$src = str_replace($obj->getPlain(),$obj->get(),$src);
				}
			}
		}
		return $src;
	}
}
?>