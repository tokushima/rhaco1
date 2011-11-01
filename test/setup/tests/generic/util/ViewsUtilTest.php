<?php
Rhaco::import("util.UnitTest");
Rhaco::import("model.GenericTest");
Rhaco::import("generic.util.ViewsUtil");

class ViewsUtilTest extends UnitTest {
	function testColumnString(){
		$db = new DbUtil(GenericTest::connection());
		$v = new ViewsUtil($db);
		$obj = $db->get(new GenericTest(1));
		$this->assertEquals('<textarea class="text" name="body" cols="70" rows="30">ラコは変態の変態によるDjangoのための宣伝フレームワークです</textarea>',$v->columnString($obj,"text_word"));
		$db->close();
	}
}
