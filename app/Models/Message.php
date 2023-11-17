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
    public ?int $file_id = null;
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
        $params += ['active' => true, 'object' => false];
        $prefix = self::$db_prefix;
        $table = self::$db_table;

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
                m.id, m.active, m.is_read, m.from_user_id, m.to_user_id, 
                u.id friend_id, u.login friend_login, u.name friend_name, u.last_name friend_last_name, 
                f.id file_id, f.name file_name, f.link file_link, f.created file_date, 
                m.message, m.created, m.updated 
            FROM {$prefix}{$table} m 
            LEFT JOIN {$prefix}mesigo.users u ON u.id = IF(m.from_user_id != :user_id, m.from_user_id, m.to_user_id) 
            LEFT JOIN {$prefix}mesigo.files f ON f.id = m.file_id AND f.active IS NOT NULL 
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
     * Сохраняет сообщение в БД
     * @param \Entity\User $user - пользователь
     * @param int $message_to - id собеседника
     * @param ?string $message - сообщение
     * @return bool|int
     */
    public static function saveMessage(\Entity\User $user, int $message_to, ?string $message = null, ?int $fileId = null)
    {
        $message = strip_tags(nl2br(trim($message)), '<br>');
        $msg = new self();
        $msg->from_user_id = $user->getId();
        $msg->to_user_id = $message_to;
        $msg->file_id = $fileId;
        $msg->message = !empty($message) ? (new Crypt($user->getPublicKey(), $user->getPrivateKey()))->encryptByPrivateKey($message) : null;
        return $msg->save();
    }

    /**
     * Проверяет данные для отправки сообщения
     * @param \Entity\Friend $friend - собеседник
     * @param ?string $message
     * @param ?int $fileId
     * @return bool
     */
    public static function checkData(\Entity\Friend $friend, ?string $message = null, ?int $fileId = null)
    {
        return self::checkUser($friend) && (self::checkMessage($message) || self::checkFileId($fileId));
    }

    /**
     * Проверяет собеседника и возможность писать ему сообщения
     * @param \Entity\Friend $friend - собеседник
     * @return bool
     */
    public static function checkUser(\Entity\Friend $friend)
    {
        return !empty($friend->getId()) && self::canMessageUser($friend->getId());
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

    /**
     * Проверяет загруженный файл
     * @param $fileId - id файла
     * @return bool
     */
    public static function checkFileId($fileId)
    {
        return !empty($fileId);
    }
}
