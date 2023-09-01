<?php
/**
 * @var \System\Crypt $crypt
 * @var \System\Crypt $cryptFriend
 * @var \Entity\User $user
 * @var \Entity\Friend $friend
 */

$friendName = !empty($friend->name) ? $cryptFriend->decryptByPublicKey($friend->name) : '';
$friendSecondName = !empty($friend->secondName) ? $cryptFriend->decryptByPublicKey($friend->secondName) : '';
$friendLastName = !empty($friend->lastName) ? $cryptFriend->decryptByPublicKey($friend->lastName) : '';
?>

<div class="message">
    <div class="message-title">
        <?= "{$friendName} {$friendSecondName} {$friendLastName}" ?>
    </div>

    <div class="message-list" id="message-list">
        <?= $this->render('message/message-list') ?>
    </div>

    <div class="message-new">
        <div class="message-file"></div>
        <div class="message-emoji">
<!--            <div class="message-smiles">-->
<!--                --><?// if (!empty($smiles) && is_array($smiles)): ?>
<!--                    --><?// foreach ($smiles as $smile): ?>
<!--                        <img src="/images/emoji/--><?//= $smile ?><!--" alt="">-->
<!--                    --><?// endforeach; ?>
<!--                --><?// endif; ?>
<!--            </div>-->
        </div>
        <div class="message-send"></div>

        <form action="">
            <input type="hidden" name="friend" value="<?= $friend->id ?>">
            <div class="message-text" tabindex="0" contenteditable="true" id="message-text" role="textbox" aria-multiline="true" ondragend="return true"></div>
        </form>

        <div class="message_error"></div>
    </div>
</div>

<script>
    $(function () {
        let timerMessages,
            messageList = document.querySelector('.message-list'),
            messageText = $('#message-text'),
            messageTextHeight = messageText.height();

        messageList.scrollTop = messageList.scrollHeight;

        // вся эта байда для отслеживания размеров поля ввода сообщения
        new ResizeSensor($('.message-new'), function(){
            let delta = messageText.height() - messageTextHeight;
            $('#message-list').css('bottom', Number($('#message-list').css('bottom').replace('px', '')) + delta + 'px');
            messageList.scrollTop = messageList.scrollHeight;
            messageTextHeight += delta;
        });

        timerMessages = getMessages();

        /* отправка ссобщения по нажатию кнопки */
        $('.message-send').on('click', function (e) {
            sendMessage(timerMessages);
        });

        /* отправка ссобщения по нажатию ctrl+enter */
        messageText.on('keydown', function (e) {
            //if (e.ctrlKey && e.which === 13) message.append(document.createElement('br'));
            if (e.ctrlKey && e.keyCode === 13) {
                e.preventDefault();
                sendMessage(timerMessages);
            }
        });
    });

    function getMessages() {
        return setInterval(function () {
            let messageList = document.querySelector('.message-list'),
                last = $('#message-list .message-item').last().data('id');
            $.ajax({
                method: "GET",
                dataType: 'text',
                url: '/messages/' + <?= $friend->id ?> + '/' + last + '/',
                beforeSend: function() {
                },
                success: function(data, textStatus, jqXHR){//console.log(data);
                    if (textStatus === 'success' && jqXHR.status === 200 && data.length > 0) {
                        let needScroll = messageList.scrollHeight - messageList.scrollTop - messageList.clientHeight < 20;
                        $('.message-list').append(data);
                        if (needScroll) messageList.scrollTop = messageList.scrollHeight;
                    }
                },
                error: function(jqXHR, textStatus, errorThrown){
                    if (textStatus === 'error' && jqXHR.status === 403 && errorThrown === 'Forbidden')
                        window.location.href = '/auth/';
                }
            });
        }, 10000);
    }

    function sendMessage(timerMessages) {
        let messageList = document.querySelector('.message-list'),
            last = $('#message-list .message-item').last().data('id'),
            data = {'message': $('#message-text').html()};

        if(data.message.length > 0) {
            $.ajax({
                method: "POST",
                dataType: 'text',
                url: '/messages/send/' + <?= $friend->id ?> + '/' + last + '/',
                data: data,
                beforeSend: function() {
                    clearInterval(timerMessages);
                },
                success: function(data, textStatus, jqXHR){console.log(data);
                    $('.message-text').html('');
                    $('.message-list').append(data);
                    messageList.scrollTop = messageList.scrollHeight;
                },
                error: function(jqXHR, textStatus, errorThrown){
                    if (textStatus === 'error' && jqXHR.status === 403 && errorThrown === 'Forbidden')
                        window.location.href = '/auth/';
                }
            });
        }
    }
</script>
