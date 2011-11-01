<?php
Rhaco::import("network.mail.Mail");
Rhaco::import("util.UnitTest");

class MailTest extends UnitTest{
	function testTo(){
		$mail = new Mail("rhli@rhaco.org", "rhli",true);
		$mail->to("rhli@rhaco.org", "rhli");
		$mail->subject("test");
		$mail->message("test mail");
		
		$manuscript  = "MIME-Version: 1.0\r\n";
		$manuscript .= "To: \"rhli\" <rhli@rhaco.org>\r\n";
		$manuscript .= "From: \"rhli\" <rhli@rhaco.org>\r\n";
		$manuscript .= "Return-Path: rhli@rhaco.org\r\n";
		$manuscript .= "Subject: test\r\n";
		$manuscript .= "Date: ".$mail->date."\r\n";
		$manuscript .= "Content-Type: text/plain; charset=\"iso-2022-jp\"\r\n";
		$manuscript .= "Content-Transfer-Encoding: 7bit\r\n";
		$manuscript .= "\r\n";
		$manuscript .= "test mail";
		$manuscript .= "\r\n";
		$this->assertEquals($manuscript, $mail->manuscript());
				
		$mail->to("rhlirhli@rhaco.org", "rhlirhli");
		$manuscript  = "MIME-Version: 1.0\r\n";
		$manuscript .= "To: \"rhli\" <rhli@rhaco.org>,\r\n";
		$manuscript .= " \"rhlirhli\" <rhlirhli@rhaco.org>\r\n";
		$manuscript .= "From: \"rhli\" <rhli@rhaco.org>\r\n";
		$manuscript .= "Return-Path: rhli@rhaco.org\r\n";
		$manuscript .= "Subject: test\r\n";
		$manuscript .= "Date: ".$mail->date."\r\n";
		$manuscript .= "Content-Type: text/plain; charset=\"iso-2022-jp\"\r\n";
		$manuscript .= "Content-Transfer-Encoding: 7bit\r\n";
		$manuscript .= "\r\n";
		$manuscript .= "test mail";
		$manuscript .= "\r\n";
		$this->assertEquals($manuscript, $mail->manuscript());
	}
	
	function testHeader(){
		$mail = new Mail("rhli@rhaco.org", "rhli");
		$mail->subject("test");
		$mail->message("test mail");
		$mail->to("rhli@rhaco.org", "rhli");
		$mail->cc("rhli@rhaco.org", "rhli");
		$mail->bcc("rhli@rhaco.org", "rhli");
		$mail->returnpath("rhli@rhaco.org");
		
		$manuscript  = "MIME-Version: 1.0\r\n";
		$manuscript .= "To: \"rhli\" <rhli@rhaco.org>\r\n";
		$manuscript .= "From: \"rhli\" <rhli@rhaco.org>\r\n";
		$manuscript .= "Cc: \"rhli\" <rhli@rhaco.org>\r\n";
		$manuscript .= "Return-Path: rhli@rhaco.org\r\n";
		$manuscript .= "Subject: test\r\n";
		$manuscript .= "Date: ".$mail->date."\r\n";
		$manuscript .= "Content-Type: text/plain; charset=\"iso-2022-jp\"\r\n";
		$manuscript .= "Content-Transfer-Encoding: 7bit\r\n";
		$manuscript .= "\r\n";
		$manuscript .= "test mail";
		$manuscript .= "\r\n";
		$this->assertEquals($manuscript, $mail->manuscript());
	}
	
	function testHtml(){
		$mail = new Mail("rhli@rhaco.org", "rhli",true);
		$mail->boundary["alternative"] = "----=_Part_alternative";//for test
		
		$mail->to("rhli@rhaco.org", "rhli");
		$mail->subject("test");
		$mail->html("<h1>test mail</h1>");
		
		$manuscript  = "MIME-Version: 1.0\r\n";
		$manuscript .= "To: \"rhli\" <rhli@rhaco.org>\r\n";
		$manuscript .= "From: \"rhli\" <rhli@rhaco.org>\r\n";
		$manuscript .= "Return-Path: rhli@rhaco.org\r\n";
		$manuscript .= "Subject: test\r\n";
		$manuscript .= "Date: ".$mail->date."\r\n";
		$manuscript .= "Content-Type: multipart/alternative; boundary=\"----=_Part_alternative\"\r\n";
		$manuscript .= "\r\n";
		$manuscript .= "------=_Part_alternative\r\n";
		$manuscript .= "Content-Type: text/plain; charset=\"iso-2022-jp\"\r\n";
		$manuscript .= "Content-Transfer-Encoding: 7bit\r\n";
		$manuscript .= "\r\n";
		$manuscript .= "test mail";
		$manuscript .= "\r\n";
		$manuscript .= "------=_Part_alternative\r\n";
		$manuscript .= "Content-Type: text/html; charset=\"iso-2022-jp\"\r\n";
		$manuscript .= "Content-Transfer-Encoding: 7bit\r\n";
		$manuscript .= "\r\n";
		$manuscript .= "<h1>test mail</h1>";
		$manuscript .= "\r\n";
		$manuscript .= "------=_Part_alternative--\r\n";
		$this->assertEquals($manuscript, $mail->manuscript());
	}

