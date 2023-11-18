<?php
namespace Controllers\User;

class Profile extends Index
{
    protected function before()
    {
        parent::before();
    }

    protected function actionDefault()
    {
        $this->view->display('user/profile');
    }
}
