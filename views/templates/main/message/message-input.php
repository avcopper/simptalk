

<div class="position-relative">
    <div class="chat-input-section p-3">
        <form id="chat-form" enctype="multipart/form-data">
            <div class="row g-0 align-items-center">
                <div class="col-auto">
                    <div class="chat-input-links me-md-2">
                        <div class="links-list-item" title="More" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top">
                            <button type="button" class="btn btn-link text-decoration-none btn-lg waves-effect" data-bs-toggle="collapse" data-bs-target="#chatinputmore" aria-expanded="false" aria-controls="chatinputmore">
                                <i class="bx bx-dots-horizontal-rounded align-middle"></i>
                            </button>
                        </div>

                        <div class="links-list-item" title="Emoji" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top">
                            <button type="button" id="emoji-btn" class="btn btn-link text-decoration-none btn-lg waves-effect emoji-btn">
                                <i class="bx bx-smile align-middle"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div id="input-message-block" class="col">
                    <div class="position-relative">
                        <div id="input-file-block" class="chat-input-feedback w-100">
                                <div class="d-flex">
                                    <div class="flex-grow-1">
                                        <label for="chat-file"></label>

                                        <input type="file" name="chat-file" id="chat-file" class="d-none form-control form-control-lg chat-input">
                                    </div>

                                    <div class="flex-shrink-0 file-block-close" title="Close">
                                        <button type="button" class="btn nav-btn text-white d-none d-lg-block">
                                            <i class="bx bx-x"></i>
                                        </button>
                                    </div>
                                </div>
                        </div>

                        <input type="text" name="chat-input" id="chat-input" class="form-control form-control-lg chat-input" autocomplete="off" placeholder="Type your message..." autofocus>
                    </div>
                </div>

                <div class="col-auto">
                    <div class="chat-input-links ms-2 gap-md-1">
                        <div id="audio-input" class="links-list-item" data-bs-container=".chat-input-links" data-bs-toggle="popover" data-bs-trigger="focus" data-bs-html="true" data-bs-placement="top" data-bs-content="<div class='loader-line'><div class='line'></div><div class='line'></div><div class='line'></div><div class='line'></div><div class='line'></div></div>">
                            <button type="button" class="btn btn-link text-decoration-none btn-lg waves-effect"">
                                <i class="bx bx-microphone align-middle"></i>
                            </button>
                        </div>

                        <div id="chat-record" class="popover bs-popover-auto" role="tooltip">
                            <div class="popover-arrow" style=""></div>
                            <div class="popover-body">
                                <div class="loader-line">
                                    <div class="line"></div>
                                    <div class="line"></div>
                                    <div class="line"></div>
                                    <div class="line"></div>
                                    <div class="line"></div>
                                </div>
                            </div>
                        </div>

                        <div class="links-list-item">
                            <button type="submit" id="chat-send" class="btn btn-primary btn-lg chat-send waves-effect waves-light" data-bs-toggle="collapse" data-bs-target=".chat-input-collapse1.show">
                                <i class="bx bxs-send align-middle" id="submit-btn"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <div class="chat-input-collapse chat-input-collapse1 collapse" id="chatinputmore">
            <div class="card mb-0">
                <div class="card-body py-2">
                    <div class="swiper chatinput-links">
                        <div class="swiper-wrapper">
                            <div class="swiper-slide">
                                <div class="text-center px-2 position-relative">
                                    <div>
                                        <input type="file" id="user-file" class="d-none" accept=".zip,.rar,.7z,.pdf">

                                        <label for="user-file" class="avatar-sm mx-auto stretched-link">
                                            <span class="avatar-title font-size-18 bg-primary-subtle text-primary rounded-circle">
                                                <i class="bx bx-paperclip"></i>
                                            </span>
                                        </label>
                                    </div>

                                    <h5 class="font-size-11 text-uppercase mt-3 mb-0 text-body text-truncate">File</h5>
                                </div>
                            </div>

                            <div class="swiper-slide">
                                <div class="text-center px-2 position-relative">
                                    <div>
                                        <input type="file" id="image-file" class="d-none" accept="image/png, image/gif, image/jpeg">

                                        <label for="image-file" class="avatar-sm mx-auto stretched-link">
                                            <span class="avatar-title font-size-18 bg-primary-subtle text-primary rounded-circle">
                                                <i class="bx bx-images"></i>
                                            </span>
                                        </label>
                                    </div>

                                    <h5 class="font-size-11 text-uppercase text-truncate mt-3 mb-0">Photo</h5>
                                </div>
                            </div>

                            <div class="swiper-slide">
                                <div class="text-center px-2">
                                    <div>
                                        <input type="file" id="audio-file" class="d-none" accept="audio/*">

                                        <label for="audio-file" class="avatar-sm mx-auto stretched-link">
                                            <span class="avatar-title font-size-18 bg-primary-subtle text-primary rounded-circle">
                                                <i class="bx bx-headphone"></i>
                                            </span>
                                        </label>
                                    </div>

                                    <h5 class="font-size-11 text-uppercase text-truncate mt-3 mb-0">Audio</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
