<?php
/**
 * @Author  : Created by Llyam Garcia.
 * @Nick    : Liightman
 * @Date    : 16/07/2015
 * @Time    : 21:32
 * @File    : NewEntity.php
 * @Version : 1.0
 */

namespace App\Entity;
use Core\Entity\Entity;

/**
 * Class ArticleEntity Class qui représente l'entitée
 */
class NewEntity extends Entity {

    public function getUrl() {
        return 'index.php?page=news.show&id=' . $this->id;
    }

    public function getExtrait() {
        $html = '<p>' . substr($this->contenu, 0, 375) . '...</p>';
        $html .= ' <p><a class="btn btn-default" href="' . $this->getUrl() . '" role="button">Voir plus &raquo;</a></p>';
        return $html;
    }
}
