<?php
namespace Entity;

class Message extends Entity
{
    public $id;
    public bool $isActive = true;
    public bool $isRead = false;
    public $messageFromUserId;
    public $messageToUserId;
    public $friendId;
    public $friendLogin;
    public $friendName;
    public $friendLastName;
    private bool $isFile = false;
    private bool $isAudio = false;
    private bool $isImage = false;
    public $message;
    public $created;
    public $updated = null;

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
            'is_file'          => ['type' => 'bool', 'field' => 'isFile'],
            'is_audio'         => ['type' => 'bool', 'field' => 'isAudio'],
            'is_image'         => ['type' => 'bool', 'field' => 'isImage'],
            'message'          => ['type' => 'string', 'field' => 'message'],
            'created'          => ['type' => 'datetime', 'field' => 'created'],
            'updated'          => ['type' => 'datetime', 'field' => 'updated'],
        ];
    }

    public function save()
    {
        return (new \Models\Message())->init($this)->save();
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

    public function isRead(): bool
    {
        return $this->isRead;
    }

    public function setIsRead(bool $isRead): void
    {
        $this->isRead = $isRead;
    }

    public function getMessageFromUserId()
    {
        return $this->messageFromUserId;
    }

    public function setMessageFromUserId($messageFromUserId): void
    {
        $this->messageFromUserId = $messageFromUserId;
    }

    public function getMessageToUserId()
    {
        return $this->messageToUserId;
    }

    public function setMessageToUserId($messageToUserId): void
    {
        $this->messageToUserId = $messageToUserId;
    }

    public function getFriendId()
    {
        return $this->friendId;
    }

    public function setFriendId($friendId): void
    {
        $this->friendId = $friendId;
    }

    public function getFriendLogin()
    {
        return $this->friendLogin;
    }

    public function setFriendLogin($friendLogin): void
    {
        $this->friendLogin = $friendLogin;
    }

    public function getFriendName()
    {
        return $this->friendName;
    }

    public function setFriendName($friendName): void
    {
        $this->friendName = $friendName;
    }

    public function getFriendLastName()
    {
        return $this->friendLastName;
    }

    public function setFriendLastName($friendLastName): void
    {
        $this->friendLastName = $friendLastName;
    }

    public function isFile(): bool
    {
        return $this->isFile;
    }

    public function setIsFile($isFile): void
    {
        $this->isFile = $isFile;
    }

    public function isAudio(): bool
    {
        return $this->isAudio;
    }

    public function setIsAudio($isAudio): void
    {
        $this->isAudio = $isAudio;
    }

    public function isImage(): bool
    {
        return $this->isImage;
    }

    public function setIsImage($isImage): void
    {
        $this->isImage = $isImage;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function setMessage($message): void
    {
        $this->message = $message;
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


}
