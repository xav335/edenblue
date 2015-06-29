<?php include_once '../inc/inc.config.php'; ?>
<?php include_once 'inc-auth-granted.php';?>
<?php include_once 'classes/utils.php';?>
<?php 
require 'classes/Newsletter.php';

if (!empty($_GET)){ //Modif 
	$action = 'modif';
	$newsletter = new Newsletter();
	$result = $newsletter->newsletterAllGet($_GET['id']);
	//print_r($result);
	//print_r($result[0]['newsletter_detail']);
	//exit();
	if (empty($result)) {
		$message = 'Aucun enregistrements';
	} else {
		$labelTitle = 'Newsletter N°: '. $_GET['id'];
		$id= 			$_GET['id'];
		$titre=  		$result[0]['titre'];
		$date= 			traitement_datetime_affiche($result[0]['date']);
		$bas_page= 		$result[0]['bas_page'];
		$ndencards=  	sizeof($result[0]['newsletter_detail']);
	}
} else { //ajout News
	$action = 'add';
	$labelTitle = 'Edition Nouvelle Newsletter ';
	$id= 			null;
	$titre=  		null;
	$date= 	null;
	$bas_page= 		null;
}
?>
<!doctype html>
<html class="no-js" lang="fr">
<head>
	<?php include_once 'inc-meta.php';?>
			
