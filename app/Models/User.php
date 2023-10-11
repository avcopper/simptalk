<?php
namespace Models;

use DateTime;
use System\Db;
use System\Auth;
use Exceptions\DbException;
use Exceptions\UserException;
use Models\User as ModelUser;

/**
 * Class User
 * @package App\Models
 */
class User extends Model
{
    const MAX_COUNT_ATTEMPT = 5;

    protected static $db_table = 'mesigo.users';

    public $id;
    public $active = 1;
    public $blocked = null;
    public $locked = null;
    public $need_request = 1;
    public $group_id = 2; // группа "Пользователи"
    public $login;
    public $password;
    public $pin = null;
    public $e_pin = null;
    public $email = null;
    public $show_email = null;
    public $phone = null;
    public $show_phone = null;
    public $name = null;
    public $second_name = null;
    public $last_name = null;
    public $gender_id = 1;
    public $personal_data_agreement = 1;
    public $mailing = null; // подписание на рассылку
    public $mailing_type_id = 2; // тип рассылки html
    public $timezone = null;
    public $created;
    public $updated = null;

    /**
     * Возвращает пользователя по id (!+)
     */
    public static function getById(int $id, bool $active = true, $object = false)
    {
        $db = Db::getInstance();
        $activity = !empty($active) ? 'AND u.active IS NOT NULL AND u.blocked IS NULL AND ub.expire IS NULL AND ug.active IS NOT NULL' : '';
        $db->params = ['id' => $id];
        $db->sql = "
            SELECT 
                u.id, u.active, u.blocked, u.locked, u.need_request, ub.expire, u.group_id, ug.name group_name, 
                u.login, u.password, u.pin, u.e_pin, u.email, u.show_email, u.phone, u.show_phone, 
                u.name,  u.second_name, u.last_name, u.gender_id, ugn.name gender, u.personal_data_agreement, u.mailing, 
                u.mailing_type_id, tt.name mailing_type, u.timezone, u.created, u.updated 
            FROM " . self::$db_prefix . self::$db_table . " u 
            LEFT JOIN " . self::$db_prefix . "mesigo.user_groups ug ON u.group_id = ug.id 
            LEFT JOIN " . self::$db_prefix . "mesigo.user_genders ugn ON u.gender_id = ugn.id 
            LEFT JOIN " . self::$db_prefix . "mesigo.text_types tt ON u.mailing_type_id = tt.id 
            LEFT JOIN " . self::$db_prefix . "mesigo.user_blocks ub ON u.id = ub.user_id AND ub.expire > NOW() 
            WHERE u.id = :id {$activity}";

        $data = $db->query($object ? static::class : null);
        return !empty($data) ? array_shift($data) : null;
    }

    /**
     * Возвращает пользователя по логину (!+)
     */
    public static function getByLogin(string $login, bool $active = true, $object = false)
    {
        $db = Db::getInstance();
        $activity = !empty($active) ? 'AND u.active IS NOT NULL AND u.blocked IS NULL AND ub.expire IS NULL AND ug.active IS NOT NULL' : '';
        $db->params = ['login' => $login];
        $db->sql = "
            SELECT 
                u.id, u.active, u.blocked, u.locked, u.need_request, ub.expire, u.group_id, ug.name group_name, 
                u.login, u.password, u.pin, u.e_pin, u.email, u.show_email, u.phone, u.show_phone, 
                u.name,  u.second_name, u.last_name, u.gender_id, ugn.name gender, u.personal_data_agreement, u.mailing, 
                u.mailing_type_id, tt.name mailing_type, u.timezone, u.created, u.updated 
            FROM " . self::$db_prefix . self::$db_table . " u 
            LEFT JOIN " . self::$db_prefix . "mesigo.user_groups ug ON u.group_id = ug.id 
            LEFT JOIN " . self::$db_prefix . "mesigo.user_genders ugn ON u.gender_id = ugn.id 
            LEFT JOIN " . self::$db_prefix . "mesigo.text_types tt ON u.mailing_type_id = tt.id 
            LEFT JOIN " . self::$db_prefix . "mesigo.user_blocks ub ON u.id = ub.user_id AND ub.expire > NOW() 
            WHERE u.login = :login {$activity}";

        $data = $db->query($object ? static::class : null);
        return !empty($data) ? array_shift($data) : null;
    }

