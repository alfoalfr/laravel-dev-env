<!DOCTYPE html>
<html>
    <head>
        <title>Laravel</title>

        <link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">

        <style>
            html, body {
                height: 100%;
            }

            body {
                margin: 0;
                padding: 0;
                width: 100%;
                display: table;
                font-weight: 100;
                font-family: 'Lato';
            }

            .container {
                text-align: center;
                display: table-cell;
                vertical-align: middle;
            }

            .content {
                text-align: center;
                display: inline-block;
            }

            .title {
                font-size: 96px;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="content">
                <div class="title">Laravel 5</div>
            </div>
        </div>

        <button class="providerLogin" data-url="{{url('')}}" data-provider="facebook">Facebook Login</button>

        <button class="providerLogin" data-url="{{url('')}}" data-provider="google">Gmail login</button>

        <button class="providerLogin" data-url="{{url('')}}" data-provider="github">GitHub login</button>

        <script>
            lista = document.getElementsByClassName('providerLogin');
            for(i = 0; i < lista.length; i++){
                lista[i].onclick =
                    function fbclick(e) {
                        e.preventDefault();
                        providerName = this.getAttribute('data-provider');
                        window.open(
                                this.getAttribute('data-url')+'/service/'+providerName+'/login',
                                'Login com '+providerName,
                                'width=1100,height=720,top='+((screen.height/2)-(360))+',left='+((screen.width/2)-(550))
                        );
                    };
            }

            function facebookResponse(providerName, authToken, clientId, clientSecret, message, success){
                if (success == true){
                    var xhttp = new XMLHttpRequest();
                    xhttp.onreadystatechange = function() {
                        if (xhttp.readyState == 4) {
                            if (xhttp.status == 200) {
                                console.log(xhttp.responseText);
                            } else {
                                console.log("Erro: Não foi possivel se conectar com o servidor.");
                            }
                        }
                    };
                    xhttp.open("POST", "{{url('service/login')}}", true);
                    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                    xhttp.send(
                            "grant_type=third_party_login&"+
                            "client_id="+clientId+"&"+
                            "client_secret="+clientSecret+"&"+
                            "provider_name="+providerName+"&"+
                            "provider_token="+authToken
                    );
                }else{
                    console.log("message: "+message);
                }
            }
        </script>
    </body>
</html>
