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
class ArticleTable extends TableObjectBase{
	/**  */
	var $id;
	/** 文書区分 */
	var $articleType;
	/** 投稿日 */
	var $writeDate;
	/** タグ数 */
	var $tagCount;
	/** 文章 */
	var $body;
	/** 写真 */
	var $photo;
	var $dependArticleTags;
	var $factArticleType;


	function ArticleTable($id=null){
		$this->__init__($id);
	}
	function __init__($id=null){
		$this->id = null;
		$this->articleType = null;
		$this->writeDate = null;
		$this->tagCount = null;
		$this->body = null;
		$this->photo = null;
		$this->setId($id);
	}
	function connection(){
		if(!Rhaco::isVariable("_R_D_CON_","test")){
			Rhaco::addVariable("_R_D_CON_",new DbConnection("test"),"test");
		}
		return Rhaco::getVariable("_R_D_CON_",null,"test");
	}
	function table(){
		if(!Rhaco::isVariable("_R_D_T_","Article")){
			Rhaco::addVariable("_R_D_T_",new Table(Rhaco::constant("DATABASE_test_PREFIX")."article",__CLASS__),"Article");
		}
		return Rhaco::getVariable("_R_D_T_",null,"Article");
	}


	/**
	 * 
	 * @return database.model.Column
	 */
	function columnId(){
		if(!Rhaco::isVariable("_R_D_C_","Article::Id")){
			$column = new Column("column=id,variable=id,type=serial,size=22,primary=true,",__CLASS__);
			$column->label(Message::_("id"));
			$column->depend("ArticleTag::ArticleId");
			Rhaco::addVariable("_R_D_C_",$column,"Article::Id");
		}
		return Rhaco::getVariable("_R_D_C_",null,"Article::Id");
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
	 * 文書区分
	 * 
	 * @return database.model.Column
	 */
	function columnArticleType(){
		if(!Rhaco::isVariable("_R_D_C_","Article::ArticleType")){
			$column = new Column("column=article_type,variable=articleType,type=integer,size=22,require=true,reference=ArticleType::Id,",__CLASS__);
			$column->label(Message::_("article_type"));
			Rhaco::addVariable("_R_D_C_",$column,"Article::ArticleType");
		}
		return Rhaco::getVariable("_R_D_C_",null,"Article::ArticleType");
	}
	/**
	 * 文書区分
	 * 
	 * @return integer
	 */
	function setArticleType($value){
		$this->articleType = TableObjectUtil::cast($value,"integer");
	}
	/**
	 * 文書区分
	 * 
	 */
	function getArticleType(){
		return $this->articleType;
	}
	/**
	 * 投稿日
	 * 
	 * @return database.model.Column
	 */
	function columnWriteDate(){
		if(!Rhaco::isVariable("_R_D_C_","Article::WriteDate")){
			$column = new Column("column=write_date,variable=writeDate,type=timestamp,",__CLASS__);
			$column->label(Message::_("write_date"));
			Rhaco::addVariable("_R_D_C_",$column,"Article::WriteDate");
		}
		return Rhaco::getVariable("_R_D_C_",null,"Article::WriteDate");
	}
	/**
	 * 投稿日
	 * 
	 * @return timestamp
	 */
	function setWriteDate($value){
		$this->writeDate = TableObjectUtil::cast($value,"timestamp");
	}
	/**
	 * 投稿日
	 * 
	 */
	function getWriteDate(){
		return $this->writeDate;
	}
	/** 投稿日 */
	function formatWriteDate($format="Y/m/d H:i:s"){
		return DateUtil::format($this->writeDate,$format);
	}
	/**
	 * タグ数
	 * 
	 * @return database.model.Column
	 */
	function columnTagCount(){
		if(!Rhaco::isVariable("_R_D_C_","Article::TagCount")){
			$column = new Column("column=tag_count,variable=tagCount,type=integer,size=2,",__CLASS__);
			$column->label(Message::_("tag_count"));
			Rhaco::addVariable("_R_D_C_",$column,"Article::TagCount");
		}
		return Rhaco::getVariable("_R_D_C_",null,"Article::TagCount");
	}
	/**
	 * タグ数
	 * 
	 * @return integer
	 */
	function setTagCount($value){
		$this->tagCount = TableObjectUtil::cast($value,"integer");
	}
	/**
	 * タグ数
	 * 
	 */
	function getTagCount(){
		return $this->tagCount;
	}
	/**
	 * 文章
	 * 
	 * @return database.model.Column
	 */
	function columnBody(){
		if(!Rhaco::isVariable("_R_D_C_","Article::Body")){
			$column = new Column("column=text_word,variable=body,type=text,size=1000,",__CLASS__);
			$column->label(Message::_("text_word"));
			Rhaco::addVariable("_R_D_C_",$column,"Article::Body");
		}
		return Rhaco::getVariable("_R_D_C_",null,"Article::Body");
	}
	/**
	 * 文章
	 * 
	 * @return text
	 */
	function setBody($value){
		$this->body = TableObjectUtil::cast($value,"text");
	}
	/**
	 * 文章
	 * 
	 */
	function getBody(){
		return $this->body;
	}
	/**
	 * 写真
	 * 
	 * @return database.model.Column
	 */
	function columnPhoto(){
		if(!Rhaco::isVariable("_R_D_C_","Article::Photo")){
			$column = new Column("column=photo,variable=photo,type=string,size=255,",__CLASS__);
			$column->label(Message::_("photo"));
			Rhaco::addVariable("_R_D_C_",$column,"Article::Photo");
		}
		return Rhaco::getVariable("_R_D_C_",null,"Article::Photo");
	}
	/**
	 * 写真
	 * 
	 * @return string
	 */
	function setPhoto($value){
		$this->photo = TableObjectUtil::cast($value,"string");
	}
	/**
	 * 写真
	 * 
	 */
	function getPhoto(){
		return $this->photo;
	}


	function getFactArticleType(){
		return $this->factArticleType;
	}
	function setFactArticleType($obj){
		$this->factArticleType = $obj;
	}
	function setDependArticleTags($value){
		$this->dependArticleTags = $value;
	}
	function getDependArticleTags(){
		return $this->dependArticleTags;
	}
}
?>