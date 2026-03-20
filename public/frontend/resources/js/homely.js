document.addEventListener('DOMContentLoaded', function () {

    if (document.querySelector('.homely-hero-swiper')) {
        let slideSettings = {
            loop: true,
            effect: 'fade',
            speed: 1000
        };

        if (window.HomelyHeroSettings) {
            const dbSettings = window.HomelyHeroSettings;
            if (dbSettings.animation) slideSettings.effect = dbSettings.animation;
            if (dbSettings.autoplay === 'accept') {
                let delay = dbSettings.animationDelay ? parseFloat(dbSettings.animationDelay) * 1000 : 5000;
                slideSettings.autoplay = {
                    delay: delay,
                    disableOnInteraction: dbSettings.pauseHover !== 'accept'
                };
            }
        } else {
            slideSettings.autoplay = {
                delay: 3500,
                disableOnInteraction: false
            };
        }

        new Swiper('.homely-hero-swiper', slideSettings);
    }

    if (document.querySelector('.homely-gallery-swiper')) {
        new Swiper('.homely-gallery-swiper', {
            slidesPerView: 'auto',
            spaceBetween: 30,
            centeredSlides: true,
            loop: true,
            grabCursor: true,
            navigation: {
                nextEl: '.gallery-next',
                prevEl: '.gallery-prev',
            }
        });
    }

    const progressBar = document.getElementById('scroll-progress');
    if (progressBar) {
        window.addEventListener('scroll', function () {
            const scrollTop = window.scrollY;
            const docHeight = document.documentElement.scrollHeight - window.innerHeight;
            const scrollPercent = docHeight > 0 ? (scrollTop / docHeight) * 100 : 0;
            progressBar.style.width = scrollPercent + '%';
        }, {
            passive: true
        });
    }

    const revealObserver = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
            if (entry.isIntersecting) {
                entry.target.classList.add('is-visible');
                const counterEl = entry.target.querySelector('[data-counter]');
                if (counterEl) animateCounter(counterEl);
                revealObserver.unobserve(entry.target);
            }
        });
    }, {
        threshold: 0.15,
        rootMargin: '0px 0px -50px 0px'
    });

    document.querySelectorAll('[data-reveal], .homely-section-title, .homely-section-label, .homely-about-desc').forEach(function (el) {
        revealObserver.observe(el);
    });

    const specListObserver = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
            if (entry.isIntersecting) {
                entry.target.querySelectorAll('.homely-spec-item').forEach(function (item) {
                    specItemObserver.observe(item);
                });
                specListObserver.unobserve(entry.target);
            }
        });
    }, {
        threshold: 0.1
    });

    const specItemObserver = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
            if (entry.isIntersecting) {
                entry.target.classList.add('is-visible');
                specItemObserver.unobserve(entry.target);
            }
        });
    }, {
        threshold: 0.2
    });

    const specList = document.querySelector('.homely-spec-list');
    if (specList) specListObserver.observe(specList);

    document.querySelectorAll('.homely-location-tabs a').forEach(function (tabLink) {
        tabLink.addEventListener('click', function () {
            setTimeout(function () {
                document.querySelectorAll('#location-tabs .uk-active [data-reveal]').forEach(function (card) {
                    card.classList.remove('is-visible');
                    revealObserver.observe(card);
                });
            }, 50);
        });
    });

    function animateCounter(el) {
        const target = parseInt(el.getAttribute('data-counter'), 10);
        const suffix = el.getAttribute('data-suffix') || '';
        const duration = 1600;
        const startTime = performance.now();

        if (isNaN(target)) return;

        function update(currentTime) {
            const elapsed = currentTime - startTime;
            const progress = Math.min(elapsed / duration, 1);
            const eased = progress === 1 ? 1 : 1 - Math.pow(2, -10 * progress);
            const current = Math.floor(eased * target);
            el.textContent = current.toLocaleString('vi-VN') + suffix;
            if (progress < 1) requestAnimationFrame(update);
        }

        requestAnimationFrame(update);
    }

    const counterObserver = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
            if (entry.isIntersecting) {
                animateCounter(entry.target);
                counterObserver.unobserve(entry.target);
            }
        });
    }, {
        threshold: 0.5
    });

    document.querySelectorAll('[data-counter]').forEach(function (el) {
        counterObserver.observe(el);
    });

    var form = document.getElementById('visit-request-form');
    if (form) {
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            var submitBtn = form.querySelector('.homely-btn-submit');
            if (submitBtn) {
                const originalText = submitBtn.textContent;
                submitBtn.textContent = 'Đang gửi...';
                submitBtn.disabled = true;
            }

            var formData = new FormData(form);
            // Use global variables or attributes for URL/Token
            const actionUrl = form.getAttribute('action') || (window.HomelyConfig ? window.HomelyConfig.visitRequestUrl : '');
            const csrfToken = form.querySelector('input[name="_token"]')?.value || (window.HomelyConfig ? window.HomelyConfig.csrfToken : '');

            $.ajax({
                url: actionUrl,
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                success: function (res) {
                    form.style.transition = 'opacity 0.5s, transform 0.5s';
                    form.style.opacity = '0';
                    form.style.transform = 'translateY(-10px)';
                    setTimeout(function () {
                        form.style.display = 'none';
                        var successEl = document.getElementById('visit-form-success');
                        if (successEl) {
                            successEl.style.display = 'block';
                            successEl.style.opacity = '0';
                            successEl.style.transform = 'translateY(10px)';
                            successEl.style.transition = 'opacity 0.5s, transform 0.5s';
                            setTimeout(function () {
                                successEl.style.opacity = '1';
                                successEl.style.transform = 'translateY(0)';
                            }, 50);
                        }
                    }, 400);
                    if (typeof toastr !== 'undefined') toastr.success(res.message || 'Yêu cầu đã được ghi nhận.');
                },
                error: function () {
                    if (submitBtn) {
                        submitBtn.textContent = 'Gửi yêu cầu';
                        submitBtn.disabled = false;
                    }
                    if (typeof toastr !== 'undefined') toastr.error('Có lỗi xảy ra, vui lòng thử lại.');
                }
            });
        });
    }

    document.querySelectorAll('.homely-input').forEach(function (input) {
        input.addEventListener('focus', function () {
            this.style.transition = 'border-color 0.3s, box-shadow 0.3s, transform 0.2s';
        });
    });

});
