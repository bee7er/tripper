
    /**
     * Post request to api
     *
     * @param url
     * @param data
     * @param callback
     * @param params - any additional parameters to pass on to the callback
     */
    function ajaxCall(url, data, callback, params) {                     alert('xxxx');

        $.ajax({
            type: 'post',
            url: url,
            data: data,
            contentType: 'application/json',
            cache: false,
            processData: false
        }).success(function (response) {
            callback(response, params);
        }).fail(function (jqXHR, textStatus, errorThrown) {
            // Optionally alert the user of an error here...
            var textResponse = jqXHR.responseText;
            var alertText = "One of the following conditions is not met:\n\n";
            var jsonResponse = jQuery.parseJSON(textResponse);

            $.each(jsonResponse, function (n, elem) {
                alertText = alertText + elem + "\n";
            });

            alert(alertText);

            return false;
        });
    }

    /**
     * Display system messages
     *
     * @param success
     * @param messages
     */
    function displayMessages(success, messages)
    {
        if (messages) {
            let messageStatus = success ? "#info-messages" : "#error-messages";
            if (Array.isArray(messages)) {
                for (let i=0; i<messages.length; i++) {
                    if (messages[i]) {
                        $(messageStatus).text(messages[i]).fadeIn(800).delay(3000).fadeOut(800);
                    }
                }
            } else {
                $(messageStatus).text(messages).fadeIn(800).delay(3000).fadeOut(800);
            }
        }
    }
