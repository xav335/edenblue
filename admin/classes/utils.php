<?
	
	function randomChar($val=12) {
		$characts = 'abcdefghijklmnopqrstuvwxyz';
		$characts .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$characts .= '1234567890';
		$code_aleatoire = '';
	
		for($i=0;$i < $val;$i++)
		{
		$code_aleatoire .= $characts[ rand() % strlen($characts) ];
		}
		return $code_aleatoire;
	}
	// Génération d'un nom de fichier temporaire aléatoire
	function aleatoire($val=64) {
		$rep_temp = "";
		for ($i=0; $i<$val; $i++){
			$choix = rand(1, 3);
			
			if ($choix == 1)
				$rep_temp = $rep_temp . Chr(rand(48, 57));
			else if($choix == 2)
				$rep_temp = $rep_temp . Chr(rand(65, 90));
			else
				$rep_temp = $rep_temp . Chr(rand(97, 122));
		}
		
		return $rep_temp;
	}
	
	function aleatoire_chiffre($val=64) {
		$rep_temp = "";
		for ($i=0; $i<$val; $i++) {
			$rep_temp = $rep_temp . Chr(rand(48, 57));
		}
		
		return $rep_temp;
	}
	
	function traitement_date_insert($date) {
		$tab = explode("/", $date);
		
	   	return $tab[2] . "-" . $tab[0] . "-" . $tab[1];
	}
	
	function inserer_date($date) {
		$tab = explode("/", $date);
		
	   	return $tab[2] . "-" . $tab[1] . "-" . $tab[0];
	}
	
	function traitement_date_affiche($date) {
		if ( $date != "" ) {
			$tab = explode("-", $date);
			
		   	return $tab[2] . "/" . $tab[1] . "/" . $tab[0];
		}
		else
			return "";
	}
	
	function traitement_heure_affiche($heure, $affichage_seconde=false) {
		$tab = explode(":", $heure);
		
		/*$heure = ($tab[0] < 10) ? "0" . $tab[0] : $tab[0];
		$minute = ($tab[1] < 10) ? "0" . $tab[1] : $tab[1];
		$seconde = ($tab[2] < 10) ? "0" . $tab[2] : $tab[2];*/
		$heure = $tab[0];
		$minute = $tab[1];
		$seconde = $tab[2];
		
		$valeur = $heure . ":" . $minute;
		$valeur .= ( $affichage_seconde ) ?  ":" . $seconde : "";
		
	   	return $valeur;
	}
	
	function traitement_datetime_affiche($date) {
		if ( $date != "" ) {
			$tab = explode(" ", $date);
			$tab2 = explode("-", $tab[0]);
			
		   	return $tab2[2] . "/" . $tab2[1] . "/" . $tab2[0] ;
		}
		else
			return "";
	}
	
	function traitement_datetime_affiche_bis($date) {
		if ( $date != "" ) {
			$tab = explode(" ", $date);
			$tab2 = explode("-", $tab[0]);
			
		   	return $tab2[2] . "/" . $tab2[1] . "/" . $tab2[0] . " " . $tab[1];
		}
		else
			return "";
	}
	
	// Mélange un tableau
	function melanger( $tab ) {
		$tab_final = array();
		if ( mysql_num_rows( $tab ) != 0 ) {
			while($data = mysqli_fetch_assoc( $tab )) {
				//echo "--> " . $data["num_produit"] . "<br>";
				$tab_final[] = $data;
			}
			
			shuffle( $tab_final );
		}
		
		return $tab_final;
	}
	
	function enviarMail($para = "",$asunto = "",$mensaje = "",$adj = "") {
		if (!is_array($para)) {
			$para = array($para);
		}
		if (!is_array($adj)) {
			$adj = array($adj);
		}
		$hmm = new htmlMimeMail5();
		//$hmm->setSMTPParams('smtp.msa.orange-business.com',587,'audial.fr',true,'audialsite','audial2133');
		$hmm->setCrlf("\n");
		$hmm->setSubject($asunto);
		$hmm->setText($mensaje);
		$hmm->setFrom("Cityzen Mobility <contact@cityzenmobility.fr>");
		//$hmm->setFrom("UMPB ");
		$hmm->setReturnPath("contact@cityzenmobility.fr");
		//$hmm->setHeader("return-path", "contact@uniport-bordeaux.fr");
		
		//echo "ici : " . count($adj) . "<br>";
		foreach ($adj as $n => $v) {
			if ($v != "") {
				//echo "ajout de " . $v . "<br>";
				$hmm->addAttachment(new fileAttachment($v));
			}
		}
		
		$result = $hmm->send($para,'mail');
		//echo "----- " . date("H:i:s") . " : " . $result . "<br>";
		if (!$result) {
			print_r($hmm->errors);
			die();
		}
	}
	
	function isUploaded( $file=array() ) {
		$mon_fichier = $file["name"];
		
		if ( count( $file ) > 0 ) {
			
			// On a bien tenté d'uploader un fichier
			if ( $file["error"] != 4 ) {
				
				// Fichier correctement uploadé
				//return ( $file["error"] == 0 ) ? true : false;
				if ( $file["error"] == 0 ) {
					//echo "Fichier " . $mon_fichier . " Correctement upload&eacute;<br>";
					return true;
				}
				else {
					//echo "Fichier " . $mon_fichier . " : Erreur " . $file["error"] . "<br>";
					return false;
				}
			}
			else {
				//echo "Fichier " . $mon_fichier . " : Aucun fichier tent&eacute;<br>";
				return true;
			}
		}
		else
			return false;
	}
?>