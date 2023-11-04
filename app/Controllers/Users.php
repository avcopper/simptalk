<?php
namespace Controllers;

use Models\User;
use System\Request;
use Exceptions\DbException;
use Exceptions\UserException;
use Models\User as ModelUser;

/**
 * Class Users
 * @package Controllers
 */
class Users extends Controller
{
    protected function before()
    {
    }

    protected function actionChange()
    {
        $this->setTemplate('simple');
        $this->display('change');
    }
}
