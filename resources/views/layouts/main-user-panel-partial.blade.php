@if(Auth::user())
    <div class="btn-group">
        <button type="button" class="btn header-btn dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            {{ Auth::user()->name }} <span class="caret"></span>
        </button>
        <ul class="dropdown-menu">
            <li>
                <a href="{{ url('/admin/'.Auth::user()->name) }}">
                    管理面板
                </a>
            </li>
            <li role="separator" class="divider"></li>
            <li>
                <a href="{{ url('/auth/logout') }}">
                    退出登录
                </a>
            </li>
        </ul>
    </div>
@else
    <a href="{{ url('/auth/login') }}">
        <button type="button" class="btn header-btn">登录</button>
    </a>
@endif