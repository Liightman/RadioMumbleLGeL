<?php
/**
 * @Author  : Created by Llyam Garcia.
 * @Nick    : Liightman
 * @Date    : 17/07/2015
 * @Time    : 15:56
 * @File    : UsersController.php
 * @Version : 1.0
 */

namespace App\Controller;

use App\Table;
use Core\Auth\DBAuth;
use Core\HTML\BootstrapForm;
use \App;

class UsersController extends AppController {

    public function __construct() {
        parent::__construct();
        $this->loadModel('User');
    }


    /**
     * @param $password string Encrypte de façon non récursive un mot de passe
     * @return string Le mot de passe enrypté
     */
    public static function cryptPass($password) {
        $encryptedPassword = crypt($password,'$2y$07$aqffrpasswordhgfhtkey$');
        return $encryptedPassword;
    }

    /**
     * Envoie un mail pour valider l'inscription
     * @param $pseudo string Pseudo (prenom + nom) du nouvel utilisateur
     * @param $mail string Mail du nouvel utilisateur
     */
    private function sendRegistrationMail($pseudo, $mail, $activation){
        $message_mail = "
		<!doctype html>
			<html lang='fr'>
				<head>
					<style>
						html, body{width:100%;margin: 0;background: #f2f2f2;}
						p{width: 500px;background: white;padding: 10px;margin: 10px auto 5px auto;font-family: Arial;font-size: 16px;}
						table{width: 250px;margin: 10px auto 5px auto;}
						a{text-decoration: none;}
						button{display: block;margin: 10px auto 5px auto;background: #3498db;background-image: -webkit-linear-gradient(top, #3498db, #2980b9);background-image: -moz-linear-gradient(top, #3498db, #2980b9);background-image: -ms-linear-gradient(top, #3498db, #2980b9);background-image: -o-linear-gradient(top, #3498db, #2980b9);background-image: linear-gradient(to bottom, #3498db, #2980b9);-webkit-border-radius: 5;-moz-border-radius: 5; border-radius: 5px;font-family: Arial;color: #ffffff;font-size: 16px;padding: 10px 20px 10px 20px;border: solid #1f628d 1px;text-decoration: none;}
						button:hover{background: #3cb0fd;background-image: -webkit-linear-gradient(top, #3cb0fd, #3498db);background-image: -moz-linear-gradient(top, #3cb0fd, #3498db);background-image: -ms-linear-gradient(top, #3cb0fd, #3498db);background-image: -o-linear-gradient(top, #3cb0fd, #3498db);background-image: linear-gradient(to bottom, #3cb0fd, #3498db);text-decoration: none;}
					</style>
				</head>
				<body>
					<p><strong>Bonjour ".$pseudo."</strong>,
					<br />merci d'avoir rejoint ". App::getInstance()->name .", pour valider votre inscription il vous suffit de confirmer votre adresse email.</p>
					<a href='".$activation."'>
					<button id='jeconfirme'>Confirmer l'adresse email</button></a>
					<p>Le bouton ne fonctionne pas ? Essayez de coller ce lien dans votre navigateur : ".$activation."</p>
				</body>
			</html>";

        $headers_mail  = 'MIME-Version: 1.0'                           ."\r\n";
        $headers_mail .= 'Content-type: text/html; charset=utf-8'      ."\r\n";
        $headers_mail .= 'From: "'.App::getInstance()->name.'" <'.App::getInstance()->masterMail.'>' ."\r\n";

        // Envoi du mail
        mail($mail, 'Inscription sur '.App::getInstance()->name.'', $message_mail, $headers_mail);
    }

    /**
     * Function qui permet de vérifier s'il n'existe pas de doublon au niveau des pseudo/mails en base de donnée
     * @param $type string nom de la colonne
     * @param $data string donnée à vérifier
     * @return bool true|false Retourne true si y a pas de doublon, retourne false s'il y en a un
     * @internal param string $var Le contenue de la variable a vérifiée
     */
    private function noDataDuplicate($type, $data){
        if(empty($this->User->checkData($type, $data))) {
            return true;
        }
        return false;
    }

    /**
     * Function qui permet de vérifier s'il n'existe pas de doublon au niveau des pseudo/mails en base de donnée
     * @param $type string nom de la colonne
     * @param $data string donnée à vérifier
     * @return bool true|false Retourne true si y a pas de doublon, retourne false s'il y en a un
     * @internal param string $var Le contenue de la variable a vérifiée
     */
    private function checkData($type, $data){
        if(empty($this->User->checkData($type, $data))) {
            return false;
        }
        return true;
    }

    /**
     * Enregistre un utilisateur après avoir effectuer une batterie de test
     * @return mixed
     */
    public function register(){
        $this->forbiddenToMember();
        if(!empty($_POST)){
            if ($_POST['pseudo'] AND $_POST['password'] AND $_POST['retypePassword'] ) {
                $pseudo = $_POST['pseudo'];
                $password = $_POST['password'];
                $retypePassword = $_POST['retypePassword'];
                $mail = $_POST['mail'];

                if ($password == $retypePassword) {
                    $passDoesntMatch = false;
                    if ($this->noDataDuplicate('mail', $mail) == true) {
                        if ($this->noDataDuplicate('pseudo', $pseudo) == true) {
                            $errorDuplicatePseudo = false;
                            $timestamp = time();
                            $user_id = uniqid();
                            $result = $this->User->create([
                                'user_id' => $user_id,
                                'pseudo' =>  ucfirst($pseudo),
                                'password' => $this->cryptPass($password),
                                'mail' => $mail,
                                'grade' => "Mem",
                                'valider' => "N",
                                'register_ip' => $_SERVER['REMOTE_ADDR'],
                                'date_inscription' => time(),
                            ]);

                            $activation = "http://website.com/?page=users.validation&var=".$user_id;
                            $this->sendRegistrationMail($pseudo, $mail, $activation);

                            if(!empty($result)){
                                return $this->result('successR');
                            } else {
                                return $this->result('errorR');
                            }
                        } else {
                            /*
                             * Doublon d'adresse e-mail
                             */
                            $errorDuplicatePseudo = true;
                            $errorDuplicateMail = false;
                            $missingFields = false;
                            $errorDuplicatePseudo = false;
                        }
                    } else {
                        /*
                         * Doublon d'adresse e-mail
                         */
                        $errorDuplicateMail = true;
                        $missingFields = false;
                        $errorDuplicatePseudo = false;
                    }
                } else {
                    $passDoesntMatch = true;
                    $errorDuplicateMail = false;
                    $missingFields = false;
                    $errorDuplicatePseudo = false;
                }
            } else {
                $missingFields = true;
                $passDoesntMatch = false;
                $errorDuplicateMail = false;
                $errorDuplicatePseudo = false;
            }
        } else {
            $missingFields = false;
            $passDoesntMatch = false;
            $errorDuplicateMail = false;
            $errorDuplicatePseudo = false;
        }

        $form = new BootstrapForm($_POST);
        $this->render('users.register', compact('form', 'errorDuplicateMail', 'errorDuplicatePseudo', 'passDoesntMatch', 'missingFields'));
    }

    public function result($info){
        $website =  App::getInstance()->name;
        switch($info):

            case 'successR':
                $successRegister = true;
                $errorRegister = false;
                $success = false;
                $error = false;
                break;
            case 'success':
                $successRegister = false;
                $errorRegister = false;
                $success = true;
                $error = false;
                break;
            case 'errorR':
                $successRegister = false;
                $errorRegister = true;
                $success = false;
                $error = false;
                break;
            case 'error':
                $successRegister = false;
                $errorRegister = false;
                $success = false;
                $error = true;
                break;
        endswitch;

        $this->render('users.result', compact('success', 'error','successRegister', 'errorRegister', 'website'));
        die();
    }
    /**
     * Management d'un utilisateur
     */
    public function manage(){
        $this->forbiddenToVisitor();
        $info = $this->User->find($_SESSION['user_id'], true);
        $uploadAvatarError = false;
        if (!empty($_FILES['avatar'])):
            $taille = @getimagesize($_FILES['avatar']['tmp_name']);
            $modif = false;
            $var = uniqid();

            if (($_FILES['avatar']['size']<(1024*1024)) && ($_FILES['avatar']['error'] == 0) && (($taille['mime'] == 'image/jpg') || ($taille['mime'] == 'image/jpeg') || ($taille['mime'] == 'image/png') || ($taille['mime'] == 'image/gif'))) {
                // *** Conversion ****************** //
                if (($taille['mime'] == 'image/jpg') || ($taille['mime'] == 'image/jpeg')) {
                    $source = imageCreateFromJpeg($_FILES['avatar']['tmp_name']);
                    imagePng($source, $_FILES['avatar']['tmp_name']);
                    $modif = true;
                } else if ($taille['mime'] == 'image/gif') {
                    $source = imageCreateFromGif($_FILES['avatar']['tmp_name']);
                    imagePng($source, $_FILES['avatar']['tmp_name']);
                    $modif = true;
                }
                // LES AVATARS SONT DE 150px/150px
                // *** Réduction ******************* //
                if (($taille[0] > 150) && ($taille[1] > 150)) {

                    // préparation
                    $source = imageCreateFromPng($_FILES['avatar']['tmp_name']);
                    $rapport = $taille[1] / $taille[0];

                    // image de destination
                    $destination = imageCreateTrueColor(150, 150);
                    $blanc = imageColorAllocate($destination, 255, 255, 255);
                    imageFill($destination, 0, 0, $blanc);

                    if ($rapport < 1) {
                        $T = round(150 * $rapport);
                        $Y = round((150 - $T) / 2);
                        imageCopyResampled($destination, $source, 0, $Y, 0, 0, 150, $T, $taille[0], $taille[1]);
                    } else {
                        $T = round(150 / $rapport);
                        $X = round((150 - $T) / 2);
                        imageCopyResampled($destination, $source, $X, 0, 0, 0, $T, 150, $taille[0], $taille[1]);
                    }

                    unset($rapport, $blanc);
                    $modif = true;
                } // *** Recadrage ******************* //
                else if (($taille[0] < 150) && ($taille[1] < 150)) {

                    // préparation
                    $source = imageCreateFromPng($_FILES['avatar']['tmp_name']);

                    // image de destination
                    $destination = imageCreateTrueColor(150, 150);
                    $blanc = imageColorAllocate($destination, 255, 255, 255);
                    imageFill($destination, 0, 0, $blanc);

                    $Y = round((150 - $taille[0]) / 2);
                    $X = round((150 - $taille[1]) / 2);
                    imageCopy($destination, $source, $X, $Y, 0, 0, $taille[0], $taille[1]);

                    unset($blanc, $Y, $X);
                    $modif = true;
                }

                if(!is_dir($_SERVER['DOCUMENT_ROOT'] . '/app/user_folder/' . $_SESSION['user_id'] . '/avatar/')) {
                    mkdir($_SERVER['DOCUMENT_ROOT'] . '/app/user_folder/' . $_SESSION['user_id'], 0705);
                    mkdir($_SERVER['DOCUMENT_ROOT'] . '/app/user_folder/' . $_SESSION['user_id'] . '/avatar', 0705);
                }
                if(file_exists($_SERVER['DOCUMENT_ROOT'] . '/app/user_folder/'.$info->avatar)){
                    unlink($_SERVER['DOCUMENT_ROOT'] . '/app/user_folder/'.$info->avatar);
                }
                if ($modif) {
                    imageDestroy($source);
                    imagePng($destination, $_SERVER['DOCUMENT_ROOT'] . '/app/user_folder/' . $_SESSION['user_id'] . '/avatar/avatar'.$var.'.png');
                } else {
                    move_uploaded_file($_FILES['avatar']['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . '/app/user_folder/' . $_SESSION['user_id'] . '/avatar/avatar'.$var.'.png');
                }
                $result = $this->User->update($_SESSION['id'], [
                    'avatar' => $_SESSION['user_id'] . '/avatar/avatar'.$var.'.png',
                ]);
                $_SESSION['avatar'] = 'http://website.com/app/user_folder/'.  $_SESSION['user_id'] . '/avatar/avatar'.$var.'.png';
            } else {
                $uploadAvatarError = true;
            }
        endif;

        $dataMember = $this->User->find($_SESSION['id']);
        $form = new BootstrapForm($_POST);
        $this->render('users.manage', compact('form', 'uploadAvatarError', 'dataMember', 'info'));
    }
    /**
     * Connecte un utilisateur
     */
    public function login(){
        $this->forbiddenToMember();
        $errors = false;
        if(!empty($_POST)){
            $auth = new DBAuth(App::getInstance()->getDb());
            if($auth->login($_POST['username'], $_POST['password'])){
                header('Location:/');
            } else {
                $errors = true;
            }
        }
        $form = new BootstrapForm($_POST);
        $this->render('users.login', compact('form', 'errors'));
    }
    /**
     * Function pour connecter un utilisateur sans passer par la page de connexion (connexion via la box)
     */
    public function loginInstant(){
        $this->forbiddenToMember();
        if(!empty($_POST)){
            $auth = new DBAuth(App::getInstance()->getDb());
            if($auth->login($_POST['pseudo'], $_POST['password'])){
                header('Location:/');
            } else {
                $errors = true;
                $form = new BootstrapForm($_POST);
                $this->render('users.login', compact('form', 'errors'));
            }
        }
    }
    /**
     * Deconnecte un utilisateur
     */
    public function logout(){
        session_unset();
        session_destroy();

        $this->forbiddenToVisitor(); //s'occupe de rediriger l'utilisateur déconnecter vers l'accueil
    }
}
