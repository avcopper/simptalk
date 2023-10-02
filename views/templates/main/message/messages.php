<style>
    /* MESSAGES */
    .message {
        flex: 1;
        position: relative;
        display: flex;
        flex-direction: column;
        height: 100%;
        background-color: #ffffff;
        overflow: hidden;
    }
    .message-title {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 50px;
        font: 14px/50px "OpenSansSemiBold";
        text-align: center;
        background-color: #ffffff;
        border-bottom: 1px solid #dddddd;
        /*z-index: 1;*/
    }
    .message-list {
        position: absolute;
        top: 50px;
        bottom: 73px;
        left: 0;
        right: 0;
        overflow-x: hidden;
        overflow-y: auto;
    }
    .message-new {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        min-height: 73px;
        padding: 15px 80px 15px 48px;
        background-color: #ffffff;
        border-top: 1px solid #dddddd;
        z-index: 1;
    }
    /* MESSAGE */
    .message-date {
        margin: 10px 0;
        color: #aaaaaa;
        text-align: center;
    }
    .message-item {
        position: relative;
        width: 75%;
        min-height: 62px;
        margin: 10px auto 10px 20px;
        padding: 10px 10px 10px 70px;
        background-color: #eeeeee;
        border: 1px solid #dddddd;
        border-radius: 5px;
        overflow: hidden;
        word-wrap: break-word;
    }
    .message-item.my {
        margin: 10px 20px 10px auto;
        background-color: #eaf5fd;
        border: 1px solid #c1daee;
    }
    .message-item.unread:before {
        position: absolute;
        top: 10px;
        right: 10px;
        width: 6px;
        height: 6px;
        content: "";
        background-color: #124c8d;
        border-radius: 50%;
    }
    .message-photo {
        position: absolute;
        top: 5px;
        left: 5px;
        width: 50px;
        height: 50px;
        border-radius: 50%;
        overflow: hidden;
    }
    .message-photo img {
        max-width: 100%;
        max-height: 100%;
    }
    .message-name {
        margin-bottom: 5px;
        font: 13px/16px 'OpenSansSemiBoldItalic';
    }
    .message-time {
        padding-left: 5px;
        color: #aaaaaa;
        font: 12px/15px 'OpenSansRegular';

    }
    .message-body {
        font: 13px/20px 'OpenSansRegular';
    }
    .message-body img {
        padding: 0 5px;
    }
    .message-new form {
        position: relative;
        padding: 10px;
        border: 1px solid #dddddd;
        border-radius: 5px;
    }
    .message-new label {
        display: block;
    }
    .message-text {
        display: inline-block;
        width: 100%;
        min-height: 20px;
        max-height: 120px;
        padding-right: 25px;
        font: 14px/20px 'OpenSansRegular';
        overflow-y: auto;
        resize: none;
        outline: none;
        cursor:text;
    }
    .message-text img {
        margin: 0 3px;
    }
    .message-file,
    .message-emoji,
    .message-send {
        cursor: pointer;
    }
    .message-file {
        position: absolute;
        bottom: 24px;
        left: 13px;
        width: 24px;
        height: 24px;
        background: url(data:image/svg+xml;charset=utf-8,%3Csvg%20height%3D%2224%22%20viewBox%3D%220%200%2024%2024%22%20width%3D%2224%22%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%3E%3Cg%20fill%3D%22none%22%20fill-rule%3D%22evenodd%22%3E%3Cpath%20d%3D%22m0%200h24v24h-24z%22%2F%3E%3Cpath%20d%3D%22m20.0291094%2015.0279907-5.384726%205.2303888c-2.5877049%202.513536-6.71408829%202.4838066-9.26530792-.0667538-2.6116233-2.6109485-2.61217034-6.8446794-.00122186-9.4563027.00760974-.0076117.01523784-.015205.02288425-.0227799l8.06657363-7.99110563c1.7601202-1.7436532%204.6004898-1.73030402%206.344143.02981623.0091252.00921136.0182104.01846224.0272554.02775238%201.7500823%201.79751906%201.7306631%204.66777042-.0435807%206.44144506l-8.1308667%208.12825806c-.8479169.8476448-2.20023168.9147308-3.12787932.1551687l-.1337127-.1094846c-.8947528-.7326277-1.02618115-2.0518803-.29355343-2.9466331.03855837-.047091.0791516-.0924786.12166404-.1360332l5.46733261-5.60136864%22%20stroke%3D%22%23828a99%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%20stroke-width%3D%221.8%22%2F%3E%3C%2Fg%3E%3C%2Fsvg%3E) 0 0 no-repeat;
    }
    .message-emoji {
        position: absolute;
        bottom: 24px;
        right: 45px;
        width: 34px;
        height: 34px;
        background: url(data:image/svg+xml;charset=utf-8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%2224%22%20height%3D%2224%22%20viewBox%3D%220%200%2024%2024%22%3E%3Cg%20fill%3D%22none%22%20fill-rule%3D%22evenodd%22%3E%3Cpath%20opacity%3D%22.4%22%20d%3D%22M0%200h24v24H0z%22%2F%3E%3Cpath%20fill%3D%22%23828A99%22%20fill-rule%3D%22nonzero%22%20d%3D%22M2%2012C2%206.477%206.477%202%2012%202s10%204.477%2010%2010-4.477%2010-10%2010S2%2017.523%202%2012zm18.3%200a8.3%208.3%200%201%200-16.6%200%208.3%208.3%200%200%200%2016.6%200zm-11.05-.5a1.25%201.25%200%201%201%200-2.5%201.25%201.25%200%200%201%200%202.5zm5.5%200a1.25%201.25%200%201%201%200-2.5%201.25%201.25%200%200%201%200%202.5z%22%2F%3E%3Cpath%20stroke%3D%22%23828A99%22%20stroke-width%3D%221.7%22%20d%3D%22M9%2014.85c.833.767%201.833%201.15%203%201.15s2.167-.383%203-1.15%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%2F%3E%3C%2Fg%3E%3C%2Fsvg%3E) no-repeat right bottom;
    }
    .message-smiles {
        display: none;
        position: absolute;
        bottom: 25px;
        right: 0;
        width: 269px;
        /*height: 240px;*/
        padding: 5px;
        background-color: #ffffff;
        border: 1px solid #dddddd;
        border-radius: 5px;
        cursor: default;
        overflow: hidden;
        z-index: 1;
    }
    .message-smiles img {
        display: inline-block;
        width: 16px;
        height: 16px;
        padding: 2px;
        cursor: pointer;
    }
    .message-send {
        position: absolute;
        bottom: 24px;
        right: 13px;
        width: 24px;
        height: 24px;
    }
    .message-send:before {
        content: '\f1d8';
        font: 20px/24px 'FontAwesome';
    }
</style>

<?php
/**
 * @var \Entity\User $user
 * @var \System\Crypt $crypt
 * @var \Entity\Friend $friend
 * @var \System\Crypt $cryptFriend
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
                last = $('#message-list .message-item').last().data('id'),
                friend = <?= json_encode($friend->login) ?>;

            $.ajax({
                method: "GET",
                dataType: 'text',
                url: "/messages/send/" + friend + "/" + last + "/",
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
            message = $('#message-text').html(),
            friend = <?= json_encode($friend->login) ?>;

        if(message.length > 0) {
            $.ajax({
                method: "POST",
                dataType: 'text',
                url: "/messages/send/" + friend + "/" + last + "/",
                data: {'message': message},
                beforeSend: function() {
                    clearInterval(timerMessages);
                    $('.message-text').html('');
                },
                success: function(data, textStatus, jqXHR) {//console.log(data);
                    $('.message-list').append(data);
                    messageList.scrollTop = messageList.scrollHeight;
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    if (textStatus === 'error' && jqXHR.status === 403 && errorThrown === 'Forbidden')
                        window.location.href = '/auth/';
                }
            });
        }
    }
</script>
