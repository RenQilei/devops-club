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
    <form method="POST" action="/auth/register" id="slick-login">
        {!! csrf_field() !!}

        <label for="name">用户名</label>
        <input type="text" name="name" value="{{ old('name') }}" placeholder="用户名">

        <label for="email">邮箱</label>
        <input type="email" name="email" value="{{ old('email') }}" placeholder="邮箱">

        <label for="password">密码</label>
        <input type="password" name="password" placeholder="密码">

        <label for="password_confirmation">确认密码</label>
        <input type="password" name="password_confirmation" placeholder="确认密码">

        <label for="role">角色</label>
        <select name="role">
            <option value="1" select>管理员</option>
            <option value="2">编辑</option>
            <option value="3">作者</option>
        </select>

        <input type="submit" value="注册">
    </form>
</body>
</html>