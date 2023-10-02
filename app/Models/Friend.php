<?php
namespace Models;

use System\Db;

/**
 * Class User
 * @package App\Models
 */
class Friend extends Model
{
    protected static $db_table = 'users.users';

    public $id;
    public $active;
    public $blocked;
    public $login;
    public $name;
    public $second_name;
    public $last_name;
    public $gender_id;

    /**
     * Возвращает пользователя по id (!+)
     */
    public static function getById(int $id, bool $active = true, $object = true)
    {
        $db = Db::getInstance();
        $activity = !empty($active) ? 'AND u.active IS NOT NULL AND u.blocked IS NULL AND ub.expire IS NULL AND ug.active IS NOT NULL' : '';
        $db->params = ['id' => $id];
        $db->sql = "
            SELECT 
                u.id, u.active, u.blocked, ub.expire, u.login, 
                u.name,  u.second_name, u.last_name, u.gender_id, ugn.name gender 
            FROM " . self::$db_prefix . self::$db_table . " u 
            LEFT JOIN " . self::$db_prefix . "users.user_groups ug ON u.group_id = ug.id 
            LEFT JOIN " . self::$db_prefix . "users.user_genders ugn ON u.gender_id = ugn.id 
            LEFT JOIN " . self::$db_prefix . "users.text_types tt ON u.mailing_type_id = tt.id 
            LEFT JOIN " . self::$db_prefix . "users.user_blocks ub ON u.id = ub.user_id AND ub.expire > NOW() 
            WHERE u.id = :id {$activity}";

        $data = $db->query($object ? static::class : null);
        return !empty($data) ? array_shift($data) : null;
    }

    /**
     * Возвращает пользователя по логину (!+)
     */
    public static function getByLogin(string $login, bool $active = true, $object = true)
    {
        $db = Db::getInstance();
        $activity = !empty($active) ? 'AND u.active IS NOT NULL AND u.blocked IS NULL AND ub.expire IS NULL AND ug.active IS NOT NULL' : '';
        $db->params = ['login' => $login];
        $db->sql = "
            SELECT 
                u.id, u.active, u.blocked, u.group_id, ug.name group_name, u.login, u.password, u.pin, u.e_pin, u.email, u.phone, 
                u.name,  u.second_name, u.last_name, u.gender_id, ugn.name gender, u.personal_data_agreement, u.mailing, 
                u.mailing_type_id, tt.name mailing_type, u.created, u.updated
            FROM " . self::$db_prefix . self::$db_table . " u 
            LEFT JOIN " . self::$db_prefix . "users.user_groups ug ON u.group_id = ug.id 
            LEFT JOIN " . self::$db_prefix . "users.user_genders ugn ON u.gender_id = ugn.id 
            LEFT JOIN " . self::$db_prefix . "users.text_types tt ON u.mailing_type_id = tt.id 
            LEFT JOIN " . self::$db_prefix . "users.user_blocks ub ON u.id = ub.user_id AND ub.expire > NOW() 
            WHERE u.login = :login {$activity}";

        $data = $db->query($object ? static::class : null);
        return !empty($data) ? array_shift($data) : null;
    }

    /**
     *
     */
    public static function searchByLogin(string $login, int $user_id, bool $active = true, $object = true)
    {
        $db = Db::getInstance();
        $notSelf = !empty($user_id) ? 'AND u.id <> :id' : '';
        $activity = !empty($active) ? 'AND u.active IS NOT NULL AND u.blocked IS NULL AND ub.expire IS NULL AND ug.active IS NOT NULL' : '';

        $db->params = ['login' => $login];
        if (!empty($user_id)) $db->params['id'] = $user_id;

        $db->sql = "
            SELECT 
                u.id, u.login, u.name,  u.second_name, u.last_name 
            FROM " . self::$db_prefix . self::$db_table . " u 
            LEFT JOIN " . self::$db_prefix . "users.user_groups ug ON u.group_id = ug.id 
            LEFT JOIN " . self::$db_prefix . "users.user_blocks ub ON u.id = ub.user_id AND ub.expire > NOW() 
            WHERE u.login LIKE CONCAT(:login, '', '%') {$notSelf} {$activity} 
            ORDER BY u.id";

        $data = $db->query($object ? static::class : null);
        return $data ?: null;
    }
}
