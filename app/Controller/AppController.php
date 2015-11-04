<?php
/**
 * @Author  : Created by Llyam Garcia.
 * @Nick    : Liightman
 * @Date    : 17/07/2015
 * @Time    : 16:02
 * @File    : AppController.php
 * @Version : 1.0
 */

namespace App\Controller;

use Core\Controller\Controller;
use \App;
use \Core\Auth\DBAuth;

class AppController extends Controller {

    protected $template = 'default';


    public function __construct(){
        $this->viewPath = ROOT . '/app/Views/';

    }

    protected function loadModel($model_name){
        $this->$model_name =  App::getInstance()->getTable($model_name);
    }


}
