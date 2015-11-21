dataTag = document.getElementById('data');
response = JSON.parse(dataTag.getAttribute('data-result')) || '';

if (response.hasOwnProperty('paymentCallback') && response.paymentCallback !== '' && !response.hasOwnProperty('avoid-callback')){
    window.opener[response.paymentCallback](response);
}

window.close();