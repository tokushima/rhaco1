<?php
Rhaco::import("lang.Variable");
Rhaco::import("lang.ObjectUtil");
Rhaco::import("util.UnitTest");
Rhaco::import("Dummy1");
Rhaco::import("Dummy2");
Rhaco::import("Dummy3");
Rhaco::import("Dummy4");
Rhaco::import("Dummy5");

class VariableTest extends UnitTest {
	function testMixin(){
		$obj = ObjectUtil::mixin(new Dummy1(),new Dummy2(),array("calc",'$a,$b','return $a + $b;'));
		
		if($this->assertTrue(is_object($obj),var_export($obj,true))){
			$obj->id = 1;
			$obj->value = "hoge";
			$obj->type = "rhaco";
	
			$this->assertEquals(">>>>1",$obj->id());
			$this->assertEquals("[hoge]",$obj->value());
			$this->assertEquals("<rhaco>",$obj->type());
			$this->assertEquals(3,$obj->calc(1,2));
			$this->assertTrue(Variable::istype("Dummy1",$obj));
			$this->assertFalse(Variable::istype("Dummy2",$obj));
			$this->assertTrue($obj->istype("Dummy1"));
			$this->assertTrue($obj->istype("Dummy2"));
		}
		$objmix = ObjectUtil::mixin($obj,new Dummy3());
		
		if($this->assertTrue(is_object($objmix),var_export($obj,true))){		
			$this->assertEquals(">>>>1",$objmix->id());
			$this->assertEquals("[hoge]",$objmix->value());
			$this->assertEquals("<rhaco>",$objmix->type());
			$this->assertEquals(3,$objmix->calc(1,2));
			$this->assertEquals("hoge",$objmix->str());
			$this->assertTrue(Variable::istype("Dummy1",$objmix));
			$this->assertFalse(Variable::istype("Dummy2",$objmix));
			$this->assertTrue($objmix->istype("Dummy1"),"Dummy1");
			$this->assertTrue($objmix->istype("Dummy2"),"Dummy2");
			$this->assertTrue($objmix->istype("Dummy3"),"Dummy3");
			$this->assertTrue(method_exists($objmix,"str"));
			$this->assertEquals("hoge",$objmix->str());
			
			$objmix->setTest("AAA");
			$this->assertEquals("AAA",$objmix->test);
			$objmixfunc = ObjectUtil::mixin($objmix,array("setTest",'$a','$this->test = $a."B";'));
			$this->assertEquals("AAA",$objmixfunc->test);
			$objmixfunc->setTest("CCC");		
			$this->assertEquals("CCCB",$objmixfunc->test);
		}
	}
	function testMixinAbbr(){
		$obj = V::mixin(new Dummy1(),new Dummy2(),array("calc",'$a,$b','return $a + $b;'));
		
		if($this->assertTrue(is_object($obj),var_export($obj,true))){
			$obj->id = 1;
			$obj->value = "hoge";
			$obj->type = "rhaco";
	
			$this->assertEquals(">>>>1",$obj->id());
			$this->assertEquals("[hoge]",$obj->value());
			$this->assertEquals("<rhaco>",$obj->type());
			$this->assertEquals(3,$obj->calc(1,2));
			$this->assertTrue(Variable::istype("Dummy1",$obj));
			$this->assertFalse(Variable::istype("Dummy2",$obj));
			$this->assertTrue($obj->istype("Dummy1"));
			$this->assertTrue($obj->istype("Dummy2"));
		}
	}	

