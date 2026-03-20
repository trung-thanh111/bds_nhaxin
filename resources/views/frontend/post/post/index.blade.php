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
        $dateFormatted = $postDate->format('F d, Y');

        $catLang = $postCatalogue->languages->first()?->pivot ?? null;
        $catName = $catLang?->name ?? ($postCatalogue->name ?? 'Bài viết');
        $catUrl = $catLang?->canonical ?? ($postCatalogue->canonical ?? '#');
    @endphp

    <div id="scroll-progress"></div>
    <div class="linden-page">

        <section class="hp-luxury-header"
            style="background-image: url('{{ $property->image ?? asset('frontend/resources/img/homely/slider/1.webp') }}');">
            <div class="hp-hero-overlay"></div>
            <div class="hp-luxury-header__content">
                <div class="uk-container uk-container-center">
                    <div class="hp-luxury-header__title-wrap" data-reveal="up">
                        <h1 class="hp-luxury-header__title">Chi tiết bài viết</h1>
                        <p class="hp-luxury-header__desc hp-hero-subtitle-main">
                            {{ $postTitle }}
                        </p>
                    </div>
                    <div class="hp-luxury-breadcrumb" data-reveal="left">
                        <div class="content-breadcrumb">
                            <a href="{{ route('home.index') }}">Trang chủ</a>
                            <span class="separator">»</span>
                            <a href="{{ write_url($catUrl) }}">{{ $catName }}</a>
                            <span class="separator">»</span>
                            <span class="current">{{ \Str::limit($postTitle, 40) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="hp-section bg-white hp-section-padding">
            <div class="uk-container uk-container-center">
                <div class="uk-grid uk-grid-large" data-uk-grid-margin>
                    <!-- Main Content (8) -->
                    <div class="uk-width-large-2-3">
                        <article class="hp-post-detail">
                            <div class="hp-post-detail__img" data-reveal="up">
                                <img src="{{ asset($postImage) }}" alt="{{ $postTitle }}">
                            </div>

                            <div class="hp-post-detail__header" data-reveal="up">
                                <div class="hp-post-detail__meta">
                                    <span class="hp-meta-item"><i class="fa fa-calendar-o"></i> {{ $dateFormatted }}</span>
                                    <span class="hp-meta-sep">•</span>
                                    <span class="hp-post-card__badge"
                                        style="position: static; vertical-align: middle;">{{ $catName }}</span>
                                </div>
                                <h2 class="hp-post-detail__title">{{ $postTitle }}</h2>
                            </div>

                            <div class="hp-post-detail__content hp-content-entry" data-reveal="up">
                                {!! $contentWithToc ?? $postLang?->content !!}
                            </div>
                        </article>

                        <!-- Related Posts -->
                        @if (isset($postCatalogue->posts) && $postCatalogue->posts->where('id', '!=', $post->id)->count() > 0)
                            <div class="hp-related-posts uk-margin-large-top">
                                <h3 class="hp-sidebar-title">Bài viết liên quan</h3>
                                <div class="uk-grid uk-grid-medium" data-uk-grid-margin>
                                    @foreach ($postCatalogue->posts->where('id', '!=', $post->id)->take(3) as $index => $related)
                                        @php
                                            $rLang = $related->languages->first()?->pivot;
                                            $rTitle = $rLang?->name ?? '';
                                            $rUrl = write_url($rLang?->canonical ?? '#');
                                            $rImg = !empty($related->image)
                                                ? asset($related->image)
                                                : asset('images/placeholder-news.jpg');
                                            $rDate = $related->released_at
                                                ? \Carbon\Carbon::parse($related->released_at)
                                                : \Carbon\Carbon::parse($related->created_at);
                                            $rDateFormatted = $rDate->format('d/m/Y');
                                        @endphp
                                        <div class="uk-width-medium-1-3">
                                            <article class="hp-post-card" style="margin-bottom: 0;">
                                                <div class="hp-post-card__img" style="padding-top: 55%;">
                                                    <a href="{{ $rUrl }}">
                                                        <img src="{{ $rImg }}" alt="{{ $rTitle }}">
                                                    </a>
                                                </div>
                                                <div class="hp-post-card__body" style="padding: 15px;">
                                                    <div class="hp-post-card__meta" style="font-size: 11px;">
                                                        <i class="fa fa-calendar-o"></i> {{ $rDateFormatted }}
                                                    </div>
                                                    <h4 class="hp-post-card__title"
                                                        style="font-size: 15px; margin-bottom: 0;">
                                                        <a href="{{ $rUrl }}">{{ Str::limit($rTitle, 50) }}</a>
                                                    </h4>
                                                </div>
                                            </article>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Sidebar (4) -->
                    <div class="uk-width-large-1-3">
                        <aside class="hp-sidebar">
                            <!-- Search -->
                            <div class="hp-sidebar-widget">
                                <h4 class="hp-sidebar-title">Tìm kiếm</h4>
                                <form
                                    action="{{ route('post.catalogue.index', ['canonical' => $postCatalogue->canonical]) }}"
                                    method="GET" class="hp-sidebar-search">
                                    <input type="text" name="keyword" placeholder="Nhập từ khóa...">
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
                                    <li>
                                        <a href="{{ url($rootCanonical) }}">Tất cả bài viết</a>
                                    </li>
                                    @if (isset($categories))
                                        @foreach ($categories as $cat)
                                            <li class="{{ $postCatalogue->id == $cat->id ? 'active' : '' }}">
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