</head>
<body>	
	<?php require_once 'inc-menu.php';?>

	<div class="container">

		<div class="row">
			<h3><?php echo $labelTitle ?></h3>
			<div class="col-xs-12 col-sm-12 col-md-12">
				<form name="formulaire" class="form-horizontal" method="POST"  action="newsletter-fp.php" >
					<input type="hidden" name="reference" value="newsletter">
					<input type="hidden" name="action" id="action" value="<?php echo $action ?>">
					<input type="hidden" name="postaction" id="postaction" value="">
					<input type="hidden" name="idbloc" id="idbloc" value="">
					<input type="hidden" name="id" id="id" value="<?php echo $id ?>">
					
					<input type="hidden"  name="idImage"  id="idImage" value=""><br>
					<div class="form-group" >
						<label class="col-sm-1">Date :</label>
					    <input class="col-sm-2" type="text" name="datepicker" required id="datepicker" value="<?php echo $date?>" >
					</div>
					<div class="form-group" >
						<label class="col-sm-1" for="titre">Titre :</label>
					    <input type="text" class="col-sm-11" name="titre" required  value="<?php echo $titre ?>">
					</div>
					<?php 
					$i = 1;
					if (isset($result[0]['newsletter_detail'])) {
						foreach ($result[0]['newsletter_detail'] as $value) { 
							$url = $value['url'];
							if ($value['url']=='') 
								$url='/ajoutImage.jpg';  ?>
								
							<div class="form-group" style=" border:6px ridge white; padding: 30px 10px 30px 10px; ">
							
								<label class="col-sm-2" for="titre">Sous-titre <?php echo $i ?> :</label>
							  	
								<input type="text" class="col-sm-10" name="sstitre<?php echo $i ?>"  id="sstitre<?php echo $i ?>" value="<?php echo $value['titre'] ?>"><br>
		             			<input type="hidden"  name="url<?php echo $i ?>"  id="url<?php echo $i ?>" value="<?php echo $url ?>"><br>
		            			<a href="javascript:openCustomRoxy('<?php echo $i ?>')"><img src="/photos/newsletter/thumbs<?php echo $url ?>" id="customRoxyImage<?php echo $i ?>" style="max-width:600px;"></a>
								<img src="img/del.png" width="20" alt="Supprimer" onclick="clearImage(<?php echo $i ?>)"/>
								<br>
		 						<label for="link<?php echo $i ?>">Url image <?php echo $i ?>:</label><br>
		 						<input type="text" class="col-sm-11" name="link<?php echo $i ?>"  id="link<?php echo $i ?>" value="<?php echo $value['link'] ?>" ><br>
		 						<br>
		 						<label for="text<?php echo $i ?>">Texte <?php echo $i ?>:</label><br>
				           		<textarea class="col-sm-11"  name="texte<?php echo $i ?>"  id="texte<?php echo $i ?>" rows="2" ><?php echo $value['texte'] ?></textarea>
				           		<br><br><br>
								
							</div>
							<div class="form-group">
								<button class="btn btn-danger col-sm-6" type="submit" onclick="$('#postaction').val('delBloc');$('#idbloc').val('<?php echo  $value['id'] ?>');"> - Supprimer le bloc précédent</button>
							</div>
					<?php 
						$i++;
						} 
					} else {
						$i=1; ?>
							<div class="form-group" style=" border:6px ridge white; padding: 30px 10px 30px 10px; ">
								<label class="col-sm-2" for="titre">Sous-titre <?php echo $i ?> :</label>
							  	
								<input type="text" class="col-sm-10" name="sstitre<?php echo $i ?>"  id="sstitre<?php echo $i ?>" value=""><br>
		             			<input type="hidden"  name="url<?php echo $i ?>"  id="url<?php echo $i ?>" value=""><br>
		            			<a href="javascript:openCustomRoxy('<?php echo $i ?>')"><img src="img/ajoutImage.jpg"" id="customRoxyImage<?php echo $i ?>" style="max-width:600px;"></a>
								<img src="img/del.png" width="20" alt="Supprimer" onclick="clearImage(<?php echo $i ?>)"/>
								<br>
		 						<label for="link<?php echo $i ?>">Url image <?php echo $i ?>:</label><br>
		 						<input type="text" class="col-sm-11" name="link<?php echo $i ?>"  id="link<?php echo $i ?>" value="<?php echo $urlSiteDefault?>" ><br>
		 						<br>
		 						<label for="text<?php echo $i ?>">Texte <?php echo $i ?>:</label><br>
				           		<textarea class="col-sm-11"  name="texte<?php echo $i ?>"  id="texte<?php echo $i ?>" rows="2" ></textarea>
				           
							</div>						
					
					
					<?php 
						$i++;
					}
					
					if (isset($_GET['addBloc']) && $_GET['addBloc']==1) {  ?>
							<div class="form-group" style=" border:6px ridge white; padding: 30px 10px 30px 10px; ">
								<label class="col-sm-2" for="titre">Sous-titre <?php echo $i ?> :</label>
							  	
								<input type="text" class="col-sm-10" name="sstitre<?php echo $i ?>"  id="sstitre<?php echo $i ?>" value=""><br>
		             			<input type="hidden"  name="url<?php echo $i ?>"  id="url<?php echo $i ?>" value=""><br>
		            			<a href="javascript:openCustomRoxy('<?php echo $i ?>')"><img src="img/ajoutImage.jpg"" id="customRoxyImage<?php echo $i ?>" style="max-width:600px;"></a>
								<img src="img/del.png" width="20" alt="Supprimer" onclick="clearImage(<?php echo $i ?>)"/>
								<br>
		 						<label for="link<?php echo $i ?>">Url image <?php echo $i ?>:</label><br>
		 						<input type="text" class="col-sm-11" name="link<?php echo $i ?>"  id="link<?php echo $i ?>" value="<?php echo $urlSiteDefault?>" ><br>
		 						<br>
		 						<label for="text<?php echo $i ?>">Texte <?php echo $i ?>:</label><br>
				           		<textarea class="col-sm-11"  name="texte<?php echo $i ?>"  id="texte<?php echo $i ?>" rows="2" ></textarea>
				           
							</div>						
						
					<?php	
						$i++;
					}
					
					?>
					
					<div class="form-group" >
						<button class="btn btn-primary col-sm-6 " type="submit" onclick="$('#postaction').val('addBloc');"> + Ajouter un bloc </button>
					</div>
					<input type="hidden" name="ndencards" id="ndencards" value="<?php echo $i-1 ?>">
					<div class="form-group" >
						<label class="col-sm-1" for="titre">Bas de page :</label>
					    <textarea class="col-sm-11"  name="bas_page"  id="bas_page" rows="3" required ><?php echo $bas_page ?></textarea>
					</div>	
					<div class="form-group" >
		            	<button class="btn btn-success col-sm-12" type="submit" onclick="$('#postaction').val('modif')"> Valider </button>
		            </div>
					<div id="roxyCustomPanel" style="display: none;">
  							<iframe src="/admin/fileman2/index.html?integration=custom" style="width:100%;height:100%" frameborder="0"></iframe>
					</div>
					
					<script type="text/javascript">
						function openCustomRoxy(idImage){
							$('#idImage').val(idImage);
						 	$('#roxyCustomPanel').dialog({modal:true, width:875,height:600});
						}
						function closeCustomRoxy(){
						  	$('#roxyCustomPanel').dialog('close');
						}
	
						function clearImage(idImage){
							$('#customRoxyImage'+idImage).attr('src', 'img/ajoutImage.jpg');
							$('#url'+idImage).val('');
						}
						
					</script>
						
					
					<script type="text/javascript">
						
						$(document).ready(
						  /* This is the function that will get executed after the DOM is fully loaded */
						  function () {
						    $( "#datepicker" ).datepicker({
						    	altField: "#datepicker",
						    	closeText: 'Fermer',
						    	prevText: 'Précédent',
						    	nextText: 'Suivant',
						    	currentText: 'Aujourd\'hui',
						    	monthNames: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
						    	monthNamesShort: ['Janv.', 'Févr.', 'Mars', 'Avril', 'Mai', 'Juin', 'Juil.', 'Août', 'Sept.', 'Oct.', 'Nov.', 'Déc.'],
						    	dayNames: ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'],
						    	dayNamesShort: ['Dim.', 'Lun.', 'Mar.', 'Mer.', 'Jeu.', 'Ven.', 'Sam.'],
						    	dayNamesMin: ['D', 'L', 'M', 'M', 'J', 'V', 'S'],
						    	weekHeader: 'Sem.',
						    	dateFormat: 'dd/mm/yy'
						    });
						  }
	
						);
			
					</script>
		        </form>
			</div>
		</div>
	</div>
</body>
</html>


