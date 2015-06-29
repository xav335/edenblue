<?php
class StorageManager{

	protected $mysqli;
	
	public function __construct(){
	
	}
	
	////////////////// UTILS UTILS UTILS UTILS UTILS UTILS UTILS UTILS UTILS UTILS UTILS //////////////////////////
	protected function inserer_date($date) {
		$tab = explode("/", $date);
		return $tab[2] . "-" . $tab[1] . "-" . $tab[0];
	}
	
	protected function dbConnect() {
// 		$host = "localhost";
// 		$user = "secretsdelouise";
// 		$pass = "secretsdelouise33";
// 		$bdd = "secretsdelouise";
	    $host = DBHOST;
	    $user = DBUSER;
	    $pass = DBPASSWD;
	    $bdd  = DBNAME;
			
		// connexion
		try {
			$this->mysqli = new mysqli ($host, $user, $pass, $bdd) ;
			mysqli_query($this->mysqli,"SET NAMES UTF8");
		} catch (Exception $e) {
			throw new Exception("Erreur Connexion DB ". $e->getMessage());
		}
		
		
	}
	
	
	protected function dbDisConnect() {
		mysqli_close($this->mysqli);
	}
	
	protected function begin() {
		mysqli_query($this->mysqli,"BEGIN");
	}
	protected function commit() {
		mysqli_query($this->mysqli,"COMMIT");
	}
	protected function rollback() {
		mysqli_query($this->mysqli,"ROLLBACK");
	}

}

?>