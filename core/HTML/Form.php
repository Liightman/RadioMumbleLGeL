<?php
/**
 * @Author  : Created by Llyam Garcia.
 * @Nick    : Liightman
 * @Date    : 15/07/2015
 * @Time    : 22:57
 * @File    : form.php
 * @Version : 1.0
 */

namespace Core\HTML;

/**
 * Class Form
 * Permet de générer un formulaire rapidement et simplement
 */

class Form{

    /**
     * @var array Données utilisées par le formulaire
     */
    private $data;

    /**
     * @var string Tag utilisé pour entourer les champs
     */
    public $surround ='p';

    /**
     * @param array $data  Données utilisées par le formulaire
     */
    public function __construct($data = array()){
        $this->data = $data;
    }

    /**
     * @param $html string Code HTML à entourer
     * @return string
     */
    protected function surround($html){
        return "<{$this->surround}>{$html}</{$this->surround}>";
    }

    /**
     * @param $index string Index de la valeur à récupérer
     * @return string
     */
    protected function getValue($index){
        if(is_object($this->data)) {
            return $this->data->$index;
        }
        return isset($this->data[$index]) ? $this->data[$index] : null;
    }

    /**
     * @param $name string
     * @param $label
     * @param array $options
     * @return string
     */
    public function input($name, $label, $options = []){
        $type = isset($options['type']) ? $options['type'] : 'text';
        return $this->surround('<input type="' . $type . '" name="' . $name . '" value="' . $this->getValue($name) . '" />');
    }

    /**
     * @param $text Le text a afficher dans le bouton
     * @return string
     */
    public function submit($text){
        return $this->surround(' <button type="submit">' . $text . '</button>');
    }

}