@extends('frontend.homepage.layout')
@section('content')
    @php
        $allImages = collect();
        if (isset($galleries) && $galleries->count() > 0) {
            foreach ($galleries as $gallery) {
                if (is_array($gallery->album)) {
                    foreach ($gallery->album as $img) {
                        $allImages->push(['url' => $img, 'name' => $gallery->name ?? 'Không gian sống']);
                    }
                }
            }
        }
    @endphp
    <section class="hp-hero">
        <div class="hp-vertical-label hp-label-left">Giá bán {{ $property->price }} {{ $property->price_unit }}</div>
        <div class="hp-social-vertical">
            @if (!empty($system['social_facebook']))
                <a href="{{ $system['social_facebook'] }}" target="_blank"><i class="fa fa-facebook"></i></a>
            @endif
            @if (!empty($system['social_instagram']))
                <a href="{{ $system['social_instagram'] }}" target="_blank"><i class="fa fa-instagram"></i></a>
            @endif
            @if (!empty($system['social_youtube']))
                <a href="{{ $system['social_youtube'] }}" target="_blank"><i class="fa fa-youtube-play"></i></a>
            @endif
            @if (!empty($system['social_tiktok']))
                <a href="{{ $system['social_tiktok'] }}" target="_blank"><i class="fa fa-tiktok"></i></a>
            @endif
            @if (!empty($system['social_twitter']))
                <a href="{{ $system['social_twitter'] }}" target="_blank"><i class="fa fa-twitter"></i></a>
            @endif
        </div>
        <div class="hp-vertical-label hp-label-right">{{ $property->status ?? 'Đang bán' }} <span class="hp-m-10">|</span>
            Diện tích: {{ $property->area_sqm ?? '120m²' }} m²</div>

        <div class="swiper ln-hero-swiper hp-h-100">
            <div class="swiper-wrapper">
                @php
                    $sliderImages =
                        isset($allImages) && count($allImages) > 0
                            ? $allImages
                            : [asset('frontend/resources/img/homely/slider/1.webp')];
                @endphp
                @foreach ($sliderImages as $img)
                    @php
                        $imgUrl = is_array($img) ? $img['url'] ?? '' : $img;
                    @endphp
                    <div class="swiper-slide hp-hero-slide" style="background-image: url('{{ $imgUrl }}')">
                        <div class="hp-hero-overlay"></div>
                        <div class="uk-container uk-container-center hp-hero-content uk-text-left">
                            <h1 class="hp-hero-title-main animated fadeInUp">
                                {!! $property->title ?? 'Homepark Elite Residence' !!}
                            </h1>
                            <p class="hp-hero-subtitle-main animated fadeInUp">
                                {{ $property->description_short ?? 'Không gian sống đẳng cấp tại trung tâm thành phố' }}
                            </p>
                            <div class="hp-hero-btns animated fadeInUp">
                                <a href="/bat-dong-san.html" class="hp-btn hp-btn-outline-primary hp-fs-11 hp-ls-2"
                                    style="max-width: 250px;">
                                    Nhận tư vấn ngay <i class="fa fa-caret-right" style="margin-left: 10px;"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    <section class="hp-visit-section">
        <div class="uk-container uk-container-center">
            <div class="hp-visit-box">
                <form action="{{ route('visit-request.store') }}" method="POST" class="hp-visit-form">
                    @csrf
                    <input type="hidden" name="property_id" value="{{ $property->id ?? '' }}">

                    <div class="uk-grid uk-grid-medium">
                        <div class="uk-width-large-1-3">
                            <div class="hp-visit-field">
                                <label>Họ và tên</label>
                                <input type="text" name="full_name" placeholder="Nguyễn Văn A" class="hp-visit-input"
                                    required>
                            </div>
                        </div>
                        <div class="uk-width-large-1-3">
                            <div class="hp-visit-field">
                                <label>Email</label>
                                <input type="email" name="email" placeholder="example@gmail.com" class="hp-visit-input"
                                    required>
                            </div>
                        </div>
                        <div class="uk-width-large-1-3">
                            <div class="hp-visit-field">
                                <label>Số điện thoại</label>
                                <input type="text" name="phone" placeholder="090 123 4567" class="hp-visit-input"
                                    required>
                            </div>
                        </div>
                    </div>

                    <div class="uk-grid uk-grid-medium">
                        <div class="uk-width-large-1-4">
                            <div class="hp-visit-field">
                                <label>Ngày tham quan</label>
                                <input type="date" name="preferred_date" class="hp-visit-input">
                            </div>
                        </div>
                        <div class="uk-width-large-1-4">
                            <div class="hp-visit-field">
                                <label>Giờ tham quan</label>
                                <input type="time" name="preferred_time" class="hp-visit-input">
                            </div>
                        </div>
                        <div class="uk-width-large-1-4">
                            <div class="hp-visit-field">
                                <label>Lời nhắn</label>
                                <input type="text" name="message" placeholder="Tôi muốn tìm hiểu thêm về..."
                                    class="hp-visit-input">
                            </div>
                        </div>
                        <div class="uk-width-large-1-4">
                            <div class="hp-visit-submit-wrap">
                                <button type="submit" class="hp-btn hp-btn-dark uk-width-1-1">Nhận tư vấn ngay</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>


    @php
        $propTitle = $property->title ?? 'Homepark Residence';
        $propDesc =
            $property->description ??
            'Mỗi căn hộ tại Homepark Residence không chỉ là nơi để ở, mà là một kiệt tác kiến trúc được đo ni đóng giày cho những chủ nhân trân trọng giá trị nghệ thuật và sự riêng tư tuyệt đối.';

        // Image logic: fallback to galleries if needed
        $img1 = asset('frontend/resources/img/homely/gallery/1.webp');
        $img2 = asset('frontend/resources/img/homely/gallery/2.webp');

        if (isset($galleries) && count($galleries) > 0) {
            $album = $galleries[0]->album;
            if (is_string($album)) {
                $album = json_decode($album, true);
            }
            if (is_array($album)) {
                if (!empty($album[0])) {
                    $img1 = $album[0];
                }
                if (!empty($album[1])) {
                    $img2 = $album[1];
                }
            }
        }
    @endphp

    <section class="hp-section bg-white hp-section-padding">
        <div class="uk-container uk-container-center">
            <div class="uk-grid uk-grid-large uk-flex-middle" data-uk-grid-match>
                <div class="uk-width-large-1-2">
                    <div class="hp-img-stack" data-uk-scrollspy="{cls:'uk-animation-slide-left', delay:300}">
                        <div class="hp-deco-block section-01-bg"></div>
                        <div class="hp-vertical-bars">
                            <span></span><span></span><span></span><span></span><span></span>
                            <span></span><span></span><span></span><span></span><span></span>
                        </div>
                        <div class="hp-pattern-dots section-01-dots"></div>

                        <img src="{{ $img1 }}" alt="{{ $propTitle }}" class="hp-img-main">
                        <img src="{{ $img2 }}" alt="{{ $propTitle }} Details" class="hp-img-sub">
                    </div>
                </div>
                <div class="uk-width-large-1-2">
                    <div class="hp-content-box" data-uk-scrollspy="{cls:'uk-animation-slide-right', delay:500}">
                        <span class="hp-section-num">01</span>
                        <div class="hp-title-serif">Về bất động sản</div>
                        <h2 class="hp-subtitle-dark">{{ $propTitle }}</h2>
                        <p class="uk-margin-large-bottom hp-text-desc">
                            {!! $propDesc !!}
                        </p>
                        <a href="{{ route('router.index', ['canonical' => $property->slug ?? '#']) }}"
                            class="hp-link-more">
                            <i class="fa fa-th-large"></i>
                            xem chi tiết căn hộ
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="hp-section hp-bg-light hp-section-padding hp-border-top"
        data-uk-scrollspy="{cls:'uk-animation-slide-bottom', delay:200}">
        <div class="uk-container uk-container-center uk-text-center"
            data-uk-scrollspy="{cls:'uk-animation-fade', delay:300}">
            <span class="hp-section-num">02</span>
            <div class="hp-title-serif">Tiện nghi Homepark</div>
            <h2 class="hp-subtitle-dark">Căn hộ hoàn mỹ tại trung tâm dự án</h2>

            <div class="uk-grid uk-grid-divider uk-margin-large-top" data-uk-grid-margin>
                <div class="uk-width-large-1-5 uk-width-medium-1-2">
                    <i class="fa fa-arrows-alt text-primary hp-stat-icon"></i>
                    <div class="hp-stat-label">Diện tích</div>
                    <div class="hp-stat-value">
                        <span data-counter="{{ $property->area_sqm ?? 0 }}">0</span>
                        <span class="hp-stat-unit">m²</span>
                    </div>
                </div>
                <div class="uk-width-large-1-5 uk-width-medium-1-2">
                    <i class="fa fa-bed text-primary hp-stat-icon"></i>
                    <div class="hp-stat-label">Phòng ngủ</div>
                    <div class="hp-stat-value">
                        <span data-counter="{{ $property->bedrooms ?? 0 }}">0</span>
                    </div>
                </div>
                <div class="uk-width-large-1-5 uk-width-medium-1-2">
                    <i class="fa fa-bath text-primary hp-stat-icon"></i>
                    <div class="hp-stat-label">Phòng tắm</div>
                    <div class="hp-stat-value">
                        <span data-counter="{{ $property->bathrooms ?? 0 }}">0</span>
                    </div>
                </div>
                <div class="uk-width-large-1-5 uk-width-medium-1-2">
                    <i class="fa fa-car text-primary hp-stat-icon"></i>
                    <div class="hp-stat-label">Chỗ đỗ xe</div>
                    <div class="hp-stat-value">
                        <span data-counter="{{ $property->parking_spots ?? 0 }}">0</span>
                    </div>
                </div>
                <div class="uk-width-large-1-5 uk-width-medium-1-2">
                    <i class="fa fa-building text-primary hp-stat-icon"></i>
                    <div class="hp-stat-label">Số tầng</div>
                    <div class="hp-stat-value">
                        <span data-counter="{{ $property->floors ?? 0 }}">0</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @php
        $galleryImages = [];
        if (isset($galleries) && $galleries->isNotEmpty()) {
            foreach ($galleries as $gallery) {
                if (!empty($gallery->album) && is_array($gallery->album)) {
                    foreach ($gallery->album as $img) {
                        $galleryImages[] = $img;
                        if (count($galleryImages) >= 4) {
                            break 2;
                        }
                    }
                }
            }
        }
        if (empty($galleryImages)) {
            for ($i = 1; $i <= 4; $i++) {
                $galleryImages[] = asset("frontend/resources/img/homely/gallery/{$i}.webp");
            }
        }
    @endphp

    <section class="hp-section bg-white hp-section-padding">
        <div class="uk-container uk-container-center">
            <div class="uk-grid uk-grid-large" data-uk-scrollspy="{cls:'uk-animation-fade', delay:300}">
                <div class="uk-width-large-2-5">
                    <span class="hp-section-num">03</span>
                    <div class="hp-title-serif">Thư viện hình ảnh</div>
                    <h2 class="hp-subtitle-dark">Không gian sống sang trọng & đẳng cấp</h2>
                    <p class="hp-text-desc uk-margin-large-bottom">
                        Từng góc nhỏ trong căn hộ đều được chăm chút kỹ lưỡng, mang lại cảm giác ấm cúng nhưng không kém
                        phần sang trọng.
                    </p>
                    <p>
                        {!! $property->description ?? '' !!}
                    </p>
                    <a href="#" class="hp-link-gallery-all">
                        Xem tất cả ảnh <i class="fa fa-caret-right"></i>
                    </a>
                </div>


                <div class="uk-width-large-3-5">
                    <div class="hp-gallery-grid">
                        @foreach ($galleryImages as $img)
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


    <section class="hp-section bg-white hp-section-padding">
        <div class="uk-container uk-container-center">
            <div class="uk-grid uk-grid-large" data-uk-grid-margin>
                <div class="uk-width-large-1-2">
                    <div class="hp-content-box hp-content-box-floorplan"
                        data-uk-scrollspy="{cls:'uk-animation-slide-left', delay:300}">
                        <span class="hp-section-num">04</span>
                        <div class="hp-title-serif">Không gian sống</div>
                        <h2 class="hp-subtitle-dark">Sơ đồ căn hộ tiêu chuẩn</h2>
                        <p class="uk-margin-medium-bottom hp-text-desc">
                            Mỗi mặt bằng căn hộ tại Homepark Residence được tối ưu hóa diện tích sử dụng, đảm bảo sự thông
                            thoáng và tận dụng tối đa ánh sáng tự nhiên cho mọi không gian.
                        </p>

                        <ul class="hp-floorplan-stats">
                            <li class="hp-floorplan-stat-item">
                                <span class="hp-floorplan-stat-label">Diện tích tổng:</span>
                                <span class="hp-floorplan-stat-value">{{ $property->area_sqm ?? '0' }} m²</span>
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
                            <li class="hp-floorplan-stat-item">
                                <span class="hp-floorplan-stat-label">Trạng thái:</span>
                                <span class="hp-floorplan-stat-value">Sẵn sàng bàn giao</span>
                            </li>
                            <li class="hp-floorplan-stat-item last-child-no-border">
                                <span class="hp-floorplan-stat-label">Năm bàn giao:</span>
                                <span class="hp-floorplan-stat-value">{{ $property->year_built ?? '2025' }}</span>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="uk-width-large-1-2" data-uk-scrollspy="{cls:'uk-animation-slide-right', delay:500}">
                    <div class="hp-floorplan-tabs">
                        @if (isset($floorplans) && $floorplans->isNotEmpty())
                            <ul class="uk-tab" data-uk-tab="{connect:'#floorplan-switcher'}">
                                @foreach ($floorplans as $plan)
                                    <li class="{{ $loop->first ? 'uk-active' : '' }}">
                                        <a href="">{{ $plan->floor_label }}</a>
                                    </li>
                                @endforeach
                            </ul>
                            <ul id="floorplan-switcher" class="uk-switcher uk-margin">
                                @foreach ($floorplans as $plan)
                                    <li>
                                        <a href="{{ $plan->plan_image }}" data-fancybox="floorplans"
                                            class="hp-floorplan-img-wrap">
                                            <img src="{{ $plan->plan_image }}" alt="{{ $plan->floor_label }}">
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <ul class="uk-tab" data-uk-tab="{connect:'#floorplan-switcher-mock'}">
                                <li class="uk-active"><a href="">1 Room 47m²</a></li>
                                <li><a href="">2 Rooms 65m²</a></li>
                                <li><a href="">3 Rooms 90m²</a></li>
                            </ul>
                            <ul id="floorplan-switcher-mock" class="uk-switcher uk-margin">
                                <li>
                                    <a href="{{ asset('frontend/resources/img/homely/gallery/1.webp') }}"
                                        data-fancybox="floorplans-mock" class="hp-floorplan-img-wrap">
                                        <img src="{{ asset('frontend/resources/img/homely/gallery/1.webp') }}"
                                            alt="1 Room">
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ asset('frontend/resources/img/homely/gallery/2.webp') }}"
                                        data-fancybox="floorplans-mock" class="hp-floorplan-img-wrap">
                                        <img src="{{ asset('frontend/resources/img/homely/gallery/2.webp') }}"
                                            alt="2 Rooms">
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ asset('frontend/resources/img/homely/gallery/3.webp') }}"
                                        data-fancybox="floorplans-mock" class="hp-floorplan-img-wrap">
                                        <img src="{{ asset('frontend/resources/img/homely/gallery/3.webp') }}"
                                            alt="3 Rooms">
                                    </a>
                                </li>
                            </ul>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>


    <section class="hp-section uk-position-relative hp-p-0" style="height: 600px;">
        <div class="uk-cover-background uk-position-cover"
            style="background-image: url('{{ isset($property) && !empty($property->image) ? $property->image : asset('frontend/resources/img/homely/slider/1.webp') }}');">
        </div>
        <div class="uk-position-relative uk-flex uk-flex-middle uk-flex-center uk-height-1-1 uk-text-center"
            data-uk-scrollspy="{cls:'uk-animation-scale-up', delay:300}">
            <div class="hp-z-10">
                <span class="section-label hp-text-white">Trải Nghiệm Thực Tế</span>
                <h2 class="hp-text-white uk-margin-large-bottom hp-title-serif hp-video-title">Góc Nhìn Từ Trong Căn Hộ
                </h2>

                <div class="hp-btn-play-wrap">
                    <a href="{{ $property->video_tour_url ?? 'https://www.youtube.com/watch?v=dQw4w9WgXcQ' }}"
                        data-fancybox class="hp-btn-play-pulse">
                        <i class="fa fa-play hp-play-icon"></i>
                    </a>
                </div>
            </div>
        </div>

        <section class="hp-cta-section">
            <div class="uk-container uk-container-center">
                <div class="hp-cta-row">

                    <div class="hp-cta-box" data-uk-scrollspy="{cls:'uk-animation-slide-left', delay:300}">
                        <span class="hp-section-num">06</span>
                        <div class="hp-title-serif">Không gian sống</div>
                        <h2 class="hp-subtitle-dark">Bạn có đang quan tâm đến Homepark?</h2>
                        <p class="hp-cta-desc">
                            Hệ thống tiện ích hiện đại cùng không gian sống xanh tại Homepark Residence hứa hẹn mang đến một
                            tổ ấm lý tưởng và cơ hội đầu tư bền vững cho cộng đồng tinh hoa. Bảo vệ môi trường và kiến tạo
                            giá trị sống là sứ mệnh của chúng tôi.
                        </p>
                        <a href="/lien-he.html" class="hp-cta-btn">
                            ĐẶT LỊCH THĂM QUAN <i class="fa fa-caret-right"></i>
                        </a>
                    </div>


                    <div class="hp-cta-img-wrap" data-uk-scrollspy="{cls:'uk-animation-slide-right', delay:500}">
                        <div class="hp-badge-accent hp-badge-yellow">Sang trọng</div>
                        @php
                            $ctaImage = asset('frontend/resources/img/homely/slider/2.webp'); // Fallback lifestyle image
                            if (isset($galleries) && $galleries->isNotEmpty()) {
                                $firstGallery = $galleries->first();
                                if (!empty($firstGallery->album) && isset($firstGallery->album[1])) {
                                    $ctaImage = $firstGallery->album[1];
                                }
                            }
                        @endphp
                        <img src="{{ $ctaImage }}" alt="Interested in Homepark">
                    </div>
                </div>
            </div>
        </section>


        <section class="hp-news-section bg-white" data-uk-scrollspy="{cls:'uk-animation-slide-bottom', delay:200}">
            <div class="uk-container uk-container-center" data-uk-scrollspy="{cls:'uk-animation-fade', delay:300}">
                <div class="hp-news-header">
                    <span class="hp-section-num">07</span>
                    <div class="hp-title-serif">Tin tức</div>
                    <h2 class="hp-header-accent hp-subtitle-dark">Tin Tức Mới Nhất</h2>
                </div>

                <div class="uk-grid uk-grid-large" data-uk-grid-margin>
                    @if (isset($posts) && count($posts) > 0)
                        @foreach ($posts as $post)
                            @php
                                $postImage = !empty($post->image)
                                    ? asset($post->image)
                                    : asset('images/placeholder-news.jpg');
                                $postUrl = !empty($post->canonical)
                                    ? url(
                                        rtrim($post->canonical, '/') .
                                            (str_ends_with($post->canonical, '.html') ? '' : '.html'),
                                    )
                                    : '#';
                                $postName = $post->name ?? 'Untitled';
                                $publishedAt = !empty($post->released_at)
                                    ? \Carbon\Carbon::parse($post->released_at)
                                    : \Carbon\Carbon::parse($post->created_at);
                                $dateFormatted = $publishedAt->format('d/m/Y');

                                $catName = '';
                                if ($post->post_catalogues->count() > 0) {
                                    $cat = $post->post_catalogues->first();
                                    $catName = $cat->languages->first()->pivot->name ?? '';
                                }
                            @endphp
                            <div class="uk-width-large-1-3 uk-width-medium-1-2 uk-margin-bottom">
                                <article class="hp-post-card" data-reveal="up">
                                    <div class="hp-post-card__img">
                                        <a href="{{ $postUrl }}">
                                            <img src="{{ $postImage }}" alt="{{ $postName }}">
                                        </a>
                                        @if ($catName)
                                            <span class="hp-post-card__badge">{{ $catName }}</span>
                                        @endif
                                    </div>
                                    <div class="hp-post-card__body">
                                        <div class="hp-post-card__meta">
                                            <i class="fa fa-calendar-o"></i> {{ $dateFormatted }}
                                        </div>
                                        <h3 class="hp-post-card__title">
                                            <a href="{{ $postUrl }}">{{ Str::limit($postName, 60) }}</a>
                                        </h3>
                                    </div>
                                </article>
                            </div>
                        @endforeach
                    @else
                        @for ($i = 1; $i <= 3; $i++)
                            <div class="uk-width-large-1-3 uk-width-medium-1-2 uk-margin-bottom">
                                <article class="hp-post-card" data-reveal="up">
                                    <div class="hp-post-card__img">
                                        <a href="#">
                                            <img src="{{ asset('frontend/resources/img/homely/gallery/' . $i . '.webp') }}"
                                                alt="Mock News">
                                        </a>
                                        <span class="hp-post-card__badge">Tin tức</span>
                                    </div>
                                    <div class="hp-post-card__body">
                                        <div class="hp-post-card__meta">
                                            <i class="fa fa-calendar-o"></i> {{ \Carbon\Carbon::now()->format('d/m/Y') }}
                                        </div>
                                        <h3 class="hp-post-card__title">
                                            <a href="#">Xu Hướng BĐS Cao Cấp Năm 2026 Có Gì Mới?</a>
                                        </h3>
                                    </div>
                                </article>
                            </div>
                        @endfor
                    @endif
                </div>
            </div>
        </section>
    @endsection
