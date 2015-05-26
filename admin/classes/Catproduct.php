<?php
require_once("StorageManager.php");
require_once("Zebra_Image.php");

class Catproduct extends StorageManager {

    var $tabView = null;
	var $i = 0;
	
	public function __construct(){
		
		
	}
	
	public function catproductByParentGet($id){
		$this->dbConnect();
		$requete = "SELECT * FROM `catproduct` WHERE parent=". $id ." ORDER BY ordre" ;
		//print_r($requete);
		$new_array = null;
		$result = mysqli_query($this->mysqli,$requete);
		while( $row = mysqli_fetch_assoc( $result)){
			$new_array[] = $row;
		}
		$this->dbDisConnect();
		return $new_array;
	}
	
	public function getProductsByCategorie($id){
		$this->dbConnect();
		$requete = "SELECT product.id,product.reference,product.prix,product.libprix,product.label,product.accroche,
					product.titreaccroche,product.description,product.image1,product.image2,product.image3,
					catproduct.label as catlabel
					FROM product 
					INNER JOIN product_categorie ON product.id = product_categorie.id_product
					INNER JOIN catproduct ON catproduct.id = product_categorie.id_categorie
					WHERE product_categorie.id_categorie=". $id ;
		//print_r($requete);exit();
		$new_array = null;
		$result = mysqli_query($this->mysqli,$requete);
		while( $row = mysqli_fetch_assoc( $result)){
			$new_array[] = $row;
		}
		$this->dbDisConnect();
		return $new_array;
	}
	
	public function getCategorieByProduct($id){
		$requete = "SELECT catproduct.label as catlabel,catproduct.id as catid
					FROM product 
					INNER JOIN product_categorie ON product.id=product_categorie.id_product 
					INNER JOIN catproduct ON catproduct.id = product_categorie.id_categorie 
					WHERE product.id=". $id ;
		//print_r($requete);exit();
		$new_array = null;
		$result = mysqli_query($this->mysqli,$requete);
		while( $row = mysqli_fetch_assoc( $result)){
			$new_array[] = $row;
		}
		return $new_array;
	}
	
	
	public function catproduitViewIterative($result){
		if ($this->i==0){
			$result = $this->catproductByParentGet(0);
			//print_r($result);
		}
		if (!empty($result)) {
	
			foreach ($result as $value) {
				$decalage = "";
				//for ($j=0; $j<($value['level'] * 5); $j++) $decalage .= " ";
				//echo $decalage. $this->i  . $value['label']." ". $value['id'] ." Lev:". $value['level'] . "<br>";
				$this->tabView[$this->i]['label'] = $value['label'];
				$this->tabView[$this->i]['id'] = $value['id'];
				$this->tabView[$this->i]['level'] = $value['level'];
				$this->tabView[$this->i]['description'] = $value['description'];
				$this->tabView[$this->i]['image'] = $value['image'];
				$result = $this->catproductByParentGet($value['id']);
				//print_r($result);
				$this->i++;
				$this->catproduitViewIterative($result);
			}
	
		}
	}
	
	public function catproductGet($id){
		$this->dbConnect();
		$requete = "SELECT * FROM `catproduct` WHERE id=". $id ;
		//print_r($requete);
		$new_array = null;
		$result = mysqli_query($this->mysqli,$requete);
		while( $row = mysqli_fetch_assoc( $result)){
			$new_array[] = $row;
		}
		$this->dbDisConnect();
		return $new_array;
	}
	
