<div id="left-sidebar-nav">
    <ul>
        @foreach($categories as $category)
            <li id="left-sidebar-nav-{{ $category['slug'] }}" class="left-sidebar-nav-root-item">
                {{-- 父节点 --}}
                <a href="{{ url('/category/'.$category['slug']) }}">
                    {{ $category['name'] }}
                </a>
            </li>
            <li>
                <ul>
                    @foreach($category['children'] as $childCategory)
                        <li id="left-sidebar-nav-{{ $childCategory['slug'] }}" class="left-sidebar-nav-child-item">
                            {{-- 子节点 --}}
                            <a href="{{ url('/category/'.$childCategory['slug']) }}">
                                {{ $childCategory['name'] }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </li>
        @endforeach
    </ul>
</div>