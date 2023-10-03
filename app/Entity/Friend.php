<?php
namespace Entity;

class Friend extends Entity
{
    public $id;
    public $isActive;
    public $isBlocked;
    public $isLocked = false;
    public $isNeedRequest = true;
    public $expire;
    public $login;
    public $email;
    public $isShowEmail = false;
    public $phone = null;
    public $isShowPhone = false;
    public $name;
    public $secondName;
    public $lastName;
    public $genderId;
    public $gender;
    public $timezone;
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
            'locked'                  => ['type' => 'bool', 'field' => 'isLocked'],
            'need_request'            => ['type' => 'bool', 'field' => 'isNeedRequest'],
            'expire'      => ['type' => 'datetime', 'field' => 'expire'],
            'login'       => ['type' => 'string', 'field' => 'login'],
            'email'                   => ['type' => 'string', 'field' => 'email'],
            'show_email'              => ['type' => 'bool', 'field' => 'isShowEmail'],
            'phone'                   => ['type' => 'string', 'field' => 'phone'],
            'show_phone'              => ['type' => 'bool', 'field' => 'isShowPhone'],
            'name'        => ['type' => 'string', 'field' => 'name'],
            'second_name' => ['type' => 'string', 'field' => 'secondName'],
            'last_name'   => ['type' => 'gender', 'field' => 'lastName'],
            'gender_id'   => ['type' => 'int', 'field' => 'genderId'],
            'gender'      => ['type' => 'gender', 'field' => 'gender'],
            'timezone'                => ['type' => 'int', 'field' => 'timezone'],
        ];
    }

    public static function search(array $params)
    {
        $login = preg_replace('/[^0-9A-Za-z-_]/', '', trim($params['login']));
        if (mb_strlen($login) < 2) return [];
        $users = \Models\Friend::searchByLogin($login, $params['user_id'] ?? null, $params['active'] ?? true, false);

        $res = [];
        if (!empty($users) && is_array($users)) {
            foreach ($users as $user) {
                $object = new self();
                $object->init($user);
                $res[] = $object;
            }
        }

        return $res ?: null;
    }
}
