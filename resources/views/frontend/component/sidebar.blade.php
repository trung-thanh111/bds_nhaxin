@php
    $offcanvasProperty = $property ?? \App\Models\Property::where('publish', 2)->orderBy('id', 'desc')->first();
    $offcanvasGalleryImages = [];
    if (isset($galleries) && count($galleries) > 0) {
        foreach ($galleries as $gallery) {
            if (!empty($gallery->album) && is_array($gallery->album)) {
                $offcanvasGalleryImages = array_merge($offcanvasGalleryImages, $gallery->album);
            }
        }
    } elseif ($offcanvasProperty) {
        $galleryRecord = \App\Models\Gallery::where('property_id', $offcanvasProperty->id)->first();
        if ($galleryRecord && !empty($galleryRecord->album) && is_array($galleryRecord->album)) {
            $offcanvasGalleryImages = $galleryRecord->album;
        } else {
            $fallbackGallery = \App\Models\Gallery::whereNotNull('album')->where('publish', 2)->first();
            if ($fallbackGallery && !empty($fallbackGallery->album) && is_array($fallbackGallery->album)) {
                $offcanvasGalleryImages = $fallbackGallery->album;
            }
        }
    }
    $offcanvasGalleryImages = array_slice($offcanvasGalleryImages, 0, 6);
@endphp

<div id="offcanvas-desktop" class="uk-offcanvas" uk-offcanvas="overlay: true">
    <div class="uk-offcanvas-bar hp-offcanvas-bar">
        <a class="uk-offcanvas-close hp-offcanvas-close">
            <i class="fa fa-times"></i>
        </a>

        <div class="hp-offcanvas-logo" style="margin-bottom: 30px;">
            <a href="/">
                <img src="{{ $system['homepage_logo'] ?? asset('frontend/resources/img/homely/logo.webp') }}"
                    alt="Logo" />
            </a>
        </div>

        <div class="hp-offcanvas-desc">
            @if ($offcanvasProperty && !empty($offcanvasProperty->description_short))
                {!! $offcanvasProperty->description_short !!}
            @else
                Chúng tôi tự hào mang đến không gian sống đẳng cấp và khác biệt. Homepark là nơi khởi đầu cho tổ ấm lý
                tưởng của bạn với những giá trị bền vững và thiết kế tinh tế.
            @endif
        </div>

        <div class="hp-offcanvas-gallery">
            @if (count($offcanvasGalleryImages) > 0)
                @foreach ($offcanvasGalleryImages as $img)
                    <img src="{{ asset($img) }}" alt="Gallery Image">
                @endforeach
            @else
                <img src="{{ asset('frontend/resources/img/homely/gallery/1.webp') }}" alt="Gallery 1">
                <img src="{{ asset('frontend/resources/img/homely/gallery/2.webp') }}" alt="Gallery 2">
                <img src="{{ asset('frontend/resources/img/homely/gallery/3.webp') }}" alt="Gallery 3">
                <img src="{{ asset('frontend/resources/img/homely/gallery/4.webp') }}" alt="Gallery 4">
                <img src="{{ asset('frontend/resources/img/homely/gallery/5.webp') }}" alt="Gallery 5">
                <img src="{{ asset('frontend/resources/img/homely/slider/2.webp') }}" alt="Gallery 6">
            @endif
        </div>

        <div class="hp-offcanvas-contact">
            @if ($offcanvasProperty)
                <div style="font-size: 16px; margin-bottom: 8px;"><strong>Giá:</strong>
                    {{ number_format($offcanvasProperty->price ?? 0, 0, ',', '.') }}
                    {{ $offcanvasProperty->price_unit }}</div>
                <div style="font-size: 14px; margin-bottom: 8px;"><strong>Diện tích:</strong>
                    {{ $offcanvasProperty->area_sqm }} m²</div>
                <div style="font-size: 14px; margin-bottom: 8px; line-height: 1.5;"><strong>Địa chỉ:</strong>
                    {{ $offcanvasProperty->address }}</div>
            @else
                <div>Địa chỉ: C. Stalingrada Avenue, 6 Vilnius</div>
                <a href="tel:{{ $system['contact_hotline'] ?? '+380(98)298-59-73' }}">
                    {{ $system['contact_hotline'] ?? '+380(98)298-59-73' }}
                </a>
                <a href="mailto:{{ $system['contact_email'] ?? 'hello@homepark.com.ua' }}">
                    {{ $system['contact_email'] ?? 'hello@homepark.com.ua' }}
                </a>
            @endif
        </div>

        <div class="hp-offcanvas-social">
            @if (!empty($system['social_facebook']))
                <a href="{{ $system['social_facebook'] }}"><i class="fa fa-facebook"></i></a>
            @endif
            @if (!empty($system['social_twitter']))
                <a href="{{ $system['social_twitter'] }}"><i class="fa fa-twitter"></i></a>
            @endif
            @if (!empty($system['social_instagram']))
                <a href="{{ $system['social_instagram'] }}"><i class="fa fa-instagram"></i></a>
            @endif
            @if (!empty($system['social_linkedin']))
                <a href="{{ $system['social_linkedin'] }}"><i class="fa fa-linkedin"></i></a>
            @endif
            @if (!empty($system['social_youtube']))
                <a href="{{ $system['social_youtube'] }}"><i class="fa fa-youtube"></i></a>
            @endif
            @if (!empty($system['social_tiktok']))
                <a href="{{ $system['social_tiktok'] }}"><i class="fa fa-tiktok"></i></a>
            @endif
        </div>

        <div style="margin-top: auto; font-size: 11px; opacity: 0.5;">
            {{ $system['copyright'] ?? '' }}
        </div>
    </div>
