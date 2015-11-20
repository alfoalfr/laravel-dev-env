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
    xhttp.onreadystatechange = function() {
        if (xhttp.readyState == 4) {
            if (xhttp.status == 200) {
                response = JSON.parse(xhttp.responseText);
                width = 1100;
                height = 720;
                window.open(
                    response.url,
                    'Payment',
                    'width='+width+',height='+height+',top='+((screen.height/2)-(height/2))+',left='+((screen.width/2)-(width/2))
                );
            } else {
                console.log('ajax error')
            }
        }
    };
    xhttp.open("POST", baseUrl+"/payment/paypal/post", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send(JSON.stringify(data));
}