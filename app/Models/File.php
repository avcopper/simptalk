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

    public static function getById(int $id, ?array $params = [])
    {
        $params += ['active' => true, 'object' => false];
        $prefix = self::$db_prefix;
        $table = self::$db_table;

        $db = Db::getInstance();
        $active = !empty($params['active']) ? 'AND f.active IS NOT NULL' : '';
        $db->params = ['id' => $id];

        $db->sql = "
            SELECT *
            FROM {$prefix}{$table} f 
            WHERE f.id = :id 
            {$active}";

        $data = $db->query(!empty($params['object']) ? static::class : null);
        return $data ? array_shift($data) : null;
    }

    public static function getByMessageId($message_id, ?array $params = [])
    {
        $params += ['active' => true, 'object' => false];
        $prefix = self::$db_prefix;
        $table = self::$db_table;

        $db = Db::getInstance();
        $active = !empty($params['active']) ? 'AND f.active IS NOT NULL AND m.active IS NOT NULL' : '';
        $db->params = ['message_id' => $message_id];

        $db->sql = "
            SELECT *
            FROM {$prefix}{$table} f 
            LEFT JOIN {$prefix}mesigo.messages m ON f.id = m.file_id
            WHERE m.id = :message_id 
            {$active}";

        $data = $db->query(!empty($params['object']) ? static::class : null);
        return $data ? array_shift($data) : null;
    }

    public static function isExistFileInMessages(int $user1, int $user2, int $fileId, $params = [])
    {
        $params += ['active' => true, 'object' => false];
        $prefix = self::$db_prefix;
        $table = self::$db_table;

        $db = Db::getInstance();
        $active = !empty($params['active']) ? 'AND m.active IS NOT NULL AND f.active IS NOT NULL' : '';
        $db->params = ['user1' => $user1, 'user2' => $user2, 'file_id' => $fileId];

        $db->sql = "
            SELECT m.id
            FROM {$prefix}mesigo.messages m
            JOIN {$prefix}{$table} f on f.id = m.file_id
            WHERE (m.from_user_id = :user1 OR m.to_user_id = :user1) AND 
                  m.file_id = :file_id {$active}";

        $data = $db->query(!empty($params['object']) ? static::class : null);

        return
            (!empty($params['object']) && !empty($data[0]->id)) || (empty($params['object']) && !empty($data[0]['id']));
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
        $fileDir = DIR_UPLOADS . DIRECTORY_SEPARATOR . $user->getId() . DIRECTORY_SEPARATOR . $currentDate;
        self::checkUploadDirectory($user->getId(), $fileDir);

        $uploadedFileInfo = pathinfo($file['name']);
        $uploadedFileName = $uploadedFileInfo['filename'];
        $uploadedFileExtension = $uploadedFileInfo['extension'];

        $savedFileName = self::getNewFileName($fileDir, $uploadedFileExtension);
        $savedFilePath = $fileDir . DIRECTORY_SEPARATOR . "{$savedFileName}.{$uploadedFileExtension}";
        
        if (move_uploaded_file($file['tmp_name'], $savedFilePath)) {
            $crypt = new Crypt($user->getPublicKey(), $user->getPrivateKey());
            $msg = new self();
            $msg->user_id = $user->getId();
            $msg->name = $crypt->encryptByPrivateKey("{$uploadedFileName}.{$uploadedFileExtension}");
            $msg->link = $crypt->encryptByPrivateKey("/uploads/{$user->getId()}/{$currentDate}/{$savedFileName}.{$uploadedFileExtension}");
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

    public static function checkUserFile(array $file)
    {
        return self::checkUserFileError($file['error']) && self::checkUserFileSize($file['size']) &&
            self::checkFile($file['tmp_name']) && self::checkMimeType($file['type']);
    }

    public static function checkUserFileError($error)
    {
        return $error === 0;
    }

    public static function checkUserFileSize($file)
    {
        return $file > 0 && $file < 50000000;
    }

    public static function checkFile($file)
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
