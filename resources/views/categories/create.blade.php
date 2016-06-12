@extends('layouts.main')

@section('body')
    <div id="article-create-wrapper">
        <div id="article-create-title">
            <h3>
                <i class="fa fa-file-text-o" aria-hidden="true"></i>
                新建分类
            </h3>
        </div>
        <form method="post" action="/category">
            {{ csrf_field() }}

            <div class="form-group">
                <label for="name">显示名称</label>
                <input type="text" name="name" class="form-control" placeholder="">
            </div>
            <div class="form-group">
                <label for="slug">存储名称（仅支持英文）</label>
                <input type="text" name="slug" class="form-control" placeholder="请键入英文名称...">
            </div>
            <div class="form-group">
                <label for="slug">详细描述</label>
                <textarea name="description" class="form-control" rows="3"></textarea>
            </div>
            <div class="form-group">
                <label for="parent_category">所属分类</label>
                <select name="parent_category" class="form-control">
                    <option value="0" selected>无所属分类</option>
                    @foreach($rootCategories as $rootCategory)
                        <option value="{{ $rootCategory['id'] }}">
                            {{ $rootCategory['name'] }}
                        </option>
                    @endforeach
                </select>
            </div>
            <button type="submit" id="category-create-submit-button" class="btn btn-success">创建分类</button>
        </form>
    </div>
@endsection

@section('head-partial')

@endsection

@section('foot-partial')

@endsection