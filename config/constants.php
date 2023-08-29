<?php

use System\Logger;
use \System\Config;
use Models\Setting;
use Exceptions\DbException;

const _ROOT = __DIR__ . '/..';
const _APP = __DIR__ . '/../app';
const _CONFIG = __DIR__ . '/../config';
const _VENDOR = __DIR__ . '/../vendor';
const _LOGS = __DIR__ . '/../logs';
const _PUBLIC = __DIR__ . '/../public';
const _VIEWS = __DIR__ . '/../views';
const _CERTIFICATES = __DIR__ . '/../certificates';
const _TEMPLATES = __DIR__ . '/../views/templates';
define("CONFIG", Config::getInstance()->data);

try {
    $constants = Setting::getList();

    if (!empty($constants) && is_array($constants)) {
        foreach ($constants as $constant) {
            define(strtoupper($constant->name), $constant->value);
        }
    }
}
catch (DbException $e) {
    Logger::getInstance()->error($e);
    echo 'Нет соединения в базой данных. Попробуйте позже.';
    die;
}

//if (mb_strpos(mb_strtolower($_SERVER['REQUEST_URI']), 'api') === false)
//    setcookie('page', $_SERVER['REQUEST_URI'] ?? '/', time() + 60 * 60 * 24 * 365, '/', SITE, 0);

/*
Cookies:
page - запоминает последнюю посещенную страницу
user - хэш анонимного пользователя. служит для идентификации анонимных корзин
cookie_hash - кука пользователя. служит для запоминания авторизации
Session:
session_hash - сессия пользователя. служит для авторизации
 */
