@php
    $lang = $item->languages->first()->pivot;
    $timeUpdate = diff_for_humans($item->updated_at);
    $area = $item->area ?? 0;

    $displayAddress = format_address($item);
    $mapUrl =
        extract_map_url($item->iframe_map) ?:
        'https://www.google.com/maps/search/?api=1&query=' . urlencode($displayAddress);

    $unitId = $item->price_unit;
    $unitName = $attributeMap[$unitId] ?? '';
    $displayUnit = $unitName != '' && !in_array($unitName, ['Tổng', '[Chọn đơn vị]']) ? $unitName : '';

    $typeId = $item->transaction_type;
    $typeName = $attributeMap[$typeId] ?? '';

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
        $showSale = $item->price_sale > 0;
        $showRent = $item->price_rent > 0;
    }

    $prices = [];
    if ($showSale && $item->price_sale > 0) {
        $label = stripos($typeName, 'Bán') !== false && !$showRent ? $typeName . ':' : 'Bán:';
        $prices[] = ['label' => $label, 'val' => formatPrice($item->price_sale)];
    }
    if ($showRent && $item->price_rent > 0) {
        $label = stripos($typeName, 'Thuê') !== false && !$showSale ? $typeName . ':' : 'Thuê:';
        $prices[] = ['label' => $label, 'val' => formatPrice($item->price_rent) . $displayUnit];
    }
    if (empty($prices)) {
        $prices[] = ['label' => 'Giá:', 'val' => 'Thỏa thuận'];
    }

    $mainPrice = $item->price_sale > 0 ? $item->price_sale : $item->price_rent;
    $pricePerM2 = $mainPrice > 0 && $area > 0 ? $mainPrice / $area : 0;
@endphp

<div class="gl-property-card">
    <div class="gl-card-img-wrapper">
        <a href="{{ url($lang->canonical . '.html') }}">
            <img src="{{ image($item->image) }}" alt="{{ $lang->name }}">
        </a>
        <a href="{{ route('contact.index') }}" class="gl-card-badge">Liên hệ ngay</a>
        <a href="{{ $mapUrl }}" target="_blank" class="gl-card-map-btn">
            <i class="fa fa-location-arrow"></i> Chỉ đường
        </a>
    </div>

    <div class="gl-card-body">
        <h3 class="gl-card-title">
            <a href="{{ url($lang->canonical . '.html') }}">{{ $lang->name }}</a>
        </h3>
        <div class="gl-card-price-row uk-flex uk-flex-middle">
            @foreach ($prices as $index => $p)
                <div class="gl-card-price-group uk-flex uk-flex-middle">
                    <span class="gl-card-price-label">{{ $p['label'] }}</span>
                    <span class="gl-card-price-val">{{ $p['val'] }}</span>
                </div>
                @if ($index < count($prices) - 1)
                    <span class="gl-card-separator"><i class="fa fa-circle"></i></span>
                @endif
            @endforeach

            <span class="gl-card-separator"><i class="fa fa-circle"></i></span>
            <span class="gl-card-area">{{ $area }} m²</span>

            @if ($pricePerM2 > 0)
                <span class="gl-card-separator"><i class="fa fa-circle"></i></span>
                <span class="gl-card-price-m2">{{ formatPrice($pricePerM2) }}/m²</span>
            @endif
        </div>

        <div class="gl-card-address">
            <i class="fa fa-map-marker"></i>
            <span>{{ $displayAddress }}</span>
        </div>

        @if (!empty($item->old_province_name))
            <div class="gl-card-address">
                <i class="fa fa-map-marker"></i>
                @php
                    $oldAddress = format_address(
                        $item->old_province_name,
                        $item->old_district_name,
                        $item->old_ward_name,
                    );
                @endphp
                <span>{{ $oldAddress }} (Cũ)</span>
            </div>
        @endif

        <div class="gl-card-features-wrapper">
            <div class="gl-card-feature-label">Đặc điểm / Thông số:</div>
            <div class="gl-card-features">
                @php
                    $allFeatures = [];
                    // Thông số chung
                    if ($item->ownership_type && isset($attributeMap[$item->ownership_type])) {
                        $allFeatures[] = '<i class="fa fa-file-text-o"></i> ' . $attributeMap[$item->ownership_type];
                    }
                    if ($item->house_direction && isset($attributeMap[$item->house_direction])) {
                        $allFeatures[] = '<i class="fa fa-compass"></i> ' . $attributeMap[$item->house_direction];
                    }
                    if ($item->view) {
                        $allFeatures[] = '<i class="fa fa-eye"></i> ' . $item->view;
                    }

                    // Nhà phố / Căn hộ
                    if ($item->bedrooms) {
                        $allFeatures[] = '<i class="fa fa-bed"></i> ' . $item->bedrooms . ' PN';
                    }
                    if ($item->bathrooms) {
                        $allFeatures[] = '<i class="fa fa-bath"></i> ' . $item->bathrooms . ' WC';
                    }
                    if ($item->floor || $item->total_floors) {
                        $floorDisplay = 'Tầng ' . ($attributeMap[$item->floor] ?? $item->floor);
                        if ($item->total_floors) {
                            $floorDisplay .= '/' . $item->total_floors;
                        }
                        $allFeatures[] = '<i class="fa fa-building-o"></i> ' . $floorDisplay;
                    }
                    if ($item->block_tower) {
                        $allFeatures[] = 'Block: ' . $item->block_tower;
                    }
                    if ($item->apartment_code) {
                        $allFeatures[] = 'Mã: ' . $item->apartment_code;
                    }
                    if ($item->balcony_direction && isset($attributeMap[$item->balcony_direction])) {
                        $allFeatures[] = 'BC: ' . $attributeMap[$item->balcony_direction];
                    }
                    if ($item->interior && isset($attributeMap[$item->interior])) {
                        $allFeatures[] = '<i class="fa fa-couch"></i> ' . $attributeMap[$item->interior];
                    }
                    if ($item->year_built) {
                        $allFeatures[] = 'Năm: ' . $item->year_built;
                    }

                    // Đất / Mặt bằng
                    if ($item->land_type && isset($attributeMap[$item->land_type])) {
                        $allFeatures[] = $attributeMap[$item->land_type];
                    }
                    if ($item->land_width && $item->land_length) {
                        $allFeatures[] =
                            '<i class="fa fa-arrows-h"></i> ' .
                            (float) $item->land_width .
                            'x' .
                            (float) $item->land_length .
                            'm';
                    } elseif ($item->land_width) {
                        $allFeatures[] = 'Ngang: ' . (float) $item->land_width . 'm';
                    } elseif ($item->land_length) {
                        $allFeatures[] = 'Dài: ' . (float) $item->land_length . 'm';
                    }
                    if ($item->road_frontage) {
                        $allFeatures[] = 'Mặt tiền: ' . (float) $item->road_frontage . 'm';
                    }
                    if ($item->road_width) {
                        $allFeatures[] = 'Đường ' . (float) $item->road_width . 'm';
                    }

                    foreach ($item->amenities as $amenity) {
                        $allFeatures[] = $amenity->languages->first()->pivot->name ?? '';
                    }

                    $allFeatures = array_filter($allFeatures);
                    $displayFeatures = array_slice($allFeatures, 0, 12);
                    $moreCount = count($allFeatures) - count($displayFeatures);
                @endphp
                @foreach ($displayFeatures as $feature)
                    <span class="gl-card-feature-tag">{!! $feature !!}</span>
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
            <span class="gl-card-time"><i class="fa fa-clock-o uk-margin-small-right"></i>
                Cập nhật {{ $timeUpdate }}</span>
        </div>
    </div>
</div>
