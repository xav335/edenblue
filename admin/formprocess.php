<?php
require 'classes/Authentication.php';
require 'classes/News.php';
require 'classes/Contact.php';
require 'classes/Newsletter.php';
require 'classes/Goldbook.php';
require 'classes/Catproduct.php';
require 'classes/Planning.php';
require 'classes/ImageManager.php';
session_start();

$authentication = new Authentication();
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
	
	// traitement du Bon de commande /planning
	$planning = new Planning();
	if ($_POST['reference'] == 'planning'){
		$imageManager = New ImageManager();
			$source = $_SERVER['DOCUMENT_ROOT'].$_POST['pdf'];
			if(strstr($source,'uploads')){
				$source = $_SERVER['DOCUMENT_ROOT'].$_POST['pdf'];
				$filenameDest = $imageManager->fileDestManagement($source,date('Ymd'));
				//Image
				$destination=$_SERVER['DOCUMENT_ROOT'].'/photos/bdc'.$filenameDest;
				//print_r($source);exit();
				if (!copy($source, $destination)) {
					throw new Exception('Erreur contactez votre administrateur <br> Le PDF error:',  $e->getMessage(), "\n");
					exit;
				}
				 
				//Vignette
				$_POST['pdf']=$filenameDest;
			}
		$imageManager =null;
		if ($_POST['action'] == 'modif') { //Modifier la news
			try {
				$result = $planning->planningModify($_POST);
				header('Location: /admin/planning.php');
			} catch (Exception $e) {
				echo 'Erreur contactez votre administrateur <br> :',  $e->getMessage(), "\n";
				exit();
			}
		} 
	}
	
	// traitement des newsletters
	if ($_POST['reference'] == 'newsletter'){
		//print_r($_POST); exit();
		$newsletter = new Newsletter();
		if ($_POST['action'] == 'modif' ) { //Modifier
			try {
				if ($_POST['postaction'] !='delBloc') {
					$result = $newsletter->newsletterModify($_POST);
				}
				if ($_POST['postaction'] == 'preview' ) {
					$newsletter = null;
					header('Location: /admin/mailnewslettercore.php?postaction=preview&id='. $_POST['id']);
				} elseif ($_POST['postaction'] == 'preview' ) {
					$newsletter = null;
					header('Location: /admin/mailnewslettercore.php?postaction=envoi&id='. $_POST['id']);
				} elseif ($_POST['postaction']=='addBloc') {
					$newsletter = null;
					header('Location: /admin/newsletter-edit.php?addBloc=1&id='. $_POST['id']);
				} elseif ($_POST['postaction']=='delBloc') {
					$newsletter->newsletterDetailUniqueDelete($_POST['idbloc']);
					$newsletter = null;
					header('Location: /admin/newsletter-edit.php?id='. $_POST['id']);
				} else {
					header('Location: /admin/newsletter-list.php');
				}
	
			} catch (Exception $e) {
				echo 'Erreur contactez votre administrateur <br> :',  $e->getMessage(), "\n";
				$newsletter = null;
				exit();
			}
		} elseif ($_POST['action'] == 'envoi' ) {  //ajouter
			if ($_POST['postaction'] == 'envoi' ) {
				header('Location: /admin/mailnewslettercore.php?action=envoi&postaction=envoi&id='. $_POST['id']);
			} else {
				header('Location: /admin/mailnewslettercore.php?action=envoi&postaction=preview&id='. $_POST['id']);
			}
				
		} else {  //ajouter
			try {
				$result = $newsletter->newsletterAdd($_POST);
				$newsletter = null;
				header('Location: /admin/newsletter-edit.php?id='.$result);
			} catch (Exception $e) {
				echo 'Erreur contactez votre administrateur <br> :',  $e->getMessage(), "\n";
				$newsletter = null;
				exit();
			}
		}
	}
	
	
	// traitement des news
	$news = new News();
	if ($_POST['reference'] == 'news'){
		//print_r($_POST);exit();
		$imageManager = New ImageManager();
		for ($i=1;$i<2;$i++){
			$source = $_SERVER['DOCUMENT_ROOT'].$_POST['url'.$i];
			if(strstr($source,'uploads')){
				$source = $_SERVER['DOCUMENT_ROOT'].$_POST['url'.$i];
				$filenameDest = $imageManager->fileDestManagement($source,$_POST['id']);
				//Image
				$destination=$_SERVER['DOCUMENT_ROOT'].'/photos/news'.$filenameDest;
				//print_r($destination);exit();
				$imageManager->imageResize($source, $destination, null, 650);
				//Vignette
				$destination=$_SERVER['DOCUMENT_ROOT'].'/photos/news/thumbs'.$filenameDest;
				$imageManager->imageResize($source, $destination, null, 250);
				$_POST['url'.$i]=$filenameDest;
			}
		}
		$imageManager =null;
		
		if ($_POST['action'] == 'modif') { //Modifier la news
			try {
				$result = $news->newsModify($_POST);
				header('Location: /admin/news-list.php');
			} catch (Exception $e) {
				echo 'Erreur contactez votre administrateur <br> :',  $e->getMessage(), "\n";
				exit();
			}
			 
		} else {  //ajouter une news
			try {
				$result = $news->newsAdd($_POST);
				header('Location: /admin/news-list.php?id='.$result);
			} catch (Exception $e) {
				echo 'Erreur contactez votre administrateur <br> :',  $e->getMessage(), "\n";
				exit();
			}
		}
		
	}
	
	// traitement des livre d'or
	if ($_POST['reference'] == 'goldbook'){
		$goldbook = new Goldbook();
		if ($_POST['action'] == 'modif') { //Modifier 
			try {
				$result = $goldbook->goldbookModify($_POST);
				$goldbook = null;
				header('Location: /admin/goldbook-list.php');
			} catch (Exception $e) {
				echo 'Erreur contactez votre administrateur <br> :',  $e->getMessage(), "\n";
				$goldbook = null;
				exit();
			}
				
		} else {  //ajouter 
			try {
				$result = $goldbook->goldbookAdd($_POST);
				$goldbook = null;
				header('Location: /admin/goldbook-edit.php?id='.$result);
			} catch (Exception $e) {
				echo 'Erreur contactez votre administrateur <br> :',  $e->getMessage(), "\n";
				$goldbook = null;
				exit();
			}
		}
	}
	
	// traitement des Categorie
	if ($_POST['reference'] == 'categorie'){
		//print_r($_POST);exit();
		$imageManager = New ImageManager();
		$catproduct = new Catproduct();
		if ($_POST['action'] == 'modif') { //Modifier
			try {
				//Gestion des images
				$source = $_SERVER['DOCUMENT_ROOT'].$_POST['url1'];

				if (strstr($source,'uploads')){
					$source = $_SERVER['DOCUMENT_ROOT'].$_POST['url1'];
					$filenameDest = $imageManager->fileDestManagement($source,$_POST['id']);
					//Image
					$destination=$_SERVER['DOCUMENT_ROOT'].'/photos/categories'.$filenameDest;
					$imageManager->imageResize($source, $destination, null, 650);
					//Vignette
					$destination=$_SERVER['DOCUMENT_ROOT'].'/photos/categories/thumbs'.$filenameDest;
					$imageManager->imageResize($source, $destination, null, 350);
					$_POST['url1']=$filenameDest;
				}
				$imageManager =null;
				
				$result = $catproduct->catproductModify($_POST);
				$catproduct = null;
				header('Location: /admin/catproduct-list.php');
			} catch (Exception $e) {
				echo 'Erreur contactez votre administrateur <br> :',  $e->getMessage(), "\n";
				$catproduct = null;
				exit();
			}
	
		} else {  //ajouter
			try {
				//print_r($_POST);exit();
				$result = $catproduct->catproductAdd($_POST);
				$catproduct = null;
				header('Location: /admin/catproduct-list.php');
			} catch (Exception $e) {
				echo 'Erreur contactez votre administrateur <br> :',  $e->getMessage(), "\n";
				$catproduct = null;
				exit();
			}
		}
	}
	
	// traitement des Produit
	if ($_POST['reference'] == 'product'){
		//print_r($_POST);exit();
		$catproduct = new Catproduct();
		$imageManager = New ImageManager();
		for ($i=1;$i<4;$i++){
			$source = $_SERVER['DOCUMENT_ROOT'].$_POST['url'.$i];
			if(strstr($source,'uploads')){
				$source = $_SERVER['DOCUMENT_ROOT'].$_POST['url'.$i];
				$filenameDest = $imageManager->fileDestManagement($source,$_POST['id']);
				//Image
				$destination=$_SERVER['DOCUMENT_ROOT'].'/photos/products'.$filenameDest;
				$imageManager->imageResize($source, $destination, null, 650);
				//Vignette
				$destination=$_SERVER['DOCUMENT_ROOT'].'/photos/products/thumbs'.$filenameDest;
				$imageManager->imageResize($source, $destination, null, 250);
				$_POST['url'.$i]=$filenameDest;
			}
		}

		if ($_POST['action'] == 'modif') { //Modifier
			try {
				$result = $catproduct->productModify($_POST);
				$catproduct = null;
				header('Location: /admin/product-list.php');
			} catch (Exception $e) {
				echo 'Erreur contactez votre administrateur <br> :',  $e->getMessage(), "\n";
				$catproduct = null;
				exit();
			}
	
		} else {  //ajouter
			try {
				//print_r($_POST);exit();
				$result = $catproduct->productAdd($_POST);
				$catproduct = null;
				header('Location: /admin/product-list.php');
			} catch (Exception $e) {
				echo 'Erreur contactez votre administrateur <br> :',  $e->getMessage(), "\n";
				$catproduct = null;
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
	if ($_GET['reference'] == 'news'){ //supprimer
		$news = new News();
		if ($_GET['action'] == 'delete'){
			try {
				$result = $news->newsDelete($_GET['id']);
				$news = null;
				header('Location: /admin/news-list.php');
			} catch (Exception $e) {
				echo 'Erreur contactez votre administrateur <br> :',  $e->getMessage(), "\n";
				$news = null;
				exit();
			}
		}	
	}
	if ($_GET['reference'] == 'goldbook'){ //supprimer
		$goldbook = new Goldbook();
		if ($_GET['action'] == 'delete'){
			try {
				$result = $goldbook->goldbookDelete($_GET['id']);
				$goldbook = null;
				header('Location: /admin/goldbook-list.php');
			} catch (Exception $e) {
				echo 'Erreur contactez votre administrateur <br> :',  $e->getMessage(), "\n";
				$goldbook = null;
				exit();
			}
		}
	}
	if ($_GET['reference'] == 'categorie'){ //supprimer
		$catproduct = new Catproduct();
		if ($_GET['action'] == 'delete'){
			try {
				$result = $catproduct->catproductDelete($_GET['id']);
				$catproduct = null;
				header('Location: /admin/catproduct-list.php');
			} catch (Exception $e) {
					echo 'Erreur contactez votre administrateur <br> :',  $e->getMessage() , '\n';
					$catproduct = null;
					if($e->getCode() == 1234){
						header('Location: /admin/catproduct-list.php?message='.$e->getCode());
					}
					exit();
			}
		}
	}
	if ($_GET['reference'] == 'product'){ //supprimer
		$catproduct = new Catproduct();
		if ($_GET['action'] == 'delete'){
			try {
				$result = $catproduct->productDelete($_GET['id']);
				$catproduct = null;
				header('Location: /admin/product-list.php');
			} catch (Exception $e) {
				echo 'Erreur contactez votre administrateur <br> :',  $e->getMessage() , '\n';
				$catproduct = null;
				if($e->getCode() == 1234){
					header('Location: /admin/catproduct-list.php?message='.$e->getCode());
				}
				exit();
			}
		}
	}
	if ($_GET['reference'] == 'newsletter'){ //supprimer
		$newsletter = new Newsletter();
		if ($_GET['action'] == 'delete'){
			try {
				$result = $newsletter->newsletterDelete($_GET['id']);
				$newsletter = null;
				header('Location: /admin/newsletter-list.php');
			} catch (Exception $e) {
				echo 'Erreur contactez votre administrateur <br> :',  $e->getMessage(), "\n";
				exit();
			}
		}
	}
} else {
	header('Location: /admin/');
}

?>