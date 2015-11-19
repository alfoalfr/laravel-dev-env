(function setCallerOnClick(){
    lista = document.getElementsByClassName('providerLogin');
    for(i = 0; i < lista.length; i++){
        lista[i].onclick =
            function (e) {
                e.preventDefault();
                providerName = this.getAttribute('data-provider');
                width = 1100;
                height = 720;
                window.open(
                    this.getAttribute('data-url')+'/service/'+providerName+'/login',
                    'Login com '+providerName,
                    'width='+width+',height='+height+',top='+((screen.height/2)-(height/2))+',left='+((screen.width/2)-(width/2))
                );
            };
    }
}());

function facebookResponse(url, providerName, authToken, clientId, clientSecret, message, success){
    if (success == true){
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (xhttp.readyState == 4) {
                if (xhttp.status == 200) {
                    response = JSON.parse(xhttp.responseText);
                    finalCallback(true, response.access_token, xhttp.status, xhttp.statusText);
                } else {
                    finalCallback(false, null, xhttp.status, xhttp.statusText);
                }
            }
        };
        xhttp.open("POST", url, true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhttp.send(
            "grant_type=third_party_login&"+
            "client_id="+clientId+"&"+
            "client_secret="+clientSecret+"&"+
            "provider_name="+providerName+"&"+
            "provider_token="+authToken
        );
    }else{
        finalCallback(false, null, 500, message);
    }
}

function finalCallback(success, access_token, status, message){
    lista = document.getElementsByClassName('providerLogin');
    for(i = 0; i < lista.length; i++){
        if (lista[i].getAttribute('data-provider') == providerName){
            window[lista[i].getAttribute('data-callback')](success, access_token, status, message);
        }
    }
}