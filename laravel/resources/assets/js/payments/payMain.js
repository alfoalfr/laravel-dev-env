mainWindow = window;

/**
 * @param {Object} data
 * @param {Object[]} data.items
 * @param {Number} data.items[].id
 * @param {Number} [data.items[].quantity]
 * @param {string} [data.transactionDescription]
 * @param {function} data.callback
 */
function getPaymentPage(data){
    var xhttp = new XMLHttpRequest();
    var popupClosingTrackingInterval;
    var popup;

    xhttp.onreadystatechange = function() {
        if (xhttp.readyState == 4) {
            if (xhttp.status == 200) {
                response = JSON.parse(xhttp.responseText);
                width = 1100;
                height = 720;

                if (popup !== null && popup !== undefined) {
                    popup.close();
                    popup = null;
                }

                popup = window.open(
                    response.url,
                    'Payment',
                    'width=' + width + ',height=' + height + ',top=' + ((screen.height / 2) - (height / 2)) + ',left=' + ((screen.width / 2) - (width / 2))
                );

                if (popup == null || typeof(popup) == 'undefined') {
                    if (data.hasOwnProperty('callback') && data.callback !== '') {
                        window[data.callback]({success: false, message: 'popup-blocked'});
                    }
                } else {
                    popup.focus();
                }

                popupClosingTrackingInterval = window.setInterval(function () {
                    try {
                        if (popup == null || popup.closed) {
                            window.clearInterval(popupClosingTrackingInterval);
                            window[data.callback]({success: null, message: 'window-closed'});
                        }
                    }
                    catch (e) {
                    }
                }, 1000);
            } else {
                window[data.callback]({success: false, message: 'ajax-error'})
            }
        }
    };

    xhttp.open("POST", baseUrl+"/payment/paypal/post", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send(JSON.stringify(data));
}