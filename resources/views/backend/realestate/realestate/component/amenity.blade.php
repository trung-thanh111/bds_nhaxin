<div class="ibox">
    <div class="ibox-title">
        <h5>Tiện ích</h5>
    </div>
    <div class="ibox-content">
        @if (isset($amenityCatalogues) && $amenityCatalogues->count() > 0)
            @foreach ($amenityCatalogues as $catalogue)
                @if ($catalogue->amenities->count() > 0)
                    <div class="amenity-group mb20">
                        <div class="uk-flex uk-flex-middle uk-flex-space-between mb10 border-bottom pb10">
                            <strong
                                class="text-primary">{{ $catalogue->languages->first()->pivot->name ?? 'N/A' }}</strong>
                            <label class="mb0 cursor-pointer uk-flex uk-flex-middle" style="gap: 10px;">
                                <input type="checkbox" class="check-all-amenities"
                                    style="width: 18px; height: 18px; margin-top: 0;">
                                <span style="line-height: 18px;"><small>Chọn tất cả</small></span>
                            </label>
                        </div>
                        <div class="row">
                            @foreach ($catalogue->amenities as $amenity)
                                <div class="col-lg-3 mb5">
                                    <label class="cursor-pointer uk-flex uk-flex-middle mb10"
                                        style="gap: 10px; font-weight: normal;">
                                        <input type="checkbox" name="amenities[]" value="{{ $amenity->id }}"
                                            {{ isset($model) && $model->amenities && $model->amenities->contains($amenity->id) ? 'checked' : '' }}
                                            class="amenity-item"
                                            style="width: 18px; height: 18px; margin-top: 0; margin-right: 0px !important">
                                        <span
                                            style="line-height: 18px;">{{ $amenity->languages->first()->pivot->name ?? 'N/A' }}</span>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endforeach
        @else
            <p class="text-danger">Chưa có dữ liệu tiện ích.</p>
        @endif
    </div>
</div>

<script>
    $(document).ready(function() {
        // Handle Check All per group
        $('.check-all-amenities').on('change', function() {
            let isChecked = $(this).prop('checked');
            $(this).closest('.amenity-group').find('.amenity-item').prop('checked', isChecked);
        });

        // Update Check All state when individual items change
        $('.amenity-item').on('change', function() {
            let group = $(this).closest('.amenity-group');
            let total = group.find('.amenity-item').length;
            let checked = group.find('.amenity-item:checked').length;
            group.find('.check-all-amenities').prop('checked', total === checked);
        });

        // Initial state for Check All
        $('.amenity-group').each(function() {
            let total = $(this).find('.amenity-item').length;
            let checked = $(this).find('.amenity-item:checked').length;
            if (total > 0 && total === checked) {
                $(this).find('.check-all-amenities').prop('checked', true);
            }
        });
    });
</script>
