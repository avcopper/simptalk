<?php

use Entity\User;
use System\Crypt;
use Entity\Message;

/**
 * @var User $user
 * @var Crypt $crypt
 * @var Crypt $cryptFriend
 * @var string $link
 * @var Message $message
 */
?>

<div class="ctext-wrap-content">
    <div class="p-2 border-primary border rounded-3 attached-container">
        <div class="d-flex flex-column align-items-center attached-file">

            <div class="audio-player audio-player-<?= $this->message->getFileId() ?>">
                <div class="holder">
                    <div class="loading" style="visibility: hidden;">
                        <div class="loading__spinner"></div>
                    </div>

                    <div class="play-pause-btn" aria-label="Play" role="button" title="Play"
                         style="visibility: visible;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="24" viewBox="0 0 18 24" tabindex="0"
                             focusable="true">
                            <path fill="#566574" fill-rule="evenodd" d="M18 12L0 24V0"
                                  class="play-pause-btn__icon"></path>
                        </svg>
                    </div>
                </div>

                <div class="controls">
                    <span class="controls__current-time" aria-live="off" role="timer">00:00</span>
                    <div class="controls__slider slider" data-direction="horizontal" tabindex="0">
                        <div class="controls__progress gap-progress" aria-label="Time Slider" aria-valuemin="0"
                             aria-valuemax="100" aria-valuenow="0" role="slider">
                            <div class="pin progress__pin" data-method="rewind"></div>
                        </div>
                    </div>
                    <span class="controls__total-time">00:00</span>
                </div>

                <div class="volume">
                    <div class="volume__button" aria-label="Open Volume Controls" role="button"
                         title="Open Volume Controls">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" tabindex="0"
                             focusable="true">
                            <path class="volume__speaker" fill-rule="evenodd"
                                  d="M14.667 0v2.747c3.853 1.146 6.666 4.72 6.666 8.946 0 4.227-2.813 7.787-6.666 8.934v2.76C20 22.173 24 17.4 24 11.693 24 5.987 20 1.213 14.667 0zM18 11.693c0-2.36-1.333-4.386-3.333-5.373v10.707c2-.947 3.333-2.987 3.333-5.334zm-18-4v8h5.333L12 22.36V1.027L5.333 7.693H0z"></path>
                        </svg>
                        <span class="message__offscreen">Press Enter or Space to show volume slider.</span>
                    </div>
                    <div class="volume__controls hidden top">
                        <div class="volume__slider slider" data-direction="vertical" tabindex="0">
                            <div class="volume__progress gap-progress" aria-label="Volume Slider" aria-valuemin="0"
                                 aria-valuemax="100" aria-valuenow="81" role="slider" style="height: 81%;">
                                <div class="pin volume__pin" data-method="changeVolume"></div>
                            </div>
                            <span class="message__offscreen">Use Up/Down Arrow keys to increase or decrease volume.</span>
                        </div>
                    </div>
                </div>

                <div class="download" tabindex="-1">
                    <a class="download__link" href="" download="" aria-label="Download" role="button" tabindex="-1"
                       title="Download">
                        <svg width="24" height="24" enable-background="new 0 0 29.978 29.978" version="1.1"
                             viewBox="0 0 29.978 29.978" xml:space="preserve" xmlns="http://www.w3.org/2000/svg"
                             tabindex="0" focusable="true">
            <path d="m25.462 19.105v6.848h-20.947v-6.848h-4.026v8.861c0 1.111 0.9 2.012 2.016 2.012h24.967c1.115 0 2.016-0.9 2.016-2.012v-8.861h-4.026z"></path>
                            <path d="m14.62 18.426l-5.764-6.965s-0.877-0.828 0.074-0.828 3.248 0 3.248 0 0-0.557 0-1.416v-8.723s-0.129-0.494 0.615-0.494h4.572c0.536 0 0.524 0.416 0.524 0.416v8.742 1.266s1.842 0 2.998 0c1.154 0 0.285 0.867 0.285 0.867s-4.904 6.51-5.588 7.193c-0.492 0.495-0.964-0.058-0.964-0.058z"></path>
        </svg>
                    </a>
                </div>

                <audio crossorigin="" preload="none">
                    <source src="<?= $link ?>" type="audio/mpeg">
                </audio>
            </div>

            <p class="audio-label text-muted text-truncate font-size-11 mb-0">
                <?= $user->getId() === $message->getMessageFromUserId() ?
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
        new GreenAudioPlayer('.audio-player-<?= $this->message->getFileId() ?>', {
            stopOthersOnPlay: true,
            showTooltips: true,
            showDownloadButton: false,
            enableKeystrokes: true
        });
    });
</script>