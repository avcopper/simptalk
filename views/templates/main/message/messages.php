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

$this->friendName = !empty($friend->getName()) ? $cryptFriend->decryptByPublicKey($friend->getName()) : '';
$this->friendSecondName = !empty($friend->getSecondName()) ? $cryptFriend->decryptByPublicKey($friend->getSecondName()) : '';
$this->friendLastName = !empty($friend->getLastName()) ? $cryptFriend->decryptByPublicKey($friend->getLastName()) : '';
?>

<link href="/css/fg-emoji-picker.css" rel="stylesheet" type="text/css"/>
<link href="/css/audio-player.css" rel="stylesheet" type="text/css"/>

<div class="user-chat w-100 overflow-hidden">
    <div class="user-chat-overlay"></div>

    <div class="chat-content d-lg-flex">
        <div class="w-100 overflow-hidden position-relative">
            <div id="users-chat" class="position-relative">
                <?= $this->render('message/message-header') ?>

                <div id="chat-conversation" class="chat-conversation p-3" data-simplebar>
                    <ul id="users-conversation" class="list-unstyled chat-conversation-list">
                        <?= $this->render('message/message-list') ?>
                    </ul>
                </div>
            </div>

            <?= $this->render('message/message-input') ?>
        </div>

        <?= $this->render('message/message-user') ?>
    </div>
</div>

<?= $this->render('message/call-audio') ?>

<?= $this->render('message/call-video') ?>

<script src="/js/audio-player.js"></script>
<script>
    $(function () {
        let messageWrapper = $("#chat-conversation .simplebar-content-wrapper"),
            messageList = $("#users-conversation"),
            friend = <?= json_encode($friend->getId()) ?>,
            timerMessages = getMessages(friend);

        messageWrapper && (messageWrapper.scrollTop(messageList.height()));

        new FgEmojiPicker({
            trigger: [".emoji-btn"],
            removeOnSelection: !0,
            closeButton: !0,
            position: ["top", "right"],
            preFetch: !0,
            dir: "",
            insertInto: document.querySelector("#chat-input")
        });

        $('#chat-send').on('click', function (e) {
            e.preventDefault();
            sendMessage(friend, timerMessages);
        });

        $('#chat-input').on('keydown', function (e) {
            if (e.ctrlKey && e.keyCode === 13) {
                e.preventDefault();
                sendMessage(friend, timerMessages);
            }
        });

        $('#audio-input').on('click', function () {
            recordAudio();
        });

        $('.file-block-close').on('click', function () {
            hideFileBlock();
        });

        $('#user-file, #image-file, #audio-file').on('change', function () {
            addAudioToForm($(this).get(0).files);
            $('#chatinputmore').toggleClass('show');
        });
    });
</script>
