<?php
Rhaco::import("tag.feed.Atom10");
Rhaco::import("io.FileUtil");
Rhaco::import("util.UnitTest");

class Atom10Test extends UnitTest {
	function testSet(){
		$feed = new Atom10();
		$feed->set(FileUtil::read(Rhaco::resource("feed/atom.xml")));
		$this->assertEquals("スパムとか",$feed->getTitle());
		$this->assertEquals("http://www.everes.net/",$feed->getId());
		$this->assertEquals(2,sizeof($feed->getLink()));
		$this->assertEquals(8,sizeof($feed->getEntry()));
	}

	function testGet(){
		$feed = new Atom10();
		$feed->set(FileUtil::read(Rhaco::resource("feed/atom.xml")));

		$this->assertEquals(FileUtil::read(Rhaco::resource("feed/atom_result.xml")),$feed->get());		
//		FileUtil::write(Rhaco::resource("feed/atom_result.xml"),$feed->get());
	}
}
?>