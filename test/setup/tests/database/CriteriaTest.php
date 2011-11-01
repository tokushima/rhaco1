<?php
Rhaco::import("util.UnitTest");
Rhaco::import("database.DbUtil");
Rhaco::import("database.model.Criteria");
Rhaco::import("abbr.Q");
Rhaco::import("abbr.V");
Rhaco::import("abbr.L");

Rhaco::import("model.ArticleType");

class CriteriaTest extends UnitTest {
	var $db = null;
	function begin() {
		$this->db = new DbUtil(ArticleType::connection());
	}
	function finish() {
		$this->db->close();
	}
	
	function testAddCriteria(){
		$criteria = new Criteria(Q::in(ArticleType::columnId(),array(1,2)));
		$list = $this->db->select(new ArticleType(),$criteria);		
		$this->assertEquals(2,sizeof($list));

		$criteria = new Criteria(Q::in(ArticleType::columnId(),array(1,2)));
		$criteria->addCriteria(new Criteria(Q::eq(ArticleType::columnSortOrder(),1)));
		$list = $this->db->select(new ArticleType(),$criteria);
		$this->assertEquals(1,sizeof($list));
		
		$criteria = new C(Q::in(ArticleType::columnId(),array(1,2)));
		$criteria->andc(new C(Q::eq(ArticleType::columnSortOrder(),1)));
		$list = $this->db->select(new ArticleType(),$criteria);
		$this->assertEquals(1,sizeof($list));
	}
	
	function testAddCriteriaOr(){
		$criteria = new Criteria(Q::in(ArticleType::columnId(),array(1,2)));
		$list = $this->db->select(new ArticleType(),$criteria);
		$this->assertEquals(2,sizeof($list));

		$criteria = new C(Q::in(ArticleType::columnId(),array(1,2)));
		$criteria->orc(new C(Q::eq(ArticleType::columnSortOrder(),2)));
		$list = $this->db->select(new ArticleType(),$criteria);
		$this->assertEquals(3,sizeof($list));
	}
	
}
?>