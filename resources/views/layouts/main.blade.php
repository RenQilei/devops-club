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
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('typo.css/typo.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('css/style.css') }}">

    @yield('head-partial')

    <!-- 百度统计 -->
    <script type="text/javascript">
        var _hmt = _hmt || [];
        (function() {
            var hm = document.createElement("script");
            hm.src = "//hm.baidu.com/hm.js?661a72f655c767507c494a04f9adbacf";
            var s = document.getElementsByTagName("script")[0];
            s.parentNode.insertBefore(hm, s);
        })();
    </script>
</head>
<body>
<header>
    <div class="container">
        <div class="row">
            <div id="logo" class="col-lg-2">
                <a href="{{ url('/') }}">
                    <img src="{{ URL::asset('image/devops-club-logo.png') }}">
                </a>
            </div>
            <nav class="col-lg-8">
                {{--<ul class="list-inline">--}}
                    {{--<li>--}}
                        {{--<button type="button" class="btn btn-default">--}}
                            {{--<i class="fa fa-list-ul" aria-hidden="true"></i>--}}
                            {{--分类--}}
                        {{--</button>--}}
                    {{--</li>--}}
                {{--</ul>--}}
            </nav>
            <div id="header-panel" class="col-lg-2">
            <span>
                <a href="{{ url('/article/create') }}">
                    <button type="button" class="btn header-btn">写文章</button>
                </a>
            </span>
            <span>
                @include('partials.main-user-panel')
            </span>
            </div>
        </div>
    </div>
</header>

<main class="container">
    <div id="main-wrapper">
        @yield('body')
    </div>
</main>

<footer>
    <div class="container">
        &copy; 2016
        <a href="{{ url('/') }}">DevOps-Club</a>
        <a href="www.miitbeian.gov.cn">苏ICP备16025223号-1</a>
    </div>
</footer>

<script type="text/javascript" src="//cdn.bootcss.com/jquery/2.2.4/jquery.min.js"></script>
<script type="text/javascript" src="//cdn.bootcss.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>

<script type="text/javascript">
    $(document).ready(function() {
        if((!document.getElementById('editor')) && (($('header').outerHeight() + $('main').outerHeight() + $('footer').outerHeight() + 50) < window.screen.availHeight)) {
            $('footer').css({'bottom': '0', 'position': 'absolute', 'width': '100%'});
        }
    });
</script>

<script type="text/javascript">
    $(document).ready(function() {
        var url = document.URL;
        @foreach($categories as $category)
            var categorySlug = '{{ $category['slug'] }}';
            if(('{{ url('/category') }}' + '/' + categorySlug) == url) {
                $('.left-sidebar-nav-root-item-active').removeClass('left-sidebar-nav-root-item-active');
                $(('#left-sidebar-nav-' + categorySlug)).addClass('left-sidebar-nav-root-item-active');
                $(('#left-sidebar-nav-' + categorySlug) + '>a').css('color', '#ffffff');
            }
            @foreach($category['children'] as $childCategory)
                var childCategorySlug = '{{ $childCategory['slug'] }}';
                if(('{{ url('/category') }}' + '/' + childCategorySlug) == url) {
                    $('.left-sidebar-nav-child-item-active').removeClass('left-sidebar-nav-child-item-active');
                    $(('#left-sidebar-nav-' + childCategorySlug)).addClass('left-sidebar-nav-child-item-active');
                    $(('#left-sidebar-nav-' + childCategorySlug) + '>a').css('color', '#ffffff');
                }
            @endforeach
        @endforeach
    });
</script>

@yield('foot-partial')

</body>
</html>
