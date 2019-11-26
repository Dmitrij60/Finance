<?php

namespace FinanceService\models;

use FinanceService\components\Db;
use \PDO;
use PDOException;

class Card
{
    /**
     * @var Db
     */
    private $db;

    function __construct()
    {
        $this->db = Db::Instance();
    }

    /**
     * @param $userId
     * @return mixed
     */
    public function getCardRequisites($userId)
    {
        $pdo = $this->db->get_pdo();
        $result = $pdo->prepare('SELECT card_number, balance FROM card WHERE user_id = :user_id');
        $result->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();
        return $result->fetch();
    }

    /**
     * @param $card_number
     * @param $balance
     * @param $user_id
     * @return bool
     */
    public static function activateBalance($card_number, $balance, $user_id)
    {
        $pdo = Db::Instance()
            ->Insert('card')
            ->values(['card_number' => $card_number, 'balance' => $balance, 'user_id' => $user_id]);
        return $pdo->execute(['card_number' => $card_number, 'balance' => $balance, 'user_id' => $user_id]);
    }

    /**
     * @param $userId
     * @param $balanceOnCard
     * @return bool
     */
    public function withdraw($userId, $balanceOnCard)
    {
        $pdo = $this->db->get_pdo();
        $pdo->setAttribute(PDO::ATTR_AUTOCOMMIT, 0);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        session_write_close();
        try {
            $pdo->setAttribute(PDO::ATTR_AUTOCOMMIT, 0);
            $pdo->beginTransaction();
            $result = $pdo->prepare("SELECT balance, user_id FROM card WHERE user_id = " . $userId . " LOCK IN SHARE MODE;");
            $result->execute();
            $sql = "UPDATE card SET balance = :balance WHERE user_id = :user_id";
            $result = $pdo->prepare($sql);
            $result->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $result->bindParam(':balance', $balanceOnCard, PDO::PARAM_STR);
            $result->execute();
            $pdo->commit();
        } catch (PDOException $e) {
            $pdo->rollBack();
            echo '<br><br><br>' . 'PDOException: ' . $e->getCode() . '|' . $e->getMessage();
            return false;
        }
        return true;
    }
}
