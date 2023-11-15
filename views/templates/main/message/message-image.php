<?php
use Entity\User;
use System\Crypt;
use Entity\Message;

/**
 * @var Message $message
 * @var string $link
 */
?>

<div class="flex-column">
    <div class="message-img mb-0">
        <div class="message-img-list">
            <div>
                <a href="<?= $link ?>" class="popup-img d-inline-block">
                    <img src="<?= $link ?>" class="rounded border" alt="">
                </a>
            </div>

            <div class="message-img-link">
                <ul class="list-inline mb-0">
                    <li class="list-inline-item dropdown">
                        <a href="#" class="dropdown-toggle" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="bx bx-dots-horizontal-rounded"></i>
                        </a>

                        <?= $this->render('menu/message-context') ?>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <?php if (!empty($message->getMessage())): ?>
        <div class="ctext-wrap-content">
            <?= $this->render('message/message-message') ?>
        </div>
    <?php endif; ?>
</div>
