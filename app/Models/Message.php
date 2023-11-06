<?php
namespace Models;

use System\Db;
use System\Crypt;

class Message extends Model
{
    protected static $db_table = 'mesigo.messages';

    public $id;
    public $active = 1;
    public $is_read = null;
    public $from_user_id;
    public $to_user_id;
    public $is_file = null;
    public $is_audio = null;
    public $is_image = null;
    public $message;
    public $created;
    public $updated;

    /**
     * @param $params
     * $params['user_id'] - id пользователя
     * $params['friend_id'] - id собеседника
     * $params['limit'] - лимит сообщений для выдачи
     * $params['start'] - стартовое сообщение, от которого вести поиск
     * $params['active'] - только активные сообщения
     * $params['sort'] - поле сортировки
     * $params['order'] - направление сортировки
     * @return null
     */
    public static function getList(?array $params = [])
    {
        $params += [
            'active' => true,
            'object' => false
        ];

        $db = Db::getInstance();
        $friend = !empty($params['friend_id']) ? 'AND (m.from_user_id = :friend_id OR m.to_user_id = :friend_id)' : '';
        $active = !empty($params['active']) ? 'AND m.active IS NOT NULL' : '';
        $start = !empty($params['start']) ? 'AND m.id > :start ' : '';
        $sort = !empty($params['sort']) ? "m.{$params['sort']}" : 'm.id';
        $order = !empty($params['order']) ? strtoupper($params['order']) : 'ASC';
        $limit = !empty($params['limit']) ? "LIMIT {$params['limit']}" : '';

        $db->params = ['user_id' => $params['user_id']];
        if (!empty($params['friend_id'])) $db->params['friend_id'] = $params['friend_id'];
        if (!empty($params['start'])) $db->params['start'] = $params['start'];

        $db->sql = "
            SELECT 
                m.id, m.active, m.is_read, m.from_user_id, m.to_user_id, u.id friend_id, u.login friend_login, 
                u.name friend_name, u.last_name friend_last_name, m.is_file, m.is_audio, m.is_image, m.message, m.created, m.updated 
            FROM " . self::$db_prefix . self::$db_table . " m 
            LEFT JOIN  " . self::$db_prefix . "mesigo.users u ON u.id = IF(m.from_user_id != :user_id, m.from_user_id, m.to_user_id) 
            WHERE 
                (m.from_user_id = :user_id OR m.to_user_id = :user_id) 
                {$friend} 
                {$active} 
                {$start} 
            ORDER BY {$sort} {$order} 
            {$limit}";

        $data = $db->query(!empty($params['object']) ? static::class : null);
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

    public static function saveFile(\Entity\User $user, int $message_to, array $file)
    {echo json_encode($file);die;
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

    public static function checkFile(array $file)
    {
        return self::checkFileError($file['error']) && self::checkFileSize($file['size']) &&
            self::checkTempFile($file['tmp_name']) && self::checkMimeType($file['type']);
    }

    public static function checkFileError($error)
    {
        return $error === 0;
    }

    public static function checkFileSize($file)
    {
        return $file > 0 && $file < 10000000;
    }

    public static function checkTempFile($file)
    {
        return !empty($file) && is_file($file);
    }

    public static function checkMimeType($type)
    {
        return in_array($type, ['audio/wav']);
    }
}
