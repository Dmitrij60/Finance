<?php

namespace FinanceService\controllers;

use FinanceService\models\User;
use FinanceService\models\Card;

class CabinetController
{
    /**
     * @return bool
     */
    public function actionIndex()
    {
        $userId = User::checkLogged();
        $user = User::getUserById($userId);
        $card = Card::getCardRequisites($userId);
        if (isset($_POST['submit'])) {
            $withdraw = $_POST['withdraw'];
            $errors = Card::withdrawValidate($withdraw, $userId);
            if ($errors == false) {
                $balance = $card['balance'] - round($withdraw, 2);
                if ($result = Card::withdraw($userId, $balance)) {
                    header("Refresh: 0");
                }
            }
        }

        require_once(ROOT . '/views/cabinet/index.php');
        return true;
    }
}