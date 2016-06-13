@extends('layouts.main')

@section('body')
    <div class="col-lg-2">
        @include('partials.left-sidebar')
    </div>
    <div class="col-lg-10">
        <ul>
            @foreach($articles as $article)
                <li>
                    <div>
                        {{ $article['title'] }}
                    </div>
                </li>
            @endforeach
        </ul>
    </div>
@endsection

@section('head-partial')

@endsection

@section('foot-partial')

@endsection