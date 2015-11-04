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

class CategoryTable extends Table {

    /**
     * @var string Etant donné qu'on a une fonction qui se charge de prendre le nom de la classe (au singulier) puis d'y rajouter un "s" afin de récupérer le table
     * il peut y avoir certains cas comme celui de Category qui ne fonctionnent pas étant donné que si on laisse tel quel, le nom de la table retourné serais : "categorys"
     * Donc on redefinit la $table directement
     */
    protected $table = "categories";
}
