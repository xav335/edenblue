<?php include_once '../inc/inc.config.php'; ?>
<?php include_once 'inc-auth-granted.php';?>
<?php include_once 'classes/utils.php';?>
<?php 
require 'classes/News.php';

	$news = new News();
	$result = $news->newsGet(null);
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

				<table class="table table-hover table-bordered table-condensed table-striped" >
					<thead>
						<tr>
							<th class="col-md-1" style="">
								Date
							</th>
							<th class="col-md-1" style="">
								Titre
							</th>
							<th class="col-md-2" style="">
								lien 
							</th>
							<th class="col-md-5" style="">
								Contenu
							</th>
							<th class="col-md-1" style="">
								Photo
							</th>
							<th class="col-md-1" style="">
								A la une
							</th>
							<th class="col-md-1" colspan="2" style="">
								Actions
							</th>
							
						</tr>
					</thead>
					<tbody>
						<?php 
						if (!empty($result)) {
							$i=0;
							foreach ($result as $value) { 
							$i++;
							if($value['online']=='1') {
								$online = 'check';
							} else {
								$online = 'vide';
							}
							?>
							<tr class="<?php if ($i%2!=0) echo 'info'?>">
								
								<td><?php echo traitement_datetime_affiche($value['date_news'])?></td>
								<td><?php echo $value['titre']?></td>
								<td><?php echo $value['accroche']?></td>
								<td><?php echo $value['contenu']?></td>
								<td><?php if(!empty($value['image1']) && isset($value['image1'])) echo 'image OK' ?></td>
								<td><img src="img/<?php echo $online ?>.png" width="30" ></td>
								<td><a href="news-edit.php?id=<?php echo $value['id_news'] ?>"><img src="img/modif.png" width="30" alt="Modifier" ></a></td>
								<td>
									<div style="display: none;" class="supp<?php echo $value['id_news']?> alert alert-warning alert-dismissible fade in" role="alert">
								      <button type="button" class="close"  aria-label="Close" onclick="$('.supp<?php echo $value['id_news']?>').css('display', 'none');"><span aria-hidden="true">×</span></button>
								      <strong>Voulez vous vraiment supprimer ?</strong>
								      <button type="button" class="btn btn-danger" onclick="location.href='news-fp.php?reference=news&action=delete&id=<?php echo $value['id_news'] ?>'">Oui !</button>
								 	</div>
								<img src="img/del.png" width="20" alt="Supprimer" onclick="$('.supp<?php echo $value['id_news']?>').css('display', 'block');"> </td>
							</tr>
							<?php } ?>
						<?php } ?>	
					</tbody>
				</table>

				<h3><?php echo $message?></h3>
			</div>
		</div>
	</div>
</body>
</html>


