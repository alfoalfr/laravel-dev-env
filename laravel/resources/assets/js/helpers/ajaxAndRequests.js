function ajaxHelper(url, token, successCallback, failCallback, tipo, dados){
    tipo = tipo || "GET";
    dados = dados || "";

    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (xhttp.readyState == 4) {
            if (xhttp.status == 200) {
                response = JSON.parse(xhttp.responseText);
                successCallback(response, xhttp.status, xhttp.statusText);
            } else {
                failCallback(xhttp.status, xhttp.statusText);
            }
        }
    };
    xhttp.open(tipo, url+'?access token='+token, true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send(dados);
}