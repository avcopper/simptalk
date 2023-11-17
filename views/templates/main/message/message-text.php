<?php
use Entity\Message;

/**
 * @var Message $message
 */
?>

<div class="ctext-wrap-content">
    <?= $this->render('message/message-message') ?>
</div>

<div class="dropdown align-self-start message-box-drop">
    <a class="dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="ri-more-2-fill"></i>
    </a>

    <?= $this->render('menu/message-context') ?>
</div>
