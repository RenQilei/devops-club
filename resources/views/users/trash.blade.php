@extends('layouts.user')

@section('user-body')
    <div class="user-main-breadcrumb">
        我的文章
        /
        <a href="{{ url('/user/'.Auth::user()->name.'/trash') }}">回收站</a>
    </div>
    <table id="user-article-table" class="table table-striped table-bordered" cellspacing="0" width="100%">
        <thead>
        <tr>
            <th>标题</th>
            <th>分类</th>
            <th>删除日期</th>
            <th>阅读量</th>
            <th>管理</th>
        </tr>
        </thead>
        <tbody>
        @foreach($articlesInTrash as $article)
            <tr>
                <td>
                    <a href="{{ refineArticleUrl($article) }}">
                        {{ mb_substr($article['title'],0,20,'utf-8') }}
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
                    <span>
                        恢复
                    </span>
                    <span>
                        彻底删除
                    </span>
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
            $('#user-article-table').DataTable({
                "lengthChange": false,
                "pageLength": 50,
                "ordering": false,
                "language": {
                    "sProcessing":   "处理中...",
                    "sLengthMenu":   "显示 _MENU_ 项结果",
                    "sZeroRecords":  "没有匹配结果",
                    "sInfo":         "显示第 _START_ 至 _END_ 项结果，共 _TOTAL_ 项",
                    "sInfoEmpty":    "显示第 0 至 0 项结果，共 0 项",
                    "sInfoFiltered": "(由 _MAX_ 项结果过滤)",
                    "sInfoPostFix":  "",
                    "sSearch":       "搜索:",
                    "sUrl":          "",
                    "sEmptyTable":     "表中数据为空",
                    "sLoadingRecords": "载入中...",
                    "sInfoThousands":  ",",
                    "oPaginate": {
                        "sFirst":    "首页",
                        "sPrevious": "上页",
                        "sNext":     "下页",
                        "sLast":     "末页"
                    },
                    "oAria": {
                        "sSortAscending":  ": 以升序排列此列",
                        "sSortDescending": ": 以降序排列此列"
                    }
                }
            });
        });
    </script>
    <script type="text/javascript" src="//cdn.bootcss.com/sweetalert/1.1.3/sweetalert.min.js"></script>
    @include('partials.article-edit-script')
@endsection