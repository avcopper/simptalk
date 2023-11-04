<?php
/**
 * @var string $friendName
 * @var string $friendSecondName
 * @var string $friendLastName
 */
?>

<div class="p-2 user-chat-topbar">
    <div class="row align-items-center">
        <div class="col-sm-4 col-8">
            <div class="d-flex align-items-center">
                <div class="flex-grow-1 overflow-hidden">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 chat-user-img online user-own-img align-self-center me-3 ms-0">
                            <img src="/images/avatar-2.jpg" class="rounded-circle avatar-sm" alt="">
                            <span class="user-status"></span>
                        </div>

                        <div class="flex-grow-1 overflow-hidden">
                            <h6 class="text-truncate mb-0 font-size-18">
                                <a href="#" class="user-profile-show text-reset">
                                    <?= "{$friendName} {$friendSecondName} {$friendLastName}" ?>
                                </a>
                            </h6>

                            <p class="text-truncate text-muted mb-0"><small>Online</small></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-8 col-4">
            <ul class="list-inline user-chat-nav text-end mb-0">
                <li class="list-inline-item">
                    <div class="dropdown">
                        <button class="btn nav-btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class='bx bx-search'></i>
                        </button>
                        <div class="dropdown-menu p-0 dropdown-menu-end dropdown-menu-lg">
                            <div class="search-box p-2">
                                <input type="text" id="searchChatMessage" class="form-control" placeholder="Search...">
                            </div>
                        </div>
                    </div>
                </li>

                <li class="list-inline-item d-none d-lg-inline-block me-2 ms-0">
                    <button type="button" class="btn nav-btn" data-bs-toggle="modal" data-bs-target=".audiocallModal">
                        <i class='bx bxs-phone-call'></i>
                    </button>
                </li>

                <li class="list-inline-item d-none d-lg-inline-block me-2 ms-0">
                    <button type="button" class="btn nav-btn" data-bs-toggle="modal" data-bs-target=".videocallModal">
                        <i class='bx bx-video'></i>
                    </button>
                </li>

                <li class="list-inline-item d-none d-lg-inline-block me-2 ms-0">
                    <button type="button" class="btn nav-btn user-profile-show">
                        <i class='bx bxs-user'></i>
                    </button>
                </li>

                <li class="list-inline-item">
                    <div class="dropdown">
                        <button class="btn nav-btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class='bx bx-dots-vertical-rounded'></i>
                        </button>

                        <div class="dropdown-menu dropdown-menu-end">
                            <a href="#" class="dropdown-item d-flex justify-content-between align-items-center d-lg-none user-profile-show">
                                View Profile <i class="bx bx-user text-muted"></i>
                            </a>

                            <a href="#" class="dropdown-item d-flex justify-content-between align-items-center d-lg-none" data-bs-toggle="modal" data-bs-target=".audiocallModal">
                                Audio <i class="bx bxs-phone-call text-muted"></i>
                            </a>

                            <a href="#" class="dropdown-item d-flex justify-content-between align-items-center d-lg-none" data-bs-toggle="modal" data-bs-target=".videocallModal">
                                Video <i class="bx bx-video text-muted"></i>
                            </a>

                            <a href="#" class="dropdown-item d-flex justify-content-between align-items-center">
                                Archive <i class="bx bx-archive text-muted"></i>
                            </a>

                            <a href="#" class="dropdown-item d-flex justify-content-between align-items-center">
                                Muted <i class="bx bx-microphone-off text-muted"></i>
                            </a>

                            <a href="#" class="dropdown-item d-flex justify-content-between align-items-center">
                                Delete <i class="bx bx-trash text-muted"></i>
                            </a>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</div>
