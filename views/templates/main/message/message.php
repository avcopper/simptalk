<?php
/**
 * @var \System\Crypt $crypt
 * @var \System\Crypt $cryptFriend
 * @var \Entity\User $user
 * @var \Entity\Friend $friend
 */

$name = !empty($user->name) ? $crypt->decryptByPublicKey($user->name) : '';
$secondName = !empty($user->secondName) ? $crypt->decryptByPublicKey($user->secondName) : '';
$lastName = !empty($user->lastName) ? $crypt->decryptByPublicKey($user->lastName) : '';

$friendName = !empty($friend->name) ? $cryptFriend->decryptByPublicKey($friend->name) : '';
$friendSecondName = !empty($friend->secondName) ? $cryptFriend->decryptByPublicKey($friend->secondName) : '';
$friendLastName = !empty($friend->lastName) ? $cryptFriend->decryptByPublicKey($friend->lastName) : '';

?>

<div class="message">
    <div class="message-title"><?= "{$friendName} {$friendSecondName} {$friendLastName}" ?></div>

    <div class="message-list" id="message-list">
        <?= $this->render('message/message-list', [
                'crypt' => $crypt,
                'cryptFriend' => $cryptFriend,
                'friendName' => $friendName,
                'friendSecondName' => $friendSecondName,
                'friendLastName' => $friendLastName,
                'name' => $name,
                'secondName' => $secondName,
                'lastName' => $lastName,
            ]
        )
        ?>
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
            <input type="hidden" name="message_to" value="<?= $friend->id ?>">
            <div class="message-text" tabindex="0" contenteditable="true" id="message-text" role="textbox" aria-multiline="true" ondragend="return true"></div>
        </form>

        <div class="message_error"></div>
    </div>
</div>

<script>
    $(function () {
        let messageList = document.querySelector('.message-list'),
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

        /* отправка ссобщения по нажатию кнопки */
        // $('.message-send').on('click', function (e) {
        //     let data = {
        //             'friend_id': $('input[name=message_to]').val(),
        //             'last': $('#message-list .message-item').last().data('id'),
        //             'message': messageText.html(),
        //         };
        //     if(data.message.length) sendMessage(data);
        // });

        /* отправка ссобщения по нажатию enter */
        // messageText.on('keydown', function (e) {
        //     //if (e.ctrlKey && e.which === 13) message.append(document.createElement('br'));
        //     if (e.ctrlKey && e.keyCode === 13) {
        //         e.preventDefault();
        //         let data = {
        //                 'friend_id': $('input[name=message_to]').val(),
        //                 'last': $('#message-list .message-item').last().data('id'),
        //                 'message': messageText.html(),
        //             };
        //         if(data.message.length) sendMessage(data);
        //     }
        // });

        // function sendMessage(data) {
        //     $.ajax({
        //         method: "POST",
        //         dataType: 'text',
        //         url: '/messages/send/' + data.friend_id + '/',
        //         data: data,
        //         beforeSend: function() {
        //         },
        //         success: function(data){
        //             $('.message-text').html('');
        //             $('.message-list').append(data);
        //             messageList.scrollTop = messageList.scrollHeight;
        //         }
        //     });
        // }

    //     $('.message-smiles img').on('click', function () {
    //         let img = ' <img src="'+ this.src +'">';
    //         //document.execCommand('insertHTML', false, img);
    //         message.append(this);
    //     });
    //     $('.message-emoji').on('mouseover', function () {
    //         $('.message-smiles').show();
    //     });
    //     $('.message-emoji').on('mouseout', function () {
    //         $('.message-smiles').hide();
    //     });
    });
</script>
