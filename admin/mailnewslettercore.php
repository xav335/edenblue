<?php include_once 'inc-auth-granted.php';?>
<?php include_once 'classes/utils.php';?>
<?php 
require 'classes/Newsletter.php';
require 'classes/Contact.php';

$newsletter = new Newsletter();

if (!empty($_GET)){ //Modif 
	
	$result = $newsletter->newsletterAllGet($_GET['id']);
	//print_r($result);
	if (empty($result)) {
		$message = 'Aucun enregistrements';
	} else {
		$id= 			$_GET['id'];
		$titre=  		$result[0]['titre'];
		$date= 			traitement_datetime_affiche($result[0]['date']);
		$bas_page= 		nl2br($result[0]['bas_page']);
		$detail=		$result[0]['newsletter_detail'];
	}
} else { 
	echo 'Erreur contactez votre administrateur <br> :\n';
	exit();
}
?>
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

?>
<?php
$urlSite = $_SERVER['HTTP_HOST'];



$corps = <<<EOD

<html>
<head>
<meta charset="utf-8" />
<title>Newsletter ICONEO</title>
</head>
<body style="background: #fff;font-family:'Open Sans',sans-serif;">
	<table width="640" border="0"  cellpadding="0" cellspacing="0" >
	<tr>
	    <td align="center">
			<div style="text-align:center;  margin-left:auto;margin-right:auto; width: 554px; border: 4px ridge white; padding:20px 20px 20px 20px; ">
				
				<a href="http://$urlSite"><img  src="http://$urlSite/newsletter/logo.png" alt=""></a>
			
				<h1 style="font-family:'Open Sans',sans-serif;color:#515050;font-size:25px;text-transform: uppercase;font-style: italic;">$titre</h1>
				<hr>
