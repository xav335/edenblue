<?php include_once '../inc/inc.config.php'; ?>
<?php include_once 'inc-auth-granted.php';?>
<?php include_once 'classes/utils.php';?>
<?php 
require 'classes/News.php';

if (!empty($_GET)){ //Modif 
	$action = 'modif';
	$news = new News();
	$result = $news->newsGet($_GET['id']);
	//print_r($result);
	if (empty($result)) {
		$message = 'Aucun enregistrements';
	} else {
		$labelTitle = 'Actu N°: '. $_GET['id'];
		$id= 			$_GET['id'];
		$titre=  		$result[0]['titre'];
		$date_news= 	traitement_datetime_affiche($result[0]['date_news']);
		$accroche= 		$result[0]['accroche'];
		$contenu= 	$result[0]['contenu'];
		if($result[0]['online']=='1') {
			$online = 'checked';
		} else {
			$online = '';
		}
		for ($i=1;$i<2;$i++) {
			$image[$i] = 	$result[0]['image'.$i];
			if(empty($image[$i]) || !isset($image[$i])){
				$img[$i]  = '/img/favicon.png';
				$imgval[$i]  = '';
			} else {
				$img[$i]  = '/photos/news/thumbs'. $image[$i];
				$imgval[$i]  = $image[$i];
			}
		}
	}
} else { //ajout News
	$action = 'add';
	$labelTitle = 'Edition Nouvelle actu ';
	$id= 			null;
	$titre=  		null;
	$date_news= 	null;
	$accroche= 		null;
	$contenu= 		null;
	$online = 		null;
	for ($i=1;$i<2;$i++) {
		$img[$i]  = '/img/favicon.png';
		$imgval[$i]  = '';
	}
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
				<form name="formulaire" class="form-horizontal" method="POST"  action="news-fp.php">
					<input type="hidden" name="reference" value="news">
					<input type="hidden" name="action" value="<?php echo $action ?>">
					<input type="hidden" name="id" id="id" value="<?php echo $id ?>">
					
					<div class="form-group" >
						<label class="col-sm-1">Date :</label>
					    <input class="col-sm-2" type="text" name="datepicker" required id="datepicker" value="<?php echo $date_news?>" >
					</div>
					 <div class="form-group" >
						<label for="titre">Actu à la une :</label>
					    <input type="checkbox" name="online" <?php echo  $online ?>>
					</div>
					<div class="form-group" >
						<label class="col-sm-1" for="titre">Titre :</label>
					    <input type="text" class="col-sm-11" name="titre" required  value="<?php echo $titre ?>">
					</div>
					
					<div class="form-group">
						<label for="accroche">Contenu :</label><br>
		           		<textarea class="col-sm-11" name="contenu" id="contenu" rows="5" ><?php echo $contenu ?></textarea>
		            </div>
		            
		            <div class="form-group" >
						<label for="accroche">Lien vers produit ou page du site :</label><br>
		           		<input class="col-sm-8"  name="accroche"  id="accroche" value="<?php echo $accroche ?>" />
		            </div> 
		           
		            <div class="form-group"><br>
						<label  for="titre">Choisissez la photos </label>
						<input type="hidden"  name="idImage"  id="idImage" value="">
					</div>	
				
						<?php for ($i=1;$i<2;$i++) {?>
							<div class="col-md-4">
						<input type="hidden"  name="url<?php echo $i ?>"  id="url<?php echo $i ?>" value="<?php echo $imgval[$i]?>"><br>
            			<a href="javascript:openCustomRoxy('<?php echo $i ?>')"><img  src="<?php echo $img[$i]?>" id="customRoxyImage<?php echo $i ?>" style="max-width:200px;"></a>
						<img src="img/del.png" width="20" alt="Supprimer" onclick="clearImage(<?php echo $i ?>)"/>
						<br><br>
						</div>	
						<?php }?>
					
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
							$('#customRoxyImage'+idImage).attr('src', '/img/favicon.png');
							$('#url'+idImage).val('');
						}
						
					</script>
		            <button class="btn btn-success col-sm-12" type="submit" class="btn btn-default"> Valider </button>
		            
					<script type="text/javascript">
						tinymce.init({
						selector: "textarea.editme",
						// ===========================================
						// INCLUDE THE PLUGIN
						// ===========================================
						plugins: [
						"advlist autolink lists link image charmap print preview anchor",
						"searchreplace visualblocks code fullscreen textcolor",
						"insertdatetime media table contextmenu paste jbimages"
						],
											
						// ===========================================
						// PUT PLUGIN'S BUTTON on the toolbar
						// ===========================================
						toolbar: "insertfile undo redo | styleselect | forecolor backcolor | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image jbimages",
						// ===========================================
						// SET RELATIVE_URLS to FALSE (This is required for images to display properly)
						// ===========================================
						// AJOUTE LES URL EN ENTIER decommenter les 2 lignes suivantes
						//document_base_url: "http://dev.bsport.fr",
						//remove_script_host : false,
						relative_urls: false,
						file_browser_callback: RoxyFileBrowser
						
						});
	
	
						function RoxyFileBrowser(field_name, url, type, win) {
						  var roxyFileman = '/admin/fileman/index.html';
						  if (roxyFileman.indexOf("?") < 0) {     
						    roxyFileman += "?type=" + type;   
						  }
						  else {
						    roxyFileman += "&type=" + type;
						  }
						  roxyFileman += '&input=' + field_name + '&value=' + document.getElementById(field_name).value;
						  if(tinyMCE.activeEditor.settings.language){
						    roxyFileman += '&langCode=' + tinyMCE.activeEditor.settings.language;
						  }
						  tinyMCE.activeEditor.windowManager.open({
						     file: roxyFileman,
						     title: 'Gestionnaire de fichiers',
						     width: 850, 
						     height: 650,
						     resizable: "yes",
						     plugins: "media",
						     inline: "yes",
						     close_previous: "no"  
						  }, {     window: win,     input: field_name    });
						  return false; 
						}

						
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


