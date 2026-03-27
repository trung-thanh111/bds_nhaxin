@extends('frontend.homepage.layout')
@section('header-class', 'header-inner')

@section('content')

    @php
        $postLang = $post->languages->first()?->pivot;
        $postTitle = $postLang?->name ?? ($post->name ?? '');
        $postDesc = $postLang?->description ?? '';
        $postImage = $post->image ?? asset('images/placeholder-news.jpg');

        $postDate = $post->released_at
            ? \Carbon\Carbon::parse($post->released_at)
            : \Carbon\Carbon::parse($post->created_at);

        $catLang = $postCatalogue->languages->first()?->pivot ?? null;
        $catName = $catLang?->name ?? ($postCatalogue->name ?? 'Bài viết');
    @endphp

    <div class="linden-page">
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
                    <li class="uk-active"><span>{{ \Str::limit($postTitle, 40) }}</span></li>
                </ul>
            </div>
        </section>
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
                    <div class="uk-width-large-3-4">
                        <article class="hp-post-detail">
                            <div class="hp-post-detail-header uk-margin-large-bottom">
                                <div class="hp-detail-meta uk-margin-small-bottom">
                                    <span class="hp-post-cat-tag uk-margin-right">{{ $catName }}</span>
                                    <span class="hp-post-date"><i class="fa fa-calendar-o uk-margin-small-right"></i>
                                        {{ $postDate->format('d/m/Y') }}</span>
                                </div>
                                <h1 class="hp-post-detail-title">{{ $postTitle }}</h1>
                            </div>

                            <div class="hp-post-main-img uk-margin-large-bottom">
                                <img src="{{ asset($postImage) }}" alt="{{ $postTitle }}" class="uk-border-rounded">
                            </div>

                            <div class="hp-post-content hp-content-entry">
                                {!! $contentWithToc ?? $postLang?->content !!}
                            </div>
                        </article>
                        @php
                            $relatedPosts = $asidePost;
                        @endphp
                        @if (isset($relatedPosts) && $relatedPosts->count() > 0)
                            <div class="hp-related-section uk-margin-large-top">
                                <h3 class="hp-related-title">Bài viết liên quan</h3>
                                <div class="uk-grid uk-grid-medium" data-uk-grid-margin>
                                    @foreach ($relatedPosts as $related)
                                        <div class="uk-width-medium-1-2 uk-margin-bottom">
                                            @include('frontend.component.post_card', ['post' => $related])
                                        </div>
                                    @endforeach
                                </div>
                            </div>
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
        .hp-post-detail {
            background-color: #fff;
            border-radius: 5px;
            padding: 30px;
        }

        .hp-section {
            padding: 40px 0;
        }

        .hp-post-detail-title {
            font-size: 32px;
            font-weight: 800;
            color: #111;
            line-height: 1.3;
            margin: 0;
        }

        .hp-detail-meta {
            display: flex;
            align-items: center;
        }

        .hp-post-cat-tag {
            background: var(--main-color);
            color: #fff;
            padding: 4px 12px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .hp-post-date {
            font-size: 14px;
            color: #888;
        }

        .hp-post-main-img img {
            width: 100%;
            display: block;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            border-radius: 12px;
        }

        .hp-post-content {
            font-size: 17px;
            line-height: 1.8;
            color: #333;
        }

        .hp-post-content p {
            margin-bottom: 25px;
        }

        /* Sidebar Styling (Sync with Catalogue) */
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
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid var(--main-color);
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

        .hp-related-title {
            font-size: 24px;
            font-weight: 700;
            color: #111;
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 1px solid #f0f0f0;
        }

        @media (max-width: 959px) {
            .hp-post-detail-title {
                font-size: 26px;
            }

            .hp-sidebar-sticky {
                position: static;
                margin-top: 40px;
            }
        }
    </style>
@endsection
