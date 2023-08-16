<?php

namespace Controllers;

use Models\User;
use System\Api;
use System\Request;
use Exceptions\DbException;
use Exceptions\UserException;

/**
 * Class Auth
 * @package Controllers
 */
class Auth extends Controller
{
    /**
     * @return void
     */
    protected function actionDefault()
    {
        $this->view->display('auth');
    }

    /**
     * Авторизация пользователя
     * @throws UserException
     */
    protected function actionLogin()
    {

        if (Request::isPost()) {
            $login = Request::post('login');
            $password = Request::post('password');
            $remember = (bool)Request::post('remember');

            $api = new Api();
            $this->user = $api->authorize($login, $password, $remember);

            header('Location: /');
            die;
        }
    }

    /**
     * Выход
     */
    protected function actionLogout()
    {
        User::logout();
        header('Location: /');
        die;
    }
}
