<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="HandheldFriendly" content="True">
    <meta name="MobileOptimized" content="320">

    <title>DevOps Club</title>

    <link rel="stylesheet" type="text/css" media="screen" href="//cdn.bootcss.com/bootstrap/3.3.6/css/bootstrap.min.css" />
    <link rel="stylesheet" type="text/css" href="//cdn.bootcss.com/font-awesome/4.6.3/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('css/style.css') }}">

    @yield('head-partial')
</head>
<body>
<header class="container">
    <div row>
        <div id="logo" class="col-lg-2">
            <a href="{{ url('/') }}">
                <img src="{{ URL::asset('image/devops-club-logo.jpg') }}">
            </a>
        </div>
        <nav class="col-lg-8">
            <ul class="list-inline">
                <li>
                    <button type="button" class="btn btn-default">
                        <i class="fa fa-list-ul" aria-hidden="true"></i>
                        分类
                    </button>
                </li>
            </ul>
        </nav>
        <div id="add-panel" class="col-lg-2">
            <a href="{{ url('/article/create') }}">
                <button type="button" class="btn btn-success">写文章</button>
            </a>
        </div>
    </div>
</header>

<main class="container">
    @yield('body')
</main>

<footer>

</footer>

<script type="text/javascript" src="//cdn.bootcss.com/jquery/2.2.4/jquery.min.js"></script>
@yield('foot-partial')

</body>
</html>
