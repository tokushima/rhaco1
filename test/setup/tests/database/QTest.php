<?php
Rhaco::import("database.DbUtil");
Rhaco::import("database.model.Criteria");
Rhaco::import("abbr.Q");
Rhaco::import("abbr.V");
Rhaco::import("abbr.L");

Rhaco::import("model.Article");
Rhaco::import("model.ArticleType");
Rhaco::import("model.Tag");
Rhaco::import("model.ArticleTag");
Rhaco::import("model.Item");

Rhaco::import("model.FactTest");
Rhaco::import("model.FactTestRef");
Rhaco::import("model.FactTestExt");
Rhaco::import("model.MapTest");
Rhaco::import("model.DependTest");

Rhaco::import("model.NumOnly");

class QTest extends UnitTest {
	var $db = null;
	function begin() {
		$this->db = new DbUtil(ArticleType::connection());
		
		$tag1 = V::ho(array("name" => "rhaco"), new Tag());
		$tag2 = V::ho(array("name" => "D_jango"), new Tag());
		$tag3 = V::ho(array("name" => "hentai++"), new Tag());
		$tag1->save($this->db);
		$tag2->save($this->db);
		$tag3->save($this->db);

		$a1 = V::ho(
					array("articleType" => 1,
						  "write_date" => "2007/08/01",
						  "body" => "久々に会った友達が、「カクタニさんに東京の暗黒面を見せたことがある」と言っていた",
						  "photo" => "var/image/001.jpg"),
								new Article());
		$a2 = V::ho(
					array("articleType" => 3,
						  "write_date" => "2007/07/31",
						  "body" => "ǝunsʇo ıɯnɟɐsɐɯ: そろそろ地方と都会問題のいっかんとして「赤坂的な集まりなんてちっとも良くない」みたいなDISを言い出す輩が出るはず",
						  "photo" => ""),
								new Article());
		$a3 = V::ho(
					array("articleType" => 1,
						  "write_date" => "2006/08/01",
						  "body" => "10年くらい前にすげーかわいいアルゼンチーナがいてさぁ、国に帰ったら結婚するんだって言っててさぁ…",
						  "photo" => ""),
								new Article());
		$a4 = V::ho(
					array("articleType" => 2,
						  "write_date" => "2011/08/01",
						  "body" => "やっとdashboardが見えた。死ぬかと思った。",
						  "photo" => "var/j g/001.jpg"),
								new Article());
		$a1->save($this->db);
		$a2->save($this->db);
		$a3->save($this->db);
		$a4->save($this->db);

		$item1 = V::ho(
					array("name" => "Pro Django",
						  "price" => 44.99),
								new Item());
		$item2 = V::ho(
					array("name" => "Pro CSS",
						  "price" => 29.69),
								new Item());
		$item3 = V::ho(
					array("name" => "LLフレームワーク Django",
						  "price" => 1980),
								new Item());
		$item4 = V::ho(
					array("name" => "Monomi",
						  "price" => -500),
								new Item());
		$item1->save($this->db);
		$item2->save($this->db);
		$item3->save($this->db);
		$item4->save($this->db);
	}
	function finish() {
		$this->db->alldelete(new Article());
		$this->db->alldelete(new Tag());
		$this->db->close();
		$this->db = null;
	}
	function setup() {
	}
	function testSelectSimple() {
		$result = $this->db->select(new Tag());
		$this->assertEquals(3, sizeof($result));
	}
	function testSelectEq() {
		$result = $this->db->select(new Tag(), new Criteria(Q::eq(Tag::columnName(), "rhaco")));
		$this->assertEquals(1, sizeof($result));
		
		if(sizeof($result) == 1){
			$this->assertEquals($result[0]->name, "rhaco");
		}
		$result = $this->db->select(new Tag(), new Criteria(Q::eq(Tag::columnName(), "hentai++")));
		
		$this->assertEquals(1, sizeof($result));
		if(sizeof($result) == 1){
			$this->assertEquals($result[0]->name, "hentai++");
		}
	}
	
	function testSelectEquals() {
		$result = $this->db->select(new Tag(), new Criteria(Q::equal(Tag::columnName(), "rhaco")));
		$this->assertEquals(1, sizeof($result));
		
		if(sizeof($result) == 1){
			$this->assertEquals($result[0]->name, "rhaco");
		}
		$result = $this->db->select(new Tag(), new Criteria(Q::equal(Tag::columnName(), "hentai++")));
		
		$this->assertEquals(1, sizeof($result));
		if(sizeof($result) == 1){
			$this->assertEquals($result[0]->name, "hentai++");
		}
	}
	function testSelectEqualsComp() {
		$result = $this->db->select(new Tag(), new Criteria(Q::comp(Tag::columnName(),"==","rhaco")));
		$this->assertEquals(1, sizeof($result));
		
		if(sizeof($result) == 1){
			$this->assertEquals($result[0]->name, "rhaco");
		}
		$result = $this->db->select(new Tag(), new Criteria(Q::comp(Tag::columnName(),"==","hentai++")));
		
		$this->assertEquals(1, sizeof($result));
		if(sizeof($result) == 1){
			$this->assertEquals($result[0]->name, "hentai++");
		}
	}

	function testSelectEqualsNoMatch() {
		$result = $this->db->select(new Tag(), new Criteria(Q::eq(Tag::columnName(), "Rhaco")));
		$this->assertEquals(0, sizeof($result));

		$result = $this->db->select(new Tag(), new Criteria(Q::eq(Tag::columnName(), "")));
		$this->assertEquals(0, sizeof($result));
		
		$result = $this->db->select(new Tag(), new Criteria(Q::equal(Tag::columnName(), "Rhaco")));
		$this->assertEquals(0, sizeof($result));

		$result = $this->db->select(new Tag(), new Criteria(Q::equal(Tag::columnName(), "")));
		$this->assertEquals(0, sizeof($result));		
	}

	function testSelectNeq() {
		$result = $this->db->select(new Tag(), new Criteria(Q::neq(Tag::columnName(), "rhaco")));
		$this->assertEquals(2, sizeof($result));
		foreach($result as $r) {
			$this->assertNotEquals($r->name, "rhaco");
		}
		
		$result = $this->db->select(new Tag(), new Criteria(Q::neq(Tag::columnName(), "hentai++")));
		
		$this->assertEquals(2, sizeof($result));
		foreach($result as $r) {
			$this->assertNotEquals($r->name, "hentai++");
		}
	}
	function testSelectNotEquals() {
		$result = $this->db->select(new Tag(), new Criteria(Q::notEqual(Tag::columnName(), "rhaco")));
		$this->assertEquals(2, sizeof($result));
		foreach($result as $r) {
			$this->assertNotEquals($r->name, "rhaco");
		}
		
		$result = $this->db->select(new Tag(), new Criteria(Q::notEqual(Tag::columnName(), "hentai++")));
		
		$this->assertEquals(2, sizeof($result));
		foreach($result as $r) {
			$this->assertNotEquals($r->name, "hentai++");
		}
	}
	function testSelectNotEqualsComp() {
		$result = $this->db->select(new Tag(), new Criteria(Q::comp(Tag::columnName(),"!=","rhaco")));
		$this->assertEquals(2, sizeof($result));
		foreach($result as $r) {
			$this->assertNotEquals($r->name, "rhaco");
		}
		
		$result = $this->db->select(new Tag(), new Criteria(Q::comp(Tag::columnName(),"!=","hentai++")));
		
		$this->assertEquals(2, sizeof($result));
		foreach($result as $r) {
			$this->assertNotEquals($r->name, "hentai++");
		}
	}
	
