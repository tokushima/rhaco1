<?php
Rhaco::import("tag.feed.FeedParser");
Rhaco::import("io.FileUtil");
Rhaco::import("util.UnitTest");

class FeedParserTest extends UnitTest {
	function testParserRss20(){
		$feed = FeedParser::parse(FileUtil::read(Rhaco::resource("feed/rss20.xml")));

		$channel = $feed->getChannel();
		$this->assertEquals("rhacoとか",$channel->getTitle());
		$this->assertEquals("http://d.hatena.ne.jp/rhaco/",$channel->getLink());
		$this->assertEquals(7,sizeof($channel->getItem()));

		$this->assertEquals(FileUtil::read(Rhaco::resource("feed/rss20_result.xml")),$feed->get());
//		FileUtil::write(Rhaco::resource("feed/atom10_to_rss20_result.xml"),$feed->get());
	}
	function testParserAtom10(){
		$feed = FeedParser::parse(FileUtil::read(Rhaco::resource("feed/atom.xml")));

		$channel = $feed->getChannel();
		$this->assertEquals("スパムとか",$channel->getTitle());
		$this->assertEquals("http://www.everes.net/",$channel->getLink());
		$this->assertEquals(8,sizeof($channel->getItem()));
		
		$this->assertEquals(FileUtil::read(Rhaco::resource("feed/atom10_to_rss20_result.xml")),$feed->get());
	}

	function testReadrRss20(){
		$feed = FeedParser::read(Rhaco::url("resources/feed/rss20.xml"));

		$channel = $feed->getChannel();
		$this->assertEquals("rhacoとか",$channel->getTitle());
		$this->assertEquals("http://d.hatena.ne.jp/rhaco/",$channel->getLink());
		$this->assertEquals(7,sizeof($channel->getItem()));

		$this->assertEquals(FileUtil::read(Rhaco::resource("feed/rss20_result.xml")),$feed->get());		
	}
	function testreadAtom10(){
		$feed = FeedParser::read(Rhaco::url("resources/feed/atom.xml"));

		$channel = $feed->getChannel();
		$this->assertEquals("スパムとか",$channel->getTitle());
		$this->assertEquals("http://www.everes.net/",$channel->getLink());
		$this->assertEquals(8,sizeof($channel->getItem()));
		
		$this->assertEquals(FileUtil::read(Rhaco::resource("feed/atom10_to_rss20_result.xml")),$feed->get());
	}
	
	function testItemRss20(){
		$items = FeedParser::getItem(Rhaco::url("resources/feed/rss20.xml"));
		$this->assertEquals(7,sizeof($items));
	}
}
?>