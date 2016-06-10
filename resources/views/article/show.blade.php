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
            <div class="btn-group" role="group" aria-label="文章管理面板">
                @if(Auth::user())
                    <button type="button" class="btn btn-default">
                        <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                        修改
                    </button>
                    @if(1)
                        @if($article['is_essential'])
                            <button type="button" name="essential-button" class="btn btn-default" id="article-show-essential-button-on">
                        @else
                            <button type="button" name="essential-button" class="btn btn-default">
                        @endif
                            <i class="fa fa-fire" aria-hidden="true"></i>
                            精华
                        </button>
                        @if($article['is_wiki'])
                            <button type="button" name="wiki-button" class="btn btn-default" id="article-show-wiki-button-on">
                        @else
                            <button type="button" name="wiki-button" class="btn btn-default">
                        @endif
                            <i class="fa fa-wikipedia-w" aria-hidden="true"></i>
                            wiki
                        </button>
                    @endif
                    <button type="button" class="btn btn-default">
                        <i class="fa fa-times" aria-hidden="true"></i>
                        删除
                    </button>
                @endif
            </div>
        </span>
    </div>
    <div class="typo" id="article-show-additional">
        @if($article['is_essential'])
            <span class="label label-success article-show-additional-label">精华</span>
        @endif
        @if($article['is_wiki'])
            <span class="label label-info article-show-additional-label">wiki</span>
        @endif
        {{ $article['author'] }}&nbsp;写于&nbsp;{{ $article['created_at'] }}&nbsp;&nbsp;
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
@endsection

@section('foot-partial')
    <script type="text/javascript">
        $(document).ready(function() {
            var essentialButton = $("button[name='essential-button']");
            var wikiButton = $("button[name='wiki-button']");
            essentialButton.click(function() {
                $.ajax("/article/{{$article['id']}}/set_essential", {
                    type: 'post',
                    data: {},
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    success: function(response) {
                        if(response == 1) {
                            essentialButton.attr('id', 'article-show-essential-button-on');
                        }
                        if(response == 0) {
                            essentialButton.attr('id', '');
                        }
                    }
                });
            });
            wikiButton.click(function() {
                $.ajax("/article/{{$article['id']}}/set_wiki", {
                    type: 'post',
                    data: {},
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    success: function(response) {
                        if(response == 1) {
                            wikiButton.attr('id', 'article-show-wiki-button-on');
                        }
                        if(response == 0) {
                            wikiButton.attr('id', '');
                        }
                    }
                });
            });
        });
    </script>
@endsection