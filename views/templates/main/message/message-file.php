<?php
use Entity\User;
use System\Crypt;
use Entity\Message;

/**
 * @var Message $message
 * @var User $user
 * @var Crypt $crypt
 * @var Crypt $cryptFriend
 * @var string $link
 */

$fileSize = filesize(DIR_PUBLIC . DIRECTORY_SEPARATOR . $this->link);
$size = $fileSize > 1000000 ?
    (round($fileSize / 1000000) . ' MB') :
    ($fileSize > 1000 ?
        (round($fileSize / 1000) . ' KB') :
        round($fileSize) . 'B');
?>

<div class="ctext-wrap-content">
    <div class="p-2 border-primary border rounded-3 attached-container">
        <div class="d-flex align-items-center attached-file">
            <div class="flex-shrink-0 avatar-sm me-3 ms-0 attached-file-avatar">
                <div class="avatar-title bg-primary-subtle text-primary rounded-circle font-size-20">
                    <i class="ri-attachment-2"></i>
                </div>
            </div>

            <div class="flex-grow-1 overflow-hidden">
                <div class="text-start">
                    <h5 class="font-size-14 mb-1">
                        <?= $user->getId() === $message->getMessageFromUserId() ?
                            str_replace("\r\n", '<br>', $crypt->decryptByPublicKey($message->getFileName())) :
                            str_replace("\r\n", '<br>', $cryptFriend->decryptByPublicKey($message->getFileName())) ?>
                    </h5>

                    <p class="text-muted text-truncate font-size-13 mb-0"><?= $size ?></p>
                </div>
            </div>

            <div class="flex-shrink-0 ms-4">
                <div class="d-flex gap-2 font-size-20 d-flex align-items-start">
                    <div>
                        <a href="<?= $link ?>" class="text-muted" target="_blank">
                            <i class="bx bxs-download"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?= $this->render('message/message-message') ?>
</div>

<div class="dropdown align-self-start message-box-drop">
    <a class="dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="ri-more-2-fill"></i>
    </a>

    <?= $this->render('menu/message-context') ?>
</div>
