<?php
use \System\Config;
use Models\Setting;
use Exceptions\DbException;
use System\Loggers\ErrorLogger;

const DIR_ROOT = __DIR__ . DIRECTORY_SEPARATOR . '..';
const DIR_APP = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'app';
const DIR_CONFIG = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'config';
const DIR_VENDOR = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'vendor';
const DIR_LOGS = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'logs';
const DIR_PUBLIC = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'public';
const DIR_VIEWS = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'views';
const DIR_CERTIFICATES = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'certificates';
const DIR_TEMPLATES = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'views/templates';
define("CONFIG", Config::getInstance()->data);

if (empty($_SESSION['protocol']) || empty($_SESSION['domain']) || empty($_SESSION['api_protocol']) ||
    empty($_SESSION['api_domain']) || empty($_SESSION['api_version']) || empty($_SESSION['auth_days']))
{
    try {
        $settings = Setting::getSiteSettings();

        $_SESSION['protocol'] = $settings['protocol'] ?: null;
        $_SESSION['domain'] = $settings['domain'] ?: null;
        $_SESSION['api_protocol'] = $settings['api_protocol'] ?: null;
        $_SESSION['api_domain'] = $settings['api_domain'] ?: null;
        $_SESSION['api_version'] = $settings['api_version'] ?: null;
        $_SESSION['sitename'] = $settings['sitename'] ?: null;
        $_SESSION['slogan'] = $settings['slogan'] ?: null;
        $_SESSION['template'] = $settings['template'] ?: null;
        $_SESSION['charset'] = $settings['charset'] ?: null;
        $_SESSION['auth_days'] = $settings['auth_days'] ?: null;
        $_SESSION['email'] = $settings['email'] ?: null;
        $_SESSION['tg'] = $settings['tg'] ?: null;
    }
    catch (DbException $e) {
        ErrorLogger::getInstance()->error($e);
        echo 'Нет соединения в базой данных. Попробуйте позже.';
        die;
    }
}

define("PROTOCOL", $_SESSION['protocol']);
define("DOMAIN", $_SESSION['domain']);
const SITE_URL = PROTOCOL . DOMAIN;

define("API_PROTOCOL", $_SESSION['api_protocol']);
define("API_DOMAIN", $_SESSION['api_domain']);
define("API_VERSION", $_SESSION['api_version']);
const API_URL = API_PROTOCOL . API_DOMAIN . '/' . API_VERSION;

define("SITENAME", $_SESSION['sitename']);
define("SLOGAN", $_SESSION['slogan']);

define("TEMPLATE", $_SESSION['template']);
define("CHARSET", $_SESSION['charset']);
define("AUTH_DAYS", $_SESSION['auth_days']);

define("EMAIL", $_SESSION['email']);
define("TG", $_SESSION['tg']);
