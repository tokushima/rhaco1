<?php
Rhaco::import("lang.Variable");
Rhaco::import("network.Socket");
Rhaco::import("network.mail.POP3");
Rhaco::import("util.Logger");
/**
 * SMTP
 * @author SHIGETA Takeshiro
 * @license New BSD License
 * @copyright Copyright 2007- rhaco project. All rights reserved.
 * @see http://www.ietf.org/rfc/rfc821.txt
 */
class SMTP extends Socket{
	var $responseText;
	var $host;
	var $port;
	var $id;
	var $pass;
	var $authtype=array();
	var $code;
	
	/**
	 * SMTP
	 * @param string $host
	 * @param integer $port
	 * @param integer $timeout
	 * @return SMTP
	 */
	function SMTP($host='',$port=25,$timeout=30){
		$this->host = $host;
		$this->port = $port;
		if($this->open($host,$port,$timeout)){
			$this->getResponse(1);
		}
	}
	/**
	 * ログイン
	 * @param string $id
	 * @param string $pass
	 * @return boolean
	 */
	function login($id="",$pass=""){
		$server = isset($_SERVER["SERVER_NAME"]) ? $_SERVER["SERVER_NAME"] : "localhost";
		if($this->cmd('EHLO '.$server) || $this->cmd('HELO '.$server)){
			if(preg_match('@starttls@im',$this->getText())){
				if(!$this->cmd('STARTTLS')){
					return false;
				}
			}
			if(preg_match('@auth\s(.*?)$@im',$this->getText(),$match)){
				$this->authtype = array_map('trim',explode(' ',$match[1]));
				if(empty($id) || empty($pass)) return false;
				$this->auth($id,$pass);
			}
			return true;	
		}
		return false;
	}
	/**
	 * 認証
	 * @param string $id
	 * @param string $pass
	 * @param string $type
	 * @return boolean
	 */
	function auth($id,$pass,$type="plain"){
		if(in_array(strtoupper($type),$this->authtype)){
			switch (strtoupper($type)){
				case 'PLAIN':
					$key = base64_encode($id."\0".$id."\0".$pass."\0");
					if($this->cmd("AUTH PLAIN $key")){
						return true;
					}
					break;
				case 'LOGIN':
					if($this->cmd("AUTH LOGIN $id $pass")){
						return true;
					}
					break;
				case 'CRAM-MD5':
					if($this->cmd("AUTH CRAM-MD5")){
						$tmp = explode(' ',$this->getText());
						$skey = $tmp[1];
						$key=base64_encode($id." ".md5(base64_decode($skey).$pass));
						if($this->cmd($key)){
								return true;
						}
					}
					break;
				case 'DIGEST-MD5'://incomplete
					if($this->cmd("AUTH DIGEST-MD5")){
						if($this->cmd($key)){
							if($this->getCode()=='503'){
								return true;
							}else{
								$challenge = base64_decode($this->getText());
								$md5digest = preg_replace('/=+$/','',base64_encode(pack('H*',md5($data)))); 
								return true;
							}
						}
					}
					break;
				default:
					break;
			}
			return false;
		}
	}
	/**
	 * POP before SMTP
	 * @param string $id
	 * @param string $pass
	 * @param string $phost
	 * @param integer $pport
	 * @param integer $timeout
	 * @return boolean
	 */
	function pop($id,$pass,$phost=null,$pport=110,$timeout=30){
		$phost = $phost ? $phost : $this->host;
		$p = new POP3($phost,$pport,$timeout);
		if($p->login($id,$pass)){
			$p->logout();
			if($this->login($id,$pass)){
				Logger::deep_debug("smtp login successful");
				return true;
			}
		}
		return false;
	}
	/**
	 * ログアウト
	 * @return boolean
	 */
	function logout(){
		if($this->cmd('QUIT')){
			if($this->close()){
				Logger::deep_debug("connection closed");
				return true;
			}
		}
	}
	/**
	 * メール送信
	 * @param Mail $mail
	 * @return string
	 */
	function mail($mail){
		if(Variable::istype("Mail",$mail)){
			if(!$this->cmd(sprintf("MAIL FROM: <%s>",$mail->from))){
				return;
			}
			foreach (array_keys($mail->to) as $to){
				if(!$this->cmd(sprintf("RCPT TO: <%s>",$to))){
					return;
				}
			}
			foreach (array_keys($mail->cc) as $cc){
				if(!$this->cmd(sprintf("RCPT TO: <%s>",$cc))){
					return;
				}
			}
			foreach (array_keys($mail->bcc) as $bcc){
				if(!$this->cmd(sprintf("RCPT TO: <%s>",$bcc))){
					return;
				}
			}
			if($this->cmd("DATA")){
				$manuscript = $mail->manuscript(true);
				if($this->cmd(preg_replace('/^\.(\r?\n)/m','..$1',$manuscript).".")){
					return $this->getText();
				}
			}
		}
	}
	/**
	 * RESET
	 * @return boolean
	 */
	function reset(){
		return $this->cmd('RSET');
	}
	/**
	 * PING
	 * @return boolean
	 */
	function ping(){
		return $this->cmd('NOOP');
	}
	/**
	 * コマンド送信
	 * @param string $cmd
	 * @return boolean
	 */
	function cmd($cmd){
		$this->code = null;
		$this->responseText = "";
		if(!fwrite($this->fp,$cmd."\r\n")){
			ExceptionTrigger::raise(new NotConnectionException("socket"));
			return false;
		}
		Logger::deep_debug("Command \"{$cmd}\" is sent.");
		while($response = $this->getResponse(1)){
			$this->responseText .= $response;
			if(preg_match("/^(\d+?)\s.*$/",$response,$matches)){
				$this->code = $matches[1];
				break;
			}
			if(feof($this->fp)){
				break;
			}
		}
		return $this->isOk();
	}
	/**
	 * 最終応答結果
	 * @return boolean
	 */
	function isOk(){
		$code = $this->getCode();
		if(!$code) return false;
		switch ($code[0]){
			case 4:
			case 5:
				Logger::warning($this->getText());
				return false;
			case 2:
			case 3:
				return true;
			default:
				return false;
		}
	}
	/**
	 * 最終応答コード
	 * @return integer
	 */
	function getCode(){
		return $this->code;
	}
	/**
	 * 最終応答テキスト
	 * @return string
	 */
	function getText(){
		return $this->responseText;
	}
}
?>