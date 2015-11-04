<?php
/**
 * @Author  : Created by Llyam Garcia.
 * @Nick    : Liightman
 * @Date    : 02/08/2015
 * @Time    : 21:54
 * @File    : JQuery.php
 * @Version : 2.5
 */
session_start();
ini_set('display_errors',1);
error_reporting(E_ALL);
/**
 * On vérifie que quelques chose est envoyé.
 */
if($_POST):

    /**
     * Connexion à la base de donnée (PROVISOIRE, LE TEMPS QUE JE TROUVE UNE SOLUTION PLUS PROPRE)
     */
    try {
        $dbname = '';
        $user = '';
        $server = '';
        $pass = '';
        $bdd = new PDO ('mysql:host=' . $server . ';dbname=' .$dbname, $user, $pass, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));

    }
    catch (PDOException $e) {
        die();
    }

    /**
     * Functions
     */

        /*
         * FUNCTION CONCERNANT LA PARTIE UTILISATEUR
         */
            /**
             * Function pour afficher un profil
             */
            if (($_POST['action'] == "charger_profil") AND (isset($_POST['joueur']))) { // Voir le profil d'un joueur

                $player = $_POST['joueur'];
                $IDperPseudo = getID($player);
                $requete = $bdd->query('SELECT pseudo, sexe, grade, avatar, points FROM users WHERE pseudo ="'.$player.'" '); //requete
                $resultat = $requete->fetch(PDO::FETCH_ASSOC);

                $membres = $bdd->query('SELECT * FROM users WHERE id ="'.$IDperPseudo.'" ');
                $result = $membres->fetch(PDO::FETCH_ASSOC);

                /**
                 * On récupere les exploits/trophés
                 */
                $trophies = $bdd->query('SELECT * FROM users_exploits WHERE user_id ="'.$result['user_id'].'" ORDER BY actual DESC ');
                $exploit = $trophies->fetchAll();

                if (empty($resultat)){
                    $return["existing_account"] = 'not_exist';
                    $resultat['result'] ="none because player doesn't exit";
                }
                else {

                    if (empty($_SESSION)) {
                        $return['profil'] = "not_logged";
                    }

                    if ($resultat['grade'] == "Ban"){
                        $return['existing_account']= "banned";
                    }else{
                        $return["existing_account"] = 'yes';
                    }

                    if ($return["existing_account"] == 'yes') {
                        if (!empty($_SESSION) AND($_SESSION["pseudo"] == $player)){
                            $return["profil"] = 'mine';
                        }
                    }
                    if($result['avatar']== "/app/user_folder/default/avatar/default.png"){
                        $return['img'] = '<img class="avatar-home avatar-sm round-corner" src="http://stoned-radio.fr/app/user_folder/default/avatar/default.png">';
                    } else {
                        $return['img'] = '<img class="avatar-home avatar-sm round-corner" src="http://stoned-radio.fr/app/user_folder/'.$result['avatar'].'">';
                    }

                    /**
                     * Affichage du grade
                     */
                    $rang = $resultat['grade'];
                    $sexe = $resultat['sexe'];

                    switch($rang) {
                        case "Ban":
                            $return['role'] = "<span style='color: red'>Compte banni définitivement</span>";
                            break;
                        case "Mem":
                            if ($sexe == "H") {//homme
                                $return['role'] = "<span style='color: blue'>Auditeur</span>";
                            } elseif ($sexe == "F") {//homme
                                $return['role'] = "<span style='color: rgb(255, 0, 184);'>Auditrice</span>";
                            } else {
                                $return['role'] = "<span style='color: rgb(0, 0, 0);'>Auditeur</span>";
                            }
                            break;
                        case "VIP":
                            if ($sexe == "H") {//homme
                                $return['role'] = "<span style='color: orange'>VIP <b>?</b></span>";
                            } else {
                                $return['role'] = "<span style='color: orange'>VIP <b>?</b></span>";
                            }
                            break;
                        case "Mod":
                            $return['role'] = "<span style='color: #00ff00'>Modérateur</span>";
                            break;
                        case "Adm":
                            $return['role'] = "<span style='color: red'>Administrateur / Fondateur</span>";
                            break;
                        case "Anim":
                            $return['role'] = "<span style='color: #ffffff'>Animateur</span>";
                            break;
                        case "Dev":
                            $return['role'] = "<span style='color: #c41500'>Développeur</span>";
                            break;
                        default:
                            $return['role'] = "<span style='color: black'>Vagabond</span>";
                        break;

                    }

                    /**
                     * Affichage des points
                     */
                     $return['formatedPoints'] = number_format($resultat['points'], 0, '', ' ');

                    /**
                     * Affichage des exploits/trophés
                     */
                    $return["trophies"] = "";
                    foreach($exploit as $xploit){
                        /**
                         * On récupère les informations concernant un trophés
                         */
                        $trophies = $bdd->query('SELECT * FROM trophies WHERE type ="'.$xploit['type'].'" ');
                        $trophy = $trophies->fetch();
                        $prepareNextLvl = $xploit['lvl']+1;

                        if ($xploit['lvl'] == "Infini") {
                            $return["trophies"] .= "<div style=\"position: relative;\" class=\"trophies\" onmouseover=\"showTrophies('<b>".$trophy['TitreLvl1']."</b><br /><i>".$trophy['desc']."</i>', 10);\" onmouseout=\"hideTrophies();\">";
                            $return["trophies"] .= ' <div class="'.$trophy['css'].'" style="position: absolute;"></div>
                            <div class="oneLevelAchievementNumber">&#x221e;</div>
                        </div>';
                        } else {
                            $return["trophies"] .= '<div style="position: relative;" class="trophies" onmouseover="showTrophies(\'Niveau '.$xploit['lvl'].' : <b>'.$trophy['TitreLvl'.$xploit['lvl']].'</b><br /><i><b>'.$xploit['actual'].'</b> '.$trophy['desc'].'</i><br /><br />(Prochain niveau à '.$trophy['requireLvl'.$prepareNextLvl].')<br />\', 10);" onmouseout="hideTrophies();">
                            <div class="'.$trophy['css'].'" style="position: absolute;"></div><sup class="achievementLvl">'.$xploit['lvl'].'</sup>
                            <div class="achievementNumber">'.$xploit['actual'].'</div>
                        </div>';
                        }
                    }
                    /**
                     * Affichage des actions de modérations
                     */
                    if (!empty($_SESSION)) {
                        if(($_SESSION['grade'] == "Mod") OR ($_SESSION['grade'] == "Adm") OR ($_SESSION['grade'] == "Dev")) {
                            $return['staff'] = "yes";

                            if ($_SESSION['grade'] == "Mod") // Action de modo
                            {
                                $return['available_choices'] = array( 'Antécédents', 'Avertir', 'Double-Compte',  'Bannir');
                                $return['name_available_choices'] = array( 'past_facts', '', 'get_same_ip_accounts',  'display_ban');
                            }
                            elseif (($_SESSION['grade'] =="Adm") OR ($_SESSION['grade'] =="Dev")) { //action d'admin
                                $return['available_choices'] = array('Antécédents','Double-Compte', 'Avertir',  'Bannir', 'Surveiller',  'Modifier');
                                $return['name_available_choices'] = array('past_facts','get_same_ip_accounts', '', 'display_ban', 'display_stalk', 'edit');
                            }
                        }
                    }
                }



                $final = array_merge($return, $resultat) ;
                echo json_encode($final);

            }

            if($_POST['action'] == "inListening"){
                $last = $bdd->query('SELECT * FROM youtube WHERE inListening = "Y" ORDER BY videoID ASC LIMIT 0,1');
                $fetch = $last->fetch();
                $row = $last->rowCount();
                if ($row == "1"){
                    $return['thumbnails'] = $fetch['thumbnails'];
                    $return['title'] =  $fetch['title'];
                    $return['author'] =  $fetch['author'];
                    $return['duration'] =  $fetch['duration'];
                    $return['applicant'] =  $fetch['applicant'];
                    $return['videoID'] =  $fetch['videoID'];
                    $return['pour'] =  $fetch['VotePour'];
                    $return['contre'] =  $fetch['VoteContre'];
                    if($fetch['forNight'] == "Y"){
                        $return['forNight'] = "<h2 style='color:#2cb0ff'>Playlist de nuit</h2>";
                    } else {
                        $return['forNight'] =  "";
                    }
                    if($_SESSION){
                        if(($_SESSION['grade'] == "Adm") OR ($_SESSION['grade'] == "Mod")) {
                            $return['staff'] =  "yes";
                            $return['applicant_ip'] =  $fetch['applicant_ip'];
                        } else {
                            $return['staff'] =  "no";
                        }
                    } else {
                        $return['staff'] =  "no";
                    }
                } else {
                    $return['thumbnails'] = "http://www.malawidemocrat.com/wp-content/uploads/2012/09/Off-Air.jpg";
                    $return['title'] =  "<span style='color:red'>Radio OFF AIR</span>";
                    $return['author'] =  " ";
                    $return['duration'] =  "PT10M10S";
                    $return['applicant'] =  "SYSTEM";
                }


                echo json_encode($return);
            }


            if($_POST['action'] == "musicEard"){
                $update = $bdd->exec('UPDATE youtube SET alreadyEar="Y" WHERE videoID = "'.$_POST['videoID'].'"');
                $update2 = $bdd->exec('UPDATE youtube SET inListening="N" WHERE videoID = "'.$_POST['videoID'].'"');
            }

            if($_POST['action'] == "randomNewSong"){
                if((date('H', time()) == 23) OR (date('H', time()) == 00 ) OR (date('H', time()) == 01 ) OR (date('H', time()) == 02 ) OR (date('H', time()) == 03 ) OR (date('H', time()) == 04 ) OR (date('H', time()) == 05 ) OR (date('H', time()) == 06 ) OR (date('H', time()) == 07 )){
                    $last = $bdd->query('SELECT * FROM youtube WHERE alreadyEar = "N" AND reported = "N" AND deleted = "N" AND forNight="Y" AND accepted="Y" ORDER BY RAND() DESC LIMIT 0,1');
                } else {
                    $last = $bdd->query('SELECT * FROM youtube WHERE alreadyEar = "N" AND reported = "N" AND deleted = "N" AND forNight="N" AND accepted="Y" ORDER BY RAND() DESC LIMIT 0,1');
                }

                $fetch = $last->fetch();
                $row = $last->rowCount();
                if($row == 0) {
                    $update = $bdd->exec('UPDATE youtube SET alreadyEar="N"');
                    if((date('H', time()) == 23) OR (date('H', time()) == 00 ) OR (date('H', time()) == 01 ) OR (date('H', time()) == 02 ) OR (date('H', time()) == 03 ) OR (date('H', time()) == 04 ) OR (date('H', time()) == 05 ) OR (date('H', time()) == 06 ) OR (date('H', time()) == 07 )){
                        $last = $bdd->query('SELECT * FROM youtube WHERE alreadyEar = "N" AND reported = "N" AND deleted = "N" AND forNight="Y" AND accepted="Y" ORDER BY RAND() DESC LIMIT 0,1');
                    } else {
                        $last = $bdd->query('SELECT * FROM youtube WHERE alreadyEar = "N" AND reported = "N" AND deleted = "N" AND accepted="Y" ORDER BY RAND() DESC LIMIT 0,1');
                    }
                    $fetch = $last->fetch();
                }
                $update = $bdd->exec('UPDATE youtube SET inListening="Y" WHERE videoID = "'.$fetch['videoID'].'"');
                $stamp = $fetch['duration'];
                $formated_stamp = str_replace(array("PT","H","M","S"), array("",":",":",""),$stamp);

                $isHour = stripos($stamp, "H");
                if($isHour === false){
                    $time = "0:".$formated_stamp;
                } else {
                    $time = $formated_stamp;
                }

                $parsed = date_parse($time);
                $seconds = $parsed['hour'] * 3600 + $parsed['minute'] * 60 + $parsed['second'];

                $return['duration'] =  $seconds."000";
                $return['videoID'] =   $fetch['videoID'];
                $return['applicant'] =   $fetch['applicant'];
                $return['applicant_ip'] =   $fetch['applicant_ip'];



                echo json_encode($return);
            }

            if($_POST['action'] == "play"){
                $update = $bdd->exec('UPDATE youtube SET inListening="N"');
                $last = $bdd->query('SELECT * FROM youtube WHERE videoID = "'.$_POST['video'].'"');
                $fetch = $last->fetch();
                $update = $bdd->exec('UPDATE youtube SET inListening="Y" WHERE videoID = "'.$fetch['videoID'].'"');

                $stamp = $fetch['duration'];
                $formated_stamp = str_replace(array("PT",'H',"M","S"), array("",":",":",""),$stamp);
                if(strlen($formated_stamp) == 4){
                    $time = "0:".$formated_stamp;
                } else {
                    $time = $formated_stamp;
                }
                $parsed = date_parse($time);
                $seconds = $parsed['hour'] * 3600 + $parsed['minute'] * 60 + $parsed['second'];

                $return['duration'] =  $seconds."000";
                $return['videoID'] =   $fetch['videoID'];
                $return['applicant'] =   $fetch['applicant'];
                $return['applicant_ip'] =   $fetch['applicant_ip'];

                echo json_encode($return);
            }

            if(($_POST['action'] == "report") AND (isset($_POST['videoID'])) AND (isset($_POST['motif']))){
                if($_SESSION){
                    $verif = $bdd->query('SELECT * FROM youtube WHERE videoID = "'.$_POST['videoID'].'"');
                    $row = $verif->rowCount();
                    if($row != 0){
                        if($_POST['motif'] == "Choisissez un motif"){
                            $return['error'] = "no motif";
                        } else {
                            if(($_POST['motif'] == "Doublon") OR ($_POST['motif'] == "Hors-Sujet") OR ($_POST['motif'] == "Style") OR($_POST['motif'] == "Contenu")){
                                $update = $bdd->exec('UPDATE youtube SET reported="Y" WHERE videoID = "'.$_POST['videoID'].'"');
//                            $update = $bdd->exec('UPDATE youtube SET deleted="Y" WHERE videoID = "'.$_POST['videoID'].'"');
                                $return['fait'] = "ok";
                            } else {
                                $return['fait'] = "error";
                            }
                        }
                    } else {
                        $return['error'] = "doesn't exist";
                    }
                } else {
                    $return['error'] = "not_logged";
                }


                echo json_encode($return);
            }
        if(($_POST['action'] == "vote") AND ($_POST['vote']) AND ($_POST['videoID'])):
