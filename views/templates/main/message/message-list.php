<?php
use Entity\User;
use System\Crypt;
use Entity\Friend;
use Entity\Message;
use Models\File as ModelFile;

/**
 * @var User $user
 * @var Crypt $crypt
 * @var Friend $friend
 * @var Crypt $cryptFriend
 * @var bool $showdate
 */

//$name = !empty($user->name) ? $crypt->decryptByPublicKey($user->name) : '';
//$lastName = !empty($user->lastName) ? $crypt->decryptByPublicKey($user->lastName) : '';
//$friendName = !empty($friend->getName()) ? $cryptFriend->decryptByPublicKey($friend->getName()) : '';
//$friendLastName = !empty($friend->getLastName()) ? $cryptFriend->decryptByPublicKey($friend->getLastName()) : '';

$date = '';
$_monthsList = [
    1 => 'января', 2 => 'февраля', 3 => 'марта',     4 => 'апреля',   5 => 'мая',     6 => 'июня',
    7 => 'июля',   8 => 'августа', 9 => 'сентября', 10 => 'октября', 11 => 'ноября', 12 => 'декабря'
];

if (!empty($messages) && is_array($this->messages)):
    foreach ($this->messages as $this->message):
        $time = $this->message->getCreated()->format('H:s');
        $dt = $this->message->getCreated()->format('d') . ' ' .
              $_monthsList[$this->message->getCreated()->format('n')] .
              ($this->message->getCreated()->format('Y') !== date('Y') ? (' ' . $this->message->getCreated()->format('Y')) : ''); ?>

        <?php if (!empty($showDate) && (empty($date) || $date !== $dt)): ?>
            <div class="chat-date"><?= $dt; ?></div>
        <?php endif; ?>
        <?php $date = $dt; ?>

        <li class="chat-list <?= ($user->getId() === $this->message->getMessageFromUserId()) ? 'right' : 'left' ?>" data-id="<?= $this->message->getId() ?>">
            <div class="conversation-list">
                <?php if($user->getId() !== $this->message->getMessageFromUserId()): ?>
                    <div class="chat-avatar">
                        <img src="/images/avatar-2.jpg" alt="">
                    </div>
                <?php endif; ?>

                <div class="user-chat-content">
                    <div class="ctext-wrap">
                        <?php if (!empty($this->message->getFileId())): ?>
                            <?php
                            $this->link = $user->getId() === $this->message->getMessageFromUserId() ?
                                str_replace("\r\n", '<br>', $crypt->decryptByPublicKey($this->message->getFileLink())) :
                                str_replace("\r\n", '<br>', $cryptFriend->decryptByPublicKey($this->message->getFileLink()));

                            $mimeType = mime_content_type(DIR_PUBLIC . DIRECTORY_SEPARATOR . $this->link);
                            ?>

                            <?php if (ModelFile::isImageFile($mimeType)): ?>
                                <?= $this->render('message/message-image') ?>
                            <?php elseif (ModelFile::isAudioFile($mimeType)): ?>
                                <?= $this->render('message/message-audio') ?>
                            <?php elseif (ModelFile::isUserFile($mimeType)): ?>
                                <?= $this->render('message/message-file') ?>
                            <?php endif; ?>
                        <?php else: ?>
                            <?= $this->render('message/message-text') ?>
                        <?php endif; ?>
                    </div>

                    <div class="conversation-name">
                        <small class="text-muted time"><?= $time ?></small>

                        <span class="<?php if ($this->message->isRead()): ?>text-success<?php endif; ?> check-message-icon">
                            <i class="bx bx-check-double"></i>
                        </span>
                    </div>
                </div>
            </div>
        </li>
    <?php endforeach;
endif;
