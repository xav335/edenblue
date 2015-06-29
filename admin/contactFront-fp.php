<?php include_once '../inc/inc.config.php'; ?>
<?php
require 'classes/Contact.php';
session_start();

 //print_r($_POST);exit();
// Forms processing
if (! empty($_POST)) {
    
    // traitement des Contact
    if ($_POST['reference'] == 'contactFront') {
        $contact = new Contact();
        if ($_POST['action'] == 'creation') { // ajout adresse
            try {
                $result1 = $contact->contactGetByEmail($_POST['email']);
                //print_r(!empty($result1));exit;
                if(!empty($result1)){
                    header('Location: /adresse.php?action=mdp&error=emailexist');
                    exit;
                }
                
                $result = $contact->contactAdressesAdd($_POST);
                $_SESSION['id_contact'] = $result;
                $contact = null;
                header('Location: /livraison.php');
            } catch (Exception $e) {
                echo 'Erreur contactez votre administrateur <br> :', $e->getMessage(), "\n";
                $contact = null;
                exit();
            }
        } 
        
        if ($_POST['action'] == 'modif') { // ajout adresse
            // TODO: tester si l contact existe déjà et si c'est le cas redirger vers identifiants
            //print_r($_POST);exit;
            try {
            $result = $contact->contactAdressesModif($_POST);
                $contact = null;
                header('Location: /livraison.php');
            } catch (Exception $e) {
            echo 'Erreur contactez votre administrateur <br> :', $e->getMessage(), "\n";
            $contact = null;
                exit();
            }
        }
        
        if ($_POST['action'] == 'ident') { //Identification contact
            // print_r($_POST);exit;
            try {
                $result = $contact->contactGetByIdent($_POST);
                //print_r($result);exit;
               
                $contact = null;
                if (empty($result)){
                   header('Location: /adresse.php?error=noident');
                } else {
                    $_SESSION['id_contact'] = $result[0]['id'];
                    header('Location: /adresse.php');
                }
               
            } catch (Exception $e) {
            echo 'Erreur contactez votre administrateur <br> :', $e->getMessage(), "\n";
            $contact = null;
                exit();
            }
        }
        
        if ($_POST['action'] == 'mdp') { // recup mot de passe
            try {
                $password = $contact->contactGetPassByEmail($_POST['email']);
                $contact = null;
                //print_r($password);exit;
                if (!empty($password[0]['password'])) {
                    $_to = $_POST['email'];
                    $sujet = "$mailNameCustomer - Recupération du compte";
                    $sujet = utf8_decode($sujet);
                    //echo "Envoi du message à " . $_to . "<br>";
        
                    $entete = "From:$mailNameCustomer <$mailCustomer>\n";
                    $entete .= "MIME-version: 1.0\n";
                    $entete .= "Content-type: text/html; charset= iso-8859-1\n";
                    $entete .= "Bcc: ". $mailBcc ."\n";
        
                    $corps = "";
                    $corps .= "Bonjour,<br>";
                    $corps .= "Votre mot de passe est :  ". $password[0]['password']. "<br>";
                    $corps .= "Identifiez-vous sur : http://". $_SERVER['HTTP_HOST'] . "/adresse.php<br>";
                    $corps = utf8_decode( $corps );
                    //echo $corps . "<br>";exit;
                    // Envoi des identifiants par mail
                    mail($_to, $sujet, stripslashes($corps), $entete);
        
                    error_log(date("Y-m-d H:i:s") ." : Password envoyé ! \n", 3, "../log/spy.log");
                    
                    header('Location: /adresse.php?error=mailsent');
                } else {
                    header('Location: /adresse.php?error=nomail');
                }
        
                //print_r($password[0]['password']);exit;
                
            } catch (Exception $e) {
                echo 'Erreur contactez votre administrateur <br> :', $e->getMessage(), "\n";
                $contact = null;
                exit();
            }
        }
    }
    
    
    
} elseif (! empty($_GET)) { // GET GET GET
    if ($_GET['reference'] == 'contactFront') { // supprimer
        $contact = new Contact();
        if ($_GET['action'] == 'delete') {
            try {
                $result = $contact->contactDelete($_GET['id']);
                $contact = null;
                header('Location: /admin/contact-list.php');
            } catch (Exception $e) {
                echo 'Erreur contactez votre administrateur <br> :', $e->getMessage(), "\n";
                $contact = null;
                exit();
            }
        }
    }
} else {
    header('Location: /admin/');
}

?>