	function testSelectGreater() {
		$result = $this->db->select(new ArticleType(), new Criteria(Q::gt(ArticleType::columnSortOrder(), 1)));
		$this->assertEquals(2, sizeof($result));
		foreach($result as $r) {
			$this->assertTrue($r->sortOrder > 1);
		}
		
		$result = $this->db->select(new ArticleType(), new Criteria(Q::greater(ArticleType::columnSortOrder(), 1)));
		$this->assertEquals(2, sizeof($result));
		foreach($result as $r) {
			$this->assertTrue($r->sortOrder > 1);
		}
		$result = $this->db->select(new ArticleType(), new Criteria(Q::comp(ArticleType::columnSortOrder(),">",1)));
		$this->assertEquals(2, sizeof($result));
		foreach($result as $r) {
			$this->assertTrue($r->sortOrder > 1);
		}		
		
	}

	function testSelectGreaterEquals() {
		$result = $this->db->select(new ArticleType(), new Criteria(Q::gte(ArticleType::columnSortOrder(), 2)));
		$this->assertEquals(2, sizeof($result));
		foreach($result as $r) {
			$this->assertTrue($r->sortOrder >= 2);
		}

		$result = $this->db->select(new ArticleType(), new Criteria(Q::greaterEquals(ArticleType::columnSortOrder(), 2)));
		$this->assertEquals(2, sizeof($result));
		foreach($result as $r) {
			$this->assertTrue($r->sortOrder >= 2);
		}
		$result = $this->db->select(new ArticleType(), new Criteria(Q::comp(ArticleType::columnSortOrder(),">=",2)));
		$this->assertEquals(2, sizeof($result));
		foreach($result as $r) {
			$this->assertTrue($r->sortOrder >= 2);
		}
	}

	function testSelectLess() {
		$result = $this->db->select(new ArticleType(), new Criteria(Q::lt(ArticleType::columnSortOrder(), 3)));
		$this->assertEquals(2, sizeof($result));
		foreach($result as $r) {
			$this->assertTrue($r->sortOrder < 3);
		}
		
		$result = $this->db->select(new ArticleType(), new Criteria(Q::less(ArticleType::columnSortOrder(), 3)));
		$this->assertEquals(2, sizeof($result));
		foreach($result as $r) {
			$this->assertTrue($r->sortOrder < 3);
		}
		$result = $this->db->select(new ArticleType(), new Criteria(Q::comp(ArticleType::columnSortOrder(),"<",3)));
		$this->assertEquals(2, sizeof($result));
		foreach($result as $r) {
			$this->assertTrue($r->sortOrder < 3);
		}
	}

	function testSelectLessEquals() {
		$result = $this->db->select(new ArticleType(), new Criteria(Q::lte(ArticleType::columnSortOrder(), 2)));
		$this->assertEquals(2, sizeof($result));
		foreach($result as $r) {
			$this->assertTrue($r->sortOrder <= 2);
		}
		
		$result = $this->db->select(new ArticleType(), new Criteria(Q::lessEquals(ArticleType::columnSortOrder(), 2)));
		$this->assertEquals(2, sizeof($result));
		foreach($result as $r) {
			$this->assertTrue($r->sortOrder <= 2);
		}
		$result = $this->db->select(new ArticleType(), new Criteria(Q::comp(ArticleType::columnSortOrder(),"<=",2)));
		$this->assertEquals(2, sizeof($result));
		foreach($result as $r) {
			$this->assertTrue($r->sortOrder <= 2);
		}
	}
	
	function testSelectLikeStartsWith() {
		// rhaco converts '.' to '_' 
		$result = $this->db->select(new Tag(), new Criteria(Criterion::like(Tag::columnName(), "rhac.")));
		$this->assertEquals(1, sizeof($result));
		foreach($result as $r) {
			$this->assertTrue(strpos($r->name, "rhac") !== false && strpos($r->name, "rhac") === 0 && $r->name !== "rhac");
		}

		// rhaco converts '.*' to '%'

		$result = $this->db->select(new Tag(), new Criteria(Criterion::like(Tag::columnName(), "D_.*")));
		$this->assertEquals(1, sizeof($result));

		foreach($result as $r) {
			$this->assertTrue(strpos($r->name, "D_") !== false && strpos($r->name, "D_") === 0);
		}

		$result = $this->db->select(new Tag(), new Criteria(Criterion::like(Tag::columnName(), "rhaC.")));
		$this->assertEquals(0, sizeof($result),"This test failed about about the problem of DB in SQLite");
	}
	function testSelectLikeEndWith() {
		// rhaco converts '.' to '_' 
		$result = $this->db->select(new Tag(), new Criteria(Criterion::like(Tag::columnName(), ".*aco")));
		$this->assertEquals(1, sizeof($result));
		foreach($result as $r) {
			$this->assertTrue(strpos($r->name, "aco") !== false && strpos($r->name, "aco") >= 0 && $r->name !== "aco");
		}

		$result = $this->db->select(new Tag(), new Criteria(Criterion::like(Tag::columnName(), ".*ac")));
		$this->assertEquals(0, sizeof($result));

		// rhaco converts '.*' to '%'
		$result = $this->db->select(new Tag(), new Criteria(Criterion::like(Tag::columnName(), "._jango")));
		$this->assertEquals(1, sizeof($result));
		foreach($result as $r) {
			$this->assertTrue(strpos($r->name, "_jango") !== false && strpos($r->name, "_jango") >= 0 && $r->name !== "_jango");
		}

		$result = $this->db->select(new Tag(), new Criteria(Criterion::like(Tag::columnName(), ".*aCo")));
		$this->assertEquals(0, sizeof($result),"This test failed about about the problem of DB in SQLite");
	}
	function testSelectLike() {
		// rhaco converts '.' to '_' 
		$result = $this->db->select(new Tag(), new Criteria(Criterion::like(Tag::columnName(), ".*ac.*")));
		$this->assertEquals(1, sizeof($result));
		foreach($result as $r) {
			$this->assertTrue(strpos($r->name, "ac") !== false && strpos($r->name, "ac") >= 0 && $r->name !== "ac");
		}

		// rhaco converts '.*' to '%'
		$result = $this->db->select(new Tag(), new Criteria(Criterion::like(Tag::columnName(), "._jang.")));
		$this->assertEquals(1, sizeof($result));
		foreach($result as $r) {
			$this->assertTrue(strpos($r->name, "_jang") !== false && strpos($r->name, "_jang") >= 0 && $r->name !== "_jang");
		}

		$result = $this->db->select(new Tag(), new Criteria(Criterion::like(Tag::columnName(), ".*aC.*")));
		$this->assertEquals(0, sizeof($result),"This test failed about about the problem of DB in SQLite");
	}

