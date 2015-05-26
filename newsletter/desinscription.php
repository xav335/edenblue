<?php
require '../admin/classes/Contact.php';

if (!empty($_POST)){
	$contact = new Contact();
	$contact->contactUnsubscribeNewsletter($_POST['email'], $_POST['message']);
	$contact = null;
	
	$_to = "admin@iconeo.fr";
	$sujet = "EdenBlue - Desinscription Newsletter";
	
	$entete = "From:Iconeo <contact@edenblue.fr>\n";
	$entete .= "MIME-version: 1.0\n";
	$entete .= "Content-type: text/html; charset= iso-8859-1\n";
	$entete .= "Bcc: fjavi.gonzalez@gmail.com,xav335@hotmail.com\n";
	
	$corps = "";
	$corps .= "Email à désinscrire :" . $_POST['email']  ."<br>";
	$corps .= "Message : ". $_POST["message"] ."<br>";
	$corps = utf8_decode( $corps );
	//echo $corps . "<br>";
	
	// Envoi des identifiants par mail
	mail($_to, $sujet, stripslashes($corps), $entete);
}
?>
<!doctype html>
<html class="no-js" lang="fr">
<head>
<meta charset="utf-8">
<title>Bsport newsletter désinscription</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
</head>
<body>	

	<img src="../admin/img/logo.png" width="200">
<?php if (!empty($_POST)){ ?>
	<br><br>Votre désincription a été prise en compte ! <br><br>
	
	<a href="http://iconeo.fr/" >Allez sur le site </a>
<?php } else { ?>
	<form name="formulaire" class="form-horizontal" method="POST"  action="desinscription.php">
		Indiquez votre e-mail<br>
		<input name="email" id="email" type="email" placeholder="e-mail" required /><br><br>
		
		Message (éventuel) <br>
		<textarea name="message" id="message" placeholder="Votre message" ></textarea><br><br>

		<input class="suite" id="bouton" type="submit" value="Validez la désincription à notre newsletter"/>
	</form>
<?php }  ?>
</body>
</html>