EOD;
if(isset($detail)) {
	$i=0;
	foreach ($detail as $value) {
		$i++;
		$couleur = $value['online'];
		switch ($couleur) {
			case 'bleu':
				$color = '39bcd6';
				$rubrique = 'OUTILS WEB - ECOMMERCE - PLV';
			break;
			case 'vert':
				$color = '7aac11';
				$rubrique = 'CRM - SERVICES INFORMATIQUES';
			break;
			case 'jaune':
				$color = 'e3a91f';
				$rubrique = 'CONSEIL';
			break;
			case 'fuschia':
				$color = 'ed4a8c';
				$rubrique = 'FORMATION';
			break;
			default:
				$color = '39bcd6';
			break;
		}
		$titre = $value['titre'];
		$link = $value['link'];
		$url = $value['url'];
		if ($url!='' & $url != '/img/ajoutImage.jpg') {
			$url = "<a href=\"". $link ."\"><img width=\"254\" src=\"http://$urlSite". $url ."\" alt=\"\"></a><br>";
		} else {
			$url= '';
		}
		$texte = nl2br($value['texte']);
		if ($titre != '' || $url != '' || $texte != '') {
			if($i%2==0){
				$corps .= <<<EOD
						<table width="554" border="0"  cellpadding="0" cellspacing="0" >
						<tr>
			    			<td valign="top" width="300">
			    				<h2 style="color:#$color;font-family:'Open Sans',sans-serif;font-size:18px;font-weight: bold;text-transform: uppercase;padding-left: 10px;padding-right: 10px;text-align: left;">
			    					$titre
			    				</h2>
			    				<p style="font-family:'Open Sans',sans-serif;margin-bottom:10px;font-size:13px; padding: 10px 10px 10px 10px;text-align: justify;">
			    					$texte
			    				</p>
			    			</td>
			    			<td valign="top">
			    				$url
			    			</td>
			    		</tr>
			    		<tr>
			    			<td align="left">
			    				<h3 style="color:#$color;font-family:'Open Sans',sans-serif;font-size:14px;text-transform: uppercase;padding-left: 10px;padding-right: 10px;text-align: left;">$rubrique</h3>
			    			</td>
			    			<td align="right">
			    				<a href="$link"><img width="80" src="http://$urlSite/newsletter/go$couleur.png" alt=""></a>
			    			</td>
			    		</tr>
			    		</table>	
						<hr style="height:2px;color:#969696;background-color:#969696;clear:both;">
						<br>
EOD;
			} else {
				$corps .= <<<EOD
						<table width="554" border="0"  cellpadding="0" cellspacing="0" >
						<tr>
			    			<td valign="top">
			    				$url
			    			</td>
			    			<td valign="top"  width="300">
			    				<h2 style="color:#$color;font-family:'Open Sans',sans-serif;font-size:18px;font-weight: bold;text-transform: uppercase;padding-left: 10px;padding-right: 10px;text-align: left;">
			    					$titre
			    				</h2>
			    				<p style="font-family:'Open Sans',sans-serif;margin-bottom:10px;font-size:13px; padding: 10px 10px 10px 10px;text-align: justify;">
			    					$texte
			    				</p>
			    			</td>
			    		</tr>
			    		<tr>
			    			<td align="left">
			    				<h3 style="color:#$color;font-family:'Open Sans',sans-serif;font-size:14px;text-transform: uppercase;padding-left: 10px;padding-right: 10px;text-align: left;">$rubrique</h3>
			    			</td>
			    			<td align="right">
			    				<a href="$link"><img width="80" src="http://$urlSite/newsletter/go$couleur.png" alt=""></a>
			    			</td>
			    		</tr>
			    		</table>
						<hr style="height:2px;color:#969696;background-color:#969696;clear:both;">
						<br>
EOD;
			}	
		}
	}
}
$corps .= <<<EOD
				
				<img width="554" src="http://$urlSite/newsletter/pano.png" alt=""><br>
				<div >
			      	<a href="https://www.facebook.com/pages/iconeofr/1497532703849844"><img width="30" src="http://$urlSite/newsletter/facebook.png" alt="" ></a>
			      	<a href="https://plus.google.com/+IconeoFr/about"><img width="30" src="http://$urlSite/newsletter/google.png" alt=""></a>
			      	<a href="https://twitter.com/iconeo33"><img width="30" src="http://$urlSite/newsletter/twiter.png" alt=""></a>
			      	<a href="http://fr.viadeo.com/fr/company/iconeo"><img width="30" src="http://$urlSite/newsletter/viadeo.png" alt=""></a>
			      	<a href="https://www.linkedin.com/pub/contact-iconeo/a8/66a/883"><img width="30" src="http://$urlSite/newsletter/linkedin.png" alt=""></a>
			  	</div>
				<br>
				<p style="font-size:14px;font-family:'Open Sans',sans-serif;">
					$bas_page
				</p>
				<br>
				<p style="font-size:9px;font-family:'Open Sans',sans-serif;">
					Si vous souhaitez vous désinscrire de cette newsletter suivez le lien suivant : <a href="http://$urlSite/newsletter/desinscription.php?id=" >désinscription</a>
				</p>
				<img src="http://$urlSite/newsletter/track.php?id=XwXwXwXw" alt="">
			</div>
		</td>
	</tr>	
	</table>	
</body>
</html>

EOD;

if (empty($_GET['action']) && empty($_GET['postaction']) ) {
	echo $corps;
}	

//$corps = utf8_decode( $corps );

$sujet = "Iconeo - Newsletter ";
$entete = "From:Iconeo <contact@iconeo.fr>\n";
$entete .= "MIME-version: 1.0\n";
$entete .= "Content-type: text/html; charset= iso-8859-1\n";