	function testSelectNotLikeStartsWith() {
		// rhaco converts '.' to '_' 
		$result = $this->db->select(new Tag(), new Criteria(Criterion::notLike(Tag::columnName(), "rhac.")));
		$this->assertTrue(sizeof($result) > 0);
		foreach($result as $r) {
			$this->assertTrue(strpos($r->name, "rhac") === false || strpos($r->name, "rhac") > 0 && $r->name !== "rhac");
		}

		// rhaco converts '.*' to '%'
		$result = $this->db->select(new Tag(), new Criteria(Criterion::notLike(Tag::columnName(), "D_.*")));
		$this->assertTrue(sizeof($result) > 0);
		foreach($result as $r) {
			$this->assertTrue(strpos($r->name, "D_") === false || strpos($r->name, "D_") > 0);
		}

		$result = $this->db->select(new Tag(), new Criteria(Criterion::notLike(Tag::columnName(), "d_.*")));
		$this->assertEquals(3, sizeof($result),"This test failed about about the problem of DB in SQLite");
	}
	function testSelectNotLikeEndWith() {
		// rhaco converts '.' to '_' 
		$result = $this->db->select(new Tag(), new Criteria(Criterion::notLike(Tag::columnName(), ".*aco")));
		$this->assertTrue(sizeof($result) > 0);
		foreach($result as $r) {
			$this->assertTrue(strpos($r->name, "aco") !== strlen($r->name) - 3);
		}

		$result = $this->db->select(new Tag(), new Criteria(Criterion::notLike(Tag::columnName(), ".*ac")));
		$this->assertTrue(sizeof($result) > 0);
		foreach($result as $r) {
			$this->assertTrue(strpos($r->name, "ac") === false || strpos($r->name, "ac") !== strlen($r->name) -2);
		}

		// rhaco converts '.*' to '%'
		$result = $this->db->select(new Tag(), new Criteria(Criterion::notLike(Tag::columnName(), "._jango")));
		$this->assertTrue(sizeof($result) > 0);
		foreach($result as $r) {
			$this->assertTrue(strpos($r->name, "_jango") === false && strlen($r->name) !== 7);
		}

		$result = $this->db->select(new Tag(), new Criteria(Criterion::notLike(Tag::columnName(), ".*aCo")));
		$this->assertEquals(3, sizeof($result),"This test failed about about the problem of DB in SQLite");
	}
	function testSelectNotLike() {
		// rhaco converts '.' to '_' 
		$result = $this->db->select(new Tag(), new Criteria(Criterion::notLike(Tag::columnName(), ".*ac.*")));
		$this->assertTrue(sizeof($result) > 0);
		foreach($result as $r) {
			$this->assertTrue(strpos($r->name, "ac") === false);
		}

		// rhaco converts '.*' to '%'
		$result = $this->db->select(new Tag(), new Criteria(Criterion::notLike(Tag::columnName(), "._jang.")));
		$this->assertTrue(sizeof($result) > 0);
		foreach($result as $r) {
			$this->assertTrue(strpos($r->name, "_jang") === false && strlen($r->name) !== 7);
		}

		$result = $this->db->select(new Tag(), new Criteria(Criterion::notLike(Tag::columnName(), ".*aC.*")));
		$this->assertEquals(3, sizeof($result),"This test failed about about the problem of DB in SQLite");
	}


	function testSelectiLikeStartsWith() {
		// rhaco converts '.' to '_' 
		$result = $this->db->select(new Tag(), new Criteria(Criterion::ilike(Tag::columnName(), "rHac.")));
		$this->assertEquals(1, sizeof($result));
		foreach($result as $r) {
			$this->assertTrue(strpos(strtolower($r->name), "rhac") !== false && strpos(strtolower($r->name), "rhac") === 0 && strtolower($r->name) !== "rhac");
		}

		// rhaco converts '.*' to '%'
		$result = $this->db->select(new Tag(), new Criteria(Criterion::ilike(Tag::columnName(), "d_.*")));
		$this->assertEquals(1, sizeof($result));
		foreach($result as $r) {
			$this->assertTrue(strpos(strtolower($r->name), "d_") !== false && strpos(strtolower($r->name), "d_") === 0);
		}
 
		$result = $this->db->select(new Tag(), new Criteria(Criterion::ilike(Tag::columnName(), "rhaC.")));
		$this->assertEquals(1, sizeof($result));
	}
	function testSelectiLikeEndWith() {
		// rhaco converts '.' to '_' 
		$result = $this->db->select(new Tag(), new Criteria(Criterion::ilike(Tag::columnName(), ".*aCo")));
		$this->assertEquals(1, sizeof($result));
		foreach($result as $r) {
			$this->assertTrue(strpos(strtolower($r->name), "aco") !== false && strpos(strtolower($r->name), "aco") >= 0 && strtolower($r->name) !== "aco");
		}

		$result = $this->db->select(new Tag(), new Criteria(Criterion::ilike(Tag::columnName(), ".*aC")));
		$this->assertEquals(0, sizeof($result));

		// rhaco converts '.*' to '%'
		$result = $this->db->select(new Tag(), new Criteria(Criterion::ilike(Tag::columnName(), "._janGo")));
		$this->assertEquals(1, sizeof($result));
		foreach($result as $r) {
			$this->assertTrue(strpos(strtolower($r->name), "_jango") !== false && strpos(strtolower($r->name), "_jango") >= 0 && strtolower($r->name) !== "_jango");
		}

		$result = $this->db->select(new Tag(), new Criteria(Criterion::ilike(Tag::columnName(), ".*aCo")));
		$this->assertEquals(1, sizeof($result));
	}
	function testSelectiLike() {
		// rhaco converts '.' to '_' 
		$result = $this->db->select(new Tag(), new Criteria(Criterion::ilike(Tag::columnName(), ".*aC.*")));
		$this->assertEquals(1, sizeof($result));
		foreach($result as $r) {
			$this->assertTrue(strpos(strtolower($r->name), "ac") !== false && strpos(strtolower($r->name), "ac") >= 0 && strtolower($r->name) !== "ac");
		}

		// rhaco converts '.*' to '%'
		$result = $this->db->select(new Tag(), new Criteria(Criterion::ilike(Tag::columnName(), "._janG.")));
		$this->assertEquals(1, sizeof($result));
		foreach($result as $r) {
			$this->assertTrue(strpos(strtolower($r->name), "_jang") !== false && strpos(strtolower($r->name), "_jang") >= 0 && strtolower($r->name) !== "_jang");
		}

		$result = $this->db->select(new Tag(), new Criteria(Criterion::ilike(Tag::columnName(), ".*aC.*")));
		$this->assertEquals(1, sizeof($result));
	}
	function testSelectLikeInStartWith() {
		$result = $this->db->select(new ArticleType(), new Criteria(Q::pattern(array(ArticleType::columnName(), ArticleType::columnDescription()), array("rha.*"))));
		$this->assertEquals(1, sizeof($result));
		foreach($result as $r) {
			$this->assertTrue(
							  (strpos($r->name, "rha") !== false && strpos($r->name, "rha") === 0)
							  ||
							  (strpos($r->description, "rha") !== false && strpos($r->description, "rha") === 0)
			);
		}
		$result = $this->db->select(new ArticleType(), new Criteria(Q::pattern(array(ArticleType::columnName(), ArticleType::columnDescription()), array("Rha.*"))));
		$this->assertEquals(0, sizeof($result),"This test failed about about the problem of DB in SQLite");
	}
	function testSelectLikeInEndWith() {
		$result = $this->db->select(new ArticleType(), new Criteria(Q::pattern(array(ArticleType::columnName(), ArticleType::columnDescription()), array(".*che"))));
		$this->assertEquals(1, sizeof($result));
		foreach($result as $r) {
			$this->assertTrue(
							  (strpos($r->name, "che") !== false && strpos($r->name, "che") === strlen($r->name) -3)
							  ||
							  (strpos($r->description, "che") !== false && strpos($r->description, "che") === strlen($r->description) - 3)
			);
		}
		$result = $this->db->select(new ArticleType(), new Criteria(Q::pattern(array(ArticleType::columnName(), ArticleType::columnDescription()), array(".*Che"))));
		$this->assertEquals(0, sizeof($result),"This test failed about about the problem of DB in SQLite");
	}
	function testSelectLikeIn() {
		$result = $this->db->select(new ArticleType(), new Criteria(Q::pattern(array(ArticleType::columnName(), ArticleType::columnDescription()), array(".*ha.*", ".*ラコ.*", ".*説明.*"))));
		$this->assertEquals(1, sizeof($result));
		foreach($result as $r) {
			$this->assertTrue(
							  ((strpos($r->name, "ha") !== false && strpos($r->name, "ha") >= 0)
							   || (strpos($r->name, "ラコ") !== false && strpos($r->name, "ラコ") >= 0)
							   || (strpos($r->name, "説明") !== false && strpos($r->name, "説明") >= 0))
							  ||
							  ((strpos($r->description, "ha") !== false && strpos($r->description, "ha") >= 0)
							   || (strpos($r->description, "ラコ") !== false && strpos($r->description, "ラコ") >= 0)
							   || (strpos($r->description, "説明") !== false && strpos($r->description, "説明") >= 0))
			);
		}
		$result = $this->db->select(new ArticleType(), new Criteria(Q::pattern(array(ArticleType::columnName(), ArticleType::columnDescription()), array(".*Ha.*"))));
		$this->assertEquals(0, sizeof($result),"This test failed about about the problem of DB in SQLite");
	}
	function testSelectiLikeIn() {
		$result = $this->db->select(new ArticleType(), new Criteria(Q::ipattern(array(ArticleType::columnName(), ArticleType::columnDescription()), array(".*Ha.*"))));
		$this->assertEquals(1, sizeof($result));
		foreach($result as $r) {
			$this->assertTrue(
							  ((strpos(strtolower($r->name), "ha") !== false && strpos(strtolower($r->name), "ha") >= 0)
							   || (strpos($r->name, "ラコ") !== false && strpos($r->name, "ラコ") >= 0)
							   || (strpos($r->name, "説明") !== false && strpos($r->name, "説明") >= 0))
							  ||
							  ((strpos(strtolower($r->description), "ha") !== false && strpos(strtolower($r->description), "ha") >= 0)
							   || (strpos($r->description, "ラコ") !== false && strpos($r->description, "ラコ") >= 0)
							   || (strpos($r->description, "説明") !== false && strpos($r->description, "説明") >= 0))
			);
		}
	}

