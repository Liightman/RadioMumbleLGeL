<?php
/**
 * @Author  : Created by Llyam Garcia.
 * @Nick    : Liightman
 * @Date    : 02/08/2015
 * @Time    : 21:54
 * @File    : JQuery.php
 * @Version : 2.5

ini_set('display_errors',1);
error_reporting(E_ALL);
/**
 * On vérifie que quelques chose est envoyé.
 */
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

    function getID($pseudo) {
        global $bdd;
        $requete = $bdd->query('SELECT * FROM users WHERE pseudo="'.$pseudo.'"');
        $resultat = $requete->fetch(PDO::FETCH_ASSOC);
        $IDperPseudo = $resultat['id'];
        return $IDperPseudo;

    }

    function updateInListening($videoID){
        global $bdd;
        $update = $bdd->exec('UPDATE youtube SET inListening="Y" WHERE videoID = "'.$videoID.'"');
    }

    function getReports(){
        global $bdd;
        $requete = $bdd->query('SELECT * FROM youtube WHERE reported="Y" AND deleted="N"');
        $row = $requete->rowCount();
        if($row != 0){
            return "<span style='color:red'><b>(<span class='notifsReports'>".$row."</span>)</b></span>";
        } else {
            return false;
        }

    }

    function getApproval(){
        global $bdd;
        $requete = $bdd->query('SELECT * FROM youtube WHERE accepted="N" AND deleted="N"');
        $row = $requete->rowCount();
        if($row != 0){
            return "<span class='notifsApproval' style='color:red'><b>(<span class='notifsApproval'>".$row."</span>)</b></span>";
        } else {
            return false;
        }
    }

    function getAllNotifs(){
        global $bdd;
        $requeteReports = $bdd->query('SELECT * FROM youtube WHERE reported="Y" AND deleted="N"');
        $rowReports = $requeteReports->rowCount();
        $requeteApproval = $bdd->query('SELECT * FROM youtube WHERE accepted="N" AND deleted="N"');
        $rowApprovals = $requeteApproval->rowCount();

        $total = $rowApprovals+$rowReports;

        if ($total != 0){
            return "<span class='allNotifs' style='color:red'><b>(<span class='allNotifs'>".$total."</span> notificitations)</b></span>";
        } else {
            return false;
        }
    }