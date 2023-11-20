<?php
namespace Entity;

class Friend extends User
{
    public static function get(array $params)
    {
        switch (true) {
            case !empty($params['id']):
                $user = \Models\User::getById($params['id'], $params);
                break;
            case !empty($params['login']):
                $user = \Models\User::getByLogin($params['login'], $params);
                break;
        }

        if (empty($user)) return null;
        $object = new self();
        $object->init($user);
        return $object;
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
            'group_id'     => ['type' => 'int', 'field' => 'groupId'],
            'group_name'   => ['type' => 'string', 'field' => 'group'],
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
        $users = \Models\User::searchByLogin($login, $params);

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
