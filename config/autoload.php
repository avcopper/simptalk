<?php

spl_autoload_register(
    function ($class){
        $name = str_replace('\\', '/', $class);

        if (defined('API') && mb_strpos(mb_strtolower($class), 'controllers') !== false) {
            $name = str_replace('Api/', '', $name);
            $file_api = _API . '/' . API_VERSION . '/' . $name . '.php';
            if (file_exists($file_api)) require_once $file_api;
        }
        else {
            $file = _APP . '/' . $name . '.php';
            if (file_exists($file)) require_once $file;
        }
    }
);
