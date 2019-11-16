<?php

class Card
{
    /**
     * @param $userId
     * @return mixed
     */
    public static function getCardRequisites($userId)
    {
        $db = Db::getConnection();
        $result = $db->query('SELECT card_number, balance FROM card WHERE user_id = ' . $userId);
        $result->setFetchMode(PDO::FETCH_ASSOC);
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
        $db = Db::getConnection();
        $sql = 'INSERT INTO card (card_number, balance, user_id) '
            . 'VALUES (:card_number, :balance, :user_id)';
        $result = $db->prepare($sql);
        $result->bindParam(':card_number', $card_number, PDO::PARAM_STR);
        $result->bindParam(':balance', $balance, PDO::PARAM_STR);
        $result->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        return $result->execute();
    }

    /**
     * @param $userId
     * @param $balance
     * @return bool
     */
    public static function withdraw($userId, $balance)
    {
        $db = Db::getConnection();

        $db->setAttribute(PDO::ATTR_AUTOCOMMIT, 0);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        session_write_close();
        try {
            $db->beginTransaction();
            $sql = "UPDATE card SET balance = :balance WHERE user_id = :user_id";
            $result = $db->prepare($sql);
            $result->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $result->bindParam(':balance', $balance, PDO::PARAM_STR);
            $result->execute();
            $db->commit();
        } catch (PDOException $e) {
            $db->rollBack();
            header('Refresh: 0');
            echo 'PDOException: ' . $e->getCode() . '|' . $e->getMessage();
            return false;
        }
        return true;
    }

    /**
     * @param $email
     * @return bool
     */
    public static function checkField($email)
    {
        if (filter_var($email, FILTER_VALIDATE_FLOAT)) {
            return true;
        }
        return false;
    }

    /**
     * @param $withdraw
     * @param $userId
     * @return bool
     */
    public static function checkBalance($withdraw, $userId)
    {
        $db = Db::getConnection();
        $result = $db->query('SELECT balance FROM card WHERE user_id = ' . $userId);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $balance = $result->fetch();
        $balance = intval($balance['balance']);
        $result = $balance - $withdraw;
        if ($result < 0) {
            return false;
        }
        return true;
    }
}
