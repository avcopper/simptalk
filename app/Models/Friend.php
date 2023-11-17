<?php
namespace Models;

use System\Db;

/**
 * Class User
 * @package App\Models
 */
class Friend extends Model
{
    protected static $db_table = 'mesigo.users';

    public $id;
    public $active;
    public $blocked;
    public $locked;
    public $need_request;
    public $login;
    public $email;
    public $show_email;
    public $phone;
    public $show_phone;
    public $name;
    public $second_name;
    public $last_name;
    public $gender_id;
    public $timezone;

    /**
     * Возвращает пользователя по id (!+)
     */
    public static function getById(int $id, ?array $params = [])
    {
        $params += ['active' => true, 'object' => false];
        $prefix = self::$db_prefix;
        $table = self::$db_table;

        $db = Db::getInstance();
        $active = !empty($params['active']) ? 'AND u.active IS NOT NULL AND u.blocked IS NULL AND ub.expire IS NULL AND ug.active IS NOT NULL' : '';
        $db->params = ['id' => $id];
        $db->sql = "
            SELECT 
                u.id, u.active, u.blocked, u.locked, u.need_request, ub.expire, u.login, 
                u.email, u.show_email, u.phone, u.show_phone, 
                u.name,  u.second_name, u.last_name, u.gender_id, ugn.name gender, u.timezone 
            FROM {$prefix}{$table} u 
            LEFT JOIN {$prefix}mesigo.user_groups ug ON u.group_id = ug.id 
            LEFT JOIN {$prefix}mesigo.user_genders ugn ON u.gender_id = ugn.id 
            LEFT JOIN {$prefix}mesigo.text_types tt ON u.mailing_type_id = tt.id 
            LEFT JOIN {$prefix}mesigo.user_blocks ub ON u.id = ub.user_id AND ub.expire > NOW() 
            WHERE u.id = :id {$active}";

        $data = $db->query(!empty($params['object']) ? static::class : null);
        return !empty($data) ? array_shift($data) : null;
    }

    /**
     * Возвращает пользователя по логину (!+)
     */
    public static function getByLogin(string $login, ?array $params = [])
    {
        $params += ['active' => true, 'object' => false];
        $prefix = self::$db_prefix;
        $table = self::$db_table;

        $db = Db::getInstance();
        $active = !empty($params['active']) ? 'AND u.active IS NOT NULL AND u.blocked IS NULL AND ub.expire IS NULL AND ug.active IS NOT NULL' : '';
        $db->params = ['login' => $login];
        $db->sql = "
            SELECT 
                u.id, u.active, u.blocked, u.locked, u.need_request, ub.expire, u.login, 
                u.email, u.show_email, u.phone, u.show_phone, 
                u.name,  u.second_name, u.last_name, u.gender_id, ugn.name gender, u.timezone 
            FROM {$prefix}{$table} u 
            LEFT JOIN {$prefix}mesigo.user_groups ug ON u.group_id = ug.id 
            LEFT JOIN {$prefix}mesigo.user_genders ugn ON u.gender_id = ugn.id 
            LEFT JOIN {$prefix}mesigo.text_types tt ON u.mailing_type_id = tt.id 
            LEFT JOIN {$prefix}mesigo.user_blocks ub ON u.id = ub.user_id AND ub.expire > NOW() 
            WHERE u.login = :login {$active}";

        $data = $db->query(!empty($params['object']) ? static::class : null);
        return !empty($data) ? array_shift($data) : null;
    }

    /**
     *
     */
    public static function searchByLogin(string $login, ?array $params = [])
    {
        $params += ['active' => true, 'object' => false];
        $prefix = self::$db_prefix;
        $table = self::$db_table;

        $db = Db::getInstance();
        $notSelf = !empty($params['not_user_id']) && is_numeric($params['not_user_id']) ? 'AND u.id <> :id' : '';
        $active = !empty($params['active']) ? 'AND u.active IS NOT NULL AND u.blocked IS NULL AND ub.expire IS NULL AND ug.active IS NOT NULL' : '';

        $db->params = ['login' => $login];
        if (!empty($user_id)) $db->params['id'] = $user_id;

        $db->sql = "
            SELECT 
                u.id, u.locked, u.need_request, u.login, u.name,  u.second_name, u.last_name 
            FROM {$prefix}{$table} u 
            LEFT JOIN {$prefix}mesigo.user_groups ug ON u.group_id = ug.id 
            LEFT JOIN {$prefix}mesigo.user_blocks ub ON u.id = ub.user_id AND ub.expire > NOW() 
            WHERE u.login LIKE CONCAT(:login, '', '%') {$notSelf} {$active} 
            ORDER BY u.id";

        $data = $db->query(!empty($params['object']) ? static::class : null);
        return $data ?: null;
    }
}
