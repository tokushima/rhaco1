<?php
Rhaco::import("exception.ExceptionTrigger");
Rhaco::import("exception.model.GenericException");
Rhaco::import("exception.model.NotConnectionException");
Rhaco::import("util.Logger");

/**
 * Socket
 *
 * @author SHIGETA Takeshiro
 * @license New BSD License
 * @copyright Copyright 2007- rhaco project. All rights reserved.
 */
class Socket {
	var $fp;
	var $delimiter='';

	/**
	 * 接続
	 *
	 * @param string $host
	 * @param integer $port
	 * @param integer $timeout
	 * @return boolean
	 */
	function open($host,$port,$timeout=30){
		$this->fp = @fsockopen($host,$port,$errno,$errstr,$timeout);
		if(!$this->fp){
			ExceptionTrigger::raise(new NotConnectionException("socket"));
			return false;
		}
		Logger::deep_debug("Socket is connected");
		return true;
	}

	/**
	 * 切断
	 *
	 * @return boolean
	 */
	function close(){
		if(fclose($this->fp)){
			Logger::deep_debug("Socket is disconnected");
			return true;
		}else{
			ExceptionTrigger::raise(new GenericException("socket is not disconnected"));
			return false;
		}
	}

	/**
	 * 送信
	 *
	 * @param string $cmd
	 * @return string
	 */
	function command($cmd){
		if(!fwrite($this->fp,$cmd)){
			ExceptionTrigger::raise(new NotConnectionException("socket"));
			return false;
		}
		Logger::deep_debug("Command \"{$cmd}\" is sent.");
		return $this->getResponse();
	}

	/**
	 * 応答のデリミタを設定する
	 *
	 * @param string $str
	 */
	function setResponseDelimiter($str=''){
		$this->delimiter = $str;
	}

	/**
	 * 応答を取得
	 *
	 * @param integer $num 取得する行数
	 * @param boolean $interactive
	 * @return string
	 */
	function getResponse($num=null){
		$num = intval($num);
		if($num > 0){
			$response = '';
			for($i=0;$i<$num;$i++){
				$response .= fgets($this->fp);
				if(feof($this->fp)){
					break;
				}
			}
			Logger::deep_debug("Response \"{$response}\" is given.");
			return $response;
		}else{
			$responseText = "";
			while($response = $this->getResponse(1)){
				$responseText .= $response;
				if(preg_match('@'.$this->delimiter.'@im',trim($response))){
					break;
				}
				if(feof($this->fp)){
					break;
				}
			}
			return $responseText;
		}
	}
}
?>