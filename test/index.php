<?php
/**
 * リクエスト用アクション
 */
if(isset($_GET["getdata"])) print($_GET["getdata"]);
if(isset($_GET["inputdata"])) print($_GET["inputdata"]);
if(isset($_POST["postdata"])) print($_POST["postdata"]);
?>
<html><body><form><input type="text" name="inputdata" value="input!" /><input type="submit" /></form></body></html>