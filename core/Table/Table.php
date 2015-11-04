<?php
/**
 * @Author  : Created by Llyam Garcia.
 * @Nick    : Liightman
 * @Date    : 16/07/2015
 * @Time    : 16:05
 * @File    : Table.php
 * @Version : 1.4
 */

namespace Core\Table;
use Core\Database\Database;

class Table {

    protected $table;
    protected $db;

    public function __construct(Database $db){
        $this->db = $db;
        if(is_null($this->table)) {
            $parse = explode('\\', get_class($this));
            $class_name = end($parse);
            $this->table = strtolower(str_replace('Table', '', $class_name)) . 's';
        }
    }

    /**
     * @param $id int Récupère un article/une new précise
     * @return mixed
     */
    public function find($id, $user = false) {
        if ($user) {
            return $this->query("SELECT *  FROM {$this->table}  WHERE user_id = ? ", [$id], false, true);
        } else {
            return $this->query("SELECT *  FROM {$this->table}  WHERE id = ? ", [$id], false, true);
        }
    }

    public function getUser($id){
        return $this->query("SELECT * FROM users WHERE user_id = ?", [$id]);

    }
    /**
     * Récupère toutes les données d'une table
     * @return mixed
     */
    public function all(){
        return $this->query("SELECT * FROM {$this->table} ORDER BY id ");
    }

    public function select($id){
        return $this->query("SELECT * FROM {$this->table} WHERE user_id = ?", [$id], false, true);
    }

    public function delete($id){
        return $this->query("DELETE FROM {$this->table} WHERE id = ?", [$id], false, true);
    }

    /**
     * @param $id int Permet de savoir quelle ligne on modifie
     * @param $fields array Les champs pour concernés par l'update
     * @return mixed
     */
    public function update($id, $fields){
        $sql_parts = [];
        $attributes = [];

        foreach($fields as $k => $v) {
            $sql_parts[] = "$k = ?";
            $attributes[] = $v;
        }
        $attributes[] = $id;
        $sql_part = implode(', ', $sql_parts);
        return $this->query("UPDATE {$this->table} SET $sql_part WHERE id = ?", $attributes, true);
    }

    public function create($fields){
        $sql_parts = [];
        $attributes = [];

        foreach($fields as $k => $v) {
            $sql_parts[] = "$k = ?";
            $attributes[] = $v;
        }
        $sql_part = implode(', ', $sql_parts);
        return $this->query("INSERT INTO {$this->table} SET $sql_part", $attributes, true);
    }

    /**
     * @param $statement string Requête à executée
     * @param null $attributes string Attributs si c'est une requête préparée
     * @param bool $count Si on veut faire un rowCount sur l'objet
     * @param bool|false $one Si l'on décide de récupérer toute les données ou qu'une seule
     * @return mixed
     */
    public function query($statement, $attributes = null, $count = false, $one = false) {
        if ($attributes){
            return $this->db->prepare(
                $statement,
                $attributes,
                str_replace('Table', 'Entity', get_class($this)),
                $one
            );
        } else {

            if($count){
                return  $this->db->query(
                    $statement,
                    str_replace('Table', 'Entity', get_class($this)),
                    $count
                );
            } else {
                return $this->db->query(
                    $statement,
                    str_replace('Table', 'Entity', get_class($this)),
                    $one
                );
            }

        }
    }
}