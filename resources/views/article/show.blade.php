@extends('layouts.main')

@section('body')
    <div id="article-show-title">
        {{ $article['title'] }}
    </div>
    <div id="article-show-content">
        {!! $article['content_html'] !!}
    </div>
@endsection

@section('head-partial')

@endsection

@section('foot-partial')

@endsection