<?php include_once '../inc/inc.config.php'; ?>
<?php include_once 'inc-auth-granted.php';?>
<?php include_once 'classes/utils.php';?>
<?php include_once 'classes/pagination.php';?>
<?php 
require 'classes/Newsletter.php';

	$newsletter = new Newsletter();
	$total = $newsletter->journalNewsletterDetailNumberGet($_GET['id'],null);
	$totalread = $newsletter->journalNewsletterDetailNumberGet($_GET['id'],1);
	//echo $totalread;
	
	$epp = 13; // nombre d'entrées à afficher par page (entries per page)
	$nbPages = ceil($total/$epp); // calcul du nombre de pages $nbPages (on arrondit à l'entier supérieur avec la fonction ceil())
	 
	// Récupération du numéro de la page courante depuis l'URL avec la méthode GET
	// S'il s'agit d'un nombre on traite, sinon on garde la valeur par défaut : 1
	$current = 1;
	if (isset($_GET['p']) && is_numeric($_GET['p'])) {
		$page = intval($_GET['p']);
		if ($page >= 1 && $page <= $nbPages) {
			// cas normal
			$current=$page;
		} else if ($page < 1) {
			// cas où le numéro de page est inférieure 1 : on affecte 1 à la page courante
			$current=1;
		} else {
			//cas où le numéro de page est supérieur au nombre total de pages : on affecte le numéro de la dernière page à la page courante
			$current = $nbPages;
		}
	}
	
	// $start est la valeur de départ du LIMIT dans notre requête SQL (dépend de la page courante)
	$start = ($current * $epp - $epp);
	
	// Récupération des données à afficher pour la page courante
	$result = $newsletter->journalNewsletterDetailGet($_GET['id'], $start, $epp);
	
	
	//print_r($result);
	if (empty($result)) {
		$message = 'Aucun enregistrements';
	} else {
		$message = '';
	}

?>
<!doctype html>
<html class="no-js" lang="en">
<head>
	<?php include_once 'inc-meta.php';?>
</head>
<body>	
	<?php require_once 'inc-menu.php';?>

	<div class="container">
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12">
			<h3>Tracking de l'envoi de mail n°: <?php echo $_GET['id']?></h3>
				<?php echo paginate('journalnewsletter-detail.php?id='. $_GET['id'], '&p=', $nbPages, $current); ?>
				<a class="btn btn-success pull-right" href="/admin/newsletterjournal-list.php">Retour</a>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12">
				
				<table class="table table-hover table-bordered table-condensed table-striped" >
					<thead>
						<tr>
							<th class="col-md-3" style="" colspan="3">
								<h4>Emails lus: <?php echo $totalread?> - Emails non lus : <?php echo $total-$totalread?></h4>  
							</th>
						</tr>
					</thead>
					<thead>
						<tr>
							<th class="col-md-3" style="">
								Nom prénom
							</th>
							<th class="col-md-1" style="">
								Lu par le destinataire
							</th>
							<th class="col-md-1" style="">
								Message erreur
							</th>
						
						</tr>
					</thead>
					<tbody>
						<?php 
						if (!empty($result)) {
							$i=0;
							foreach ($result as $value) { 
							$i++;
							if($value['read']=='1') {
								$online = 'check';
							} else {
								$online = 'vide';
							}
							?>
							<tr class="<?php if ($i%2!=0) echo 'info'?>">
								
								<td><?php echo $value['email']?></td>
								<td><img src="img/<?php echo $online ?>.png" width="30" ></td>
								<td><?php echo $value['error'] ?></td>
							</tr>
							<?php } ?>
						<?php } ?>	
					</tbody>
				</table>

				<h3><?php echo $message?></h3>
			</div>
			<?php echo paginate('journalnewsletter-detail.php?id='. $_GET['id'], '&p=', $nbPages, $current); ?>
		</div>
	</div>
</body>
</html>


