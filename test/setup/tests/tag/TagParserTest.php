<?php
Rhaco::import("tag.TagParser");
Rhaco::import("util.UnitTest");
Rhaco::import("io.FileUtil");

class TagParserTest extends UnitTest {
	function testRead(){
		$parser = new TagParser();
		$result = <<< __RESULT__
hey
hehey
jack

__RESULT__;
		$this->assertEquals($result,$parser->read("tag/tag.html",array("hoge"=>"hey","hogehoge"=>"hehey","hogelist"=>array("tom","jack","mike"))));
	}
	function testWrite(){
		$parser = new TagParser();
		$result = <<< __RESULT__
hey
hehey
jack

__RESULT__;
		
		$snap = new Snapshot();
			$parser->write("tag/tag.html",array("hoge"=>"hey","hogehoge"=>"hehey","hogelist"=>array("tom","jack","mike")));
		$return = $snap->get();
		$this->assertEquals($result,$return);
	}
	function testParse(){
		$parser = new TagParser();
		$result = <<< __RESULT__
hey
hehey
jack

__RESULT__;
		$this->assertEquals($result,$parser->parse(FileUtil::read(Rhaco::templatepath("tag/tag.html")),array("hoge"=>"hey","hogehoge"=>"hehey","hogelist"=>array("tom","jack","mike"))));
	}
}
?>