<?php
namespace Controllers;

use Models\User;
use System\Request;
use Exceptions\DbException;
use Exceptions\UserException;
use Models\User as ModelUser;

/**
 * Class Register
 * @package Controllers
 */
class Register extends Controller
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
        $this->display('register');
    }
}
