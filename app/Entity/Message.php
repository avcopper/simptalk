<?php
namespace Entity;

class Message extends Entity
{
    public $id;
    public $isActive = true;
    public $isRead = false;
    public $messageFromUserId;
    public $messageToUserId;
    public $friendId;
    public $friendLogin;
    public $friendName;
    public $friendLastName;
    public $message;
    public $created;
    public $updated;

    public static function getList(array $params)
    {
        $messages = \Models\Message::getList($params);
        $list = [];

        if (!empty($messages) && is_array($messages)) {
            foreach ($messages as $message) {
                $object = new self();
                $object->init($message);
                $list[] = $object;
            }
        }

        return $list;
    }

    public static function getUserList($params)
    {
        if (empty($params['user_id'])) return [];

        $messages = self::getList($params);
        $friends = [];
        $result = [];

        if (!empty($messages) && is_array($messages)) {
            foreach ($messages as $message) {
                if (in_array($message->messageFromUserId, $friends) || in_array($message->messageToUserId, $friends)) continue;

                $friends[] = $message->messageFromUserId !== $params['user_id'] ? $message->messageFromUserId : $message->messageToUserId;
                $result[] = $message;
            }
        }

        return $result;
    }

//    public static function get(array $params)
//    {
//        switch (true) {
//            case !empty($params['id']):
//                $user = \Models\Message::getById($params['id'], $params['active'] ?? true, false);
//                break;
//        }
//
//        if (empty($user)) return null;
//        $object = new self();
//        $object->init($user);
//        return $object;
//    }

    public function getFields()
    {
        return [
            'id'               => ['type' => 'int', 'field' => 'id'],
            'active'           => ['type' => 'bool', 'field' => 'isActive'],
            'is_read'          => ['type' => 'bool', 'field' => 'isRead'],
            'from_user_id'     => ['type' => 'int', 'field' => 'messageFromUserId'],
            'to_user_id'       => ['type' => 'int', 'field' => 'messageToUserId'],
            'friend_id'        => ['type' => 'int', 'field' => 'friendId'],
            'friend_login'     => ['type' => 'string', 'field' => 'friendLogin'],
            'friend_name'      => ['type' => 'string', 'field' => 'friendName'],
            'friend_last_name' => ['type' => 'string', 'field' => 'friendLastName'],
            'message'          => ['type' => 'string', 'field' => 'message'],
            'created'          => ['type' => 'datetime', 'field' => 'created'],
            'updated'          => ['type' => 'datetime', 'field' => 'updated'],
        ];
    }

    public function save()
    {
        $user = new \Models\Message();
        $user->id = $this->id;
        $user->active = $this->isActive ? 1 : null;
        $user->is_read = $this->isRead ? 1 : null;
        $user->from_user_id = $this->messageFromUserId;
        $user->to_user_id = $this->messageToUserId;
        $user->message = $this->message;
        $user->created = !empty($this->created) ? $this->created->format('Y-m-d H:i:s') : date('Y-m-d H:i:s');
        $user->updated = date('Y-m-d H:i:s');
        return $user->save();
    }
}
