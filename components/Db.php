<?php

namespace FinanceService\components;

class Db
{
    /**
     * @return PDO
     */
    public static function getConnection()
    {
        $paramsPath = ROOT . '/config/db_params.php';
        $params = include($paramsPath);
        $dsn = "mysql:host={$params['host']};dbname={$params['dbname']}";
        $db = new \PDO($dsn, $params['user'], $params['password']);
       // self::$db = new \PDO($dsn, self::$user, self::$password, $opt);
        $db->exec("set names utf8");
        return $db;
    }
}