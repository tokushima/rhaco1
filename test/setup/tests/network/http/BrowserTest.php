<?php
Rhaco::import("network.http.Browser");
Rhaco::import("util.UnitTest");

class BrowserTest extends UnitTest {
	function testGet(){
		$b = new Browser();
		$b->setVariable("getdata","hogehoge");
		$this->assertEquals('hogehoge<html><body><form><input type="text" name="inputdata" value="input!" /><input type="submit" /></form></body></html>',$b->get(Rhaco::url("index.php")));
	}
	function testPost(){
		$b = new Browser();
		$b->setVariable("postdata","mogemoge");
		$this->assertEquals('mogemoge<html><body><form><input type="text" name="inputdata" value="input!" /><input type="submit" /></form></body></html>',$b->post(Rhaco::url("index.php")));
	}
	function testPostGet(){
		$b = new Browser();
		$b->setVariable("getdata","hogehoge");
		$b->setVariable("postdata","mogemoge");
		$this->assertEquals('mogemoge<html><body><form><input type="text" name="inputdata" value="input!" /><input type="submit" /></form></body></html>',$b->post(Rhaco::url("index.php")));
	}
	function status(){
		$b = new Browser();
		$b->get(Rhaco::url("index.php"));
		$this->assertEquals(404,$b->status());
	}
	function testBody(){
		$b = new Browser();
		$b->get(Rhaco::url("index.php"));
		$this->assertEquals('<html><body><form><input type="text" name="inputdata" value="input!" /><input type="submit" /></form></body></html>',$b->body(0));		
	}
	function testHeader(){
		$b = new Browser();
		$b->get(Rhaco::url("index.php"));
		$this->assertNotEquals("",$b->header(0));
	}
	function testUrl(){
		$b = new Browser();
		$b->get(Rhaco::url("index.php"));
		$this->assertEquals(Rhaco::url("index.php"),$b->url(0));
	}
	function testSubmit(){
		$b = new Browser();
		$b->get(Rhaco::url("index.php"));
		$this->assertEquals('input!<html><body><form><input type="text" name="inputdata" value="input!" /><input type="submit" /></form></body></html>',$b->submit(0));		
	}
}
?>