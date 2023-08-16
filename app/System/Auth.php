<?php
namespace System;

use DateTime;
use Entity\User;
use DateInterval;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Models\UserBlock;
use Entity\UserSession;
use Exceptions\UserException;
use Models\UserSession as ModelUserSession;

class Auth
{
    const NOT_AUTHORIZED = 'Not authorized';
    const USER_NOT_FOUND = 'User not found';
    const TOO_MANY_FAILED_ATTEMPTS = 'Too many failed attempts';
    const EMERGENCY_PIN_ENTERED = 'Emergency PIN entered';
    const PIN_ENTERED = 'PIN entered';
    const PASSWORD_ENTERED = 'Password entered';
    const API_NO_RESPONSE = 'No response from api';
    const WRONG_TOKEN = 'Wrong token';
    const WRONG_DEVICE = 'Wrong device information';
    const WRONG_SERVICE = 'Wrong service information';
    const WRONG_IP = 'Wrong ip address information';
    const WRONG_LOGIN_PASSWORD = 'Wrong login/password';

    public ?string $token;
    public ?User $user;
    public ?UserSession $userSession;

    /**
     * Проверяет авторизацию пользователя (+++)
     * @param $jwt - токен
     * @param $data - пользовательские данные
     * @return bool
     * @throws UserException
     */
    public static function checkAuthorization($jwt, $data)
    {
        try {
            $token = JWT::decode($jwt, new Key(ModelUserSession::KEY, 'HS512'));
            $userData = self::decodeUserData($data);
            $userSession = UserSession::get(['token' => $jwt]);
            if (!empty($userSession->userId)) $user = User::get(['id' => $userSession->userId]);
        } catch (\Exception $e) {
            return false;
        }

        return
            !empty($user->id) && self::checkToken($token ?? null) && self::checkUserData($userData, $token) &&
            self::checkUserSession($userSession, $token);
    }

    /**
     * Декодирует пользовательские данные (+++)
     * @param $data - пользовательские данные
     * @return array
     */
    public static function decodeUserData($data)
    {
        $userData = !empty($data['user']) ? explode(':', base64_decode($data['user'])) : [];

        return [
            'user' => [
                'login' => !empty($userData[0]) ? strip_tags($userData[0]) : null,
                'password' => !empty($userData[1]) ? $userData[1] : null,
            ],
            'device' => $data['device'] ? base64_decode($data['device']) : null,
            'ip' => $data['ip'] ? base64_decode($data['ip']) : null,
            'serviceId' => $data['serviceId'] ?? null,
        ];
    }

    /**
     * Проверяет данные пользователя из запроса (+++)
     * @param $data - массив данных
     * @return bool
     * @throws UserException
     */
    public static function checkData($data)
    {
        return self::checkUser($data['user']['login'], $data['user']['password']) && self::checkDeviceData($data);
    }

    /**
     * Проверяет наличие логина/пароля в данных (+++)
     * @param $login - логин
     * @param $password - пароль
     * @return bool
     * @throws UserException
     */
    public static function checkUser($login, $password)
    {
        if (empty($login) || empty($password)) throw new UserException(self::WRONG_LOGIN_PASSWORD, 401);
        return true;
    }

    /**
     * Проверяет данные устройства пользователя (+++)
     * @param $data - массив данных
     * @return bool
     * @throws UserException
     */
    public static function checkDeviceData($data)
    {
        return self::checkDevice($data['device']) && self::checkService($data['serviceId']) && self::checkIp($data['ip']);
    }

    /**
     * Проверяет наличие fingerprint устройства в данных (+++)
     * @param $data - массив данных
     * @return bool
     * @throws UserException
     */
    private static function checkDevice($data)
    {
        if (empty($data)) throw new UserException(self::WRONG_DEVICE, 401);
        return true;
    }

    /**
     * Проверяет наличие сервиса в данных (+++)
     * @param $data - массив данных
     * @return bool
     * @throws UserException
     */
    private static function checkService($data)
    {
        if (empty($data) || !is_numeric($data) || !in_array($data, ModelUserSession::SERVICES))
            throw new UserException(self::WRONG_SERVICE, 401);

        return true;
    }

