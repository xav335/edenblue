<?php
require_once ("StorageManager.php");

class Contact extends StorageManager
{

    public function __construct()
    {}

    public function contactGet($id, $offset, $count)
    {
        $this->dbConnect();
        if (! isset($id)) {
            if (isset($offset) && isset($count)) {
                $sql = "SELECT * FROM `contact` ORDER BY `name` ASC LIMIT " . $offset . "," . $count . ";";
            } else {
                $sql = "SELECT * FROM `contact` ORDER BY `name`;";
            }
        } else {
            $sql = "SELECT * FROM `contact` WHERE id=" . $id;
        }
        // print_r($sql);
        $new_array = null;
        $result = mysqli_query($this->mysqli, $sql);
        if (! $result) {
            throw new Exception('Erreur Mysql contactGet sql = : ' . $sql);
        }
        while (($row = mysqli_fetch_assoc($result)) != false) {
            // address recovery
            if (! empty($row['id_facturation'])) {
                $row['facturation'] = $this->adresseGet($row['id_facturation']);
            }
            if (! empty($row['id_livraison'])) {
                $row['livraison'] = $this->adresseGet($row['id_livraison']);
            }
            $new_array[] = $row;
        }
        // print_r($new_array);exit;
        
        $this->dbDisConnect();
        return $new_array;
    }
    
    public function contactGetByEmail($email)
    {
        $this->dbConnect();
        $sql = "SELECT * FROM `contact` WHERE email='" . $email ."';";
        // print_r($sql);
        $new_array = null;
        $result = mysqli_query($this->mysqli, $sql);
        if (! $result) {
            throw new Exception('Erreur Mysql contactGetByEmail sql = : ' . $sql);
        }
        while (($row = mysqli_fetch_assoc($result)) != false) {
            $new_array[] = $row;
        }
         //print_r($new_array);exit;
    
        $this->dbDisConnect();
        return $new_array;
    }

    public function contactGetByIdent($value)
    {
        $this->dbConnect();
        $sql = "SELECT * FROM `contact` WHERE email='" . $this->mysqli->real_escape_string($value['email']) . "' AND password='" . $this->mysqli->real_escape_string($value['password']) . "';";
        // print_r($sql);
        $new_array = null;
        $result = mysqli_query($this->mysqli, $sql);
        if (! $result) {
            throw new Exception('Erreur Mysql contactGetByIdent sql = : ' . $sql);
        }
        while (($row = mysqli_fetch_assoc($result)) != false) {
            // address recovery
            if (! empty($row['id_facturation'])) {
                $row['facturation'] = $this->adresseGet($row['id_facturation']);
            }
            if (! empty($row['id_livraison'])) {
                $row['livraison'] = $this->adresseGet($row['id_livraison']);
            }
            $new_array[] = $row;
        }
        // print_r($new_array);exit;
        
        $this->dbDisConnect();
        return $new_array;
    }

    public function contactGetPassByEmail($email)
    {
        $this->dbConnect();
        $sql = "SELECT password FROM `contact` WHERE email = '" . $email . "';";
        //print_r($sql);exit;
        $new_array = null;
        $result = mysqli_query($this->mysqli, $sql);
        if (! $result) {
            throw new Exception('Erreur Mysql contactGetPassByEmail sql = : ' . $sql);
        }
        while (($row = mysqli_fetch_assoc($result)) != false) {
            $new_array[] = $row;
        }
        
        $this->dbDisConnect();
        return $new_array;
    }
    
    
    public function contactAddresseGet($id_adresse)
    {
        $this->dbConnect();
        $new_array = $this->adresseGet($id_adresse);
        //print_r($new_array);exit;
    
        $this->dbDisConnect();
        return $new_array;
    }

    protected function adresseGet($id)
    {
        $sql = "SELECT * FROM `adresse` WHERE id_adresse = " . $id . ";";
        // print_r($sql);
        $new_array = null;
        $result = mysqli_query($this->mysqli, $sql);
        if (! $result) {
            throw new Exception('Erreur Mysql adresseGet sql = : ' . $sql);
        }
        while (($row = mysqli_fetch_assoc($result)) != false) {
            $new_array[] = $row;
        }
        
        return $new_array;
    }

    public function contactGetForNewsletter()
    {
        $this->dbConnect();
        try {
            $sql = "SELECT DISTINCT email FROM contact WHERE newsletter=1;";
            // print_r($sql);exit();
            $new_array = null;
            $result = mysqli_query($this->mysqli, $sql);
            while (($row = mysqli_fetch_assoc($result)) != false) {
                $new_array[] = $row;
            }
            $this->dbDisConnect();
            return $new_array;
        } catch (Exception $e) {
            throw new Exception("Erreur Mysql contactGet " . $e->getMessage());
            return "errrrrrrooooOOor";
        }
    }

