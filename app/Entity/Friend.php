<?php
namespace Entity;

class Friend extends Entity
{
    public $id;
    public $isActive;
    public $isBlocked;
    public $expire;
    public $login;
    public $name;
    public $secondName;
    public $lastName;
    public $genderId;
    public $gender;
    public $publicKey;

    public static function get(array $params)
    {
        switch (true) {
            case !empty($params['id']):
                $user = \Models\Friend::getById($params['id'], $params['active'] ?? true, false);
                break;
            case !empty($params['login']):
                $user = \Models\Friend::getByLogin($params['login'], $params['active'] ?? true, false);
                break;
        }

        if (empty($user)) return null;
        $object = new self();
        $object->init($user);
        return $object;
    }

    public function init(?array $data, array $properties = [])
    {
        parent::init($data, $properties);
        $publicKeyFile = DIR_CERTIFICATES . DIRECTORY_SEPARATOR . $this->id . DIRECTORY_SEPARATOR . 'public.pem';
        $this->publicKey = (is_file($publicKeyFile) && filesize($publicKeyFile) > 0) ? file_get_contents($publicKeyFile) : null;
    }

    public function getFields()
    {
        return [
            'id'          => ['type' => 'int', 'field' => 'id'],
            'active'      => ['type' => 'bool', 'field' => 'isActive'],
            'blocked'     => ['type' => 'bool', 'field' => 'isBlocked'],
            'expire'      => ['type' => 'datetime', 'field' => 'expire'],
            'login'       => ['type' => 'string', 'field' => 'login'],
            'name'        => ['type' => 'string', 'field' => 'name'],
            'second_name' => ['type' => 'string', 'field' => 'secondName'],
            'last_name'   => ['type' => 'gender', 'field' => 'lastName'],
            'gender_id'   => ['type' => 'int', 'field' => 'genderId'],
            'gender'      => ['type' => 'gender', 'field' => 'gender'],
        ];
    }
}
