<header class="hp-header @yield('header-class')" id="hp-header">
    <div class="hp-header-top">
        <div class="uk-container uk-container-center">
            <div class="uk-grid uk-grid-collapse uk-flex-middle">
                <div class="uk-width-1-2 uk-width-large-1-4">
                    <div class="logo">
                        <a href="/" title="logo">
                            <img src="{{ $system['homepage_logo'] ?? asset('frontend/resources/img/homely/logo.webp') }}"
                                alt="logo" style="max-height: 45px;">
                        </a>
                    </div>
                </div>

                <div class="uk-width-1-2 uk-width-large-3-4 uk-text-right uk-flex uk-flex-middle uk-flex-right">
                    <div class="hp-contact-info uk-margin-large-right uk-visible-large">
                        <div style="font-weight: 700; font-size: 16px;">
                            {{ $system['contact_hotline'] ?? '+380(98)298-59-73' }}</div>
                        <div style="opacity: 0.6; font-size: 11px;">
                            {{ $system['contact_email'] ?? 'hello@homepark.com.ua' }}</div>
                    </div>

                    <a class="hp-hamburger uk-visible-large" href="#offcanvas-desktop"
                        data-uk-offcanvas="{target:'#offcanvas-desktop'}"
                        style="color: var(--color-white); display: flex; align-items: center; justify-content: flex-end;">
                        <i class="fa fa-bars" style="font-size: 26px;"></i>
                    </a>

                    <a class="hp-hamburger uk-hidden-large" href="#offcanvas-mobile"
                        data-uk-offcanvas="{target:'#offcanvas-mobile'}"
                        style="color: var(--color-white); display: flex; align-items: center; justify-content: flex-end;">
                        <i class="fa fa-bars" style="font-size: 26px;"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="hp-header-nav uk-visible-large">
        <div class="uk-container uk-container-center">
            <nav class="uk-navbar uk-flex uk-flex-middle uk-flex-space-between" style="width: 100%;">
                <div class="hp-nav-logo">
                    <a href="/" title="logo">
                        <img src="{{ $system['homepage_logo'] ?? asset('frontend/resources/img/homely/logo.webp') }}"
                            alt="logo" style="max-height: 35px;">
                    </a>
                </div>
                <ul class="uk-navbar-nav">
                    {!! $menu['main-menu'] ?? '' !!}
                </ul>
            </nav>
        </div>
    </div>
</header>

<script>
    window.addEventListener('scroll', function() {
        const headerTop = document.querySelector('.hp-header-top');
        const headerNav = document.querySelector('.hp-header-nav');
        if (window.scrollY > 100) {
            headerNav.classList.add('hp-header-nav--sticky');
        } else {
            headerNav.classList.remove('hp-header-nav--sticky');
        }
    });
</script>

@include('frontend.component.sidebar')
