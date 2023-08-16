<?php

namespace Controllers;

use System\OldRSA;
use Models\User;
use Models\Message;
use System\Request;

/**
 * Class Messages
 * @package Controllers
 */
class Messages extends Controller
{
    /**
     * Выводит страницу с сообщениями
     */
    protected function actionDefault()
    {
        $this->view->display('messages');
    }

    protected function actionShow(int $friend_id)
    {
        $friend = User::getById($friend_id);

//        $this->set('friend', $friend);
//        $this->set('messages', Message::getAll($this->user->id, $friend_id, 100));
//        $this->set('rsa_my', new OldRSA($this->user->private_key));
//        $this->set('rsa_friend', new OldRSA($friend->private_key));

        $this->view->display('message/message');
    }

    protected function actionSend(int $user_id)
    {

    }
}
