<?php

error_log(date("Y-m-d H:i:s") ." : ". $_POST['action'] ."\n", 3, "../log/spy.log");
error_log(date("Y-m-d H:i:s") ." : ". $_POST['name'] ."\n", 3, "../log/spy.log");
error_log(date("Y-m-d H:i:s") ." : ". $_POST['horaire'] ."\n", 3, "../log/spy.log");
error_log(date("Y-m-d H:i:s") ." : ". $_POST['email'] ."\n", 3, "../log/spy.log");
error_log(date("Y-m-d H:i:s") ." : ". $_POST['tel'] ."\n", 3, "../log/spy.log");
error_log(date("Y-m-d H:i:s") ." : ". $_POST['message'] ."\n", 3, "../log/spy.log");
error_log(date("Y-m-d H:i:s") ." : ". $_POST['cp'] ."\n", 3, "../log/spy.log");


if ($_POST["action"] == "sendMail") {

	//$_to = "edenblue33@gmail.com";
	$_to = "fjavi.gonzalez@gmail.com";
	$sujet = "Eden Blue - Contact Site";
	//echo "Envoi du message à " . $_to . "<br>";

	$entete = "From:EdenBlue <edenblue33@gmail.com.fr>\n";
	$entete .= "MIME-version: 1.0\n";
	$entete .= "Content-type: text/html; charset= iso-8859-1\n";
	$entete .= "Bcc: xav335@hotmail.com\n";

	$corps = "";
	$corps .= "Bonjour,<br>";
	$corps .= "Nv message de :<br>" . $_POST["name"] . " (" . $_POST["email"] . ")<br>";
	$corps .= "Tel : ". $_POST["tel"] ."<br>";
	$corps .= "Code postal : ". $_POST["cp"] ."<br>";
	$corps .= "Horaire souhaité de rappel: " . $_POST["horaire"] ."<br>";
	$corps .= "<b>Message :</b><br>";
	$corps .= $_POST["message"] . "<br><br>";
	$corps = utf8_decode( $corps );
	//echo $corps . "<br>";

	// Envoi des identifiants par mail
	mail($_to, $sujet, stripslashes($corps), $entete);

	error_log(date("Y-m-d H:i:s") ." : Message envoyé ! \n", 3, "../log/spy.log");
} else {
	error_log(date("Y-m-d H:i:s") ." : Message NON envoyé ! \n", 3, "../log/spy.log");
}
error_log(date("Y-m-d H:i:s") ." xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx  \n", 3, "../log/spy.log");

?>