//            if(!empty($_SESSION)){
                switch($_POST['vote']):
                    case "pour": $voteP = 1; $voteC = 0; break;
                    case "contre": $voteP = 0; $voteC = 1; break;
                    default:  $voteP = 0; $voteC = 0; break;
                endswitch;
                if($_POST['vote'] == "pour"){
                    $lastVote = "POUR";
                } else {
                    $lastVote = "CONTRE";
                }

                $verifIfARVote = $bdd->prepare('SELECT * FROM youtubeVotes WHERE videoID= :videoID AND ip=:ip');
                $exec = $verifIfARVote->execute(array(
                    ":videoID" => $_POST['videoID'],
                    ":ip" => $_SERVER['REMOTE_ADDR']
                ));
                $rowCountARV = $verifIfARVote->rowCount();
                if($rowCountARV == 0) {
                    $updatePour = $bdd->exec('UPDATE youtube SET votePour = votePour+'.$voteP.'  WHERE videoID="'. $_POST['videoID'].'" ');
                    $updateContre = $bdd->exec('UPDATE youtube SET voteContre = voteContre+'.$voteC.'  WHERE videoID="'. $_POST['videoID'].'" ');
                    $updateLast = $bdd->exec('UPDATE youtube SET lastVote = "'.$lastVote.'"  WHERE videoID="'. $_POST['videoID'].'" ');

                    if($_SESSION){
                        $insertUV = $bdd->prepare('INSERT INTO youtubeVotes (videoID, user_id, ip) VALUES (:videoID, :id, :ip) ');
                        $exec = $insertUV->execute(array(
                            ":videoID" => $_POST['videoID'],
                            ":id" => $_SESSION['user_id'],
                            ":ip" => $_SERVER['REMOTE_ADDR']
                        ));
                    } else {
                        $insertUV = $bdd->prepare('INSERT INTO youtubeVotes (videoID, ip) VALUES (:videoID, :ip) ');
                        $exec = $insertUV->execute(array(
                            ":videoID" => $_POST['videoID'],
                            ":ip" => $_SERVER['REMOTE_ADDR']
                        ));
                    }


                    $return['fait'] = "OK";

                    echo json_encode($return);
                    
                } else {
                    $return['fait'] = "NO";
                    $return['because'] = "ALREADY_VOTE";

                    echo json_encode($return);
                }

           /* } else {
                $return['fait'] = "NO";
                $return['because'] = "NOT_LOGGED";

                echo json_encode($return);
            }*/

        endif;
    if (!empty($_SESSION)):
            /**
             * Function pour avoir les formulaires pour changer les informations de son compte
             */
            if(($_POST['action'] == "getFormulaireToChangeProfile") AND ($_POST['askFor'])):
                $return = "";
                if ($_POST['askFor']=="password"){
                    $return .= '<label class="label-edit-profil">Ancien <br>mot de passe :</label>';
                    $return .= '<input type="password" name="OldPass" class="OldPass" /><br><br>';
                    $return .= '<label class="label-edit-profil">Nouveau mot de passe :</label>';
                    $return .= '<input type="password" name="NewPass" class="NewPass" /><br><br>';
                    $return .= '<label class="label-edit-profil">Confirmation (retapez-le) :</label>';
                    $return .= '<input type="password" name="confNewPass" class="confNewPass" />';
                    $return .= '<button onclick="updateInfoProfile(\'password\')" class="btn btn-warning pull-right" style="display:inline-block; padding:1px 12px">Modifier</button>';
                } elseif ($_POST['askFor']=="mail") {
                    $return .= '<label class="label-edit-profil">Ancienne e-mail :</label>';
                    $return .= '<input type="email" name="mail" class="OldMail" /><br><br>';
                    $return .= '<label class="label-edit-profil">Nouvelle e-mail :</label>';
                    $return .= '<input type="email" name="mail" class="NewMail" />';
                    $return .= '<button onclick="updateInfoProfile(\'mail\')" class="btn btn-warning pull-right" style="display:inline-block; padding:1px 12px">Modifier</button>';
                }
                echo json_encode($return);
            endif;
            /**
             * Function pour modifier les informations de son compte
             */
            if(($_POST['action'] == "updateProfile") AND ($_POST['askFor']) AND ($_POST['newInfo'])):
                if ($_POST['askFor'] == "password") {
                    if($_POST['newInfoOption']){
                        if($_POST['oldInfo']){
                            if($_POST['newInfoOption'] == $_POST['newInfo']) {
                                $verif = $bdd->prepare('SELECT * FROM users WHERE password= :pass AND user_id=:id');
                                $exec = $verif->execute(array(
                                    ":pass" => crypt($_POST['oldInfo'],'$2y$07$wqazsxcedfrvbtghynujkiomp$'),
                                    ":id" => $_SESSION['user_id']
                                ));

                                $row = $verif->rowCount();
                                if($row == 0){
                                    $return['erreur'] = "Votre ancien mot de passe est incorrect.";
                                } else {
                                    $update = $bdd->prepare('UPDATE users SET password = :pass  WHERE user_id=:id');
                                    $exec = $update->execute(array(
                                        ":pass" => crypt($_POST['oldInfo'],'$2y$07$wqazsxcedfrvbtghynujkiomp$'),
                                        ":id" => $_SESSION['user_id']
                                    ));
                                    $return['fait'] = "ok";
                                    $return['erreur'] = "none";
                                    }
                                }  else {
                                $return['erreur'] = "Votre nouveau mot de passe est différent de la confirmation.";
                                }
                            } else {
                                $return['erreur'] = "Vous n'avez pas taper votre ancien mot de passe.";
                            }
                        } else {
                            $return['erreur'] = "Vous n'avez pas tapez votre ancien mot de passe.";
                        }
                    echo json_encode($return);
                } elseif ($_POST['askFor'] == "mail") {
                    if ($_POST['oldInfo']) {
                        $verif = $bdd->prepare('SELECT * FROM users WHERE mail= :mail AND user_id=:id');
                        $exec = $verif->execute(array(
                            ":mail" => $_POST['oldInfo'],
                            ":id" => $_SESSION['user_id']
                        ));
                        $row = $verif->rowCount();
                        if($row == 0){
                            $return['erreur'] = "Vous avez spécifiez une mauvaise adresse e-mail.";
                        } else {
                            if (filter_var($_POST['newInfo'], FILTER_VALIDATE_EMAIL)) {
                                $update = $bdd->prepare('UPDATE users SET mail = :mail  WHERE user_id=:id');
                                $exec = $update->execute(array(
                                    ":mail" => $_POST['newInfo'],
                                    ":id" => $_SESSION['user_id']
                                ));
                                $return['fait'] = "ok";
                                $return['erreur'] = "none";
                            } else {
                                $return['erreur'] = "Vous avez spécifiez une adresse e-mail invalide.";
                            }
                        }

                    } else {
                        $return['erreur'] = "Vous n'avez pas tapez votre ancienne adresse e-mail.";
                    }
                    echo json_encode($return);
                }

            endif;

            /**
             * Function pour changer son sexe
             */
             if ($_POST['action'] == "updateSexe"){
                 $requete = $bdd->query('SELECT * FROM users WHERE pseudo="'.$_SESSION['pseudo'].'"');
                 $resultat = $requete->fetch();
                 if ($resultat['sexe'] == NULL){
                     $return['result'] = "haveToChange";
                 } else {
                     $return['result'] = "ok";
                 }
                 echo json_encode($return);
             }
             if (($_POST['action'] == "setSexe") AND ($_POST['sexe'])){
                $exec = $bdd->exec('UPDATE users SET sexe ="'.$_POST['sexe'].'" WHERE pseudo="'.$_SESSION['pseudo'].'"');
             }
            /**
             * Function pour delete son compte
             */
            if (($_POST['action'] == "delete_account") AND (isset($_POST['delPassword']))) {

                $player = $_SESSION['pseudo'];
                $delPassword = $_POST['delPassword'];
                $passCrypt = crypt($delPassword, '$2y$07$wqazsxcedfrvbtghynujkiomp$');

                $requete = $bdd->query('SELECT * FROM users WHERE pseudo="' . $player . '" AND password="' . $passCrypt . '"');
                $resultat = $requete->fetch(PDO::FETCH_ASSOC);
                $rows = $requete->rowCount();

                if ($rows == 1) {
                    $exec = $bdd->exec('UPDATE users SET deleted ="Y" WHERE pseudo="' . $player . '" AND password="' . $passCrypt . '"');
                    $return['fait'] = "ok";
                } else {
                    $return['fait'] = "error";
                }

                echo json_encode($return);
            }
    endif;


