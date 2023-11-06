let stream,
    mediaRecorder,
    audioChunks = [],
    audioRecordStart = function (e) {
        audioChunks.push(e.data);
    },
    audioRecordStop = function () {
        let audioBlob = new Blob(audioChunks, {type: 'audio/wav'});
        //addAudioToForm(audioBlob);
        addAudioToForm(getFileFromBlob(audioBlob));
        //sendAudio(audioBlob);
        clear();
    };

/**
 * Запись сообщения
 */
function recordAudio() {
    let audioButton = $('#audio-input'),
        chatRecord = $('#chat-record');

    audioButton.toggleClass('active');
    chatRecord.toggle();

    if (audioButton.hasClass('active')) startAudioRecord();
    else stopAudioRecord();
}

/**
 * Старт записи аудио
 */
function startAudioRecord() {
    stream = navigator.mediaDevices.getUserMedia({ audio: true});

    stream ?
        stream
            .then(stream => {
                mediaRecorder = new MediaRecorder(stream);
                addAudioListeners();
                mediaRecorder.start();
            }).catch(function (e) {
                console.log(e)
            }) : console.log("No audio device");
}

/**
 * Остановка записи аудио
 */
function stopAudioRecord() {
    if (mediaRecorder) mediaRecorder.stop();
}

/**
 * Добавляем обработчики
 */
function addAudioListeners() {
    mediaRecorder.addEventListener("dataavailable", audioRecordStart);
    mediaRecorder.addEventListener("stop", audioRecordStop);
}

/**
 * Удаляем обработчики
 */
function removeAudioListeners() {
    mediaRecorder.removeEventListener("dataavailable", audioRecordStart);
    mediaRecorder.removeEventListener("stop", audioRecordStop);
}

/**
 * Чистим дымоход
 */
function clear() {
    removeAudioListeners();
    audioChunks = [];
    stream = mediaRecorder = null;
}

/**
 * Добавление файла в форму
 * @param file
 */
function addAudioToForm(file) {
    if (file.length > 0) {
        $('#chat-file').get(0).files = file;
        showFileBlock();
    }
}

/**
 * Конвертация данных в файл
 * @param blob
 * @returns {FileList}
 */
function getFileFromBlob(blob) {
    let dt  = new DataTransfer();
    dt.items.add(new File([blob], 'Message.wav', {type: blob.type}));
    return dt.files;
}

/**
 * Показываем в форме блок отправки файла
 */
function showFileBlock() {
    $('#input-file-block').show();
    $('#input-file-block label').html($('#chat-file').get(0).files[0].name ?? 'No data');
}

/**
 * Прячем в форме блок отправки файла
 */
function hideFileBlock() {
    $('#input-file-block').hide();
    $('#input-file-block label').html('');
    $('#chat-file').val('');
}

/**
 * Получение сообщений
 * @param user
 * @returns {number}
 */
function getMessages(user) {
    return setInterval(function () {
        let container = $("#chat-conversation .simplebar-content-wrapper"),
            messageList = $('#users-conversation'),
            last = $('#users-conversation .chat-list').last().data('id');

        $.ajax({
            method: "POST",
            dataType: 'json',
            url: "/messages/get/" + user + "/" + last + "/",
            beforeSend: function () {
            },
            success: function (data, textStatus, jqXHR) {//console.log(data);
                if (textStatus === 'success' && jqXHR.status === 200) {
                    if (data.result && data.message.length > 0) {
                        let needScroll = messageList.height() - container.scrollTop() - container.height() < 20;
                        messageList.append(data.message);
                        if (needScroll) container.scrollTop(messageList.height());
                    }
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                if (textStatus === 'error' && jqXHR.status === 403 && errorThrown === 'Forbidden')
                    window.location.href = '/';
            }
        });
    }, 15000);
}

/**
 * Отправка сообщений
 * @param user
 * @param timer
 */
function sendMessage(user, timer) {
    let container = $("#chat-conversation .simplebar-content-wrapper"),
        messageList = $('#users-conversation'),
        last = $('#users-conversation .chat-list').last().data('id'),
        formData = new FormData($('#chat-form').get(0));

    if ($('#chat-input').val().length > 0 || $('#chat-file').val().length > 0) {
        $.ajax({
            url: "/messages/send/" + user + "/" + last + "/",
            type: "POST",
            dataType: 'json',
            data: formData,
            contentType: false,
            processData: false,
            beforeSend: function () {
                clearInterval(timer);
                $('#chat-input').val('');
                hideFileBlock();
            },
            success: function(data, textStatus, jqXHR) {console.log(data);
                if (textStatus === 'success' && jqXHR.status === 200) {
                    if (data.result && data.message.length > 0) {
                        messageList.append(data.message);
                        container.scrollTop(messageList.height());
                    }
                }
                timer = getMessages(user);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                if (textStatus === 'error' && jqXHR.status === 403 && errorThrown === 'Forbidden')
                    window.location.href = '/';
            }
        });
    }
}

/**
 * Отправка аудио на сервер
 * @param blob
 */
function sendAudio(blob) {
    let form = new FormData();
    form.append('chat-file', blob);

    $.ajax({
        url: '/messages/send/2/50/',
        type: "POST",
        dataType: 'json',
        data: form,
        contentType: false,
        processData: false,
        beforeSend: function () {
        },
        success:  function(data) {
            console.log(data);
        }
    });
}










function cameraPermission() {
    stream = navigator.mediaDevices.getUserMedia({ video: true});

    stream ?
        stream
            .then(stream => {
                video.srcObject = stream;
                // mediaRecorder = new MediaRecorder(stream);
                // addAudioListeners();
                // mediaRecorder.start();
            }).catch(function (e) {
                console.log(e)
            }) : console.log("No video device");
}

