@if ($projects->count() > 0)
    <div class="hp-listing-results">
        @foreach ($projects as $item)
            @include('frontend.component.project_card_horizontal', [
                'item' => $item,
            ])
        @endforeach
    </div>

    @if ($projects->hasPages())
        <div class="uk-margin-large-top ajax-pagination">
            {{ $projects->links('frontend.component.pagination') }}
        </div>
    @endif
@else
    <div class="hp-empty-state uk-text-center">
        <i class="fa-solid fa-box-open"
            style="font-size: 80px; color: #ddd; margin-bottom: 20px; display: block;"></i>
        <p class="uk-text-muted uk-margin-top">Không tìm thấy bản ghi phù hợp.</p>
        <a href="{{ request()->url() }}" class="uk-button uk-button-link">Đặt lại bộ lọc</a>
    </div>
@endif
