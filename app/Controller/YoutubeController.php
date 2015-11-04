<?php
/**
 * @Author  : Created by Llyam Garcia.
 * @Nick    : Liightman
 * @Date    : 26/10/2015
 * @Time    : 18:39
 * @File    : YoutubeController.php
 * @Version : 1.0
 */

namespace App\Controller;

use Core\Controller\Controller;
use \App;
use Core\HTML\BootstrapForm;
use \Core\Auth\DBAuth;

class YoutubeController extends AppController {


    public function __construct() {
        parent::__construct();
        $this->loadModel('Youtube');
    }

    /**
     * @var string Clé PRIVE de l'api Google
     */
    private $api_key = "AIzaSyB0sTZpI94R7xBiocvbZM4BcJCiHj7Z-NA";
    /**
     * @var string URL de contacte des services api Google
     */
    private $api_base = 'https://www.googleapis.com/youtube/v3/videos';
    /**
     * @var string Pour les minatures youtube
     */
    private $thumbnail_base = 'https://i.ytimg.com/vi/';

    private function checkSystem($title){
        $titleToCheck = explode(' - ', addslashes($title));
//        die(var_dump($titleToCheck));
        $v = 0;
        for($i = 0; $i < count($titleToCheck); ++$i) {
            $checkIfMatch = $this->Youtube->checkIfMatch($titleToCheck[''.$i.'']);
            if($checkIfMatch){
                $v++;
            }
        }
        if(count($titleToCheck) > 2){
            $limit =  count($titleToCheck)/2;
        } else {
            $limit = 2;
        }

        /**
         * DEBUG
         *
        echo "<pre>";
        var_dump($titleToCheck);
        echo $limit." ".$v;
        echo "</pre>"; */
        if ($v < $limit){
            return true;
        } else {
            return false;
        }


    }
    /**
     * @param $videoID string Identifiant de la vidéo youtube
     * @param $thumbnails string Miniature de la vidéo youtube
     * @param $title string Titre de la vidéo youtube
     * @param $author string Chaine du proprietaire de la vidéo
     * @param $duration string Durée de la vidéo
     * @param $applicant string Personne qui demande la musique
     * @return string mixed Renvoie true ou addError si la requête return false
     */
    protected function addVideo($videoID, $thumbnails, $title, $author, $duration, $applicant){
        $this->forbiddenToBannedVisitor();
        if(!$this->Youtube->checkIfAlreadyAsked($videoID)){
            $result = $this->Youtube->create([
                'videoID' => $videoID,
                'applicant' =>  ucfirst($applicant),
                'thumbnails' => $thumbnails,
                'title' => $title,
                'author' => $author,
                'duration' => $duration,
                'applicant_ip' => $_SERVER['REMOTE_ADDR'],
            ]);
        } else {
            return $addError = "Cette vidéo a déjà été demandée par un autre membre!";
        }
    }

    /**
     * @param null $videoID Identifiant de la vidéo youtube
     * @return array|bool|string Tableau des informations nécessaire à l'enregistrement des infos
     */
    private function getInformations($videoID = null){
        $this->forbiddenToBannedVisitor();
        if($videoID == null) return false;

        $url= $this->api_base.'?id='.$videoID.'&part=snippet,contentDetails&key='.$this->api_key;
        $headers = get_headers($url);

        if(strpos($headers[0], '200 OK')){

            try{

                $json = file_get_contents($url);
                $data = json_decode($json);

                if(!empty($data->items)){
                    $vidInfo = $data->items[0]->snippet;
                    $item = $data->items[0]->contentDetails;

                    if (!empty($vidInfo->thumbnails->standard->url)){
                        $image = $vidInfo->thumbnails->standard->url;
                    } else {
                        $image = $vidInfo->thumbnails->high->url;
                    }
                    $video = array(
                        'title'             => $vidInfo->title,
                        'channelTitle'      => $vidInfo->channelTitle,
                        'thumbnails'        => $image,
                        'duration'          => $item->duration
                    );
                    return $video;
                } else {
                    return $videoInfo = "Cette vidéo youtube n'existe pas! Elle n'a donc pas été ajoutée dans la playlist<br>";
                }

            } catch (\Exception $e) {
                echo "Une erreur est survenue : ". $e;
            }

        } else {
            return $videoInfo = "Une erreur est survenue dans le contact avec l'API de YouTube";
        }
    }

