<?php
use Entity\User;
use System\Crypt;
use Entity\Friend;

/**
 * @var User $user
 * @var Crypt $crypt
 * @var Friend $friend
 * @var Crypt $cryptFriend
 */

$this->friendName = !empty($friend->name) ? $cryptFriend->decryptByPublicKey($friend->name) : '';
$this->friendSecondName = !empty($friend->secondName) ? $cryptFriend->decryptByPublicKey($friend->secondName) : '';
$this->friendLastName = !empty($friend->lastName) ? $cryptFriend->decryptByPublicKey($friend->lastName) : '';
?>

<div class="user-chat w-100 overflow-hidden">
    <div class="user-chat-overlay"></div>

    <div class="chat-content d-lg-flex">
        <div class="w-100 overflow-hidden position-relative">
            <div id="users-chat" class="position-relative">
                <?= $this->render('message/message-header') ?>

                <div id="chat-conversation" class="chat-conversation p-3" data-simplebar>
                    <ul id="users-conversation" class="list-unstyled chat-conversation-list">
                        <?= $this->render('message/message-examples') ?>

                        <?= $this->render('message/message-list') ?>
                    </ul>
                </div>
            </div>

            <?= $this->render('message/message-input') ?>
        </div>

        <?= $this->render('message/message-user') ?>
    </div>
</div>

<?= $this->render('message/message-audio') ?>

<?= $this->render('message/message-video') ?>

<script>
    $(function () {
        let messageWrapper = $("#chat-conversation .simplebar-content-wrapper"),
            messageList = $("#users-conversation"),
            friend = <?= json_encode($friend->id) ?>,
            timerMessages = getMessages(friend, messageWrapper);

        messageWrapper && (messageWrapper.scrollTop(messageList.height()));

        $('#chat-send').on('click', function (e) {
            e.preventDefault();
            sendMessage(friend, messageWrapper, timerMessages);
        });

        $('#chat-input').on('keydown', function (e) {
            //if (e.ctrlKey && e.which === 13) message.append(document.createElement('br'));
            if (e.ctrlKey && e.keyCode === 13) {
                e.preventDefault();
                sendMessage(friend, messageWrapper, timerMessages);
            }
        });

        new FgEmojiPicker({
            trigger: [".emoji-btn"],
            removeOnSelection: !1,
            closeButton: !0,
            position: ["top", "right"],
            preFetch: !0,
            dir: "",
            insertInto: document.querySelector(".chat-input")
        });
    });
</script>
