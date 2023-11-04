<?php
namespace Controllers;

use Models\User;
use System\Request;
use Exceptions\DbException;
use Exceptions\UserException;
use Models\User as ModelUser;

/**
 * Class Auth
 * @package Controllers
 */
class Auth extends Controller
{
    protected function before()
    {
    }

    /**
     * @return void
     */
    protected function actionDefault()
    {
        if (ModelUser::isAuthorized()) {
            header('Location: /');
            die;
        }

        $this->setTemplate('simple');
        $this->display('auth');
    }

    /**
     * Авторизация пользователя
     * @throws UserException|DbException
     */
    protected function actionLogin()
    {
        if (Request::isPost()) {
            $login = Request::post('login');
            $password = Request::post('password');
            $remember = (bool)Request::post('remember');

            User::authorize($login, $password, $remember);
        }
    }

    /**
     * Выход
     */
    protected function actionLogout()
    {
        User::logout();
        $this->setTemplate('simple');
        $this->display('logout');
    }
}
