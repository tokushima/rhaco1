<?php
class CustomFlowFilterBefore{
	function before($src,&$parser){
		return str_replace("xyz","ABC",$src);
	}
}
?>