<?php

namespace Models;

use System\Db;
use Traits\Magic;
use Traits\CastableToArray;

/**
 * Class Model
 * @package App\Models
 */
abstract class Model
{
    protected static $db_prefix = CONFIG['db']['dbprefix'];
    protected static $db_table = null;

    use Magic;
    use CastableToArray;

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
     * @param string $order
     * @param string $sort
     * @param bool $active
     * @param bool $object
     * @return array|bool
     */
    public static function getList(string $order = 'created', string $sort = 'ASC', bool $active = true, bool $object = true)
    {
        $db = Db::getInstance();
        $activity = !empty($active) ? 'WHERE active IS NOT NULL' : '';
        $db->sql = "SELECT * FROM " . self::$db_prefix . static::$db_table . " {$activity} ORDER BY {$order} " . strtoupper($sort);
        $data = $db->query($object ? static::class : null);
        return $data ?? false;
    }

    /**
     * Находит и возвращает одну запись из БД по id
     * @param int $id
     * @param bool $active
     * @param bool $object
     * @return bool|mixed
     */
    public static function getById(int $id, bool $active = true)
    {
        $db = Db::getInstance();
        $where = !empty($active) ? ' AND active IS NOT NULL' : '';
        $db->params = ['id' => $id];
        $db->sql = "SELECT * FROM " . self::$db_prefix . static::$db_table . " WHERE id = :id {$where}";
        $data = $db->query();
        return !empty($data) ? array_shift($data) : false;
    }












    /**
     * Находит и возвращает одну запись из БД по полю и его значению
     * @param string $field
     * @param string $value
     * @param bool $active
     * @param bool $object
     * @return array|false
     */
    public static function getByField(string $field, string $value, bool $active = true, bool $object = true)
    {
        $db = Db::getInstance();
        $activity = !empty($active) ? ' AND active IS NOT NULL' : '';
        $db->params = ['value' => $value];
        $db->sql = "SELECT * FROM `" . self::$db_prefix . static::$db_table . "` WHERE {$field} = :value {$activity}";
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
            //if ($val === null) continue;
            $cols[] = $key;
            $db->params[$key] = $val;
        }
        $db->sql =  "
            INSERT INTO " . self::$db_prefix . static::$db_table . " (" . implode(', ', $cols) . ") 
            VALUES (" . ":" . implode(', :', $cols) . ")";
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