</div>


<div id="offcanvas-mobile" class="uk-offcanvas" uk-offcanvas="overlay: true">
    <div class="uk-offcanvas-bar hp-offcanvas-bar">
        <a class="uk-offcanvas-close hp-offcanvas-close">
            <i class="fa fa-times"></i>
        </a>

        <div class="hp-offcanvas-logo" style="margin-bottom: 30px;">
            <a href="/">
                <img src="{{ $system['homepage_logo'] ?? asset('frontend/resources/img/homely/logo.webp') }}"
                    alt="Logo" />
            </a>
        </div>

        <nav class="hp-offcanvas-nav uk-margin-large-bottom">
            <ul class="uk-nav uk-nav-offcanvas" data-uk-nav>
                {!! $menu['main-menu'] ?? '' !!}
            </ul>
        </nav>

        <div class="hp-offcanvas-contact hp-border-top uk-padding-top">
            <div
                style="font-family: var(--font-accent); font-size: 11px; font-weight: 700; color: var(--color-primary); margin-bottom: 15px; text-transform: uppercase; letter-spacing: 2px;">
                Liên hệ
            </div>
            <div style="font-size: 14px; margin-bottom: 10px; opacity: 0.8;">
                {{ $system['contact_address'] ?? '742 Evergreen Terrace, Quận 7, TP. HCM' }}
            </div>
            <a href="tel:{{ $system['contact_hotline'] ?? '+380(98)298-59-73' }}"
                style="font-size: 18px; font-weight: 700; margin-bottom: 5px;">
                {{ $system['contact_hotline'] ?? '+380(98)298-59-73' }}
            </a>
            <a href="mailto:{{ $system['contact_email'] ?? 'hello@homepark.com.ua' }}"
                style="font-size: 14px; opacity: 0.8;">
                {{ $system['contact_email'] ?? 'hello@homepark.com.ua' }}
            </a>
        </div>

        <div class="hp-offcanvas-social uk-margin-top">
            @if (!empty($system['social_facebook']))
                <a href="{{ $system['social_facebook'] }}"><i class="fa fa-facebook"></i></a>
            @endif
            @if (!empty($system['social_instagram']))
                <a href="{{ $system['social_instagram'] }}"><i class="fa fa-instagram"></i></a>
            @endif
            @if (!empty($system['social_youtube']))
                <a href="{{ $system['social_youtube'] }}"><i class="fa fa-youtube-play"></i></a>
            @endif
        </div>

        <div style="margin-top: auto; font-size: 11px; opacity: 0.5; padding-top: 20px;">
            {{ $system['copyright'] ?? '' }}
        </div>
    </div>
</div>
