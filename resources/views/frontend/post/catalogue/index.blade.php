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
                        <h1 class="hp-luxury-header__title">
                            @if (isset($postCatalogue) && $postCatalogue && $postCatalogue->parent_id != 0)
                                {{ $postCatalogue->languages->first()->pivot->name ?? 'Bài viết' }}
                            @else
                                Bài Viết
                            @endif
                        </h1>
                        <p class="hp-luxury-header__desc hp-hero-subtitle-main">
                            Cập nhật những thông tin mới nhất về thị trường bất động sản và các dự án của
                            {{ $system['homepage_brand'] ?? 'Homepark' }}.
                        </p>
                    </div>
                    <div class="hp-luxury-breadcrumb" data-reveal="left">
                        <div class="content-breadcrumb">
                            <a href="{{ route('home.index') }}">Trang chủ</a>
                            <span class="separator">»</span>
                            <span class="current">
                                @if (isset($postCatalogue) && $postCatalogue && $postCatalogue->parent_id != 0)
                                    {{ $postCatalogue->languages->first()->pivot->name ?? 'Tin tức' }}
                                @else
                                    Tin tức
                                @endif
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="hp-section bg-white hp-section-padding">
            <div class="uk-container uk-container-center">
                <div class="uk-grid uk-grid-large" data-uk-grid-margin>
                    <div class="uk-width-large-2-3">
                        @if (!empty($posts) && $posts->count() > 0)
                            <div class="uk-grid uk-grid-medium" data-uk-grid-margin>
                                @foreach ($posts as $index => $post)
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

                                        $categoryName = '';
                                        if ($post->post_catalogues->count() > 0) {
                                            $cat = $post->post_catalogues->first();
                                            $categoryName = $cat->languages->first()->pivot->name ?? '';
                                        }
                                    @endphp
                                    <div class="uk-width-medium-1-2 uk-margin-bottom">
                                        <article class="hp-post-card" data-reveal="up">
                                            <div class="hp-post-card__img">
                                                <a href="{{ $postUrl }}">
                                                    <img src="{{ $postImage }}" alt="{{ $postName }}">
                                                </a>
                                                @if ($categoryName)
                                                    <span class="hp-post-card__badge">{{ $categoryName }}</span>
                                                @endif
                                            </div>
                                            <div class="hp-post-card__body">
                                                <div class="hp-post-card__meta">
                                                    <i class="fa fa-calendar-o"></i> {{ $dateFormatted }}
                                                </div>
                                                <h3 class="hp-post-card__title">
                                                    <a href="{{ $postUrl }}">{{ $postName }}</a>
                                                </h3>
                                                <div class="hp-post-card__excerpt">
                                                    {!! Str::limit(strip_tags($post->languages->first()->pivot->description ?? ''), 100) !!}
                                                </div>
                                            </div>
                                        </article>
                                    </div>
                                @endforeach
                            </div>

                            @if ($posts->hasPages())
                                <div class="uk-margin-large-top">
                                    {{ $posts->links('frontend.component.pagination') }}
                                </div>
                            @endif
                        @else
                            <div class="hp-empty-state">
                                <p>Không tìm thấy bài viết nào trong chuyên mục này.</p>
                            </div>
                        @endif
                    </div>

                    <!-- Sidebar (4) -->
                    <div class="uk-width-large-1-3">
                        <aside class="hp-sidebar">
                            <!-- Search -->
                            <div class="hp-sidebar-widget">
                                <h4 class="hp-sidebar-title">Tìm kiếm</h4>
                                <form action="{{ request()->url() }}" method="GET" class="hp-sidebar-search">
                                    <input type="text" name="keyword" value="{{ request('keyword') }}"
                                        placeholder="Nhập từ khóa...">
                                    <button type="submit"><i class="fa fa-search"></i></button>
                                </form>
                            </div>

                            <!-- Categories -->
                            <div class="hp-sidebar-widget">
                                <h4 class="hp-sidebar-title">Danh mục</h4>
                                <ul class="hp-sidebar-list">
                                    @php
                                        $rootCanonical = 'bai-viet.html';
                                        if (isset($postCatalogue) && $postCatalogue && $postCatalogue->parent_id != 0) {
                                            if (isset($breadcrumb)) {
                                                foreach ($breadcrumb as $item) {
                                                    if ($item->parent_id == 0) {
                                                        $rootCanonical = rtrim($item->canonical, '/') . '.html';
                                                        break;
                                                    }
                                                }
                                            }
                                        }
                                    @endphp
                                    <li
                                        class="{{ !isset($postCatalogue) || $postCatalogue->parent_id == 0 ? 'active' : '' }}">
                                        <a href="{{ url($rootCanonical) }}">Tất cả bài viết</a>
                                    </li>
                                    @if (isset($categories))
                                        @foreach ($categories as $cat)
                                            <li
                                                class="{{ isset($postCatalogue) && $postCatalogue && $postCatalogue->id == $cat->id ? 'active' : '' }}">
                                                <a href="{{ url(rtrim($cat->canonical, '/') . '.html') }}">
                                                    {{ $cat->name }}
                                                </a>
                                            </li>
                                        @endforeach
                                    @endif
                                </ul>
                            </div>
                        </aside>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
