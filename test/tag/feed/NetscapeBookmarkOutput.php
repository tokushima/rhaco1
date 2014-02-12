<?php
include_once("__init__.php");
Rhaco::import("tag.feed.NetscapeBookmark");
Rhaco::import("util.Logger");

Logger::disableDisplay();
$feed = new NetscapeBookmark();
$feed->set(FileUtil::read(Rhaco::resource("feed/bookmarks.html")));

$feed->output();
?>