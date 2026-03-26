@extends('frontend.homepage.layout')

@php
    $currentCatalogue =
        $realEstateCatalogue ?? ($attributeCatalogue ?? ($amenityCatalogue ?? ($attribute ?? $amenity)));
    $realEstates = $realEstates ?? new \Illuminate\Pagination\LengthAwarePaginator([], 0, 8);
    $attributeMap = $attributeMap ?? [];
@endphp

@section('header-class', 'header-inner')
@section('content')
    <div id="scroll-progress"></div>
    <section class="hp-detail-header">
        <div class="uk-container uk-container-center">
            <ul class="uk-breadcrumb uk-flex uk-flex-middle">
                <li><a href="{{ url('/') }}">Trang chủ</a></li>
                <li class="uk-active"><span>{{ $currentCatalogue->name }}</span></li>
            </ul>
        </div>
    </section>

    <div class="hp-full-promo-section uk-margin-bottom">
        <div class="uk-container uk-container-center">
            <div class="hp-promo-inner">
                <h2 class="hp-promo-title">TÌM KIẾM BẤT ĐỘNG SẢN ƯNG Ý</h2>
                <p class="hp-promo-desc">
                    Hàng ngàn tin <strong>mua bán, cho thuê</strong> nhà đất và dự án với thông tin xác thực, vị trí đắc địa
                    và pháp lý an toàn.<br>
                    Chuyên trang bất động sản và quy hoạch giúp bạn tìm kiếm cơ hội đầu tư và an cư lý tưởng nhất.
                </p>
                <div class="hp-promo-actions">
                    <a href="https://zalo.me/{{ preg_replace('/\D/', '', $system['contact_hotline'] ?? '0983284379') }}"
                        class="hp-btn-zalo-blue" target="_blank">
                        <img src="{{ asset('frontend/resources/img/icon_zalo_white.png') }}" alt="Zalo"
                            onerror="this.src='https://upload.wikimedia.org/wikipedia/commons/9/91/Icon_of_Zalo.svg'">
                        TƯ VẤN NGAY
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="uk-container uk-container-center">
        <div class="linden-listing-content">
        </div>
        </section>

        <!-- Horizontal Filter System -->
        @include('frontend.component.filter_horizontal')

        <section class="hp-section bg-white">
            <div class="uk-container uk-container-center">
                <div class="uk-grid uk-grid-large" data-uk-grid-margin>
                    <div class="uk-width-large-7-10">
                        <div class="hp-listing-top uk-flex uk-flex-middle uk-flex-space-between uk-margin-large-bottom">
                            @php
                                $sorts = [
                                    'id:desc' => 'Mặc định',
                                    'verified:desc' => 'Tin xác thực xếp trước',
                                    'price_sale:asc' => 'Giá thấp đến cao',
                                    'price_sale:desc' => 'Giá cao đến thấp',
                                    'area:asc' => 'Diện tích nhỏ đến lớn',
                                    'area:desc' => 'Diện tích lớn đến nhỏ',
                                ];
                                $currentSort = request('sort') ?: 'id:desc';
                            @endphp
                            <div class="hp-listing-title">
                                <h1 class="hp-category-name">{{ $currentCatalogue->name }}</h1>
                                <div class="hp-listing-count">
                                    <i class="fas fa-home uk-margin-small-right"></i>
                                    Hiện có <strong>{{ number_format($realEstates->total(), 0, ',', '.') }}</strong> bất
                                    động sản
                                </div>
                            </div>

                            <div class="hp-listing-sort uk-flex uk-flex-middle">
                                <div class="hp-sort-dropdown" data-uk-dropdown="{mode:'click', pos:'bottom-right'}">
                                    <button class="hp-sort-btn uk-flex uk-flex-middle">
                                        <span id="sort-label">{{ $sorts[$currentSort] ?? 'Mặc định' }}</span>
                                        <i class="fas fa-chevron-down uk-margin-small-left"></i>
                                    </button>
                                    <div class="uk-dropdown uk-dropdown-small">
                                        <ul class="hp-sort-list">
                                            @foreach ($sorts as $key => $label)
                                                <li class="{{ $currentSort == $key ? 'uk-active' : '' }}">
                                                    <a href="#"
                                                        data-sort="{{ $key }}">{{ $label }}</a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if ($realEstates->count() > 0)
                            <div class="hp-listing-results">
                                @foreach ($realEstates as $item)
                                    @include('frontend.component.real_estate_card_horizontal', [
                                        'item' => $item,
                                        'attributeMap' => $attributeMap,
                                    ])
                                @endforeach
                            </div>

                            @if ($realEstates->hasPages())
                                <div class="uk-margin-large-top">
                                    {{ $realEstates->links('frontend.component.pagination') }}
                                </div>
                            @endif
                        @else
                            <div class="hp-empty-state uk-text-center">
                                <img src="{{ asset('frontend/resources/img/empty-box.png') }}" alt="Empty"
                                    style="max-width: 150px; opacity: 0.3;">
                                <p class="uk-text-muted uk-margin-top">Không tìm thấy bất động sản nào trong danh mục này.
                                </p>
                                <a href="{{ request()->url() }}" class="uk-button uk-button-link">Xóa tất cả bộ lọc</a>
                            </div>
                        @endif
                    </div>

                    <!-- Sidebar (30%) -->
                    <div class="uk-width-large-3-10">
                        <aside class="hp-sidebar">
                            <!-- Agent Card -->
                            <!-- Sidebar Filters -->
                            @include('frontend.component.sidebar_filters')

                            <!-- Related Services/Tags -->
                            <div class="hp-sidebar-widget">
                                <h4 class="hp-sidebar-title">Tìm kiếm phổ biến</h4>
                                <div class="hp-tag-cloud">
                                    <a href="#">Nhà quận 1</a>
                                    <a href="#">Căn hộ giá rẻ</a>
                                    <a href="#">Biệt thự ven hồ</a>
                                    <a href="#">Dự án mới 2024</a>
                                    <a href="#">Shophouse</a>
                                    <a href="#">Penhouse</a>
                                </div>
                            </div>

                            <!-- Widgets -->
                            @if (isset($widgets['featured-products']))
                                <div class="hp-sidebar-widget">
                                    <h4 class="hp-sidebar-title">Dự án tiêu biểu</h4>
                                    <div class="hp-sidebar-projects">
                                        @foreach ($widgets['featured-products']->items as $p)
                                            <a href="{{ url($p->canonical . '.html') }}" class="hp-mini-item uk-flex">
                                                <div class="img">
                                                    <img src="{{ image($p->image) }}" alt="{{ $p->name }}">
                                                </div>
                                                <div class="info">
                                                    <h5 class="title">{{ $p->name }}</h5>
                                                    <div class="meta">{{ $p->area }} m² - {{ $p->status }}</div>
                                                </div>
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </aside>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
