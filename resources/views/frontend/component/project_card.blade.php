@php
    $lang = $item->languages->first()->pivot;
    $timeUpdate = diff_for_humans($item->updated_at);
    $displayAddress = format_address($item);
    $mapUrl =
        extract_map_url($item->iframe_map) ?:
        'https://www.google.com/maps/search/?api=1&query=' . urlencode($displayAddress);
@endphp

<div class="gl-property-card">
    <div class="gl-card-img-wrapper">
        <a href="{{ url($lang->canonical . '.html') }}">
            <div class="img-cover">
                <img src="{{ image($item->cover_image) }}" alt="{{ $lang->name }}">
            </div>
        </a>
        <a href="{{ route('contact.index') }}" class="gl-card-badge">Liên hệ</a>
        <a href="{{ $mapUrl }}" target="_blank" class="gl-card-map-btn">
            <i class="fa fa-location-arrow"></i> Chỉ đường
        </a>
    </div>
    <div class="gl-card-body">
        <h3 class="gl-card-title">
            <a href="{{ url($lang->canonical . '.html') }}">{{ $lang->name }}</a>
        </h3>

        <!-- Price & Area -->
        <div class="gl-card-price-row uk-flex uk-flex-middle">
            <div class="gl-card-price-group uk-flex uk-flex-middle">
                <span class="gl-card-price-val" style="color: #27ae60; font-weight: 700;">Liên hệ</span>
            </div>
            <span class="gl-card-separator"><i class="fa fa-circle"></i></span>
            <span class="gl-card-area">{{ $item->area }} m²</span>
        </div>

        <!-- Address -->
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
            {!! Str::limit(strip_tags($lang->content), 200) !!}
        </div>

        <div class="gl-card-footer">
            <span class="gl-card-id">Mã: {{ $item->code }}</span>
            <span class="gl-card-time"><i class="fa fa-clock-o uk-margin-small-right"></i>
                <span class="uk-visible-large">Cập nhật&nbsp;</span>{{ $timeUpdate }}</span>
        </div>
    </div>
</div>
