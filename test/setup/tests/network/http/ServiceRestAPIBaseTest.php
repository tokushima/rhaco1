<?php
Rhaco::import("network.http.ServiceRestAPIBase");
Rhaco::import("util.UnitTest");

class ServiceRestAPIBaseTest extends UnitTest {
	function testGet(){
		$b = new ServiceRestAPIBase(Rhaco::url("index.php"));
		$this->assertEquals('hogehoge<html><body><form><input type="text" name="inputdata" value="input!" /><input type="submit" /></form></body></html>',$b->get(array("getdata"=>"hogehoge")));
	}
	function testPost(){
		$b = new ServiceRestAPIBase(Rhaco::url("index.php"));
		$this->assertEquals('mogemoge<html><body><form><input type="text" name="inputdata" value="input!" /><input type="submit" /></form></body></html>',$b->post(array("postdata"=>"mogemoge")));
	}
}
?>