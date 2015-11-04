<?php
/**
 * @Author  : Created by Llyam Garcia.
 * @Nick    : Liightman
 * @Date    : 16/07/2015
 * @Time    : 16:05
 * @File    : UserTable.php
 * @Version : 1.0
 */

namespace App\Table;
use \Core\Table\Table;


class NewTable extends Table {

    /**
     *  Récupère les derniers news/news
     */
    public function last(){
        return $this->query("SELECT * FROM news ORDER BY date DESC LIMIT 0,3");
    }
}
