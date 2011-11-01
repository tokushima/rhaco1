<?php
Rhaco::import("lang.Variable");
Rhaco::import("lang.ObjectUtil");
Rhaco::import("util.UnitTest");
Rhaco::import("Dummy1");
Rhaco::import("Dummy2");
Rhaco::import("Dummy3");
Rhaco::import("ObjTest");

class ObjectUtilTest extends UnitTest {
	function testCopyProperties(){
		$obj1 = new Dummy1();
		$obj1->id = 100;
		$obj1->value = "hogehoge";
		
		$obj2 = new Dummy2();
		$obj2->id = 400;
		$obj2->type = "text";
		
		$this->assertEquals(400,$obj2->id);
		$this->assertEquals("text",$obj2->type);
		$obj = ObjectUtil::copyProperties($obj1,$obj2);
		
		$this->assertEquals(100,$obj1->id);
		$this->assertEquals("hogehoge",$obj1->value);
		$this->assertEquals(200,$obj2->id); // 値が変更されている
		$this->assertEquals("text",$obj2->type);

		$this->assertEquals(200,$obj->id);
		$this->assertEquals("text",$obj->type);
		
		
		$obj1 = new Dummy1();
		$obj1->id = 100;
		$obj1->value = "hogehoge";
		
		$obj2 = new Dummy2();
		$obj2->id = 400;
		$obj2->type = "text";
		
		$this->assertEquals(400,$obj2->id);
		$this->assertEquals("text",$obj2->type);
		$obj = ObjectUtil::copyProperties($obj1,$obj2,true);
		
		$this->assertEquals(100,$obj1->id);
		$this->assertEquals("hogehoge",$obj1->value);
		$this->assertEquals(100,$obj2->id); // 値が変更されている
		$this->assertEquals("text",$obj2->type);
		
		$this->assertEquals(100,$obj->id);
		$this->assertEquals("text",$obj->type);
	}
	function testCopyPropertiesSimpleTag(){
		$tag = new SimpleTag("hoge",null,array("id"=>1,"value"=>"hoge","test"=>"rhaco"));
		$obj = new Dummy1();
		$obj = ObjectUtil::copyProperties($tag,$obj,true);
		$this->assertEquals(1,$obj->id);
		$this->assertEquals("hoge",$obj->value);
		$this->assertEquals("rhaco",$obj->test);
		
		
		$tag = new SimpleTag("hoge",null,array("id"=>1,"value"=>"hoge","test"=>"rhaco"));
		$obj = new Dummy1();
		$obj = ObjectUtil::copyProperties($tag,$obj,false);
		$this->assertEquals(-1,$obj->id);
		$this->assertEquals("hoge",$obj->value);
		$this->assertEquals("rhaco",$obj->test);
		
		
		$tag = new SimpleTag("hoge",null,array("id"=>1,"value"=>"hoge","test"=>"rhaco"));
		$obj = new Dummy1();
		ObjectUtil::copyProperties($tag,$obj,true);
		$this->assertEquals(1,$obj->id);
		$this->assertEquals("hoge",$obj->value);
		$this->assertEquals("rhaco",$obj->test);
		
		$tag = new SimpleTag("hoge",null,array("id"=>1,"value"=>"hoge","test"=>"rhaco"));
		$obj = new Dummy1();
		ObjectUtil::copyProperties($tag,$obj,false);
		$this->assertEquals(-1,$obj->id);
		$this->assertEquals("hoge",$obj->value);
		$this->assertEquals("rhaco",$obj->test);
	}
	function testAnonym(){
		$obj = ObjectUtil::anonym('
					function getId(){
						return $this->id + 100;
					}
				',new Dummy1());
		
		$obj->setId(10);
		$this->assertEquals(-10,$obj->id);
		$this->assertEquals(90,$obj->getId());
	}
	
	
	function testSort(){
		$objs[] = new ObjTest(2);
		$objs[] = new ObjTest(1);
		$objs[] = new ObjTest(3);
		
		ObjectUtil::sort($objs,"id");
		
		$i = 1;
		foreach($objs as $obj){
			$this->assertEquals($i,$obj->id);
			$i++;
		}
		
		ObjectUtil::sort($objs,"id",false);
		$i = 3;
		foreach($objs as $obj){
			$this->assertEquals($i,$obj->id);
			$i--;
		}

		ObjectUtil::sort($objs,"getId()");
		$i = 3;
		foreach($objs as $obj){
			$this->assertEquals($i,$obj->id);
			$i--;
		}
	}
	
	function testCalls(){
		$objs[] = new ObjTest(1);
		$objs[] = new ObjTest(2);
		$objs[] = new ObjTest(3);
		
		$result = ObjectUtil::calls($objs,"to",array("abc","hoge"),1);
		$this->assertEquals("hoge+++",$result);
	}	
}
?>