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

        <a class="providerLogin" href="{{url('service/facebook/login')}}">Facebook Login</a>

        <a class="providerLogin" href="{{url('service/google/login')}}">Gmail login</a>

        <a class="providerLogin" href="{{url('service/github/login')}}">GitHub login</a>

        <script>
            lista = document.getElementsByClassName('providerLogin');
            for(i = 0; i < lista.length; i++){
                lista[i].onclick =
                    function fbclick(e) {
                        e.preventDefault();
                        window.open(
                                this.getAttribute('href'),
                                'LoginWithFacebook',
                                'width=1100,height=720,top='+((screen.height/2)-(360))+',left='+((screen.width/2)-(550))
                        );
                    };
            }

            function facebookResponse(user){
                if (user != null){
                    var xhttp = new XMLHttpRequest();
                    xhttp.onreadystatechange = function() {
                        if (xhttp.readyState == 4) {
                            if (xhttp.status == 200) {
                                console.log(xhttp.responseText);
                            } else {
                                console.log('error');
                            }
                        }
                    };
                    xhttp.open("POST", "{{url('service/save')}}", true);
                    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                    xhttp.send("user="+user);
                }else{
                    console.log('error');
                }
            }
        </script>
    </body>
</html>
