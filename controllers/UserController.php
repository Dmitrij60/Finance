<?php

namespace FinanceService\controllers;

use FinanceService\models\User;
use FinanceService\models\Card;

class UserController
{

    /**
     * @return bool
     */
    public function actionRegister()
    {
        $name = '';
        $email = '';
        $password = '';
        $balance = 6000;
        $card_number = strval(time());
        $result = false;
        if (isset($_POST['submit'])) {
            $name = $_POST['name'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $errors = User::registerValidate($name, $email, $password);
            if ($errors == false) {
                $hashed_password = User::generateHash($password);
                if (!User::register($name, $email, $hashed_password)) {
                    $errors[] = 'Ошибка Базы Данных';
                } else {
                    $userId = User::checkUserData($email, $hashed_password);
                    User::auth($userId);
                    Card::activateBalance($card_number, $balance, $userId);
                    header("Location:/cabinet");
                }
            }
        }
        require_once(ROOT . '/views/user/register.php');
        return true;
    }

    /**
     * @return bool
     */
    public function actionLogin()
    {
        $email = '';
        $password = '';
        if (isset($_POST['submit'])) {
            $email = $_POST['email'];
            $password = $_POST['password'];
            $errors = false;
            if (!User::checkName($email)) {
                $errors[] = 'Неккоректное имя';
            }
            $check = User::checkUserDataHash($email);
            $hashed_password = $check['password'];
            $userId = $check['id'];
            if (User::verify($password, $hashed_password)) {
                User::auth($userId);
                header("Location: /cabinet");
            } else $errors[] = 'Неправильные данные для входа на сайт';
        }

        require_once(ROOT . '/views/user/login.php');
        return true;
    }


    /**
     * Logout
     */
    public function actionLogout()
    {
        unset($_SESSION['user']);
        header('Location: /');
    }
}