	function testHashConvObject(){
		$obj = new Dummy1();
		$obj->id = 100;
		$obj->value = "hogehoge";
		
		$this->assertEquals(100,$obj->id);
		$this->assertEquals("hogehoge",$obj->value);
		$hobj = ObjectUtil::hashConvObject(array("id"=>99,"value"=>"rhaco"),$obj);
		
		$this->assertEquals(-99,$hobj->id);
		$this->assertEquals("rhaco",$hobj->value);
		$this->assertEquals(-99,$obj->id);
		$this->assertEquals("rhaco",$obj->value);


		$obj = new Dummy1();
		$obj->id = 100;
		$obj->value = "hogehoge";
		
		$this->assertEquals(100,$obj->id);
		$this->assertEquals("hogehoge",$obj->value);
		$hobj = ObjectUtil::hashConvObject(array("id"=>99,"value"=>"rhaco"),$obj,false);
		
		$this->assertEquals(99,$hobj->id);
		$this->assertEquals("rhaco",$hobj->value);
		$this->assertEquals(99,$obj->id);
		$this->assertEquals("rhaco",$obj->value);

		
		$obj = new Dummy5();
		$this->assertEquals("",$obj->hoge);
		$hobj = ObjectUtil::hashConvObject(array("abc_def"=>"abc"),$obj);
		$this->assertEquals("abc",$hobj->hoge);
		$this->assertEquals("abc",$obj->hoge);
	}
	function testHashConvObjectAbbr(){
		$obj = new Dummy1();
		$obj->id = 100;
		$obj->value = "hogehoge";
		
		$this->assertEquals(100,$obj->id);
		$this->assertEquals("hogehoge",$obj->value);
		$hobj = V::ho(array("id"=>99,"value"=>"rhaco"),$obj);
		
		$this->assertEquals(-99,$hobj->id);
		$this->assertEquals("rhaco",$hobj->value);
		$this->assertEquals(-99,$obj->id);
		$this->assertEquals("rhaco",$obj->value);


		$obj = new Dummy1();
		$obj->id = 100;
		$obj->value = "hogehoge";
		
		$this->assertEquals(100,$obj->id);
		$this->assertEquals("hogehoge",$obj->value);
		$hobj = V::ho(array("id"=>99,"value"=>"rhaco"),$obj,false);
		
		$this->assertEquals(99,$hobj->id);
		$this->assertEquals("rhaco",$hobj->value);
		$this->assertEquals(99,$obj->id);
		$this->assertEquals("rhaco",$obj->value);		
	}

	function testObjectConvHash(){
		$obj = new Dummy1();
		$obj->id = 100;
		$obj->value = "hogehoge";
		
		$hash = array("id"=>1,"value"=>"abc");
		$ohash = ObjectUtil::objectConvHash($obj,$hash);
		$this->assertEquals(array("id"=>100,"value"=>"hogehoge","test"=>"TEST"),$ohash);
		$this->assertEquals(array("id"=>1,"value"=>"abc"),$hash);
		
		$obj = new Dummy1();
		$obj->id = 100;
		$obj->value = "hogehoge";
		
		$hash = array("id"=>1,"value"=>"abc");
		$ohash = ObjectUtil::objectConvHash($obj,$hash,true);
		$this->assertEquals(array("id"=>100,"value"=>"hogehoge","test"=>"TEST","getid"=>100,"getvalue"=>"hogehoge--"),$ohash);
		$this->assertEquals(array("id"=>1,"value"=>"abc"),$hash);
	}
	function testObjectConvHashAbbr(){
		$obj = new Dummy1();
		$obj->id = 100;
		$obj->value = "hogehoge";
		
		$hash = array("id"=>1,"value"=>"abc");
		$ohash = V::oh($obj,$hash);
		$this->assertEquals(array("id"=>100,"value"=>"hogehoge","test"=>"TEST"),$ohash);
		$this->assertEquals(array("id"=>1,"value"=>"abc"),$hash);
		
		$obj = new Dummy1();
		$obj->id = 100;
		$obj->value = "hogehoge";
		
		$hash = array("id"=>1,"value"=>"abc");
		$ohash = V::oh($obj,$hash,true);
		$this->assertEquals(array("id"=>100,"value"=>"hogehoge","test"=>"TEST","getid"=>100,"getvalue"=>"hogehoge--"),$ohash);
		$this->assertEquals(array("id"=>1,"value"=>"abc"),$hash);
	}

	function testCopyProperties(){
		$obj1 = new Dummy1();
		$obj1->id = 100;
		$obj1->value = "hogehoge";
		
		$obj2 = new Dummy2();
		$obj2->id = 400;
		$obj2->type = "text";
		
		$this->assertEquals(400,$obj2->id);
		$this->assertEquals("text",$obj2->type);
		$obj = Variable::copyProperties($obj1,$obj2);
		
		$this->assertEquals(100,$obj1->id);
		$this->assertEquals("hogehoge",$obj1->value);
		$this->assertEquals(400,$obj2->id);
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
		$obj = Variable::copyProperties($obj1,$obj2,true);
		
		$this->assertEquals(100,$obj1->id);
		$this->assertEquals("hogehoge",$obj1->value);
		$this->assertEquals(400,$obj2->id);
		$this->assertEquals("text",$obj2->type);
		
		$this->assertEquals(100,$obj->id);
		$this->assertEquals("text",$obj->type);
	}

