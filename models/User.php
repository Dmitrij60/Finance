<?php

namespace FinanceService\models;

use Exception;
use FinanceService\components\Db;
use \PDO;

class User
{
    /**
     * User constructor.
     * @param $db
     * @throws Exception
     */
    public function __construct($db) {
        if ($db instanceof Db) {
            $this->db = $db;
        } else {
            throw new Exception('Give me a Database object');
        }
    }
    /**
     * @param $name
     * @param $email
     * @param $password
     * @return bool
     */
    public static function register($name, $email, $hashed_password)
    {
        $db = Db::getConnection();
        $sql = 'INSERT INTO user (name, email, password) '
            . 'VALUES (:name, :email, :password)';
        $result = $db->prepare($sql);
        $result->bindParam(':name', $name, PDO::PARAM_STR);
        $result->bindParam(':email', $email, PDO::PARAM_STR);
        $result->bindParam(':password', $hashed_password, PDO::PARAM_STR);
        return $result->execute();
    }

    /**
     * @param string $email
     * @param string $password
     * @return mixed : ingeger user id or false
     */
    public static function checkUserData($email)
    {
        $db = Db::getConnection();
        $sql = 'SELECT * FROM user WHERE email = :email ';
        $result = $db->prepare($sql);
        $result->bindParam(':email', $email, PDO::PARAM_STR);
        $result->execute();
        $user = $result->fetch();
        if ($user) {
            return $user['id'];
        }
        return false;
    }

    /**
     * @param $userId
     */
    public static function auth($userId)
    {
        $_SESSION['user'] = $userId;
    }

    /**
     * @return mixed
     */
    public static function checkLogged()
    {
        if (isset($_SESSION['user'])) {
            return $_SESSION['user'];
        }
        header('Location: /user/login');
    }

    /**
     * @return bool
     */
    public static function isGuest()
    {
        if (isset($_SESSION['user'])) {
            return false;
        }
        return true;
    }

    /**
     * @param $name
     * @return bool
     */
    public static function checkName($name)
    {
        if (strlen($name) >= 2) {
            return true;
        }
        return false;
    }

    /**
     * @param $password
     * @return bool
     */
    public static function checkPassword($password)
    {
        if (strlen($password) >= 6) {
            return true;
        }
        return false;
    }

    /**
     * @param $email
     * @return bool
     */
    public static function checkEmail($email)
    {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return true;
        }
        return false;
    }

    /**
     * @param $email
     * @return bool
     */
    public static function checkEmailExists($email)
    {
        $db = Db::getConnection();
        $sql = 'SELECT COUNT(*) FROM user WHERE email = :email';
        $result = $db->prepare($sql);
        $result->bindParam(':email', $email, PDO::PARAM_STR);
        $result->execute();
        if ($result->fetchColumn())
            return true;
        return false;
    }

    /**
     * @param $id
     * @return mixed
     */
    public static function getUserById($id)
    {
        if ($id) {
            $db = Db::getConnection();
            $sql = 'SELECT * FROM user WHERE id = :id';
            $result = $db->prepare($sql);
            $result->bindParam(':id', $id, PDO::PARAM_INT);
            $result->setFetchMode(PDO::FETCH_ASSOC);
            $result->execute();
            return $result->fetch();
        }
    }

    /**
     * @param $email
     * @param $password
     * @param $userId
     * @return array|bool
     */
    public static function loginValidate($email, $password, $userId)
    {
        $errors = false;
        if (!User::checkEmail($email)) {
            $errors[] = 'Неправильный email';
        }
        if ($userId == false) {
            $errors[] = 'Неправильные данные для входа на сайт';
        }
        return $errors;
    }

    /**
     * @param $name
     * @param $email
     * @param $password
     * @return array|bool
     */
    public static function registerValidate($name, $email, $password)
    {
        $errors = false;
        if (!User::checkName($name)) {
            $errors[] = 'Имя не должно быть короче 2-х символов';
        }
        if (!User::checkEmail($email)) {
            $errors[] = 'Неправильный email';
        }
        if (!User::checkPassword($password)) {
            $errors[] = 'Пароль не должен быть короче 6-ти символов';
        }
        if (User::checkEmailExists($email)) {
            $errors[] = 'Такой email уже используется';
        }
        return $errors;
    }

    /**
     * @param $password
     * @return string
     */
    public static function generateHash($password)
    {
        if (defined("CRYPT_BLOWFISH") && CRYPT_BLOWFISH) {
            $salt = '$2y$11$' . substr(md5(uniqid(rand(), true)), 0, 22);
            return crypt($password, $salt);
        }
    }

    public function getNews() {
        $result = $this->db->row('SELECT title, description FROM news');
        return $result;
    }

    /**
     * @param $login
     * @return mixed
     */
    public static function checkUserDataHash($email)
    {
        // Соединение с БД
        $db = Db::getConnection();
        // Текст запроса к БД
        $sql = "SELECT * FROM user WHERE email = :email";

        $result = $db->prepare($sql);
        $result->bindParam(':email', $email, PDO::PARAM_STR);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();
        return $result->fetch();
    }

    public static function verify($password, $hashedPassword) {
        return crypt($password, $hashedPassword) == $hashedPassword;
    }
}