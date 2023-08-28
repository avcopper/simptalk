<?php

namespace Models;

class Smile extends Model
{
    protected static $db_table = 'smiles';

    public static function getCollection()
    {
        $result = [];
        $files = scandir(__DIR__ . '/../../public/images/emoji/');

        if (!empty($files) && is_array($files)) {
            foreach ($files as $file) {
                if ($file === '.' || $file === '..') continue;

                $result[] = $file;
            }
        }

        return $result;
    }
}
