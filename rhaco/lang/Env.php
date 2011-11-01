<?php
/**
 * 環境ユーティリティ
 * 
 * @author Kazutaka Tokushima
 * @license New BSD License
 * @copyright Copyright 2009- rhaco project. All rights reserved.
 */
class Env{
	/**
	 * 実行ファイルの名前を取得
	 * @return string
	 */
	function called(){
		/*** eq($_SERVER["SCRIPT_NAME"],Env::called()); */
		return $_SERVER["SCRIPT_NAME"];
	}
	/**
	 * 指定のバージョン以上のPHPか
	 * @param string $version
	 * @return unknown
	 */
	function isphp($version){
		return (version_compare(phpversion(),strval($version)) >= 0);
	}
	/**
	 * Windowsであればtrue
	 * @return boolean
	 */
	function w(){
		/*** eq(substr(PHP_OS,0,3) == 'WIN',Env::w()); */
		return (substr(PHP_OS,0,3) == 'WIN');
	}
	/**
	 * SSL通信であればtrue
	 * @return boolean
	 */
	function isSSL(){
		/*** #viewing */
		return isset($_SERVER["HTTPS"]) && ($_SERVER["HTTPS"] != "off");
	}
}
?>