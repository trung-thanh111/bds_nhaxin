@extends('frontend.homepage.layout')
@section('header-class', 'header-inner')
@section('content')
    <style>
        .hp-contact-title {
            font-weight: 700;
            font-size: 32px;
            margin-bottom: 10px;
            color: #1a1a1a;
        }

        .hp-contact-subtitle {
            color: var(--main-color);
            font-weight: 600;
            letter-spacing: 1px;
            font-size: 13px;
            text-transform: uppercase;
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 30px;
        }

        .hp-line {
            height: 2px;
            width: 40px;
            background: var(--main-color);
        }

        .hp-contact-info-block {
            padding: 20px 0;
        }

        .hp-contact-info-block h4 {
            font-weight: 700;
            font-size: 16px;
            margin-bottom: 12px;
            color: #1a1a1a;
            text-transform: uppercase;
        }

        .hp-contact-info-block h4 i {
            color: var(--main-color);
            width: 20px;
        }

        .hp-contact-info-block p {
            color: #666;
            margin: 5px 0;
            font-size: 15px;
            line-height: 1.6;
        }

        .hp-contact-map-wrap {
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
            border: 1px solid #eee;
            margin-right: -1px;
            z-index: 2;
            position: relative;
        }

        .hp-contact-form-wrap {
            background: #fff;
            padding: 50px;
            border-radius: 0 16px 16px 0;
            box-shadow: 20px 10px 50px rgba(0, 0, 0, 0.04);
            border: 1px solid #eee;
            border-left: none;
            position: relative;
            z-index: 1;
        }

        .hp-form-title {
            font-weight: 700;
            margin-bottom: 5px;
        }

        .hp-form-desc {
            color: #777;
            font-size: 14px;
        }

        .hp-btn-submit {
            background: var(--main-color) !important;
            border: none !important;
            font-weight: 700 !important;
            height: 55px !important;
            border-radius: 8px !important;
        }

        .contact-form-success {
            display: none;
            margin-top: 20px;
            padding: 20px;
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            border-radius: 8px;
            color: #166534;
            text-align: center;
        }

        .hp-success-title {
            margin: 0;
            color: #166534;
            font-weight: 700;
            margin-right: 30px;
        }

        .hp-success-desc {
            margin-top: 10px;
        }

        .hp-section-secondary {
            background: #f9fbff;
            border-top: 1px solid #f0f0f0;
        }

        @media (max-width: 959px) {
            .hp-contact-form-wrap {
                border-radius: 16px;
                border-left: 1px solid #eee;
                padding: 30px;
                margin-top: 30px;
            }
        }

        .hp-section-padding {
            padding: 20px 0;
        }

        .uk-form-controls input,
        .uk-form-controls textarea {
            border-radius: 5px !important;
        }

        .uk-form-controls input:focus,
        .uk-form-controls textarea:focus {
            border-color: var(--main-color) !important;
        }
    </style>

    <div id="scroll-progress"></div>
    <div class="linden-page">

        <section class="hp-detail-header">
            <div class="uk-container uk-container-center">
                <ul class="uk-breadcrumb uk-flex uk-flex-middle">
                    <li><a href="{{ url('/') }}">Trang chủ</a></li>
                    <li class="uk-active"><span>Liên hệ</span></li>
                </ul>
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
                            <h4><i class="fa fa-map-marker uk-margin-small-right"></i> Địa chỉ</h4>
                            <p>{{ $system['contact_address'] ?? '88 Nguyễn Hữu Cảnh, Phường 22, Bình Thạnh, Hồ Chí Minh' }}
                            </p>
                        </div>
                    </div>
                    <div class="uk-width-large-1-4 uk-width-medium-1-2">
                        <div class="hp-contact-info-block" data-reveal="up">
                            <h4><i class="fa fa-phone uk-margin-small-right"></i> Liên hệ</h4>
                            <p><strong>Email:</strong> {{ $system['contact_email'] ?? 'homepark@gmail.com' }}</p>
                            <p><strong>Hotline:</strong> {{ $system['contact_hotline'] ?? '(+84) 0987 654 321' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Map & Form Section -->
        <section class="hp-section hp-section-padding hp-section-secondary">
            <div class="uk-container uk-container-center">
                <div class="uk-grid uk-grid-collapse uk-flex-middle">
                    <!-- Map Column -->
                    <div class="uk-width-large-1-2">
                        <div class="hp-contact-map-wrap" data-reveal="left">
                            <div class="hp-map-decoration"></div>
                            <div class="hp-map-inner">
                                <iframe
                                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3919.4602324243567!2d106.718144975838!3d10.776019489372922!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31752f44dc1b78ad%3A0xc07ce67822989f92!2sLinden%20Residences!5e0!3m2!1svi!2s!4v1710145241085!5m2!1svi!2s"
                                    width="100%" height="550" style="border:0;" allowfullscreen=""
                                    loading="lazy"></iframe>
                            </div>
                        </div>
                    </div>
                    <!-- Form Column -->
                    <div class="uk-width-large-1-2">
                        <div class="hp-contact-form-wrap" data-reveal="right">
                            <div class="hp-form-header uk-margin-bottom">
                                <h2 class="hp-form-title">Gửi yêu cầu tư vấn</h2>
                                <p class="hp-form-desc">Chúng tôi sẽ phản hồi yêu cầu của bạn trong vòng
                                    24h làm việc.</p>
                            </div>
                            <form id="contact-request-form" class="uk-form uk-form-stacked ajax-contact-form" method="post"
                                action="{{ route('contact-request.store') }}">
                                @csrf
                                <div class="uk-form-row">
                                    <label class="uk-form-label">Họ và tên <span class="uk-text-danger">*</span></label>
                                    <div class="uk-form-controls">
                                        <input type="text" name="full_name" required class="uk-width-1-1 uk-form-large"
                                            placeholder="Nhập họ và tên...">
                                    </div>
                                </div>

                                <div class="uk-grid uk-grid-small uk-margin-top" data-uk-grid-margin>
                                    <div class="uk-width-medium-1-2">
                                        <div class="uk-form-row">
                                            <label class="uk-form-label">Email</label>
                                            <div class="uk-form-controls">
                                                <input type="email" name="email" class="uk-width-1-1 uk-form-large"
                                                    placeholder="Nhập địa chỉ email...">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="uk-width-medium-1-2">
                                        <div class="uk-form-row">
                                            <label class="uk-form-label">Số điện thoại <span
                                                    class="uk-text-danger">*</span></label>
                                            <div class="uk-form-controls">
                                                <input type="text" name="phone" required
                                                    class="uk-width-1-1 uk-form-large" placeholder="Nhập số điện thoại...">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="uk-form-row uk-margin-top">
                                    <label class="uk-form-label">Tiêu đề</label>
                                    <div class="uk-form-controls">
                                        <input type="text" name="subject" class="uk-width-1-1 uk-form-large"
                                            placeholder="Bạn quan tâm về vấn đề gì?">
                                    </div>
                                </div>

                                <div class="uk-form-row uk-margin-top">
                                    <label class="uk-form-label">Lời nhắn của bạn <span
                                            class="uk-text-danger">*</span></label>
                                    <div class="uk-form-controls">
                                        <textarea name="content" required class="uk-width-1-1" rows="5" placeholder="Nhập nội dung yêu cầu..."></textarea>
                                    </div>
                                </div>

                                <div class="uk-form-row uk-margin-large-top">
                                    <button type="submit"
                                        class="uk-button uk-button-primary uk-button-large uk-width-1-1 ln-btn-submit hp-btn-submit">
                                        GỬI YÊU CẦU
                                    </button>
                                </div>

                                <div class="contact-form-success">
                                    <h4 class="hp-success-title">Yêu cầu của
                                        bạn đã được gửi thành
                                        công!</h4>
                                    <p class="hp-success-desc">Chúng tôi đã tiếp nhận thông tin và sẽ liên hệ lại sớm
                                        nhất.</p>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </div>
@endsection
