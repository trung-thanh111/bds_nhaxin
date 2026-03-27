@php
    $lang = $item->languages->first()->pivot;
    $timeUpdate = diff_for_humans($item->updated_at);
    $displayAddress = format_address($item);
    $mapUrl =
        extract_map_url($item->iframe_map) ?:
        'https://www.google.com/maps/search/?api=1&query=' . urlencode($displayAddress);

    $allFeatures = [];
    if ($item->apartment_count > 0) {
        $allFeatures[] = $item->apartment_count . ' Căn hộ';
    }
    if ($item->block_count > 0) {
        $allFeatures[] = $item->block_count . ' Tòa';
    }
    foreach ($item->amenities ?? [] as $amenity) {
        $allFeatures[] = $amenity->languages->first()->pivot->name ?? '';
    }
    $allFeatures = array_filter($allFeatures);
    $displayFeatures = array_slice($allFeatures, 0, 8);
    $moreCount = count($allFeatures) - count($displayFeatures);
@endphp

<div class="gl-property-card-horizontal gl-project-card-horizontal">
    <div class="uk-grid uk-grid-collapse" data-uk-grid-margin>
        <div class="uk-width-medium-2-5 uk-width-large-1-3">
            <div class="gl-card-img-wrapper">
                <a href="{{ url($lang->canonical . '.html') }}">
                    <img src="{{ image($item->cover_image) }}" alt="{{ $lang->name }}">
                </a>
                <a href="{{ route('contact.index') }}" class="gl-card-badge">LIÊN HỆ</a>
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
                    <span class="gl-card-price-val" style="color: var(--main-green); font-weight: 700;">
                        Thỏa thuận
                    </span>
                    <span class="gl-card-separator"><i class="fa fa-circle"></i></span>
                    @if ($item->area)
                        <span class="gl-card-area">{{ $item->area }} m²</span>
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

                <div class="gl-card-description">
                    {!! strip_tags($lang->description ?: $lang->content) !!}
                </div>

                <div class="gl-card-footer uk-flex uk-flex-middle uk-flex-space-between">
                    <span class="gl-card-id">Mã: {{ $item->code }}</span>
                    <span class="gl-card-time uk-margin-left"><i class="fa fa-clock-o"></i>
                        <span class="uk-visible-large">Cập nhật&nbsp;</span>{{ $timeUpdate }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