    public function contactNumberGet()
    {
        $this->dbConnect();
        try {
            $sql = "SELECT count(*) as nb FROM `contact`;";
            // print_r($sql);
            $new_array = null;
            $result = mysqli_query($this->mysqli, $sql);
            while (($row = mysqli_fetch_assoc($result)) != false) {
                $new_array[] = $row;
            }
            $this->dbDisConnect();
            return $new_array[0]['nb'];
        } catch (Exception $e) {
            throw new Exception("Erreur Mysql contactGet " . $e->getMessage());
            return "errrrrrrooooOOor";
        }
    }

    public function contactAdd($value)
    {
        // print_r($value);
        // exit();
        $this->dbConnect();
        $this->begin();
        try {
            (! empty($value['newsletter']) && $value['newsletter'] == 'on') ? $newsletter = 1 : $newsletter = 0;
            (! empty($value['fromgoldbook']) && $value['fromgoldbook'] == 'on') ? $fromgoldbook = 1 : $fromgoldbook = 0;
            (! empty($value['fromcontact']) && $value['fromcontact'] == 'on') ? $fromcontact = 1 : $fromcontact = 0;
            
            $sql = "INSERT INTO  .`contact`
						(`name`, `email`, `firstname`,`password`,`newsletter`,`fromgoldbook`,`fromcontact`)
						VALUES (
						'" . addslashes($value['name']) . "',
						'" . addslashes($value['email']) . "',
						'" . addslashes($value['firstname']) . "',
						'" . randomChar(5) . "',
						" . $newsletter . ",
						" . $fromgoldbook . ",
						" . $fromcontact . "
					);";
            // error_log(date("Y-m-d H:i:s") ." : ".$sql."\n", 3, "../log/spy.log");
            $result = mysqli_query($this->mysqli, $sql);
            
            if (! $result) {
                throw new Exception($sql);
            }
            $id_record = mysqli_insert_id($this->mysqli);
            $this->commit();
        } catch (Exception $e) {
            $this->rollback();
            throw new Exception("Erreur Mysql contactAdd " . $e->getMessage());
            return "errrrrrrooooOOor";
        }
        $this->dbDisConnect();
        return $id_record;
    }

    public function contactModify($value)
    {
        // print_r($value);
        // exit();
        $this->dbConnect();
        $this->begin();
        try {
            (! empty($value['newsletter']) && $value['newsletter'] == 'on') ? $newsletter = 1 : $newsletter = 0;
            (! empty($value['fromgoldbook']) && $value['fromgoldbook'] == 'on') ? $fromgoldbook = 1 : $fromgoldbook = 0;
            (! empty($value['fromcontact']) && $value['fromcontact'] == 'on') ? $fromcontact = 1 : $fromcontact = 0;
            
            $sql = "UPDATE  .`contact` SET
					`name`='" . addslashes($value['name']) . "',
					`email`='" . addslashes($value['email']) . "',
					`firstname`='" . addslashes($value['firstname']) . "',
					`newsletter`=" . $newsletter . ",
					`fromgoldbook`=" . $fromgoldbook . ",
					`fromcontact`=" . $fromcontact . "
					WHERE `id`=" . $value['id'] . ";";
            $result = mysqli_query($this->mysqli, $sql);
            
            if (! $result) {
                throw new Exception($sql);
            }
            
            $this->commit();
        } catch (Exception $e) {
            $this->rollback();
            throw new Exception("Erreur Mysql " . $e->getMessage());
            return "errrrrrrooooOOor";
        }
        
        $this->dbDisConnect();
    }

    public function contactUnsubscribeNewsletter($email, $message)
    {
        // print_r($value);
        // exit();
        $this->dbConnect();
        $this->begin();
        try {
            $sql = "UPDATE  contact SET
					`newsletter`= 0,
					`message`='" . addslashes($message) . "'
					WHERE `email`='" . $email . "';";
            $result = mysqli_query($this->mysqli, $sql);
            
            if (! $result) {
                throw new Exception($sql);
            }
            
            $this->commit();
        } catch (Exception $e) {
            $this->rollback();
            throw new Exception("Erreur Mysql " . $e->getMessage());
            return "errrrrrrooooOOor";
        }
        
        $this->dbDisConnect();
    }

