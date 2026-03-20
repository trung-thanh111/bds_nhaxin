@extends('frontend.homepage.layout')
@section('header-class', 'header-inner')
@section('content')
    <div id="scroll-progress"></div>
    <div class="linden-page">

        <section class="hp-luxury-header"
            style="background-image: url('{{ $property->image ?? asset('frontend/resources/img/homely/slider/1.webp') }}');">
            <div class="hp-hero-overlay"></div>
            <div class="hp-luxury-header__content">
                <div class="uk-container uk-container-center">
                    <div class="hp-luxury-header__title-wrap" data-reveal="up">
                        <h1 class="hp-luxury-header__title">Tiện ích cao cấp</h1>
                        <p class="hp-luxury-header__desc hp-hero-subtitle-main">
                            Trải nghiệm hệ thống tiện ích đặc quyền kiến tạo phong cách sống thượng lưu.
                        </p>
                    </div>
                    <div class="hp-luxury-breadcrumb" data-reveal="left">
                        <div class="content-breadcrumb">
                            <a href="{{ route('home.index') }}">Trang chủ</a>
                            <span class="separator">»</span>
                            <span class="current">Tiện nghi</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="hp-section bg-white hp-section-padding hp-border-top">
            <div class="uk-container uk-container-center">
                <div class="hp-section-header">
                    <span class="hp-section-num">01</span>
                    <div class="hp-title-serif">Nghệ thuật sống</div>
                    <h2 class="hp-subtitle-dark">Đặc quyền cư dân,<br>tinh tế từng chi tiết</h2>
                    <p class="hp-text-desc uk-margin-large-bottom" style="max-width: 600px; margin: 0 auto;">
                        Mỗi tiện ích tại {{ $property->title ?? 'Homepark' }} đều được chọn lọc và thiết kế kỹ lưỡng nhằm
                        nâng cao chất lượng cuộc sống hàng ngày của bạn.
                    </p>
                </div>

                @php
                    $defaultFeatures = [
                        [
                            'name' => 'Vị Trí Trung Tâm',
                            'description' =>
                                'Tất cả những gì bạn cần đều ở ngay cạnh — vị trí trung tâm với đầy đủ tiện ích hạ tầng.',
                            'icon' => 'fa-map-marker',
                        ],
                        [
                            'name' => 'Thiết Kế Đạt Giải',
                            'description' =>
                                'Căn hộ được thiết kế bởi kiến trúc sư hàng đầu với sự chú ý đến từng chi tiết nhỏ nhất.',
                            'icon' => 'fa-trophy',
                        ],
                        [
                            'name' => 'Tầm Nhìn Tuyệt Đẹp',
                            'description' =>
                                'Căn hộ sáng sủa và rộng rãi với tầm nhìn ấn tượng ra hướng sông thành phố.',
                            'icon' => 'fa-sun-o',
                        ],
                        [
                            'name' => 'Nhà Thông Minh',
                            'description' => 'Công nghệ nhà thông minh cho phép điều khiển mọi thiết bị từ xa dễ dàng.',
                            'icon' => 'fa-wifi',
                        ],
                        [
                            'name' => 'Năng Lượng Xanh',
                            'description' =>
                                'Hệ thống pin năng lượng mặt trời giảm chi phí sinh hoạt hàng tháng hiệu quả.',
                            'icon' => 'fa-leaf',
                        ],
                        [
                            'name' => 'Hồ Bơi Riêng',
                            'description' =>
                                'Hồ bơi riêng thiết kế phong cách resort, bao quanh bởi sân vườn xanh mát.',
                            'icon' => 'fa-tint',
                        ],
                        [
                            'name' => 'An Ninh 24/7',
                            'description' =>
                                'Hệ thống an ninh thông minh với camera HD và khóa vân tay hoạt động liên tục.',
                            'icon' => 'fa-shield',
                        ],
                        [
                            'name' => 'Sân Vườn Xanh',
                            'description' =>
                                'Sân vườn thoáng đãng với cây xanh phủ bóng, tạo không gian sống gần gũi thiên nhiên.',
                            'icon' => 'fa-tree',
                        ],
                    ];
                    $displayFeatures =
                        $facilities->count() > 0 ? $facilities : collect($defaultFeatures)->map(fn($f) => (object) $f);
                @endphp

                <div class="uk-grid uk-grid-medium" data-uk-grid-margin data-uk-grid-match="{target:'.hp-amenity-card'}">
                    @foreach ($displayFeatures as $feature)
                        <div class="uk-width-large-1-4 uk-width-medium-1-2">
                            <div class="hp-amenity-card" data-reveal="up">
                                <div class="hp-amenity-card__icon">
                                    @php
                                        $iconClass =
                                            isset($feature->icon) && !empty($feature->icon)
                                                ? $feature->icon
                                                : 'fa-check-circle-o';
                                        if (strpos($iconClass, 'fa ') === false && strpos($iconClass, 'fa-') === 0) {
                                            $iconClass = 'fa ' . $iconClass;
                                        }
                                    @endphp
                                    <i class="{{ $iconClass }}"></i>
                                </div>
                                <div class="hp-amenity-card__body">
                                    <h3 class="hp-amenity-card__title">{{ $feature->name }}</h3>
                                    <p class="hp-amenity-card__desc">{{ $feature->description ?? ($feature->desc ?? '') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    </div>
@endsection
