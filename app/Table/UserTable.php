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


class UserTable extends Table {

    public function checkData($type, $data){
        return $this->query("SELECT * FROM users WHERE {$type} = '{$data}'");
    }

}
