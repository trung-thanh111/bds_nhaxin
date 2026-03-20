document.addEventListener('DOMContentLoaded', function () {

    if (document.querySelector('.ln-hero-swiper')) {
        var slideSettings = {
            loop: true,
            effect: 'fade',
            speed: 1200,
            autoplay: { delay: 5000, disableOnInteraction: false }
        };
        if (window.LindenHeroSettings) {
            var s = window.LindenHeroSettings;
            if (s.animation) slideSettings.effect = s.animation;
            if (s.autoplay === 'accept') {
                slideSettings.autoplay = {
                    delay: s.animationDelay ? parseFloat(s.animationDelay) * 1000 : 5000,
                    disableOnInteraction: s.pauseHover !== 'accept'
                };
            }
        }
        new Swiper('.ln-hero-swiper', slideSettings);
    }

    if (document.querySelector('.ln-gallery-swiper')) {
        new Swiper('.ln-gallery-swiper', {
            slidesPerView: 'auto',
            spaceBetween: 30,
            centeredSlides: true,
            loop: true,
            grabCursor: true,
            navigation: { nextEl: '.gallery-next', prevEl: '.gallery-prev' }
        });
    }

    if (document.querySelector('.ln-amenity-swiper')) {
        new Swiper('.ln-amenity-swiper', {
            slidesPerView: 1,
            spaceBetween: 0,
            loop: true,
            speed: 800,
            autoplay: { delay: 4000, disableOnInteraction: false },
            navigation: { nextEl: '.amenity-next', prevEl: '.amenity-prev' }
        });
    }

    if (document.querySelector('.ln-building-swiper')) {
        new Swiper('.ln-building-swiper', {
            slidesPerView: 1,
            spaceBetween: 0,
            loop: true,
            speed: 800,
            autoplay: { delay: 4000, disableOnInteraction: false },
            navigation: { nextEl: '.building-next', prevEl: '.building-prev' }
        });
    }

    if (document.querySelector('.ln-interior-swiper')) {
        new Swiper('.ln-interior-swiper', {
            slidesPerView: 1,
            spaceBetween: 0,
            loop: true,
            speed: 800,
            navigation: { nextEl: '.interior-next', prevEl: '.interior-prev' }
        });
    }

    if (document.querySelector('.ln-location-swiper')) {
        new Swiper('.ln-location-swiper', {
            slidesPerView: 4.5,
            spaceBetween: 20,
            grabCursor: true,
            navigation: { nextEl: '.loc-next', prevEl: '.loc-prev' },
            breakpoints: {
                0: { slidesPerView: 1.2 },
                640: { slidesPerView: 2.2 },
                960: { slidesPerView: 3.5 },
                1200: { slidesPerView: 4.5 }
            }
        });
    }

    var progressBar = document.getElementById('scroll-progress');
    if (progressBar) {
        window.addEventListener('scroll', function () {
            var scrollTop = window.scrollY;
            var docHeight = document.documentElement.scrollHeight - window.innerHeight;
            progressBar.style.width = (docHeight > 0 ? (scrollTop / docHeight) * 100 : 0) + '%';
        }, { passive: true });
    }

    var header = document.querySelector('.linden-header');
    if (header && !header.classList.contains('header-inner')) {
        window.addEventListener('scroll', function () {
            header.classList.toggle('scrolled', window.scrollY > 50);
        }, { passive: true });
    }

    var backToTop = document.getElementById('back-to-top');
    if (backToTop) {
        window.addEventListener('scroll', function () {
            backToTop.classList.toggle('visible', window.scrollY > 400);
        }, { passive: true });
        backToTop.addEventListener('click', function () {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    }

    var revealObserver = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
            if (entry.isIntersecting) {
                entry.target.classList.add('is-visible');
                var counter = entry.target.querySelector('[data-counter]');
                if (counter) animateCounter(counter);
                revealObserver.unobserve(entry.target);
            }
        });
    }, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });

    document.querySelectorAll('[data-reveal], .ln-section-title, .ln-label').forEach(function (el) {
        revealObserver.observe(el);
    });

    var groupObserver = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
            if (entry.isIntersecting) {
                var children = entry.target.querySelectorAll('[data-reveal]');
                children.forEach(function (child, i) {
                    setTimeout(function () { child.classList.add('is-visible'); }, i * 100);
                });
                groupObserver.unobserve(entry.target);
            }
        });
    }, { threshold: 0.1 });

    document.querySelectorAll('[data-reveal-group]').forEach(function (g) {
        groupObserver.observe(g);
    });

    var floatImg = document.querySelector('.ln-about__img-secondary');
    if (floatImg) {
        window.addEventListener('scroll', function () {
            var rect = floatImg.closest('.ln-about').getBoundingClientRect();
            var viewH = window.innerHeight;
            if (rect.top < viewH && rect.bottom > 0) {
                var progress = (viewH - rect.top) / (viewH + rect.height);
                floatImg.style.transform = 'translateY(' + ((progress - 0.5) * -40) + 'px)';
            }
        }, { passive: true });
    }

    function animateCounter(el) {
        var target = parseInt(el.getAttribute('data-counter'), 10);
        var suffix = el.getAttribute('data-suffix') || '';
        var duration = 1600;
        var startTime = performance.now();
        if (isNaN(target)) return;
        function update(currentTime) {
            var elapsed = currentTime - startTime;
            var progress = Math.min(elapsed / duration, 1);
            var eased = progress === 1 ? 1 : 1 - Math.pow(2, -10 * progress);
            el.textContent = Math.floor(eased * target).toLocaleString('vi-VN') + suffix;
            if (progress < 1) requestAnimationFrame(update);
        }
        requestAnimationFrame(update);
    }

    var counterObserver = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
            if (entry.isIntersecting) {
                animateCounter(entry.target);
                counterObserver.unobserve(entry.target);
            }
        });
    }, { threshold: 0.5 });

    document.querySelectorAll('[data-counter]').forEach(function (el) {
        counterObserver.observe(el);
    });

    document.querySelectorAll('form[id="visit-request-form"]').forEach(function (form) {
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            var submitBtn = form.querySelector('.ln-btn-submit');
            var originalText = submitBtn ? submitBtn.textContent : '';
            if (submitBtn) { submitBtn.textContent = 'Đang gửi...'; submitBtn.disabled = true; }

            var formData = new FormData(form);
            var actionUrl = form.getAttribute('action') || (window.LindenConfig ? window.LindenConfig.visitRequestUrl : '');
            var csrfToken = form.querySelector('input[name="_token"]');
            csrfToken = csrfToken ? csrfToken.value : (window.LindenConfig ? window.LindenConfig.csrfToken : '');

            $.ajax({
                url: actionUrl,
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: { 'X-CSRF-TOKEN': csrfToken },
                success: function (res) {
                    form.style.transition = 'opacity 0.5s, transform 0.5s';
                    form.style.opacity = '0';
                    form.style.transform = 'translateY(-10px)';
                    setTimeout(function () {
                        form.style.display = 'none';
                        var suc = form.parentElement.querySelector('.visit-form-success');
                        if (suc) {
                            suc.style.display = 'block';
                            suc.style.opacity = '0';
                            suc.style.transform = 'translateY(10px)';
                            suc.style.transition = 'opacity 0.5s, transform 0.5s';
                            setTimeout(function () { suc.style.opacity = '1'; suc.style.transform = 'translateY(0)'; }, 50);
                        }
                    }, 400);
                    if (typeof toastr !== 'undefined') toastr.success(res.message || 'Yêu cầu đã được ghi nhận.');
                },
                error: function () {
                    if (submitBtn) { submitBtn.textContent = originalText || 'Gửi yêu cầu'; submitBtn.disabled = false; }
                    if (typeof toastr !== 'undefined') toastr.error('Có lỗi xảy ra, vui lòng thử lại.');
                }
            });
        });
    });

});