    public function contactDelete($value)
    {
        // print_r($value);
        // exit();
        $this->dbConnect();
        $this->begin();
        try {
            $sql = "DELETE FROM  .`contact` WHERE `id`=" . $value . ";";
            $result = mysqli_query($this->mysqli, $sql);
            
            if (! $result) {
                throw new Exception($sql);
            }
            
            $this->commit();
        } catch (Exception $e) {
            $this->rollback();
            throw new Exception("Erreur Mysql " . $e->getMessage());
            return "errrrrrrooooOOor";
        }
        
        $this->dbDisConnect();
    }

    public function contactExportCSV()
    {
        // print_r($value);
        // exit();
        $date = date("Ymd-H:i:s");
        $value = $_SERVER["DOCUMENT_ROOT"] . "/admin/FileUpload/server/php/files/export-" . $date . ".csv";
        $this->dbConnect();
        $this->begin();
        try {
            // Ne pas oublier d'ajouter les prilèges FILE sur le users de la DB
            $sql = "SELECT firstname,name,email,newsletter INTO OUTFILE '" . $value . "'
				FIELDS
					TERMINATED BY ';'
					ENCLOSED BY '\\\"'
					ESCAPED BY '\\\\'
				LINES
					STARTING BY ''
					TERMINATED BY '\\r'
				FROM contact;";
            echo $sql;
            
            $result = mysqli_query($this->mysqli, $sql) or die(mysql_error());
            if (! $result) {
                throw new Exception($sql);
            }
            $this->commit();
            return $result;
        } catch (Exception $e) {
            $this->rollback();
            throw new Exception("Erreur Mysql contactImportCSV" . $e->getMessage());
            return "errrrrrrooooOOor";
        }
        
        $this->dbDisConnect();
    }

    public function contactImportCSV($value)
    {
        // print_r($value);
        // exit();
        $this->dbConnect();
        $this->begin();
        try {
            $this->contactDeleteALL();
            // Ne pas oublier d'ajouter les prilèges FILE sur le users de la DB
            $sql = "LOAD DATA INFILE '" . $value . "'
	          INTO TABLE contact
			FIELDS
				TERMINATED BY ';'
				ENCLOSED BY '\\\"'
				ESCAPED BY '\\\\'
			LINES
				STARTING BY ''
				TERMINATED BY '\\r'
				(`firstname`,`name`,`email`,`newsletter`) ;";
            // echo $sql;
            
            $result = mysqli_query($this->mysqli, $sql) or die(mysql_error());
            if (! $result) {
                throw new Exception($sql);
            }
            $this->commit();
            return $result;
        } catch (Exception $e) {
            $this->rollback();
            throw new Exception("Erreur Mysql contactImportCSV" . $e->getMessage());
            return "errrrrrrooooOOor";
        }
        
        $this->dbDisConnect();
    }

    protected function contactDeleteALL()
    {
        try {
            $sql = "DELETE FROM  .`contact` WHERE fromcontact=0 AND fromgoldbook=0 ;";
            $result = mysqli_query($this->mysqli, $sql);
            
            if (! $result) {
                throw new Exception($sql);
            }
        } catch (Exception $e) {
            throw new Exception("Erreur Mysql " . $e->getMessage());
            return "errrrrrrooooOOor";
        }
    }

    public function contactAdressesAdd($value)
    {
        // print_r($value);exit();
        $this->dbConnect();
        $this->begin();
        try {
            // Vérification email existe déjà
            
            $nom = $value['nom'];
            $prenom = $value['prenom'];
            $email = $value['email'];
            $tel = $value['tel'];
            
            // Création adresse de facturation
            $value['livraison'] = 0;
            $id_facturation = $this->adresseAdd($value);
            
            // Création adresse de livraison
            $value['nom'] = $value['nomliv'];
            $value['prenom'] = $value['prenomliv'];
            $value['adresse'] = $value['adresseliv'];
            $value['cp'] = $value['cpliv'];
            $value['tel'] = $value['telliv'];
            $value['ville'] = $value['villeliv'];
            $value['email'] = $value['emailliv'];
            $value['livraison'] = 1;
            
            $id_livraison = $this->adresseAdd($value);
            
            // Création du contact
            $value['nom'] = $nom;
            $value['prenom'] = $prenom;
            $value['email'] = $email;
            $value['tel'] = $tel;
            $value['message'] = '';
            $value['id_facturation'] = $id_facturation;
            $value['id_livraison'] = $id_livraison;
            
            $id_contact = $this->contactAddressAdd($value);
        } catch (Exception $e) {
            $this->rollback();
            throw new Exception("Erreur Mysql contactAdressesAdd " . $e->getMessage());
            return "errrrrrrooooOOor";
        }
        
        $this->commit();
        $this->dbDisConnect();
        return $id_contact;
    }

