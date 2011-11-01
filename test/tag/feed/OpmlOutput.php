<?php
include_once("__init__.php");
Rhaco::import("tag.feed.Opml");
Rhaco::import("util.Logger");

Logger::disableDisplay();
$feed = new Opml();
$feed->set(FileUtil::read(Rhaco::resource("feed/opml.xml")));

$feed->output();
?>