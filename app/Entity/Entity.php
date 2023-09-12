<?php
namespace Entity;

abstract class Entity
{
    public function init(array $data, array $properties = [])
    {
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
}
