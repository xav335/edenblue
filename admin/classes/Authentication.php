<?php
require_once("StorageManager.php");
class Authentication extends StorageManager {

	public function __construct(){

	}
	
	public function grantAccess($login,$mdp){
		$this->dbConnect();
		$requete = "SELECT * FROM admin";
		$requete .= " WHERE login = '" . mysqli_real_escape_string($this->mysqli, $login ) . "'";
		$requete .= " AND mdp = '" . mysqli_real_escape_string($this->mysqli, $mdp ) . "'";
		//echo $requete . "<br><br>";exit();
		$result = mysqli_query($this->mysqli,$requete);
		if (!$result) {
			throw new Exception("Erreur Mysql grantAccess". $sql);
		}
		$num_rows = mysqli_num_rows($result);
		$this->dbDisConnect();
		if ($num_rows > 0)  {
			return true;
		} else {
			return false;
		}
	
	}
	
}