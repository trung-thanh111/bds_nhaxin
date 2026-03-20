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
                        <h1 class="hp-luxury-header__title">Liên Hệ</h1>
                        <p class="hp-luxury-header__desc hp-hero-subtitle-main">
                            Chúng tôi luôn sẵn sàng lắng nghe và hỗ trợ bạn
                        </p>
                    </div>
                    <div class="hp-luxury-breadcrumb" data-reveal="left">
                        <div class="content-breadcrumb">
                            <a href="{{ route('home.index') }}">Trang chủ</a>
                            <span class="separator">»</span>
                            <span class="current">Liên hệ</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Contact Info Section -->
        <section class="hp-section bg-white hp-section-padding">
            <div class="uk-container uk-container-center">
                <div class="uk-grid uk-grid-large" data-uk-grid-margin>
                    <div class="uk-width-large-1-2">
                        <div class="hp-contact-office" data-reveal="left">
                            <h2 class="hp-contact-title">Thông tin liên hệ</h2>
                            <div class="hp-contact-subtitle">
                                <span>TƯ VẤN CHUYÊN NGHIỆP</span>
                                <span class="hp-line"></span>
                            </div>
                        </div>
                    </div>
                    <div class="uk-width-large-1-4 uk-width-medium-1-2">
                        <div class="hp-contact-info-block" data-reveal="up">
                            <h4>Địa chỉ</h4>
                            <p>{{ $property->address ?? '742 Evergreen Terrace, Quận 7, TP. HCM' }}</p>
                        </div>
                    </div>
                    <div class="uk-width-large-1-4 uk-width-medium-1-2">
                        <div class="hp-contact-info-block" data-reveal="up">
                            <h4>Liên hệ</h4>
                            <p>{{ $system['contact_email'] ?? 'hello@homepark.com' }}</p>
                            <p>{{ $system['contact_hotline'] ?? '(+84) 123 456 789' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Map & Form Section -->
        <section class="hp-section bg-white">
            <div class="uk-container uk-container-center">
                <div class="uk-grid uk-grid-collapse uk-flex-middle">
                    <!-- Map Column -->
                    <div class="uk-width-large-1-2">
                        <div class="hp-contact-map-wrap" data-reveal="left">
                            <div class="hp-map-decoration"></div>
                            <div class="hp-map-inner">
                                <iframe
                                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3919.4602324243567!2d106.718144975838!3d10.776019489372922!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31752f44dc1b78ad%3A0xc07ce67822989f92!2sLinden%20Residences!5e0!3m2!1svi!2s!4v1710145241085!5m2!1svi!2s"
                                    width="100%" height="450" style="border:0;" allowfullscreen=""
                                    loading="lazy"></iframe>
                            </div>
                        </div>
                    </div>
                    <!-- Form Column -->
                    <div class="uk-width-large-1-2">
                        <div class="hp-contact-form-wrap" data-reveal="right">
                            <form id="visit-request-form" method="post" action="{{ route('visit-request.store') }}">
                                @csrf
                                <input type="hidden" name="property_id" value="{{ $property->id ?? '' }}">

                                <div class="hp-form-group">
                                    <label>Họ và tên (bắt buộc)</label>
                                    <input type="text" name="full_name" required class="hp-luxury-input"
                                        placeholder="Nhập họ và tên...">
                                </div>

                                <div class="hp-form-group">
                                    <label>Email (bắt buộc)</label>
                                    <input type="email" name="email" required class="hp-luxury-input"
                                        placeholder="Nhập địa chỉ email...">
                                </div>

                                <div class="hp-form-group">
                                    <label>Số điện thoại (bắt buộc)</label>
                                    <input type="text" name="phone" required class="hp-luxury-input"
                                        placeholder="Nhập số điện thoại...">
                                </div>

                                <div class="uk-grid uk-grid-small" data-uk-grid-margin>
                                    <div class="uk-width-1-2">
                                        <div class="hp-form-group">
                                            <label>Ngày hẹn</label>
                                            <input type="date" name="preferred_date" class="hp-luxury-input">
                                        </div>
                                    </div>
                                    <div class="uk-width-1-2">
                                        <div class="hp-form-group">
                                            <label>Giờ hẹn</label>
                                            <input type="time" name="preferred_time" class="hp-luxury-input">
                                        </div>
                                    </div>
                                </div>

                                <div class="hp-form-group">
                                    <label>Lời nhắn của bạn</label>
                                    <textarea name="message" class="hp-luxury-input hp-luxury-textarea" placeholder="Nhập lời nhắn..."></textarea>
                                </div>

                                <div class="hp-form-action">
                                    <button type="submit" class="hp-btn hp-btn-dark" style="padding: 15px 50px;">
                                        GỬI YÊU CẦU
                                    </button>
                                </div>

                                <div class="visit-form-success"
                                    style="display:none; margin-top:20px; padding:20px; background:#f9f5f0; color:var(--color-black); text-align:center;">
                                    <h4 style="margin:0;">Yêu cầu của bạn đã được gửi thành công!</h4>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </div>
@endsection
