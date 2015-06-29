<?php include_once '../inc/inc.config.php'; ?>
<?php
require 'classes/Contact.php';
session_start();

//Security
if (!isset($_SESSION['accessGranted']) || !$_SESSION['accessGranted']) {
	$result = $storageManager->grantAccess($_POST['login'], $_POST['mdp']);
	if (!$result){
		header('Location: /admin/?action=error');
	} else {
		$_SESSION['accessGranted'] = true;
	}
}

//print_r($_POST);exit();
//Forms processing
if (!empty($_POST)){
	
	// traitement des Contact
	if ($_POST['reference'] == 'contact'){
		$contact = new Contact();
		if ($_POST['action'] == 'modif') { //Modifier
			try {
				$result = $contact->contactModify($_POST);
				$contact = null;
				header('Location: /admin/contact-list.php');
			} catch (Exception $e) {
				echo 'Erreur contactez votre administrateur <br> :',  $e->getMessage(), "\n";
				$contact = null;
				exit();
			}
	
		} else {  //ajouter
			try {
				$result = $contact->contactAdd($_POST);
				$contact = null;
				header('Location: /admin/contact-edit.php?id='.$result);
			} catch (Exception $e) {
				echo 'Erreur contactez votre administrateur <br> :',  $e->getMessage(), "\n";
				$contact = null;
				exit();
			}
		}
	}
	
	
	
} elseif (!empty($_GET)) { // GET GET GET
	if ($_GET['reference'] == 'contact'){ //supprimer
		$contact = new Contact();
		if ($_GET['action'] == 'delete'){
			try {
				$result = $contact->contactDelete($_GET['id']);
				$contact = null;
				header('Location: /admin/contact-list.php');
			} catch (Exception $e) {
				echo 'Erreur contactez votre administrateur <br> :',  $e->getMessage(), "\n";
				$contact = null;
				exit();
			}
		}
	}
	
} else {
	header('Location: /admin/');
}

?>