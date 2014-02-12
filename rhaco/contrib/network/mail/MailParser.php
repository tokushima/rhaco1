<?php
Rhaco::import("lang.Variable");
/**
 * メールテンプレートを解析する
 *
 * @author Kentaro YABE
 * @license New BSD License
 * @copyright Copyright 2008- rhaco project. All rights reserved.
 */
class MailParser{
	var $sender;
	var $login;
	var $password;
	var $from;
	var $name;
	var $to;
	var $cc;
	var $bcc;
	var $returnpath;
	var $subject;
	var $body;
	var $html;
	
	/**
	 * コンストラクタ
	 *
	 * @param string $text
	 * @param boolean $html
	 * @return MailParser
	 */
	function MailParser($text="",$html=false){
		$this->__init__($text,$html);
	}
	function __init__($text,$html=false){
		$this->clear();
		if($text) $this->parse($text,$html);
		$this->sender("network.mail.Mail","","");
	}
	
	/**
	 * メール送信ライブラリ設定
	 * 
	 * @param string $path
	 * @param string $login
	 * @param string $password
	 */
	function sender($path,$login="",$password=""){
		/*** #pass */
		$this->sender = $path;
		$this->login = $login;
		$this->password = $password;
	}
	
	/**
	 * 解析結果をクリア
	 */
	function clear(){
		/*** #pass */
		$this->from = "";
		$this->name = "";
		$this->to = array();
		$this->cc = array();
		$this->bcc = array();
		$this->returnpath = "";
		$this->subject = "";
		$this->body = "";
		$this->html = false;
	}
	
	/**
	 * 解析結果を適用したMailインスタンスを取得
	 *
	 * @param string $text
	 * @param boolean $html
	 * @return Mail
	 */
	function mailer($text="",$html=false){
		/*** #viewing */
		if($text && !$this->parse($text,$html)){
			return false;
		}
		$mail = Rhaco::obj($this->sender,$this->from,$this->name);
		if(Variable::istype("Mail",$mail)){
			foreach($this->to as $address){
				$mail->to($address[0],$address[1]);
			}
			
			foreach($this->cc as $address){
				$mail->cc($address[0],$address[1]);
			}
			
			foreach($this->bcc as $address){
				$mail->bcc($address[0],$address[1]);
			}
			
			if($this->returnpath) $mail->returnpath($this->returnpath);
			
			$mail->subject($this->subject);
			if($this->html){
				$mail->html($this->body);
			}else{
				$mail->message($this->body);
			}
			
			if($this->login){
				$mail->login = $this->login;
				$mail->password = $this->password;
			}
			return $mail;
		}
	}
	
