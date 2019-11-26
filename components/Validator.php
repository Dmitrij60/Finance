<?php

namespace FinanceService\components;

use FinanceService\components\Db;
use \PDO;

class Validator
{

    private $db;

    public function __construct()
    {
        $this->db = Db::Instance();
    }

    /**
     * @param $name
     * @return bool
     */
    public function checkName($name)
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
    public function checkEmail($email)
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
        $pdo = Db::Instance()->get_pdo();
        $result = $pdo->prepare('SELECT COUNT(*) FROM user WHERE email = :email');
        $result->bindParam(':email', $email, PDO::PARAM_STR);
        $result->execute();
        if ($result->fetchColumn())
            return true;
        return false;
    }


    /**
     * @param $email
     * @return mixed
     */
    public function checkUserDataHash($email)
    {
        $pdo = Db::Instance();
        $pdo->Select('user')
            ->where(['email' => $email]);
        return $pdo->execute(['email' => $email]);
    }

    /**
     * @param $name
     * @param $email
     * @param $password
     * @return array|bool
     */
    public function registerValidate($name, $email, $password)
    {
        $errors = false;
        if (!$this->checkName($name)) {
            $errors[] = 'Имя не должно быть короче 2-х символов';
        }
        if (!$this->checkEmail($email)) {
            $errors[] = 'Неправильный email';
        }
        if (!$this->checkPassword($password)) {
            $errors[] = 'Пароль не должен быть короче 6-ти символов';
        }
        if ($this->checkEmailExists($email)) {
            $errors[] = 'Такой email уже используется';
        }
        return $errors;
    }

    /**
     * @param $email
     * @return bool
     */
    public function checkField($email)
    {
        if (filter_var($email, FILTER_VALIDATE_FLOAT)) {
            return true;
        }
        return false;
    }

    /**
     * @param $withdraw
     * @return bool
     */
    public function checkNumber($withdraw)
    {
        if ($withdraw > 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param $withdraw
     * @param $userId
     * @return bool
     */
    public function checkBalance($withdraw, $userId)
    {
        $pdo = $this->db->get_pdo();
        $result = $pdo->prepare('SELECT balance FROM card WHERE user_id = :user_id');
        $result->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();
        $balance = $result->fetch();
        $withdraw = intval($withdraw);
        $balance = intval($balance['balance']);
        $result = $balance - $withdraw;
        if ($result < 0) {
            return false;
        }
        return true;
    }

    /**
     * @param $withdraw
     * @param $userId
     * @return array|bool
     */
    public function withdrawValidate($withdraw, $userId)
    {
        $errors = false;
        if (!isset($withdraw) || empty($withdraw)) {
            $errors[] = 'Заполните поля';
        }
        if (!$this->checkField($withdraw)) {
            $errors[] = 'Введите корректное значение';
        }
        if (!$this->checkBalance($withdraw, $userId)) {
            $errors[] = 'У Вас не хватает средств';
        }
        if (!$this->checkNumber($withdraw)) {
            $errors[] = 'Введите положительное число';
        }
        return $errors;

    }
}