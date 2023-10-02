<?php
namespace Controllers;

use Entity\Friend;
use System\Request;

class Search extends Controller
{
    protected function before()
    {
        $this->checkAuthorization();
    }

    /**
     * Выводит страницу с сообщениями
     */
    protected function actionDefault()
    {
        $query = Request::get('q');

        $this->set('users', Friend::search(['login' => $query, 'user_id' => $this->user->id]));
        if (Request::isAjax()) echo $this->render('search/user-list');
        else $this->display('search/users');
    }
}