<?php
Rhaco::import("util.UnitTest");
Rhaco::import("io.Snapshot");

class SnapshotTest extends UnitTest {
	function testGet(){
		$snap = new Snapshot();
			print("hoge");
		$this->assertEquals("hoge",$snap->get());
	}
	function tearDown(){
		Snapshot::clear("hogehoge",array("abc"=>"hoge"));
	}
	function testSave(){
		$snap = new Snapshot();
			print("<?php print('hogehoge'); ?>");
		$snap->save("hogehoge",array("abc"=>"hoge"));
		$this->assertEquals("<?php print('hogehoge'); ?>",FileUtil::read($snap->path("hogehoge",array("abc"=>"hoge"))));
		$snap->close();
	}
	function testExist(){
		$this->assertFalse(Snapshot::exist("hogehoge",array("abc"=>"hoge")));
		$snap = new Snapshot();
			print("<?php print('hogehoge'); ?>");
		$snap->save("hogehoge",array("abc"=>"hoge"));
		$this->assertTrue(Snapshot::exist("hogehoge",array("abc"=>"hoge")));
		$snap->close();
	}
	function testLoad(){
		$snap = new Snapshot();
			print("<?php print('hogehoge'); ?>");
		$snap->save("hogehoge",array("abc"=>"hoge"));
		$this->assertTrue(Snapshot::exist("hogehoge",array("abc"=>"hoge")));
		$this->assertEquals("<?php print('hogehoge'); ?>",Snapshot::load("hogehoge",array("abc"=>"hoge")));
		$snap->close();
	}
	function testWrite(){
		$snap = new Snapshot();
			print("<?php print('hogehoge'); ?>");
		$snap->save("hogehoge",array("abc"=>"hoge"));
		$this->assertTrue(Snapshot::exist("hogehoge",array("abc"=>"hoge")));

		$snapwrite = new Snapshot();
			Snapshot::write("hogehoge",array("abc"=>"hoge"));
		$this->assertEquals("hogehoge",$snapwrite->get());
		$snapwrite->close();
		$snap->close();
	}
	function testClear(){
		$this->assertFalse(Snapshot::exist("hogehoge",array("abc"=>"hoge")));
		$snap = new Snapshot();
			print("<?php print('hogehoge'); ?>");
		$snap->save("hogehoge",array("abc"=>"hoge"));
		$this->assertTrue(Snapshot::exist("hogehoge",array("abc"=>"hoge")));
		Snapshot::clear("hogehoge",array("abc"=>"hoge"));
		$this->assertFalse(Snapshot::exist("hogehoge",array("abc"=>"hoge")));
		$snap->close();
	}
}
?>