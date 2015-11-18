<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>{{$providerName}} Login</title>
</head>
<body>
<script>
    window.opener.facebookResponse(
            '{!!$providerName!!}',
            '{!!$authToken!!}',
            '{!!$clientId!!}',
            '{!!$clientSecret!!}',
            '{!!$message!!}',
            '{!!$success!!}'
    );
    window.close();
</script>
</body>
</html>