    public function contactAdressesModif($value)
    {
        ///print_r($value);exit();
        $this->dbConnect();
        $this->begin();
        try {
            
            $nom = $value['nom'];
            $prenom = $value['prenom'];
            $email = $value['email'];
            $tel = $value['tel'];
            
            // Création adresse de facturation
            $value['livraison'] = 0;
            $id_facturation = $this->adresseAdd($value);
            
            // Création adresse de livraison
            $value['nom'] = $value['nomliv'];
            $value['prenom'] = $value['prenomliv'];
            $value['adresse'] = $value['adresseliv'];
            $value['cp'] = $value['cpliv'];
            $value['tel'] = $value['telliv'];
            $value['ville'] = $value['villeliv'];
            $value['email'] = $value['emailliv'];
            $value['livraison'] = 1;
            
            $id_livraison = $this->adresseAdd($value);
            
            // Création du contact
            $value['nom'] = $nom;
            $value['prenom'] = $prenom;
            $value['email'] = $email;
            $value['tel'] = $tel;
            $value['message'] = '';
            $value['id_facturation'] = $id_facturation;
            $value['id_livraison'] = $id_livraison;
            
            $id_contact = $this->contactAddressModif($value);
        } catch (Exception $e) {
            $this->rollback();
            throw new Exception("Erreur Mysql contactAdressesAdd " . $e->getMessage());
            return "errrrrrrooooOOor";
        }
        
        $this->commit();
        $this->dbDisConnect();
    }

    protected function adresseAdd($value)
    {
        $sql = "INSERT INTO  `adresse`
					(`nom`,`prenom`, `email`,`tel`,`adresse`,`cp`,`ville`,`message`,livraison)
					VALUES (
					'" . addslashes($value['nom']) . "',
					'" . addslashes($value['prenom']) . "',
					'" . addslashes($value['email']) . "',
					'" . addslashes($value['tel']) . "',
					'" . addslashes($value['adresse']) . "',
					'" . addslashes($value['cp']) . "',
					'" . addslashes($value['ville']) . "',
					'" . addslashes($value['message']) . "',
					" . $value['livraison'] . "
				);";
        //print_r($sql);exit;
        $result = mysqli_query($this->mysqli, $sql);
        
        if (! $result) {
            $this->rollback();
            throw new Exception('Erreur Mysql adresseAdd sql = : ' . $sql);
        }
        $id_record = mysqli_insert_id($this->mysqli);
        
        return $id_record;
    }

    protected function contactAddressAdd($value)
    {
        (! empty($value['newsletter']) && $value['newsletter'] == 'on') ? $newsletter = 1 : $newsletter = 0;
        (! empty($value['fromgoldbook']) && $value['fromgoldbook'] == 'on') ? $fromgoldbook = 1 : $fromgoldbook = 0;
        (! empty($value['fromcontact']) && $value['fromcontact'] == 'on') ? $fromcontact = 1 : $fromcontact = 0;
        
        $sql = "INSERT INTO  .`contact`
					(`name`, `email`,`password`,`firstname`,`tel`,`newsletter`,`fromgoldbook`,`fromcontact`,`id_facturation`,`id_livraison`)
					VALUES (
					'" . addslashes($value['nom']) . "',
					'" . addslashes($value['email']) . "',
					'" . addslashes($value['password']) . "',
					'" . addslashes($value['prenom']) . "',
					'" . addslashes($value['tel']) . "',
					" . $newsletter . ",
					" . $fromgoldbook . ",
					" . $fromcontact . ",
					" . $value['id_facturation'] . ",
					" . $value['id_livraison'] . "
				);";
        $result = mysqli_query($this->mysqli, $sql);
        
        if (! $result) {
            throw new Exception($sql);
        }
        $id_record = mysqli_insert_id($this->mysqli);
        
        return $id_record;
    }

    protected function contactAddressModif($value)
    {
        (! empty($value['newsletter']) && $value['newsletter'] == 'on') ? $newsletter = 1 : $newsletter = 0;
        
        $sql = "UPDATE  `contact` SET
					`name`='" . addslashes($value['nom']) . "',
					`email`='" . addslashes($value['email']) . "',
					`firstname`='" . addslashes($value['prenom']) . "',
					`tel`='" . addslashes($value['tel']) . "',    
					`newsletter`=" . $newsletter . ",
					`id_facturation`=" . $value['id_facturation'] . ",
					`id_livraison`=" . $value['id_livraison'] . " 
					 WHERE `id`=" . $value['id_contact'] . ";";
        $result = mysqli_query($this->mysqli, $sql);
        
        if (! $result) {
            throw new Exception($sql);
        }
        
    }
}