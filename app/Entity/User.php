<?php
namespace Entity;

use DateTime;
use DateInterval;
use Models\UserBlock;

class User extends Entity
{
    public $id;
    public $isActive;
    public $isBlocked;
    public $expire;
    public $groupId = 2; // группа "Пользователи"
    public $group;
    public $login;
    public $password;
    public $pin;
    public $ePin;
    public $email;
    public $phone;
    public $name;
    public $secondName;
    public $lastName;
    public $genderId;
    public $gender;
    public $hasPersonalDataAgreement;
    public $hasMailingAgreement; // подписка на рассылку
    public $mailingTypeId = 2; // тип рассылки html
    public $mailingType;
    public $created;
    public $updated;
    public $publicKey;
    public $privateKey;

    public static function get(array $params)
    {
        switch (true) {
            case !empty($params['id']):
                $user = \Models\User::getById($params['id'], $params['active'] ?? true, false);
                break;
            case !empty($params['login']):
                $user = \Models\User::getByLogin($params['login'], $params['active'] ?? true, false);
                break;
            case !empty($params['token']):
                $user = \Models\User::getByToken($params['token'], $params['active'] ?? true, false);
                break;
        }

        if (empty($user)) return null;
        $object = new self();
        $object->init($user);
        return $object;
    }

    public function init(array $data, array $properties = [])
    {
        parent::init($data, $properties);
        $publicKeyFile = DIR_CERTIFICATES . DIRECTORY_SEPARATOR . $this->id . DIRECTORY_SEPARATOR . 'public.pem';
        $privateKeyFile = DIR_CERTIFICATES . DIRECTORY_SEPARATOR . $this->id . DIRECTORY_SEPARATOR . 'private.pem';
        $this->publicKey = (is_file($publicKeyFile) && filesize($publicKeyFile) > 0) ? file_get_contents($publicKeyFile) : null;
        $this->privateKey = (is_file($privateKeyFile) && filesize($privateKeyFile) > 0) ? file_get_contents($privateKeyFile) : null;
        return $this;
    }

    public function getFields()
    {
        return [
            'id'                      => ['type' => 'int', 'field' => 'id'],
            'active'                  => ['type' => 'bool', 'field' => 'isActive'],
            'blocked'                 => ['type' => 'bool', 'field' => 'isBlocked'],
            'expire'                  => ['type' => 'datetime', 'field' => 'expire'],
            'group_id'                => ['type' => 'int', 'field' => 'groupId'],
            'group_name'              => ['type' => 'string', 'field' => 'group'],
            'login'                   => ['type' => 'string', 'field' => 'login'],
            'password'                => ['type' => 'string', 'field' => 'password'],
            'pin'                     => ['type' => 'string', 'field' => 'pin'],
            'e_pin'                   => ['type' => 'string', 'field' => 'ePin'],
            'email'                   => ['type' => 'string', 'field' => 'email'],
            'phone'                   => ['type' => 'string', 'field' => 'phone'],
            'name'                    => ['type' => 'string', 'field' => 'name'],
            'second_name'             => ['type' => 'string', 'field' => 'secondName'],
            'last_name'               => ['type' => 'gender', 'field' => 'lastName'],
            'gender_id'               => ['type' => 'int', 'field' => 'genderId'],
            'gender'                  => ['type' => 'gender', 'field' => 'gender'],
            'personal_data_agreement' => ['type' => 'bool', 'field' => 'hasPersonalDataAgreement'],
            'mailing'                 => ['type' => 'bool', 'field' => 'hasMailingAgreement'],
            'mailing_type_id'         => ['type' => 'int', 'field' => 'mailingTypeId'],
            'mailing_type'            => ['type' => 'string', 'field' => 'mailingType'],
            'created'                 => ['type' => 'datetime', 'field' => 'created'],
            'updated'                 => ['type' => 'datetime', 'field' => 'updated'],
        ];
    }

    public function save()
    {
        $user = new \Models\User();
        $user->id = $this->id;
        $user->active = $this->isActive ? 1 : null;
        $user->blocked = $this->isBlocked ? 1 : null;
        $user->group_id = $this->groupId;
        $user->login = $this->login;
        $user->password = $this->password;
        $user->pin = $this->pin;
        $user->e_pin = $this->ePin;
        $user->email = $this->email;
        $user->phone = $this->phone;
        $user->name = $this->name;
        $user->second_name = $this->secondName;
        $user->last_name = $this->lastName;
        $user->gender_id = $this->genderId;
        $user->personal_data_agreement = $this->hasPersonalDataAgreement ? 1 : null;
        $user->mailing = $this->hasMailingAgreement ? 1 : null;
        $user->mailing_type_id = $this->mailingTypeId;
        $user->created = $this->created->format('Y-m-d H:i:s');
        $user->updated = date('Y-m-d H:i:s');
        return $user->save();
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
}
