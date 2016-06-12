@extends('layouts.main')

@section('body')
    <div>

    </div>
    <div>
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