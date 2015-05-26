<?php
require_once("StorageManager.php");
class Planning extends StorageManager {

	public function __construct(){

	}

	public function planningGet(){
		 $this->dbConnect();
		$requete = "SELECT * FROM `planning` WHERE id=1";
		//print_r($requete);
		$new_array = null;
		$result = mysqli_query($this->mysqli,$requete);
		while( $row = mysqli_fetch_assoc( $result)){
			$new_array[] = $row;
		}
		$this->dbDisConnect();
		return $new_array;
	}

	
	
	
	public function planningModify($value){
		//print_r($value);
		//exit();
	
		 $this->dbConnect();
		$this->begin();
		try {
			$sql = "UPDATE  .`planning` SET
					`titre`='". addslashes($value['titre']) ."',
					`url`='". addslashes($value['url']) ."',
					`pdf`='". addslashes($value['pdf']) ."'
					WHERE `id`= 1;";
			$result = mysqli_query($this->mysqli,$sql);
	
			if (!$result) {
				throw new Exception($sql);
			}
	
			$this->commit();
	
		} catch (Exception $e) {
			$this->rollback();
			throw new Exception("Erreur Mysql ". $e->getMessage());
			return "errrrrrrooooOOor";
		}
	
	
		$this->dbDisConnect();
	}	
	
	
	
}