@extends('layouts.user')

@section('user-body')
    <div class="user-main-breadcrumb">
        我的文章
        /
        <a href="{{ url('/user/'.Auth::user()->name.'/article') }}">所有文章</a>
    </div>
    <table id="user-article-table" class="table table-striped table-bordered" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>标题</th>
                <th>分类</th>
                <th>日期</th>
                <th>阅读量</th>
                <th>管理</th>
            </tr>
        </thead>
        <tbody>
            @foreach($articles as $article)
                <tr>
                    <td>
                        <a href="{{ refineArticleUrl($article) }}">
                            {{ $article['title'] }}
                        </a>
                    </td>
                    <td>
                        <a href="{{ url('/category/'.$article['category_info']['slug']) }}">
                            {{ $article['category_info']['name'] }}
                        </a>
                    </td>
                    <td>
                        {{ $article['date'] }}
                    </td>
                    <td>
                        {{ $article['view_count'] }}
                    </td>
                    <td class="user-article-table-edit">
                        @include('partials.article-edit-panel')
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection

@section('head-partial')
    @if(Auth::user())
        <meta name="csrf-token" content="{{ csrf_token() }}">
    @endif
    <link type="text/css" href="//cdn.bootcss.com/datatables/1.10.12/css/jquery.dataTables.min.css" rel="stylesheet">
    <link type="text/css" href="//cdn.bootcss.com/datatables/1.10.12/css/dataTables.bootstrap.min.css" rel="stylesheet">
    <link type="text/css" href="//cdn.bootcss.com/sweetalert/1.1.3/sweetalert.min.css" rel="stylesheet">
@endsection

@section('foot-partial')
    <script type="text/javascript" src="//cdn.bootcss.com/datatables/1.10.12/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="//cdn.bootcss.com/datatables/1.10.12/js/dataTables.bootstrap.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#user-article-table').DataTable();
        });
    </script>
    <script type="text/javascript" src="//cdn.bootcss.com/sweetalert/1.1.3/sweetalert.min.js"></script>
    @include('partials.article-edit-script')
@endsection