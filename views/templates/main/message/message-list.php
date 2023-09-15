<?php
$name = !empty($this->user->name) ? $this->crypt->decryptByPublicKey($this->user->name) : '';
$lastName = !empty($this->user->lastName) ? $this->crypt->decryptByPublicKey($this->user->lastName) : '';
$friendName = !empty($this->friend->name) ? $this->cryptFriend->decryptByPublicKey($this->friend->name) : '';
$friendLastName = !empty($this->friend->lastName) ? $this->cryptFriend->decryptByPublicKey($this->friend->lastName) : '';

$date = '';
$_monthsList = [
    1 => 'января', 2 => 'февраля', 3 => 'марта',     4 => 'апреля',   5 => 'мая',     6 => 'июня',
    7 => 'июля',   8 => 'августа', 9 => 'сентября', 10 => 'октября', 11 => 'ноября', 12 => 'декабря'
];

if (!empty($this->messages) && is_array($this->messages)):
    foreach ($this->messages as $message):
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
            <?= ($this->user->id === $message->messageFromUserId && !$message->isRead) ? 'unread' : '' ?>
            <?= ($this->user->id === $message->messageFromUserId) ? 'my' : '' ?>" data-id="<?= $message->id ?>">
            <div class="message-photo">
                <img src="/images/user.jpg" alt="">
            </div>

            <div class="message-name">
                <?= $this->user->id === $message->messageFromUserId ? "{$name} {$lastName}" : "{$friendName} {$friendLastName}" ?>
                <span class="message-time"><?= $time ?></span>
            </div>

            <div class="message-body">
                <?= $this->user->id === $message->messageFromUserId ?
                    str_replace("\r\n", '<br>', $this->crypt->decryptByPublicKey($message->message)) :
                    str_replace("\r\n", '<br>', $this->cryptFriend->decryptByPublicKey($message->message)) ?>
            </div>
        </div>
    <?php endforeach;
endif;
