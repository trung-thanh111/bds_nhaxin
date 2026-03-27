@extends('frontend.homepage.layout')

@section('content')
    <div class="hp-detail-page">
        {{-- Breadcrumb & Header --}}
        <section class="hp-detail-header">
            <div class="uk-container uk-container-center">
                <ul class="uk-breadcrumb uk-flex uk-flex-middle">
                    <li><a href="{{ url('/') }}">Trang chủ</a></li>
                    @if ($project->catalogue)
                        @php
                            $canonicalCatalogue = url(
                                $project->catalogue->languages->first()->pivot->canonical . '.html',
                            );
                        @endphp
                        <li><a
                                href="{{ $canonicalCatalogue }}">{{ $project->catalogue->languages->first()->pivot->name }}</a>
                        </li>
                    @endif
                    <li class="uk-active"><span>{{ $project->name }}</span></li>
                </ul>
            </div>
        </section>

        <section class="hp-section">
            <div class="uk-container uk-container-center">
                <div class="uk-grid uk-grid-small hp-detail-grid" data-uk-grid-margin>
                    <div class="uk-width-large-7-10 uk-width-medium-2-3">
                        <div class="hp-property-main">
                            <div class="hp-property-gallery">
                                @php
                                    $album = json_decode($project->album, true) ?? [];
                                    $fullAlbum = array_unique(
                                        array_filter(array_merge([$project->cover_image], $album)),
                                    );
                                @endphp

                                <div id="sync1" class="owl-carousel owl-theme">
                                    @foreach ($fullAlbum as $img)
                                        <div class="item">
                                            <a href="{{ asset($img) }}" data-fancybox="gallery" data-type="image"
                                                data-caption="{{ $project->languages->first()->pivot->name ?? '' }}">
                                                <img src="{{ asset($img) }}" alt="{{ $project->name }}"
                                                    class="hp-main-img">
                                            </a>
                                        </div>
                                    @endforeach
                                </div>

                                {{-- Thumbnail Slider --}}
                                @if (count($fullAlbum) > 1)
                                    <div id="sync2" class="owl-carousel owl-theme uk-margin-small-top">
                                        @foreach ($fullAlbum as $img)
                                            <div class="item">
                                                <div class="hp-thumb-item">
                                                    <img src="{{ asset($img) }}" alt="thumb">
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>

                            <div class="hp-property-info mt30">
                                <h1 class="hp-detail-title">{{ $project->name }}</h1>
                                <div class="hp-detail-meta-top">
                                    <span class="hp-detail-price">
                                        @php
                                            $unitName = $attributeMap[$project->price_unit] ?? '';
                                            $displayUnit =
                                                $unitName != '' && $unitName != 'Tổng' && $unitName != '[Chọn đơn vị]'
                                                    ? $unitName
                                                    : '';
                                        @endphp
                                        {{ !empty($project->price) ? formatPrice($project->price) . ' ' . $displayUnit : 'Thỏa thuận' }}
                                    </span>
                                    <span class="hp-detail-area">
                                        {{ $project->area }} m²
                                    </span>
                                </div>
                                <div class="hp-detail-address">
                                    <i class="fa fa-map-marker"></i>
                                    {{ format_address($project) }}
                                </div>
                                @if ($project->old_province_name)
                                    <div class="hp-detail-address old-address">
                                        <i class="fa fa-history"></i> Địa chỉ cũ:
                                        {{ $project->old_ward_name }}, {{ $project->old_district_name }},
                                        {{ $project->old_province_name }}
                                    </div>
                                @endif
                                <div class="hp-detail-time">
                                    Mã: <strong>{{ $project->code }}</strong>
                                    <span style="margin: 0 10px; color: #ddd;">|</span>
                                    Cập nhật:
                                    {{ diff_for_humans($project->updated_at) }}
                                </div>
                            </div>

                            <hr class="uk-article-divider">

                            <div class="hp-property-specs">
                                <h3 class="hp-section-title">Thông số dự án</h3>
                                <div class="uk-grid uk-grid-width-medium-1-2" data-uk-grid-margin>
                                    @if ($project->apartment_count)
                                        <div class="spec-item">
                                            <span class="label"><i class="fa fa-building"></i> Số căn hộ:</span>
                                            <span class="value">{{ $project->apartment_count }} căn</span>
                                        </div>
                                    @endif
                                    @if ($project->block_count)
                                        <div class="spec-item">
                                            <span class="label"><i class="fa fa-th-large"></i> Số block/tòa:</span>
                                            <span class="value">{{ $project->block_count }} block</span>
                                        </div>
                                    @endif
                                    @if ($project->status)
                                        <div class="spec-item">
                                            <span class="label"><i class="fa fa-info-circle"></i> Trạng thái:</span>
                                            @php
                                                $statusVal = $attributeMap[$project->status] ?? $project->status;
                                                $statusMap = [
                                                    'active' => 'Hoạt động',
                                                    'inactive' => 'Tạm dừng',
                                                    'pending' => 'Chờ duyệt',
                                                    'sold' => 'Đã bán/Giao dịch',
                                                ];
                                                $statusVal = $statusMap[$statusVal] ?? $statusVal;
                                            @endphp
                                            <span class="value">{{ $statusVal }}</span>
                                        </div>
                                    @endif
                                    @if ($project->legal_status)
                                        <div class="spec-item">
                                            <span class="label"><i class="fa fa-file-text-o"></i> Pháp lý:</span>
                                            <span
                                                class="value">{{ $attributeMap[$project->legal_status] ?? $project->legal_status }}</span>
                                        </div>
                                    @endif
                                    @if ($project->company)
                                        <div class="spec-item">
                                            <span class="label"><i class="fa fa-briefcase"></i> Chủ đầu tư:</span>
                                            <span class="value">{{ $project->company }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            {{-- Content with Toggle --}}
                            <div class="hp-property-content uk-margin-large-top">
                                <h3 class="hp-section-title">Giới thiệu dự án</h3>
                                <div id="hp-content-box" class="hp-content-box entry-content collapsed">
                                    {!! $project->languages->first()->pivot->content !!}
                                </div>
                                <div id="hp-content-toggle" class="hp-content-toggle" style="display: none;">
                                    <button class="btn-toggle-content" onclick="toggleContent()">
                                        <span>Xem thêm</span> <i class="fa fa-angle-down"></i>
                                    </button>
                                </div>
                            </div>

                            {{-- Amenities --}}
                            @php
                                $amenities = $project->amenities;
                            @endphp
                            @if ($amenities && $amenities->count() > 0)
                                <div class="hp-property-amenities uk-margin-top">
                                    <h3 class="hp-section-title">Tiện ích</h3>
                                    <div class="uk-grid uk-grid-small uk-grid-width-1-2 uk-grid-width-medium-1-3"
                                        data-uk-grid-margin>
                                        @foreach ($amenities as $amenity)
                                            <div class="amenity-item">
                                                <i class="fa fa-check"></i>
                                                {{ $amenity->languages->first()->pivot->name }}
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            {{-- Map --}}
                            @if ($project->iframe_map)
                                <div class="hp-property-map uk-margin-large-top">
                                    <h3 class="hp-section-title">Vị trí dự án</h3>
                                    <div class="map-container">
                                        {!! $project->iframe_map !!}
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Column 3: Sidebar --}}
                    <div class="uk-width-large-3-10 uk-width-medium-1-3 hp-sidebar-sticky">
                        <aside class="hp-detail-sidebar hp-sidebar-sticky">
                            {{-- Agent Card Redesigned --}}
                            @include('frontend.component.agent_card', ['agent' => $agent])

                            @if (isset($projectCatalogues) && count($projectCatalogues))
                                <div class="hp-sidebar-widget">
                                    <h4 class="hp-sidebar-title">Danh mục dự án</h4>
                                    <ul class="hp-sidebar-list">
                                        @foreach ($projectCatalogues as $cat)
                                            <li>
                                                <a href="{{ url($cat->languages->first()->pivot->canonical . '.html') }}">
                                                    {{ $cat->languages->first()->pivot->name }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            @if (isset($realEstateCatalogues) && count($realEstateCatalogues))
                                <div class="hp-sidebar-widget">
                                    <h4 class="hp-sidebar-title">Danh mục BĐS</h4>
                                    <ul class="hp-sidebar-list">
                                        @foreach ($realEstateCatalogues as $cat)
                                            <li>
                                                <a href="{{ url($cat->languages->first()->pivot->canonical . '.html') }}">
                                                    {{ $cat->languages->first()->pivot->name }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                    </div>
                </div>
            </div>

            {{-- Related Projects (Dự án liên quan) - Moved below content --}}
            @if (isset($relatedProjects) && count($relatedProjects))
                <div class="hp-related-section uk-container uk-container-center mt30">
                    <h3 class="hp-section-title">Dự án liên quan</h3>
                    <div class="uk-grid uk-grid-medium uk-margin-top" data-uk-grid-margin>
                        @foreach ($relatedProjects as $item)
                            <div class="uk-width-large-1-3 uk-width-medium-1-2 mb20">
                                @include('frontend.component.project_card', ['item' => $item])
                            </div>
                        @endforeach
                    </div>
                    <div class="uk-margin-large-top">
                        {{ $relatedProjects->links('pagination::default') }}
                    </div>
                </div>
            @endif
    </div>
    </section>
    </div>

    <script>
        function toggleContent() {
            const box = document.getElementById('hp-content-box');
            const btn = document.querySelector('.btn-toggle-content');
            if (box.classList.contains('collapsed')) {
                box.classList.remove('collapsed');
                btn.innerHTML = '<span>Thu gọn</span> <i class="fa fa-angle-up"></i>';
            } else {
                box.classList.add('collapsed');
                btn.innerHTML = '<span>Xem thêm</span> <i class="fa fa-angle-down"></i>';
                window.scrollTo({
                    top: box.offsetTop - 100,
                    behavior: 'smooth'
                });
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const box = document.getElementById('hp-content-box');
            const toggle = document.getElementById('hp-content-toggle');
            if (box && box.scrollHeight > 400) {
                toggle.style.display = 'block';
            } else if (box) {
                box.classList.remove('collapsed');
            }

            // Synced Slider Initialization
            var sync1 = $("#sync1");
            var sync2 = $("#sync2");
            var slidesPerPage = 5;
            var syncedSecondary = true;

            sync1.owlCarousel({
                items: 1,
                slideSpeed: 2000,
                nav: true,
                autoplay: false,
                dots: false,
                loop: false,
                responsiveRefreshRate: 200,
                navText: ['<i class="fa fa-angle-left"></i>', '<i class="fa fa-angle-right"></i>'],
            }).on('changed.owl.carousel', syncPosition);

            sync2
                .on('initialized.owl.carousel', function() {
                    sync2.find(".owl-item").eq(0).addClass("current");
                })
                .owlCarousel({
                    items: 7,
                    dots: false,
                    nav: false,
                    margin: 10,
                    smartSpeed: 200,
                    slideSpeed: 500,
                    responsiveRefreshRate: 100,
                    responsive: {
                        0: {
                            items: 4
                        },
                        600: {
                            items: 5
                        },
                        1000: {
                            items: 7
                        }
                    }
                }).on('changed.owl.carousel', syncPosition2);

            function syncPosition(el) {
                var count = el.item.count - 1;
                var current = Math.round(el.item.index - (el.item.count / 2) - .5);
                if (current < 0) {
                    current = count;
                }
                if (current > count) {
                    current = 0;
                }
                sync2.find(".owl-item").removeClass("current").eq(current).addClass("current");
                var onscreen = sync2.find('.owl-item.active').length - 1;
                var start = sync2.find('.owl-item.active').first().index();
                var end = sync2.find('.owl-item.active').last().index();
                if (current > end) {
                    sync2.data('owl.carousel').to(current, 100, true);
                }
                if (current < start) {
                    sync2.data('owl.carousel').to(current - onscreen, 100, true);
                }
            }

            function syncPosition2(el) {
                if (syncedSecondary) {
                    var number = el.item.index;
                    sync1.data('owl.carousel').to(number, 100, true);
                }
            }

            sync2.on("click", ".owl-item", function(e) {
                e.preventDefault();
                var number = $(this).index();
                sync1.data('owl.carousel').to(number, 300, true);
            });
        });
    </script>
@endsection
