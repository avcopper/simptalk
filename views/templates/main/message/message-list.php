<?php
/**
 * @var \Entity\User $user
 * @var \System\Crypt $crypt
 * @var \Entity\Friend $friend
 * @var \System\Crypt $cryptFriend
 * @var bool $showdate
 */

$name = !empty($user->name) ? $crypt->decryptByPublicKey($user->name) : '';
$lastName = !empty($user->lastName) ? $crypt->decryptByPublicKey($user->lastName) : '';
$friendName = !empty($friend->name) ? $cryptFriend->decryptByPublicKey($friend->name) : '';
$friendLastName = !empty($friend->lastName) ? $cryptFriend->decryptByPublicKey($friend->lastName) : '';

$date = '';
$_monthsList = [
    1 => 'января', 2 => 'февраля', 3 => 'марта',     4 => 'апреля',   5 => 'мая',     6 => 'июня',
    7 => 'июля',   8 => 'августа', 9 => 'сентября', 10 => 'октября', 11 => 'ноября', 12 => 'декабря'
];

if (!empty($messages) && is_array($this->messages)):
    foreach ($this->messages as $message):
        $time = $message->created->format('H:s');
        $dt =
            $message->created->format('d') . ' ' .
            $_monthsList[$message->created->format('n')] .
            ($message->created->format('Y') !== date('Y') ? (' ' . $message->created->format('Y')) : ''); ?>

        <?php if (!empty($showDate) && (empty($date) || $date !== $dt)): ?>
            <div class="chat-date"><?= $dt; ?></div>
        <?php endif; ?>
        <?php $date = $dt; ?>

        <li class="chat-list <?= ($user->id === $message->messageFromUserId) ? 'right' : 'left' ?>" data-id="<?= $message->id ?>">
            <div class="conversation-list">
                <?php if($user->id !== $message->messageFromUserId): ?>
                    <div class="chat-avatar">
                        <img src="/images/avatar-2.jpg" alt="">
                    </div>
                <?php endif; ?>

                <div class="user-chat-content">
                    <div class="ctext-wrap">
                        <div class="ctext-wrap-content">
                            <p class="mb-0 ctext-content">
                                <?= $user->id === $message->messageFromUserId ?
                                    str_replace("\r\n", '<br>', $crypt->decryptByPublicKey($message->message)) :
                                    str_replace("\r\n", '<br>', $cryptFriend->decryptByPublicKey($message->message)) ?>
                            </p>
                        </div>

                        <div class="dropdown align-self-start message-box-drop">
                            <a class="dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="ri-more-2-fill"></i>
                            </a>

                            <?= $this->render('message/message-context') ?>
                        </div>
                    </div>

                    <div class="conversation-name">
                        <small class="text-muted time"><?= $time ?></small>

                        <span class="<?php if ($message->isRead): ?>text-success<?php endif; ?> check-message-icon">
                            <i class="bx bx-check-double"></i>
                        </span>
                    </div>
                </div>
            </div>
        </li>
    <?php endforeach;
endif;
