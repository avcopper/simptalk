<?php
namespace Entity;

use Models\File as ModelFile;

class File extends Entity
{
    protected $id;
    protected bool $isActive = true;
    protected int $userId;
    protected ?int $albumId = null;
    protected string $fileName;
    protected string $fileLink;
    protected $created;
    protected ?string $updated = null;

    public static function getList(array $params)
    {
        $messages = ModelFile::getList($params);
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

    public static function get(array $params)
    {
        switch (true) {
            case !empty($params['id']):
                $user = ModelFile::getById($params['id'], $params);
                break;
            case !empty($params['message_id']):
                $user = ModelFile::getByMessageId($params['message_id'], $params);
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
            'id'        => ['type' => 'int', 'field' => 'id'],
            'active'    => ['type' => 'bool', 'field' => 'isActive'],
            'user_id'   => ['type' => 'int', 'field' => 'userId'],
            'album_id'  => ['type' => 'int', 'field' => 'albumId'],
            'name'      => ['type' => 'string', 'field' => 'fileName'],
            'link'      => ['type' => 'string', 'field' => 'fileLink'],
            'created'   => ['type' => 'datetime', 'field' => 'created'],
            'updated'   => ['type' => 'datetime', 'field' => 'updated'],
        ];
    }

    public function save()
    {
        return (new ModelFile())->init($this)->save();
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

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }

    public function getAlbumId(): ?int
    {
        return $this->albumId;
    }

    public function setAlbumId(?int $albumId): void
    {
        $this->albumId = $albumId;
    }

    public function getFileName(): string
    {
        return $this->fileName;
    }

    public function setFileName(string $fileName): void
    {
        $this->fileName = $fileName;
    }

    public function getFileLink(): string
    {
        return $this->fileLink;
    }

    public function setFileLink(string $fileLink): void
    {
        $this->fileLink = $fileLink;
    }

    public function getCreated()
    {
        return $this->created;
    }

    public function setCreated($created): void
    {
        $this->created = $created;
    }

    public function getUpdated(): ?string
    {
        return $this->updated;
    }

    public function setUpdated(?string $updated): void
    {
        $this->updated = $updated;
    }
}
