<?php
namespace Controllers;

use Entity\Friend;
use System\Request;

class Bookmarks extends Index
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
        $this->display('bookmarks');
    }
}