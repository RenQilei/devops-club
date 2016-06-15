@extends('layouts.main')

@section('body')
    <div id="user-main-sidebar-nav" class="col-lg-3">
        <ul>
            <li class="user-main-sidebar-nav-title">
                我的文章
            </li>
            <li>
                <ul>
                    <li>
                        <a href="{{ url('/user/'.Auth::user()->name.'/article') }}">所有文章</a>
                    </li>
                    <li>
                        <a href="{{ url('/user/'.Auth::user()->name.'/trash') }}">回收站</a>
                    </li>
                </ul>
            </li>
            <li class="user-main-sidebar-nav-title">
                我的账号
            </li>
            <li>
                <ul>
                    <li>
                        <a href="">修改用户名</a>
                    </li>
                    <li>
                        <a href="">重设密码</a>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
    <div class="col-lg-9">
        @yield('user-body')
    </div>
@endsection