	/**
	 * メールテンプレートを解析する
	 *
	 * @param string $text
	 * @param boolean $html
	 * @return boolean
	 */
	function parse($text,$html=false){
		/***
		 * $text = "To:rhli@rhaco.org\r\n";
		 * $text.= "To:rhli@rhaco-users.jp\r\n";
		 * $text.= "From:るり<rhli@rhaco.org>\r\n";
		 * $text.= "Cc:rhli@rhaco.org,\"rhli, hoshino\" <rhli.hoshino@rhaco.org>\r\n";
		 * $text.= "Cc:rhli@rhaco-users.jp\r\n";
		 * $text.= "Bcc:るりるり<rhlirhli@rhaco.org>\r\n";
		 * $text.= "Bcc:rhli@rhaco-users.jp\r\n";
		 * $text.= "Return-Path:rhli@rhaco.org\r\n";
		 * $text.= "Subject:馬鹿ばっか\r\n";
		 * $text.= "\r\n";
		 * $text.= "めーるてんぷれだよ！\r\n\r\nふろむ るり";
		 * 
		 * $parser = new MailParser();
		 * $parser->parse($text);
		 * 
		 * eq(array("rhli@rhaco.org",""), array_shift($parser->to));
		 * eq(array("rhli@rhaco-users.jp",""), array_shift($parser->to));
		 * eq("rhli@rhaco.org", $parser->from);
		 * eq("るり",$parser->name);
		 * eq(array("rhli@rhaco.org",""), array_shift($parser->cc));
		 * eq(array("rhli.hoshino@rhaco.org","rhli, hoshino"), array_shift($parser->cc));
		 * eq(array("rhli@rhaco-users.jp",""), array_shift($parser->cc));
		 * eq(array("rhlirhli@rhaco.org","るりるり"), array_shift($parser->bcc));
		 * eq(array("rhli@rhaco-users.jp",""), array_shift($parser->bcc));
		 * eq("rhli@rhaco.org",$parser->returnpath);
		 * eq("馬鹿ばっか",$parser->subject);
		 * eq("めーるてんぷれだよ！\r\n\r\nふろむ るり",$parser->body);
		 * 
		 * $text = "Cc: \r\n";
		 * $text.= "\r\n";
		 * $text.= "めーるてんぷれだよ！\r\n\r\nふろむ るり";
		 * 
		 * $parser->parse($text);
		 * 
		 * eq(array(), $parser->cc);
		 */
		$this->clear();
		if(!preg_match("/^(.*?)\r?\n\r?\n(.*)/s", $text, $matches)){
			$this->clear();
			return false;
		}

		if(!$this->_parseHeader($matches[1])){
			$this->clear();
			return false;
		}
		$this->html = $html ? true : false;
		$this->body = $matches[2];
		return true;
	}
	
	/**
	 * ヘッダを解析する
	 * 
	 * @param string $headers
	 */
	function _parseHeader($headers){
		/*** #pass */
		foreach(explode("\n",StringUtil::toULD($headers)) as $header){
			if(!preg_match("/^(.+?):(.*)$/",$header,$matches)){
				return false;
			}
			$value = trim($matches[2]);
			if($value=="") continue;
			switch(strtolower($matches[1])){
				case "subject":
					$this->subject = $value;
					break;
				case "to":
					$addresses = $this->_parseAddressList($value);
					if($addresses === false) return false;
					$this->to = array_merge($this->to,$addresses);
					break;
				case "from":
					$address = $this->_parseAddress($value);
					if($address === false) return false;
					list($this->from,$this->name) = $address;
					break;
				case "cc":
					$addresses = $this->_parseAddressList($value);
					if($addresses === false) return false;
					$this->cc = array_merge($this->cc,$addresses);
					break;
				case "bcc":
					$addresses = $this->_parseAddressList($value);
					if($addresses === false) return false;
					$this->bcc = array_merge($this->bcc,$addresses);
					break;
				case "return-path":
					$address = $this->_parseAddress($value);
					if($address === false) return false;
					$this->returnpath = $address[0];
					break;
			}
		}
		return true;
	}
	
	/**
	 * メールアドレスリストを簡易解析
	 * 
	 * @param string $list
	 * @return array
	 */
	function _parseAddressList($list){
		/*** #pass */
		$result = array();
		$list = explode(",",trim($list," \r\n\r\0\x0B,"));
		while($address=current($list)){
			while(($parsed=$this->_parseAddress($address))==false){
				$parts = next($list);
				if($parts===false) return false;
				$address .= ",".$parts;
			}
			$result[] = $parsed;
			next($list);
		}
		return $result;
	}
	
	/**
	 * メールアドレスを簡易解析
	 * 
	 * @param string $address
	 * @return array
	 */
	function _parseAddress($address){
		/*** #pass */
		$address = trim($address);
		$email = "[\x01-\x7F]+@(?:[A-Z0-9-]+\.)+[A-Z]{2,6}";
		if(preg_match("/^{$email}$/i",$address)){
			return array($address,"");
		}elseif(preg_match("/(.+?)\s*<\s*({$email})\s*>/i",$address,$matches)){
			return array($matches[2],trim($matches[1],"\""));
		}
		return false;
	}
}
?>