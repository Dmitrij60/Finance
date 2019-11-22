<?php

namespace FinanceService\components;

use PDO;

class Db
{
    /**
     * @return \PDO
     */
    public static function getConnection()
    {
        $paramsPath = ROOT . '/config/db_params.php';
        $params = include($paramsPath);
        $dsn = "mysql:host={$params['host']};dbname={$params['dbname']}";
        $db = new \PDO($dsn, $params['user'], $params['password']);
        $db->exec("set names utf8");
        return $db;
    }


   /* private static $db = null;


    private function __construct ()
    {
        $paramsPath = ROOT . '/config/db_params.php';
        $params = include($paramsPath);
        $dsn = "mysql:host={$params['host']};dbname={$params['dbname']}";
        $this->db = new PDO($dsn, $params['user'], $params['password']
            /*[PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"]*/
   /*  );

    }

    private function __clone () {}
    private function __wakeup () {}

    /**
     * @return PDO|null
     */
    /*public static function getConnection()
    {
        if (self::$db != null) {
            return self::$db;
        }

        return new self;
    }*/
}
