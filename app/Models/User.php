<?php
namespace Models;

use DateTime;
use System\Api;
use System\Auth;
use System\Db;
use Exceptions\DbException;
use Exceptions\UserException;
use Models\User as ModelUser;
use Models\UserSession as ModelUserSession;

/**
 * Class User
 * @package App\Models
 */
class User extends Model
{
    const MAX_COUNT_ATTEMPT = 5;

    protected static $table = 'auth.users';

    public $id;
    public $active;
    public $blocked;
    public $group_id = 2; // группа "Пользователи"
    public $login;
    public $password;
    public $pin;
    public $e_pin;
    public $email;
    public $phone;
    public $name;
    public $second_name;
    public $last_name;
    public $gender_id;
    public $personal_data_agreement;
    public $mailing = 1; // подписание на рассылку
    public $mailing_type_id = 2; // тип рассылки html
    public $created;
    public $updated;

    /**
     * Возвращает пользователя по id (+++)
     */
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
            FROM " . self::$prefix . self::$table . " u 
            LEFT JOIN " . self::$prefix . "auth.user_groups ug ON u.group_id = ug.id 
            LEFT JOIN " . self::$prefix . "auth.user_genders ugn ON u.gender_id = ugn.id 
            LEFT JOIN " . self::$prefix . "simptalk.text_types tt ON u.mailing_type_id = tt.id 
            LEFT JOIN " . self::$prefix . "auth.user_blocks ub ON u.id = ub.user_id AND ub.expire > NOW() 
            WHERE u.id = :id {$activity}";