	function testSelectNotiLikeStartsWith() {
		// rhaco converts '.' to '_' 
		$result = $this->db->select(new Tag(), new Criteria(Criterion::notiLike(Tag::columnName(), "rHac.")));
		$this->assertTrue(sizeof($result) > 0);
		foreach($result as $r) {
			$this->assertTrue(strpos(strtolower($r->name), "rhac") === false || strpos(strtolower($r->name), "rhac") > 0 && strtolower($r->name) !== "rhac");
		}

		// rhaco converts '.*' to '%'
		$result = $this->db->select(new Tag(), new Criteria(Criterion::notiLike(Tag::columnName(), "d_.*")));
		$this->assertTrue(sizeof($result) > 0);
		foreach($result as $r) {
			$this->assertTrue(strpos(strtolower($r->name), "d_") === false || strpos(strtolower($r->name), "d_") > 0);
		}

		$result = $this->db->select(new Tag(), new Criteria(Criterion::notiLike(Tag::columnName(), "d_.*")));
		$this->assertEquals(2, sizeof($result));
	}
	function testSelectNotiLikeEndWith() {
		// rhaco converts '.' to '_' 
		$result = $this->db->select(new Tag(), new Criteria(Criterion::notiLike(Tag::columnName(), ".*aCo")));
		$this->assertTrue(sizeof($result) > 0);
		foreach($result as $r) {
			$this->assertTrue(strpos(strtolower($r->name), "aco") !== strlen(strtolower($r->name)) - 3);
		}

		$result = $this->db->select(new Tag(), new Criteria(Criterion::notiLike(Tag::columnName(), ".*aC")));
		$this->assertTrue(sizeof($result) > 0);
		foreach($result as $r) {
			$this->assertTrue(strpos(strtolower($r->name), "ac") === false || strpos(strtolower($r->name), "ac") !== strlen(strtolower($r->name)) -2);
		}

		// rhaco converts '.*' to '%'
		$result = $this->db->select(new Tag(), new Criteria(Criterion::notiLike(Tag::columnName(), "._janGo")));
		$this->assertTrue(sizeof($result) > 0);
		foreach($result as $r) {
			$this->assertTrue(strpos(strtolower($r->name), "_jango") === false && strlen(strtolower($r->name)) !== 7);
		}

		$result = $this->db->select(new Tag(), new Criteria(Criterion::notiLike(Tag::columnName(), ".*aCo")));
		$this->assertEquals(2, sizeof($result));
	}
	function testSelectNotiLike() {
		// rhaco converts '.' to '_' 
		$result = $this->db->select(new Tag(), new Criteria(Criterion::notiLike(Tag::columnName(), ".*aC.*")));
		$this->assertTrue(sizeof($result) > 0);
		foreach($result as $r) {
			$this->assertTrue(strpos(strtolower($r->name), "ac") === false);
		}

		// rhaco converts '.*' to '%'
		$result = $this->db->select(new Tag(), new Criteria(Criterion::notiLike(Tag::columnName(), "._janG.")));
		$this->assertTrue(sizeof($result) > 0);
		foreach($result as $r) {
			$this->assertTrue(strpos(strtolower($r->name), "_jang") === false && strlen($r->name) !== 7);
		}

		$result = $this->db->select(new Tag(), new Criteria(Criterion::notiLike(Tag::columnName(), ".*aC.*")));
		$this->assertEquals(2, sizeof($result));
	}
	
