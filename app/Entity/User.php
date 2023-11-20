<?php
namespace Entity;

use DateTime;
use DateInterval;
use Models\UserBlock;

class User extends Entity
{
    protected $id;
    protected $isActive = true;
    protected $isBlocked = false;
    protected $isLocked = false;
    protected $isNeedRequest = true;
    protected $expire;
    protected $groupId = 2; // группа "Пользователи"
    protected $group;
    protected $login;
    protected $password;
    protected $pin = null;
    protected $ePin = null;
    protected $email;
    protected $isShowEmail = false;
    protected $phone = null;
    protected $isShowPhone = false;
    protected $name;
    protected $secondName = null;
    protected $lastName = null;
    protected $genderId = 1;
    protected $gender;
    protected $hasPersonalDataAgreement = 1;
    protected $hasMailingAgreement = null; // подписка на рассылку
    protected $mailingTypeId = 2; // тип рассылки html
    protected $mailingType;
    protected $timezone;
    protected $created;
    protected $updated = null;
    protected $publicKey = null;
    protected $privateKey = null;

    public static function get(array $params)
    {
        switch (true) {
            case !empty($params['id']):
                $user = \Models\User::getById($params['id'], $params);
                break;
            case !empty($params['login']):
                $user = \Models\User::getByLogin($params['login'], $params);
                break;
            case !empty($params['token']):
                $user = \Models\User::getByToken($params['token'], $params);
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
        $this->publicKey = self::loadPublicKey($this->id);
        if (!($this instanceof Friend)) $this->privateKey = self::loadPrivateKey($this->id);
        return $this;
    }

    public static function loadPublicKey($user_id)
    {
        $publicKeyFile = DIR_CERTIFICATES . DIRECTORY_SEPARATOR . $user_id . DIRECTORY_SEPARATOR . 'public.pem';
        return (is_file($publicKeyFile) && filesize($publicKeyFile) > 0) ? file_get_contents($publicKeyFile) : null;
    }

    public static function loadPrivateKey($user_id)
    {
        $privateKeyFile = DIR_CERTIFICATES . DIRECTORY_SEPARATOR . $user_id . DIRECTORY_SEPARATOR . 'private.pem';
        return (is_file($privateKeyFile) && filesize($privateKeyFile) > 0) ? file_get_contents($privateKeyFile) : null;
    }

    public function getFields()
    {
        return [
            'id'                      => ['type' => 'int', 'field' => 'id'],
            'active'                  => ['type' => 'bool', 'field' => 'isActive'],
            'blocked'                 => ['type' => 'bool', 'field' => 'isBlocked'],
            'locked'                  => ['type' => 'bool', 'field' => 'isLocked'],
            'need_request'            => ['type' => 'bool', 'field' => 'isNeedRequest'],
            'expire'                  => ['type' => 'datetime', 'field' => 'expire'],
            'group_id'                => ['type' => 'int', 'field' => 'groupId'],
            'group_name'              => ['type' => 'string', 'field' => 'group'],
            'login'                   => ['type' => 'string', 'field' => 'login'],
            'password'                => ['type' => 'string', 'field' => 'password'],
            'pin'                     => ['type' => 'string', 'field' => 'pin'],
            'e_pin'                   => ['type' => 'string', 'field' => 'ePin'],
            'email'                   => ['type' => 'string', 'field' => 'email'],
            'show_email'              => ['type' => 'bool', 'field' => 'isShowEmail'],
            'phone'                   => ['type' => 'string', 'field' => 'phone'],
            'show_phone'              => ['type' => 'bool', 'field' => 'isShowPhone'],
            'name'                    => ['type' => 'string', 'field' => 'name'],
            'second_name'             => ['type' => 'string', 'field' => 'secondName'],
            'last_name'               => ['type' => 'gender', 'field' => 'lastName'],
            'gender_id'               => ['type' => 'int', 'field' => 'genderId'],
            'gender'                  => ['type' => 'gender', 'field' => 'gender'],
            'personal_data_agreement' => ['type' => 'bool', 'field' => 'hasPersonalDataAgreement'],
            'mailing'                 => ['type' => 'bool', 'field' => 'hasMailingAgreement'],
            'mailing_type_id'         => ['type' => 'int', 'field' => 'mailingTypeId'],
            'mailing_type'            => ['type' => 'string', 'field' => 'mailingType'],
            'timezone'                => ['type' => 'int', 'field' => 'timezone'],
            'created'                 => ['type' => 'datetime', 'field' => 'created'],
            'updated'                 => ['type' => 'datetime', 'field' => 'updated'],
        ];
    }

    public function save()
    {
        return (new \Models\User())->init($this)->save();
    }

    /**
     * Создает запись о блокировке пользователя
     * @param int $time - время блокировки
     * @param string|null $reason - причина блокировки
     * @return bool|int
     */
    public function block(int $time, ?string $reason = null)
    {
        $date = new DateTime();
        $userBlock = new UserBlock();
        $userBlock->user_id = $this->id;
        $userBlock->created = $date->format('Y-m-d H:i:s');
        $userBlock->expire = $date->add(new DateInterval( "PT{$time}S" ))->format('Y-m-d H:i:s');
        $userBlock->reason = $reason;
        return $userBlock->save();
    }

    public static function checkUser(?User $user)
    {
        return !empty($user->id) && $user instanceof User;
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

    public function getGroupId(): int
    {
        return $this->groupId;
    }

    public function setGroupId(int $groupId): void
    {
        $this->groupId = $groupId;
    }

    public function getGroup()
    {
        return $this->group;
    }

    public function setGroup($group): void
    {
        $this->group = $group;
    }

    public function getLogin()
    {
        return $this->login;
    }

    public function setLogin($login): void
    {
        $this->login = $login;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password): void
    {
        $this->password = $password;
    }

    public function getPin()
    {
        return $this->pin;
    }

    public function setPin($pin): void
    {
        $this->pin = $pin;
    }

    public function getEPin()
    {
        return $this->ePin;
    }

    public function setEPin($ePin): void
    {
        $this->ePin = $ePin;
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

    public function getHasPersonalDataAgreement(): int
    {
        return $this->hasPersonalDataAgreement;
    }

    public function setHasPersonalDataAgreement(int $hasPersonalDataAgreement): void
    {
        $this->hasPersonalDataAgreement = $hasPersonalDataAgreement;
    }

    public function getHasMailingAgreement()
    {
        return $this->hasMailingAgreement;
    }

    public function setHasMailingAgreement($hasMailingAgreement): void
    {
        $this->hasMailingAgreement = $hasMailingAgreement;
    }

    public function getMailingTypeId(): int
    {
        return $this->mailingTypeId;
    }

    public function setMailingTypeId(int $mailingTypeId): void
    {
        $this->mailingTypeId = $mailingTypeId;
    }

    public function getMailingType()
    {
        return $this->mailingType;
    }

    public function setMailingType($mailingType): void
    {
        $this->mailingType = $mailingType;
    }

    public function getTimezone()
    {
        return $this->timezone;
    }

    public function setTimezone($timezone): void
    {
        $this->timezone = $timezone;
    }

    public function getCreated()
    {
        return $this->created;
    }

    public function setCreated($created): void
    {
        $this->created = $created;
    }

    public function getUpdated()
    {
        return $this->updated;
    }

    public function setUpdated($updated): void
    {
        $this->updated = $updated;
    }

    public function getPublicKey()
    {
        return $this->publicKey;
    }

    public function setPublicKey($publicKey): void
    {
        $this->publicKey = $publicKey;
    }

    public function getPrivateKey()
    {
        return $this->privateKey;
    }

    public function setPrivateKey($privateKey): void
    {
        $this->privateKey = $privateKey;
    }
}