        $data = $db->query($object ? static::class : null);
        return !empty($data) ? array_shift($data) : null;
    }

    /**
     * Возвращает пользователя по логину (+++)
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
            FROM " . self::$prefix . self::$table . " u 
            LEFT JOIN " . self::$prefix . "auth.user_groups ug ON u.group_id = ug.id 
            LEFT JOIN " . self::$prefix . "auth.user_genders ugn ON u.gender_id = ugn.id 
            LEFT JOIN " . self::$prefix . "simptalk.text_types tt ON u.mailing_type_id = tt.id 
            LEFT JOIN " . self::$prefix . "auth.user_blocks ub ON u.id = ub.user_id AND ub.expire > NOW() 
            WHERE u.login = :login {$activity}";

        $data = $db->query($object ? static::class : null);
        return !empty($data) ? array_shift($data) : null;
    }

    /**
     * Возвращает пользователя по токену (+++)
     */
    public static function getByToken(?string $token, bool $active = true, bool $object = true)
    {
        if ($token === null) return null;

        $db = Db::getInstance();
        $activity = !empty($active) ? 'AND u.active IS NOT NULL AND u.blocked IS NULL AND ub.expire IS NULL AND ug.active IS NOT NULL AND us.expire > NOW()' : '';
        $db->params = ['token' => $token];
        $db->sql = "
            SELECT 
                u.id, u.active, u.blocked, u.group_id, ug.name group_name, u.login, u.password, u.pin, u.e_pin, u.email, u.phone, 
                u.name,  u.second_name, u.last_name, u.gender_id, ugn.name gender, u.personal_data_agreement, u.mailing, 
                u.mailing_type_id, tt.name mailing_type, u.created, u.updated
            FROM " . self::$prefix . "auth.user_sessions  us 
            LEFT JOIN " . self::$prefix . self::$table . " u ON us.login = u.login 
            LEFT JOIN " . self::$prefix . "auth.user_groups ug ON u.group_id = ug.id 
            LEFT JOIN " . self::$prefix . "auth.user_genders ugn ON u.gender_id = ugn.id 
            LEFT JOIN " . self::$prefix . "simptalk.text_types tt ON u.mailing_type_id = tt.id 
            LEFT JOIN " . self::$prefix . "auth.user_blocks ub ON u.id = ub.user_id AND ub.expire > NOW() 
            WHERE us.token = :token {$activity}";

        $data = $db->query($object ? static::class : null);
        return !empty($data) ? array_shift($data) : null;
    }

    /**
     * Получает текущего пользователя (+++)
     * @return mixed|null
     * @throws UserException
     */
    public static function getCurrent()
    {
        $token = defined('API') ? self::getRequestToken() : self::getUserToken();
        return !empty($_SESSION['user']) ? $_SESSION['user'] : self::getByToken($token);
    }

    /**
     * Возвращает текущий сессионный токен (+++)
     * @return mixed|null
     */
    public static function getUserToken()
    {
        return !empty($_SESSION['token']) ? $_SESSION['token'] : (!empty($_COOKIE['token']) ? $_COOKIE['token'] : null);
    }

    /**
     * Возвращает авторизационный токен из тела запроса (+++)
     * @return mixed
     * @throws UserException
     */
    public static function getRequestToken()
    {
        if (empty($_SERVER['HTTP_AUTHORIZATION']) ||
            !preg_match('/Bearer\s(\S+)/', $_SERVER['HTTP_AUTHORIZATION'], $matches) ||
            empty($matches[1]))
        {
            throw new UserException(Auth::WRONG_TOKEN);
        }

        return $matches[1];
    }

    /**
     * Возвращает данные пользовательского устройства (+++)
     * @return array
     */
    public static function getUserDevice()
    {
        return [
            'device' => base64_encode($_SERVER['HTTP_USER_AGENT']),
            'ip' => base64_encode($_SERVER['REMOTE_ADDR']),
            'serviceId' => 2
        ];
    }

    /**
     * Возвращает данные пользовательского устройства из запроса к апи (+++)
     * @return mixed
     */
    public static function getUserDeviceFromApi()
    {
        return json_decode(file_get_contents("php://input"), true);
    }

    /**
     * Возвращает данные пользователя из запроса
     * @param $userData
     * @return array
     */
    public static function getUserFromApi($userData)
    {
        $user = explode(':', base64_decode($userData['user']));
        return [
            'login' => $user[0] ?? null,
            'password' => $user[1] ?? null
        ];
    }

    /**
     * Проверяет авторизован ли пользователь (+++)
     * @return bool
     * @throws UserException
     */
    public static function isAuthorized()
    {
        return (new Api())->validate();
    }

    /**
     * Авторизация (+++)
     * @param string $login - логин
     * @param string $password - пароль
     * @param bool $remember - запомнить пользователя
     * @param array $userData - пользовательское устройство
     * @throws UserException|DbException
     */
    public static function authorization(string $login, string $password, bool $remember = false, $userData = [])
    {
        Auth::checkUser($login, $password);
        Auth::checkDeviceData($userData);

        $auth = new Auth();
        $auth->user = \Entity\User::get(['login' => $login]);
        $message = Auth::NOT_AUTHORIZED;

        if (!empty($auth->user->id)) { // найден активный пользователь
            $countFailedAttempts = UserSession::getCountFailedAttempts($login);

            if ($countFailedAttempts < ModelUser::MAX_COUNT_ATTEMPT) { // меньше 5 активных попыток входа
                $auth->userSession = self::setUserSession($auth->user, $userData);

                if (password_verify($password, $auth->user->ePin) && $userData['serviceId'] === UserSession::SERVICE_MOBILE)
                    $auth->loginEmergencyPin();

                elseif (password_verify($password, $auth->user->pin) && $userData['serviceId'] === UserSession::SERVICE_MOBILE &&
                    Auth::checkAuthorization(User::getRequestToken(), $userData)) $auth->loginPin();

                elseif (password_verify($password, $auth->user->password)) $auth->login($remember);

                else $auth->userSession->comment = Auth::WRONG_LOGIN_PASSWORD;

                $auth->userSession->save();
            }
            else {
                $message = Auth::TOO_MANY_FAILED_ATTEMPTS;
                UserSession::clearFailedAttempts($auth->user->login);
                $auth->user->block(UserBlock::INTERVAL_DAY, $message);
            }
        }
        else $message = Auth::USER_NOT_FOUND;

        throw new UserException($message, 401);
    }

    /**
     * Выход (+)
     * @return bool
     */
    public static function logout(): bool
    {
        unset($_SESSION['token']);
        unset($_SESSION['user']);
        setcookie('token', '', time() - UserSession::LIFE_TIME, '/', SITE, 0);
        return UserSession::deleteCurrent();
    }

    /**
     * Создает пользовательскую сессию (+++)
     * @param $user
     * @param $userData
     * @return \Entity\UserSession
     */
    protected static function setUserSession($user, $userData)
    {
        $userSession = new \Entity\UserSession();
        $userSession->isActive = 1;
        $userSession->login = $user->login;
        $userSession->userId = $user->id;
        $userSession->serviceId = $userData['serviceId'];
        $userSession->ip = base64_decode($userData['ip']);
        $userSession->device = base64_decode($userData['device']);
        $userSession->logIn = new DateTime();
        $userSession->expire = null;
        $userSession->token = null;
        $userSession->comment = null;
        return $userSession;
    }






















































































    public function filter_id($id)
    {
        return (int)$id;
    }

    public function filter_group_id($value)
    {
        return (int)$value;
    }

    public function filter_name($text)
    {
        return strip_tags(trim($text));
    }

    public function filter_email($text)
    {
        return strip_tags(trim($text));
    }

    public function filter_phone($value)
    {
        return (int)$value;
    }

    public function filter_password($text)
    {
        return strip_tags(trim($text));
    }
}
