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
                $withdraw = round($withdraw, 2);
                $balanceOnCard = $card['balance'] - $withdraw;
                if ($result = Card::withdraw($userId, $balanceOnCard)) {
                    header("Refresh: 0");
                }
            }
        }

        require_once(ROOT . '/views/cabinet/index.php');
        return true;
    }
}