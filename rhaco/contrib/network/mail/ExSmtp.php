<?php
Rhaco::import("network.mail.SMTP");

/**
 * SMTP拡張 Yahoo mail/Hotmail
 *
 * @author @author SHIGETA Takeshiro
 * @license New BSD License
 * @copyright Copyright 2008- rhaco project. All rights reserved.
 */
class ExSmtp extends SMTP{
	function sendGmail($id,$pass,$mail){
		$s = new SMTP('tls://smtp.gmail.com',465);
		if($s->login($id,$pass)){
			if(is_array($mail)){
				foreach($mail as $m){
					$s->mail($m);
				}
				$s->logout();
				return true;
			}else{
				$s->mail($mail);
				$s->logout();
				return true;
			}
		}
		return false;
	}

	function sendYahoomail($pop_id,$pop_pass,$mail){
		$s = new SMTP('smtp.mail.yahoo.co.jp',25);
		if($s->pop($pop_id,$pop_pass,'pop.mail.yahoo.co.jp',110)){
			if(is_array($mail)){
				foreach($mail as $m){
					$s->mail($m);
				}
				$s->logout();
				return true;
			}else{
				$s->mail($mail);
				$s->logout();
				return true;
			}
		}
		return false;
	}

	function sendHotmail($id,$pass,$mail){
		$s = new SMTP('tls://smtp.live.com',25);
		if($s->login($id,$pass)){
			if(is_array($mail)){
				foreach($mail as $m){
					$s->mail($m);
				}
				$s->logout();
				return true;
			}else{
				$s->mail($mail);
				$s->logout();
				return true;
			}
		}
		return false;
	}
}
?>