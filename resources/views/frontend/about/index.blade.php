@extends('frontend.homepage.layout')
@section('header-class', 'header-inner')
@section('content')
    <div id="scroll-progress"></div>

    <section class="hp-luxury-header"
        style="background-image: url('{{ $property->image ?? asset('frontend/resources/img/homely/slider/1.webp') }}');">
        <div class="hp-hero-overlay"></div>
        <div class="hp-luxury-header__content">
            <div class="uk-container uk-container-center">
                <div class="hp-luxury-header__title-wrap" data-reveal="up">
                    <h1 class="hp-luxury-header__title">{{ $property->title ?? 'Homepark Residence' }}</h1>
                    <p class="hp-luxury-header__desc hp-hero-subtitle-main">
                        {{ $property->description_short ?? 'Không gian sống sang trọng được thiết kế dành cho cuộc sống hiện đại.' }}
                    </p>
                </div>
                <div class="hp-luxury-breadcrumb" data-reveal="left">
                    <div class="content-breadcrumb">
                        <a href="{{ route('home.index') }}">Trang chủ</a>
                        <span class="separator">»</span>
                        <span class="current">{{ $property->title ?? 'Về dự án' }}</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @php
        $sliderImages = collect();
        if (isset($galleries) && $galleries->count() > 0) {
            foreach ($galleries as $g) {
                if (is_array($g->album)) {
                    foreach ($g->album as $img) {
                        $sliderImages->push($img);
                    }
                }
            }
        }
        $img1 = $sliderImages->get(0) ?? asset('frontend/resources/img/homely/gallery/1.webp');
        $img2 = $sliderImages->get(1) ?? asset('frontend/resources/img/homely/gallery/2.webp');
    @endphp


    <section class="hp-section bg-white hp-section-padding">
        <div class="uk-container uk-container-center">
            <div class="hp-about-intro" data-uk-scrollspy="{cls:'uk-animation-slide-top', delay:300}">
                <span class="hp-section-num">01</span>
                <div class="hp-title-serif">Về dự án</div>
                <h2 class="hp-subtitle-dark">
                    Nơi <span>Nghệ Thuật</span> & <br>Cuộc Sống Hiện Đại Giao Thoa
                </h2>
            </div>

            <div class="hp-magazine-grid">
                <div class="hp-magazine-content" data-uk-scrollspy="{cls:'uk-animation-slide-left', delay:500}">
                    <div class="hp-text-magazine">
                        {!! $property->description ??
                            'Ngôi nhà này được thiết kế tinh tế với tầm nhìn về một không gian sống hoàn hảo. Mỗi chi tiết kiến trúc đều phản ánh sự kết hợp giữa nghệ thuật và công năng hiện đại. Linden Residence không chỉ là nơi để ở, mà là một biểu tượng của phong cách sống thượng lưu, nơi mỗi ngày đều là một trải nghiệm đáng nhớ.' !!}
                    </div>
                    <a href="/lien-he.html" class="hp-btn hp-btn-dark">
                        KHÁM PHÁ CHI TIẾT <i class="fa fa-caret-right" style="margin-left:10px;"></i>
                    </a>
                </div>

                <div class="hp-magazine-visual" data-uk-scrollspy="{cls:'uk-animation-slide-right', delay:700}">
                    <img src="{{ $img1 }}" alt="{{ $property->title }}" class="hp-magazine-img-tall">
                    <img src="{{ $img2 }}" alt="{{ $property->title }} Details" class="hp-magazine-img-overlap">
                </div>
            </div>
        </div>
    </section>
    </section>


    <section class="hp-section hp-bg-light hp-section-padding hp-border-top"
        data-uk-scrollspy="{cls:'uk-animation-slide-bottom', delay:200}">
        <div class="uk-container uk-container-center uk-text-center"
            data-uk-scrollspy="{cls:'uk-animation-fade', delay:300}">
            <span class="hp-section-num">02</span>
            <div class="hp-title-serif">Tiện nghi Linden</div>
            <h2 class="hp-subtitle-dark">Căn hộ hoàn mỹ cho cuộc sống thượng lưu</h2>

            <div class="uk-grid uk-grid-divider uk-margin-large-top" data-uk-grid-margin>
                <div class="uk-width-large-1-5 uk-width-medium-1-2">
                    <i class="fa fa-arrows-alt text-primary hp-stat-icon"></i>
                    <div class="hp-stat-label">Diện tích</div>
                    <div class="hp-stat-value">
                        {{ $property->area_sqm ?? 0 }}<span class="hp-stat-unit">m²</span>
                    </div>
                </div>
                <div class="uk-width-large-1-5 uk-width-medium-1-2">
                    <i class="fa fa-bed text-primary hp-stat-icon"></i>
                    <div class="hp-stat-label">Phòng ngủ</div>
                    <div class="hp-stat-value">
                        {{ $property->bedrooms ?? 0 }}
                    </div>
                </div>
                <div class="uk-width-large-1-5 uk-width-medium-1-2">
                    <i class="fa fa-bath text-primary hp-stat-icon"></i>
                    <div class="hp-stat-label">Phòng tắm</div>
                    <div class="hp-stat-value">
                        {{ $property->bathrooms ?? 0 }}
                    </div>
                </div>
                <div class="uk-width-large-1-5 uk-width-medium-1-2">
                    <i class="fa fa-car text-primary hp-stat-icon"></i>
                    <div class="hp-stat-label">Chỗ đỗ xe</div>
                    <div class="hp-stat-value">
                        {{ $property->parking_spots ?? 0 }}
                    </div>
                </div>
                <div class="uk-width-large-1-5 uk-width-medium-1-2">
                    <i class="fa fa-building text-primary hp-stat-icon"></i>
                    <div class="hp-stat-label">Số tầng</div>
                    <div class="hp-stat-value">
                        {{ $property->floors ?? 0 }}
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="hp-section hp-bg-light hp-section-padding hp-border-top">
        <div class="uk-container uk-container-center">
            <div class="hp-section-header">
                <span class="hp-section-num">03</span>
                <div class="hp-title-serif">Vị trí đắc địa</div>
                <h2 class="hp-subtitle-dark">Mọi thứ bạn cần,<br>ngay bên cạnh</h2>
            </div>

            @php
                $locItems = collect();
                if (isset($locationHighlights) && $locationHighlights->count() > 0) {
                    $locItems = $locationHighlights;
                } else {
                    $defaults = [
                        [
                            'name' => 'Siêu Thị Vinmart',
                            'distance_text' => '15 phút đi bộ',
                            'description' => 'Siêu thị tiện lợi với đa dạng sản phẩm chất lượng cao.',
                        ],
                        [
                            'name' => 'Phúc Long Coffee',
                            'distance_text' => '7 phút đi bộ',
                            'description' => 'Quán cà phê nổi tiếng với đồ uống thủ công và bánh ngọt.',
                        ],
                        [
                            'name' => 'Trường QT Việt Úc',
                            'distance_text' => '7 phút đi bộ',
                            'description' => 'Trường quốc tế K-12 nổi tiếng với chất lượng giảng dạy.',
                        ],
                        [
                            'name' => 'AEON Mall',
                            'distance_text' => '6 phút lái xe',
                            'description' => 'Trung tâm thương mại lớn với đầy đủ dịch vụ giải trí.',
                        ],
                        [
                            'name' => 'Bệnh Viện FV',
                            'distance_text' => '10 phút lái xe',
                            'description' => 'Bệnh viện quốc tế với đội ngũ bác sĩ giàu kinh nghiệm.',
                        ],
                        [
                            'name' => 'Metro Line 1',
                            'distance_text' => '10 phút đi bộ',
                            'description' => 'Tuyến metro đầu tiên kết nối Quận 1 - Quận 9.',
                        ],
                    ];
                    $locItems = collect($defaults)->map(fn($d) => (object) $d);
                }
            @endphp

            <div class="uk-grid uk-grid-medium" data-uk-grid-margin data-uk-grid-match="{target:'.hp-neighbour-card'}">
                @foreach ($locItems as $item)
                    <div class="uk-width-large-1-3 uk-width-medium-1-2">
                        <div class="hp-neighbour-card" data-reveal="up">
                            <div class="hp-neighbour-card__img">
                                <img src="{{ asset('frontend/resources/img/homely/slider/1.webp') }}"
                                    alt="{{ $item->name ?? '' }}">
                            </div>
                            <div class="hp-neighbour-card__body">
                                <div class="hp-neighbour-card__title">{{ $item->name ?? '' }}</div>
                                <p class="hp-neighbour-card__desc">{{ $item->description ?? '' }}</p>
                                <div class="hp-neighbour-card__time">
                                    <i class="fa fa-clock-o"></i> {{ $item->distance_text ?? '' }}
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>


    <section class="hp-section bg-white hp-section-padding">
        <div class="uk-container uk-container-center">
            <div class="uk-grid uk-grid-large" data-uk-scrollspy="{cls:'uk-animation-fade', delay:300}">
                <div class="uk-width-large-2-5">
                    <span class="hp-section-num">04</span>
                    <div class="hp-title-serif">Thư viện hình ảnh</div>
                    <h2 class="hp-subtitle-dark">Kiến trúc sang trọng & tinh tế</h2>
                    <p class="hp-text-desc uk-margin-large-bottom">
                        Từng góc nhỏ trong căn hộ đều được chăm chút kỹ lưỡng, mang lại cảm giác ấm cúng nhưng không kém
                        phần sang trọng.
                    </p>
                    <a href="{{ route('gallery.index') }}" class="hp-link-more">
                        Xem tất cả ảnh <i class="fa fa-caret-right"></i>
                    </a>
                </div>

                <div class="uk-width-large-3-5">
                    <div class="hp-gallery-grid">
                        @foreach ($sliderImages->take(4) as $img)
                            <a href="{{ $img }}" class="hp-gallery-item" data-fancybox="hp-gallery">
                                <img src="{{ $img }}" alt="Gallery Image">
                                <div class="hp-gallery-overlay">
                                    <i class="fa fa-expand"></i>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>


    <section class="hp-section hp-bg-light hp-section-padding">
        <div class="uk-container uk-container-center">
            <div class="uk-grid uk-grid-large" data-uk-grid-margin>
                <div class="uk-width-large-1-2">
                    <div class="hp-content-box hp-content-box-floorplan"
                        data-uk-scrollspy="{cls:'uk-animation-slide-left', delay:300}" style="padding-left: 0;">
                        <span class="hp-section-num">05</span>
                        <div class="hp-title-serif">Sơ đồ mặt bằng</div>
                        <h2 class="hp-subtitle-dark">Thiết kế thông minh</h2>
                        <p class="uk-margin-medium-bottom hp-text-desc">
                            Mỗi căn hộ được tối ưu hóa diện tích, đảm bảo sự thông thoáng và tận dụng tối đa ánh sáng tự
                            nhiên cho mọi không gian sống.
                        </p>

                        <ul class="hp-floorplan-stats">
                            <li class="hp-floorplan-stat-item">
                                <span class="hp-floorplan-stat-label">Dòng căn hộ:</span>
                                <span class="hp-floorplan-stat-value">{{ $property->title ?? 'Luxury Elite' }}</span>
                            </li>
                            <li class="hp-floorplan-stat-item">
                                <span class="hp-floorplan-stat-label">Số phòng ngủ:</span>
                                <span class="hp-floorplan-stat-value">{{ $property->bedrooms ?? '0' }} phòng</span>
                            </li>
                            <li class="hp-floorplan-stat-item">
                                <span class="hp-floorplan-stat-label">Số phòng tắm:</span>
                                <span class="hp-floorplan-stat-value">{{ $property->bathrooms ?? '0' }} phòng</span>
                            </li>
                            <li class="hp-floorplan-stat-item">
                                <span class="hp-floorplan-stat-label">Hướng nhà:</span>
                                <span class="hp-floorplan-stat-value">Đông Nam</span>
                            </li>
                            <li class="hp-floorplan-stat-item last-child-no-border">
                                <span class="hp-floorplan-stat-label">Trạng thái:</span>
                                <span class="hp-floorplan-stat-value">Sẵn sàng bàn giao</span>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="uk-width-large-1-2" data-uk-scrollspy="{cls:'uk-animation-slide-right', delay:500}">
                    <div class="hp-floorplan-tabs">
                        @if (isset($floorplans) && $floorplans->isNotEmpty())
                            <ul class="uk-tab" data-uk-tab="{connect:'#floorplan-switcher'}"
                                style="margin-bottom: 20px;">
                                @foreach ($floorplans as $plan)
                                    <li class="{{ $loop->first ? 'uk-active' : '' }}">
                                        <a href="">{{ $plan->floor_label }}</a>
                                    </li>
                                @endforeach
                            </ul>
                            <ul id="floorplan-switcher" class="uk-switcher">
                                @foreach ($floorplans as $plan)
                                    <li>
                                        <a href="{{ $plan->plan_image }}" data-fancybox="floorplans"
                                            class="hp-floorplan-img-wrap"
                                            style="display: block; background: #fff; padding: 20px; border: 1px solid #eee;">
                                            <img src="{{ $plan->plan_image }}" alt="{{ $plan->floor_label }}"
                                                style="width: 100%;">
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <div class="hp-floorplan-img-wrap"
                                style="background: #fff; padding: 20px; border: 1px solid #eee;">
                                <img src="{{ asset('frontend/resources/img/homely/misc/floorplan.webp') }}"
                                    alt="Sơ đồ tầng" style="width: 100%;">
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>


    <section class="hp-section uk-position-relative hp-p-0" style="height: 500px;">
        <div class="uk-cover-background uk-position-cover"
            style="background-image: url('{{ $property->image ?? asset('frontend/resources/img/homely/slider/1.webp') }}');">
        </div>
        <div class="uk-position-relative uk-flex uk-flex-middle uk-flex-center uk-height-1-1 uk-text-center"
            data-uk-scrollspy="{cls:'uk-animation-scale-up', delay:300}">
            <div class="hp-z-10">
                <span class="hp-title-serif hp-text-white">Trải Nghiệm Thực TẾ</span>
                <h2 class="hp-subtitle-dark hp-text-white">Góc Nhìn Nghệ Thuật</h2>

                <div class="hp-btn-play-wrap">
                    <a href="{{ $property->video_tour_url ?? 'https://www.youtube.com/watch?v=dQw4w9WgXcQ' }}"
                        data-fancybox class="hp-btn-play-pulse">
                        <i class="fa fa-play"></i>
                    </a>
                </div>
            </div>
        </div>
    </section>


    <section class="hp-cta-section hp-section-padding hp-border-top">
        <div class="uk-container uk-container-center">
            <div class="hp-cta-row">

                <div class="hp-cta-box" data-uk-scrollspy="{cls:'uk-animation-slide-left', delay:300}">
                    <span class="hp-section-num">06</span>
                    <div class="hp-title-serif">Liên hệ</div>
                    <h2 class="hp-subtitle-dark">Quan tâm đến {{ $property->title ?? 'Homepark' }}?</h2>
                    <p class="hp-cta-desc">
                        Hệ thống tiện ích hiện đại cùng không gian sống xanh tại Linden Residence hứa hẹn mang đến một tổ ấm
                        lý tưởng.
                    </p>
                    <a href="/lien-he.html" class="hp-btn hp-btn-dark">
                        ĐẶT LỊCH THĂM QUAN <i class="fa fa-caret-right" style="margin-left:10px;"></i>
                    </a>
                </div>


                <div class="hp-cta-img-wrap" data-uk-scrollspy="{cls:'uk-animation-slide-right', delay:500}">
                    <div class="hp-badge-accent hp-badge-yellow">Sang trọng</div>
                    <img src="{{ $img1 }}" alt="Interested in Homepark">
                </div>
            </div>
        </div>
    </section>

@endsection
