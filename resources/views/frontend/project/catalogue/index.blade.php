@extends('frontend.homepage.layout')

@section('header-class', 'header-inner')
@section('content')
    <div id="scroll-progress"></div>
    <div class="linden-page">
        <!-- Minimalist Header & Category Tags -->
        <section class="hp-detail-header">
            <div class="uk-container uk-container-center">
                <ul class="uk-breadcrumb uk-flex uk-flex-middle">
                    <li><a href="{{ url('/') }}">Trang chủ</a></li>
                    @if ($project->catalogue)
                        @php
                            $canonicalCatalogue = url(
                                $project->catalogue->languages->first()->pivot->canonical . '.html',
                            );
                        @endphp
                        <li><a
                                href="{{ $canonicalCatalogue }}">{{ $project->catalogue->languages->first()->pivot->name }}</a>
                        </li>
                    @endif
                    <li class="uk-active"><span>{{ $project->name }}</span></li>
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

            <!-- Main Content (70/30 Layout) -->
            <section class="hp-section bg-white">
                <div class="uk-container uk-container-center">
                    <div class="uk-grid uk-grid-large" data-uk-grid-margin>
                        <!-- Results (70%) -->
                        <div class="uk-width-large-7-10">
                            <div class="hp-listing-top uk-flex uk-flex-middle uk-flex-space-between uk-margin-large-bottom">
                                <div class="hp-listing-sort uk-flex uk-flex-middle">
                                    <span class="uk-margin-medium-right">Sắp xếp:</span>
                                    <div class="hp-custom-dropdown" data-uk-dropdown="{mode:'click'}">
                                        <button class="hp-dropdown-btn">
                                            <span>Mặc định</span> <i class="fa fa-chevron-down"></i>
                                        </button>
                                        <div class="uk-dropdown uk-dropdown-bottom uk-dropdown-small">
                                            <ul class="uk-nav uk-nav-dropdown hp-sort-list">
                                                <li><a href="#" data-sort="id:desc">Mặc định</a></li>
                                                <li><a href="#" data-sort="area:desc">Quy mô lớn đến nhỏ</a></li>
                                                <li><a href="#" data-sort="apartment_count:desc">Nhiều căn hộ nhất</a>
                                                </li>
                                                <li><a href="#" data-sort="created_at:desc">Mới nhất</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @if ($projects->count() > 0)
                                <div class="hp-listing-results">
                                    @foreach ($projects as $item)
                                        @include('frontend.component.project_card_horizontal', [
                                            'item' => $item,
                                        ])
                                    @endforeach
                                </div>

                                @if ($projects->hasPages())
                                    <div class="uk-margin-large-top">
                                        {{ $projects->links('frontend.component.pagination') }}
                                    </div>
                                @endif
                            @else
                                <div class="hp-empty-state uk-text-center">
                                    <img src="{{ asset('frontend/resources/img/empty-box.png') }}" alt="Empty"
                                        style="max-width: 150px; opacity: 0.3;">
                                    <p class="uk-text-muted uk-margin-top">Không tìm thấy dự án nào trong danh mục này.</p>
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
                                    <h4 class="hp-sidebar-title">Dự án theo khu vực</h4>
                                    <div class="hp-tag-cloud">
                                        <a href="#">Dự án Quận 1</a>
                                        <a href="#">Dự án Quận 2</a>
                                        <a href="#">Dự án Quận 7</a>
                                        <a href="#">Dự án Thủ Đức</a>
                                        <a href="#">Dự án Bình Thạnh</a>
                                        <a href="#">Dự án Phú Mỹ Hưng</a>
                                    </div>
                                </div>

                                <!-- Widgets -->
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
