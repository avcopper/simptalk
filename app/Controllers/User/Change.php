<?php
namespace Controllers\User;

class Change extends Index
{
    protected function before()
    {
        parent::before();
    }

    protected function actionDefault()
    {
        $this->setTemplate('simple');
        $this->view->display('change');
    }
}
