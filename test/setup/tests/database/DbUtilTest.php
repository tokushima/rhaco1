<?php
Rhaco::import("abbr.Q");
Rhaco::import("abbr.V");
Rhaco::import("abbr.L");
Rhaco::import("util.UnitTest");
Rhaco::import("model.Article");
Rhaco::import("model.ArticleType");
Rhaco::import("model.Tag");
Rhaco::import("model.ArticleTag");
Rhaco::import("model.Item");
Rhaco::import("model.Frog");
Rhaco::import("model.CFrog");
Rhaco::import("model.GcFrog");
Rhaco::import("model.Jojo");
Rhaco::import("model.Dio");

Rhaco::import("model.UniqueTest");
Rhaco::import("model.UniqueTestExt");
Rhaco::import("model.UniqueTestMap");

Rhaco::import("model.TypeTest");
Rhaco::import("model.RequireTest");
Rhaco::import("model.NumOnly");
Rhaco::import("model.BirthdayTest");


class DbUtilTest extends UnitTest {
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
	}
	
	function tearDown(){
		$this->db->alldelete(new Item());
		$this->db->alldelete(new Frog());
		$this->db->alldelete(new CFrog());
		$this->db->alldelete(new GcFrog());
		$this->db->alldelete(new Jojo());
		$this->db->alldelete(new Dio());
	}
	
	function testInsert(){
		$insertItem = V::ho(
					array("name" => "insert",
						  "price" => 1000),
								new Item());

		$beforeCount = $this->db->count(new Item());
		$returnItem = $this->db->insert($insertItem);

		$afterCount = $this->db->count(new Item());
		$this->assertEquals($beforeCount+1,$afterCount);
		
		if($this->assertTrue(Variable::istype("Item",$returnItem) && $returnItem->getId() > 0)){	  
			$resultItem = $this->db->get($returnItem);

			if($this->assertTrue(Variable::istype("Item",$resultItem))){
				$this->assertEquals($insertItem->getName(),$resultItem->getName());
				$this->assertEquals($insertItem->getPrice(),$resultItem->getPrice());
			}
			$this->assertEquals($returnItem,$resultItem);
		}
	}
	
	function testUpdate(){
		$insertItem01 = V::ho(
					array("name" => "insert1",
						  "price" => 999),
								new Item());
								
	   	$insertItem02 = V::ho(
					array("name" => "insert2",
						  "price" => 999),
								new Item());
								
		$insertItem1 = $this->db->insert($insertItem01);
		$insertItem2 = $this->db->insert($insertItem02);
		$resultItem1　= null;

		if($this->assertTrue(Variable::istype("Item",$insertItem1))){
			$insertItem1->setName("insert3");
			$this->db->update($insertItem1);
			$resultItem1 = $this->db->get($insertItem1);

			if($this->assertTrue(Variable::istype("Item",$resultItem1))){
				$this->assertEquals($insertItem1->getName(),$resultItem1->getName());
				$this->assertEquals($insertItem1->getPrice(),$insertItem01->getPrice());
			}
		}
		
		$searchCriteria = new Criteria(Q::eq(Item::columnPrice(),999));
		$beforeCount = $this->db->count(new Item(),$searchCriteria);
		
		$this->assertEquals(2,$beforeCount);
		
		// Q::which
		$itemList = $this->db->select(new Item(),new Criteria(Q::eq(Item::columnPrice(),999)));
		$this->assertEquals(2,sizeof($itemList));
		foreach($itemList as $item){
			$this->assertEquals((float)999,$item->getPrice());
			$this->assertNotEquals("update",$item->getName());			
		}
		$updateItem = V::ho(array("name" => "update","price" => 8888),new Item());		
		$this->db->update($updateItem,new Criteria(Q::eq(Item::columnPrice(),999),Q::which(Item::columnPrice())));

		$afterCount = $this->db->count(new Item(),new Criteria(Q::eq(Item::columnPrice(),999)));		
		$this->assertEquals(0,$afterCount);
		
		$itemList = $this->db->select(new Item(),new Criteria(Q::eq(Item::columnPrice(),8888)));
		$this->assertEquals(2,sizeof($itemList));
		foreach($itemList as $item){
			$this->assertEquals($updateItem->getPrice(),$item->getPrice());
			$this->assertNotEquals("update",$item->getName());			
		}
	}
	
	function testDelete(){
		$insertGCFrog = V::ho(
					array("name" => "deleteGCF",
						  "color" => "緑",
						  "home" => "森",
					),
					new GcFrog());
		$returnGCFrog = $this->db->insert($insertGCFrog);

		if(Variable::istype("GcFrog",$returnGCFrog)){
			$insertCFrog = V::ho(
						array("name" => "deleteCF",
							  "color" => "緑",
							  "home" => "森"),
									new CFrog());
			$insertCFrog->setGcFrogId($returnGCFrog->getId());
			$returnCFrog = $this->db->insert($insertCFrog);

			$insertFrog = V::ho(
					array("name" => "deleteF",
						  "color" => "赤",
						  "home" => "池"),
								new Frog());

			if($this->assertTrue(Variable::istype("CFrog",$returnCFrog))){
				$insertFrog->setCFrogId($returnCFrog->getId());
				$returnFrog = $this->db->insert($insertFrog);
			}
			$this->db->delete($returnGCFrog);

		
			$resultGCFrog = $this->db->get($returnGCFrog);
			$resultCFrog = $this->db->get($returnCFrog);
			$resultFrog = $this->db->get($returnFrog);
			
			$this->assertTrue($resultGCFrog === null);
			$this->assertTrue($resultCFrog === null);
			$this->assertTrue($resultFrog === null);
		}
	}
	
	function testDeleteCriteria(){
		$insertGCFrog01 = V::ho(
					array("name" => "deleteGCF01",
						  "color" => "緑",
						  "home" => "森"),
								new GcFrog());
		$insertGCFrog02 = V::ho(
					array("name" => "deleteGCF02",
						  "color" => "青",
						  "home" => "森"),
								new GcFrog());
					   
		$returnGCFrog1 = $this->db->insert($insertGCFrog01);
		$returnGCFrog2 = $this->db->insert($insertGCFrog02);
		
		$criteria = new Criteria(Q::eq(GcFrog::columnColor(),"青"));
		$this->db->delete(new GcFrog(),$criteria);
		
		$resultGCFrog1 = $this->db->get($returnGCFrog1);
		$resultGCFrog2 = $this->db->get($returnGCFrog2);

		$this->assertEquals($returnGCFrog1,$resultGCFrog1);
		$this->assertTrue($resultGCFrog2 === null);
	}
	
	function testAllDelete(){
		$insertJojo1 = V::ho(
					array("name" => "ジョナサン",
						  "stand" => "波紋"),
								new Jojo());
		$insertJojo2 = V::ho(
					array("name" => "ジョセフ",
						  "stand" => "隠者の紫"),
								new Jojo());
		$insertJojo3 = V::ho(
					array("name" => "承太郎",
						  "stand" => "スタープラチナ"),
								new Jojo());

		$this->db->insert($insertJojo1);
		$this->db->insert($insertJojo2);
		$this->db->insert($insertJojo3);
		
		$beforeCount = $this->db->count(new Jojo());
		$this->assertEquals(3,$beforeCount);

		$this->db->alldelete(new Jojo());
		
		$afterCount = $this->db->count(new Jojo());
		$this->assertEquals(0,$afterCount);
	}
	
	function testBeforeInsert(){
		$insertDio = V::ho(
					array("name" => "Dio様",
						  "stand" => "ザ・ワールド"),
								new Dio());
		
		$obj = $this->db->insert($insertDio);
		$resultDio = $this->db->get($obj);

		if($this->assertTrue(Variable::istype("Dio",$resultDio))){
			$this->assertEquals("隠者の紫insert",$resultDio->getStand());
		}
	}
	
	function testBeforeUpdate(){
		$insertDio = V::ho(
					array("name" => "Dio様",
						  "stand" => "ザ・ワールド"),
								new Dio());
		
		$returnDio = $this->db->insert($insertDio);
		$this->db->update($returnDio);
		$updateDio = $this->db->get($returnDio);

		if($this->assertTrue(Variable::istype("Dio",$updateDio))){
			$this->assertEquals("隠者の紫update",$updateDio->getStand());
		}
	}
	
	function testAfterInsert(){
		$insertJojo = V::ho(
					array("name" => "承太郎",
						  "stand" => "スタープラチナ"),
								new Jojo());
		$returnJojo = $this->db->insert($insertJojo);
		$resultJojo = $this->db->get($returnJojo);

		if($this->assertTrue(Variable::istype("Jojo",$resultJojo))){
			$this->assertEquals("スタープラチナ",$resultJojo->getStand());
			$this->assertEquals("スタープラチナ・ザ・ワールドinsert",$returnJojo->getStand());
		}
	}
	
	function testAfterGet(){
		$insertJojo = V::ho(
					array("name" => "承太郎",
						  "stand" => "スタープラチナ"),
								new Jojo());
		$returnJojo = $this->db->insert($insertJojo);
		$resultJojo = $this->db->get($returnJojo);

		if($this->assertTrue(Variable::istype("Jojo",$resultJojo))){
			$this->assertEquals("承り太郎",$resultJojo->getName());
			$this->assertEquals("承太郎",$returnJojo->getName());
		}
	}
	function testDrop(){
		$insertDio = V::ho(
					array("name" => "Dio様",
						  "stand" => "ザ・ワールド"),
								new Dio());
		$obj = $this->db->insert($insertDio);
		$resultDio = $this->db->get($obj);
		
		if($this->assertTrue(Variable::istype("Dio",$resultDio))){
			$this->assertEquals("隠者の紫insert",$resultDio->getStand());
			$resultDio->drop($this->db);
			$this->assertTrue($this->db->get($resultDio) === null);
		}
	}
	function testSave(){
		$insertDio = V::ho(
					array("name" => "Dio様",
						  "stand" => "ザ・ワールド"),
								new Dio());
		$resultDio = $insertDio->save($this->db);
		
	   if($this->assertTrue(Variable::istype("Dio",$resultDio))){
			$this->assertEquals("隠者の紫insert",$resultDio->getStand());
	
			$get = $this->db->get($resultDio);
			
			if($this->assertTrue(Variable::istype("Dio",$get))){
				$this->assertEquals("隠者の紫insert",$get->getStand());
				$get->setStand("JOJO");
				$update = $get->save($this->db);

				$get = $this->db->get($resultDio);
				if($this->assertNotEquals($get,null)){
					$this->assertEquals("隠者の紫update",$get->getStand());
				}
			}
	   }
	}

	function testCommitRollback(){
		$insertItem = V::ho(
					array("name" => "insert",
						  "price" => 1000),
								new Item());
		$this->db->commit();
		$beforeCount = $this->db->count(new Item());
		$returnItem = $this->db->insert($insertItem);

		$afterCount = $this->db->count(new Item());
		$this->assertEquals($beforeCount+1,$afterCount);
		
		$this->db->rollback();		
		$afterCount = $this->db->count(new Item());
		$this->assertEquals($beforeCount,$afterCount);
		
		
		
		$returnItem = $this->db->insert($insertItem);		
		$this->db->commit();		
		$afterCount = $this->db->count(new Item());
		$this->assertEquals($beforeCount+1,$afterCount);
	}
	function testUniqueWith(){
		// すべて削除
		$this->db->alldelete(new UniqueTestMap());

		$friend = new UniqueTestMap();
		$friend->setUser(1);
		$friend->setComp(2);
		
		// 1件追加
		$this->assertTrue(Variable::istype("UniqueTestMap",$this->db->insert($friend)));
		$this->assertEquals(1,$this->db->sizeof(new UniqueTestMap()));

		$friend = new UniqueTestMap();
		$friend->setUser(1);
		$friend->setComp(3);

		// 1件追加
		$this->assertTrue(Variable::istype("UniqueTestMap",$this->db->insert($friend)));
		// 追加されるはず
		$this->assertEquals(2,$this->db->sizeof(new UniqueTestMap()));

		$friend = new UniqueTestMap();
		$friend->setUser(1);
		$friend->setComp(2);

		// 失敗するはず
		$this->assertFalse($this->db->insert($friend));
		$this->assertEquals(2,$this->db->sizeof(new UniqueTestMap()));

		// すべて削除
		$this->db->alldelete(new UniqueTestMap());		
	}
	
	function testRquireWith(){
		// すべて削除
		$this->db->alldelete(new RequireTest());

		// 必須チェック違反
		$data = new RequireTest();
		$this->assertFalse($this->db->insert($data));
		$this->assertEquals(0,$this->db->sizeof(new RequireTest()));
		
		// 正常
		$data = new RequireTest();
		$data->setName("hoge");
		$this->assertTrue(Variable::istype("RequireTest",$this->db->insert($data)));
		$this->assertEquals(1,$this->db->sizeof(new RequireTest()));
		
		// require with 違反
		$data = new RequireTest();
		$data->setName("hoge");
		$data->setZip(1234567);
		$this->assertFalse($this->db->insert($data));
		$this->assertEquals(1,$this->db->sizeof(new RequireTest()));
		
		// require with 正常
		$data = new RequireTest();
		$data->setName("hoge");
		$data->setZip(1234567);
		$data->setAddress("japan");
		$this->assertTrue(Variable::istype("RequireTest",$this->db->insert($data)));
		$this->assertEquals(2,$this->db->sizeof(new RequireTest()));
		
		// require with 正常 (zipなしでもOK)
		$data = new RequireTest();
		$data->setName("hoge");
		$data->setAddress("japan");
		$this->assertTrue(Variable::istype("RequireTest",$this->db->insert($data)));
		$this->assertEquals(3,$this->db->sizeof(new RequireTest()));
		
		// すべて削除
		$this->db->alldelete(new RequireTest());
	}
	function testSelectSum() {
		$this->db->alldelete(new TypeTest());
		$this->assertEquals(0,$this->db->count(new TypeTest()));
		
		$data = new TypeTest();
		$data->setId(1);
		$data->setIntegerValue(10);
		$data->setFloatValue(2.5);
		$data->save($this->db);
		
		$data = new TypeTest();
		$data->setId(2);
		$data->setIntegerValue(20);
		$data->setFloatValue(5);
		$data->save($this->db);

		$data = new TypeTest();
		$data->setId(3);
		$data->setIntegerValue(70);
		$data->setFloatValue(2.49);
		$data->save($this->db);
		
		$result = $this->db->sum(new TypeTest(),TypeTest::columnIntegerValue());
		$this->assertEquals(100, $result);
		
		$result = $this->db->sum(new TypeTest(),TypeTest::columnIntegerValue(),new C(Q::in(TypeTest::columnId(),array(2,3))));
		$this->assertEquals(90, $result);		
		
		$result = $this->db->sum(new TypeTest(),TypeTest::columnFloatValue());
		$this->assertEquals(9.99, $result);

		$result = $this->db->sum(new TypeTest(),TypeTest::columnFloatValue(),new C(Q::in(TypeTest::columnId(),array(2,3))));
		$this->assertEquals(7.49, $result);		

		$this->db->alldelete(new TypeTest());
	}
	function testInsertDefault(){
		$this->db->alldelete(new TypeTest());
		$data = new TypeTest();
		$data->setId(1);
		$data->setTimeValue("00:00:00");
		$data->save($this->db);
		$obj = $this->db->get(new TypeTest(1));
		$this->assertEquals(0,$obj->getTimeValue(),"00:00:00");
		
		$this->db->alldelete(new TypeTest());
		$data = new TypeTest();
		$data->setId(1);
		$data->setTimeValue(null);
		$data->save($this->db);
		$obj = $this->db->get(new TypeTest(1));
		$this->assertEquals(null,$obj->getTimeValue(),"null");
		
		$this->db->alldelete(new TypeTest());
		$data = new TypeTest();
		$data->setId(1);
		$data->setTimeValue("");
		$data->save($this->db);
		$obj = $this->db->get(new TypeTest(1));
		$this->assertEquals(null,$obj->getTimeValue(),"''");
		
		$this->db->alldelete(new TypeTest());
		$data = new TypeTest();
		$data->setId(1);
		$data->setTimeValue(0);
		$data->save($this->db);
		$obj = $this->db->get(new TypeTest(1));
		$this->assertEquals(0,$obj->getTimeValue(),"0");
	}
	function testSelectCount() {
		$this->db->alldelete(new TypeTest());
		$this->assertEquals(0,$this->db->count(new TypeTest()));
		
		$data = new TypeTest();
		$data->setId(1);
		$data->setIntegerValue(10);
		$data->setFloatValue(2.5);
		$data->save($this->db);
		
		$data = new TypeTest();
		$data->setId(2);
		$data->setIntegerValue(20);
		$data->setFloatValue(5);
		$data->save($this->db);

		$data = new TypeTest();
		$data->setId(3);
		$data->setIntegerValue(70);
		$data->setFloatValue(2.49);
		$data->save($this->db);
		
		$result = $this->db->count(new TypeTest());
		$this->assertEquals(3, $result);

		$result = $this->db->count(new TypeTest(),new C(Q::in(TypeTest::columnId(),array(2,3))));
		$this->assertEquals(2, $result);
		
		// NumOnlyは1,2のみのはず
		$result = $this->db->count(new TypeTest(),new C(
									Q::eq(TypeTest::columnId(),NumOnly::columnId()),
									Q::in(NumOnly::columnId(),array(2,3))));
		$this->assertEquals(1, $result);

		
		$result = $this->db->sizeof(new TypeTest());
		$this->assertEquals(3, $result);

		$result = $this->db->sizeof(new TypeTest(),new C(Q::in(TypeTest::columnId(),array(2,3))));
		$this->assertEquals(2, $result);

		// NumOnlyは1,2のみのはず
		$result = $this->db->sizeof(new TypeTest(),new C(
									Q::eq(TypeTest::columnId(),NumOnly::columnId()),
									Q::in(NumOnly::columnId(),array(2,3))));
		$this->assertEquals(1, $result);
		
		$this->db->alldelete(new TypeTest());
	}
	function testExportXml(){
		$XML = <<< __XML
<default class="articletype"><data><column var="id">1</column><column var="name">rhaco</column><column var="description">ラコについての説明</column><column var="sortOrder">1</column></data>
<data><column var="id">2</column><column var="name">php</column><column var="description">ぴーえっちぴー勉強会への参加備忘録</column><column var="sortOrder">3</column></data>
<data><column var="id">3</column><column var="name">apache</column><column var="description">あぱっちプロジェクトに関する話題</column><column var="sortOrder">2</column></data>
</default>
__XML;
		
		if(Env::isphp("5")){
		$XML = <<< __XML
<default class="ArticleType"><data><column var="id">1</column><column var="name">rhaco</column><column var="description">ラコについての説明</column><column var="sortOrder">1</column></data>
<data><column var="id">2</column><column var="name">php</column><column var="description">ぴーえっちぴー勉強会への参加備忘録</column><column var="sortOrder">3</column></data>
<data><column var="id">3</column><column var="name">apache</column><column var="description">あぱっちプロジェクトに関する話題</column><column var="sortOrder">2</column></data>
</default>
__XML;
		}
		$this->assertEquals($XML,$this->db->exportXml(new ArticleType()));
	}
	function testImportXml(){
		$this->db->alldelete(new Tag());
		$this->db->commit();
		
		$XML = <<< __XML
<default class="Tag"><data><column var="id">10</column><column var="name">hoge</column></data>
</default>
__XML;
		$this->db->importXml(new Tag(),$XML,true);
		$obj = $this->db->get(new Tag(10));
		
		if($this->assertTrue(Variable::istype("Tag",$obj))){
			$this->assertEquals(10,$obj->getId());
			$this->assertEquals("hoge",$obj->getName());
		}
		$this->assertEquals(1,$this->db->count(new Tag()));
	}
	
	function testExecuteSelect(){
		$this->assertTrue($this->db->executeSelect(new ArticleType()));
	}
	
	function testNext(){
		$this->assertTrue($this->db->executeSelect(new ArticleType()));

		$i = 0;
		while($this->db->next()){
			$this->assertTrue(is_array($this->db->getResultset()));
			$i++;
		}
		$this->assertEquals(3,$i);
	}
	function testNextObject(){
		$this->assertTrue($this->db->executeSelect(new ArticleType()));
		
		$i = 0;
		while($this->db->nextObject($obj)){
			$this->assertTrue(Variable::istype("ArticleType",$obj));
			$i++;
		}
		$this->assertEquals(3,$i);
	}
	
	function testRequireInteger(){
		$num = new NumOnly(100);
		$this->assertFalse($this->db->insert($num),"none");
		$this->db->delete(new NumOnly(100));
		
		$num = new NumOnly(100);
		$num->setNum(null);
		$this->assertFalse($this->db->insert($num),"null");
		$this->db->delete(new NumOnly(100));
		
		$num = new NumOnly(100);
		$num->setNum("");
		$this->assertFalse($this->db->insert($num),"''");
		$this->db->delete(new NumOnly(100));
		
		$num = new NumOnly(100);
		$num->setNum("hoge");
		$this->assertFalse($this->db->insert($num),"hoge");
		$this->db->delete(new NumOnly(100));
		
		$num = new NumOnly(100);
		$num->num = "";
		$this->assertFalse($this->db->insert($num),"direct ''");
		$this->db->delete(new NumOnly(100));
		
		$num = new NumOnly(100);
		$num->num = "hoge";
		$this->assertFalse($this->db->insert($num),"direct hoge");
		$this->db->delete(new NumOnly(100));		
	}
	
	function testBirthday(){
		$obj = new BirthdayTest();
		$obj->setName("tokushima");
		$obj->setBirthday("1820/10/04");
		
		$obj = $this->db->insert($obj);
		if($this->assertNotEquals(false,$obj)){
			$this->assertEquals("tokushima",$obj->getName());
			$this->assertEquals(18201004,$obj->getBirthday());
			$this->db->delete($obj);
		}		
		$bd = new BirthdayTest();
		$bd->setName('hoge');
		$bd->setBirthday('1966/01/13');
		$this->assertNotEquals(false,$this->db->insert($bd));
		$this->assertNotEquals(false,$bd->save($this->db));
		$this->db->delete($bd);
	}
}
?>