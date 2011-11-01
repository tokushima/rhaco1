<?php
class CustomFlowFilter{
	function after($src,&$parser){
		return str_replace("hoge","HOGE",$src);
	}
}
?>