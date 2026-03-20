<footer class="hp-footer">
    <div class="uk-container uk-container-center">
        <div class="uk-grid" data-uk-grid-margin>

            <div class="uk-width-large-1-4 uk-width-medium-1-2"
                data-uk-scrollspy="{cls:'uk-animation-slide-bottom', delay:100}">
                <div class="hp-footer-logo">
                    <a href="/">
                        <img src="{{ $system['homepage_logo'] ?? asset('frontend/resources/img/homely/logo.webp') }}"
                            alt="Logo">
                    </a>
                </div>
                <p class="hp-footer-desc">
                    {{ $system['homepage_description'] ?? 'Không gian sống sang trọng được thiết kế dành cho cuộc sống hiện đại. Mỗi chi tiết đều được chăm chút tỉ mỉ để mang đến trải nghiệm hoàn hảo.' }}
                </p>
                <div class="hp-footer-socials">
                    @if (!empty($system['social_facebook']))
                        <a href="{{ $system['social_facebook'] }}" target="_blank"><i class="fa fa-facebook"></i></a>
                    @endif
                    @if (!empty($system['social_instagram']))
                        <a href="{{ $system['social_instagram'] }}" target="_blank"><i class="fa fa-instagram"></i></a>
                    @endif
                    @if (!empty($system['social_youtube']))
                        <a href="{{ $system['social_youtube'] }}" target="_blank"><i class="fa fa-youtube-play"></i></a>
                    @endif
                    @if (!empty($system['social_twitter']))
                        <a href="{{ $system['social_twitter'] }}" target="_blank"><i class="fa fa-twitter"></i></a>
                    @endif
                </div>
            </div>


            <div class="uk-width-large-1-4 uk-width-medium-1-2"
                data-uk-scrollspy="{cls:'uk-animation-slide-bottom', delay:300}">
                @if (isset($menu['footer-menu'][2]))
                    <h4 class="hp-footer-title">
                        {{ $menu['footer-menu'][2]['item']->languages->first()->pivot->name }}
                    </h4>
                    @php
                        $addressMenu = $menu['footer-menu'][2];
                    @endphp
                    <ul class="hp-footer-links">
                        @foreach ($addressMenu['children'] as $child)
                            <li class="hp-footer-link">
                                <a href="{{ write_url($child['item']->languages->first()->pivot->canonical) }}">
                                    {{ $child['item']->languages->first()->pivot->name }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <h4 class="hp-footer-title">Địa Chỉ</h4>
                    <ul class="hp-footer-links">
                        <li class="hp-footer-link uk-flex uk-flex-middle hp-gap-10">
                            <i class="fa fa-map-marker text-primary"></i>
                            <span>{{ $system['contact_address'] ?? '742 Evergreen Terrace, Quận 7, TP. HCM' }}</span>
                        </li>
                    </ul>
                @endif
            </div>


            <div class="uk-width-large-1-4 uk-width-medium-1-2"
                data-uk-scrollspy="{cls:'uk-animation-slide-bottom', delay:500}">
                @if (isset($menu['footer-menu'][0]))
                    <h4 class="hp-footer-title">
                        {{ $menu['footer-menu'][0]['item']->languages->first()->pivot->name }}
                    </h4>
                    @php
                        $projectMenu = $menu['footer-menu'][0];
                    @endphp
                    <ul class="hp-footer-links">
                        @foreach ($projectMenu['children'] as $child)
                            <li class="hp-footer-link">
                                <span>{!! $child['item']->languages->first()->pivot->name !!}</span>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <h4 class="hp-footer-title">Dự Án</h4>
                    <ul class="hp-footer-links">
                        <li class="hp-footer-link"><a href="/vi-tri-du-an.html">Vị trí dự án</a></li>
                        <li class="hp-footer-link"><a href="/tien-nghi.html">Tiện ích cao cấp</a></li>
                    </ul>
                @endif
            </div>


            <div class="uk-width-large-1-4 uk-width-medium-1-2"
                data-uk-scrollspy="{cls:'uk-animation-slide-bottom', delay:700}">
                @if (isset($menu['footer-menu'][1]))
                    <h4 class="hp-footer-title">
                        {{ $menu['footer-menu'][1]['item']->languages->first()->pivot->name }}
                    </h4>
                    @php
                        $contactMenu = $menu['footer-menu'][1];
                    @endphp
                    <ul class="hp-footer-links">
                        @foreach ($contactMenu['children'] as $child)
                            <li class="hp-footer-link">
                                <span>{!! $child['item']->languages->first()->pivot->name !!}</span>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <h4 class="hp-footer-title">Liên Hệ</h4>
                    <ul class="hp-footer-links">
                        <li class="hp-footer-link uk-flex uk-flex-middle hp-gap-10">
                            <i class="fa fa-phone text-primary"></i>
                            <span>{{ $system['contact_hotline'] ?? '(+84) 123 456 789' }}</span>
                        </li>
                        <li class="hp-footer-link uk-flex uk-flex-middle hp-gap-10">
                            <i class="fa fa-envelope-o text-primary"></i>
                            <span>{{ $system['contact_email'] ?? 'info@homepark.vn' }}</span>
                        </li>
                    </ul>
                @endif
            </div>
        </div>

        <div class="hp-footer-bottom">
            <div>{!! $system['homepage_copyright'] ?? '© ' . date('Y') . ' Hompark. All rights reserved.' !!}</div>
            <div class="uk-flex uk-flex-middle hp-gap-20 uk-white">
                <a href="#">Privacy Policy</a>
                <a href="#">Terms of Use</a>
            </div>
        </div>
    </div>
</footer>

@include('frontend.component.floating-social')
