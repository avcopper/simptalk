<?php

namespace Models;

use System\Db;
use Traits\Magic;
use Entity\Entity;

/**
 * Class Model
 * @package App\Models
 */
abstract class Model
{
    protected static $db_prefix = CONFIG['db']['dbprefix'];
    protected static $db_table = null;

    use Magic;

    public function init(?Entity $data)
    {
        if (empty($data)) return null;

        $fields = $data->getFields();

        foreach ($fields as $key => $field) {
            if (!property_exists($this, $key)) continue;

            $prop = $field['field'];

            switch ($field['type']) {
                case 'int':
                    $this->$key = (int) $data->$prop;
                    break;
                case 'float':
                    $this->$key = (float) $data->$prop;
                    break;
                case 'string':
                    $this->$key = (string) $data->$prop;
                    break;
                case 'bool':
                    $this->$key = !empty($data->$prop) ? (bool) $data->$prop : null;
                    break;
                case 'datetime':
                    $this->$key =
                        !empty($data->$prop) ?
                            ($data->$prop instanceof \DateTime ?
                                $data->$prop->format('Y-m-d H:i:s') :
                                (is_string($data->$prop) ? $data->$prop : null)) :
                            null;
                    break;
                default:
                    $this->$key = $data->$prop;
            }
        }

        return $this;
    }

    public function toArray()
    {
        $result = [];
        foreach ($this as $key => $value) {
            if ($key === 'data') continue;
            $result[$key] = $value;
        }

        return $result;
    }

    /**
     * Создает объект вызвавшего класса и заполняет его свойства
     * @param Model $item
     * @return Model|null
     */
    public static function factory(self $item)
    {
        if (!empty($item) && is_object($item)) {
            $object = new static();
            foreach (get_class_vars(get_called_class()) as $key => $field) {
                if ($key === 'table') continue;
                if (empty($item->$key)) continue;

                $object->$key = $item->$key ?? null;
            }
        }
        return $object ?? null;
    }

    /**
     * Находит и возвращает записи из БД
     * @param $params
     * $params['active'] - только активные сообщения
     * $params['sort'] - поле сортировки
     * $params['order'] - направление сортировки
     * $params['limit'] - лимит сообщений для выдачи
     * @return array|bool
     */
    public static function getList(?array $params = [])
    {
        $db = Db::getInstance();
        $active = !empty($params['active']) ? 'WHERE active IS NOT NULL' : '';
        $sort = !empty($params['sort']) ? $params['sort'] : 'id';
        $order = !empty($params['order']) ? strtoupper($params['order']) : 'ASC';
        $limit = !empty($params['limit']) ? "LIMIT {$params['limit']}" : '';

        $db->sql = "
            SELECT * 
            FROM " . self::$db_prefix . static::$db_table . " 
            {$active} 
            ORDER BY {$sort} {$order} 
            {$limit}";

        $data = $db->query(!empty($params['object']) ? static::class : null);
        return $data ?? false;
    }

    /**
     * Находит и возвращает одну запись из БД по id
     * @param int $id
     * @param bool $active
     * @param bool $object
     * @return bool|mixed
     */
    public static function getById(int $id, bool $active = true, bool $object = true)
    {
        $db = Db::getInstance();
        $where = !empty($active) ? ' AND active IS NOT NULL' : '';
        $db->params = ['id' => $id];
        $db->sql = "SELECT * FROM " . self::$db_prefix . static::$db_table . " WHERE id = :id {$where}";
        $data = $db->query($object ? static::class : null);
        return !empty($data) ? array_shift($data) : false;
    }

    /**
     * Сохраняет запись в БД
     * @return bool|int
     */
    public function save()
    {
        return $this->isNew() ? $this->insert() : $this->update();
    }

    /**
     * Проверяет добавляется новый элемент или редактируется существующий
     * @return bool
     */
    public function isNew(): bool
    {
        return !(!empty($this->id) && !empty(self::getById($this->id, false)));
    }

    /**
     * Добавляет запись в БД
     * @return bool|int
     */
    public function insert()
    {
        $db = Db::getInstance();
        $cols = [];
        $db->params = [];
        foreach ($this as $key => $val) {
            if ($val === null) continue;
            if ($key === 'data') continue;
            $cols[] = $key;
            $db->params[$key] = $val;
        }
        $db->sql =  "
            INSERT INTO " . self::$db_prefix . static::$db_table . " (" . implode(', ', $cols) . ") 
            VALUES (" . ":" . implode(', :', $cols) . ")";//echo json_encode($db->sql);die;
        $res = $db->execute();
        return !empty($res) ? $db->lastInsertId() : false;
    }

    /**
     * Обновляет запись в БД
     */
    public function update()
    {
        $db = Db::getInstance();
        $binds = [];
        $db->params = [];
        foreach ($this as $key => $val) {
            //if ($val === null) continue;
            if ($key === 'data') continue;
            if ('id' !== $key) $binds[] = $key . ' = :' . $key;
            $db->params[$key] = $val;
        }
        $db->sql = 'UPDATE ' . self::$db_prefix . static::$db_table . ' SET ' . implode(', ', $binds) . ' WHERE id = :id';

        return $db->execute() ? $this->id : false;
    }

    /**
     * Удаляет запись из БД
     * @return bool
     */
    public function delete(): bool
    {
        $db = Db::getInstance();
        $db->params = [':id' => $this->id];
        $db->sql = "DELETE FROM " . self::$db_prefix . static::$db_table . " WHERE id = :id";
        return $db->execute();
    }

    /**
     * Возвращает количество записей в таблице
     * @return bool|int
     */
    public static function count()
    {
        $db = Db::getInstance();
        $db->sql = "SELECT COUNT(*) count FROM " . self::$db_prefix . static::$db_table;
        $data = $db->query(static::class);
        return !empty($data) ? (int)array_shift($data)->count : false;
    }

    /**
     * Заполняет поля модели данными из массива
     * Запускает метод обработки даного поля, если он существует
     * @param array $data
     * @return $this
     */
    public function fill(array $data)
    {
        foreach ($data as $key => $value) {
            $method = 'filter_' . mb_strtolower($key);
            if (method_exists($this, $method)) $value = $this->$method($value);
            if ($value === '') $value = null;
            $this->$key = $value;
        }
        return $this;
    }
}
