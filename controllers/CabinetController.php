<?php

namespace FinanceService\controllers;

use FinanceService\components\Validator;
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
        $card = new Card();
        $requisites = $card->getCardRequisites($userId);
        $requisites = array_values($requisites);
        if (isset($_POST['submit'])) {
            $withdraw = $_POST['withdraw'];
            $validate = new Validator;
            $errors = $validate->withdrawValidate($withdraw, $userId);
            if ($errors === false) {
                $withdraw = round($withdraw, 2);
                $balanceOnCard = $requisites[1] - $withdraw;
                $card->withdraw($userId, $balanceOnCard);
                header("Refresh: 0");
            }
        }

        require_once(ROOT . '/views/cabinet/index.php');
        return true;
    }
}