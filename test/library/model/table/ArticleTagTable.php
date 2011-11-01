<?php
Rhaco::import("resources.Message");
Rhaco::import("database.model.TableObjectBase");
Rhaco::import("database.model.DbConnection");
Rhaco::import("database.TableObjectUtil");
Rhaco::import("database.model.Table");
Rhaco::import("database.model.Column");
/**
 * #ignore
 * 
 */
class ArticleTagTable extends TableObjectBase{
	/**  */
	var $id;
	/**  */
	var $articleId;
	/**  */
	var $tagId;
	var $category;
	var $factArticleId;
	var $factTagId;


	function ArticleTagTable($id=null){
		$this->__init__($id);
	}
	function __init__($id=null){
		$this->id = null;
		$this->articleId = null;
		$this->tagId = null;
		$this->category = null;
		$this->setId($id);
	}
	function connection(){
		if(!Rhaco::isVariable("_R_D_CON_","test")){
			Rhaco::addVariable("_R_D_CON_",new DbConnection("test"),"test");
		}
		return Rhaco::getVariable("_R_D_CON_",null,"test");
	}
	function table(){
		if(!Rhaco::isVariable("_R_D_T_","ArticleTag")){
			Rhaco::addVariable("_R_D_T_",new Table(Rhaco::constant("DATABASE_test_PREFIX")."article_tag",__CLASS__),"ArticleTag");
		}
		return Rhaco::getVariable("_R_D_T_",null,"ArticleTag");
	}


	/**
	 * 
	 * @return database.model.Column
	 */
	function columnId(){
		if(!Rhaco::isVariable("_R_D_C_","ArticleTag::Id")){
			$column = new Column("column=id,variable=id,type=serial,size=22,primary=true,",__CLASS__);
			$column->label(Message::_("id"));
			Rhaco::addVariable("_R_D_C_",$column,"ArticleTag::Id");
		}
		return Rhaco::getVariable("_R_D_C_",null,"ArticleTag::Id");
	}
	/**
	 * 
	 * @return serial
	 */
	function setId($value){
		$this->id = TableObjectUtil::cast($value,"serial");
	}
	/**
	 * 
	 */
	function getId(){
		return $this->id;
	}
	/**
	 * 
	 * @return database.model.Column
	 */
	function columnArticleId(){
		if(!Rhaco::isVariable("_R_D_C_","ArticleTag::ArticleId")){
			$column = new Column("column=article_id,variable=articleId,type=integer,size=22,require=true,unique=true,reference=Article::Id,uniqueWith=ArticleTag::TagId,",__CLASS__);
			$column->label(Message::_("article_id"));
			Rhaco::addVariable("_R_D_C_",$column,"ArticleTag::ArticleId");
		}
		return Rhaco::getVariable("_R_D_C_",null,"ArticleTag::ArticleId");
	}
	/**
	 * 
	 * @return integer
	 */
	function setArticleId($value){
		$this->articleId = TableObjectUtil::cast($value,"integer");
	}
	/**
	 * 
	 */
	function getArticleId(){
		return $this->articleId;
	}
	/**
	 * 
	 * @return database.model.Column
	 */
	function columnTagId(){
		if(!Rhaco::isVariable("_R_D_C_","ArticleTag::TagId")){
			$column = new Column("column=tag_id,variable=tagId,type=integer,size=22,require=true,unique=true,reference=Tag::Id,uniqueWith=ArticleTag::ArticleId,",__CLASS__);
			$column->label(Message::_("tag_id"));
			Rhaco::addVariable("_R_D_C_",$column,"ArticleTag::TagId");
		}
		return Rhaco::getVariable("_R_D_C_",null,"ArticleTag::TagId");
	}
	/**
	 * 
	 * @return integer
	 */
	function setTagId($value){
		$this->tagId = TableObjectUtil::cast($value,"integer");
	}
	/**
	 * 
	 */
	function getTagId(){
		return $this->tagId;
	}
	/**
	 * 
	 * @return database.model.Column
	 */
	function extraCategory(){
		if(!Rhaco::isVariable("_R_D_C_","ArticleTag::Category")){
			$column = new Column("column=category,variable=category,type=string,",__CLASS__);
			$column->label(Message::_("category"));
			Rhaco::addVariable("_R_D_C_",$column,"ArticleTag::Category");
		}
		return Rhaco::getVariable("_R_D_C_",null,"ArticleTag::Category");
	}
	/**
	 * 
	 * @return string
	 */
	function setCategory($value){
		$this->category = TableObjectUtil::cast($value,"string");
	}
	/**
	 * 
	 */
	function getCategory(){
		return $this->category;
	}


	function getFactArticleId(){
		return $this->factArticleId;
	}
	function setFactArticleId($obj){
		$this->factArticleId = $obj;
	}
	function getFactTagId(){
		return $this->factTagId;
	}
	function setFactTagId($obj){
		$this->factTagId = $obj;
	}
}
?>