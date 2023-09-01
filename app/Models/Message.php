<?php
namespace Models;

use System\Db;
use System\Crypt;

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

    /**
     * Возвращает список сообщений
     * @param int $user_id - id пользователя
     * @param int $friend_id - id собеседника
     * @param int $limit - лимит сообщений для выдачи
     * @param int $start - стартовое сообщение, от которого вести поиск
     * @param bool $active - активные сообщения
     * @param bool $object - возвращать объект/массив
     * @return array|null
     */
    public static function getAll(int $user_id, int $friend_id, int $limit = 0, int $start = 0, bool $active = true, $object = true)
    {
        $db = Db::getInstance();
        $activity = !empty($active) ? 'AND active IS NOT NULL' : '';
        $start_msg = !empty($start) ? 'AND id > :start ' : '';
        $quantity = !empty($limit) ? "LIMIT {$limit}" : '';

        $db->params = [
            ':user_id' => $user_id,
            ':friend_id' => $friend_id,
        ];
        if (!empty($start)) $db->params['start'] = $start;

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
                        {$start_msg} 
                    ORDER BY created DESC, id DESC 
                    {$quantity}
                ) m 
            ORDER BY m.created, m.id";

        $data = $db->query($object ? static::class : null);
        return $data ?? null;
    }

    /**
     * Сохраняет сообщение
     * @param \Entity\User $user - пользователь
     * @param int $message_to - id собеседника
     * @param string $message - сообщение
     * @return bool|int
     */
    public static function saveMessage(\Entity\User $user, int $message_to, string $message)
    {
        $message = strip_tags(nl2br(trim($message)), '<br>');
        $msg = new self();
        $msg->from_user_id = $user->id;
        $msg->to_user_id = $message_to;
        $msg->message = (new Crypt($user->publicKey, $user->privateKey))->encryptByPrivateKey($message);
        return $msg->save();
    }

    /**
     * Проверяет данные для отправки сообщения
     * @param \Entity\Friend $friend - собеседник
     * @param string $message
     * @return bool
     */
    public static function checkData(\Entity\Friend $friend, string $message)
    {
        return self::checkUser($friend) && self::checkMessage($message);
    }

    /**
     * Проверяет собеседника и возможность писать ему сообщения
     * @param \Entity\Friend $friend - собеседник
     * @return bool
     */
    public static function checkUser(\Entity\Friend $friend)
    {
        return !empty($friend->id) && self::canMessageUser($friend->id);
    }

    /**
     * Проверяет возможность писать сообщения собеседнику TODO доделать это говно
     * @param int $friend_id - id собеседника
     * @return bool
     */
    public static function canMessageUser(int $friend_id)
    {
        return true;
    }

    /**
     * Проверяет сообщение
     * @param $message - сообщение
     * @return bool
     */
    public static function checkMessage($message)
    {
        return !empty($message);
    }
}
