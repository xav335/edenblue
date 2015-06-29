<?php include_once '../inc/inc.config.php'; ?>
<?php
require 'classes/News.php';
require 'classes/ImageManager.php';
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
				$imageManager->imageResize($source, $destination, null, 650, false);
				//Vignette
				$destination=$_SERVER['DOCUMENT_ROOT'].'/photos/news/thumbs'.$filenameDest;
				$imageManager->imageResize($source, $destination, null, 250, false);
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
				header('Location: /admin/news-edit.php?id='.$result);
			} catch (Exception $e) {
				echo 'Erreur contactez votre administrateur <br> :',  $e->getMessage(), "\n";
				exit();
			}
		}
		
	}
	
	
} elseif (!empty($_GET)) { // GET GET GET
	
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
	
} else {
	header('Location: /admin/');
}

?>