// TEST TEST TEST TEST TEST TEST TEST TEST TEST TEST TEST TEST TEST TEST TEST 
if (!empty($_GET['postaction']) && $_GET['postaction']=='preview') {
	echo "<br><br><h3>Newsletter de Test envoyee a contact@iconeo.fr !!!! </h3><br><br>
		<a href='javascript:history.back()'>retour</a>";
	
	//$_to = "fredericlesca@iconeo.fr";
	$_to = "fjavi.gonzalez@gmail.com";
	$entete .= "Bcc: fjavi.gonzalez@gmail.com, xav335@hotmail.com,xavier.gonzalez@laposte.net,jav_gonz@yahoo.com\n";
	
	//echo "Envoi du message à " . $_to . "<br>";
	$corpsCode = str_replace('XwXwXwXw', randomChar(), $corps);
	//echo $corpsCode;
	////////////////!!!!!!!!!!!!!!!!!!!!!!!!!!!!////////////
	//mail($_to, $sujet, stripslashes($corps), $entete);
	///////////////////////////////////////////////////////////
	////////////////ELASTIC MAIL ICONEO!!!!!!!!!!////////////
	sendElasticEmail($_to, $sujet, "", stripslashes($corpsCode), "contact@iconeo.fr", "iconeo");
	sendElasticEmail("fjavi.gonzalez@gmail.com", $sujet, "", stripslashes($corpsCode), "contact@iconeo.fr", "iconeo");
	sendElasticEmail("xav335@hotmail.com", $sujet, "", stripslashes($corpsCode), "contact@iconeo.fr", "iconeo");
	sendElasticEmail("jav_gonz@yahoo.com", $sujet, "", stripslashes($corpsCode), "contact@iconeo.fr", "iconeo");
	///////////////////////////////////////////////////////////
	error_log(date("Y-m-d H:i:s") ." envoi : OK : fjavi.gonzalez@gmail.com \n", 3, "spy.log");
	error_log(date("Y-m-d H:i:s") ." envoi : OK : xav335@hotmail.com \n", 3, "spy.log");
	error_log(date("Y-m-d H:i:s") ." envoi : OK : jav_gonz@yahoo.com \n", 3, "spy.log");
	
} elseif (!empty($_GET['postaction']) && $_GET['postaction']=='envoi') { 
// ENVOI EN MASSE ENVOI EN MASSEENVOI EN MASSEENVOI EN MASSEENVOI EN MASSEENVOI EN MASSE
	$id_journal = $newsletter->journalNewsletterAdd($_GET['id']);
	
	$contact = new Contact();
	$result = $contact->contactGetForNewsletter();
	//print_r($result);
	if (!empty($result)) {
		foreach ($result as $value) {
			$_to = $value['email'];
			$regex = '#^[\w.-]+@[\w.-]+\.[a-zA-Z]{2,6}$#';
			if (preg_match( $regex, $_to)) {
				$codeRandom =randomChar();
				$corpsCode = str_replace('XwXwXwXw', $codeRandom, $corps);
				$newsletter->journalNewsletterDetailAdd($id_journal,$_to,$codeRandom,null);
				////////////////!!!!!!!!!!!!!!!!!!!!!!!!!!!!////////////
				//mail($_to, $sujet, stripslashes($corpsCode), $entete);
				///////////////////////////////////////////////////////////
				////////////////ELASTIC MAIL ICONEO!!!!!!!!!!////////////
        		//sendElasticEmail($_to, $sujet, "", stripslashes($corpsCode), "contact@iconeo.fr", "iconeo");
        		///////////////////////////////////////////////////////////
				//echo "envoi OK : ". $value['email'] ."<br>";
				error_log(date("Y-m-d H:i:s") ." envoi : OK : ". $value['email'] ."\n", 3, "spy.log");
			} else {
				$newsletter->journalNewsletterDetailAdd($id_journal,$_to,null,'bad email');
				//echo "XXXX envoi KO : ". $value['email'] ."<br>";
				error_log(date("Y-m-d H:i:s") ." envoi : KO : ". $value['email'] ."\n", 3, "spy.log");
			}	
			
			
		}
		echo "<br><br><h3>Newsletter REELLE envoyee à tous les adhérents !!!! </h3><br><br>
		<a href='javascript:history.back()'>retour</a>";
	}	
}	
