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
                        <h1 class="hp-luxury-header__title">Xung quanh</h1>
                        <p class="hp-luxury-header__desc hp-hero-subtitle-main">
                            Khám phá tiện ích ngoại khu và môi trường sống xung quanh {{ $property->title ?? 'dự án' }}.
                        </p>
                    </div>
                    <div class="hp-luxury-breadcrumb" data-reveal="left">
                        <div class="content-breadcrumb">
                            <a href="{{ route('home.index') }}">Trang chủ</a>
                            <span class="separator">»</span>
                            <span class="current">Xung quanh</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="hp-section bg-white hp-section-padding">
            <div class="uk-container uk-container-center">
                <div class="hp-section-header">
                    <div class="hp-title-serif">Vị trí đắc địa</div>
                    <h2 class="hp-subtitle-dark">Mọi thứ bạn cần,<br>ngay bên cạnh</h2>
                </div>

                @php
                    $locItems = collect();
                    if ($locationHighlights->count() > 0) {
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

                <div class="uk-grid uk-grid-medium uk-margin-large-bottom" data-uk-grid-margin
                    data-uk-grid-match="{target:'.hp-neighbour-card'}">
                    @foreach ($locItems as $item)
                        <div class="uk-width-large-1-3 uk-width-medium-1-2">
                            <div class="hp-neighbour-card"
                                data-uk-scrollspy="{cls:'uk-animation-slide-bottom', delay: {{ $loop->index * 100 }}}">
                                <div class="hp-neighbour-card__img">
                                    <img src="{{ $property->image ?? asset('frontend/resources/img/homely/slider/1.webp') }}"
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

        <section class="hp-section hp-bg-light hp-section-padding">
            <div class="uk-container uk-container-center uk-text-center">
                <div class="hp-title-serif" data-reveal="fade">Quan tâm?</div>
                <h2 class="hp-subtitle-dark" data-reveal="up">Đặt lịch tham quan ngay</h2>
                <p class="hp-text-desc uk-margin-large-bottom"
                    style="margin-left: auto; margin-right:auto; max-width: 600px;" data-reveal="up">
                    Hãy trực tiếp đến xem và cảm nhận không gian sống tuyệt vời tại
                    {{ $property->title ?? 'Homepark Residence' }}.
                </p>
                <div data-reveal="up">
                    <a href="/lien-he.html" class="hp-btn hp-btn-dark">
                        LIÊN HỆ NGAY <i class="fa fa-caret-right" style="margin-left: 10px;"></i>
                    </a>
                </div>
            </div>
        </section>

    </div>
@endsection
