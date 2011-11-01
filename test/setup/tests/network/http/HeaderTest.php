<?php
Rhaco::import("network.http.Header");
Rhaco::import("network.http.Browser");
Rhaco::import("util.UnitTest");

class HeaderTest extends UnitTest {
	function testRedirect(){
		$b = new Browser();
		$this->assertEquals("b",$b->get(Rhaco::url("network/http/HeaderRedirect.php")));
		$this->assertEquals("abc",$b->get(Rhaco::url("network/http/HeaderRedirectVar.php")));
	}
	function testAttach(){
		$b = new Browser();
		$this->assertEquals("hoge",$b->get(Rhaco::url("network/http/HeaderAttach.php")));
		$this->assertTrue(strpos(strtolower($b->header()),"content-disposition: attachment; filename=hoge.php") !== false);
		$this->assertTrue(strpos(strtolower($b->header()),"content-length: 4") !== false);
		$this->assertTrue(strpos(strtolower($b->header()),"content-type: application/octet-stream; name=hoge.php") !== false);
	}
	function testInline(){
		$b = new Browser();
		$this->assertEquals("hoge",$b->get(Rhaco::url("network/http/HeaderInline.php")));
		$this->assertTrue(strpos(strtolower($b->header()),"content-disposition: inline; filename=hoge.php") !== false);
		$this->assertTrue(strpos(strtolower($b->header()),"content-length: 4") !== false);
		$this->assertTrue(strpos(strtolower($b->header()),"content-type: image/jpeg; name=hoge.php") !== false);
	}
	function testWrite(){
		$b = new Browser();
		$b->get(Rhaco::url("network/http/HeaderWrite.php"));
		$this->assertTrue(strpos($b->header(),"Context-Rhaco: rhacophorus") !== false);
		$this->assertTrue(strpos($b->header(),"Remote-Url: http://rhaco.org") !== false);
	}
}
?>