@extends('layouts.main')

@section('body')
    <div class="row">
        <div class="col-lg-2">
            @include('partials.left-sidebar')
        </div>
        <div class="col-lg-10">
            <div id="article-show-breadcrumb">
                <a href="{{ url('/') }}">
                    首页
                </a>
                /
                @if($article['category_info']['parent_category'])
                    <a href="{{ url('/category/'.$article['category_info']['parent_category_info']['slug']) }}">
                        {{ $article['category_info']['parent_category_info']['name'] }}
                    </a>
                    /
                @endif
                <a href="{{ url('/category/'.$article['category_info']['slug']) }}">
                    {{ $article['category_info']['name'] }}
                </a>
                /
                正文
            </div>
            <div id="article-show-title">
                @if($article['source_from'] == 0)
                    <span class="label label-primary article-show-title-label">原</span>
                @elseif($article['source_from'] == 1)
                    <span class="label label-primary article-show-title-label">译</span>
                @elseif($article['source_from'] == 2)
                    <span class="label label-primary article-show-title-label">转</span>
                @endif
                <span>
                    {{ $article['title'] }}
                </span>
                <span id="article-show-edit-panel">
                    @include('partials.article-edit-panel')
                </span>
            </div>
            <div class="typo" id="article-show-additional">
                @if($article['is_essential'])
                    <span class="label label-success article-show-additional-label">精华</span>
                @endif
                @if($article['is_wiki'])
                    <span class="label label-info article-show-additional-label">wiki</span>
                @endif
                {{ $article['user_info']['name'] }}&nbsp;写于&nbsp;{{ $article['date'] }}&nbsp;&nbsp;
                {{ $article['view_count'] }}&nbsp;次浏览
            </div>
            <div class="typo" id="article-show-content">
                {!! $article['content_html'] !!}
            </div>
            <div id="article-show-tags">
                <i class="fa fa-tags" aria-hidden="true"></i>
                @foreach($article['tags'] as $tag)
                    <a href="{{ url('/tag/'.$tag['id']) }}">
                        {{$tag['name']}}
                    </a>
                @endforeach
            </div>
            <div id="article-show-like">
                <button name="article-show-like-button" class="">
                    <i class="fa fa-heart-o" aria-hidden="true"></i>
                    喜欢
                    |
                    <span id="article-show-like-count">{{ $article['like_count'] }}</span>
                </button>
            </div>
        </div>
    </div>
@endsection

@section('head-partial')
    @if(Auth::user())
        <meta name="csrf-token" content="{{ csrf_token() }}">
    @endif
    <link type="text/css" href="//cdn.bootcss.com/sweetalert/1.1.3/sweetalert.min.css" rel="stylesheet">
    <link type="text/css" href="//cdn.bootcss.com/highlight.js/9.4.0/styles/solarized_light.min.css" rel="stylesheet">
@endsection

@section('foot-partial')
    <script type="text/javascript" src="//cdn.bootcss.com/highlight.js/9.4.0/highlight.min.js"></script>
    <script type="text/javascript">
        hljs.initHighlightingOnLoad();
    </script>
    <script type="text/javascript" src="//cdn.bootcss.com/sweetalert/1.1.3/sweetalert.min.js"></script>

    @include('partials.article-edit-script')

    <script type="text/javascript">
        $(document).ready(function() {
            var likeButton = $('button[name="article-show-like-button"]');
            var likeCountSpan = $('#article-show-like-count');
            @if(Auth::user())
                $.ajax("/article/check_user_article_like", {
                    type: 'post',
                    data: {
                        user_id: '{{Auth::user()->id}}',
                        article_id: '{{ $article['id'] }}'
                    },
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    success: function(response) {
                        if(response == 1) {
                            likeButton.addClass('article-show-like-button-active');
                            likeButton.css({"background-color": "#ec6d51", "color": "#ffffff"});
                        }
                    }
                });
            @endif
            likeButton.click(function() {
                @if(Auth::user())
                    // only could be operated if user authenticated.
                    if($(this).attr('class') == 'article-show-like-button-active') {
                        $.ajax("/article/{{$article['id']}}/modify_like", {
                            type: 'post',
                            data: {like:-1},
                            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                            success: function(response) {
                                if(response == 1) {
                                    likeButton.removeClass('article-show-like-button-active');
                                    likeButton.css({"background-color": "#ffffff", "color": "#ec6d51"});
                                    likeCountSpan.text(function(i, original) {
                                        return parseInt(original) - 1;
                                    });
                                }
                            }
                        });
                    }
                    else {
                        $.ajax("/article/{{$article['id']}}/modify_like", {
                            type: 'post',
                            data: {like:1},
                            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                            success: function(response) {
                                if(response == 1) {
                                    likeButton.addClass('article-show-like-button-active');
                                    likeButton.css({"background-color": "#ec6d51", "color": "#ffffff"});
                                    likeCountSpan.text(function(i, original) {
                                        return parseInt(original) + 1;
                                    });
                                }
                            }
                        });
                    }
                @else
                    window.location.href = "{{ url('/auth/login') }}";
                @endif
            });
        });
    </script>
@endsection