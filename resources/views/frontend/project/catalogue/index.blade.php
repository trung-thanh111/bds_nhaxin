@extends('frontend.homepage.layout')

@section('header-class', 'header-inner')
@section('content')
    <div id="scroll-progress"></div>
    <div class="linden-page">
        <!-- Minimalist Header & Category Tags -->
        <section class="hp-detail-header">
            <div class="uk-container uk-container-center">
                <ul class="uk-breadcrumb uk-flex uk-flex-middle">
                    @if (isset($breadcrumb) && is_array($breadcrumb))
                        @foreach ($breadcrumb as $key => $val)
                            @if ($key == count($breadcrumb) - 1)
                                <li class="uk-active"><span>{{ $val['name'] }}</span></li>
                            @else
                                <li><a href="{{ $val['canonical'] }}">{{ $val['name'] }}</a></li>
                            @endif
                        @endforeach
                    @else
                        <li><a href="{{ url('/') }}">Trang chủ</a></li>
                        <li class="uk-active"><span>Dự án</span></li>
                    @endif
                </ul>
            </div>
        </section>
        <!-- Full-width Zalo Promo Section -->
        <div class="hp-full-promo-section uk-margin-bottom">
            <div class="uk-container uk-container-center">
                <div class="hp-promo-inner">
                    <h2 class="hp-promo-title">TÌM KIẾM BẤT ĐỘNG SẢN ƯNG Ý</h2>
                    <p class="hp-promo-desc">
                        Hàng ngàn tin <strong>mua bán, cho thuê</strong> nhà đất và dự án với thông tin xác thực, vị trí đắc
                        địa và pháp lý an toàn.<br>
                        Chuyên trang bất động sản và quy hoạch giúp bạn tìm kiếm cơ hội đầu tư và an cư lý tưởng nhất.
                    </p>
                    <div class="hp-promo-actions">
                        <a href="https://zalo.me/{{ get_hotline_link($agent ?? null, $system['contact_hotline'] ?? '') }}"
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
            <div data-uk-sticky="{offset: 85, media: 960}">
                @include('frontend.component.filter_horizontal_project')
            </div>

            <!-- Main Content (70/30 Layout) -->
            <section class="hp-section bg-white">
                <div class="uk-container uk-container-center">
                    <div class="uk-grid uk-grid-medium" data-uk-grid-margin id="main-listing-grid">
                        <!-- Results (70%) -->
                        <div class="uk-width-large-7-10">
                            <div class="hp-listing-top uk-flex uk-flex-middle uk-flex-space-between uk-margin-large-bottom">
                                <div class="hp-listing-title">
                                    <h1 class="hp-category-name">
                                        {{ isset($projectCatalogue) ? $projectCatalogue->name : 'Tất cả dự án' }}
                                    </h1>
                                    <div class="hp-listing-count">
                                        <i class="fas fa-city uk-margin-small-right"></i>
                                        Hiện có <strong
                                            id="total-records">{{ number_format($projects->total(), 0, ',', '.') }}</strong>
                                        dự án
                                    </div>
                                </div>
                                @php
                                    $currentSort = request('sort') ?: 'id:desc';
                                @endphp
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
                                                        <a href="#" class="ajax-sort"
                                                            data-sort="{{ $key }}">{{ $label }}</a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div id="ajax-listing-container">
                                @include('frontend.component.project_list', ['projects' => $projects])
                            </div>
                        </div>

                        <div class="uk-width-large-3-10">
                            <aside class="hp-sidebar hp-sidebar-sticky">
                                @include('frontend.component.sidebar_filters')

                                @if (isset($widgets['product-category']))
                                    <div class="hp-sidebar-widget">
                                        <h4 class="hp-sidebar-title">Loại hình sản phẩm</h4>
                                        <ul class="hp-sidebar-list">
                                            @foreach ($widgets['product-category']->items as $cat)
                                                <li><a href="{{ url($cat->canonical . '.html') }}">{{ $cat->name }}</a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                            </aside>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    @endsection
