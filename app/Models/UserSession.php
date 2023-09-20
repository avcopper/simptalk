<?php

namespace Models;

use System\Db;
use Firebase\JWT\JWT;
use Exceptions\DbException;
use System\Loggers\ErrorLogger;
use System\Loggers\AccessLogger;

class UserSession extends Model
{
    const SERVER = 'https://mesigo.net'; // сервер токена
    const SERVICE_MOBILE = 1; // сервис мобильный
    const SERVICE_SITE = 2; // сервис сайт
    const SERVICES = [self::SERVICE_MOBILE, self::SERVICE_SITE]; // сервисы, использующие авторизацию
    const LIFE_TIME = 60 * 60 * 24 * AUTH_DAYS; // время жизни токена
    const KEY = 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAl7rrc5Co4lgcrq6xVeMt'.
                't/RAJ9w0TZab8451RgSd+TyMncLazFZrJOOnL9/Sif3McH3wXzkMo0pjdIqEZi2j'.
                't3B4XlERPw4jI6WyrOIxPXPWtJ3q4POr+tiACEHwzG6lkTZucP3tecqWbpM6O86P'.
                '5DVRbAOp9ZSzGJ8rob1HfrhK+D7GJ8qWF3MmTE7zjup7QamC7dAabtcxUGA31NPn'.
                'HFFgQ111I6zx9AXtizSLeV+qH5Tf9BnnbdN2ayH+aqM20ERSt5xa7GGSSiqf41Of'.
                'H2mCo6UjM9m8vVbkaaw5hNapjhHILOgN5UVcXGI6b3XoyKZwYX0hTpnImydmDGUJ'.
                'XwIDAQAB'; // ключ для шифрования токена

    protected static $db_table = 'users.user_sessions';

    public $id;         // id сессии
    public $active;     // активность сессии
    public $login;      // login пользователя
    public $user_id;    // id пользователя
    public $service_id; // id сервиса
    public $ip;         // ip-адрес пользователя
    public $device;     // fingerprint устройства пользователя
    public $log_in;     // время авторизации
    public $expire;     // время истечения срока действия авторизации
    public $token;      // токен авторизации
    public $comment;    // комментарий

    /**
     * Получает сессию по токену (!+)
     * @param string|null $token - токен
     * @param bool $active - только активные или нет искать
     * @return false|mixed|null
     */
    public static function getByToken(?string $token, bool $active = true)
    {
        $activity = !empty($active) ? 'AND u.active IS NOT NULL AND u.blocked IS NULL AND us.active IS NOT NULL AND us.expire > NOW()' : '';
        $db = Db::getInstance();
        $db->params = ['token' => $token];
        $db->sql = "
            SELECT us.id, us.active, us.user_id, u.login, us.service_id, s.name service, us.ip, us.device, us.log_in, us.expire, us.token
            FROM " . self::$db_prefix . self::$db_table . " us 
            LEFT JOIN " . self::$db_prefix . "users.users u ON us.user_id = u.id 
            LEFT JOIN " . self::$db_prefix . "users.services s ON us.service_id = s.id 
            WHERE us.token = :token {$activity}";
        $data = $db->query();
        return !empty($data) ? array_shift($data) : false;
    }

    /**
     * Возвращает количество неудачных попыток залогиниться (!+)
     * @param $login - логин
     * @return int|mixed
     * @throws DbException
     */
    public static function getCountFailedAttempts($login)
    {
        $db = new Db();
        $db->params = ['login' => $login];
        $db->sql = "SELECT count(id) count FROM " . self::$db_prefix . self::$db_table . " WHERE token IS NULL AND active IS NOT NULL AND login = :login";
        $res = $db->query();
        return !empty($res) ? array_shift($res)['count'] : 0;
    }

    /**
     * Очищает неудачные попытки залогиниться (!+)
     * @param $login - логин
     * @return array
     * @throws DbException
     */
    public static function clearFailedAttempts($login)
    {
        $db = new Db();
        $db->params = ['login' => $login];
        $db->sql = "UPDATE " . self::$db_prefix . self::$db_table . " SET active = NULL WHERE login = :login AND token IS NULL";
        return $db->query();
    }

    /**
     * Генерирует токен для пользователя (!+)
     * @param \Entity\User $user - пользователь
     * @param \Entity\UserSession $userSession - сессия пользователя
     * @param int $timeStamp - метка времени
     * @return string
     */
    public function getToken(\Entity\User $user, \Entity\UserSession $userSession, int $timeStamp)
    {
        $data = [
            "iss" => SITE_URL, // адрес или имя удостоверяющего центра
            "aud" => $user->login, // имя клиента для которого токен выпущен
            "iat" => $timeStamp, // время, когда был выпущен JWT
            "nbf" => $timeStamp, // время, начиная с которого может быть использован (не раньше, чем)
            "exp" => $timeStamp + UserSession::LIFE_TIME, // время истечения срока действия токена
            "data" => [
                "id"         => $userSession->userId,
                "serviceId"  => $userSession->serviceId,
                "ip"         => $userSession->ip,
                "device"     => $userSession->device,
                "expired"    => $userSession->expire
            ]
        ];

        return JWT::encode($data, self::KEY, 'HS512');
    }

    /**
     * Удаляет текущую сессию пользователя (разлогинивает) (!+)
     * @param null $token - токен
     * @return bool
     */
    public static function deleteCurrent($token = null)
    {
        if (empty($token)) $token = User::getUserToken();
        $userSession = \Entity\UserSession::get(['token' => $token]);

        if (!empty($userSession->id)) {
            $userSession->isActive = null;
            $userSession->expire = (new \DateTime())->modify('-1 hour');
            $userSession->save();

            if ($userSession->save()) {
                AccessLogger::getInstance()->info("Пользователь {$userSession->login} разлогинен. UserId: {$userSession->userId}.");
                return true;
            } else ErrorLogger::getInstance()->error('Не удалось удалить сессию пользователя. UserId: ' . $userSession->userId);
        } else ErrorLogger::getInstance()->error('Не обнаружена текущая сессия для удаления');

        return false;
    }
}
