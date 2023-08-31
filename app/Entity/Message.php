<?php
namespace Entity;

class Message extends Entity
{
    public $id;
    public $isActive = true;
    public $isRead = false;
    public $messageFromUserId;
    public $messageToUserId;
    public $message;
    public $created;
    public $updated;

    public static function getList(array $params)
    {
        $messages = \Models\Message::getAll(
            $params['user_id'],
            $params['friend_id'],
            $params['limit'] ?? 0,
            $params['start'] ?? 0,
            $params['active'] ?? true,
            false
        );
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
            'id'           => ['type' => 'int', 'field' => 'id'],
            'active'       => ['type' => 'bool', 'field' => 'isActive'],
            'is_read'      => ['type' => 'bool', 'field' => 'isRead'],
            'from_user_id' => ['type' => 'int', 'field' => 'messageFromUserId'],
            'to_user_id'   => ['type' => 'int', 'field' => 'messageToUserId'],
            'message'      => ['type' => 'string', 'field' => 'message'],
            'created'      => ['type' => 'datetime', 'field' => 'created'],
            'updated'      => ['type' => 'datetime', 'field' => 'updated'],
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
