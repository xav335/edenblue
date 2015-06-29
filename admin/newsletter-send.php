<?php include_once '../inc/inc.config.php'; ?>
<?php include_once 'inc-auth-granted.php';?>
<?php include_once 'classes/utils.php';?>
<?php 
$id = null;
if (!empty($_GET)){
	$id = $_GET['id'];
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
			<div class="col-md-10">
				<h3>Aperçu de la newsletter :</h3>
				<iframe id="laframe" src="/admin/newsletter-corecontent.php?id=<?php  echo $id ?>" style="width:720px;height:500px;" frameborder="1" ></iframe>
			</div>
			<div class="col-md-2">
				<h4>Tester la news <br>(envoi limité à <?php echo $mailCustomer?>)</h4>
				<form name="formulaire" class="form-horizontal" method="POST"  action="newsletter-fp.php">
					<input type="hidden" name="reference" value="newsletter">
					<input type="hidden" name="action" id="action" value="envoi">
					<input type="hidden" name="postaction" id="postaction" value="">
					<input type="hidden" name="id" id="id" value="<?php echo $id ?>">
		            <button class="btn btn-warning" type="submit" onclick="return validation('preview')"> Tester la newsletter </button>
		            <br><br><br><br><br><br><br>
		            <h4>Envoi Massif de la newsletter à tous les contacts :</h4>
					<button class="btn btn-danger" type="submit" class="btn btn-default" onclick="return validation('envoi')"> Envoi massif </button>
				</form>
					<script type="text/javascript">
						function validation(variable){
							$('#postaction').val(variable);
							return confirm('Etes vous certain ! Dernière chance');
						}
					
					</script>
			</div>
		</div>
	</div>
	
</body>
</html>


