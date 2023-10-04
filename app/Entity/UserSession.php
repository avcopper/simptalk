<?php
namespace Entity;

class UserSession extends Entity
{
    public $id;
    public $isActive;
    public $login;
    public $userId;
    public $serviceId;
    public $service;
    public $ip;
    public $device;
    public $logIn;
    public $expire;
    public $token;
    public $comment;

    public static function get(array $params)
    {
        $userSession = \Models\UserSession::getByToken($params['token']);
        if (empty($userSession)) return null;
        $object = new self();
        $object->init($userSession);
        return $object;
    }

    public function getFields()
    {
        return [
            'id'         => ['type' => 'int', 'field' => 'id'],
            'active'     => ['type' => 'bool', 'field' => 'isActive'],
            'login'      => ['type' => 'string', 'field' => 'login'],
            'user_id'    => ['type' => 'int', 'field' => 'userId'],
            'user'       => ['type' => 'string', 'field' => 'user'],
            'service_id' => ['type' => 'int', 'field' => 'serviceId'],
            'service'    => ['type' => 'string', 'field' => 'service'],
            'ip'         => ['type' => 'string', 'field' => 'ip'],
            'device'     => ['type' => 'string', 'field' => 'device'],
            'log_in'     => ['type' => 'datetime', 'field' => 'logIn'],
            'expire'     => ['type' => 'datetime', 'field' => 'expire'],
            'token'      => ['type' => 'string', 'field' => 'token'],
            'comment'    => ['type' => 'string', 'field' => 'comment'],
        ];
    }

    public function save()
    {
        return (new \Models\UserSession())->init($this)->save();
    }
}
