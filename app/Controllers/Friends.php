<?php
namespace Controllers;

use Entity\Friend;
use System\Request;

class Friends extends Index
{
    protected function before()
    {
        parent::before();
    }

    /**
     * Выводит страницу с сообщениями
     */
    protected function actionDefault()
    {
        $this->display('friends');
    }
}