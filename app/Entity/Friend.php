<?php
namespace Entity;

class Friend extends User
{
    protected $id;
    protected $isActive = true;
    protected $isBlocked = false;
    protected $isLocked = false;
    protected $isNeedRequest = true;
    protected $expire;
    protected $login;
    protected $email;
    protected $isShowEmail = false;
    protected $phone = null;
    protected $isShowPhone = false;
    protected $name;
    protected $secondName = null;
    protected $lastName = null;
    protected $genderId = 1;
    protected $gender;
    protected $timezone;
    protected $publicKey = null;

    public static function get(array $params)
    {
        switch (true) {
            case !empty($params['id']):
                $user = \Models\Friend::getById($params['id'], $params);
                break;
            case !empty($params['login']):
                $user = \Models\Friend::getByLogin($params['login'], $params);
                break;
        }

        if (empty($user)) return null;
        $object = new self();
        $object->init($user);
        return $object;
    }

    public function init(?array $data)
    {
        parent::init($data);
        $publicKeyFile = DIR_CERTIFICATES . DIRECTORY_SEPARATOR . $this->id . DIRECTORY_SEPARATOR . 'public.pem';
        $this->publicKey = (is_file($publicKeyFile) && filesize($publicKeyFile) > 0) ? file_get_contents($publicKeyFile) : null;
    }

    public function getFields()
    {
        return [
            'id'           => ['type' => 'int', 'field' => 'id'],
            'active'       => ['type' => 'bool', 'field' => 'isActive'],
            'blocked'      => ['type' => 'bool', 'field' => 'isBlocked'],
            'locked'       => ['type' => 'bool', 'field' => 'isLocked'],
            'need_request' => ['type' => 'bool', 'field' => 'isNeedRequest'],
            'expire'       => ['type' => 'datetime', 'field' => 'expire'],
            'login'        => ['type' => 'string', 'field' => 'login'],
            'email'        => ['type' => 'string', 'field' => 'email'],
            'show_email'   => ['type' => 'bool', 'field' => 'isShowEmail'],
            'phone'        => ['type' => 'string', 'field' => 'phone'],
            'show_phone'   => ['type' => 'bool', 'field' => 'isShowPhone'],
            'name'         => ['type' => 'string', 'field' => 'name'],
            'second_name'  => ['type' => 'string', 'field' => 'secondName'],
            'last_name'    => ['type' => 'gender', 'field' => 'lastName'],
            'gender_id'    => ['type' => 'int', 'field' => 'genderId'],
            'gender'       => ['type' => 'gender', 'field' => 'gender'],
            'timezone'     => ['type' => 'int', 'field' => 'timezone'],
        ];
    }

    public static function search(array $params)
    {
        $login = preg_replace('/[^0-9A-Za-z-_]/', '', trim($params['login']));
        if (mb_strlen($login) < 3) return [];
        $users = \Models\Friend::searchByLogin($login, $params);

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

    public function getId()
    {
        return $this->id;
    }

    public function setId($id): void
    {
        $this->id = $id;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): void
    {
        $this->isActive = $isActive;
    }

    public function isBlocked(): bool
    {
        return $this->isBlocked;
    }

    public function setIsBlocked(bool $isBlocked): void
    {
        $this->isBlocked = $isBlocked;
    }

    public function isLocked(): bool
    {
        return $this->isLocked;
    }

    public function setIsLocked(bool $isLocked): void
    {
        $this->isLocked = $isLocked;
    }

    public function isNeedRequest(): bool
    {
        return $this->isNeedRequest;
    }

    public function setIsNeedRequest(bool $isNeedRequest): void
    {
        $this->isNeedRequest = $isNeedRequest;
    }

    public function getExpire()
    {
        return $this->expire;
    }

    public function setExpire($expire): void
    {
        $this->expire = $expire;
    }

    public function getLogin()
    {
        return $this->login;
    }

    public function setLogin($login): void
    {
        $this->login = $login;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email): void
    {
        $this->email = $email;
    }

    public function isShowEmail(): bool
    {
        return $this->isShowEmail;
    }

    public function setIsShowEmail(bool $isShowEmail): void
    {
        $this->isShowEmail = $isShowEmail;
    }

    public function getPhone()
    {
        return $this->phone;
    }

    public function setPhone($phone): void
    {
        $this->phone = $phone;
    }

    public function isShowPhone(): bool
    {
        return $this->isShowPhone;
    }

    public function setIsShowPhone(bool $isShowPhone): void
    {
        $this->isShowPhone = $isShowPhone;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name): void
    {
        $this->name = $name;
    }

    public function getSecondName()
    {
        return $this->secondName;
    }

    public function setSecondName($secondName): void
    {
        $this->secondName = $secondName;
    }

    public function getLastName()
    {
        return $this->lastName;
    }

    public function setLastName($lastName): void
    {
        $this->lastName = $lastName;
    }

    public function getGenderId(): int
    {
        return $this->genderId;
    }

    public function setGenderId(int $genderId): void
    {
        $this->genderId = $genderId;
    }

    public function getGender()
    {
        return $this->gender;
    }

    public function setGender($gender): void
    {
        $this->gender = $gender;
    }

    public function getTimezone()
    {
        return $this->timezone;
    }

    public function setTimezone($timezone): void
    {
        $this->timezone = $timezone;
    }

    public function getPublicKey()
    {
        return $this->publicKey;
    }

    public function setPublicKey($publicKey): void
    {
        $this->publicKey = $publicKey;
    }
}
