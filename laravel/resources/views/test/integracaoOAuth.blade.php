<!doctype html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <title>Teste Login oAuth</title>
    </head>
    <body>
        <button class="providerLogin" data-url="{{url('')}}" data-provider="facebook" data-callback="showUserInfo">Facebook Login</button>

        <button class="providerLogin" data-url="{{url('')}}" data-provider="google" data-callback="showUserInfo">Gmail login</button>

        <button class="providerLogin" data-url="{{url('')}}" data-provider="github" data-callback="showUserInfo">GitHub login</button>

        <div id="personalInfo">
            <img id="personalFoto" src="" alt="">
            <h2 id="personalName"></h2>
        </div>


        <script type="text/javascript" src="{!!asset('build/js/helpers/ajaxAndRequests.js')!!}"></script>
        <script type="text/javascript" src="{!!asset('build/js/third-party-login/loginMain.js')!!}"></script>
        <script>
            function showUserInfo(success, token, status, message){
                ajaxHelper('{{url('user/info')}}', token,
                    function (response, status, statusText){
                        document.getElementById('personalFoto').setAttribute('src', response.foto);
                        document.getElementById('personalName').innerHTML = response.name;
                    }, function (status, statusText){
                        console.log(xhttp.status+' - '+xhttp.statusText);
                    }
                );
            }
        </script>

    </body>
</html>