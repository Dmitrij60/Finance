<?php


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

            $errors = false;
            if (!isset($withdraw) || empty($withdraw)) {
                $errors[] = 'Заполните поля';
            }
            if (!Card::checkField($withdraw)) {
                $errors[] = 'Введите корректное значение';
            }
            if (!Card::checkBalance($withdraw, $userId)) {
                $errors[] = 'У Вас не хватает средств';
            }
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