<?php
/**
 * @Author  : Created by Llyam Garcia.
 * @Nick    : Liightman
 * @Date    : 16/07/2015
 * @Time    : 15:07
 * @File    : Config.php
 * @Version : 1.1
 */

namespace Core;


class Config {

    /**
     * @var string À ne pas toucher. Merci, par respect pour l'auteur, de laisser cette information quelque part sur votre site (par défaut, dans le footer)
     */
    public static $author = "<small>Powered with LightPHPFramework created by Liightman</small>";
    private $settings = [];
    private static $_instance;

    public static function getInstance($file) {
        if(is_null(self::$_instance)) {
            self::$_instance = new Config($file);
        }
        return self::$_instance;
    }

    public function __construct($file) {
        $this->settings = require($file);
    }

    public function get($key) {
        if(!isset($this->settings[$key])) {
            return null;
        }

        return $this->settings[$key];
    }
}