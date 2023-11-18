<?php
namespace Controllers\User;

class Settings extends Index
{
    protected function before()
    {
        parent::before();
    }

    protected function actionDefault()
    {
        $this->view->display('user/settings');
    }
}
