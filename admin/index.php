<?php
	session_start();
	//Deconnection request
	if (isset($_GET['action']) && $_GET['action']=='getout') {
		$_SESSION['accessGranted'] = false;
	}
	
	//Si session existe => goto home.php
	if (isset($_SESSION['accessGranted']) && $_SESSION['accessGranted']) {
		 header('Location: /admin/home.php');
	}
?>
<!doctype html>
<html class="no-js" lang="fr">
<head>
<meta charset="utf-8">
<title>Back Office / Administration</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<!-- Le styles -->
<link href="/admin/css/bootstrap.min.css" media="screen" rel="stylesheet" type="text/css">
<link href="/admin/css/bootstrap-theme.min.css" media="screen" rel="stylesheet" type="text/css">
<link href="/admin/css/style.css" media="screen" rel="stylesheet" type="text/css">
<link href="/img/favicon.ico" rel="shortcut icon" type="image/vnd.microsoft.icon">
<!-- Scripts -->
<script type="text/javascript" src="/admin/js/custom.js"></script>
<!--[if lt IE 9]><script type="text/javascript" src="/admin/js/html5shiv.js"></script><![endif]-->
<!--[if lt IE 9]><script type="text/javascript" src="/admin/js/respond.min.js"></script><![endif]-->
<script type="text/javascript" src="/admin/js/jquery.min.js"></script>
<script type="text/javascript" src="/admin/js/bootstrap.min.js"></script>

</head>
<body>	

  	<div class="container">       
		<div class="col-md-4">
			<img src="img/logo.png" class="img-responsive" alt="Responsive image">
		</div>
	</div>
	
	<div class="container">    
		<div class="col-md-4">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title">Identifiez Vous</h3>
				</div>
				<?php if (isset($_GET['action']) && $_GET['action']=='error') { ?>
					<h4 class="bg-danger">- Erreur d'identification -</h4>
				<?php }?>
				<div class="panel-body">
					<form role="form" action="home.php" method="post">
				  		<div class="form-group">
				    		 <label for="login" class="control-label">Identifiants</label>
    						 <input type="text" class="form-control" name="login" id="login" placeholder="login" required>
				  		</div>
				  		<div class="form-group">
				    		 <label for="mdp" class="control-label">Identifiants</label>
    						 <input type="password" class="form-control" name="mdp" id="mdp" placeholder="mot de passe" required>
				  		</div>
				  		<button type="submit" class="btn btn-default">Validez</button>
					</form>
				</div>
			</div>
		</div>
	</div>
	
	
</body>
</html>