	function testSelectIn() {
		$result = $this->db->select(new Tag(), new Criteria(Criterion::in(Tag::columnName(), array("rhaco", "hentai++", ".*ang.*') or 1"))));
		$this->assertEquals(2, sizeof($result));
		$flag_a = false;
		$flag_b = false;
		foreach($result as $r) {
			if($r->name === "rhaco") $flag_a = true;
			else if($r->name === "hentai++") $flag_b = true;
		}
		$this->assertTrue($flag_a);
		$this->assertTrue($flag_b);
	}
	function testSelectNotIn() {
		$result = $this->db->select(new Tag(), new Criteria(Criterion::notIn(Tag::columnName(), array("rhaco", "hentai++", ".*ang.*') or 1"))));
		$this->assertEquals(1, sizeof($result));
		$flag_a = true;
		$flag_b = true;
		foreach($result as $r) {
			if($r->name === "rhaco") $flag_a = false;
			else if($r->name === "hentai++") $flag_b = false;
		}
		$this->assertTrue($flag_a);
		$this->assertTrue($flag_b);
	}
	
	function testSelectJoin() {
		$result = $this->db->select(array(new ArticleType(), new Article()), new Criteria(Criterion::join(ArticleType::columnId(), Article::columnArticleType())));

		if(!empty($result)){
			list($articleTypes, $articles) = $result;

			$this->assertEquals(4, sizeof($articleTypes));
			$this->assertEquals(4, sizeof($articles));
			for($i = 0;$i < sizeof($articleTypes);$i++) {
				$this->assertEquals($articleTypes[$i]->getId(), $articles[$i]->getArticleType());
			}
	
			$criteria = new Criteria(Criterion::join(ArticleType::columnId(), Article::columnArticleType()),
									 Q::eq(ArticleType::columnId(), 1)
									 );
			list($articleTypes, $articles) = $this->db->select(array(new ArticleType(), new Article()), $criteria);
			$this->assertEquals(2, sizeof($articleTypes));
			$this->assertEquals(2, sizeof($articles));
			for($i = 0;$i < sizeof($articles);$i++) {
				$this->assertEquals(1, $articleTypes[$i]->getId());
				$this->assertEquals(1, $articles[$i]->getArticleType());
			}
		}
	}
	
	function testSelectOrder() {
		$articleTypes = $this->db->select(array(new ArticleType()),
														   new Criteria(
																		Criterion::order(ArticleType::columnSortOrder())
																		)
														   );
		if($this->assertTrue(sizeof($articleTypes) > 0)){
			$tmp_order = $articleTypes[0]->getSortOrder();
			for($i = 0;$i < sizeof($articleTypes);$i++) {
				$this->assertTrue($tmp_order <= $articleTypes[$i]->getSortOrder());
				$tmp_order = $articleTypes[$i]->getSortOrder();
			}
		}
	}
	
	function testSelectJoinOrder() {
		$result = $this->db->select(array(new ArticleType(), new Article()),
														   new Criteria(
																		Criterion::join(ArticleType::columnId(), Article::columnArticleType()),
																		Criterion::order(ArticleType::columnSortOrder())
																		)
														   );
		if(!empty($result)){
			list($articleTypes, $articles) = $result;
			
			if($this->assertEquals(4, sizeof($articleTypes))){
				if($this->assertEquals(4, sizeof($articles))){
					$tmp_order = $articleTypes[0]->getSortOrder();
					for($i = 0;$i < sizeof($articleTypes);$i++) {
						$this->assertEquals($articleTypes[$i]->getId(), $articles[$i]->getArticleType());
						$this->assertTrue($tmp_order <= $articleTypes[$i]->getSortOrder());
						$tmp_order = $articleTypes[$i]->getSortOrder();
					}
				}
			}
		}
	}

	function testSelectOrderDesc() {
		$articleTypes = $this->db->select(array(new ArticleType()),
														   new Criteria(
																		Criterion::orderDesc(ArticleType::columnSortOrder())
																		)
														   );
		if($this->assertTrue(sizeof($articleTypes) > 0)){
			$tmp_order = $articleTypes[0]->getSortOrder();
			for($i = 0;$i < sizeof($articleTypes);$i++) {
				$this->assertTrue($tmp_order >= $articleTypes[$i]->getSortOrder());
				$tmp_order = $articleTypes[$i]->getSortOrder();
			}
		}
	}
	
	function testSelectJoinOrderDesc() {
		$result = $this->db->select(array(new ArticleType(), new Article()),
														   new Criteria(
																		Criterion::join(ArticleType::columnId(), Article::columnArticleType()),
																		Criterion::orderDesc(ArticleType::columnSortOrder())
																		)
														   );
		if(!empty($result)){
			list($articleTypes, $articles) = $result;

			if($this->assertEquals(4, sizeof($articleTypes))){
				if($this->assertEquals(4, sizeof($articles))){
					$tmp_order = $articleTypes[0]->getSortOrder();
					for($i = 0;$i < sizeof($articleTypes);$i++) {
						$this->assertEquals($articleTypes[$i]->getId(), $articles[$i]->getArticleType());
						$this->assertTrue($tmp_order >= $articleTypes[$i]->getSortOrder());
						$tmp_order = $articleTypes[$i]->getSortOrder();
					}
				}
			}
		}
	}
	
