<?php
//$rsa_my = new System\OldRSA($user->private_key);
//$rsa_friend = new System\OldRSA($friend->private_key);
$date = '';
$_monthsList = [
    1 => 'января',
    2 => 'февраля',
    3 => 'марта',
    4 => 'апреля',
    5 => 'мая',
    6 => 'июня',
    7 => 'июля',
    8 => 'августа',
    9 => 'сентября',
    10 => 'октября',
    11 => 'ноября',
    12 => 'декабря'
];

if (!empty($messages) && is_array($messages)):
    foreach ($messages as $message):
        $_dt = DateTime::createFromFormat('Y-m-d H:i:s', $message->created);
        $time = $_dt->format('H:s');
        $dt =
            $_dt->format('d') . ' ' .
            $_monthsList[$_dt->format('n')] .
            ($_dt->format('Y') !== date('Y') ? (' ' . $_dt->format('Y')) : '');

        if (!empty($showDate) && (empty($date) || $date !== $dt)): ?>
            <div class="message-date"><?= $dt; ?></div>
        <?php endif;
        $date = $dt; ?>

        <div class="message-item <?= ($user->id === $message->message_from) ? 'my' : '' ?>" data-id="<?= $message->id ?>">
            <div class="message-photo">
                <img src="/images/user.jpg" alt="">
            </div>

            <div class="message-name">
                <?= $user->id === $message->message_from ? $rsa_my->decrypt($user->name) : $rsa_friend->decrypt($friend->name) ?>
                <span class="message-time"><?= $time ?></span>
            </div>

            <div class="message-body">
                <?= $user->id === $message->message_from ?
                    str_replace("\r\n", '<br>', $rsa_my->decrypt($message->message)) :
                    str_replace("\r\n", '<br>', $rsa_friend->decrypt($message->message)) ?>
            </div>
        </div>
    <?php endforeach;
endif;
