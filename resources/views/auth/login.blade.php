<!DOCTYPE html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="HandheldFriendly" content="True">
    <meta name="MobileOptimized" content="320">

    <title>登录|DevOps Club</title>

    <link rel="stylesheet" type="text/css" href="{{ URL::asset('css/login.css') }}" />
    <script type="text/javascript" src="//cdn.bootcss.com/jquery/2.2.4/jquery.min.js"></script>
    <script type="text/javascript" src="//cdn.bootcss.com/modernizr/2.8.3/modernizr.min.js"></script>
    <script type="text/javascript" src="{{ URL::asset('js/login-placeholder.js') }}"></script>
</head>
<body>
    <form method="POST" action="/auth/login" id="slick-login">
        {{ csrf_field() }}

        <label for="email">邮箱</label>
        <input type="email" name="email" class="placeholder" value="{{ old('email') }}" placeholder="example@me.com">

        <label for="password">密码</label>
        <input type="password" name="password" class="placeholder" placeholder="密码">

        <input type="submit" value="登录">
    </form>
</body>
</html>