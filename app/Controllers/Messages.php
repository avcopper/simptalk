<?php
namespace Controllers;

use System\Crypt;
use Entity\Friend;
use Entity\Message;
use System\Request;
use Exceptions\NotFoundException;
use \Models\Message as ModelMessage;
use System\Response;

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
     * @param int $friend_id - login собеседника
     * @throws NotFoundException
     */
    protected function actionShow(int $friend_id)
    {
        $friend = Friend::get(['id' => $friend_id]);
        if (empty($friend)) throw new NotFoundException('User not found');

        $this->set('showDate', true);
        $this->set('friend', $friend);
        $this->set('cryptFriend', new Crypt($friend->publicKey));
        $this->set('messages', Message::getList(['user_id' => $this->user->id, 'friend_id' => $friend->id, 'limit' => 100]));

        $this->display('message/messages');
    }

    /**
     * Отправляет сообщение собеседнику
     * @param int $friend_id - login собеседника
     * @param int $last_id - id последнего сообщения
     * @throws NotFoundException
     */
    protected function actionSend(int $friend_id, int $last_id = 0)
    {
        if (is_numeric($friend_id) && Request::isPost()) {
            $friend = Friend::get(['id' => intval($friend_id)]);
            $message = trim(Request::post('message'));
            if (empty($friend)) throw new NotFoundException('User not found');

            if (ModelMessage::checkData($friend, $message) && ModelMessage::saveMessage($this->user, $friend->id, $message)) {
                $this->actionGet($friend->id, $last_id);
            }
        }
    }

    /**
     * Возвращает последние сообщения
     * @param int $friend_id - id собеседника
     * @param int $last_id - id последнего сообщения
     * @throws NotFoundException
     */
    protected function actionGet(int $friend_id, int $last_id = 0)
    {
        if (is_numeric($friend_id) && Request::isPost()) {
            $friend = Friend::get(['id' => intval($friend_id)]);
            if (empty($friend)) throw new NotFoundException('User not found');

            $this->set('friend', $friend);
            $this->set('cryptFriend', new Crypt($friend->publicKey));
            $this->set('messages', Message::getList(['user_id' => $this->user->id, 'friend_id' => $friend->id, 'start' => $last_id]));
            $this->display_element('message/message-list');

//            Response::result(200, true, 'OK',
//                Message::getList(['user_id' => $this->user->id, 'friend_id' => $friend->id, 'start' => $last_id])
//            );
        }
    }
}
