@extends('layouts.main')

@section('body')
    <div class="row">
        {{-- Left siderbar --}}
        <div class="col-lg-2">
            <div id="index-sidebar-nav">
                <ul>
                    @foreach($categories as $category)
                        <li class="index-sidebar-nav-root-item">
                            {{-- 父节点 --}}
                            <a href="{{ url('/category/'.$category['slug']) }}">
                                {{ $category['name'] }}
                            </a>
                        </li>
                        <li>
                            <ul>
                                @foreach($category['children'] as $childCategory)
                                    <li class="index-sidebar-nav-child-item">
                                        {{-- 子节点 --}}
                                        <a href="{{ url('/category/'.$childCategory['slug']) }}">
                                            {{ $childCategory['name'] }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </li>
                    @endforeach
                </ul>
            </div>
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