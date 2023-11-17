<?php
namespace Controllers;

use System\Crypt;
use Entity\Friend;
use Entity\Message;
use System\Request;
use System\Response;
use Exceptions\NotFoundException;
use \Models\File as ModelFile;
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
        $this->set('messages', Message::getUserList(['user_id' => $this->user->getId(), 'order' => 'desc']));
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
        $this->set('cryptFriend', new Crypt($friend->getPublicKey()));
        $this->set('messages', Message::getList(['user_id' => $this->user->getId(), 'friend_id' => $friend->getId(), 'limit' => 100]));
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
        if (is_numeric($friend_id) && Request::isPost() && (!empty($_FILES['chat-file']) || !empty($_POST['message']))) {
            $friend = Friend::get(['id' => intval($friend_id)]);
            if (empty($friend)) throw new NotFoundException('User not found');

            if (ModelFile::checkUserFile($_FILES['chat-file'])) $fileId = $this->saveUserFile($friend);
            $this->saveMessage($friend, $fileId ?? null);
            $this->actionGet($friend->getId(), $last_id);
        }
    }

    private function saveUserFile(Friend $friend)
    {
        if (ModelMessage::checkUser($friend))
            return ModelFile::saveFile($this->user, $friend->getId(), $_FILES['chat-file']);

        return false;
    }

    private function saveMessage(Friend $friend, ?int $fileId)
    {
        $message = Request::post('chat-input');

        return ModelMessage::checkData($friend, $message, $fileId) &&
            ModelMessage::saveMessage($this->user, $friend->getId(), $message, $fileId);
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
            $this->set('cryptFriend', new Crypt($friend->getPublicKey()));
            $this->set('messages', Message::getList(['user_id' => $this->user->getId(), 'friend_id' => $friend->getId(), 'start' => $last_id]));
            //$this->display_element('message/message-list');
            Response::result(200, true, $this->render('message/message-list'));
        }
    }
}
