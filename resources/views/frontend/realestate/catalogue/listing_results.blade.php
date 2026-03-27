@if ($realEstates->count() > 0)
    <div class="hp-listing-results">
        @foreach ($realEstates as $item)
            @include('frontend.component.real_estate_card_horizontal', [
                'item' => $item,
                'attributeMap' => $attributeMap,
            ])
        @endforeach
    </div>

    @if ($realEstates->hasPages())
        <div class="uk-margin-large-top ajax-pagination">
            {{ $realEstates->links('frontend.component.pagination') }}
        </div>
    @endif
@else
    <div class="hp-empty-state uk-text-center">
        <i class="fa-solid fa-box-open" style="font-size: 80px; color: #ddd; margin-bottom: 20px; display: block;"></i>
        <p class="uk-text-muted uk-margin-top">Không tìm thấy bản ghi phù hợp.
        </p>
        <a href="{{ request()->url() }}" class="uk-button uk-button-link">Đặt lại bộ lọc</a>
    </div>
@endif
