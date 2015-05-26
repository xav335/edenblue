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
// 		$user = "edenblue";
// 		$pass = "edenblue33";
// 		$bdd = "edenblue";
		
		$host = "edenbluesy1.mysql.db";
		$user = "edenbluesy1";
		$pass = "Edenblue33";
		$bdd = "edenbluesy1";
			
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