/**
 * Fonction de modération
 */
if(!empty($_SESSION)):
    /**
     * Permet d'afficher les doubles-comptes (même IP)
     */
    if(($_SESSION['grade'] == "Dev") OR($_SESSION['grade'] == "Adm") OR($_SESSION['grade'] == "Mod") ){

        if(($_POST['action'] == "delReport") AND (isset($_POST['videoID']))){
            $autoriser=$bdd->exec("UPDATE youtube SET reported = 'N' WHERE videoID = '".$_POST['videoID']."'");
            $return['fait'] = "ok";
            echo json_encode($return);
        }
        if(($_POST['action'] == "acceptApproval") AND (isset($_POST['videoID']))){
            $autoriser=$bdd->exec("UPDATE youtube SET accepted = 'Y' WHERE videoID = '".$_POST['videoID']."'");
            $return['fait'] = "ok";
            echo json_encode($return);
        }

        if(($_POST['action'] == "get_same_ip_accounts") AND (isset($_POST['joueur']))) // vérification de D-C
        {
            $player = $_POST['joueur'];

            $pre_requete = $bdd->query('SELECT * FROM users WHERE pseudo ="'.$_POST['joueur'].'"');
            $pre_resultat = $pre_requete->fetch();
            $ip = $pre_resultat['register_ip'];
            $pseudo = $pre_resultat['pseudo'];
            $requete = $bdd->query('SELECT * FROM users WHERE register_ip = "'.$ip.'" ');
            $resultat= $requete->fetchAll();
            if (count($resultat) < 2)
            {
                $return['same_ip_accounts'] = $player." n'a aucun double compte.";
                $return['fait']= "ok";
            }
            else {
                $return['same_ip_accounts'] = "Liste des doubles comptes : ";
                foreach ($resultat as $var) {

                    $requete2 = $bdd->query('SELECT * FROM users WHERE user_id = "'.$var['user_id'].'" ');
                    $result2 = $requete2->fetch();

                    if ($var['grade'] == "Ban")	{
                        $return['same_ip_accounts'] .= "<font color=\"red\"><b>".$result2['pseudo']." </b> </font>, ";
                    }
                    else {
                        $return['same_ip_accounts'] .= "<b>".$result2['pseudo']."</b>, ";
                    }

                }
                $return['fait']= "ok";
            }
            echo json_encode($return);
        }
    }
    /**
     * Permet d'afficher les antécédents
     */
    if (($_POST['action'] == "past_facts") AND (isset($_POST['joueur']))) { //Antécédents du joueur

        $player = $_POST['joueur'];

        $return['fait'] = "ok";

        $pre_requete = $bdd->query('SELECT * FROM users WHERE pseudo ="'.$player.'"  ');
        $pre_resultat = $pre_requete->fetch(PDO::FETCH_ASSOC);
        $id = $pre_resultat['user_id'];

        $execution = $bdd->query('SELECT * FROM ban WHERE compte_banni= "'.$player.'" ORDER by date DESC');
        $resultat = $execution->fetchAll(PDO::FETCH_ASSOC);

        if(empty($resultat)) {
            $return["past_facts"] = "<h4 style='text-align:center'><i> $player n'a aucun antécédents</i></h4>";
        }
        else {

            $return["past_facts"] ="";
            foreach ($resultat as $afficherBan)
            {
                $p_1 = $afficherBan['duree'] - $afficherBan['date'];
                $pendant = ceil($p_1/60/60);//en heure
                $pendant2 = ceil($p_1/60/60/24);//en jour
                $pendant3 = ceil($p_1/60/60/24/30);//en mois

                $a = strftime("le <b>%d-%m-%Y</b> &agrave; %H:%M", $afficherBan['date']);

                if($pendant<25){
                    $temps= $pendant.' heures';
                }
                elseif($pendant2<30){
                    $temps= $pendant2.' jours';
                }
                else {
                    $temps= $pendant3.' mois';
                }


                $return["past_facts"] .="$temps/ $a /<br />";
            }
            $return["past_facts"] .="FIN";
        }

        $final = array_merge($return, $resultat) ;
        echo json_encode($final);

    }

    /**
     * Permet de bannir un utilisateur
     */

    if (($_POST['action'] == "bannir") AND (isset($_POST['motif'])) AND (isset($_POST['ip'])) AND (isset($_POST['title']))/* AND (isset($_POST['commentaire']))*/ AND(isset($_POST['joueur']))) { //bannir un joueur

        $player = $_POST['joueur'];
        $motif = $_POST['motif'];
/*        $commentaire = $_POST['commentaire'];*/

        switch($motif) {
            case "1":
                $motiff = 'Pseudo';
                $duree =  60*60*24;
                break;
            case "2":
                $motiff = 'Contenu de la musique';
                $duree = 60*60*24*10;
                break;
            case "3":
                $motiff = 'Style de la musique';
                $duree = 60*60*24*2;
                break;
            case "4":
                $motiff = 'Durée du remix';
                $duree = 60*60*3;
                break;
            case "5":
                $motiff = 'Hors-Sujet';
                $duree = 60*60*1;
                break;
            case "6":
                $motiff = 'Contournement de ban';
                $duree = 60*60*24*30*4;
                break;
            case "7":
                $motiff = 'Tentatives de piratage';
                $duree = 60*60*24*30*6;
                break;
            case "-1":
                $motiff = 'BANNIR DEFINITIVEMENT';
                $duree = 60*60*24*30*12*100;
                break;
        }

        $pre_requete = $bdd->query('SELECT * FROM users WHERE pseudo="'.$player.'"  ');
        $pre_resultat = $pre_requete->fetch(PDO::FETCH_ASSOC);
        $row = $pre_requete->rowCount();
        if ($row == 1){
            $user_id = $pre_resultat['user_id'];
        } else {
            $user_id = "NOT_REGISTER";
        }


/*
        if($motiff=="Double Comptes"){
            $requete = $bdd->query('SELECT * FROM users WHERE register_ip = "'.$ip.'" ');
            $resultat= $requete->fetchAll();
                foreach ($resultat as $var) {
                    $banCompte=$bdd->exec("INSERT INTO ban (ip, user_id, motif, duree, date, fini) VALUES ('".$ip."', '".$var['pseudo']."', '".$motiff."', '".$duree."', '".time()."', 'N')");
                }
                $return['fait']= "ok";
        } else {*/
        $dureeFinal = time()+$duree;
        $banCompte=$bdd->exec("INSERT INTO ban (ip, user_id, pseudo, motif, fin, date, fini, musique) VALUES ('".$_POST['ip']."', '".$user_id."', '".$player."', '".$motiff."', '".$dureeFinal."', '".time()."', 'N', '".addslashes($_POST['title'])."')");
        $deleteMusic=$bdd->exec("UPDATE youtube SET deleted = 'Y' WHERE title = '".addslashes($_POST['title'])."'");
        /*}*/

        if ($motiff = 'BANNIR DEFINITIVEMENT') {
//            $update = $bdd->exec("UPDATE users SET grade = 'Ban' WHERE pseudo = '".$player."' ");
        }

        $return ="fait";

        echo json_encode($return);

    }
endif;
endif;