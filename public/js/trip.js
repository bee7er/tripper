/**
 * Post request to api
 *
 * @param url
 * @param data
 */
function ajaxCall(url, data, callback) {

    let instanceId = target.closest('div').attr('id');

    $.ajax({
        type: 'post',
        url: url,
        data: data,
        contentType: 'application/json',
        cache: false,
        processData: false
    }).success(function (response) {
        callback(response);
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