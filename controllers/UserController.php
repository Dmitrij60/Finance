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
                User::register($name, $email, $password);
                $userId = User::checkUserData($email, $password);
                User::auth($userId);
                Card::activateBalance($card_number, $balance, $userId);
                header('Location: /cabinet');
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
            $userId = User::checkUserData($email, $password);
            $errors = User::loginValidate($email, $password, $userId);
            if ($errors == false) {
                User::auth($userId);
                header('Location: /cabinet');
            }
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