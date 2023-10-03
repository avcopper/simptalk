<?php
namespace Entity;

use DateTime;
use DateInterval;
use Models\UserBlock;

class User extends Entity
{
    public $id;
    public $isActive = true;
    public $isBlocked = false;
    public $isLocked = false;
    public $isNeedRequest = true;
    public $expire;
    public $groupId = 2; // группа "Пользователи"
    public $group;
    public $login;
    public $password;
    public $pin = null;
    public $ePin = null;
    public $email;
    public $isShowEmail = false;
    public $phone = null;
    public $isShowPhone = false;
    public $name;
    public $secondName = null;
    public $lastName = null;
    public $genderId = 1;
    public $gender;
    public $hasPersonalDataAgreement = 1;
    public $hasMailingAgreement = null; // подписка на рассылку
    public $mailingTypeId = 2; // тип рассылки html
    public $mailingType;
    public $timezone;
    public $created;
    public $updated = null;
    public $publicKey = null;
    public $privateKey = null;

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

    public function init(?array $data, array $properties = [])
    {
        parent::init($data, $properties);
        $this->publicKey = self::getPublicKey($this->id);
        $this->privateKey = self::getPrivateKey($this->id);
        return $this;
    }

    public static function getPublicKey($user_id)
    {
        $publicKeyFile = DIR_CERTIFICATES . DIRECTORY_SEPARATOR . $user_id . DIRECTORY_SEPARATOR . 'public.pem';
        return (is_file($publicKeyFile) && filesize($publicKeyFile) > 0) ? file_get_contents($publicKeyFile) : null;
    }

    public static function getPrivateKey($user_id)
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

//    public function save()
//    {
//        parent::save();
//        $user = new \Models\User();
//        $user->id = $this->id;
//        $user->active = $this->isActive ? 1 : null;
//        $user->blocked = $this->isBlocked ? 1 : null;
//        $user->group_id = $this->groupId;
//        $user->login = $this->login;
//        $user->password = $this->password;
//        $user->pin = $this->pin;
//        $user->e_pin = $this->ePin;
//        $user->email = $this->email;
//        $user->phone = $this->phone;
//        $user->name = $this->name;
//        $user->second_name = $this->secondName;
//        $user->last_name = $this->lastName;
//        $user->gender_id = $this->genderId;
//        $user->personal_data_agreement = $this->hasPersonalDataAgreement ? 1 : null;
//        $user->mailing = $this->hasMailingAgreement ? 1 : null;
//        $user->mailing_type_id = $this->mailingTypeId;
//        $user->created = !empty($this->created) && $this->created instanceof DateTime ?
//            $this->created->format('Y-m-d H:i:s') :
//            (new DateTime())->format('Y-m-d H:i:s');
//        $user->updated = date('Y-m-d H:i:s');var_dump($user);die;
//        return $user->save();
//    }

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
