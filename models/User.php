<?php

namespace FinanceService\models;

use FinanceService\components\Db;

class User
{
    /**
     * User constructor.
     * @param Db $db
     */
    private $db;

    public function __construct()
    {
        $this->db = Db::Instance();
    }

    /**
     * @param $name
     * @param $email
     * @param $hashed_password
     * @return array|bool|string
     */
    public function register($name, $email, $password)
    {
        $hashed_password = $this->generateHash($password);
        $pdo = $this->db;
        $pdo->Insert('user')
            ->values(['name' => $name, 'email' => $email, 'password' => $hashed_password]);
        return $pdo->execute(['name' => $name, 'email' => $email, 'password' => $hashed_password]);

    }

    /**
     * @param $email
     * @return bool
     */
    public function checkUserData($email)
    {
        $pdo = $this->db;
        $pdo->Select('user')
            ->where(['email' => $email]);
        $user = $pdo->execute(['email' => $email]);
        if ($user) {
            return $user['id'];
        }
        return false;
    }

    /**
     * @param $userId
     */
    public function auth($userId)
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
     * @param $id
     * @return mixed
     */
    public static function getUserById($id)
    {
        if ($id) {
            $pdo = Db::Instance();
            $pdo->Select('user')
                ->where(['id' => $id]);
            return $pdo->execute(['id' => $id]);
        }
    }

    /**
     * @param $password
     * @return string
     */
    public function generateHash($password)
    {
        if (defined("CRYPT_BLOWFISH") && CRYPT_BLOWFISH) {
            $salt = '$2y$11$' . substr(md5(uniqid(rand(), true)), 0, 22);
            return crypt($password, $salt);
        }
    }

    /**
     * @param $email
     * @return mixed
     */
    public function checkUserDataHash($email)
    {
        $pdo = $this->db;
        $pdo->Select('user')
            ->where(['email' => $email]);
        return $pdo->execute(['email' => $email]);
    }

    /**
     * @param $password
     * @param $hashedPassword
     * @return bool
     */
    public function verify($password, $hashedPassword)
    {
        return crypt($password, $hashedPassword) == $hashedPassword;
    }
}