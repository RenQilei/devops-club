@extends('layouts.main')

@section('body')
    <div class="col-lg-2">
        @include('partials.left-sidebar')
    </div>
    <div class="col-lg-7">
        <div id="category-show-breadcrumb">
            <a href="{{ url('/') }}">
                首页
            </a>
            /
            @if($category['parent_category'])
                <a href="{{ url('/category/'.$category['parent_category_info']['slug']) }}">
                    {{ $category['parent_category_info']['name'] }}
                </a>
                /
            @endif
            <a href="{{ url('/category/'.$category['slug']) }}">
                {{ $category['name'] }}
            </a>
        </div>
        <ul>
            @foreach($articles as $article)
                <li class="category-show-article-list-item">
                    <div class="category-show-article-title">
                        <a href="{{ refineArticleUrl($article) }}">
                            {{ $article['title'] }}
                        </a>
                    </div>
                    <div class="category-show-article-additional">
                            @if(!$category['parent_category'])
                                <span>
                                    <a href="{{ url('/category/'.$article['category_info']['id']) }}">
                                        {{ $article['category_info']['name'] }}
                                    </a>
                                </span>
                                <span>
                                    &nbsp;|&nbsp;
                                </span>
                            @endif
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