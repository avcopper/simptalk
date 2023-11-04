function getMessages(user, container) {
    return setInterval(function () {
        let messageList = container.find('#users-conversation'),
            last = container.find('#users-conversation .chat-list').last().data('id');

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

function sendMessage(user, container, timer) {
    let messageList = container.find('#users-conversation'),
        last = container.find('#users-conversation .chat-list').last().data('id'),
        message = $('#chat-input').val();

    if(message.length > 0) {
        $.ajax({
            method: "POST",
            dataType: 'json',
            url: "/messages/send/" + user + "/" + last + "/",
            data: {'message': message},
            beforeSend: function() {
                clearInterval(timer);
                $('#chat-input').val('');
            },
            success: function(data, textStatus, jqXHR) {//console.log(data);
                if (textStatus === 'success' && jqXHR.status === 200) {
                    if (data.result && data.message.length > 0) {
                        messageList.append(data.message);
                        container.scrollTop(messageList.height());
                    }
                }
                timer = getMessages(user, container);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                if (textStatus === 'error' && jqXHR.status === 403 && errorThrown === 'Forbidden')
                    window.location.href = '/';
            }
        });
    }
}






function cameraPermission() {
    navigator.mediaDevices.getUserMedia ? navigator.mediaDevices.getUserMedia({video: !0}).then(function (e) {
        video.srcObject = e
    }).catch(function (e) {
        console.log(e)
    }) : console.log("No")
}

function audioPermission() {
    navigator.mediaDevices.getUserMedia({audio: !0}).then(function (e) {
        window.localStream = e, window.localAudio.srcObject = e, window.localAudio.autoplay = !0
    })
}

