@extends('layouts.main')

@section('body')
    <div id="article-create-wrapper">
        <div id="article-create-title">
            <h3>
                <i class="fa fa-file-text-o" aria-hidden="true"></i>
                写文章
            </h3>
        </div>
        <form method="post" action="/article">
            {{ csrf_field() }}

            <div class="row">
                <div id="article-create-form-original-select" class="col-lg-4">
                    <select name="source-from" class="form-control">
                        <option value="0">原创</option>
                        <option value="1">翻译</option>
                        <option value="2">转载</option>
                    </select>
                </div>

                <div id="article-create-form-category-select" class="col-lg-8">
                    <select name="category" class="form-control">
                        <option value="">请选择一个分类</option>
                        @foreach($categories as $rootCategory)
                            <optgroup label="{{ $rootCategory['name'] }}">
                                <option value="{{ $rootCategory['id'] }}">{{ $rootCategory['name'] }}</option>
                                @foreach($rootCategory['children'] as $childCategory)
                                    <option value="{{ $childCategory['id'] }}">{{ $childCategory['name'] }}</option>
                                @endforeach
                            </optgroup>
                        @endforeach
                    </select>
                </div>
            </div>

            <div id="article-create-form-title" class="row">
                <div class="col-lg-12">
                    <input type="text" class="form-control" name="title" placeholder="此处键入标题...">
                </div>
            </div>

            <div id="editor">
            </div>

            <div id="article-create-form-uri" class="row">
                <div class="col-lg-12">
                    <input type="text" class="form-control" name="uri" placeholder="此处键入URI...">
                </div>
            </div>

            <div id="article-create-form-submit">
                <button class="btn btn-success" type="submit" value="submit">发布</button>
            </div>
        </form>
    </div>
@endsection

@section('head-partial')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('editor.md/css/editormd.min.css') }}" />
@endsection

@section('foot-partial')
    <script type="text/javascript" src="{{ URL::asset('editor.md/editormd.min.js') }}"></script>
    <script type="text/javascript">
        $(function() {
            var editor = editormd({
                id: "editor",
                width: "100%",
                height: 480,
                path: '{{ URL::asset('editor.md/lib')  }}/',
                toolbarIcons : function() {
                    return ["undo", "redo", "|", "bold", "del", "italic", "quote", "|", "h1", "h2", "h3", "h4", "h5", "h6", "|", "list-ul", "list-ol", "hr", "|", "link", "reference-link", "image", "code", "preformatted-text", "code-block", "table", "datetime", "emoji", "html-entities", "|", "preview", "fullscreen"]
                },
                codeFold: true,
                saveHTMLToTextarea: true, // 保存 HTML 到 Textarea
                emoji: true,
                taskList: true,
                tocm: true, // Using [TOCM]
                tex: true, // 开启科学公式TeX语言支持，默认关闭
                flowChart: true, // 开启流程图支持，默认关闭
                sequenceDiagram: true, // 开启时序/序列图支持，默认关闭
                imageUpload : true,
                imageFormats : ["jpg", "jpeg", "gif", "png", "bmp", "webp"],
                imageUploadURL : "{{ url('/article/image/upload') }}"
            });
        });
    </script>
@endsection