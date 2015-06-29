<?php include_once '../inc/inc.config.php'; ?>
<?php 
require 'classes/Authentication.php';
session_start();

$authentication = new Authentication();
if (!isset($_SESSION['accessGranted']) || !$_SESSION['accessGranted']) {
	$result = $authentication->grantAccess($_POST['login'], $_POST['mdp']);
	if (!$result){
		header('Location: /admin/?action=error');
	} else {
		$_SESSION['accessGranted'] = true;
	}
}

?>
<!doctype html>
<html class="no-js" lang="en">
<head>
	<?php include_once 'inc-meta.php';?>
</head>
<body>	
	<?php require_once 'inc-menu.php';?>
  	
	<div class="row">
		<div class="col-md-12">
			<iframe id="laframe" src="https://logs.ovh.net/edenblue.fr/urchin6/" style="width:1100px;height:700px;" frameborder="1" ></iframe>
		</div>
		
	</div>	
		
</body>
</html>


