dataTag = document.getElementById('data');

window.opener.facebookResponse(
    dataTag.getAttribute('data-url'),
    dataTag.getAttribute('data-provider'),
    dataTag.getAttribute('data-token'),
    dataTag.getAttribute('data-client-id'),
    dataTag.getAttribute('data-client-secret'),
    dataTag.getAttribute('data-message'),
    dataTag.getAttribute('data-success')
);

window.close();
