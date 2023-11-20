<?php
namespace Controllers;

use Entity\Friend;
use Exceptions\NotFoundException;

class Profile extends Controller
{
    protected function before()
    {
    }

    protected function actionDefault()
    {
        $this->view->display('user/profile');
    }

    /**
     * @throws NotFoundException
     */
    protected function actionShow(string $login)
    {
        if (is_numeric($login)) throw new NotFoundException('User not found');
        $user = Friend::get(['login' => $login]);
        if (empty($login)) throw new NotFoundException('User not found');





        $this->view->display('user/profile');
    }
}
