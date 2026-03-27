@php
    $hotline = preg_replace('/\D/', '', $system['contact_hotline'] ?? '0903030303');
    $menuMain = $menu['main-menu_array'] ?? [];
    $menuMapping = [
        'Mua bán' => ['icon' => 'fa-home', 'color' => 'var(--main-color)'],
        'Cho thuê' => ['icon' => 'fa-key', 'color' => '#2e7d32'],
        'Dự án' => ['icon' => 'fa-building', 'color' => '#ef6c00'],
        'Liên hệ' => ['icon' => 'fa-edit', 'color' => '#7b1fa2'],
    ];
    $defaultMapping = ['icon' => 'fa-folder-open', 'color' => '#666'];
@endphp

<div id="offcanvas-desktop" class="uk-offcanvas" uk-offcanvas="overlay: true">
    <div class="uk-offcanvas-bar hp-offcanvas-bar uk-offcanvas-bar-flip">
        <a class="uk-offcanvas-close hp-offcanvas-close">
            <i class="fa fa-times"></i>
        </a>

        <div class="gl-offcanvas-header">
            <a href="/">
                <img src="{{ $system['homepage_logo'] ?? asset('frontend/resources/img/homely/logo.webp') }}"
                    alt="Logo" style="max-height: 50px;" />
            </a>
            <div
                style="font-size: 11px; margin-top: 5px; color: var(--primary-color); font-weight: 700; text-transform: uppercase;">
                {{ $system['homepage_company'] ?? 'THÔNG TIN THẬT - GIÁ TRỊ THẬT' }}
            </div>
        </div>

        <div class="gl-offcanvas-menu">
            @foreach ($menuMain as $val)
                @php
                    $name = $val['item']->languages->first()->pivot->name;
                    $canonical = write_url($val['item']->languages->first()->pivot->canonical, true, true);
                    $style = $menuMapping[$name] ?? $defaultMapping;
                @endphp
                <a href="{{ $canonical }}" class="gl-offcanvas-link">
                    <i class="fa {{ $style['icon'] }}" style="color: {{ $style['color'] }};"></i>
                    <span>{{ $name }}</span>
                </a>
            @endforeach
        </div>

        <div class="gl-offcanvas-info-box">
            <div class="gl-info-title">TÌM KIẾM BẤT ĐỘNG SẢN ƯNG Ý</div>
            <div class="gl-info-content">
                Hàng ngàn tin <strong>mua bán, cho thuê</strong> nhà đất và dự án với thông tin xác thực, vị trí
                đắc địa và pháp lý an toàn.<br>
                Chuyên trang bất động sản và quy hoạch giúp bạn tìm kiếm cơ hội đầu tư và
                an cư lý tưởng nhất.
            </div>
            <a href="https://zalo.me/{{ $hotline }}" class="gl-zalo-btn" target="_blank">
                <img src="{{ asset('frontend/resources/img/icon_zalo_white.png') }}" alt="Zalo"
                    style="height: 20px; margin-right: 10px;"
                    onerror="this.src='https://upload.wikimedia.org/wikipedia/commons/9/91/Icon_of_Zalo.svg'">
                TƯ VẤN NGAY
            </a>
        </div>

        <div class="gl-footer-text">
            Nhiều dự án quy hoạch, pháp lý an toàn, giá tốt đang chờ bạn.
        </div>

        <div style="text-align: center; padding: 10px; opacity: 0.5; font-size: 10px;">
            {{ $system['copyright'] ?? 'Copyright © 2024 Guland' }}
        </div>
    </div>
</div>

<div id="offcanvas-mobile" class="uk-offcanvas" uk-offcanvas="overlay: true">
    <div class="uk-offcanvas-bar hp-offcanvas-bar">
        <a class="uk-offcanvas-close hp-offcanvas-close">
            <i class="fa fa-times"></i>
        </a>
        <div class="gl-offcanvas-header">
            <a href="/">
                <img src="{{ $system['homepage_logo'] ?? asset('frontend/resources/img/homely/logo.webp') }}"
                    alt="Logo" style="max-height: 40px;" />
            </a>
        </div>
        <nav class="hp-offcanvas-nav uk-margin-large-bottom">
            <ul class="uk-nav uk-nav-offcanvas" data-uk-nav>
                @foreach ($menuMain as $val)
                    @php
                        $name = $val['item']->languages->first()->pivot->name;
                        $canonical = write_url($val['item']->languages->first()->pivot->canonical, true, true);
                        $style = $menuMapping[$name] ?? $defaultMapping;
                    @endphp
                    <li>
                        <a href="{{ $canonical }}">
                            <i class="fa {{ $style['icon'] }} uk-margin-small-right"
                                style="color: {{ $style['color'] }}; width: 20px;"></i>
                            {{ $name }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </nav>
    </div>
</div>
