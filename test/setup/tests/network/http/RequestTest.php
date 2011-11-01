<?php
Rhaco::import("network.http.Request");
Rhaco::import("util.UnitTest");
Rhaco::import("Dummy1");

class RequestTest extends UnitTest {
	function testParseObject(){
		$req = new Request();
		$req->clearVariable();

		$obj = new Dummy1();
		$obj->id = 100;
		$obj->value = "hogehoge";
		
		$req->parseObject($obj);
		$this->assertEquals(array("id"=>100,"value"=>"hogehoge","test"=>"TEST"),$req->getVariable());

		$req->parseObject($obj,true);
		$this->assertEquals(array("id"=>100,"value"=>"hogehoge","test"=>"TEST","getid"=>100,"getvalue"=>"hogehoge--"),$req->getVariable());
	}
	function testToObject(){
		$req = new Request();
		$req->setVariable("id",100);
		$req->setVariable("value","hoge");
		
		$obj = new Dummy1();
		$obj->setId(100);
		$obj->setValue("hoge");
		$this->assertEquals($obj,$req->toObject(new Dummy1()));
		
		$obj = new Dummy1();
		$obj->id = 100;
		$obj->value = "hoge";
		$this->assertEquals($obj,$req->toObject(new Dummy1(),false));		
	}
	function testClearVariable(){
		$req = new Request();
		$req->setVariable("id",100);
		$req->setVariable("value","hoge");

		$this->assertEquals(100,$req->getVariable("id"));
		$this->assertEquals("hoge",$req->getVariable("value"));
		
		$req->clearVariable();
		$this->assertEquals(null,$req->getVariable("id"));
		$this->assertEquals(null,$req->getVariable("value"));
		
		
		$req = new Request();
		$req->setVariable("id",100);
		$req->setVariable("value","hoge");

		$this->assertEquals(100,$req->getVariable("id"));
		$this->assertEquals("hoge",$req->getVariable("value"));
		
		$req->clearVariable("id");
		$this->assertEquals(null,$req->getVariable("id"));
		$this->assertEquals("hoge",$req->getVariable("value"));
		
	}
}
?>