	function testSelectDistinct() {
		$result = $this->db->select(new Article(),
								   new Criteria(
												Criterion::distinct(Article::columnArticleType())
											   )
								  );
		$this->assertEquals(3, sizeof($result));
		$hash = array();
		foreach($result as $articleType) {
			$this->assertFalse(array_key_exists($articleType->getArticleType(), $hash));
			$hash[$articleType->getArticleType()] = $articleType->getArticleType();
		}
	}
	function testClear(){
		$criteria = new Criteria(
								 Criterion::join(ArticleType::columnId(), Article::columnArticleType()),
								 Criterion::orderDesc(ArticleType::columnSortOrder()),
								 Criterion::notiLike(Tag::columnName(), "rHac."),
								 Criterion::distinct(Article::columnArticleType())
								 );
		$this->assertNotEquals(new Criteria(),$criteria);
		$criteria->clear();
		$this->assertEquals(new Criteria(),$criteria);
	}
	function testIsCond(){
		$criteria = new Criteria(
								 Criterion::join(ArticleType::columnId(), Article::columnArticleType()),
								 Criterion::orderDesc(ArticleType::columnSortOrder()),
								 Criterion::notiLike(Tag::columnName(), "rHac."),
								 Criterion::distinct(Article::columnArticleType())
								 );
		$this->assertFalse($criteria->isCond());
		$criteria->clear();
		$this->assertTrue($criteria->isCond());
	}
	function testFact(){
		$result = $this->db->select(new Article(),new Criteria(Q::fact()));
		$this->assertTrue(sizeof($result) > 0);

		foreach($result as $obj){
			$this->assertTrue(Variable::istype("ArticleType",$obj->getFactArticleType()));
		}

		// 普通のfact
		$result = $this->db->select(new FactTest(),new Criteria(Q::fact()));
		foreach($result as $obj){
			if($this->assertTrue(Variable::istype("FactTestRef",$obj->getFactRefId()))){
				$fact = $obj->getFactRefId();
				if($obj->getId() == 1){
					$this->assertEquals(2,$fact->getId());
					$this->assertEquals("2222",$fact->getRefName());
				}else if($obj->getId() == 2){
					$this->assertEquals(3,$fact->getId());
					$this->assertEquals("3333",$fact->getRefName());
				}				
			}
		}
		
		// extからfact
		$result = $this->db->select(new FactTestExt(),new Criteria(Q::fact()));
		foreach($result as $obj){
			if($this->assertTrue(Variable::istype("FactTestRef",$obj->getFactRefId()))){
				$fact = $obj->getFactRefId();
				if($obj->getId() == 1){
					$this->assertEquals(2,$fact->getId());
					$this->assertEquals("2222",$fact->getRefName());
				}else if($obj->getId() == 2){
					$this->assertEquals(3,$fact->getId());
					$this->assertEquals("3333",$fact->getRefName());
				}				
			}
		}
		
		// 同一テーブルに対するreferenceのマップ
		$result = $this->db->select(new MapTest(),new Criteria(Q::fact()));
		$this->assertTrue(sizeof($result) > 0);
		foreach($result as $obj){
			if($obj->getId() == 1){
				if($this->assertTrue(Variable::istype("FactTest",$obj->getFactFactTestId()))){
					$fact = $obj->getFactFactTestId();
					$this->assertEquals(1,$fact->getId());
				}else if($this->assertTrue(Variable::istype("FactTestExt",$obj->getFactFactTestExtId()))){
					$fact = $obj->getFactFactTestExtId();
					$this->assertEquals(2,$fact->getId());
				}
				break;
			}
		}
	}
	function testFlat(){
		$result = $this->db->select(new Article(),new Criteria(Q::flat()));
		$this->assertTrue(sizeof($result) > 0);

		foreach($result as $obj){
			$this->assertTrue(Variable::istype("Article",$obj));
			if($this->assertTrue(method_exists($obj,"istype"))){
				$this->assertTrue($obj->istype("Article"));
				$this->assertTrue($obj->istype("ArticleType"));
			}
			$this->assertTrue(Variable::istype("ArticleType",$obj->getFactArticleType()));
		}

		// 普通のfact
		$result = $this->db->select(new FactTest(),new Criteria(Q::flat(),Q::eq(FactTest::columnRefId(),1)));
		foreach($result as $obj){
			$this->assertTrue(Variable::istype("FactTest",$obj));
			if($this->assertTrue(method_exists($obj,"istype"))){
				$this->assertTrue($obj->istype("FactTest"));
				$this->assertTrue($obj->istype("FactTestRef"));
				$this->assertEquals("1111",$obj->getRefName());
			}
		}
		
		// extからfact
		$result = $this->db->select(new FactTestExt(),new Criteria(Q::flat()));
		foreach($result as $obj){
			if($this->assertTrue(Variable::istype("FactTestRef",$obj->getFactRefId()))){
				$fact = $obj->getFactRefId();

				if($this->assertTrue(method_exists($obj,"istype"))){
					$this->assertTrue($obj->istype("FactTestExt"));
					$this->assertTrue($obj->istype("FactTestRef"));

					if($obj->getId() == 1){
						$this->assertEquals(2,$fact->getId());
						$this->assertEquals("2222",$fact->getRefName());
						$this->assertEquals("2222",$obj->getRefName());
					}else if($obj->getId() == 2){
						$this->assertEquals(3,$fact->getId());
						$this->assertEquals("3333",$fact->getRefName());
						$this->assertEquals("3333",$obj->getRefName());
					}
				}
			}
		}
	}
	function testDepend(){
		$result = $this->db->select(new FactTest(),new Criteria(Q::depend()));
		$this->assertTrue(sizeof($result) > 0);
		$bool = false;
		
		foreach($result as $obj){
			foreach(ArrayUtil::arrays($obj->getDependMapTests()) as $dep){
				$this->assertTrue(Variable::istype("MapTest",$dep));
				$bool = true;
			}
		}
		$this->assertTrue($bool);
	}
	function testPager(){
		$result = $this->db->select(new FactTest());
		$this->assertTrue(3 >= sizeof($result));

		$paginator = new Paginator();
		$paginator->setLimit(2);
		$paginator->setOffset(1);
		$result = $this->db->select(new FactTest(),new C(Q::pager($paginator),Q::order(FactTest::columnId())));

		if($this->assertEquals(2,sizeof($result))){
			list($result1,$result2) = $result;
			$this->assertEquals(2,$result1->getId());
			$this->assertEquals(3,$result2->getId());
		}		
		
		$result = $this->db->select(new FactTest(),new C(Q::pager(2,1),Q::order(FactTest::columnId())));

		if($this->assertEquals(2,sizeof($result))){
			list($result1,$result2) = $result;
			$this->assertEquals(2,$result1->getId());
			$this->assertEquals(3,$result2->getId());
		}
	}
	
	
	function testCriterionGetEqual(){
	  	//NumOnlyに２件入ってるはず
		// NumOnlyが複数返ってきて失敗
//		$list = $this->db->select(new ArticleType(),new C(
//			Q::getEqual(ArticleType::columnId(),NumOnly::columnNum())));
//		$this->assertEquals(0,sizeof($list));

		$list = $this->db->select(new ArticleType(),new C(
			Q::getEqual(ArticleType::columnId(),NumOnly::columnNum(),new Criteria(Q::eq(NumOnly::columnId(),1)))));			
		if($this->assertEquals(1,sizeof($list))){
			$this->assertEquals(1,$list[0]->getId());
		}
		
		// abbr 失敗
//		$list = $this->db->select(new ArticleType(),new C(
//			Q::geq(ArticleType::columnId(),NumOnly::columnNum())));
//		$this->assertEquals(0,sizeof($list));

		$list = $this->db->select(new ArticleType(),new C(
			Q::geq(ArticleType::columnId(),NumOnly::columnNum(),new Criteria(Q::eq(NumOnly::columnId(),1)))));			
		if($this->assertEquals(1,sizeof($list))){
			$this->assertEquals(1,$list[0]->getId());
		}		
	}
	
	function testCriterionGetNotEqual(){
	  	//NumOnlyに２件入ってるはず　失敗
//		$list = $this->db->select(new ArticleType(),new C(
//			Q::getNotEqual(ArticleType::columnId(),NumOnly::columnNum())));
//		$this->assertEquals(0,sizeof($list));			

		$list = $this->db->select(new ArticleType(),new C(
			Q::getNotEqual(ArticleType::columnId(),NumOnly::columnNum(),new Criteria(Q::eq(NumOnly::columnId(),1)))));			
		if($this->assertEquals(2,sizeof($list))){
			foreach($list as $data){
				$this->assertNotEquals(1,$data->getId());
			}
		}
		
		//abbr　失敗
//		$list = $this->db->select(new ArticleType(),new C(
//			Q::gneq(ArticleType::columnId(),NumOnly::columnNum())));
//		$this->assertEquals(0,sizeof($list));			

		$list = $this->db->select(new ArticleType(),new C(
			Q::gneq(ArticleType::columnId(),NumOnly::columnNum(),new Criteria(Q::eq(NumOnly::columnId(),1)))));			
		if($this->assertEquals(2,sizeof($list))){
			foreach($list as $data){
				$this->assertNotEquals(1,$data->getId());
			}
		}
	}
	
