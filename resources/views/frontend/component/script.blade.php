<script src="{{ asset('vendor/backend/js/plugins/select2/select2.full.min.js') }}"></script>
<script src="{{ asset('vendor/backend/js/plugins/toastr/toastr.min.js') }}"></script>

<script src="{{ asset('vendor/frontend/uikit/js/uikit.min.js') }}"></script>
<script src="{{ asset('vendor/frontend/uikit/js/components/sticky.min.js') }}"></script>
<script src="{{ asset('vendor/frontend/uikit/js/components/accordion.min.js') }}"></script>
<script src="{{ asset('vendor/frontend/uikit/js/components/lightbox.min.js') }}"></script>

<script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.umd.js"></script>
<script src="{{ asset('frontend/resources/plugins/wow/dist/wow.min.js') }}"></script>
<script src="{{ asset('frontend/resources/function.js') }}"></script>
<script src="{{ asset('frontend/resources/js/linden.js') }}"></script>

@if (isset($config['js']) && is_array($config['js']))
    @foreach ($config['js'] as $key => $val)
        {!! '<script src="' . asset($val) . '"></script>' !!}
    @endforeach
@endif
<script>
    document.addEventListener("DOMContentLoaded", function() {
        if (typeof Fancybox !== 'undefined') {
            Fancybox.bind("[data-fancybox]", {});
        }

        // Back to top logic
        const backToTop = document.getElementById('hp-back-to-top');
        if (backToTop) {
            window.addEventListener('scroll', function() {
                if (window.pageYOffset > 300) {
                    backToTop.classList.add('active');
                } else {
                    backToTop.classList.remove('active');
                }
            });

            backToTop.addEventListener('click', function() {
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            });
        }
    });
</script>
