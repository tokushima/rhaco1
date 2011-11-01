<?php
Rhaco::import("tag.feed.Rss20");
Rhaco::import("io.FileUtil");
Rhaco::import("util.UnitTest");

class RssTest extends UnitTest {
	function testOutput(){
		$b = new Browser();
		$this->assertEquals(<<< __RSS__
<?xml version="1.0" encoding="utf-8"?>

__RSS__
,$b->get(Rhaco::url("tag/feed/RssOutput.php")));

		$this->assertTrue(strpos($b->header(0),'Content-Type: application/rss+xml; name=') !== false);
	}
}
?>