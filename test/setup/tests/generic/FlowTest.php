<?php
Rhaco::import("generic.Flow");
Rhaco::import("util.UnitTest");
Rhaco::import("io.FileUtil");

class FlowTest extends UnitTest {
	function testWrite(){
		$parser = new Flow();
		$result = <<< __RESULT__
hey
hehey
jack

__RESULT__;
		
		$snap = new Snapshot();
			$parser->setVariable("hoge","hey");
			$parser->setVariable("hogehoge","hehey");
			$parser->setVariable("hogelist",array("tom","jack","mike"));
		
			$parser->write("tag/tag.html");
		$return = $snap->get();
		$this->assertEquals($result,$return);
	}
}
?>