<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>{{$providerName}} Login</title>
</head>
<body>
<script>
    @if(isset($user))
        window.opener.facebookResponse({!!json_encode($user)!!});
    @else()
        window.opener.facebookResponse();
    @endif
    window.close();
</script>
</body>
</html>