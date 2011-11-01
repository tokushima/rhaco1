<?php
Rhaco::import("tag.feed.Rss20");
Rhaco::import("io.FileUtil");
Rhaco::import("util.UnitTest");

class Rss20Test extends UnitTest {
	function testSet(){
		$feed = new Rss20();
		$feed->set(FileUtil::read(Rhaco::resource("feed/rss20.xml")));
		$channel = $feed->getChannel();
		$this->assertEquals("rhacoとか",$channel->getTitle());
		$this->assertEquals("http://d.hatena.ne.jp/rhaco/",$channel->getLink());
		$this->assertEquals(7,sizeof($channel->getItem()));
	}

	function testGet(){
		$feed = new Rss20();
		$feed->set(FileUtil::read(Rhaco::resource("feed/rss20.xml")));

		$this->assertEquals(FileUtil::read(Rhaco::resource("feed/rss20_result.xml")),$feed->get());		
//		FileUtil::write(Rhaco::resource("feed/rss20_result.xml"),$feed->get());
	}
}
?>