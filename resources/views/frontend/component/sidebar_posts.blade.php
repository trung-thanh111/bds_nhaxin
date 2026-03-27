<style>
    .hp-mini-item .info .title {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
        font-weight: 500;
        margin-bottom: 5px;
        line-height: 1.4;
        font-size: 13px;
    }

    .hp-mini-item .info .meta {
        color: var(--main-color);
        font-weight: 600;
        font-size: 13px;
    }

    .hp-sidebar-list li a {
        display: flex !important;
        justify-content: space-between !important;
        align-items: center !important;
        width: 100%;
        text-decoration: none;
    }

    .hp-sidebar-list li a::after {
        content: '\f105';
        font-family: FontAwesome;
        color: #ccc;
        font-size: 14px;
    }
</style>

<div class="hp-sidebar-posts">
    <div class="hp-sidebar-widget uk-margin-remove">
        <h4 class="hp-sidebar-title">Tìm kiếm</h4>
        @php
            $searchAction = isset($postCatalogue) ? url($postCatalogue->canonical . '.html') : url('bai-viet.html');
        @endphp
        <form action="{{ $searchAction }}" method="GET" class="hp-sidebar-search">
            <div class="hp-search-container">
                <i class="fa fa-search"></i>
                <input type="text" name="keyword" placeholder="Nhập từ khóa..." value="{{ request('keyword') }}"
                    class="uk-width-1-1">
            </div>
        </form>
    </div>

    @if (isset($categories) && count($categories))
        <div class="hp-sidebar-widget">
            <h4 class="hp-sidebar-title">Danh mục tin tức</h4>
            <ul class="hp-sidebar-list">
                <li class="{{ !isset($postCatalogue) ? 'active' : '' }}">
                    <a href="{{ url('bai-viet.html') }}">Tất cả bài viết</a>
                </li>
                @foreach ($categories as $cat)
                    @php
                        $catName = $cat->languages->first()->pivot->name ?? ($cat->name ?? 'Untitled');
                        $catCanonical = $cat->languages->first()->pivot->canonical ?? ($cat->canonical ?? '#');
                    @endphp
                    <li class="{{ isset($postCatalogue) && $postCatalogue->id == $cat->id ? 'active' : '' }}">
                        <a href="{{ url(rtrim($catCanonical, '/') . '.html') }}">
                            {{ $catName }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif
</div>
