@extends('layouts.main')

@section('body')
    <div class="row">
        <div class="col-lg-3">
            <ul>
                @foreach($categories as $category)
                    <li>
                        {{-- 父节点 --}}
                        <a href="{{ url('/category/'.$category['name']) }}">
                            {{ $category['name'] }}
                        </a>
                    </li>
                    <li>
                        <ul>
                            @foreach($category['children'] as $childCategory)
                                <li>
                                    {{-- 子节点 --}}
                                    <a href="{{ url('/category/'.$category['name']) }}">
                                        {{ $childCategory['name'] }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </li>
                @endforeach
            </ul>
        </div>
        <div class="col-lg-9">

        </div>
    </div>
@endsection

@section('head-partial')

@endsection

@section('foot-partial')

@endsection