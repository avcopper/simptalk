<?php
namespace Controllers;

use System\Crypt;
use Entity\Friend;
use Entity\Message;
use System\Request;
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
        $this->view->display('messages');
    }

    /**
     * Показывает переписку с собеседником
     * @param int $friend_id - id собеседника
     */
    protected function actionShow(int $friend_id, int $last_id = 0)
    {
        $friend = Friend::get(['id' => $friend_id]);

        if (!Request::isAjax()) $this->set('showDate', true);
        $this->set('friend', $friend);
        $this->set('messages', Message::getList(['user_id' => $this->user->id, 'friend_id' => $friend->id, 'start' => $last_id]));
        $this->set('crypt', new Crypt($this->user->publicKey));
        $this->set('cryptFriend', new Crypt($friend->publicKey));

        if (Request::isAjax()) $this->display_element('message/message-list');
        else $this->display('message/messages');
    }

    /**
     * Отправляет сообщение собеседнику
     * @param int $friend_id - id собеседника
     */
    protected function actionSend(int $friend_id, int $last_id = 0)
    {
        if (Request::isPost() && $this->canMessageUser($friend_id)) {
            $message = htmlspecialchars(
                strip_tags(nl2br(trim(Request::post('message')), '<br>')),
                ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML5
            );
            $friend = Friend::get(['id' => $friend_id]);

            if (!empty($message) && !empty($friend->id)) {
                if (ModelMessage::saveMessage($this->user, $friend_id, $message)) {
                    $this->actionShow($friend_id, $last_id);
                }
            }
        }
    }

    /**
     * Проверяет возможность писать сообщения собеседнику TODO доделать это
     * @param $id - id собеседника
     * @return bool
     */
    protected function canMessageUser($id)
    {
        return true;
    }
}
