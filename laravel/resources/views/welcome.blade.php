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

        <button class="providerLogin" data-url="{{url('')}}" data-provider="facebook" data-callback="showUserInfo">Facebook Login</button>

        <button class="providerLogin" data-url="{{url('')}}" data-provider="google" data-callback="showUserInfo">Gmail login</button>

        <button class="providerLogin" data-url="{{url('')}}" data-provider="github" data-callback="showUserInfo">GitHub login</button>

        <div id="personalInfo">
            <img id="personalFoto" src="" alt="">
            <h2 id="personalName"></h2>
        </div>


        <script>
            function showUserInfo(success, token){
                console.log(success);
                console.log(token);
            }
        </script>

        <script type="text/javascript" src="{!!asset('build/js/third-party-login/loginMain.js')!!}"></script>
    </body>
</html>
