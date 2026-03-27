@extends('frontend.homepage.layout')
@section('header-class', 'header-inner')

@section('content')
    <div class="linden-page">
        <!-- Minimalist Header & Breadcrumbs -->
        <section class="hp-detail-header">
            <div class="uk-container uk-container-center">
                <ul class="uk-breadcrumb uk-flex uk-flex-middle">
                    <li><a href="{{ url('/') }}">Trang chủ</a></li>
                    <li><a href="{{ url('bai-viet.html') }}">Bài viết</a></li>
                    @if (isset($breadcrumb) && is_array($breadcrumb))
                        @foreach ($breadcrumb as $key => $val)
                            <li><a href="{{ $val['canonical'] }}">{{ $val['name'] }}</a></li>
                        @endforeach
                    @endif
                    @if (isset($postCatalogue) && $postCatalogue)
                        <li class="uk-active"><span>{{ $postCatalogue->languages->first()->pivot->name }}</span></li>
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

        <section class="hp-section bg-white">
            <div class="uk-container uk-container-center">
                <div class="uk-grid uk-grid-medium" data-uk-grid-margin>
                    <!-- Main Content (75%) -->
                    <div class="uk-width-large-3-4">
                        <div class="hp-listing-top uk-flex uk-flex-middle uk-flex-space-between uk-margin-large-bottom">
                            <div class="hp-listing-title">
                                <h1 class="hp-category-name">
                                    @if (request('keyword'))
                                        Kết quả tìm kiếm: "{{ request('keyword') }}"
                                    @else
                                        {{ isset($postCatalogue) && $postCatalogue ? $postCatalogue->languages->first()->pivot->name : 'Tin tức & Sự kiện' }}
                                    @endif
                                </h1>
                                <div class="hp-listing-count">
                                    <i class="fa fa-newspaper-o uk-margin-small-right"></i>
                                    {{ $posts->total() }} bài viết
                                </div>
                            </div>
                        </div>

                        @if ($posts->count() > 0)
                            <div class="uk-grid uk-grid-medium" data-uk-grid-margin>
                                @foreach ($posts as $post)
                                    <div class="uk-width-medium-1-2 uk-margin-bottom">
                                        @include('frontend.component.post_card', ['post' => $post])
                                    </div>
                                @endforeach
                            </div>

                            <div class="uk-margin-large-top">
                                {{ $posts->links('frontend.component.pagination') }}
                            </div>
                        @else
                            <div class="uk-alert uk-alert-warning">Đang cập nhật bài viết...</div>
                        @endif
                    </div>

                    <div class="uk-width-large-1-4">
                        @include('frontend.component.sidebar_posts')
                    </div>
                </div>
            </div>
        </section>
    </div>

    <style>
        .hp-section {
            padding: 40px 0;
        }

        .hp-category-name {
            font-size: 28px;
            font-weight: 800;
            color: #111;
            margin: 0 0 5px 0;
            text-transform: uppercase;
        }

        .hp-listing-count {
            font-size: 14px;
            color: #888;
        }

        /* Sidebar Styling */
        .hp-sidebar-sticky {
            position: sticky;
            top: 100px;
            z-index: 10;
        }

        .hp-sidebar-widget {
            background: #fff;
            border-radius: 15px;
            padding: 24px;
            margin-bottom: 30px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            border: 1px solid #f5f5f5;
        }

        .hp-sidebar-title {
            font-size: 18px;
            font-weight: 700;
            color: #111;
        }

        .hp-sidebar-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .hp-sidebar-list li {
            margin-bottom: 8px;
        }

        .hp-sidebar-list li a {
            color: #555;
            font-size: 15px;
            transition: all 0.3s;
            display: block;
            text-decoration: none;
        }

        .hp-sidebar-list li.active a,
        .hp-sidebar-list li a:hover {
            color: var(--main-color);
            font-weight: 600;
        }

        /* Custom Search Box */
        .hp-search-container {
            position: relative;
        }

        .hp-search-container i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #999;
            z-index: 5;
        }

        .hp-search-container input {
            background: #f8f8f8 !important;
            border: 1px solid #eee !important;
            border-radius: 8px !important;
            padding: 12px 15px 12px 45px !important;
            font-size: 14px !important;
            height: auto !important;
            width: 100%;
            box-sizing: border-box;
        }

        .hp-search-container input:focus {
            background: #fff !important;
            border-color: var(--main-color) !important;
            outline: none;
        }

        @media (max-width: 959px) {
            .hp-sidebar-sticky {
                position: static;
                margin-top: 40px;
            }
        }
    </style>
@endsection
