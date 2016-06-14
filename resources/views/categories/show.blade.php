@extends('layouts.main')

@section('body')
    <div class="col-lg-2">
        @include('partials.left-sidebar')
    </div>
    <div class="col-lg-7">
        <ul>
            @foreach($articles as $article)
                <li class="category-show-article-list-item">
                    <div class="category-show-article-title">
                        <a href="{{ refineArticleUrl($article) }}">
                            {{ $article['title'] }}
                        </a>
                    </div>
                    <div class="category-show-article-additional">
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
                    <div class="category-show-article-abstract">
                        {!! $article['abstract'] !!}
                    </div>
                </li>
            @endforeach
        </ul>
    </div>
    <div class="col-lg-3">

    </div>
@endsection

@section('head-partial')

@endsection

@section('foot-partial')

@endsection