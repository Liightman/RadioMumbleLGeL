<?php
/**
 * @Author  : Created by Llyam Garcia.
 * @Nick    : Liightman
 * @Date    : 16/07/2015
 * @Time    : 22:17
 * @File    : Entity.php
 * @Version : 1.0
 */

namespace Core\Entity;
class Entity {

    /**
     * @param $key string fonction magique
     * @return mixed
     */
    public function __get($key){
        $method = 'get' . ucfirst($key);
        $this->key = $this->$method();
        return $this->key;
    }


}