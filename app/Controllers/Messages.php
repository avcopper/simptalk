<?php
namespace Controllers;

use System\Crypt;
use Entity\Friend;
use Entity\Message;

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
        $friend = Friend::get(['id' => $friend_id]);
        $messages = Message::getList(['user_id' => $this->user->id, 'friend_id' => $friend->id]);
        $crypt = new Crypt($this->user->publicKey);
        $cryptFriend = new Crypt($friend->publicKey);

        $this->set('showDate', true);
        $this->set('friend', $friend);
        $this->set('messages', $messages);
        $this->set('crypt', $crypt);
        $this->set('cryptFriend', $cryptFriend);

        $this->view->display('message/message');
    }

    protected function actionSend(int $user_id)
    {
    }
}
