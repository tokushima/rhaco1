<?php
Rhaco::import('network.http.ServiceRestAPIBase');
Rhaco::import('tag.model.SimpleTag');
/**
 * OutputzAPI
 *
 * @author  riaf <riafweb@gmail.com>
 * @package arbo
 * @license New BSD License
 * @version $Id$
 */
class OutputzAPI extends ServiceRestAPIBase
{
	var $url = 'http://outputz.com/api/';
	var $method;
	var $key;
	
	function post($uri, $size){
		$this->method = 'post';
		$r = parent::post(array('uri' => $uri, 'size' => $size, 'key' => $this->key));
		if(is_null($r)){
			return ExceptionTrigger::raise(new GenericException($this->browser->body()));
		}
		return true;
	}
	
	function OutputzAPI($key){
		parent::ServiceRestAPIBase();
		$this->setKey($key);
	}
	function setKey($key){
		$this->key = $key;
	}
	function buildUrl($hash=array()){
		return parent::buildUrl($hash, array(), $this->method);
	}
}