<?php
Rhaco::import("model.table.ArticleTagTable");
class ArticleTag extends ArticleTagTable{
	
	function viewsModelTestMethod(){
		/***
		 * $obj = new ArticleTag();
		 * assert(is_array($obj->viewsModelTestMethod()));
		 */
		return array("hoge"=>"id,tagId",
					"kaeru"=>"tagId,category",
					);
	}
}

?>