	public function catproductAdd($value){
		if ($value['parent'] != 0){
			$parent = $this->catproductGet($value['parent']);
			$level = $parent[0]['level']+1;
		} else {
			$level = 0;
		}
		//print_r($value);exit();
		
		$this->dbConnect();
		$this->begin();
		
		$sql = "INSERT INTO  .`catproduct`
					(`label`, `parent`, `level`)
					VALUES (
					'". addslashes($value['label']) ."',
					'". addslashes($value['parent']) ."',
					". $level ." 	
				);";
		$result = mysqli_query($this->mysqli,$sql);
		
		if (!$result) {
			$this->rollback();
			throw new Exception('Erreur Mysql catproductAdd sql = : '.$sql);
		}
		$id_record = mysqli_insert_id($this->mysqli);
		$this->commit();
		
		$this->dbDisConnect();
		return $id_record;
	}
	
	public function catproductModify($value){
		//print_r($value);exit();
		$this->dbConnect();
		$this->begin();
		try {
			$sql = "UPDATE  .`catproduct` SET
					`label`='". addslashes($value['label']) ."', 
					`description`='". addslashes($value['description']) ."', 
					`image`='". addslashes($value['url1']) ."' 
					WHERE `id`=". $value['id'] .";";
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
	
	public function catproductDelete($value){
		
		//Check if the categorie is empty !
		$prod = $this->getProductsByCategorie($value);
		//print_r($prod);
		if (!empty($prod)){
			throw new Exception("La categorie n'est pas vide ! ",1234);
		}
		
		$this->dbConnect();
		$this->begin();
		
		$sql = "DELETE FROM  .`catproduct` 
				WHERE `id`=". $value .";";
		$result = mysqli_query($this->mysqli,$sql);
			
		if (!$result) {
			$this->rollback();
			throw new Exception('Erreur Mysql catproductDelete sql = : '.$sql);
		}

		$this->commit();
	
		$this->dbDisConnect();
	}
	
	public function productNumberGet($categorie){
		$this->dbConnect();
		
		if (empty($categorie)) {
			$sql = "SELECT count(*) as nb FROM `product`;" ;
		} else {
			$sql = "SELECT count(*) as nb
					FROM product
					INNER JOIN product_categorie 
					ON product_categorie.id_product=product.id
					WHERE product_categorie.id_categorie=". $categorie . ";" ;
				
		}	
		//print_r($requete);
		$new_array = null;
		$result = mysqli_query($this->mysqli,$sql);
		if (!$result) {
			throw new Exception('Erreur Mysql productNumberGet sql = : '.$sql);
		}
		while( $row = mysqli_fetch_assoc( $result)){
			$new_array[] = $row;
		}
		$this->dbDisConnect();
		return $new_array[0]['nb'];
	}
	
	public function productGet($id, $offset, $count, $categorie){
		$this->dbConnect();
		try {
			if (!isset($id)){
				if (empty($categorie) && isset($offset) && isset($count)) {
					$sql = "SELECT product.id,product.reference,product.prix,product.libprix,product.label
								FROM product 
								ORDER BY  product.label
								ASC LIMIT ". $offset .",". $count .";" ;
					
				} elseif (!empty($categorie) && isset($offset) && isset($count)) {
					$sql = "SELECT product.id,product.reference,product.prix,product.libprix,product.label
								FROM product
								INNER JOIN product_categorie 
								ON product_categorie.id_product=product.id
								WHERE product_categorie.id_categorie=". $categorie . "
								ORDER BY  product.label
								ASC LIMIT ". $offset .",". $count .";" ;
					
				} else {
					$sql = "SELECT * FROM `product` ORDER BY `label`;" ;
				}
			} else {
				$sql = "SELECT product.*
							FROM product 
							WHERE product.id=". $id;
			}
			//print_r($sql);
			$new_array = null;
			$result = mysqli_query($this->mysqli,$sql);
			while( $row = mysqli_fetch_assoc( $result)){
				$resultdetail = $this->getCategorieByProduct($row['id']);
				$row['categories'] = $resultdetail;
				$new_array[] = $row;
			}
			
			$this->dbDisConnect();
			return $new_array;
		} catch (Exception $e) {
			die('Erreur : ' . $e->getMessage());
			//throw new Exception("Erreur Mysql productGet ". $e->getMessage());
			return "errrrrrrooooOOor";
		}
	}
	
	
	public function productModify($value){
		//print_r($value);exit();
		$this->dbConnect();
		$this->begin();
		
		$sql = "UPDATE  .`product` SET
				`label`='". addslashes($value['label']) ."',
				`reference`='". addslashes($value['ref']) ."',
				`titreaccroche`='". addslashes($value['titreaccroche']) ."',
				`accroche`='". addslashes($value['accroche']) ."',
				`prix`='". addslashes($value['prix']) ."',
				`libprix`='". addslashes($value['libprix']) ."',		
				`description`='". addslashes($value['description']) ."',
				`image1`='". addslashes($value['url1']) ."',
				`image2`='". addslashes($value['url2']) ."',
				`image3`='". addslashes($value['url3']) ."'
				WHERE `id`=". $value['id'] .";";
		$result = mysqli_query($this->mysqli,$sql);
			
		if (!$result) {
			$this->rollback();
			throw new Exception('Erreur Mysql productModify sql = : '.$sql);
		}
		
		$this->categoriesProductModify($_POST['categories'], $value['id']);
		
		$this->commit();
	
		$this->dbDisConnect();
	}
	
	private function categoriesProductDel($id){
	
		$sql = "DELETE FROM  `product_categorie`
				WHERE `id_product`=". $id .";";
		$result = mysqli_query($this->mysqli,$sql);
	
		if (!$result) {
			$this->rollback();
			throw new Exception('Erreur Mysql categoriesProductDel sql = : '.$sql);
		}
	
	}
	
	private function categoriesProductModify($categories,$id){
		
		$this->categoriesProductDel($id);
		
		$sql = "INSERT INTO  `product_categorie`
				(`id_product`, `id_categorie`)
				VALUES "; 
		foreach ($categories as $values){
			$sql .= "(". $id .",". $values ."),";
		}	
		$sql = substr($sql, 0, strlen($sql)-1);
		$sql .= ";";
		$result = mysqli_query($this->mysqli,$sql);

		if (!$result) {
			$this->rollback();
			throw new Exception('Erreur Mysql categoriesProductModify sql = : '.$sql);
		}
		
	}
	
	
	public function productAdd($value){
		//print_r($value);exit();
	
		$this->dbConnect();
		$this->begin();
	
		$sql = "INSERT INTO  .`product`
					(`label`, `reference`, `titreaccroche`, `accroche`, `description`, `image1`, `image2`, `image3`,`libprix`,`prix`)
					VALUES (
					'". addslashes($value['label']) ."',
					'". addslashes($value['ref']) ."',
					'". addslashes($value['titreaccroche']) ."',
					'". addslashes($value['accroche']) ."',
					'". addslashes($value['description']) ."',
					'". addslashes($value['url1']) ."',
					'". addslashes($value['url2']) ."',
					'". addslashes($value['url3']) ."',
					'". addslashes($value['libprix']) ."',
					". $value['prix'] ."
				);";
		$result = mysqli_query($this->mysqli,$sql);
	
		if (!$result) {
			$this->rollback();
			throw new Exception('Erreur Mysql productAdd sql = : '.$sql);
		}
		$id_record = mysqli_insert_id($this->mysqli);
		
		$this->categoriesProductModify($value['categories'], $id_record);
		
		$this->commit();
	
		$this->dbDisConnect();
		return $id_record;
	}
	
	public function productDelete($value){
	
		$this->dbConnect();
		$this->begin();
		
		$this->categoriesProductDel($value);
	
		$sql = "DELETE FROM  .`product`
				WHERE `id`=". $value .";";
		$result = mysqli_query($this->mysqli,$sql);
			
		if (!$result) {
			$this->rollback();
			throw new Exception('Erreur Mysql productDelete sql = : '.$sql);
		}
	
		$this->commit();
	
		$this->dbDisConnect();
	}
}