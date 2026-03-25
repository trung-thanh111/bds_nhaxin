@php
    $hotline = preg_replace('/\D/', '', $system['contact_hotline'] ?? '0903030303');
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
            <a href="/mua-ban.html" class="gl-offcanvas-link">
                <i class="fa fa-home" style="color: var(--main-color);"></i>
                <span>Mua bán</span>
            </a>
            <a href="/cho-thue.html" class="gl-offcanvas-link">
                <i class="fa fa-key" style="color: #2e7d32;"></i>
                <span>Cho thuê</span>
            </a>
            <a href="/du-an.html" class="gl-offcanvas-link">
                <i class="fa fa-building" style="color: #ef6c00;"></i>
                <span>Dự án</span>
            </a>
            <a href="/lien-he.html" class="gl-offcanvas-link">
                <i class="fa fa-edit" style="color: #7b1fa2;"></i>
                <span>Liên hệ</span>
            </a>
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
            Cho vay tài chính cầm cố nhà đất, nguồn tiền sẵn giải ngân
        </div>

        <div style="text-align: center; padding: 10px; opacity: 0.5; font-size: 10px;">
            {{ $system['copyright'] ?? 'Copyright © 2024 HomePark' }}
        </div>
    </div>
</div>

{{-- Mobile Menu --}}
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
                {!! $menu['main-menu'] ?? '' !!}
            </ul>
        </nav>
    </div>
</div>
