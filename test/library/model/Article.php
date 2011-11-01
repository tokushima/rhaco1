<?php
Rhaco::import("model.table.ArticleTable");
class Article extends ArticleTable{
	function admin(){
		/***
		 * $obj = new Article();
		 * assert(is_array($obj->admin()));
		 */
		return array("reference_criteria"=>array(
						"article_type"=>"criteriaType",
//						"article_type"=>new Criteria(Q::in(ArticleType::columnId(),array(1,2,3)))
						)
				);
	}
	
	function criteriaType(){
		/***
		 * $obj = new Article();
		 * assert(Variable::istype("Criteria",$obj->criteriaType()));
		 */
		return new Criteria(Q::in(ArticleType::columnId(),array(1,3)));
	}
}

?>