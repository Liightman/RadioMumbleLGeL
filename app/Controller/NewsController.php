<?php
/**
 * @Author  : Created by Llyam Garcia.
 * @Nick    : Liightman
 * @Date    : 17/07/2015
 * @Time    : 15:51
 * @File    : NewsController.php
 * @Version : 1.0
 */

namespace App\Controller;

use Core\HTML\bootstrapForm;
use Core\Controller\Controller;

class NewsController extends AppController {

    public function __construct() {
        parent::__construct();
        $this->loadModel('New');
    }
    /**
     * Page d'accueil qui liste les news/articles
     */
    public function index(){
        $news =$this->New->last();
        $this->render('news.index', compact('news'));
    }

    public function categories(){

    }

    /**
     *  Permet de voir un article/une news en particulier
     */
    public function show(){
        $news = $this->New->find($_GET['id']);
        $this->render('news.show', compact('news'));
    }
}
