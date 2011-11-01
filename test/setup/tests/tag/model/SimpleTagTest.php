<?php
Rhaco::import("tag.model.SimpleTag");
Rhaco::import("util.UnitTest");
Rhaco::import("network.http.Browser");

class SimpleTagTest extends UnitTest {
	function testOutput(){
		$result = <<< XML
<?xml version="1.0" encoding="UTF-8"?>
<tag>hogehoge</tag>
XML;
		$browser = new Browser();
		$this->assertEquals($result,$browser->get(Rhaco::url("tag/model/SimpleTagOutput.php")));
	}
	
}
?>