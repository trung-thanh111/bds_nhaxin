@extends('frontend.homepage.layout')

@section('content')
    <div class="hp-detail-page">
        {{-- Breadcrumb & Header --}}
        <section class="hp-detail-header">
            <div class="uk-container uk-container-center">
                <ul class="uk-breadcrumb uk-flex uk-flex-middle">
                    <li><a href="{{ url('/') }}">Trang chủ</a></li>
                    @if ($realEstate->catalogue)
                        <li><a
                                href="{{ url($realEstate->catalogue->languages->first()->pivot->canonical . '.html') }}">{{ $realEstate->catalogue->languages->first()->pivot->name }}</a>
                        </li>
                    @endif
                    <li class="uk-active"><span>{{ $realEstate->name }}</span></li>
                </ul>
            </div>
        </section>

        <section class="hp-section">
            <div class="uk-container uk-container-center">
                <div class="uk-grid uk-grid-small hp-detail-grid" data-uk-grid-margin>
                    <div class="uk-width-large-7-10 uk-width-medium-2-3">
                        <div class="hp-property-main">
                            {{-- Image Slider --}}
                            <div class="hp-property-gallery">
                                @php
                                    $album = json_decode($realEstate->album, true) ?? [];
                                    $fullAlbum = array_unique(array_filter(array_merge([$realEstate->image], $album)));
                                @endphp

                                {{-- Main Slider --}}
                                <div id="sync1" class="owl-carousel owl-theme">
                                    @foreach ($fullAlbum as $img)
                                        <div class="item">
                                            <a href="{{ asset($img) }}" data-fancybox="gallery" data-type="image"
                                                data-caption="{{ $realEstate->languages->first()->pivot->name ?? '' }}">
                                                <img src="{{ asset($img) }}" alt="{{ $realEstate->name }}"
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

                            {{-- Title & Meta --}}
                            <div class="hp-property-info mt30">
                                <h1 class="hp-detail-title">{{ $realEstate->name }}</h1>

                                <div class="hp-detail-meta-top">
                                    @php
                                        $prices = [];
                                        $unitName = $attributeMap[$realEstate->price_unit] ?? '';
                                        $displayUnit =
                                            $unitName != '' && $unitName != 'Tổng' && $unitName != '[Chọn đơn vị]'
                                                ? $unitName
                                                : '';

                                        $typeName = $attributeMap[$realEstate->transaction_type] ?? '';
                                        $isSale = stripos($typeName, 'Bán') !== false;
                                        $isRent = stripos($typeName, 'Thuê') !== false;

                                        if (($isSale || $realEstate->price_sale > 0) && $realEstate->price_sale > 0) {
                                            $prices[] = [
                                                'label' => 'Bán:',
                                                'val' => formatPrice($realEstate->price_sale),
                                            ];
                                        }
                                        if (($isRent || $realEstate->price_rent > 0) && $realEstate->price_rent > 0) {
                                            $prices[] = [
                                                'label' => 'Thuê:',
                                                'val' => formatPrice($realEstate->price_rent) . $displayUnit,
                                            ];
                                        }
                                    @endphp

                                    @if (empty($prices))
                                        <span class="hp-detail-price">Thỏa thuận</span>
                                    @else
                                        @foreach ($prices as $index => $p)
                                            <span class="hp-detail-price"
                                                style="{{ $index > 0 ? 'margin-left: 10px; border-left: 1px solid #ddd; padding-left: 10px;' : '' }}">
                                                <small
                                                    style="font-size: 14px; color: #888; font-weight: 500;">{{ $p['label'] }}</small>
                                                {{ $p['val'] }}
                                            </span>
                                        @endforeach
                                    @endif

                                    <span class="hp-detail-area">
                                        {{ $realEstate->area }} m²
                                    </span>
                                </div>

                                <div class="hp-detail-address">
                                    <i class="fa fa-map-marker"></i>
                                    {{ format_address($realEstate) }}
                                </div>
                                @if ($realEstate->old_province_name)
                                    <div class="hp-detail-address old-address">
                                        <i class="fa fa-history"></i> Địa chỉ cũ:
                                        {{ $realEstate->old_ward_name }}, {{ $realEstate->old_district_name }},
                                        {{ $realEstate->old_province_name }}
                                    </div>
                                @endif
                                <div class="hp-detail-time">
                                    Mã: <strong>{{ $realEstate->code }}</strong>
                                    <span style="margin: 0 10px; color: #ddd;">|</span>
                                    Cập nhật:
                                    {{ diff_for_humans($realEstate->updated_at) }}
                                </div>
                            </div>

                            {{-- Specific Details Grid --}}
                            <div class="hp-property-specs mt30">
                                <h3 class="hp-section-title">Thông tin chi tiết</h3>
                                <div class="uk-grid uk-grid-width-medium-1-2" data-uk-grid-margin>
                                    @if ($realEstate->project)
                                        <div class="spec-item">
                                            <span class="label"><i class="fa fa-building-o"></i> Dự án:</span>
                                            <span class="value"><a
                                                    href="{{ url($realEstate->project->canonical . '.html') }}">{{ $realEstate->project->name }}</a></span>
                                        </div>
                                    @endif
                                    @if ($realEstate->ownership_type)
                                        <div class="spec-item">
                                            <span class="label"><i class="fa fa-file-text-o"></i> Pháp lý:</span>
                                            <span
                                                class="value">{{ $attributeMap[$realEstate->ownership_type] ?? $realEstate->ownership_type }}</span>
                                        </div>
                                    @endif
                                    @if ($realEstate->house_direction)
                                        <div class="spec-item">
                                            <span class="label"><i class="fa fa-compass"></i> Hướng nhà:</span>
                                            <span
                                                class="value">{{ $attributeMap[$realEstate->house_direction] ?? $realEstate->house_direction }}</span>
                                        </div>
                                    @endif
                                    @if ($realEstate->view)
                                        <div class="spec-item">
                                            <span class="label"><i class="fa fa-eye"></i> Tầm nhìn (View):</span>
                                            <span class="value">{{ $realEstate->view }}</span>
                                        </div>
                                    @endif
                                    @if ($realEstate->bedrooms)
                                        <div class="spec-item">
                                            <span class="label"><i class="fa fa-bed"></i> Phòng ngủ:</span>
                                            <span class="value">{{ $realEstate->bedrooms }} PN</span>
                                        </div>
                                    @endif
                                    @if ($realEstate->bathrooms)
                                        <div class="spec-item">
                                            <span class="label"><i class="fa fa-bath"></i> Phòng tắm:</span>
                                            <span class="value">{{ $realEstate->bathrooms }} WC</span>
                                        </div>
                                    @endif
                                    @if ($realEstate->floor || $realEstate->total_floors)
                                        <div class="spec-item">
                                            <span class="label"><i class="fa fa-align-justify"></i> Tầng số:</span>
                                            <span class="value">
                                                {{ $attributeMap[$realEstate->floor] ?? $realEstate->floor }}
                                                @if ($realEstate->total_floors)
                                                    / {{ $realEstate->total_floors }} (Tổng tầng)
                                                @endif
                                            </span>
                                        </div>
                                    @endif
                                    @if ($realEstate->block_tower)
                                        <div class="spec-item">
                                            <span class="label"><i class="fa fa-th-large"></i> Block / Tòa:</span>
                                            <span class="value">{{ $realEstate->block_tower }}</span>
                                        </div>
                                    @endif
                                    @if ($realEstate->apartment_code)
                                        <div class="spec-item">
                                            <span class="label"><i class="fa fa-id-badge"></i> Mã căn hộ:</span>
                                            <span class="value">{{ $realEstate->apartment_code }}</span>
                                        </div>
                                    @endif
                                    @if ($realEstate->balcony_direction)
                                        <div class="spec-item">
                                            <span class="label"><i class="fa fa-external-link"></i> Hướng ban công:</span>
                                            <span
                                                class="value">{{ $attributeMap[$realEstate->balcony_direction] ?? $realEstate->balcony_direction }}</span>
                                        </div>
                                    @endif
                                    @if ($realEstate->interior)
                                        <div class="spec-item">
                                            <span class="label"><i class="fa fa-couch"></i> Nội thất:</span>
                                            <span
                                                class="value">{{ $attributeMap[$realEstate->interior] ?? $realEstate->interior }}</span>
                                        </div>
                                    @endif
                                    @if ($realEstate->year_built)
                                        <div class="spec-item">
                                            <span class="label"><i class="fa fa-calendar"></i> Năm bàn giao:</span>
                                            <span class="value">{{ $realEstate->year_built }}</span>
                                        </div>
                                    @endif
                                    @if ($realEstate->land_type)
                                        <div class="spec-item">
                                            <span class="label"><i class="fa fa-map-o"></i> Loại đất:</span>
                                            <span
                                                class="value">{{ $attributeMap[$realEstate->land_type] ?? $realEstate->land_type }}</span>
                                        </div>
                                    @endif
                                    @if ($realEstate->land_width)
                                        <div class="spec-item">
                                            <span class="label"><i class="fa fa-arrows-h"></i> Chiều ngang:</span>
                                            <span class="value">{{ (float) $realEstate->land_width }} m</span>
                                        </div>
                                    @endif
                                    @if ($realEstate->land_length)
                                        <div class="spec-item">
                                            <span class="label"><i class="fa fa-arrows-v"></i> Chiều dài:</span>
                                            <span class="value">{{ (float) $realEstate->land_length }} m</span>
                                        </div>
                                    @endif
                                    @if ($realEstate->road_frontage)
                                        <div class="spec-item">
                                            <span class="label"><i class="fa fa-road"></i> Mặt tiền đường:</span>
                                            <span class="value">{{ (float) $realEstate->road_frontage }} m</span>
                                        </div>
                                    @endif
                                    @if ($realEstate->road_width)
                                        <div class="spec-item">
                                            <span class="label"><i class="fa fa-car"></i> Đường rộng:</span>
                                            <span class="value">{{ (float) $realEstate->road_width }} m</span>
                                        </div>
                                    @endif
                                    @if ($realEstate->usable_area)
                                        <div class="spec-item">
                                            <span class="label"><i class="fa fa-clone"></i> Diện tích sử dụng:</span>
                                            <span class="value">{{ (float) $realEstate->usable_area }} m²</span>
                                        </div>
                                    @endif
                                    @if ($realEstate->land_area)
                                        <div class="spec-item">
                                            <span class="label"><i class="fa fa-crop"></i> Diện tích đất:</span>
                                            <span class="value">{{ (float) $realEstate->land_area }} m²</span>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            {{-- Content with Toggle --}}
                            <div class="hp-property-content mt30">
                                <h3 class="hp-section-title">Nội dung chi tiết</h3>
                                <div id="hp-content-box" class="hp-content-box entry-content collapsed">
                                    {!! $realEstate->languages->first()->pivot->content !!}
                                </div>
                                <div id="hp-content-toggle" class="hp-content-toggle" style="display: none;">
                                    <button class="btn-toggle-content" onclick="toggleContent()">
                                        <span>Xem thêm</span> <i class="fa fa-angle-down"></i>
                                    </button>
                                </div>
                            </div>

                            {{-- Amenities --}}
                            @php
                                $amenities = $realEstate->amenities;
                            @endphp
                            @if ($amenities && $amenities->count() > 0)
                                <div class="hp-property-amenities mt30">
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
                            @if ($realEstate->iframe_map)
                                <div class="hp-property-map mt30">
                                    <h3 class="hp-section-title">Vị trí thực tế</h3>
                                    <div class="map-container">
                                        {!! $realEstate->iframe_map !!}
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Column 3: Sidebar --}}
                    <div class="uk-width-large-3-10 uk-width-medium-1-3">
                        <aside class="hp-detail-sidebar hp-sidebar-sticky">
                            {{-- Agent Card Redesigned --}}
                            @include('frontend.component.agent_card', ['agent' => $agent])

                            {{-- Sidebar Categories --}}
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
                    </div>
                </div>
            </div>

            {{-- Related Real Estates (Tin liên quan) --}}
            @if (isset($relatedRealEstates) && count($relatedRealEstates))
                <div class="hp-related-section uk-container uk-container-center mt30">
                    <h3 class="hp-section-title">Tin liên quan</h3>
                    <div class="uk-grid uk-grid-medium uk-margin-top" data-uk-grid-margin>
                        @foreach ($relatedRealEstates as $item)
                            <div class="uk-width-large-1-3 uk-width-medium-1-2 mb20">
                                @include('frontend.component.real_estate_card', [
                                    'item' => $item,
                                    'attributeMap' => $attributeMap,
                                ])
                            </div>
                        @endforeach
                    </div>
                    <div class="uk-margin-large-top">
                        {{ $relatedRealEstates->links('pagination::default') }}
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