	function testCriterionSelectIn(){
	  	//NumOnlyに２件入ってるはず
		$list = $this->db->select(new ArticleType(),new C(
			Q::selectIn(ArticleType::columnId(),NumOnly::columnNum())));
		$this->assertEquals(2,sizeof($list));			

		$list = $this->db->select(new ArticleType(),new C(
			Q::selectIn(ArticleType::columnId(),NumOnly::columnNum(),new Criteria(Q::eq(NumOnly::columnId(),1)))));

		if($this->assertEquals(1,sizeof($list))){
			foreach($list as $data){
				$this->assertEquals(1,$data->getId());
			}
		}
		
		// abbr
		$list = $this->db->select(new ArticleType(),new C(
			Q::sin(ArticleType::columnId(),NumOnly::columnNum())));
		$this->assertEquals(2,sizeof($list));			

		$list = $this->db->select(new ArticleType(),new C(
			Q::sin(ArticleType::columnId(),NumOnly::columnNum(),new Criteria(Q::eq(NumOnly::columnId(),1)))));

		if($this->assertEquals(1,sizeof($list))){
			foreach($list as $data){
				$this->assertEquals(1,$data->getId());
			}
		}
	}
	
	function testCriterionSelectNotIn(){
	  	//NumOnlyに２件入ってるはず
		$list = $this->db->select(new ArticleType(),new C(
			Q::selectNotIn(ArticleType::columnId(),NumOnly::columnNum())));
		$this->assertEquals(1,sizeof($list));			

		$list = $this->db->select(new ArticleType(),new C(
			Q::selectNotIn(ArticleType::columnId(),NumOnly::columnNum(),new Criteria(Q::eq(NumOnly::columnId(),1)))));

		if($this->assertEquals(2,sizeof($list))){
			foreach($list as $data){
				$this->assertNotEquals(1,$data->getId());
			}
		}

		// abbr
		$list = $this->db->select(new ArticleType(),new C(
			Q::snin(ArticleType::columnId(),NumOnly::columnNum())));
		$this->assertEquals(1,sizeof($list));			

		$list = $this->db->select(new ArticleType(),new C(
			Q::snin(ArticleType::columnId(),NumOnly::columnNum(),new Criteria(Q::eq(NumOnly::columnId(),1)))));

		if($this->assertEquals(2,sizeof($list))){
			foreach($list as $data){
				$this->assertNotEquals(1,$data->getId());
			}
		}
	}
	function testGetNoneNew(){
		if($this->assertEquals(0,$this->db->count(new Article(),new C(Q::eq(Article::columnId(),1000))))){
			$data = new Article();
			$data->setId(1000);
			$data->setArticleType(1);			
			$obj = $this->db->get($data);
			$this->assertEquals(null,$obj);

			$data = new Article();
			$data->setId(1000);
			$data->setArticleType(1);
			$obj = $this->db->get($data,new C(Q::getNoneNew()));

			$this->assertTrue(Variable::istype("Article",$obj));
			$this->db->delete($data);
			
			$data = new Article();
			$data->setId(1000);
			$data->setArticleType(1);			
			$obj = $this->db->get($data);
			$this->assertEquals(null,$obj);

			$data = new Article();
			$data->setId(1000);
			$data->setArticleType(1);
			$obj = $this->db->get($data,new C(Q::goc()));

			$this->assertTrue(Variable::istype("Article",$obj));
			$this->db->delete($data);
			
		}
	}
	function testAdd(){
		$this->assertTrue(Variable::istype("CriteriaPattern",Q::add(Article::columnId(),1,1)));
	}
	function testDepend2(){
		$result = $this->db->select(new ArticleType(),new Criteria(Q::depend()));
		$this->assertTrue(sizeof($result) > 0);

		foreach($result as $obj){
			$depends = $obj->getDependArticles();
			$this->assertTrue(is_array($depends));
			$this->assertTrue(Variable::istype("Article",$depends[0]));
		}

		// 普通のdepend
		$result = $this->db->select(new DependTest(),new Criteria(Q::depend()));

		foreach($result as $obj){
			$depends = $obj->getDependDependTestReffereds();
			if($obj->getId()==1 || $obj->getId()==2) $this->assertTrue(is_array($depends));
			if($obj->getId()==3) $this->assertTrue(is_null($depends));
			if(is_array($depends)){
				foreach($depends as $depend){
					if($this->assertTrue(Variable::istype("DependTestReffered",$depend))){
						if($obj->getId() == 2){
							$this->assertTrue(in_array($depend->getId(),array(1,2)));
							if($depend->getId() == 1){
								$this->assertEquals("1111",$depend->getName());
							}elseif($depend->getId() == 2){
								$this->assertEquals("2222",$depend->getName());
							}
						}else if($obj->getId() == 1){
							$this->assertEquals(3,$depend->getId());
							$this->assertEquals("3333",$depend->getName());
						}
					}
				}
			}
		}

		// depend get
		$result = $this->db->get(new DependTest(),new Criteria(Q::eq(DependTest::columnId(),2),Q::depend()));
		if($this->assertTrue(V::istype("DependTest",$result))){
			$this->assertEquals(2,$result->getId());
			$depends = $result->getDependDependTestReffereds();

			if($this->assertTrue(is_array($depends)) && $this->assertEquals(2,count($depends))){
				$this->assertEquals(1,$depends[0]->getId());
				$this->assertEquals("1111",$depends[0]->getName());
				$this->assertEquals(2,$depends[1]->getId());
				$this->assertEquals("2222",$depends[1]->getName());
			}
		}

		$result = $this->db->get(new DependTest(2),new Criteria(Q::depend()));
		if($this->assertTrue(V::istype("DependTest",$result))){
			$this->assertEquals(2,$result->getId());
			$depends = $result->getDependDependTestReffereds();

			if($this->assertTrue(is_array($depends)) 
				&& $this->assertEquals(2,count($depends))){
				$this->assertEquals(1,$depends[0]->getId());
				$this->assertEquals("1111",$depends[0]->getName());
				$this->assertEquals(2,$depends[1]->getId());
				$this->assertEquals("2222",$depends[1]->getName());
			}
		}

		// dependが無い場合
		$result = $this->db->get(new DependTest(),new Criteria(Q::eq(DependTest::columnId(),3),Q::depend()));

		if($this->assertTrue(V::istype("DependTest",$result))){
			$this->assertEquals(3,$result->getId());
			$depends = $result->getDependDependTestReffereds();
			$this->assertFalse(is_array($depends));
		}

		$result = $this->db->get(new DependTest(3),new Criteria(Q::depend()));

		if($this->assertTrue(V::istype("DependTest",$result))){
			$this->assertEquals(3,$result->getId());
			$depends = $result->getDependDependTestReffereds();
			$this->assertFalse(is_array($depends));
		}

		// extからdepend
//		$result = $this->db->select(new DependTestExt(),new Criteria(Q::depend()));
//		foreach($result as $obj){
//			$depends = $obj->getDependDependReffered();
//			if($this->assertTrue(Variable::istype("DependTestReffered",$obj->getFactRefId()))){
//				$fact = $obj->getFactRefId();
//				if($obj->getId() == 1){
//					$this->assertEquals(2,$fact->getId());
//					$this->assertEquals("2222",$fact->getName());
//				}else if($obj->getId() == 2){
//					$this->assertEquals(3,$fact->getId());
//					$this->assertEquals("3333",$fact->getName());
//				}
//			}
//		}
	}
	
