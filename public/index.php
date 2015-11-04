<?php
/**
 * @Author  : Created by Llyam Garcia.
 * @Nick    : Liightman
 * @Date    : 16/07/2015
 * @Time    : 10:16
 * @File    : index.php
 * @Version : 1.0
 */

/**
 * On impose les règles d'affichage des erreurs
 */
ini_set('display_errors',1);
error_reporting(E_ALL);

/**
 * On créer la constante root
 */
define('ROOT', dirname(__DIR__));
require ROOT . '/app/App.php';
require ROOT . '/app/Functions.php';
//get_include_path('/app/google-api-php-client/src');
//include_path(get_include_path() . PATH_SEPARATOR . '/app/google-api-php-client/src');

/**
 * On charge le core de notre appli
 */
App::load();

$app = App::getInstance();

isset($_GET['page']) ? $page = $_GET['page'] : $page = 'news.index';

$page = explode('.', $page);

/**
 * On vérifie que tout les paramètre nécessaires sont présents
 */
if(empty($page[0]) OR empty($page[1])) :
    $page[0] = "news";
    $page[1] = "index";
endif;
/**
 * On vérifie si le dossier existe
 */
/*if (is_dir(ROOT . '/app/Views/' . $page[0] . '/')){
    if (is_file(ROOT . '/app/Views/' . $page[0] . '/' . $page[1] . '.php')){}else {
        $page[0] = "news";
        $page[1] = "index";
    }
} else {
    $page[0] = "news";
    $page[1] = "index";
}*/

if($page[0] == 'admin'){
    $controller = '\App\Controller\Admin\\' . ucfirst($page[1]) . 'Controller';
    $action = $page[2];
} else {
    $controller = '\App\Controller\\' . ucfirst($page[0]) . 'Controller';
    $action = $page[1];
}

$controller = new $controller();

$controller->$action();

?>