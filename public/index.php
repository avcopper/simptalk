<?php
require __DIR__ . '/../config/autoload.php';
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../config/constants.php';

session_start();

use System\Route;
use System\Request;
use System\Response;
use System\Security;
use Controllers\Errors;
use Exceptions\DbException;
use Exceptions\EditException;
use Exceptions\MailException;
use Exceptions\UserException;
use Exceptions\DeleteException;
use Exceptions\UploadException;
use Exceptions\NotFoundException;
use Exceptions\ForbiddenException;

Security::array_xss_clean($_GET);
Security::array_xss_clean($_POST);

Route::parseUrl($_SERVER['REQUEST_URI']);

if (!defined('API')) {
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
    } catch (DeleteException | EditException | MailException | UploadException | UserException $e) {
        if (Request::isAjax()) Response::result(false, $e->getMessage());
        else (new Errors($e))->action('action400');
    }
} else {
    try {
        Route::startApi();
    } catch (Exception $e) {
        Response::apiResult($e->getCode(), false, $e->getMessage());
    }
}

session_destroy();
