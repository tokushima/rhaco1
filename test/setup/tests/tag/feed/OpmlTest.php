<?php
Rhaco::import("tag.feed.Opml");
Rhaco::import("io.FileUtil");
Rhaco::import("util.UnitTest");
Rhaco::import("network.http.Browser");

class OpmlTest extends UnitTest {
	function testSet(){
		$feed = new Opml();
		$feed->set(FileUtil::read(Rhaco::resource("feed/opml.xml")));
		$this->assertEquals("livedoor Reader Subscriptions",$feed->getTitle());
		$this->assertEquals("1.0",$feed->getVersion());
		$this->assertEquals("Mon, 19 May 2008 13:51:05 JST",$feed->getDateCreated());
		$this->assertEquals("rhaco",$feed->getOwnerName());
		$this->assertEquals(null,$feed->getOwnerEmail());
		
		$this->assertEquals(1,sizeof($feed->getOutline()),"opml set 1");
		$opml = $feed->getOutline();
		$this->assertEquals("Subscriptions",$opml[0]->getTitle());

		$this->assertEquals(3,sizeof($opml[0]->getXmlOutlines()),"opml set 2");
		$this->assertEquals(3,sizeof($opml[0]->getHtmlOutlines()),"opml set 3");
	}
	function testGet(){
		$feed = new Opml();
		$feed->set(FileUtil::read(Rhaco::resource("feed/opml.xml")));

		$this->assertEquals(FileUtil::read(Rhaco::resource("feed/opml_result.xml")),$feed->get());
//		FileUtil::write(Rhaco::resource("feed/opml_result.xml"),$feed->get());
	}

	function testOutput(){
		$b = new Browser();
		$this->assertEquals(<<< __OPML__
<?xml version="1.0" encoding="utf-8"?>

__OPML__
		.FileUtil::read(Rhaco::resource("feed/opml_result.xml")),$b->get(Rhaco::url("tag/feed/OpmlOutput.php")));

		$this->assertTrue(strpos($b->header(0),'Content-Type: application/xml; name=') !== false);
	}
}
?>