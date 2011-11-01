<?php
include_once("__init__.php");
Rhaco::import("tag.model.SimpleTag");
Rhaco::import("util.Logger");

Logger::disableDisplay();
$tag = new SimpleTag("tag","hogehoge");
$tag->output();
Logger::enableDisplay();
?>