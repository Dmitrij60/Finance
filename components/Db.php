<?php

namespace FinanceService\components;

use PDO;

class Db
{
    public $pdo;
    public static $instance;

    private $type;
    private $sql_query;
    private $values_for_exec;

    /**
     * Db constructor.
     */
    private function __construct(){
        $this->sql_query = "";
        $this->values_for_exec = array();
        $paramsPath = ROOT . '/config/db_params.php';
        $params = include($paramsPath);
        $dsn = "mysql:host={$params['host']};dbname={$params['dbname']}";
        $this->pdo = new \PDO($dsn, $params['user'], $params['password']);
        $this->pdo->exec("SET CHARSET utf8");
    }


    private function __clone()
    {
    }

    private function __wakeup()
    {
    }

    /**
     * @return Db
     */
    public static function Instance(){
        if(self::$instance == NULL){
            self::$instance = new Db();
        }
        return self::$instance;
    }

    /**
     * @param $table
     * @return $this
     */
    public function Select($table){
        $this->sql_query = "SELECT * FROM `$table` ";
        $this->type = 'select';
        return $this;
    }

    public function SelectCount($table){
        $this->sql_query = "SELECT COUNT(*) FROM `$table` ";
        $this->type = 'select';
        return $this;
    }

    /**
     * @param $table
     * @return $this
     */
    public function Insert($table){
        $this->sql_query = "INSERT INTO `$table` ";
        $this->type = 'insert';
        return $this;
    }

    /**
     * @param $table
     * @return $this
     */
    public function Update($table){
        $this->sql_query = "UPDATE `$table` ";
        $this->type = 'update';
        return $this;
    }

    /**
     * @param $table
     * @return $this
     */
    public function Delete($table){
        $this->sql_query = "DELETE FROM `$table`";
        $this->type = 'delete';
        return $this;
    }

    /**
     * @param $val
     * @param $type
     * @return $this
     */
    public function order_by($val, $type){
        $this->sql_query .= " ORDER BY `$val` $type ";
        return $this;
    }

    /**
     * @param $where
     * @param string $op
     * @return $this
     */
    public function where($where, $op = '='){
        $vals = array();
        foreach($where as $k => $v){
            $vals[] = "`$k` $op :$k";
            $this->values_for_exec[":".$k] = $v;
        }
        $str = implode(' AND ',$vals);
        $this->sql_query .= " WHERE " . $str .' ';
        return $this;
    }

    /**
     * @param $from
     * @param null $count
     * @return $this
     */
    public function limit($from, $count = NULL){
        $res_str = "";
        if($count == NULL){
            $res_str = $from;
        }else{
            $res_str = $from . "," . $count;
        }
        $this->sql_query .= " LIMIT " . $res_str;
        return $this;
    }

    /**
     * @param $arr_val
     * @return $this
     */
    public function values($arr_val){
        $cols = array();
        $masks = array();
        $val_for_update = array();

        foreach($arr_val as $k => $v){
            $value_mask = explode(' ',$v);
            $value_mask = $value_mask[0];
            $value_key = explode(' ', $k);
            $value_key = $value_key[0];
            $cols[] = "`$value_key`";
            $masks[] = ':'.$value_key;

            $val_for_update[] = "`$value_key`=:$value_key";
            $this->values_for_exec[":$value_key"] = $v;
        }
        if($this->type == "insert"){
            $cols_all = implode(',',$cols);
            $masks_all = implode(',',$masks);
            $this->sql_query .= "($cols_all) VALUES ($masks_all)";
        }else if($this->type == 'update'){
            $this->sql_query .= "SET ";
            $this->sql_query .= implode(',',$val_for_update);
        }
        return $this;
    }

    /**
     * @param $arr_val
     * @return $this|bool
     */
    public function bindValue($arr_val)
    {
        foreach ($arr_val as $key => $value) {
            $stmt = $this->pdo->prepare($this->sql_query);
            if($stmt->bindValue(':'.$key, $value)) {
                return $this;
            } else {
                return false;
            }
        }
    }

    /**
     * @param $arr_val
     * @return array|bool
     */
    public function execute($arr_val = null){
        $stmt = $this->pdo->prepare($this->sql_query);
        if($arr_val) {
            foreach ($arr_val as $key => $value) {
                $stmt->bindValue(':' . $key, $value);
            }
        }
        $stmt->execute($this->values_for_exec);
        if($this->type == "select"){
            $this->set_default();
            return $stmt->fetch();
        }else if($this->type == 'insert'){
            $this->set_default();
            return true;
        }else{
            $this->set_default();
            return true;
        }
    }

    /**
     * @return PDO
     */
    public function get_pdo(){
        return $this->pdo;
    }

    /**
     * drop class field
     */
    private function set_default(){
        $this->type = "";
        $this->sql_query = "";
        $this->values_for_exec = array();
    }
}
