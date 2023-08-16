<?php

namespace Models;

use System\Db;

class Message extends Model
{
    protected static $table = 'friends';

    public static function getAll(int $user_id, $friend_id, int $limit = null, bool $active = true, $object = true)
    {
        $activity = !empty($active) ? 'AND active IS NOT NULL' : '';
        $quantity = !empty($limit) ? "LIMIT {$limit}" : '';

        $params = [
            ':user_id' => $user_id,
            ':friend_id' => $friend_id,
        ];

        $sql = "
            SELECT * 
                FROM (
                    SELECT 
                        id, from_user_id message_from, to_user_id message_to, message, created, updated 
                    FROM messages 
                    WHERE 
                        (from_user_id = :user_id OR from_user_id = :friend_id) AND 
                        (to_user_id = :user_id OR to_user_id = :friend_id) 
                        {$activity} 
                    ORDER BY created DESC, id DESC 
                    {$quantity}
                ) m 
            ORDER BY m.created, m.id";

        $db = new Db();
        $data = $db->query($sql, $params, $object ? static::class : null);
        return $data ?? false;
    }

    public static function getLast(int $user_id, $friend_id, int $start = null, int $limit = null, bool $active = true, $object = true)
    {
        $activity = !empty($active) ? 'AND active IS NOT NULL ' : '';
        $start_msg = !empty($start) ? 'AND id > :start ' : '';
        $quantity = !empty($limit) ? "LIMIT {$limit}" : '';

        $params = [
            ':user_id' => $user_id,
            ':friend_id' => $friend_id,
        ];
        if (!empty($start)) $params['start'] = $start;

        $sql = "
            SELECT * 
                FROM (
                    SELECT 
                        id, from_user_id message_from, to_user_id message_to, message, created, updated 
                    FROM messages 
                    WHERE 
                        (from_user_id = :user_id OR from_user_id = :friend_id) AND 
                        (to_user_id = :user_id OR to_user_id = :friend_id) 
                        {$start_msg}
                        {$activity} 
                    ORDER BY created DESC, id DESC
                    {$quantity}
                ) m 
            ORDER BY m.created, m.id";

        $db = new Db();
        $data = $db->query($sql, $params, $object ? static::class : null);
        return $data ?? false;
    }

    public static function saveMessage($message_from, $message_to, $message)
    {
        $params = [
            ':message_from' => $message_from,
            ':message_to' => $message_to,
            ':message' => $message
        ];
        $sql = "
            INSERT INTO messages 
                (from_user_id, to_user_id, message) 
            VALUES 
                (:message_from, :message_to, :message)
        ";

        $db = new Db();
        return $db->execute($sql, $params);
    }

    public static function checkData($friend, $message)
    {
        return self::checkUser($friend) && self::checkMessage($message);
    }

    public static function checkUser($friend)
    {
        return !empty($friend->id);
    }

    public static function checkMessage($message)
    {
        return !empty($message);
    }
}
