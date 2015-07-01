<?php
error_log ( date ( "Y-m-d H:i:s" ) . " : " . $_POST ['action'] . "\n", 3, "../log/spy.log" );
error_log ( date ( "Y-m-d H:i:s" ) . " : " . $_POST ['name'] . "\n", 3, "../log/spy.log" );
error_log ( date ( "Y-m-d H:i:s" ) . " : " . $_POST ['horaire'] . "\n", 3, "../log/spy.log" );
error_log ( date ( "Y-m-d H:i:s" ) . " : " . $_POST ['email'] . "\n", 3, "../log/spy.log" );
error_log ( date ( "Y-m-d H:i:s" ) . " : " . $_POST ['tel'] . "\n", 3, "../log/spy.log" );
error_log ( date ( "Y-m-d H:i:s" ) . " : " . $_POST ['message'] . "\n", 3, "../log/spy.log" );
error_log ( date ( "Y-m-d H:i:s" ) . " : " . $_POST ['cp'] . "\n", 3, "../log/spy.log" );

if ($_POST ["action"] == "sendMail") {
	
	//$_to = "edenblue33@gmail.com";
	//$_to = "fjavi.gonzalez@gmail.com";
	$_to = "info@edenblue.fr";
	//$_to = "web-IYAFtN@mail-tester.com";
	$sujet = "Eden Blue - Contact Site";
	
	$entete = "From:EdenBlue <contact@edenblue.fr>\n";
	$entete .= "MIME-version: 1.0\n";
	$entete .= "Content-type: text/plain; charset= iso-8859-1\n";
	$entete .= "Bcc: edenblue33@gmail.com,xav335@hotmail.com,fjavi.gonzalez@gmail.com\n";
	
	$corps = "";
	$corps .= "Bonjour,\n";
	$corps .= "Nv message de :\n" . $_POST ["name"] . " (" . $_POST ["email"] . ")\n";
	$corps .= "Tel : " . $_POST ["tel"] . "\n";
	$corps .= "Code postal : " . $_POST ["cp"] . "\n";
	$corps .= "Horaire souhaité de rappel: " . $_POST ["horaire"] . "\n";
	$corps .= "Message :\n";
	$corps .= $_POST ["message"] . "\n";
	$corps = utf8_decode ( $corps );
	
	// Envoi des identifiants par mail
	mail ( $_to, $sujet, stripslashes ( $corps ), $entete );
	
	error_log ( date ( "Y-m-d H:i:s" ) . " : Message envoyé ! \n", 3, "../log/spy.log" );
} else {
	error_log ( date ( "Y-m-d H:i:s" ) . " : Message NON envoyé ! \n", 3, "../log/spy.log" );
}
error_log ( date ( "Y-m-d H:i:s" ) . " xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx  \n", 3, "../log/spy.log" );

?>