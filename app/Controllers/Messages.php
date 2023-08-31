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
    protected function before()
    {
        $this->checkAuthorization();
    }

    /**
     * Выводит страницу с сообщениями
     */
    protected function actionDefault()
    {
        $this->view->display('messages');
    }

    /**
     * Показывает переписку с собеседником
     * @param int $friend_id - id собеседника
     */
    protected function actionShow(int $friend_id)
    {
        $friend = Friend::get(['id' => $friend_id]);

        $this->set('showDate', true);
        $this->set('friend', $friend);
        $this->set('messages', Message::getList(['user_id' => $this->user->id, 'friend_id' => $friend->id]));
        $this->set('crypt', new Crypt($this->user->publicKey));
        $this->set('cryptFriend', new Crypt($friend->publicKey));
        $this->display('message/messages');
    }

    /**
     * Отправляет сообщение собеседнику
     * @param int $friend_id - id собеседника
     */
    protected function actionSend(int $friend_id)
    {
        // TODO добавить проверку разрешений писать выбранному адресату
        // TODO фильтровать сообщение

        if (Request::isPost()) {
            $params = Request::post();
            $friend = Friend::get(['id' => $friend_id]);

            if (!empty($params['message']) && !empty($params['friend']) && intval($params['friend']) === $friend_id && !empty($friend->id)) {
                if (\Models\Message::saveMessage($this->user, $friend_id, $params['message'])) {
                    $this->actionGet($friend_id, $params['last']);
                }
            }
        }
    }

    /**
     * Показывает последние сообщения
     * @param int $friend_id - id собеседника
     * @param int $last_id - id последнего сообщения
     */
    protected function actionGet(int $friend_id, int $last_id)
    {
        $friend = Friend::get(['id' => $friend_id]);
        $messages = Message::getList(['user_id' => $this->user->id, 'friend_id' => $friend_id, 'start' => $last_id]);

        $this->set('friend', $friend);
        $this->set('messages', $messages);
        $this->set('crypt', new Crypt($this->user->publicKey, $this->user->privateKey));
        $this->set('cryptFriend', new Crypt($friend->publicKey));
        $this->display_element('message/message-list');
    }
}
