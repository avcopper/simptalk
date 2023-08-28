<?php
namespace System;

use Entity\User;
use Models\UserSession;
use Exceptions\UserException;

class Api
{
    const API_PROTOCOL = 'http';
    const API_URL = '//api.simptalk';
    const API_VERSION = 'v1';

    const URL_LOGIN = '/users/login/';
    const URL_VALIDATION = '/users/validate/';

    const NOT_AUTHORIZED = 'Not authorized';
    const USER_NOT_FOUND = 'User not found';
    const TOO_MANY_FAILED_ATTEMPTS = 'Too many failed attempts';
    const EMERGENCY_PIN_ENTERED = 'Emergency PIN entered';
    const PIN_ENTERED = 'PIN entered';
    const PASSWORD_ENTERED = 'Password entered';
    const WRONG_TOKEN = 'Wrong token';
    const WRONG_DEVICE = 'Wrong device information';
    const WRONG_SERVICE = 'Wrong service information';
    const WRONG_IP = 'Wrong ip address information';
    const WRONG_LOGIN_PASSWORD = 'Wrong login/password';
    const AUTH_FAILED = 'Authorization failed';

    private $url;
    private $token;
    private $device;
    private $ip;
    private $serviceId = 2;

    public function __construct()
    {
        $this->url = self::API_PROTOCOL . ':' . self::API_URL . '/' . self::API_VERSION;
        $this->token = !empty($_SESSION['token']) ? $_SESSION['token'] : (!empty($_COOKIE['token']) ? $_COOKIE['token'] : null);
        $this->device = base64_encode($_SERVER['HTTP_USER_AGENT']);
        $this->ip = base64_encode($_SERVER['REMOTE_ADDR']);
    }

    /**
     * Авторизация пользователя через апи
     * @param $user - логин
     * @param $password - пароль
     * @param false $remember - флаг запоминания
     * @return User
     * @throws UserException
     */
    public function authorizeByApi($user, $password, $remember = false)
    {
        if (!empty($user) && !empty($password)) {
            $fields = [
                'user' => base64_encode("{$user}:{$password}"),
                'device' => $this->device,
                'ip' => $this->ip,
                'serviceId' => $this->serviceId
            ];

            $response = $this->request('POST', self::URL_LOGIN, $fields);
            if ($response['status'] === 200 && empty($response['error']) &&
                !empty($response['response']['result']) && $response['response']['result'] === true &&
                !empty($response['response']['message']) && $response['response']['message'] === 'OK' &&
                !empty($response['response']['token']) && !empty($response['response']['data']['user']))
            {
                $user = (new User())->init($response['response']['data']['user']);
                $_SESSION['token'] = $response['response']['token'];
                $_SESSION['user'] = $user;
                if ($remember) setcookie('token', $response['response']['token'], time() + UserSession::LIFE_TIME, '/', SITE, 0);

                return $user;
            }
            throw new UserException($response['response']['message'] ?? self::AUTH_FAILED, $response['status']);
        }
        throw new UserException(self::WRONG_LOGIN_PASSWORD, 403);
    }

    /**
     * Проверка токена авторизации через апи
     * @return bool
     */
    public function validate()
    {
        $fields = [
            'device' => $this->device,
            'ip' => $this->ip,
            'serviceId' => $this->serviceId
        ];

        $response = $this->request('POST', self::URL_VALIDATION, $fields, $this->token);

        return $response['status'] === 200 && empty($response['error']) &&
            !empty($response['response']['result']) && $response['response']['result'] === true &&
            !empty($response['response']['message']) && $response['response']['message'] === 'OK' &&
            !empty($response['response']['token']) && $response['response']['token'] === $this->token;
    }

    /**
     * Делает запрос в апи
     * @param string $method - метод
     * @param string $url - адрес
     * @param array $fields - поля запроса
     * @param string|null $token - токен
     * @return mixed
     */
    private function request(string $method, string $url, array $fields, string $token = null)
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $this->url . $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_POSTFIELDS => json_encode($fields),
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                "Authorization: Bearer {$token}"
            )
        ]);

        $response = json_decode(curl_exec($curl), true);
        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $error = curl_error($curl);
        curl_close($curl);

        return [
            'status' => $status,
            'error' => $error,
            'response' => $response
        ];
    }
}
