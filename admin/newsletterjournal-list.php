<?php include_once '../inc/inc.config.php'; ?>
<?php include_once 'inc-auth-granted.php';?>
<?php include_once 'classes/utils.php';?>
<?php 
require 'classes/Newsletter.php';

	$newsletter = new Newsletter();
	$result = $newsletter->journalNewsletterGet();
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
				<h3>Liste des envois en masse</h3><br>
				<table class="table table-hover table-bordered table-condensed table-striped" >
					<thead>
						<tr>
							<th class="col-md-1" style="">
								ID
							</th>
							<th class="col-md-1" style="">
								Date
							</th>
							<th class="col-md-2" style="">
								Titre
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
							?>
							<tr class="<?php if ($i%2!=0) echo 'info'?>">
								<td><?php echo $value['id']?></td>
								<td><?php echo traitement_datetime_affiche($value['date_envoi'])?></td>
								<td><?php echo $value['titre']?></td>
								<td><a href="newsletterjournal-detail.php?id=<?php echo $value['id_newsletter'] ?>"><img src="img/modif.png" width="30" alt="Modifier" ></a>&nbsp;&nbsp;&nbsp;&nbsp;
								<img src="img/eye.png" width="20" alt="preview" onclick="openPreview('<?php echo $value['id']?>')"> </td>
								<td>
							</tr>
							<?php } ?>
						<?php } ?>	
					</tbody>
				</table>

				<h3><?php echo $message?></h3>
			</div>
		</div>
	</div>
	
					<div id="preview" style="display: none;">
  						<iframe id="laframe" src="" style="width:100%;height:100%" frameborder="0"></iframe>
					</div>
					<script type="text/javascript">
						function openPreview(id){
							$('#laframe').attr('src', '/admin/newsletter-corecontent.php?id='+id);
						 	$('#preview').dialog({modal:true, width:780,height:500});
						}
					</script>
	
</body>
</html>


