<?php
/**
 * @var string $friendName
 * @var string $friendSecondName
 * @var string $friendLastName
 */
?>

<div class="modal fade audiocallModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg border-0">
            <div class="modal-body p-0">
                <div class="text-center p-4 pb-0">
                    <div class="avatar-xl mx-auto mb-4">
                        <img src="/images/avatar-2.jpg" class="img-thumbnail rounded-circle" alt="">
                    </div>

                    <div class="d-flex justify-content-center align-items-center mt-4">
                        <div class="avatar-md h-auto">
                            <button type="button" class="btn btn-light avatar-sm rounded-circle">
                                <span class="avatar-title bg-transparent text-muted font-size-20">
                                    <i class="bx bx-microphone-off"></i>
                                </span>
                            </button>
                            <h5 class="font-size-11 text-uppercase text-muted mt-2">Mute</h5>
                        </div>
                        <div class="avatar-md h-auto">
                            <button type="button" class="btn btn-light avatar-sm rounded-circle">
                                <span class="avatar-title bg-transparent text-muted font-size-20">
                                    <i class="bx bx-volume-full"></i>
                                </span>
                            </button>
                            <h5 class="font-size-11 text-uppercase text-muted mt-2">Speaker</h5>
                        </div>
                        <div class="avatar-md h-auto">
                            <button type="button" class="btn btn-light avatar-sm rounded-circle">
                                <span class="avatar-title bg-transparent text-muted font-size-20">
                                    <i class="bx bx-user-plus"></i>
                                </span>
                            </button>
                            <h5 class="font-size-11 text-uppercase text-muted mt-2">Add New</h5>
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="button" class="btn btn-danger avatar-md call-close-btn rounded-circle" data-bs-dismiss="modal">
                            <span class="avatar-title bg-transparent font-size-24">
                                <i class="mdi mdi-phone-hangup"></i>
                            </span>
                        </button>
                    </div>
                </div>

                <div class="p-4 bg-primary-subtle mt-n4">
                    <div class="mt-4 text-center">
                        <h5 class="font-size-18 mb-0 text-truncate">
                            <?= "{$friendName} {$friendSecondName} {$friendLastName}" ?>
                        </h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
