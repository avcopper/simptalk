<?php
namespace Entity;

use Traits\Magic;

abstract class Entity
{
    use Magic;

    public function init(?array $data, array $properties = [])
    {
        if (empty($data)) return null;

        $fields = $properties ?: $this->getFields();

        foreach ($fields as $key => $field) {
            $prop = $field['field'];
            $type = $field['type'];

            if (isset($data[$key])) {
                switch ($type) {
                    case 'int':
                        $this->$prop = (int) $data[$key];
                        break;
                    case 'float':
                        $this->$prop = (float) $data[$key];
                        break;
                    case 'string':
                        $this->$prop = (string) $data[$key];
                        break;
                    case 'bool':
                        $this->$prop = (bool) $data[$key];
                        break;
                    case 'datetime':
                        $this->$prop =
                            !empty($data[$key]) ?
                                ($data[$key] instanceof \DateTime ?
                                    $data[$key] :
                                    (is_string($data[$key]) ?
                                        \DateTime::createFromFormat('Y-m-d H:i:s', $data[$key]) :
                                        (is_array($data[$key]) ?
                                            \DateTime::__set_state($data[$key]) :
                                            null))) :
                                null;
                        break;
                    default:
                        $this->$prop = $data[$key];
                        break;
                }
            }
        }

        return $this;
    }

    public function save()
    {
        switch (get_class($this)) {
            case 'Entity\User':
                $object = new \Models\User();
                break;
            case 'Entity\UserSession':
                $object = new \Models\UserSession();
                break;
            case 'Entity\Message':
                $object = new \Models\Message();
                break;
            default:
                return false;
        }

        $fields = $this->getFields();

        foreach ($fields as $key => $field) {
            if (!property_exists($object, $key)) continue;

            $prop = $field['field'];

            switch ($field['type']) {
                case 'int':
                    $object->$key = (int) $this->$prop;
                    break;
                case 'float':
                    $object->$key = (float) $this->$prop;
                    break;
                case 'string':
                    $object->$key = (string) $this->$prop;
                    break;
                case 'bool':
                    $object->$key = !empty($this->$prop) ? (bool) $this->$prop : null;
                    break;
                case 'datetime':
                    $object->$key =
                        !empty($this->$prop) ?
                            ($this->$prop instanceof \DateTime ?
                                $this->$prop->format('Y-m-d H:i:s') :
                                (is_string($this->$prop) ? $this->$prop : null)) :
                            null;
                    break;
                default:
            }
        }

        return $object->save();
    }
}
