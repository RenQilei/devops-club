@extends('layouts.main')

@section('body')
    <div class="row">
        @foreach($categories as $category)
            <div>
                <div class="col-lg-12">
                    <a href="{{ url('/category/'.$category['id']) }}">
                        {{ $category['name'] }}
                    </a>
                </div>
                <div class="col-lg-12">
                    @foreach($category['children'] as $childCategory)
                        <div class="col-lg-3">
                            <a href="{{ url('/category/'.$childCategory['id']) }}">
                                {{ $childCategory['name'] }}
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
@endsection

@section('head-partial')

@endsection

@section('foot-partial')

@endsection