	function testCopyPropertiesAbbr(){
		$obj1 = new Dummy1();
		$obj1->id = 100;
		$obj1->value = "hogehoge";
		
		$obj2 = new Dummy2();
		$obj2->id = 400;
		$obj2->type = "text";
		
		$this->assertEquals(400,$obj2->id);
		$this->assertEquals("text",$obj2->type);
		$obj = V::cp($obj1,$obj2);
		
		$this->assertEquals(100,$obj1->id);
		$this->assertEquals("hogehoge",$obj1->value);
		$this->assertEquals(400,$obj2->id);
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
		$obj = V::cp($obj1,$obj2,true);
		
		$this->assertEquals(100,$obj1->id);
		$this->assertEquals("hogehoge",$obj1->value);
		$this->assertEquals(400,$obj2->id);
		$this->assertEquals("text",$obj2->type);
		
		$this->assertEquals(100,$obj->id);
		$this->assertEquals("text",$obj->type);
	}	
	


	function testToSimpleTag(){
		$obj1 = new Dummy1();
		$obj1->id = 100;
		$obj1->value = "hogehoge";
		
		$tag = Variable::toSimpleTag("hoge",$obj1);
		$this->assertEquals('<hoge><id>100</id><value>hogehoge</value><test>TEST</test><getid>100</getid><getvalue>hogehoge--</getvalue></hoge>',$tag->get());
		
		$tag = Variable::toSimpleTag("hoge","hogehoge");
		$this->assertEquals('<hoge>hogehoge</hoge>',$tag->get());

		$tag = Variable::toSimpleTag("hoge",array("abc"=>123,"def"=>"AAA"));
		$this->assertEquals('<hoge><abc>123</abc><def>AAA</def></hoge>',$tag->get());
		
		$tag = Variable::toSimpleTag("hoge",array("abc"=>123,"def"=>"AAA","ghi"=>array("AAA"=>1,"BBB"=>2)));
		$this->assertEquals('<hoge><abc>123</abc><def>AAA</def><ghi><AAA>1</AAA><BBB>2</BBB></ghi></hoge>',$tag->get());
		
		$tag = Variable::toSimpleTag("tag",array("hoge"=>array("data"=>array(1,2,3))));
		$this->assertEquals('<tag><hoge><data>1</data><data>2</data><data>3</data></hoge></tag>',$tag->get());
	}
	function testToJson(){
		$this->assertEquals('{"id":0,"value":"","test":"TEST"}',Variable::toJson(new Dummy1()));
		$this->assertEquals('{"id":0,"type":""}',Variable::toJson(new Dummy2()));
		
		$this->assertEquals('{"id":0,"value":"","test":"TEST","getid":0,"getvalue":"--"}',Variable::toJson(new Dummy1(),true));
		$this->assertEquals('{"id":0,"type":"","getid":-100}',Variable::toJson(new Dummy2(),true));
	}	
	function testToHttpQuery(){
		Rhaco::import("Dummy4");
		$list = array("A"=>"aaaa",
						"B"=>array("Ba"=>1,
								"Bb"=>array("Bb1"=>"cccc",
											"Bb2"=>array(0=>"dddd")
								),
								"Bc"=>new Dummy4(),
								"Bd"=>true,
								"Be"=>null,
							),
					);
		$this->assertEquals("rhaco[A]=aaaa&rhaco[B][Ba]=1&rhaco[B][Bb][Bb1]=cccc&rhaco[B][Bb][Bb2][0]=dddd&rhaco[B][Bc][aa]=1&rhaco[B][Bc][bb]=2&rhaco[B][Bd]=true&rhaco[B][Be]=&",
							Variable::toHttpQuery($list,"rhaco",true,true));
		$this->assertEquals("rhaco[A]=aaaa&rhaco[B][Ba]=1&rhaco[B][Bb][Bb1]=cccc&rhaco[B][Bb][Bb2][0]=dddd&rhaco[B][Bc][aa]=1&rhaco[B][Bc][bb]=2&rhaco[B][Bd]=true&",
							Variable::toHttpQuery($list,"rhaco",false,true));

		$this->assertEquals("rhaco[A]=aaaa&rhaco[B][Ba]=1&rhaco[B][Bb][Bb1]=cccc&rhaco[B][Bb][Bb2][0]=dddd&rhaco[B][Bc]=&rhaco[B][Bd]=true&rhaco[B][Be]=&",
							Variable::toHttpQuery($list,"rhaco"));
		$this->assertEquals("rhaco[A]=aaaa&rhaco[B][Ba]=1&rhaco[B][Bb][Bb1]=cccc&rhaco[B][Bb][Bb2][0]=dddd&rhaco[B][Bc]=&rhaco[B][Bd]=true&",
							Variable::toHttpQuery($list,"rhaco",false));
	}
	
	function testCopy(){
		Rhaco::import("Dummy4");
		$obj = new Dummy4();
		$obj->aa = 5;
		
		$copy = Variable::copy($obj);
		$copy->aa = 10;

		$this->assertEquals(10,$copy->aa);
		$this->assertEquals(5,$obj->aa);
	}
}
?>