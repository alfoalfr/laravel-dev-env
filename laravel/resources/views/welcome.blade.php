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

        <a id="providerLogin" href="{{url('service/facebook/login')}}">Facebook Login</a>

        <script>
            document.getElementById('providerLogin').onclick =
            function fbclick(e) {
                e.preventDefault();
                window.open(this.getAttribute('href'), 'LoginWithFacebook', 'width=978,height=672');
            };

            function facebookResponse(user){
                console.log(user);

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
            }
        </script>
    </body>
</html>
