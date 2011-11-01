<?php
include_once '__init__.php';
Rhaco::import("generic.Flow");


$flow = new Flow("filter.CustomFlowFilter","filter.CustomFlowFilterBefore");
$flow->write("flow.html");

L::d(is_numeric(123),is_numeric(123.45),is_numeric("123"),is_numeric("123.45"),is_numeric("hoge"));
?>