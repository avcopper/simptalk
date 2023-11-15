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
?>

<div class="ctext-wrap-content">
    <div class="p-2 border-primary border rounded-3 attached-container">
        <div class="d-flex flex-column align-items-center attached-file">

            <div class="audioplayer audioplayer-<?= $this->message->getFileId() ?>">
                <audio class="audio" crossorigin="" preload="none" style="width: 0px; height: 0px; visibility: hidden;">
                    <source src="<?= $link ?>" type="audio/mpeg">
                </audio>
                <div class="audioplayer-playpause" title=""><a href="#"></a></div>
                <div class="audioplayer-time audioplayer-time-current">00:00</div>
                <div class="audioplayer-bar">
                    <div class="audioplayer-bar-loaded"></div>
                    <div class="audioplayer-bar-played"></div>
                </div>
                <div class="audioplayer-time audioplayer-time-duration">…</div>
                <div class="audioplayer-volume">
                    <div class="audioplayer-volume-button" title=""><a href="#"></a></div>
                    <div class="audioplayer-volume-adjust">
                        <div>
                            <div style="width: 100%;"></div>
                        </div>
                    </div>
                </div>
            </div>

<!--            <audio class="audio---><?//= $this->message->getFileId() ?><!--" crossorigin="" preload="none"-->
<!--                   style="width: 0px; height: 0px; visibility: hidden;">-->
<!--                <source src="--><?//= $link ?><!--" type="audio/mpeg">-->
<!--            </audio>-->

            <p class="audio-label text-muted text-truncate font-size-11 mb-0">
                <?= $user->id === $message->messageFromUserId ?
                    str_replace("\r\n", '<br>', $crypt->decryptByPublicKey($message->getFileName())) :
                    str_replace("\r\n", '<br>', $cryptFriend->decryptByPublicKey($message->getFileName())) ?>
            </p>
        </div>
    </div>

    <?= $this->render('message/message-message') ?>
</div>

<div class="dropdown align-self-start message-box-drop">
    <a class="dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true"
       aria-expanded="false">
        <i class="ri-more-2-fill"></i>
    </a>

    <?= $this->render('menu/message-context') ?>
</div>


<script>
    $(function () {
        //$('.audioplayer-<?//= $this->message->getFileId() ?>//').audioPlayer({
        //    classPrefix:'audioplayer-<?//= $this->message->getFileId() ?>//',
        //    strPlay:'',
        //    strPause:'',
        //    strVolume:''
        //});
    });
</script>
