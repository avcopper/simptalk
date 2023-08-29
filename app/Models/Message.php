<?php

namespace Models;

use System\Db;

class Message extends Model
{
    protected static $db_table = 'simptalk.messages';

    public $id;
    public $active = 1;
    public $is_read = null;
    public $from_user_id;
    public $to_user_id;
    public $message;
    public $created;
    public $updated;

    public static function getAll(int $user_id, $friend_id, int $limit = null, bool $active = true, $object = true)
    {
        $db = Db::getInstance();
        $activity = !empty($active) ? 'AND active IS NOT NULL' : '';
        $quantity = !empty($limit) ? "LIMIT {$limit}" : '';

        $db->params = [
            ':user_id' => $user_id,
            ':friend_id' => $friend_id,
        ];

        $db->sql = "
            SELECT * 
                FROM (
                    SELECT 
                        id, active, is_read, from_user_id, to_user_id, message, created, updated 
                    FROM " . self::$db_prefix . self::$db_table . " 
                    WHERE 
                        (from_user_id = :user_id OR from_user_id = :friend_id) AND 
                        (to_user_id = :user_id OR to_user_id = :friend_id) 
                        {$activity} 
                    ORDER BY created DESC, id DESC 
                    {$quantity}
                ) m 
            ORDER BY m.created, m.id";

        $data = $db->query($object ? static::class : null);
        return $data ?? null;
    }

    public static function getById(int $id, bool $active = true, $object = true)
    {
        $db = Db::getInstance();
        $activity = !empty($active) ? 'AND u.active IS NOT NULL AND u.blocked IS NULL AND ub.expire IS NULL AND ug.active IS NOT NULL' : '';
        $db->params = ['id' => $id];
        $db->sql = "
            SELECT 
                u.id, u.active, u.blocked, ub.expire, u.group_id, ug.name group_name, u.login, u.password, u.pin, u.e_pin, u.email, u.phone, 
                u.name,  u.second_name, u.last_name, u.gender_id, ugn.name gender, u.personal_data_agreement, u.mailing, 
                u.mailing_type_id, tt.name mailing_type, u.created, u.updated
            FROM " . self::$db_prefix . self::$db_table . " u 
            LEFT JOIN " . self::$db_prefix . "auth.user_groups ug ON u.group_id = ug.id 
            LEFT JOIN " . self::$db_prefix . "auth.user_genders ugn ON u.gender_id = ugn.id 
            LEFT JOIN " . self::$db_prefix . "simptalk.text_types tt ON u.mailing_type_id = tt.id 
            LEFT JOIN " . self::$db_prefix . "auth.user_blocks ub ON u.id = ub.user_id AND ub.expire > NOW() 
            WHERE u.id = :id {$activity}";

        $data = $db->query($object ? static::class : null);
        return !empty($data) ? array_shift($data) : null;
    }

//    public static function getLast(int $user_id, $friend_id, int $start = null, int $limit = null, bool $active = true, $object = true)
//    {
//        $activity = !empty($active) ? 'AND active IS NOT NULL ' : '';
//        $start_msg = !empty($start) ? 'AND id > :start ' : '';
//        $quantity = !empty($limit) ? "LIMIT {$limit}" : '';
//
//        $params = [
//            ':user_id' => $user_id,
//            ':friend_id' => $friend_id,
//        ];
//        if (!empty($start)) $params['start'] = $start;
//
//        $sql = "
//            SELECT *
//                FROM (
//                    SELECT
//                        id, from_user_id message_from, to_user_id message_to, message, created, updated
//                    FROM messages
//                    WHERE
//                        (from_user_id = :user_id OR from_user_id = :friend_id) AND
//                        (to_user_id = :user_id OR to_user_id = :friend_id)
//                        {$start_msg}
//                        {$activity}
//                    ORDER BY created DESC, id DESC
//                    {$quantity}
//                ) m
//            ORDER BY m.created, m.id";
//
//        $db = new Db();
//        $data = $db->query($sql, $params, $object ? static::class : null);
//        return $data ?? false;
//    }

//    public static function saveMessage($message_from, $message_to, $message)
//    {
//        $params = [
//            ':message_from' => $message_from,
//            ':message_to' => $message_to,
//            ':message' => $message
//        ];
//        $sql = "
//            INSERT INTO messages
//                (from_user_id, to_user_id, message)
//            VALUES
//                (:message_from, :message_to, :message)
//        ";
//
//        $db = new Db();
//        return $db->execute($sql, $params);
//    }

//    public static function checkData($friend, $message)
//    {
//        return self::checkUser($friend) && self::checkMessage($message);
//    }

//    public static function checkUser($friend)
//    {
//        return !empty($friend->id);
//    }

//    public static function checkMessage($message)
//    {
//        return !empty($message);
//    }
}
