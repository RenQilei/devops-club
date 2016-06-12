<div class="btn-group" role="group" aria-label="文章管理面板">
    @if(Auth::user())
        <button type="button" name="edit-button" value="{{ $article['id'] }}" class="btn btn-default">
            <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
            修改
        </button>

        @if(Auth::user()->hasRole('admin'))
            @if($article['is_essential'])
                <button type="button" name="essential-button" value="{{ $article['id'] }}" class="btn btn-default" id="article-show-essential-button-on">
            @else
                <button type="button" name="essential-button" value="{{ $article['id'] }}" class="btn btn-default">
            @endif
                <i class="fa fa-fire" aria-hidden="true"></i>
                精华
            </button>

            @if($article['is_wiki'])
                <button type="button" name="wiki-button" value="{{ $article['id'] }}" class="btn btn-default" id="article-show-wiki-button-on">
            @else
                <button type="button" name="wiki-button" value="{{ $article['id'] }}" class="btn btn-default">
            @endif
                <i class="fa fa-wikipedia-w" aria-hidden="true"></i>
                wiki
            </button>
        @endif

        <button type="button" name="delete-button" value="{{ $article['id'] }}" class="btn btn-default">
            <i class="fa fa-times" aria-hidden="true"></i>
            删除
        </button>
    @endif
</div>