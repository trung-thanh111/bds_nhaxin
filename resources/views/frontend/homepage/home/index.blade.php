@extends('frontend.homepage.layout')
@section('content')
    @php
        $menuMain = $menu['main-menu_array'] ?? [];
        $menuMapping = [
            'Mua bán' => ['icon' => 'fa-home', 'bg' => '#fff8e1', 'color' => '#ff8f00'],
            'Cho thuê' => ['icon' => 'fa-key', 'bg' => '#e8f5e9', 'color' => '#2e7d32'],
            'Dự án' => ['icon' => 'fa-building', 'bg' => '#fff3e0', 'color' => '#ef6c00'],
            'Liên hệ' => ['icon' => 'fa-edit', 'bg' => '#f3e5f5', 'color' => '#7b1fa2'],
        ];
        $defaultMapping = ['icon' => 'fa-folder-open', 'bg' => '#f5f5f5', 'color' => '#666'];
    @endphp
    <div class="gl-section">
        <div class="uk-container uk-container-center">
            <div class="gl-category-grid">
                @foreach ($menuMain as $val)
                    @php
                        $name = $val['item']->languages->first()->pivot->name;
                        $canonical = write_url($val['item']->languages->first()->pivot->canonical, true, true);
                        $style = $menuMapping[$name] ?? $defaultMapping;
                    @endphp
                    <a href="{{ $canonical }}" class="gl-cat-item">
                        <div class="gl-cat-icon" style="background: {{ $style['bg'] }}; color: {{ $style['color'] }};">
                            <i class="fa {{ $style['icon'] }}"></i>
                        </div>
                        <div class="gl-cat-label">{{ $name }}</div>
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    <div class="gl-promo-section">
        <div class="uk-container uk-container-center">
            <div class="gl-promo-content">
                <h2 class="gl-promo-title">TÌM KIẾM BẤT ĐỘNG SẢN ƯNG Ý</h2>
                <p class="gl-promo-desc">
                    Hàng ngàn tin <strong>mua bán, cho thuê</strong> nhà đất và dự án với thông tin xác thực, vị trí
                    đắc địa và pháp lý an toàn.<br>
                    Chuyên trang bất động sản và quy hoạch giúp bạn tìm kiếm cơ hội đầu tư và
                    an cư lý tưởng nhất.
                </p>
                <div class="gl-promo-actions">
                    <a href="https://zalo.me/{{ get_hotline_link($agent ?? null, $system['contact_hotline'] ?? '') }}"
                        class="gl-btn-zalo" target="_blank">
                        <img src="{{ asset('frontend/resources/img/icon_zalo_white.png') }}" alt="Zalo"
                            onerror="this.src='https://upload.wikimedia.org/wikipedia/commons/9/91/Icon_of_Zalo.svg'">
                        TƯ VẤN NGAY
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="gl-section">
        <div class="uk-container uk-container-center">
            @if (isset($homepageCatalogues) && count($homepageCatalogues))
                @foreach ($homepageCatalogues as $catalogue)
                    @if (isset($catalogue->real_estates) && count($catalogue->real_estates))
                        <div class="gl-section">
                            <div class="gl-section-header">
                                <h2 class="gl-section-title">{{ $catalogue->languages->first()->pivot->name }}</h2>
                                <a href="{{ url($catalogue->languages->first()->pivot->canonical . '.html') }}"
                                    class="gl-view-more">Xem tất cả <i class="fa fa-arrow-right"></i></a>
                            </div>
                            <div class="uk-grid uk-grid-medium" data-uk-grid-margin>
                                @foreach ($catalogue->real_estates as $item)
                                    <div class="uk-width-large-1-3 uk-width-medium-1-2 mb20">
                                        @include('frontend.component.real_estate_card', [
                                            'item' => $item,
                                            'attributeMap' => $attributeMap,
                                        ])
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                @endforeach
            @endif


            @if (isset($projects) && count($projects))
                <div class="gl-section">
                    <div class="gl-section-header">
                        <h2 class="gl-section-title">Dự án mới nhất</h2>
                        <a href="/du-an.html" class="gl-view-more">Xem tất cả <i class="fa fa-arrow-right"></i></a>
                    </div>
                    <div class="uk-grid uk-grid-medium" data-uk-grid-margin>
                        @foreach ($projects as $item)
                            <div class="uk-width-large-1-3 uk-width-medium-1-2 mb20">
                                @include('frontend.component.project_card', ['item' => $item])
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="gl-section">
                <h2 class="gl-section-title">Tin tức & Kinh nghiệm</h2>
                <div class="uk-grid uk-grid-medium" data-uk-grid-margin>
                    @if (isset($posts) && count($posts) > 0)
                        @foreach ($posts as $post)
                            <div class="uk-width-large-1-3 uk-width-medium-1-2 mb20">
                                @include('frontend.component.post_card', ['post' => $post])
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
