dataTag = document.getElementById('data');
response = JSON.parse(dataTag.getAttribute('data-result'));

if (response.paymentCallback !== ''){
    window.opener[response.paymentCallback](response);
}
window.close();