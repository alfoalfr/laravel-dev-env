<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>{{$providerName}} Login</title>
</head>
<body>
<div id="data"
     data-url='{{url('service/login')}}'
     data-provider='{!!$providerName!!}'
     data-token='{!!$authToken!!}'
     data-client-id='{!!$clientId!!}'
     data-client-secret='{!!$clientSecret!!}'
     data-message='{!!$message!!}'
     data-success='{!!$success!!}'>
</div>

<script type="text/javascript" src="{!!asset('build/js/third-party-login/loginPopup.js')!!}"></script>
</body>
</html>