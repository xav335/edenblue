<?php include_once 'inc-auth-granted.php';?>
<?php include_once 'classes/utils.php';?>
<?php 
require 'classes/Contact.php';

$contact = new Contact();

if (!empty($_GET)) { // GET GET GET
	//print_r($_SERVER);
	if ($_GET['action'] == 'IMPORT'){
		//recup du nom du fichier
		$url = $_GET['url'];
		$url = substr(stristr($url, '='), 1);
		$url = $_SERVER["DOCUMENT_ROOT"] ."/admin/FileUpload/server/php/files/".$url;
		//echo $url;
		try {
			$result = $contact->contactImportCSV($url);
			$contact = null;
			header('Location: /admin/contact-list.php');
		} catch (Exception $e) {
			echo 'Erreur contactez votre administrateur <br> :',  $e->getMessage(), "\n";
			$contact = null;
			exit();
		}
	}
}

?>