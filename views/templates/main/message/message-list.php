<?php
/**
 * @var \System\Crypt $crypt
 * @var \System\Crypt $cryptFriend
 * @var \Entity\User $user
 * @var \Entity\Friend $friend
 * @var string $name
 * @var string $lastName
 * @var string $friendName
 * @var string $friendLastName
 */

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
        $time = $message->created->format('H:s');
        $dt =
            $message->created->format('d') . ' ' .
            $_monthsList[$message->created->format('n')] .
            ($message->created->format('Y') !== date('Y') ? (' ' . $message->created->format('Y')) : '');

        if (!empty($showDate) && (empty($date) || $date !== $dt)): ?>
            <div class="message-date"><?= $dt; ?></div>
        <?php endif;
        $date = $dt; ?>

        <div class="message-item
            <?= ($user->id === $message->messageFromUserId && !$message->isRead) ? 'unread' : '' ?>
            <?= ($user->id === $message->messageFromUserId) ? 'my' : '' ?>" data-id="<?= $message->id ?>">
            <div class="message-photo">
                <img src="/images/user.jpg" alt="">
            </div>

            <div class="message-name">
                <?= $user->id === $message->messageFromUserId ? "{$name} {$lastName}" : "{$friendName} {$friendLastName}" ?>
                <span class="message-time"><?= $time ?></span>
            </div>

            <div class="message-body">
                <?= $user->id === $message->messageFromUserId ?
                    str_replace("\r\n", '<br>', $crypt->decryptByPublicKey($message->message)) :
                    str_replace("\r\n", '<br>', $cryptFriend->decryptByPublicKey($message->message)) ?>
            </div>
        </div>
    <?php endforeach;
endif;
