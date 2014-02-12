<?php
include_once("__init__.php");
Rhaco::import("tag.feed.Rss");
Rhaco::import("util.Logger");

Logger::disableDisplay();
$rss = new Rss();
$rss->output();
?>