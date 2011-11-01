<?php
Rhaco::import("tag.feed.Atom");
Rhaco::import("io.FileUtil");
Rhaco::import("util.UnitTest");
Rhaco::import("network.http.Browser");

class AtomTest extends UnitTest {
	function testOutput(){
		$b = new Browser();
		$this->assertEquals(<<< __RSS__
<?xml version="1.0" encoding="utf-8"?>

__RSS__
,$b->get(Rhaco::url("tag/feed/AtomOutput.php")));

		$this->assertTrue(strpos($b->header(0),'Content-Type: application/atom+xml; name=') !== false);
	}
}
?>