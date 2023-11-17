<?php
use Entity\Message;

/**
 * @var Message $message
 */
?>

<div class="chat-message-list">
    <ul id="favourite-users" class="list-unstyled chat-list chat-user-list">
    <?php if (!empty($messages) && is_array($messages)): ?>
        <?php foreach ($messages as $message): ?>
            <li>
                <a href="<?= $message->getFriendId() ?>/" class="unread-msg-user">
                    <div class="d-flex align-items-center">
                        <div class="chat-user-img online align-self-center me-2 ms-0">
                            <img src="/images/avatar-2.jpg" class="rounded-circle avatar-xs" alt="">
                            <span class="user-status"></span>
                        </div>

                        <div class="overflow-hidden">
                            <p class="text-truncate mb-0"><?= $message->getFriendLogin() ?></p>
                        </div>

                        <div class="ms-auto">
                            <span class="badge bg-dark-subtle text-reset rounded p-1">18</span>
                        </div>
                    </div>
                </a>
            </li>
        <?php endforeach; ?>
    <?php else: ?>
        <li>
            <div class="no-msg-user">
                <div class="d-flex align-items-center">
                    <div class="overflow-hidden">
                        <p class="text-truncate mb-0">Диалоги не обнаружены</p>
                    </div>
                </div>
            </div>
        </li>
    <?php endif; ?>
    </ul>
</div>
