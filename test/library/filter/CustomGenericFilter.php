<?php
Rhaco::import("generic.filter.ViewsFilter");

class CustomGenericFilter extends ViewsFilter {
	function afterCreate($object=null){
		Header::redirect(Rhaco::page("urls")."/hoge/".$object->getId());
	}
}
?>