<?php
Rhaco::import("tag.feed.NetscapeBookmark");
Rhaco::import("io.FileUtil");
Rhaco::import("util.UnitTest");
Rhaco::import("network.http.Browser");

class NetscapeBookmarkTest extends UnitTest {
	function testSet(){
		$feed = new NetscapeBookmark();
		$feed->set(FileUtil::read(Rhaco::resource("feed/bookmarks.html")));
		
		$this->assertEquals("Bookmarks",$feed->getTitle());
		$this->assertEquals("Bookmarks Herder",$feed->getHeader());
		$this->assertTrue(Variable::istype("NetscapeBookmarkBlock",$feed->getBlock()));
		
		$this->assertEquals(2,sizeof($feed->getItems()));
	}
	
	function testGet(){
		$feed = new NetscapeBookmark();
		$feed->set(FileUtil::read(Rhaco::resource("feed/bookmarks.html")));

		$this->assertEquals(FileUtil::read(Rhaco::resource("feed/netscape_result.xml")),$feed->get());
//		FileUtil::write(Rhaco::resource("feed/netscape_result.xml"),$feed->get());
	}
	
	function testOutput(){
		$b = new Browser();
		$this->assertEquals(<<< __OPML__
<?xml version="1.0" encoding="utf-8"?>

__OPML__
		.FileUtil::read(Rhaco::resource("feed/netscape_result.xml")),$b->get(Rhaco::url("tag/feed/NetscapeBookmarkOutput.php")));

		$this->assertTrue(strpos($b->header(0),'Content-Type: application/xml; name=') !== false);
	}
}
?>