@extends('layouts.main')

@section('body')
    <div id="article-create-wrapper">
        <div id="article-create-title" class="row">
            <span class="col-lg-3">
                <h3>
                    <i class="fa fa-file-text-o" aria-hidden="true"></i>
                    写文章
                </h3>
            </span>
            <span id="article-create-writing-guidelines" class="col-lg-offset-6 col-lg-3">
                <a href="https://mazhuang.org/wiki/chinese-copywriting-guidelines/" target="_blank">
                    <i class="fa fa-lightbulb-o" aria-hidden="true"></i>
                    文案排版遵循规范
                </a>
            </span>
        </div>
        <form method="post" action="/article">
            {{ csrf_field() }}

            <!-- 文章来源 -->
            <div class="row">
                <div id="article-create-form-original-select" class="col-lg-2">
                    <select name="source-from" class="form-control">
                        @if(old('source-from') == 0)
                            <option value="0" selected>原创</option>
                        @else
                            <option value="0">原创</option>
                        @endif
                        @if(old('source-from') == 1)
                            <option value="1" selected>翻译</option>
                        @else
                            <option value="1">翻译</option>
                        @endif
                        @if(old('source-from') == 2)
                            <option value="2" selected>转载</option>
                        @else
                            <option value="2">转载</option>
                        @endif
                    </select>
                </div>

                <!-- 文章分类 -->
                <div id="article-create-form-category-select" class="col-lg-4">
                    <select name="category" class="form-control">
                        <option value="">请选择一个分类</option>
                        @foreach($categories as $rootCategory)
                            <optgroup label="{{ $rootCategory['name'] }}">
                                @if($rootCategory['id'] == old('category'))
                                    <option value="{{ $rootCategory['id'] }}" selected>{{ $rootCategory['name'] }}</option>
                                @else
                                    <option value="{{ $rootCategory['id'] }}">{{ $rootCategory['name'] }}</option>
                                @endif
                                @foreach($rootCategory['children'] as $childCategory)
                                    @if($childCategory['id'] == old('category'))
                                        <option value="{{ $childCategory['id'] }}" selected>{{ $childCategory['name'] }}</option>
                                    @else
                                        <option value="{{ $childCategory['id'] }}">{{ $childCategory['name'] }}</option>
                                    @endif
                                @endforeach
                            </optgroup>
                        @endforeach
                    </select>
                </div>

                <!-- 文章专题 -->
                <div id="article-create-form-category-select" class="col-lg-4">
                    <select name="topic" class="form-control">
                        <option value="">请选择一个专题</option>
                        {{--@foreach($categories as $rootCategory)--}}
                            {{--<optgroup label="{{ $rootCategory['name'] }}">--}}
                                {{--@if($rootCategory['id'] == old('category'))--}}
                                    {{--<option value="{{ $rootCategory['id'] }}" selected>{{ $rootCategory['name'] }}</option>--}}
                                {{--@else--}}
                                    {{--<option value="{{ $rootCategory['id'] }}">{{ $rootCategory['name'] }}</option>--}}
                                {{--@endif--}}
                                {{--@foreach($rootCategory['children'] as $childCategory)--}}
                                    {{--@if($childCategory['id'] == old('category'))--}}
                                        {{--<option value="{{ $childCategory['id'] }}" selected>{{ $childCategory['name'] }}</option>--}}
                                    {{--@else--}}
                                        {{--<option value="{{ $childCategory['id'] }}">{{ $childCategory['name'] }}</option>--}}
                                    {{--@endif--}}
                                {{--@endforeach--}}
                            {{--</optgroup>--}}
                        {{--@endforeach--}}
                    </select>
                </div>

                <div class="col-lg-2">
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#mymodal">新增专题</button>

                    <div id="mymodal" class="modal fade" tabindex="-1" role="dialog">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <h4 class="modal-title">Modal title</h4>
                                </div>
                                <div class="modal-body">
                                    <p>One fine body&hellip;</p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    <button type="button" class="btn btn-primary">Save changes</button>
                                </div>
                            </div><!-- /.modal-content -->
                        </div><!-- /.modal-dialog -->
                    </div><!-- /.modal -->
                </div>
            </div>

            <div id="article-create-form-title" class="row">
                <div class="col-lg-12">
                    <input type="text" class="form-control" name="title" value="{{ old('title') }}" placeholder="此处键入标题...">
                </div>
            </div>

            <div id="editor">
                <textarea>{{ old('editor-markdown-doc') }}</textarea>
            </div>

            <div id="article-create-form-uri" class="row">
                <div class="col-lg-12">
                    <input type="text" class="form-control" name="uri" value="{{ old('uri') }}" placeholder="此处键入URI..." data-toggle="tooltip" data-placement="top" title="仅支持字母、数字、下划线和空格。">
                </div>
            </div>

            <div id="article-create-form-tags" class="row">
                <div class="col-lg-12">
                    <input type="text" class="form-control" name="tags" value="{{ old('tags') }}" placeholder="">
                </div>
            </div>

            <div id="article-create-form-submit">
                <button class="btn btn-success" name="submit" type="submit" value="submit">发布</button>
            </div>
        </form>
    </div>
@endsection

@section('head-partial')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link type="text/css" href="{{ URL::asset('editor.md/css/editormd.min.css') }}" rel="stylesheet" />
    <link type="text/css" href="{{ URL::asset('tagEditor/jquery.tag-editor.css') }}" rel="stylesheet" />
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
    <script type="text/javascript" src="{{ URL::asset('tagEditor/jquery.tag-editor.min.js') }}"></script>
    <script type="text/javascript">
        $('input[name="tags"]').tagEditor({
            delimiter: ',， ',
            placeholder: '此处键入标签...'
        });
    </script>

    <script type="text/javascript">
//        $(function() {
//            $('#myModal').modal();
//        });

        $(function () {
            $('[data-toggle="tooltip"]').tooltip();
        });

        $(function() {
            $("input[name='uri']").change(function() {
                // retrieve latest value of uri after typing
                var uri = $(this).val();
                // get the length of uri
                var uriLength = uri.length;
                // evaluate the correctness of uri
                // only allow english character, number, '_', ' '
                var reg = new RegExp("[\\w| ]{" + uriLength + "}");
                var result = reg.test(uri);

                if (!result) {
                    // error

                    // display error info

                    // disable submit button
                    $("button[name='submit']").attr('disabled', 'disabled');
                }
                else {
                    // correct

                    // enable submit button
                    $("button[name='submit']").removeAttr('disabled');
                }
            });
        });
    </script>
@endsection