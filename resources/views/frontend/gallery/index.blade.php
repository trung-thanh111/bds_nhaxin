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
                        <h1 class="hp-luxury-header__title">Thư viện ảnh</h1>
                        <p class="hp-luxury-header__desc hp-hero-subtitle-main">
                            Thư viện ảnh của {{ $property->title ?? 'dự án' }}.
                        </p>
                    </div>
                    <div class="hp-luxury-breadcrumb" data-reveal="left">
                        <div class="content-breadcrumb">
                            <a href="{{ route('home.index') }}">Trang chủ</a>
                            <span class="separator">»</span>
                            <span class="current">Thư viện ảnh</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="ln-gallery-page">
            <div class="uk-container uk-container-center">
                @php
                    $allImages = collect();
                    $catalogueImages = [];

                    if (isset($galleryCatalogues) && $galleryCatalogues->count() > 0) {
                        foreach ($galleryCatalogues as $catalogue) {
                            $catName = $catalogue->languages->first()->pivot->name ?? 'Không tên';
                            $catalogueImages[$catName] = collect();

                            if ($catalogue->galleries->count() > 0) {
                                foreach ($catalogue->galleries as $gallery) {
                                    if (is_array($gallery->album)) {
                                        foreach ($gallery->album as $img) {
                                            $catalogueImages[$catName]->push(['url' => $img, 'name' => $catName]);
                                            $allImages->push(['url' => $img, 'name' => $catName]);
                                        }
                                    }
                                }
                            }
                        }
                    } else {
                        if ($galleries->count() > 0) {
                            foreach ($galleries as $gallery) {
                                if (is_array($gallery->album)) {
                                    foreach ($gallery->album as $img) {
                                        $allImages->push(['url' => $img, 'name' => 'Tất Cả']);
                                    }
                                }
                            }
                        }
                    }
                @endphp

                <ul class="uk-subnav ln-gallery-page__tabs" data-uk-switcher="{connect:'#gallery-tabs'}" data-reveal="up">
                    <li><a href="#">Tất Cả ({{ $allImages->count() }})</a></li>
                    @foreach ($catalogueImages as $catName => $images)
                        @if ($images->count() > 0)
                            <li><a href="#">{{ $catName }} ({{ $images->count() }})</a></li>
                        @endif
                    @endforeach
                </ul>

                <ul id="gallery-tabs" class="uk-switcher">

                    <li>
                        <div class="hp-gallery-mosaic">
                            @if ($allImages->count() > 0)
                                @foreach ($allImages as $img)
                                    <a href="{{ $img['url'] }}" class="hp-gallery-mosaic__item"
                                        data-fancybox="gallery-all" data-caption="{{ $img['name'] }}" data-reveal="up">
                                        <img src="{{ $img['url'] }}" alt="{{ $img['name'] }}" loading="lazy">
                                        <div class="hp-gallery-mosaic__overlay">
                                            <i class="fa fa-expand"></i>
                                        </div>
                                    </a>
                                @endforeach
                            @else
                                @for ($i = 1; $i <= 8; $i++)
                                    <a href="{{ asset('frontend/resources/img/homely/gallery/' . $i . '.webp') }}"
                                        class="hp-gallery-mosaic__item" data-fancybox="gallery-all" data-reveal="up">
                                        <img src="{{ asset('frontend/resources/img/homely/gallery/' . $i . '.webp') }}"
                                            alt="Gallery {{ $i }}" loading="lazy">
                                        <div class="hp-gallery-mosaic__overlay">
                                            <i class="fa fa-expand"></i>
                                        </div>
                                    </a>
                                @endfor
                            @endif
                        </div>
                    </li>


                    @foreach ($catalogueImages as $catName => $images)
                        @if ($images->count() > 0)
                            <li>
                                <div class="hp-gallery-mosaic">
                                    @foreach ($images as $img)
                                        <a href="{{ $img['url'] }}" class="hp-gallery-mosaic__item"
                                            data-fancybox="gallery-{{ Str::slug($catName) }}"
                                            data-caption="{{ $img['name'] }}" data-reveal="up">
                                            <img src="{{ $img['url'] }}" alt="{{ $img['name'] }}" loading="lazy">
                                            <div class="hp-gallery-mosaic__overlay">
                                                <i class="fa fa-expand"></i>
                                            </div>
                                        </a>
                                    @endforeach
                                </div>
                            </li>
                        @endif
                    @endforeach
                </ul>
            </div>
        </section>

    </div>
@endsection
