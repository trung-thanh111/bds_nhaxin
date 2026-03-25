@extends('frontend.homepage.layout')
@section('content')
    <div class="gl-section">
        <div class="uk-container uk-container-center">
            <div class="gl-category-grid">
                <a href="/mua-ban.html" class="gl-cat-item">
                    <div class="gl-cat-icon"><i class="fa fa-home"></i></div>
                    <div class="gl-cat-label">Mua bán</div>
                </a>
                <a href="/cho-thue.html" class="gl-cat-item">
                    <div class="gl-cat-icon" style="background: #e8f5e9; color: #2e7d32;"><i class="fa fa-key"></i></div>
                    <div class="gl-cat-label">Cho thuê</div>
                </a>
                <a href="/du-an.html" class="gl-cat-item">
                    <div class="gl-cat-icon" style="background: #fff3e0; color: #ef6c00;"><i class="fa fa-building"></i>
                    </div>
                    <div class="gl-cat-label">Dự án</div>
                </a>
                <a href="/lien-he.html" class="gl-cat-item">
                    <div class="gl-cat-icon" style="background: #f3e5f5; color: #7b1fa2;"><i class="fa fa-edit"></i></div>
                    <div class="gl-cat-label">Liên hệ</div>
                </a>
            </div>
        </div>
    </div>

    <div class="gl-promo-section">
        <div class="uk-container uk-container-center">
            <div class="gl-promo-content">
                <h2 class="gl-promo-title">TÌM KIẾM BẤT ĐỘNG SẢN ƯNG Ý</h2>
                <p class="gl-promo-desc">
                    Hàng ngàn tin <strong>mua bán, cho thuê</strong> nhà đất và dự án với thông tin xác thực, vị trí
                    đắc địa và pháp lý an toàn.<br>
                    Chuyên trang bất động sản và quy hoạch giúp bạn tìm kiếm cơ hội đầu tư và
                    an cư lý tưởng nhất.
                </p>
                <div class="gl-promo-actions">
                    <a href="https://zalo.me/{{ preg_replace('/\D/', '', $system['contact_hotline'] ?? '0983284379') }}"
                        class="gl-btn-zalo" target="_blank">
                        <img src="{{ asset('frontend/resources/img/icon_zalo_white.png') }}" alt="Zalo"
                            onerror="this.src='https://upload.wikimedia.org/wikipedia/commons/9/91/Icon_of_Zalo.svg'">
                        TƯ VẤN NGAY
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="gl-section">
        <div class="uk-container uk-container-center">
            @if (isset($homepageCatalogues) && count($homepageCatalogues))
                @foreach ($homepageCatalogues as $catalogue)
                    @if (isset($catalogue->real_estates) && count($catalogue->real_estates))
                        <div class="gl-section">
                            <div class="gl-section-header">
                                <h2 class="gl-section-title">{{ $catalogue->languages->first()->pivot->name }}</h2>
                                <a href="{{ url($catalogue->languages->first()->pivot->canonical . '.html') }}"
                                    class="gl-view-more">Xem tất cả <i class="fa fa-arrow-right"></i></a>
                            </div>
                            <div class="uk-grid uk-grid-medium" data-uk-grid-margin>
                                @foreach ($catalogue->real_estates as $item)
                                    @php
                                        $lang = $item->languages->first()->pivot;
                                        $price = $item->price_sale > 0 ? $item->price_sale : $item->price_rent;
                                        $priceText = number_format($price, 0, ',', '.') . ' ' . $item->price_unit;
                                    @endphp
                                    <div class="uk-width-large-1-3 uk-width-medium-1-2 mb20">
                                        <div class="gl-property-card">
                                            @php
                                                $lang = $item->languages->first()->pivot;
                                                $timeUpdate = \Carbon\Carbon::parse($item->updated_at)
                                                    ->locale('vi')
                                                    ->diffForHumans();
                                            @endphp
                                            <div class="gl-card-img-wrapper">
                                                <a href="{{ url($lang->canonical . '.html') }}">
                                                    <img src="{{ image($item->image) }}" alt="{{ $lang->name }}">
                                                </a>
                                                <a href="{{ route('contact.index') }}" class="gl-card-badge">Liên hệ
                                                    ngay</a>
                                                <a href="{{ $item->iframe_map }}" target="_blank" class="gl-card-map-btn">
                                                    <i class="fa fa-location-arrow"></i> Chỉ đường
                                                </a>
                                            </div>

                                            <div class="gl-card-body">
                                                <h3 class="gl-card-title">
                                                    <a href="{{ url($lang->canonical . '.html') }}">{{ $lang->name }}</a>
                                                </h3>
                                                <div class="gl-card-price-row uk-flex uk-flex-middle">
                                                    @php
                                                        $area = $item->area ?? 0;
                                                        $prices = [];

                                                        // Lấy tên đơn vị từ mapping
                                                        $unitId = $item->price_unit;
                                                        $unitName = isset($attributeMap[$unitId])
                                                            ? $attributeMap[$unitId]
                                                            : '';
                                                        $displayUnit =
                                                            $unitName != '' &&
                                                            $unitName != 'Tổng' &&
                                                            $unitName != '[Chọn đơn vị]'
                                                                ? $unitName
                                                                : '';

                                                        // Lấy nhãn loại giao dịch từ mapping (nếu có)
                                                        $typeId = $item->transaction_type;
                                                        $typeName = isset($attributeMap[$typeId])
                                                            ? $attributeMap[$typeId]
                                                            : '';

                                                        $showSale = false;
                                                        $showRent = false;

                                                        if ($typeName) {
                                                            $isSale = stripos($typeName, 'Bán') !== false;
                                                            $isRent = stripos($typeName, 'Thuê') !== false;

                                                            if ($isSale && $isRent) {
                                                                $showSale = true;
                                                                $showRent = true;
                                                            } elseif ($isSale) {
                                                                $showSale = true;
                                                            } elseif ($isRent) {
                                                                $showRent = true;
                                                            }
                                                        } else {
                                                            // Fallback nếu không có mapping
                                                            $showSale = $item->price_sale > 0;
                                                            $showRent = $item->price_rent > 0;
                                                        }

                                                        if ($showSale && $item->price_sale > 0) {
                                                            $label = 'Bán:';
                                                            if (
                                                                $typeName &&
                                                                stripos($typeName, 'Bán') !== false &&
                                                                !$showRent
                                                            ) {
                                                                $label = $typeName . ':';
                                                            }

                                                            $prices[] = [
                                                                'label' => $label,
                                                                'val' => formatPrice($item->price_sale),
                                                            ];
                                                        }

                                                        if ($showRent && $item->price_rent > 0) {
                                                            $label = 'Thuê:';
                                                            if (
                                                                $typeName &&
                                                                stripos($typeName, 'Thuê') !== false &&
                                                                !$showSale
                                                            ) {
                                                                $label = $typeName . ':';
                                                            }

                                                            $prices[] = [
                                                                'label' => $label,
                                                                'val' => formatPrice($item->price_rent) . $displayUnit,
                                                            ];
                                                        }

                                                        if (empty($prices)) {
                                                            $prices[] = ['label' => 'Giá:', 'val' => 'Liên hệ'];
                                                        }
                                                    @endphp

                                                    @foreach ($prices as $index => $p)
                                                        <div class="gl-card-price-group uk-flex uk-flex-middle">
                                                            <span class="gl-card-price-label">{{ $p['label'] }}</span>
                                                            <span class="gl-card-price-val">{{ $p['val'] }}</span>
                                                        </div>
                                                        @if ($index < count($prices) - 1)
                                                            <span class="gl-card-separator"><i
                                                                    class="fa fa-circle"></i></span>
                                                        @endif
                                                    @endforeach

                                                    <span class="gl-card-separator"><i class="fa fa-circle"></i></span>
                                                    <span class="gl-card-area">{{ $area }} m²</span>

                                                    @php
                                                        $mainPrice =
                                                            $item->price_sale > 0
                                                                ? $item->price_sale
                                                                : $item->price_rent;
                                                        $pricePerM2 =
                                                            $mainPrice > 0 && $area > 0 ? $mainPrice / $area : 0;
                                                    @endphp
                                                    @if ($pricePerM2 > 0)
                                                        <span class="gl-card-separator"><i class="fa fa-circle"></i></span>
                                                        <span
                                                            class="gl-card-price-m2">{{ formatPrice($pricePerM2) }}/m²</span>
                                                    @endif
                                                </div>

                                                <div class="gl-card-address">
                                                    <i class="fa fa-map-marker"></i>
                                                    @php
                                                        $addressArray = array_filter([
                                                            $item->ward_name,
                                                            $item->district_name,
                                                            $item->province_name,
                                                        ]);
                                                        $displayAddress = implode(
                                                            ', ',
                                                            array_slice($addressArray, 0, 2),
                                                        );
                                                    @endphp
                                                    <span>{{ $displayAddress }}</span>
                                                </div>

                                                @if (!empty($item->old_province_name))
                                                    <div class="gl-card-address">
                                                        <i class="fa fa-map-marker"></i>
                                                        @php
                                                            $oldAddressArray = array_filter([
                                                                $item->old_ward_name,
                                                                $item->old_district_name,
                                                                $item->old_province_name,
                                                            ]);
                                                            $displayOldAddress = implode(
                                                                ', ',
                                                                array_slice($oldAddressArray, 0, 3),
                                                            );
                                                        @endphp
                                                        <span>{{ $displayOldAddress }} (Cũ)</span>
                                                    </div>
                                                @endif

                                                <div class="gl-card-features-wrapper">
                                                    <div class="gl-card-feature-label">Đặc điểm / Tiện ích:</div>
                                                    <div class="gl-card-features">
                                                        @php
                                                            $allFeatures = [];
                                                            if ($item->bedrooms) {
                                                                $allFeatures[] = $item->bedrooms . ' PN';
                                                            }
                                                            if ($item->bathrooms) {
                                                                $allFeatures[] = $item->bathrooms . ' WC';
                                                            }
                                                            if ($item->road_width) {
                                                                $allFeatures[] = 'Đường ' . $item->road_width . 'm';
                                                            }

                                                            foreach ($item->amenities as $amenity) {
                                                                $allFeatures[] =
                                                                    $amenity->languages->first()->pivot->name ?? '';
                                                            }
                                                            $allFeatures = array_filter($allFeatures);
                                                            $displayFeatures = array_slice($allFeatures, 0, 10);
                                                            $moreCount = count($allFeatures) - count($displayFeatures);
                                                        @endphp
                                                        @foreach ($displayFeatures as $feature)
                                                            <span class="gl-card-feature-tag">{{ $feature }}</span>
                                                        @endforeach
                                                        @if ($moreCount > 0)
                                                            <span class="gl-card-feature-more">+{{ $moreCount }}</span>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="gl-card-description">
                                                    {!! strip_tags($lang->content) !!}
                                                </div>

                                                <div class="gl-card-footer">
                                                    <span class="gl-card-id">Mã: {{ $item->code }}</span>
                                                    <span class="gl-card-time"><i
                                                            class="fa fa-clock-o uk-margin-small-right"></i>
                                                        Cập nhật {{ $timeUpdate }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                @endforeach
            @endif


            @if (isset($projects) && count($projects))
                <div class="gl-section">
                    <div class="gl-section-header">
                        <h2 class="gl-section-title">Dự án mới nhất</h2>
                        <a href="/du-an.html" class="gl-view-more">Xem tất cả <i class="fa fa-arrow-right"></i></a>
                    </div>
                    <div class="uk-grid uk-grid-medium" data-uk-grid-margin>
                        @foreach ($projects as $item)
                            @php
                                $lang = $item->languages->first()->pivot;
                                $timeUpdate = \Carbon\Carbon::parse($item->updated_at)->locale('vi')->diffForHumans();
                            @endphp
                            <div class="uk-width-large-1-3 uk-width-medium-1-2 mb20">
                                <div class="gl-property-card">
                                    <div class="gl-card-img-wrapper">
                                        <a href="{{ url($lang->canonical . '.html') }}">
                                            <div class="img-cover">
                                                <img src="{{ image($item->cover_image) }}" alt="{{ $lang->name }}">
                                            </div>
                                        </a>
                                        <a href="{{ route('contact.index') }}" class="gl-card-badge">Liên hệ</a>
                                        @if ($item->iframe_map)
                                            <a href="{{ $item->iframe_map }}" target="_blank" class="gl-card-map-btn">
                                                <i class="fa fa-location-arrow"></i> Chỉ đường
                                            </a>
                                        @endif
                                    </div>
                                    <div class="gl-card-body">
                                        <h3 class="gl-card-title">
                                            <a href="{{ url($lang->canonical . '.html') }}">{{ $lang->name }}</a>
                                        </h3>

                                        <!-- Price & Area -->
                                        <div class="gl-card-price-row uk-flex uk-flex-middle">
                                            <div class="gl-card-price-group uk-flex uk-flex-middle">
                                                <span class="gl-card-price-val"
                                                    style="color: #27ae60; font-weight: 700;">Liên hệ</span>
                                            </div>
                                            <span class="gl-card-separator"><i class="fa fa-circle"></i></span>
                                            <span class="gl-card-area">{{ $item->area }} m²</span>
                                        </div>

                                        <!-- Address -->
                                        <div class="gl-card-address">
                                            <i class="fa fa-map-marker"></i>
                                            @php
                                                $addressArray = array_filter([
                                                    $item->ward_name,
                                                    $item->district_name,
                                                    $item->province_name,
                                                ]);
                                                $displayAddress = implode(', ', $addressArray);
                                            @endphp
                                            <span>{{ $displayAddress }}</span>
                                        </div>

                                        @if (!empty($item->old_province_name))
                                            <div class="gl-card-address">
                                                <i class="fa fa-map-marker"></i>
                                                @php
                                                    $oldAddressArray = array_filter([
                                                        $item->old_ward_name,
                                                        $item->old_district_name,
                                                        $item->old_province_name,
                                                    ]);
                                                    $displayOldAddress = implode(', ', $oldAddressArray);
                                                @endphp
                                                <span>{{ $displayOldAddress }} (Cũ)</span>
                                            </div>
                                        @endif

                                        <!-- Features/Amenities -->
                                        <div class="gl-card-features-wrapper">
                                            <div class="gl-card-feature-label">Quy mô / Tiện ích:</div>
                                            <div class="gl-card-features">
                                                @php
                                                    $allFeatures = [];
                                                    if ($item->apartment_count > 0) {
                                                        $allFeatures[] = $item->apartment_count . ' Căn hộ';
                                                    }
                                                    if ($item->block_count > 0) {
                                                        $allFeatures[] = $item->block_count . ' Tòa';
                                                    }
                                                    foreach ($item->amenities as $amenity) {
                                                        $allFeatures[] =
                                                            $amenity->languages->first()->pivot->name ?? '';
                                                    }
                                                    $allFeatures = array_filter($allFeatures);
                                                    $displayFeatures = array_slice($allFeatures, 0, 8);
                                                    $moreCount = count($allFeatures) - count($displayFeatures);
                                                @endphp
                                                @foreach ($displayFeatures as $feature)
                                                    <span class="gl-card-feature-tag">{{ $feature }}</span>
                                                @endforeach
                                                @if ($moreCount > 0)
                                                    <span class="gl-card-feature-more">+{{ $moreCount }}</span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="gl-card-description">
                                            {!! Str::limit(strip_tags($lang->description), 100) !!}
                                        </div>

                                        <div class="gl-card-footer">
                                            <span class="gl-card-id">Mã: {{ $item->code }}</span>
                                            <span class="gl-card-time"><i class="fa fa-clock-o uk-margin-small-right"></i>
                                                Cập nhật {{ $timeUpdate }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- News Section -->
            <div class="gl-section">
                <h2 class="gl-section-title">Tin tức & Kinh nghiệm</h2>
                <div class="uk-grid uk-grid-medium" data-uk-grid-margin>
                    @if (isset($posts) && count($posts) > 0)
                        @foreach ($posts as $post)
                            <div class="uk-width-large-1-3 uk-width-medium-1-2 mb20">
                                <div class="gl-post-card">
                                    <a href="{{ url($post->canonical . '.html') }}" class="gl-post-img-wrapper">
                                        <img src="{{ asset($post->image) }}" alt="{{ $post->name }}">
                                    </a>
                                    <div class="gl-post-body">
                                        @if ($post->post_catalogues->first())
                                            <span
                                                class="gl-post-cat-tag">{{ $post->post_catalogues->first()->languages->first()->pivot->name ?? '' }}</span>
                                        @endif
                                        <h3 class="gl-post-title">
                                            <a href="{{ url($post->canonical . '.html') }}">{{ $post->name }}</a>
                                        </h3>
                                        <div class="gl-post-desc">
                                            {!! Str::limit(strip_tags($post->languages->first()->pivot->description ?? ''), 240) !!}
                                        </div>
                                        <div class="gl-post-footer">
                                            <span class="gl-post-time">
                                                <i class="fa fa-clock-o"></i>
                                                {{ \Carbon\Carbon::parse($post->released_at ?? $post->created_at)->locale('vi')->diffForHumans() }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