	function testHtmlImage(){
		$mail = new Mail("rhli@rhaco.org", "rhli",true);
		$mail->boundary["alternative"] = "----=_Part_alternative";//for test
		$mail->boundary["related"] = "----=_Part_related";//for test
		
		$mail->to("rhli@rhaco.org", "rhli");
		$mail->subject("test");
		$html  = "<h1>test mail</h1>\r\n";
		$html .= "<img src=\"rhli.png\">\r\n";
		$html .= "<a href=\"rhli.png\">rhli</a>\r\n";
		$html .= "<div style=\"background-image:url(rhli.png);\"></div>";
		$mail->html($html);
		$mail->image("rhli.png", "png binary...", "image/png");
		
		$manuscript  = <<< __TEXT__
MIME-Version: 1.0
To: "rhli" <rhli@rhaco.org>
From: "rhli" <rhli@rhaco.org>
Return-Path: rhli@rhaco.org
Subject: test
Date: %s
Content-Type: multipart/alternative; boundary="----=_Part_alternative"

------=_Part_alternative
Content-Type: text/plain; charset="iso-2022-jp"
Content-Transfer-Encoding: 7bit

test mail

rhli

------=_Part_alternative
Content-Type: multipart/related; boundary="----=_Part_related"

------=_Part_related
Content-Type: text/html; charset="iso-2022-jp"
Content-Transfer-Encoding: 7bit

<h1>test mail</h1>
<img src="cid:rhli.png">
<a href="cid:rhli.png">rhli</a>
<div style="background-image:url(cid:rhli.png);"></div>
------=_Part_related
Content-Type: image/png; name="rhli.png"
Content-Transfer-Encoding: base64
Content-ID: <rhli.png>

cG5nIGJpbmFyeS4uLg==
------=_Part_related--

------=_Part_alternative--

__TEXT__;
		$manuscript = sprintf($manuscript,$mail->date);
		$this->assertEquals(str_replace("\n","\r\n",$manuscript), $mail->manuscript());
	}
	
	function testAttach(){
		$mail = new Mail("rhli@rhaco.org", "rhli",true);
		$mail->boundary["mixed"] = "----=_Part_mixed";//for test
		
		$mail->to("rhli@rhaco.org", "rhli");
		$mail->subject("test");
		$mail->message("test mail");
		$mail->attach("rhli.txt","rhliはrhacoに付属しています。","text/plain");
		
		$manuscript  = "MIME-Version: 1.0\r\n";
		$manuscript .= "To: \"rhli\" <rhli@rhaco.org>\r\n";
		$manuscript .= "From: \"rhli\" <rhli@rhaco.org>\r\n";
		$manuscript .= "Return-Path: rhli@rhaco.org\r\n";
		$manuscript .= "Subject: test\r\n";
		$manuscript .= "Date: ".DateUtil::formatRfc2822(time())."\r\n";
		$manuscript .= "Content-Type: multipart/mixed; boundary=\"----=_Part_mixed\"\r\n";
		$manuscript .= "\r\n";
		$manuscript .= "------=_Part_mixed\r\n";
		$manuscript .= "Content-Type: text/plain; charset=\"iso-2022-jp\"\r\n";
		$manuscript .= "Content-Transfer-Encoding: 7bit\r\n";
		$manuscript .= "\r\n";
		$manuscript .= "test mail";
		$manuscript .= "\r\n";
		$manuscript .= "------=_Part_mixed\r\n";
		$manuscript .= "Content-Type: text/plain; name=\"rhli.txt\"\r\n";
		$manuscript .= "Content-Transfer-Encoding: base64\r\n";
		$manuscript .= "\r\n";
		$manuscript .= chunk_split(base64_encode("rhliはrhacoに付属しています。"));
		$manuscript .= "------=_Part_mixed--\r\n";		
		$this->assertEquals($manuscript, $mail->manuscript());
	}
	
	function testHtmlImageAndAttach(){
		$mail = new Mail("rhli@rhaco.org", "rhli",true);
		$mail->boundary["mixed"] = "----=_Part_mixed";//for test
		$mail->boundary["alternative"] = "----=_Part_alternative";//for test
		$mail->boundary["related"] = "----=_Part_related";//for test
		
		$mail->to("rhli@rhaco.org", "rhli");
		$mail->subject("test");
		$mail->html("<h1>test mail</h1>\r\n<img src=\"rhli.png\">");
		$mail->image("rhli.png", "png binary...", "image/png");
		$mail->attach("rhli.txt","rhliはrhacoに付属しています。","text/plain");

		
		$manuscript  = <<< __TEXT__
MIME-Version: 1.0
To: "rhli" <rhli@rhaco.org>
From: "rhli" <rhli@rhaco.org>
Return-Path: rhli@rhaco.org
Subject: test
Date: %s
Content-Type: multipart/mixed; boundary="----=_Part_mixed"

------=_Part_mixed
Content-Type: multipart/alternative; boundary="----=_Part_alternative"

------=_Part_alternative
Content-Type: text/plain; charset="iso-2022-jp"
Content-Transfer-Encoding: 7bit

test mail

------=_Part_alternative
Content-Type: multipart/related; boundary="----=_Part_related"

------=_Part_related
Content-Type: text/html; charset="iso-2022-jp"
Content-Transfer-Encoding: 7bit

<h1>test mail</h1>
<img src="cid:rhli.png">
------=_Part_related
Content-Type: image/png; name="rhli.png"
Content-Transfer-Encoding: base64
Content-ID: <rhli.png>

cG5nIGJpbmFyeS4uLg==
------=_Part_related--

------=_Part_alternative--
------=_Part_mixed
Content-Type: text/plain; name="rhli.txt"
Content-Transfer-Encoding: base64

cmhsaeOBr3JoYWNv44Gr5LuY5bGe44GX44Gm44GE44G+44GZ44CC
------=_Part_mixed--

__TEXT__;
		$manuscript = sprintf($manuscript,$mail->date);
		$this->assertEquals(str_replace("\n","\r\n",$manuscript), $mail->manuscript());
	}
}
?>