<?php
namespace Controllers;

use Entity\Friend;
use System\Request;

class Search extends Index
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
        $query = Request::get('q');

        $this->set('users', Friend::search(['login' => $query, 'not_user_id' => $this->user->getId()]));
        if (Request::isAjax()) echo $this->render('search/user-list');
        else $this->display('search/users');
    }
}