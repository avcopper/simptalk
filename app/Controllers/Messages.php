<?php
namespace Controllers;

use System\Crypt;
use Entity\Friend;
use Entity\Message;
use System\Request;
use Exceptions\NotFoundException;
use \Models\Message as ModelMessage;

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
        $this->set('messages', Message::getUserList(['user_id' => $this->user->id, 'order' => 'desc']));
        $this->display('message/users');
    }

    /**
     * Показывает переписку с собеседником
     * @param string $friend_id - login собеседника
     * @throws NotFoundException
     */
    protected function actionShow(string $friend_id, int $last_id = 0)
    {
        //$friend_id = preg_replace('/[^0-9A-Za-z-_]/', '', $friend_id);
        $friend = Friend::get(['login' => $friend_id]);
        if (empty($friend)) throw new NotFoundException('User not found');

        if (!Request::isAjax()) $this->set('showDate', true);
        $this->set('friend', $friend);
        $this->set('cryptFriend', new Crypt($friend->publicKey));
        $this->set('messages', Message::getList(['user_id' => $this->user->id, 'friend_id' => $friend->id, 'start' => $last_id]));

        if (Request::isAjax()) $this->display_element('message/message-list');
        else $this->display('message/messages');
    }

    /**
     * Отправляет сообщение собеседнику
     * @param string $friend_id - login собеседника
     * @throws NotFoundException
     */
    protected function actionSend(string $friend_id, int $last_id = 0)
    {
        if (Request::isPost()) {
            $friend = Friend::get(['login' => $friend_id]);
            $message = trim(Request::post('message'));
            if (empty($friend)) throw new NotFoundException('User not found');

            if (ModelMessage::checkData($friend, $message) && ModelMessage::saveMessage($this->user, $friend->id, $message)) {
                $this->actionShow($friend->login, $last_id);
            }
        }
    }
}
