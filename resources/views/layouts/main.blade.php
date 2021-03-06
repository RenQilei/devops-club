<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="HandheldFriendly" content="True">
    <meta name="MobileOptimized" content="320">

    <meta name="Keywords" content="Devops,开发运维,开发技术,前端,研发,运维">
    <meta name="Description" content="与 Devops 相关的原创文章、外文翻译和转载分享。">

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

    <!-- 百度提交 -->
    <script>
        (function(){
            var bp = document.createElement('script');
            var curProtocol = window.location.protocol.split(':')[0];
            if (curProtocol === 'https') {
                bp.src = 'https://zz.bdstatic.com/linksubmit/push.js';
            }
            else {
                bp.src = 'http://push.zhanzhang.baidu.com/push.js';
            }
            var s = document.getElementsByTagName("script")[0];
            s.parentNode.insertBefore(bp, s);
        })();
    </script>
</head>
<body>
<header>
    <div class="container">
        <div class="row">
            <div id="logo" class="col-lg-2">
                <a href="{{ url('/') }}">
                    <img alt="DevOps Club" src="{{ URL::asset('image/devops-club-logo.png') }}">
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
    <div id="main-wrapper" class="row">
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
        if((!document.getElementById('editor')) && (($('header').outerHeight(true) + $('main').outerHeight(true) + $('footer').outerHeight(true) + 50) < $(window).height())) {
            $('footer').css({'bottom': '0', 'position': 'absolute', 'width': '100%'});
        }
    });
</script>

@if(isset($categories))
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
@endif

@yield('foot-partial')

</body>
</html>
