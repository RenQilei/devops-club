@extends('layouts.main')

@section('body')
    <div id="index-wrapper" class="row">
        {{-- Left siderbar --}}
        <div class="col-lg-2">
            @include('partials.left-sidebar')
        </div>
        {{-- Main body -- Article list --}}
        <div class="col-lg-7">
            <ul>
                @foreach($articles as $article)
                    <li class="index-article-list-item">
                        <div class="index-article-title">
                            <a href="{{ refineArticleUrl($article) }}">
                                {{ $article['title'] }}
                            </a>
                        </div>
                        <div class="index-article-additional">
                            <span>
                                {{ $article['date'] }}
                            </span>
                            <span>
                                &nbsp;|&nbsp;
                            </span>
                            <span>
                                {{ $article['user_info']['name'] }}
                            </span>
                        </div>
                        <div class="index-article-abstract">
                            {!! $article['abstract'] !!}
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>

        {{-- Right sidebar--}}
        <div class="col-lg-3">

        </div>
    </div>
@endsection

@section('head-partial')

@endsection

@section('foot-partial')

@endsection