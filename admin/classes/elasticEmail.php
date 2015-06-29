<?php 

	function sendElasticEmail($to, $subject, $body_text, $body_html, $from, $fromName)
	{
		$res = "";
	
		$data = "username=".urlencode("691f06a3-f0d1-41b6-87c1-b8a769c5c1f1");
		$data .= "&api_key=".urlencode("691f06a3-f0d1-41b6-87c1-b8a769c5c1f1");
		$data .= "&from=".urlencode($from);
		$data .= "&from_name=".urlencode($fromName);
		$data .= "&to=".urlencode($to);
		$data .= "&subject=".urlencode($subject);
		if($body_html)
			$data .= "&body_html=".urlencode($body_html);
		if($body_text)
			$data .= "&body_text=".urlencode($body_text);
	
		$header = "POST /mailer/send HTTP/1.0\r\n";
		$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
		$header .= "Content-Length: " . strlen($data) . "\r\n\r\n";
		$fp = fsockopen('ssl://api.elasticemail.com', 443, $errno, $errstr, 30);
	
		if(!$fp)
			return "ERROR. Could not open connection";
		else {
			fputs ($fp, $header.$data);
			while (!feof($fp)) {
				$res .= fread ($fp, 1024);
			}
			fclose($fp);
		}
		return $res;
	}
//echo sendElasticEmail("test@test.com", "My Subject", "My Text", "My HTML", "youremail@yourdomain.com", "Your Name");
