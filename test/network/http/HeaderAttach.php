<?php
include_once '__init__.php';
Rhaco::import("network.http.Header");
Rhaco::import("io.model.File");

Logger::disableDisplay();
Header::attach(new File(Rhaco::resource("dummy.html")),"hoge.php");
?>