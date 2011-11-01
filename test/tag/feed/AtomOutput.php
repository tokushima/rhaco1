<?php
include_once("__init__.php");
Rhaco::import("tag.feed.Atom");
Rhaco::import("util.Logger");

Logger::disableDisplay();
$rss = new Atom();
$rss->output();
?>