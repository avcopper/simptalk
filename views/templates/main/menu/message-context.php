<?php
use Entity\Message;

/**
 * @var Message $message
 * @var string $link
 */
?>

<div class="dropdown-menu">
    <?php if (!empty($message->getFileId())): ?>
    <a href="/files/download/<?= $message->getFileId() ?>/" class="dropdown-item d-flex align-items-center justify-content-between" target="_blank">
        Download <i class="bx bx-download ms-2 text-muted"></i>
    </a>
    <?php endif; ?>

    <a href="#" class="dropdown-item d-flex align-items-center justify-content-between copy-message">
        Copy <i class="bx bx-copy text-muted ms-2"></i>
    </a>

    <a href="#" class="dropdown-item d-flex align-items-center justify-content-between delete-item">
        Delete <i class="bx bx-trash text-muted ms-2"></i>
    </a>

<!--    <a href="#" class="dropdown-item d-flex align-items-center justify-content-between reply-message" data-bs-toggle="collapse" data-bs-target=".replyCollapse">-->
<!--        Reply <i class="bx bx-share ms-2 text-muted"></i>-->
<!--    </a>-->
<!--    <a href="#" class="dropdown-item d-flex align-items-center justify-content-between" data-bs-toggle="modal" data-bs-target=".forwardModal">-->
<!--        Forward <i class="bx bx-share-alt ms-2 text-muted"></i>-->
<!--    </a>-->
<!--    <a href="#" class="dropdown-item d-flex align-items-center justify-content-between">-->
<!--        Bookmark <i class="bx bx-bookmarks text-muted ms-2"></i>-->
<!--    </a>-->
<!--    <a href="#" class="dropdown-item d-flex align-items-center justify-content-between">-->
<!--        Mark as Unread <i class="bx bx-message-error text-muted ms-2"></i>-->
<!--    </a>-->
</div>
