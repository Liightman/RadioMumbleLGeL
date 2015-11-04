<?php
/**
 * @Author  : Created by Llyam Garcia.
 * @Nick    : Liightman
 * @Date    : 17/07/2015
 * @Time    : 15:51
 * @File    : Controller.php
 * @Version : 1.0
 */

namespace Core\Controller;

use App;
use Core\Auth\DBAuth;

class Controller {

    /**
     * @var string Chemin du dossier qui contient les vues
     */
    protected $viewPath;

    /**
     * @var string Chemin du fichier template
     */
    protected $template;

    /**
     * @param $view Rend la vue à afficher
     */
    protected function render($view, $variables = []){
        ob_start();
        extract($variables);
        require ($this->viewPath. str_replace('.', '/', $view). '.php');
        $content = ob_get_clean();
        require($this->viewPath . 'templates/' . $this->template .'.php');
    }

    /**
     * Function pour interdir l'accès à certaines page comme l'administration
     */
    protected function forbidden(){
        header('HTTP/1.0 403 Forbidden');
        die("Accès Interdit");
    }

    /**
     * Function pour dire qu'une page n'existe pas
     */
    protected function notFound(){
        header('HTTP/1.0 404 Not Found');
        die('Page introuvable');
    }

    protected function forbiddenToMember(){
        $app = App::getInstance();
        $auth = new DBAuth($app->getDb());
        if($auth->logged()) {
            header('Location: /');
        }
    }

    protected function forbiddenToVisitor(){
        $app = App::getInstance();
        $auth = new DBAuth($app->getDb());
        if(!$auth->logged()) {
            header('Location: /');
        }
    }

    protected function forbiddenToBannedVisitor(){
        $app = App::getInstance();
        $auth = new DBAuth($app->getDb());
        if($auth->isBanned()){
            $info = $auth->isBanned();
            $this->render('ban.index', compact('info'));
            die();
        }
    }
}