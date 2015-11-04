<?php
/**
 * @Author  : Created by Llyam Garcia.
 * @Nick    : Liightman
 * @Date    : 16/07/2015
 * @Time    : 10:17
 * @File    : Autoloader.php
 * @Version : 1.0
 * @Todo    : � completer!
 */


namespace App;
/**
 * Class Autoloader Permet de charger automatique les fichiers class appelés
 */
class Autoloader{

    /**
     * @param $class string Récupère le nom de la class afin d'aller chercher le fichier correspondant
     */
    static function autoload($class){
        if (strpos($class, __NAMESPACE__ . '\\') === 0) {
            $class = str_replace(__NAMESPACE__ .'\\', '', $class);
            $class = str_replace('\\', '/', $class);
            require __DIR__. '/'. $class . '.php';
        }
    }

    static function register () {
        spl_autoload_register(array(__CLASS__, 'autoload'));
    }
}