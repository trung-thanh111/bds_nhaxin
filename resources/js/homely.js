/**
 * HomePark — Frontend Interactions
 */
(function () {
    'use strict';

    const header = document.getElementById('hm-header');
    if (header) {
        window.addEventListener('scroll', () => {
            header.classList.toggle('scrolled', window.scrollY > 50);
        });
    }
    const hamburger = document.getElementById('hm-hamburger');
    const mobileNav = document.getElementById('hm-mobile-nav');
    const overlay = document.getElementById('hm-overlay');
    const mobileClose = document.getElementById('hm-mobile-close');

    function openMobileNav() {
        mobileNav?.classList.add('open');
        overlay?.classList.add('active');
        document.body.style.overflow = 'hidden';
    }
    function closeMobileNav() {
        mobileNav?.classList.remove('open');
        overlay?.classList.remove('active');
        document.body.style.overflow = '';
    }

    hamburger?.addEventListener('click', openMobileNav);
    mobileClose?.addEventListener('click', closeMobileNav);
    overlay?.addEventListener('click', closeMobileNav);

    document.querySelectorAll('[data-tab-group]').forEach(group => {
        const groupName = group.dataset.tabGroup;
        const tabs = group.querySelectorAll('[data-tab]');
        const contents = document.querySelectorAll(`[data-tab-content="${groupName}"]`);

        tabs.forEach(tab => {
            tab.addEventListener('click', () => {
                tabs.forEach(t => t.classList.remove('active'));
                tab.classList.add('active');

                const target = tab.dataset.tab;
                contents.forEach(c => {
                    c.classList.toggle('active', c.dataset.tabId === target);
                });
            });
        });
    });

    document.querySelectorAll('.hm-gallery-page__filter').forEach(btn => {
        btn.addEventListener('click', () => {
            document.querySelectorAll('.hm-gallery-page__filter').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');

            const cat = btn.dataset.category;
            document.querySelectorAll('.hm-gallery-page__item').forEach(item => {
                if (cat === 'all' || item.dataset.category === cat) {
                    item.style.display = '';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    });
    const lightbox = document.getElementById('hm-lightbox');
    const lightboxImg = document.getElementById('hm-lightbox-img');
    const lightboxClose = document.getElementById('hm-lightbox-close');

    document.querySelectorAll('[data-lightbox]').forEach(trigger => {
        trigger.addEventListener('click', (e) => {
            e.preventDefault();
            const src = trigger.dataset.lightbox || trigger.querySelector('img')?.src;
            if (src && lightbox && lightboxImg) {
                lightboxImg.src = src;
                lightbox.classList.add('active');
                document.body.style.overflow = 'hidden';
            }
        });
    });

    lightboxClose?.addEventListener('click', closeLightbox);
    lightbox?.addEventListener('click', (e) => {
        if (e.target === lightbox) closeLightbox();
    });

    function closeLightbox() {
        lightbox?.classList.remove('active');
        document.body.style.overflow = '';
    }

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            closeLightbox();
            closeMobileNav();
        }
    });


    document.querySelectorAll('.hm-marquee__track').forEach(track => {
        const items = track.innerHTML;
        track.innerHTML = items + items;
    });


    const scheduleForm = document.getElementById('hm-schedule-form');
    if (scheduleForm) {
        scheduleForm.addEventListener('submit', function (e) {
            e.preventDefault();
            const formData = new FormData(this);
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.textContent;
            submitBtn.textContent = 'Đang gửi...';
            submitBtn.disabled = true;

            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
                    'Accept': 'application/json'
                }
            })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        this.style.display = 'none';
                        const success = document.getElementById('hm-schedule-success');
                        if (success) success.style.display = 'block';
                    } else {
                        alert(data.message || 'Có lỗi xảy ra. Vui lòng thử lại.');
                    }
                })
                .catch(() => alert('Có lỗi xảy ra. Vui lòng thử lại.'))
                .finally(() => {
                    submitBtn.textContent = originalText;
                    submitBtn.disabled = false;
                });
        });
    }

    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                e.preventDefault();
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    });

    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('[data-uk-lightbox]').forEach(function (link) {
            var href = link.getAttribute('href');
            if (!href) return;
            var idMatch = href.match(/(?:youtu\.be\/|youtube\.com\/(?:watch\?v=|embed\/))([\w-]+)/);
            if (idMatch) {
                link.setAttribute('href', 'https://www.youtube.com/embed/' + idMatch[1]);
                link.setAttribute('data-lightbox-type', 'iframe');
            }
        });
    });

})();
