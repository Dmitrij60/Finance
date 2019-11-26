<?php

namespace FinanceService\controllers;

use FinanceService\components\Validator;
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
        $errors = false;
        $result = false;
        if (isset($_POST['submit'])) {
            $name = $_POST['name'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $validate = new Validator;
            $errors = $validate->registerValidate($name, $email, $password);
            if ($errors === false) {
                $usr = new User;
                if (!$usr->register($name, $email, $password)) {
                    $errors[] = 'Ошибка Базы Данных';
                } else {
                    $userId = $usr->checkUserData($email);
                    $usr->auth($userId);
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
        $errors = false;
        if (isset($_POST['submit'])) {
            $email = $_POST['email'];
            $password = $_POST['password'];
            $usr = new User;
            $check = $usr->checkUserDataHash($email);
            if ($usr->verify($password, $check['password'])) {
                $usr->auth($check['id']);
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