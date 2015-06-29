<?php
require_once("StorageManager.php");
class Newsletter extends StorageManager {

	public function __construct(){

	}
	
	public function newsletterGet($id){
		 $this->dbConnect();
		if (!isset($id)){
			$requete = "SELECT * FROM `newsletter` ORDER BY `date` DESC" ;
		} else {
			$requete = "SELECT * FROM `newsletter` WHERE `id_newsletter`=". $id ;
		}
		//print_r($requete);
		$new_array = null;
		$result = mysqli_query($this->mysqli,$requete);
		while( $row = mysqli_fetch_assoc( $result)){
			$new_array[] = $row;
		}
		
		$this->dbDisConnect();
		return $new_array;
	}
	
	public function newsletterAllGet($id){
		 $this->dbConnect();
			
		$requete = "SELECT * FROM `newsletter` WHERE `id`=". $id ;
		//print_r($requete);
		$new_array = null;
		$result = mysqli_query($this->mysqli,$requete);
		while( $row = mysqli_fetch_assoc( $result)){
			$new_arraydetail = null;
			$requete = "SELECT * FROM `newsletter_detail` as nld
						WHERE `id_newsletter`=". $row['id'] ." ORDER BY `id` ASC" ;
			//print_r($requete);
			$resultdetail = mysqli_query($this->mysqli,$requete);
			
			$row['newsletter_detail'] = null;
			while( $rowdetail = mysqli_fetch_assoc( $resultdetail)){
				$row['newsletter_detail'][] = $rowdetail;
			}
			
			$new_array[] = $row;
		}	
		$this->dbDisConnect();
		return $new_array;
	}
	
	public function newsletterAdd($value){
		//print_r($value); exit();
		 $this->dbConnect();
		$this->begin();
		try {
			$sql = "INSERT INTO  .`newsletter`
						(`date`, `titre`, `bas_page`)
						VALUES (
						'". $this->inserer_date($value['datepicker']) ."',
						'". addslashes($value['titre']) ."',
						'". addslashes($value['bas_page']) ."'
					);";
			$result = mysqli_query($this->mysqli,$sql);
				
			if (!$result) {
				throw new Exception($sql);
			}
			$id_record = mysqli_insert_id($this->mysqli);
			
			$this->newsletterDetailAdd($value,$id_record);
			
			$this->commit();
	
		} catch (Exception $e) {
			$this->rollback();
			throw new Exception("Erreur Mysql newsletterAdd". $e->getMessage());
			return "errrrrrrooooOOor";
		}
		$this->dbDisConnect();
		return $id_record;
	}
	
	public function newsletterModify($value){
		//print_r($value); exit();
	
		 $this->dbConnect();
		$this->begin();
		try {
			$sql = "UPDATE  .`newsletter` SET
					`date`='". $this->inserer_date($value['datepicker']) ."',
					`titre`='". addslashes($value['titre']) ."',
					`bas_page`='". addslashes($value['bas_page']) ."'
					WHERE `id`=". $value['id'] .";";
			$result = mysqli_query($this->mysqli,$sql);
			if (!$result) {
				throw new Exception($sql);
			}
			
			$this->newsletterDetailDelete( $value['id']);
			
			$this->newsletterDetailAdd($value, $value['id']);
			
			$this->commit();
	
		} catch (Exception $e) {
			$this->rollback();
			throw new Exception("Erreur Mysql newsletterModify ". $e->getMessage());
			return "errrrrrrooooOOor";
		}
	
		$this->dbDisConnect();
	}
	
