<?php
use Entity\User;
use System\Crypt;
use Entity\Message;

/**
 * @var Message $message
 * @var User $user
 * @var Crypt $crypt
 * @var Crypt $cryptFriend
 */
?>

<?php if (!empty($message->getMessage())): ?>
<p class="mb-0 ctext-content">
    <?= $user->id === $message->messageFromUserId ?
        str_replace("\r\n", '<br>', $crypt->decryptByPublicKey($message->getMessage())) :
        str_replace("\r\n", '<br>', $cryptFriend->decryptByPublicKey($message->getMessage())) ?>
</p>
<?php endif;
