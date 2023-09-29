<?php
session_start();
require __DIR__ . '/../config/autoload.php';
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../config/constants.php';
require __DIR__ . '/../app/System/ErrorSupervisor.php';

use System\Route;
use System\Request;
use System\Response;
use System\Security;
use Controllers\Errors;

Security::array_xss_clean($_GET);
Security::array_xss_clean($_POST);

Route::parseUrl($_SERVER['REQUEST_URI']);

try {
    Route::start();
} catch (Exception $e) {
    if (Request::isAjax()) Response::result($e->getCode(), false, $e->getMessage());
    else (new Errors($e))->action('actionError', $e->getCode());
} catch(TypeError $e) {
    if (Request::isAjax()) Response::result($e->getCode(), false, $e->getMessage());
    else (new Errors($e))->action('actionError', $e->getCode());
}
