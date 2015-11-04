<?php
/**
 * @Author  : Created by Llyam Garcia.
 * @Nick    : Liightman
 * @Date    : 16/07/2015
 * @Time    : 12:51
 * @File    : App.php
 * @Version : 1.0
 * @Todo    : À completer!
 */

use Core\Config;
use Core\Database\MysqlDatabase;

/**
 * Class App Factory + singleton | Permet d'initialiser les instances dès qu'on en a besoin
 */
class App{

    /**
     * @var string Définit le titre de page
     */
    public $title = "Radio Mumble";
    /**
     * @var string Définit le nom du site/projet
     */
    public $name = "Radio Mumble";
    /**
     * @var null Définit les class="active" dans les menu selon la page où l'on se trouves
     */
    public $menuActive = null;
    /**
     * @var
     */
    private $db_instance;
    private static $_instance;

    /**
     * Singletons
     * @return mixed
     */
    public static function getInstance(){
        if(is_null(static::$_instance)) {
            static::$_instance = new App();
        }
        return static::$_instance;
    }

    public static function load(){
        session_start();
        require ROOT . '/app/Autoloader.php';
        App\Autoloader::register();
        require ROOT . '/core/Autoloader.php';
        Core\Autoloader::register();

    }

    /**
     * @return string À ne pas toucher. Ce framework n'est pas OpenSource et appartient donc de droit à Llyam Garcia
     */
    public static function getAuthor(){
        return Config::$author;
    }

    public function getTable($name){
        $class_name = '\\App\\Table\\' .ucfirst($name). 'Table';
        return new $class_name($this->getDb());
    }

    public function getDb(){
        $config = Config::getInstance(ROOT . '/config/config.php');

        if(is_null($this->db_instance)){
            $this->db_instance = new MysqlDatabase($config->get('_db_name'),$config->get('_db_user'),$config->get('_db_host'),$config->get('_db_pass') );
        }
        return  $this->db_instance;
    }

}