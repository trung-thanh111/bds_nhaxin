<footer class="hp-footer">
    <div class="uk-container uk-container-center">
        <div class="uk-grid uk-grid-large" data-uk-grid-margin>
            <div class="uk-width-large-1-3 uk-width-medium-1-2">
                @if (isset($menu['footer-menu'][2]))
                    <h4 class="hp-footer-title">{{ $menu['footer-menu'][2]['item']->languages->first()->pivot->name }}
                    </h4>
                    <ul class="hp-footer-links">
                        @foreach ($menu['footer-menu'][2]['children'] as $child)
                            <li class="hp-footer-link">
                                <a href="{{ write_url($child['item']->languages->first()->pivot->canonical) }}">
                                    {{ $child['item']->languages->first()->pivot->name }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
            <div class="uk-width-large-1-3 uk-width-medium-1-2">
                @if (isset($menu['footer-menu'][0]))
                    <h4 class="hp-footer-title">{{ $menu['footer-menu'][0]['item']->languages->first()->pivot->name }}
                    </h4>
                    <ul class="hp-footer-links">
                        @foreach ($menu['footer-menu'][0]['children'] as $child)
                            <li class="hp-footer-link">
                                <span>{!! $child['item']->languages->first()->pivot->name !!}</span>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
            <div class="uk-width-large-1-3 uk-width-medium-1-2">
                <h4 class="hp-footer-title">Mạng xã hội</h4>
                <div class="hp-footer-socials">
                    @if (!empty($system['social_facebook']))
                        <a href="{{ $system['social_facebook'] }}" target="_blank" class="social-icon facebook">
                            <i class="fa fa-facebook"></i>
                        </a>
                    @endif
                    @if (!empty($system['social_youtube']))
                        <a href="{{ $system['social_youtube'] }}" target="_blank" class="social-icon youtube">
                            <i class="fa fa-youtube"></i>
                        </a>
                    @endif
                    @if (!empty($system['social_zalo']))
                        <a href="{{ $system['social_zalo'] }}" target="_blank" class="social-icon zalo">
                            <i class="fa fa-zalo"></i>
                        </a>
                    @endif
                    @if (!empty($system['social_messenger']))
                        <a href="{{ $system['social_messenger'] }}" target="_blank" class="social-icon messenger">
                            <i class="fa fa-messenger"></i>
                        </a>
                    @endif

                    @if (!empty($system['social_twitter']))
                        <a href="{{ $system['social_twitter'] }}" target="_blank" class="social-icon twitter">
                            <i class="fa fa-twitter"></i>
                        </a>
                    @endif
                    @if (!empty($system['social_instagram']))
                        <a href="{{ $system['social_instagram'] }}" target="_blank" class="social-icon instagram">
                            <i class="fa fa-instagram"></i>
                        </a>
                    @endif
                </div>
            </div>
        </div>
        <div class="hp-footer-bottom">
            <div class="footer-bottom-info">
                <img src="{{ $system['homepage_logo'] ?? asset('frontend/resources/img/homely/logo.webp') }}"
                    alt="Logo" class="footer-logo-bottom">
                <p class="footer-contact-info">
                    Email: <span>{{ $system['contact_email'] ?? 'bdsguland@gmail.com' }}</span>
                    - Hotline CSKH:
                    <span>{{ get_hotline($agent ?? null, $system['contact_hotline'] ?? '098.328.4379') }}</span>
                </p>
                <p class="footer-disclaimer">
                    Guland.vn có trách nhiệm chuyển tải thông tin. Mọi thông tin chỉ có giá trị tham khảo.
                    Chúng tôi không chịu trách nhiệm từ các tin đăng và thông tin quy hoạch được đăng tải trên trang
                    này.
                </p>
            </div>
        </div>
    </div>
</footer>

@include('frontend.component.floating-social')