    /**
     * Проверяет наличие ip-адреса в данных (+++)
     * @param $data - массив данных
     * @return bool
     * @throws UserException
     */
    private static function checkIp($data)
    {
        if (empty($data)) throw new UserException(self::WRONG_IP, 401);
        return true;
    }

    /**
     * Проверяет актуальность токена (+++)
     * @param Object $token - токен
     * @return bool
     * @throws UserException
     */
    private static function checkToken(Object $token)
    {
        $now = (new DateTime())->getTimestamp();

        if (empty($token) || $token->exp < $now || $token->iat > $now || $token->nbf > $now)
            throw new UserException(self::WRONG_TOKEN);

        return true;
    }

    /**
     * @param array $userData - пользовательские данные (+++)
     * @param Object $token - токен
     * @return bool
     * @throws UserException
     */
    private static function checkUserData(array $userData, Object $token)
    {
        if (empty($userData['device']) || $userData['device'] !== $token->data->device ||
            empty($userData['ip']) || $userData['ip'] !== $token->data->ip ||
            empty($userData['serviceId']) || $userData['serviceId'] !== $token->data->serviceId
        ) throw new UserException(self::WRONG_TOKEN);

        return true;
    }

    /**
     * Проверяет соответствие сессии токену (+++)
     * @param UserSession|null $userSession - пользовательская сессия
     * @param Object $token - токен
     * @return bool
     * @throws UserException
     */
    private static function checkUserSession(?UserSession $userSession, Object $token)
    {
        if (empty($userSession)) throw new UserException(self::NOT_AUTHORIZED, 401);

        if ($token->iss !== SITE || $token->aud !== $userSession->login ||
            $token->data->id !== $userSession->userId || $token->data->serviceId !== $userSession->serviceId ||
            $token->data->ip !== $userSession->ip || $token->data->device !== $userSession->device
        ) throw new UserException('Токен не принадлежит данному пользователю');

        return true;
    }

    /**
     * Вход по ePin (+++)
     * @throws UserException
     */
    public function loginEmergencyPin()
    {
        $this->userSession->comment = self::EMERGENCY_PIN_ENTERED;
        $this->userSession->save();
        $this->user->block(UserBlock::INTERVAL_CENTURY, $this->userSession->comment);
        throw new UserException(self::EMERGENCY_PIN_ENTERED, 401);
    }

    /**
     * Вход по pin (+++)
     */
    public function loginPin()
    {
        $this->generateSessionToken();
        $this->userSession->comment = self::PIN_ENTERED;

        if ($this->userSession->token && $this->userSession->save()) {
            ModelUserSession::clearFailedAttempts($this->user->login);
            Response::apiResult(200, true, 'OK', [], $this->userSession->token);
        }
    }

    /**
     * Вход по паролю
     * @param bool $remember - запоминать ли пользователя
     */
    public function login(bool $remember = false)
    {
        $this->generateSessionToken();
        $this->userSession->comment = self::PASSWORD_ENTERED;

        if ($this->userSession->token && $this->userSession->save()) {
            ModelUserSession::clearFailedAttempts($this->user->login);
            if (defined('API')) Response::apiResult(200, true, 'OK', [], $this->userSession->token);
            else {
                $_SESSION['token'] = $this->token;
                $_SESSION['user'] = $this->user;
                if ($remember) setcookie('token', $this->token, time() + ModelUserSession::LIFE_TIME, '/', SITE, 0);
                header('Location: /');
                die;
            }
        }
    }

    /**
     * Генерирует сессионный токен
     */
    private function generateSessionToken()
    {
        $date = new DateTime();
        $timestamp = $date->getTimestamp();
        $lifeTime = ModelUserSession::LIFE_TIME;

        $this->userSession->expire = $date->add(new DateInterval( "PT{$lifeTime}S" ));
        $this->token = $this->userSession->token = (new ModelUserSession())->getToken($this->user, $this->userSession, $timestamp);
    }
}
