<div class="position-relative">
    <div class="chat-input-section p-3">
        <form id="chatinput-form" enctype="multipart/form-data">
            <div class="row g-0 align-items-center">
                <div class="file_Upload"></div>

                <div class="col-auto">
                    <div class="chat-input-links me-md-2">
                        <div class="links-list-item" title="More" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top">
                            <button type="button" class="btn btn-link text-decoration-none btn-lg waves-effect" data-bs-toggle="collapse" data-bs-target="#chatinputmorecollapse" aria-expanded="false" aria-controls="chatinputmorecollapse">
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

                <div class="col">
                    <div class="position-relative">
                        <div class="chat-input-feedback">
                            Please Enter a Message
                        </div>
                        <input type="text" id="chat-input" class="form-control form-control-lg chat-input" autocomplete="off" placeholder="Type your message..." autofocus>
                    </div>
                </div>

                <div class="col-auto">
                    <div class="chat-input-links ms-2 gap-md-1">
                        <div class="links-list-item d-none d-sm-block" data-bs-container=".chat-input-links" data-bs-toggle="popover" data-bs-trigger="focus" data-bs-html="true" data-bs-placement="top" data-bs-content="">
                            <button type="button" class="btn btn-link text-decoration-none btn-lg waves-effect" onclick="audioPermission()">
                                <i class="bx bx-microphone align-middle"></i>
                            </button>
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

        <div class="chat-input-collapse chat-input-collapse1 collapse" id="chatinputmorecollapse">
            <div class="card mb-0">
                <div class="card-body py-2">
                    <div class="swiper chatinput-links">
                        <div class="swiper-wrapper">
                            <div class="swiper-slide">
                                <div class="text-center px-2 position-relative">
                                    <div>
                                        <input type="file" id="attachedfile-input" class="d-none" accept=".zip,.rar,.7zip,.pdf" multiple>
                                        <label for="attachedfile-input" class="avatar-sm mx-auto stretched-link">
                                            <span class="avatar-title font-size-18 bg-primary-subtle text-primary rounded-circle">
                                                <i class="bx bx-paperclip"></i>
                                            </span>
                                        </label>
                                    </div>

                                    <h5 class="font-size-11 text-uppercase mt-3 mb-0 text-body text-truncate">
                                        File
                                    </h5>
                                </div>
                            </div>

                            <div class="swiper-slide">
                                <div class="text-center px-2 position-relative">
                                    <div>
                                        <input type="file" id="galleryfile-input" class="d-none" accept="image/png, image/gif, image/jpeg" multiple>
                                        <label for="galleryfile-input" class="avatar-sm mx-auto stretched-link">
                                            <span class="avatar-title font-size-18 bg-primary-subtle text-primary rounded-circle">
                                                <i class="bx bx-images"></i>
                                            </span>
                                        </label>
                                    </div>

                                    <h5 class="font-size-11 text-uppercase text-truncate mt-3 mb-0">
                                        Photo
                                    </h5>
                                </div>
                            </div>

                            <div class="swiper-slide">
                                <div class="text-center px-2">
                                    <div>
                                        <input type="file" id="audiofile-input" class="d-none" accept="audio/*" multiple>
                                        <label for="audiofile-input" class="avatar-sm mx-auto stretched-link">
                                            <span class="avatar-title font-size-18 bg-primary-subtle text-primary rounded-circle">
                                                <i class="bx bx-headphone"></i>
                                            </span>
                                        </label>
                                    </div>

                                    <h5 class="font-size-11 text-uppercase text-truncate mt-3 mb-0">
                                        Audio
                                    </h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
