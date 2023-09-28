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
use Exceptions\DbException;
use Exceptions\UserException;
use Exceptions\NotFoundException;
use Exceptions\ForbiddenException;

Security::array_xss_clean($_GET);
Security::array_xss_clean($_POST);

Route::parseUrl($_SERVER['REQUEST_URI']);

try {
    Route::start();
} catch (DbException $e) {
    if (Request::isAjax()) Response::result(false, $e->getMessage());
    else (new Errors($e))->action('action500');
} catch (NotFoundException $e) {
    if (Request::isAjax()) Response::result(false, $e->getMessage());
    else (new Errors($e))->action('action404');
} catch (ForbiddenException $e) {
    if (Request::isAjax()) Response::result(false, $e->getMessage());
    else (new Errors($e))->action('action403');
} catch (UserException $e) {
    if (Request::isAjax()) Response::result(false, $e->getMessage());
    else (new Errors($e))->action('action400');
} catch (Exception $e) {
    if (Request::isAjax()) Response::result(false, $e->getMessage());
    else (new Errors($e))->action('action500');
}
