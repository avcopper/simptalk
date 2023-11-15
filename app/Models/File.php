<?php
namespace Models;

use System\Crypt;
use System\Db;

class File extends Model
{
    protected static $db_table = 'mesigo.files';

    public $id;
    public ?bool $active = true;
    public int $user_id;
    public ?int $album_id = null;
    public string $name;
    public string $link;
    public $created;
    public ?string $updated = null;

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
                m.id, m.active, m.is_read, m.from_user_id, m.to_user_id, 
                u.id friend_id, u.login friend_login, u.name friend_name, u.last_name friend_last_name, 
                f.id file_id, f.name file_name, f.link file_link, 
                m.message, m.created, m.updated 
            FROM " . self::$db_prefix . self::$db_table . " m 
            LEFT JOIN  " . self::$db_prefix . "mesigo.users u ON u.id = IF(m.from_user_id != :user_id, m.from_user_id, m.to_user_id) 
            LEFT JOIN  " . self::$db_prefix . "mesigo.files f ON f.id = m.file_id AND f.is_active IS NOT NULL 
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

    public static function getByMessageId($message_id, ?array $params = [])
    {
        $params += [
            'active' => true,
            'object' => false
        ];

        $db = Db::getInstance();
        $active = !empty($params['active']) ? 'AND f.active IS NOT NULL AND m.active IS NOT NULL' : '';
        $db->params = ['message_id' => $message_id];

        $db->sql = "
            SELECT *
            FROM talk_mesigo.files f 
            LEFT JOIN talk_mesigo.messages m ON f.id = m.file_id
            WHERE m.id = :message_id 
            {$active}";

        $data = $db->query(!empty($params['object']) ? static::class : null);
        return $data ? array_shift($data) : null;
    }

    /**
     * Сохраняет файл и делает запись о нем в БД
     * @param \Entity\User $user
     * @param int $message_to
     * @param array $file
     * @return bool|int
     */
    public static function saveFile(\Entity\User $user, int $message_to, array $file)
    {
        $currentDate = date('Y-m-d');
        $fileDir = DIR_UPLOADS . DIRECTORY_SEPARATOR . $user->id . DIRECTORY_SEPARATOR . $currentDate;
        self::checkUploadDirectory($user->id, $fileDir);

        $uploadedFileInfo = pathinfo($file['name']);
        $uploadedFileName = $uploadedFileInfo['filename'];
        $uploadedFileExtension = $uploadedFileInfo['extension'];

        $savedFileName = self::getNewFileName($fileDir, $uploadedFileExtension);
        $savedFilePath = $fileDir . DIRECTORY_SEPARATOR . "{$savedFileName}.{$uploadedFileExtension}";
        
        if (move_uploaded_file($file['tmp_name'], $savedFilePath)) {
            $crypt = new Crypt($user->publicKey, $user->privateKey);
            $msg = new self();
            $msg->user_id = $user->id;
            $msg->name = $crypt->encryptByPrivateKey("{$uploadedFileName}.{$uploadedFileExtension}");
            $msg->link = $crypt->encryptByPrivateKey("/uploads/{$user->id}/{$currentDate}/{$savedFileName}.{$uploadedFileExtension}");
            return $msg->save();
        }
        
        return false;
    }

    /**
     * Проверяет наличие директорий для загрузки и создает их
     * @param $user_id - id пользователя
     * @param $directory - требуемая директория
     * @return bool
     */
    private static function checkUploadDirectory($user_id, $directory)
    {
        $userDir = DIR_UPLOADS . DIRECTORY_SEPARATOR . $user_id;
        if (!is_dir($userDir)) mkdir($userDir);
        if (!is_dir($directory)) mkdir($directory);
        return true;
    }

    /**
     * Возвращает новое уникальное имя для загружаемого файла
     * @param $directory - директория для загрузки
     * @param $extension - расширение файла
     * @return string
     */
    private static function getNewFileName($directory, $extension)
    {
        $newFileName = md5(microtime());
        $newFilePath = $directory . DIRECTORY_SEPARATOR . "{$newFileName}.{$extension}";

        while (file_exists($newFilePath)) {
            $newFileName = md5(microtime());
            $newFilePath = $directory . DIRECTORY_SEPARATOR . "{$newFileName}.{$extension}";
        }

        return $newFileName;
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
        return $file > 0 && $file < 50000000;
    }

    public static function checkTempFile($file)
    {
        return !empty($file) && is_file($file);
    }

    public static function checkMimeType($type)
    {
        return self::isAudioFile($type) || self::isImageFile($type) || self::isUserFile($type);
    }

    public static function isAudioFile($type)
    {
        return in_array($type, ['audio/wav', 'audio/mpeg', 'audio/webm', 'audio/ogg', 'audio/webm', 'video/webm']);
    }

    public static function isImageFile($type)
    {
        return in_array($type, ['image/png', 'image/gif', 'image/jpeg', 'image/webp']);
    }

    public static function isUserFile($type)
    {
        return in_array($type, ['application/pdf', 'application/zip', 'application/x-rar', 'application/x-7z-compressed', 'application/x-compressed']);
    }
}
