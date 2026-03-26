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
    foreach ($item->amenities ?? [] as $amenity) {
        $allFeatures[] = $amenity->languages->first()->pivot->name ?? '';
    }
    $allFeatures = array_filter($allFeatures);
    $displayFeatures = array_slice($allFeatures, 0, 10);
    $moreCount = count($allFeatures) - count($displayFeatures);

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
        $prices[] = ['label' => 'Giá:', 'val' => 'Liên hệ'];
    }

    $mainPrice = $item->price_sale > 0 ? $item->price_sale : $item->price_rent;
    $pricePerM2 = $mainPrice > 0 && $area > 0 ? $mainPrice / $area : 0;
@endphp

<div class="gl-property-card-horizontal">
    <div class="uk-grid uk-grid-collapse" data-uk-grid-margin>
        <div class="uk-width-medium-2-5 uk-width-large-1-3">
            <div class="gl-card-img-wrapper">
                <a href="{{ url($lang->canonical . '.html') }}">
                    <img src="{{ image($item->image) }}" alt="{{ $lang->name }}">
                </a>
                <a href="{{ route('contact.index') }}" class="gl-card-badge">LIÊN HỆ NGAY</a>
                <a href="{{ $mapUrl }}" target="_blank" class="gl-card-map-btn">
                    <i class="fa fa-location-arrow"></i> Chỉ đường
                </a>
            </div>
        </div>
        <div class="uk-width-medium-3-5 uk-width-large-2-3">
            <div class="gl-card-body">
                <h3 class="gl-card-title">
                    <a href="{{ url($lang->canonical . '.html') }}">{{ $lang->name }}</a>
                </h3>

                <div class="gl-card-price-area-row uk-flex uk-flex-middle">
                    @foreach ($prices as $index => $p)
                        <div class="gl-card-price-group uk-flex uk-flex-middle">
                            <span class="gl-card-price-label">{{ $p['label'] }}</span>
                            <span class="gl-card-price-val">{{ $p['val'] }}</span>
                        </div>
                        @if ($index < count($prices) - 1)
                            <span class="gl-card-separator"><i class="fa fa-circle"></i></span>
                        @endif
                    @endforeach

                    @if ($area > 0)
                        <span class="gl-card-separator"><i class="fa fa-circle"></i></span>
                        <span class="gl-card-area">{{ $area }} m²</span>
                    @endif

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
                    <div class="gl-card-address" style="font-style: italic;">
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


                <div class="gl-card-description">
                    {!! strip_tags($lang->content ?: $lang->description) !!}
                </div>

                <div class="gl-card-footer uk-flex uk-flex-middle uk-flex-space-between">
                    <div class="gl-card-meta">
                        <span class="gl-card-id">Mã: {{ $item->code }}</span>
                        <span class="gl-card-time uk-margin-left"><i class="fa fa-clock-o"></i> Cập nhật
                            {{ $timeUpdate }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
