@extends('layouts.main')

@section('body')
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
    <div id="article-show-like">
        <span>
            <i class="fa fa-heart-o" aria-hidden="true"></i>
            {{ $article['like_count'] }}
        </span>
    </div>
@endsection

@section('head-partial')
    @if(Auth::user())
        <meta name="csrf-token" content="{{ csrf_token() }}">
    @endif
    <link type="text/css" href="//cdn.bootcss.com/sweetalert/1.1.3/sweetalert.min.css" rel="stylesheet">
@endsection

@section('foot-partial')
    <script type="text/javascript" src="//cdn.bootcss.com/sweetalert/1.1.3/sweetalert.min.js"></script>
    @include('partials.article-edit-script')
@endsection