	private function newsletterDetailAdd($value,$id){
		
		for ($i = 1; $i <  $value['ndencards']+1; $i++) {
		
			$sql = "INSERT INTO  .`newsletter_detail`
						(`id_newsletter`,`titre`, `url`, `link`,`texte`)
						VALUES (
						". $id .",
						'". addslashes($value['sstitre'.$i]) ."',
						'". addslashes($value['url'.$i]) ."',
						'". addslashes($value['link'.$i]) ."',
						'". addslashes($value['texte'.$i]) ."'
					);";
			$result = mysqli_query($this->mysqli,$sql);
		
			if (!$result) {
				throw new Exception($sql);
			}
		
		}
	}	
	
	
	private function newsletterDetailDelete($id_newsletter){
		//print_r($id_newsletter); exit();
		try {
			$sql = "DELETE FROM  .`newsletter_detail`
					WHERE `id_newsletter`=". $id_newsletter .";";
			$result = mysqli_query($this->mysqli,$sql);
			if (!$result) {
				throw new Exception($sql);
			}
		
		} catch (Exception $e) {
			throw new Exception("Erreur Mysql ". $e->getMessage());
			return "errrrrrrooooOOor";
		}
		
	}
	
	
	public function newsletterDetailUniqueDelete($id){
		//print_r($id); exit();
	
		 $this->dbConnect();
		$this->begin();
		try {
		$sql = "DELETE FROM  .`newsletter_detail`
					WHERE `id`=". $id .";";
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
	
	}
	
	public function newsletterDelete($value){
		//print_r($value); exit();
	
		 $this->dbConnect();
		$this->begin();
		try {
			$sql = "DELETE FROM  .`newsletter`
					WHERE `id`=". $value .";";
			$result = mysqli_query($this->mysqli,$sql);
	
			if (!$result) {
				throw new Exception($sql);
			}
	
			$this->newsletterDetailDelete( $value );
			
			$this->commit();
	
		} catch (Exception $e) {
			$this->rollback();
			throw new Exception("Erreur Mysql ". $e->getMessage());
			return "errrrrrrooooOOor";
		}
	
	
		$this->dbDisConnect();
	}
	
	public function journalNewsletterAdd($value){
		//print_r($value); exit();
		$this->dbConnect();
		$this->begin();
		try {
			$sql = "INSERT INTO  newsletter_journal
						(id_newsletter)
						VALUES (
						'". addslashes($value) ."'
					);";
			$result = mysqli_query($this->mysqli,$sql);
	
			if (!$result) {
				throw new Exception($sql);
			}
			$id_record = mysqli_insert_id($this->mysqli);
				
			$this->commit();
	
		} catch (Exception $e) {
			$this->rollback();
			throw new Exception("Erreur Mysql journalNewsletterAdd". $e->getMessage());
			return "errrrrrrooooOOor";
		}
		$this->dbDisConnect();
		return $id_record;
	}
	
	public function journalNewsletterDetailAdd($id_newsletter_journal,$email,$coderandom,$error){
		//print_r($value); exit();
		$this->dbConnect();
		$this->begin();
		try {
			$sql = "INSERT INTO  newsletter_journal_detail
						(id_newsletter_journal,email,coderandom,error)
						VALUES (
						". $id_newsletter_journal .",
						'". addslashes($email) ."',
						'". addslashes($coderandom) ."',
						'". addslashes($error) ."'
					);";
			$result = mysqli_query($this->mysqli,$sql);
	
			if (!$result) {
				throw new Exception($sql);
			}
			$id_record = mysqli_insert_id($this->mysqli);
	
			$this->commit();
	
		} catch (Exception $e) {
			$this->rollback();
			throw new Exception("Erreur Mysql journalNewsletterDetailAdd". $e->getMessage());
			return "errrrrrrooooOOor";
		}
		$this->dbDisConnect();
		return $id_record;
	}
	
	public function journalNewsletterTrack($value){
		//print_r($value); exit();
		$this->dbConnect();
		$this->begin();
		try {
			$sql = "UPDATE  newsletter_journal_detail SET
					`read`=1 WHERE `coderandom`='". $value ."';";
			$result = mysqli_query($this->mysqli,$sql);
			if (!$result) {
				throw new Exception($sql);
			}
				
			$this->commit();
	
		} catch (Exception $e) {
			$this->rollback();
			throw new Exception("Erreur Mysql journalNewsletterTrack ". $e->getMessage());
			return "errrrrrrooooOOor";
		}
	
		$this->dbDisConnect();
	}
	
	public function journalNewsletterGet(){
		$this->dbConnect();
		try {
			$sql = "SELECT nsj.date_envoi, newsletter.titre,newsletter.id,nsj.id as id_newsletter
					FROM newsletter_journal as nsj 
					INNER JOIN newsletter ON newsletter.id = nsj.id_newsletter 
					ORDER BY date_envoi DESC;" ;
			//print_r($requete);
			$new_array = null;
			$result = mysqli_query($this->mysqli,$sql);
			while( $row = mysqli_fetch_assoc( $result)){
				$new_array[] = $row;
			}
			$this->dbDisConnect();
			return $new_array;
		} catch (Exception $e) {
			throw new Exception("Erreur Mysql contactGet ". $e->getMessage());
			return "errrrrrrooooOOor";
		}
	}
	
	public function journalNewsletterDetailNumberGet($id, $read){

		$this->dbConnect();
		try {
			if(!empty($read)){
				$sql = "SELECT count(*) as nb FROM `newsletter_journal_detail` 
					WHERE id_newsletter_journal=".$id ." AND `read`=". $read .";" ;
			} else {
				$sql = "SELECT count(*) as nb FROM `newsletter_journal_detail`
					WHERE id_newsletter_journal=".$id .";" ;
			}
			//print_r($sql);
			$new_array = null;
			$result = mysqli_query($this->mysqli,$sql);
			while( $row = mysqli_fetch_assoc( $result)){
				$new_array[] = $row;
			}
			$this->dbDisConnect();
			return $new_array[0]['nb'];
		} catch (Exception $e) {
			throw new Exception("Erreur Mysql contactGet ". $e->getMessage());
			return "errrrrrrooooOOor";
		}
	}
	
	public function journalNewsletterDetailGet($id, $offset, $count){
		$this->dbConnect();
		try {
				if (isset($offset) && isset($count)) {
					$sql = "SELECT * FROM `newsletter_journal_detail` WHERE `id_newsletter_journal`=".$id ." ORDER BY `email` ASC LIMIT ". $offset .",". $count .";" ;
				} else {
					$sql = "SELECT * FROM `newsletter_journal_detail` WHERE `id_newsletter_journal`=".$id ." ORDER BY `email`;" ;
				}
			//print_r($sql);
			$new_array = null;
			$result = mysqli_query($this->mysqli,$sql);
			while( $row = mysqli_fetch_assoc( $result)){
				$new_array[] = $row;
			}
			$this->dbDisConnect();
			return $new_array;
		} catch (Exception $e) {
			throw new Exception("Erreur Mysql contactGet ". $e->getMessage());
			return "errrrrrrooooOOor";
		}
	}
	
	
}