    /**
     * Возвращает пользователя по токену (!+)
     */
    public static function getByToken(?string $token, bool $active = true, bool $object = false)
    {
        if ($token === null) return null;

        $db = Db::getInstance();
        $activity = !empty($active) ? 'AND u.active IS NOT NULL AND u.blocked IS NULL AND ub.expire IS NULL AND ug.active IS NOT NULL AND us.expire > NOW()' : '';
        $db->params = ['token' => $token];
        $db->sql = "
            SELECT 
                u.id, u.active, u.blocked, u.locked, u.need_request, ub.expire, u.group_id, ug.name group_name, 
                u.login, u.password, u.pin, u.e_pin, u.email, u.show_email, u.phone, u.show_phone, 
                u.name,  u.second_name, u.last_name, u.gender_id, ugn.name gender, u.personal_data_agreement, u.mailing, 
                u.mailing_type_id, tt.name mailing_type, u.timezone, u.created, u.updated 
            FROM " . self::$db_prefix . "mesigo.user_sessions us 
            LEFT JOIN " . self::$db_prefix . self::$db_table . " u ON us.login = u.login 
            LEFT JOIN " . self::$db_prefix . "mesigo.user_groups ug ON u.group_id = ug.id 
            LEFT JOIN " . self::$db_prefix . "mesigo.user_genders ugn ON u.gender_id = ugn.id 
            LEFT JOIN " . self::$db_prefix . "mesigo.text_types tt ON u.mailing_type_id = tt.id 
            LEFT JOIN " . self::$db_prefix . "mesigo.user_blocks ub ON u.id = ub.user_id AND ub.expire > NOW() 
            WHERE us.token = :token {$activity}";

        $data = $db->query($object ? static::class : null);
        return !empty($data) ? array_shift($data) : null;
    }

    /**
     * Возвращает текущего пользователя (!+)
     * @return \Entity\User|null
     */
    public static function getCurrent()
    {
        return !empty($_SESSION['user']) ?
            (new \Entity\User())->init($_SESSION['user']) :
            (new \Entity\User())->init(self::getByToken(self::getUserToken()));
    }

    /**
     * Проверяет авторизацию пользователя (!+)
     * @return bool
     */
    public static function isAuthorized()
    {
        $token = self::getUserToken();
        $device = self::getUserDevice();
        if (empty($token) || empty($device)) return false;

        return Auth::checkAuthorization($token, $device);
    }

    /**
     * Возвращает текущий сессионный токен (!+)
     * @return string|null
     */
    public static function getUserToken()
    {
        return !empty($_SESSION['token']) ? $_SESSION['token'] : (!empty($_COOKIE['token']) ? $_COOKIE['token'] : null);
    }

    /**
     * Возвращает данные пользовательского устройства (!+)
     * @return array
     */
    public static function getUserDevice()
    {
        return [
            'device' => $_SERVER['HTTP_USER_AGENT'],
            'ip' => $_SERVER['REMOTE_ADDR'],
            'serviceId' => UserSession::SERVICE_SITE
        ];
    }

    /**
     * Авторизация (!+)
     * @throws UserException|DbException
     */
    public static function authorize($login, $password, $remember)
    {
        Auth::checkUser($login, $password);

        $auth = new Auth();
        $auth->user = \Entity\User::get(['login' => $login]);

        if (!empty($auth->user->id)) { // найден активный пользователь
            $auth->userSession = self::setUserSession($auth->user);
            $countFailedAttempts = UserSession::getCountFailedAttempts($login);

            if ($countFailedAttempts < ModelUser::MAX_COUNT_ATTEMPT) { // меньше 5 активных попыток входа
                if (password_verify($password, $auth->user->password)) $auth->login($remember);
                else {
                    $auth->userSession->comment = Auth::WRONG_LOGIN_PASSWORD;
                    $auth->userSession->save();
                    throw new UserException(Auth::WRONG_LOGIN_PASSWORD, 401);
                }

            } else {
                UserSession::clearFailedAttempts($auth->user->login);
                $auth->user->block(UserBlock::INTERVAL_DAY, Auth::TOO_MANY_FAILED_ATTEMPTS);
                throw new UserException(Auth::TOO_MANY_FAILED_ATTEMPTS, 401);
            }
        }

        throw new UserException(Auth::USER_NOT_FOUND, 401);
    }

    /**
     * Выход
     * @return bool
     */
    public static function logout(): bool
    {
        unset($_SESSION['token']);
        unset($_SESSION['user']);
        setcookie('token', '', time() - UserSession::LIFE_TIME, '/', SITE_URL, 0);
        setcookie('PHPSESSID', '', time() - UserSession::LIFE_TIME, '/', SITE_URL, 0);
        session_destroy();
        return UserSession::deleteCurrent();
    }

    /**
     * Создает пользовательскую сессию (!+)
     * @param $user
     * @return \Entity\UserSession
     */
    protected static function setUserSession($user)
    {
        $userSession = new \Entity\UserSession();
        $userSession->isActive = 1;
        $userSession->login = $user->login;
        $userSession->userId = $user->id;
        $userSession->serviceId = UserSession::SERVICE_SITE;
        $userSession->ip = $_SERVER['REMOTE_ADDR'];
        $userSession->device = $_SERVER['HTTP_USER_AGENT'];
        $userSession->logIn = new DateTime();
        $userSession->expire = null;
        $userSession->token = null;
        $userSession->comment = null;
        return $userSession;
    }
}