    /**
     * Function qui permet d'afficher la page pour demander une musique
     */
    public function ask(){
        $this->forbiddenToBannedVisitor();
        /**
         * @todo Un système de filtre pour éviter que des musique qui comporte certains mots soit interdits
         */
        $error = false;
        if($_POST):
            if(!empty($_SESSION)){
                $_POST['pseudo'] = $_SESSION['pseudo'];
            }
            if((!empty($_POST['videoID'])) AND (!empty($_POST['pseudo']))){
                $videoInfo = $this->getInformations($_POST['videoID']);
                if(is_array($videoInfo)){
                    $checkSystem = $this->checkSystem($videoInfo['title']);
                    if($checkSystem){
                        $applicant = strip_tags(stripslashes($_POST['pseudo']));
                        $addError = $this->addVideo($_POST['videoID'], $videoInfo['thumbnails'], $videoInfo['title'], $videoInfo['channelTitle'], $videoInfo['duration'], $applicant);
                    } else {
                        $addError = "Cette musique a déjà été demandé par un autre membre!";
                    }
                }
                $error = false;
            } else {
                $error = true;
            }
        endif;
        $form = new BootstrapForm($_POST);
        $this->render('youtube.ask', compact('form', 'addError', 'error', 'videoInfo'));
    }

    /**
     * Function qui permet d'afficher les demande d'un utilisateur
     */
    public function queries(){
        $this->forbiddenToVisitor();
        if($_POST):
            if((!empty($_POST['delete'])) AND (!empty($_POST['idToDelete']))):
                $id = $_POST['idToDelete'];
                $this->Youtube-> queryOwner($id);
                header('Location:?page=youtube.queries');
            endif;
        endif;
        if($this->Youtube->rowAskedMusic() === 0) {
            $erreur = "<div class=\"alert alert-info\" role=\"alert\">Vous n'avez pas encore proposé de musique... Pour en proposer une, remplissez le formulaire ci dessus.</div>";
        } else {
            $askedHits = $this->Youtube->getAskedMusic();
            $erreur = false;
        }
        $this->render('youtube.queries', compact('erreur', 'askedHits'));

    }

    /**
     * Function qui permet de retourner la playlist en cours de lexture
     */
    public function playlist(){
        $playlist = $this->Youtube->getCurrentPlaylist();
        $this->render('youtube.playlist', compact('playlist'));
    }

    public function all(){
        $musicPerPage=25;
        $total= $this->Youtube->getAll();
        $rowPage =ceil($total/$musicPerPage);
        if(isset($_GET['p'])) {
            $pageActuelle=intval($_GET['p']);
            if($pageActuelle>$rowPage) {
                $pageActuelle=$rowPage;
            }
        }
        else {
            $pageActuelle=1;
        }
        $premiereEntree=($pageActuelle-1)*$musicPerPage;
        $list=$this->Youtube->getList($premiereEntree,$musicPerPage);

        $this->render('youtube.all', compact('list', 'rowPage', 'pageActuelle'));
    }

    /**
     * function pour voir la liste des morceaux en attente d'approbation
     */
    public function approval(){
        $this->forbiddenToVisitor();
        if(($_SESSION['grade'] == "Adm") OR ($_SESSION['grade'] == "Mod")){
            $approval = $this->Youtube->getApproval();
            $this->render('youtube.approval', compact('approval'));
        }
    }
    /**
     * function pour voir la liste des morceaux signalés et en attente de modération
     */
    public function reports(){
        $this->forbiddenToVisitor();
        if(($_SESSION['grade'] == "Adm") OR ($_SESSION['grade'] == "Mod")){
            $reports = $this->Youtube->getReports();
            $this->render('youtube.reports', compact('reports'));
        }
    }
    /**
     * Function qui permet de lire les vidéos demandées
     */
    public function dj(){
        $this->forbiddenToVisitor();

        if($_SESSION['pseudo'] == "Liightman"){
            $music = $this->Youtube->chooseRandomMusic();
            $this->Youtube->inListening($music[0]->videoID);
            $this->render('youtube.dj', compact("music"));
        }

    }


}
