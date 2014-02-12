<?php
/**
 * Urlsの検証用アクション
 */
include_once("__init__.php");
Rhaco::import("generic.Urls");
Rhaco::import("model.Article");

$article = new Article();
$parser = Urls::parser(array(
				"^read"=>array(
							"method"=>"read",
							"args"=>array($article),
							"default"=>true
						),
				"^detail/(\d+)"=>array(
							"method"=>"detail",
							"args"=>array($article),
						),
				"^create"=>array(
							"method"=>"create",
							"args"=>array($article,array(Rhaco::page("urls"),true))
						),
				"^ccreate"=>array(
							"method"=>"confirmedCreate",
							"args"=>array($article,null,"filter.CustomGenericFilter")
						),
				"^update/(\d+)"=>array(
							"method"=>"update",
							"args"=>array($article,null,Rhaco::page("urls"))
						),
				"^cupdate/(\d+)"=>array(
							"method"=>"update",
							"args"=>array($article,null,Rhaco::page("urls"))
						),
				"^drop/(\d+)"=>array(
							"method"=>"drop",
							"args"=>array($article,null,Rhaco::page("urls"))
						),
				"^noarg/(\d+)/(\d+)"=>array(
							"method"=>"newmethod"
						),
			));
$parser->write();

function newmethod($arg1,$arg2){
	Header::redirect(Rhaco::page("urls")."/".$arg1."?id=".$arg2);	
}
?>