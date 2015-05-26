<?php include_once 'inc-auth-granted.php';?>
<?php include_once 'classes/utils.php';?>
<?php 
require 'classes/Contact.php';

if (!empty($_GET)){ //Modif 
	$action = 'modif';
	$contact = new Contact();
	$result = $contact->contactGet($_GET['id'], null, null);
	//print_r($result);
	if (empty($result)) {
		$message = 'Aucun enregistrements';
	} else {
		$labelTitle= 	'Contact N°: '. $_GET['id'];
		$id= 			$_GET['id'];
		$name=  			$result[0]['name'];
		$email=  		$result[0]['email'];
		$firstname= 	$result[0]['firstname'];
		($result[0]['newsletter']=='1') ? $online = 'checked' : $online = '';
		($result[0]['fromcontact']=='1') ? $fromcontact = "origine: formulaire de contact" : $fromcontact = '';
		($result[0]['fromgoldbook']=='1') ? $fromgoldbook = "origine: livre d'or" : $fromgoldbook = '';
	}
} else { //ajout goldbook
	$action = 'add';
	$labelTitle = 'Edition Contact';
	$id= 			null;
	$name=  			null;
	$email= 		null;
	$firstname= 		null;
	$online= 	null;
	$fromcontact = '';
	$fromgoldbook = '';
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
			<h3><?php echo $labelTitle ?></h3><br>
			<div class="col-xs-12 col-sm-12 col-md-12">
				
					<form name="formulaire" class="form-horizontal" method="POST"  action="formprocess.php">
						<input type="hidden" name="reference" value="contact">
						<input type="hidden" name="action" value="<?php echo $action ?>">
						<input type="hidden" name="id" id="id" value="<?php echo $id ?>">
						
						<div class="form-group" >
							<label class="col-sm-1" for="titre">Prénom :</label>
						    <input type="text" class="col-sm-11" name="firstname" required  value="<?php echo $firstname ?>">
						</div>
						<div class="form-group" >
							<label class="col-sm-1" for="titre">Nom :</label>
						    <input type="text" class="col-sm-11" name="name" required  value="<?php echo $name ?>">
						</div>
						
						<div class="form-group" >
							<label class="col-sm-1" for="titre">Email :</label>
						    <input class="col-sm-11" name="email" type="email" required  value="<?php echo $email ?>">
						</div>
						
						<div class="form-group" >
							<label for="titre"> Newsletter:</label>
						    <input type="checkbox" name="newsletter" <?php echo  $online ?>>
						</div>
						<div class="form-group" >
							<label class="col-sm-3"><?php echo $fromcontact ?></label> ---- <label class="col-sm-3"><?php echo $fromgoldbook ?></label><br>
						</div>
			            <button class="btn btn-success col-sm-12" type="submit" class="btn btn-default"> Valider </button>
			        </form>
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
			</div>
		</div>
	</div>
</body>
</html>


