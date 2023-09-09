<?php

namespace Models;

class Setting extends Model
{
    protected static $db_table = 'mesigo.settings';

    /**
     * Возвращает массив настроек из БД
     * @return array
     */
    public static function getSiteSettings()
    {
        $settings = [];
        $data = Setting::getList();

        if (!empty($data) && is_array($data)) {
            foreach ($data as $item) {
                $settings[$item->name] = $item->value;
            }
        }

        return $settings;
    }

    public function filter_id($id)
    {
        return (int)$id;
    }

    public function filter_active($value)
    {
        return (int)$value;
    }

    public function filter_name($text)
    {
        return strip_tags(trim($text));
    }

    public function filter_value($text)
    {
        return strip_tags(trim($text));
    }

    public function filter_description($text)
    {
        return strip_tags(trim($text), '<p><div><span><b><strong><i><br><h1><h2><h3><h4><h5><h6><ul><ol><li><a><table><tr><th><td><caption>');
    }
}
