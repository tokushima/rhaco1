<?php
include_once '__init__.php';
Rhaco::import("network.http.Header");
Header::write(array("Context-Rhaco"=>"rhacophorus","Remote-Url"=>"http://rhaco.org"));
?>