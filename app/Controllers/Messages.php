<?php
namespace Controllers;

use System\Crypt;
use Entity\Friend;
use Entity\Message;
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
        $friend = Friend::get(['id' => $friend_id]);
        $messages = Message::getList(['user_id' => $this->user->id, 'friend_id' => $friend->id]);
        $crypt = new Crypt($this->user->publicKey);
        $cryptFriend = new Crypt($friend->publicKey);

        $this->set('showDate', true);
        $this->set('friend', $friend);
        $this->set('messages', $messages);
        $this->set('crypt', $crypt);
        $this->set('cryptFriend', $cryptFriend);

        $this->view->display('message/messages');
    }

    protected function actionSend(int $friend_id)
    {
        // TODO добавить проверку разрешений писать выбранному адресату
        // TODO фильтровать сообщение

        if (Request::isPost()) {
            $params = Request::post();

            if (!empty($params['message']) && !empty($params['friend_id']) && intval($params['friend_id']) === $friend_id) {
                $crypt = new Crypt($this->user->publicKey, $this->user->privateKey);

                $message = new Message();
                $message->messageFromUserId = $this->user->id;
                $message->messageToUserId = $friend_id;
                $message->message = $crypt->encryptByPrivateKey($params['message']);
                $result = $message->save();

                echo json_encode($result);
                die;
            }
        }
    }
}