	function testAndc(){
		$criteria = new Criteria(Q::in(ArticleType::columnId(),array(1,2)));
		$list = $this->db->select(new ArticleType(),$criteria);
		$this->assertEquals(2,sizeof($list));

		$criteria = new Criteria(Q::in(ArticleType::columnId(),array(1,2)),Q::andc(Q::eq(ArticleType::columnSortOrder(),1)));
		$list = $this->db->select(new ArticleType(),$criteria);
		$this->assertEquals(1,sizeof($list));
		
		$criteria = new C(Q::in(ArticleType::columnId(),array(1,2)),Q::andc(new C(Q::eq(ArticleType::columnSortOrder(),1))));
		$list = $this->db->select(new ArticleType(),$criteria);
		$this->assertEquals(1,sizeof($list));
	}
	
	function testOrc(){
		$criteria = new Criteria(Q::in(ArticleType::columnId(),array(1,2)));
		$list = $this->db->select(new ArticleType(),$criteria);
		$this->assertEquals(2,sizeof($list));

		$criteria = new C(Q::in(ArticleType::columnId(),array(1,2)),Q::orc(Q::eq(ArticleType::columnSortOrder(),2)));
		$list = $this->db->select(new ArticleType(),$criteria);
		$this->assertEquals(3,sizeof($list));
	}
	
	function testSelectStartswith(){
		$result = $this->db->select(new Item(), new Criteria(Q::startswith(Item::columnName(), "Pro")));
		$this->assertEquals(2, sizeof($result),"This test failed about about the problem of DB in SQLite");
		
		$result = $this->db->select(new Item(), new Criteria(Q::startswith(Item::columnName(), "pro")));
		$this->assertEquals(0, sizeof($result),"This test failed about about the problem of DB in SQLite");
	}
	function testSelectNotStartswith(){
		$result = $this->db->select(new Item(), new Criteria(Q::notStartswith(Item::columnName(), "Pro")));
		$this->assertEquals(2, sizeof($result),"This test failed about about the problem of DB in SQLite");
		
		$result = $this->db->select(new Item(), new Criteria(Q::notStartswith(Item::columnName(), "pro")));
		$this->assertEquals(4, sizeof($result),"This test failed about about the problem of DB in SQLite");
	}
	function testSelectiStartswith(){
		$result = $this->db->select(new Item(), new Criteria(Q::istartswith(Item::columnName(), "Pro")));
		$this->assertEquals(2, sizeof($result),"This test failed about about the problem of DB in SQLite");
		
		$result = $this->db->select(new Item(), new Criteria(Q::istartswith(Item::columnName(), "pro")));
		$this->assertEquals(2, sizeof($result),"This test failed about about the problem of DB in SQLite");
	}
	function testSelectNotiStartswith(){
		$result = $this->db->select(new Item(), new Criteria(Q::notiStartswith(Item::columnName(), "Pro")));
		$this->assertEquals(2, sizeof($result),"This test failed about about the problem of DB in SQLite");
		
		$result = $this->db->select(new Item(), new Criteria(Q::notiStartswith(Item::columnName(), "pro")));
		$this->assertEquals(2, sizeof($result),"This test failed about about the problem of DB in SQLite");
	}
	
	function testSelectEndswith(){
		$result = $this->db->select(new Item(), new Criteria(Q::endswith(Item::columnName(), "Django")));
		$this->assertEquals(2, sizeof($result),"This test failed about the problem of DB in SQLite");
		
		$result = $this->db->select(new Item(), new Criteria(Q::endswith(Item::columnName(), "django")));
		$this->assertEquals(0, sizeof($result),"This test failed about the problem of DB in SQLite");
	}
	function testSelectNotEndswith(){
		$result = $this->db->select(new Item(), new Criteria(Q::notEndswith(Item::columnName(), "Django")));
		$this->assertEquals(2, sizeof($result),"This test failed about the problem of DB in SQLite");
		
		$result = $this->db->select(new Item(), new Criteria(Q::notEndswith(Item::columnName(), "django")));
		$this->assertEquals(4, sizeof($result),"This test failed about the problem of DB in SQLite");
	}
	function testSelectiEndswith(){
		$result = $this->db->select(new Item(), new Criteria(Q::iendswith(Item::columnName(), "Django")));
		$this->assertEquals(2, sizeof($result),"This test failed about the problem of DB in SQLite");
		
		$result = $this->db->select(new Item(), new Criteria(Q::iendswith(Item::columnName(), "django")));
		$this->assertEquals(2, sizeof($result),"This test failed about the problem of DB in SQLite");
	}
	function testSelectNotiEndswith(){
		$result = $this->db->select(new Item(), new Criteria(Q::notiEndswith(Item::columnName(), "Django")));
		$this->assertEquals(2, sizeof($result),"This test failed about the problem of DB in SQLite");
		
		$result = $this->db->select(new Item(), new Criteria(Q::notiEndswith(Item::columnName(), "django")));
		$this->assertEquals(2, sizeof($result),"This test failed about the problem of DB in SQLite");
	}
	
	function testSelectContains(){
		$result = $this->db->select(new Article(), new Criteria(Q::contains(Article::columnBody(), "D")));
		$this->assertEquals(1, sizeof($result),"This test failed about the problem of DB in SQLite");
		
		$result = $this->db->select(new Article(), new Criteria(Q::contains(Article::columnBody(), "d")));
		$this->assertEquals(1, sizeof($result),"This test failed about the problem of DB in SQLite");
	}
	function testSelectNotContains(){
		$result = $this->db->select(new Article(), new Criteria(Q::notContains(Article::columnBody(), "D")));
		$this->assertEquals(3, sizeof($result),"This test failed about the problem of DB in SQLite");
		
		$result = $this->db->select(new Article(), new Criteria(Q::notContains(Article::columnBody(), "d")));
		$this->assertEquals(3, sizeof($result),"This test failed about the problem of DB in SQLite");
	}
	function testSelectiContains(){
		$result = $this->db->select(new Article(), new Criteria(Q::icontains(Article::columnBody(), "D")));
		$this->assertEquals(2, sizeof($result),"This test failed about the problem of DB in SQLite");
		
		$result = $this->db->select(new Article(), new Criteria(Q::icontains(Article::columnBody(), "d")));
		$this->assertEquals(2, sizeof($result),"This test failed about the problem of DB in SQLite");
	}
	function testSelectNotiContains(){
		$result = $this->db->select(new Article(), new Criteria(Q::notiContains(Article::columnBody(), "D")));
		$this->assertEquals(2, sizeof($result),"This test failed about the problem of DB in SQLite");
		
		$result = $this->db->select(new Article(), new Criteria(Q::notiContains(Article::columnBody(), "d")));
		$this->assertEquals(2, sizeof($result),"This This test failed about the problem of DB in SQLite");
	}
}
?>