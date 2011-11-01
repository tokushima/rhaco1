<?php
Rhaco::import("network.http.Http");
Rhaco::import("util.UnitTest");

class HttpTest extends UnitTest {
	function testRequest(){
		list($head,$body) = Http::request(Rhaco::url("index.php"),"POST",array("var"=>array("postdata"=>"mogemoge")));
		$this->assertEquals('mogemoge<html><body><form><input type="text" name="inputdata" value="input!" /><input type="submit" /></form></body></html>',$body);
	}
	function testGet(){
		$this->assertEquals('hogehoge<html><body><form><input type="text" name="inputdata" value="input!" /><input type="submit" /></form></body></html>',Http::get(Rhaco::url("index.php?getdata=hogehoge")));
	}
	function testPost(){
		$this->assertEquals('mogemoge<html><body><form><input type="text" name="inputdata" value="input!" /><input type="submit" /></form></body></html>',Http::post(Rhaco::url("index.php"),array("postdata"=>"mogemoge")));
	}
	function testPostGet(){
		$this->assertEquals('hogehogemogemoge<html><body><form><input type="text" name="inputdata" value="input!" /><input type="submit" /></form></body></html>',Http::post(Rhaco::url("index.php?getdata=hogehoge"),array("postdata"=>"mogemoge")));
	}
}
?>