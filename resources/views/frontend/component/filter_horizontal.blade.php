    @php
        // Identify branch based on URL if not explicitly passed
        $currentUrl = request()->url();
        $isRentalBranch = strpos($currentUrl, 'cho-thue') !== false;
        $transactionType = $transactionType ?? request('transaction_type', $isRentalBranch ? '75' : '74');

        $priceOptions = [
            '' => 'Tất cả mức giá',
            '0-0' => 'Thỏa thuận',
            '0-2' => 'Dưới 2 tỷ',
            '2-3' => '2 - 3 tỷ',
            '3-5' => '3 - 5 tỷ',
            '5-7' => '5 - 7 tỷ',
            '7-10' => '7 - 10 tỷ',
            '10-999' => 'Trên 10 tỷ',
        ];

        if ($transactionType == '75') {
            $priceOptions = [
                '' => 'Tất cả mức giá',
                '0-0' => 'Thỏa thuận',
                '0-1' => 'Dưới 1 triệu',
                '1-3' => '1 - 3 triệu',
                '3-5' => '3 - 5 triệu',
                '5-10' => '5 - 10 triệu',
                '10-40' => '10 - 40 triệu',
                '40-70' => '40 - 70 triệu',
                '70-100' => '70 - 100 triệu',
                '100-9999' => 'Trên 100 triệu',
            ];
        }

        $areaOptions = [
            '' => 'Tất cả diện tích',
            '0-30' => 'Dưới 30 m²',
            '30-50' => '30 - 50 m²',
            '50-80' => '50 - 80 m²',
            '80-100' => '80 - 100 m²',
            '100-150' => '100 - 150 m²',
            '150-200' => '150 - 200 m²',
            '200-300' => '200 - 300 m²',
            '300-500' => '300 - 500 m²',
            '500-99999' => 'Trên 500 m²',
        ];
    @endphp
    <div class="uk-container uk-container-center">
        <div class="hp-filter-wrapper uk-flex uk-flex-middle uk-flex-space-between">
            <div class="hp-filter-main uk-flex uk-flex-middle">
                <!-- All Filters Button -->
                <button class="hp-filter-btn hp-filter-btn-all uk-margin-right"
                    data-uk-modal="{target:'#modal-all-filters'}">
                    <i class="fa fa-filter"></i> <span>Lọc</span>
                </button>

                <!-- Property Type Dropdown -->
                <div class="hp-filter-dropdown" data-uk-dropdown="{mode:'click', pos:'bottom-left'}">
                    <button class="hp-filter-btn">
                        <i class="fa fa-home"></i>
                        <span id="bar-selected-property">Loại hình</span>
                        <i class="fa fa-chevron-down uk-margin-small-left"></i>
                    </button>
                    <div class="uk-dropdown hp-dropdown-panel">
                        <div class="hp-dropdown-header">Loại bất động sản</div>
                        <div class="hp-dropdown-body">
                            <div class="hp-selection-list">
                                @if (isset($propertyTypes))
                                    @foreach ($propertyTypes as $type)
                                        <label class="hp-selection-item uk-flex uk-flex-middle uk-flex-space-between">
                                            <span>{{ $type->languages->first()->pivot->name ?? '' }}</span>
                                            <input type="checkbox" class="bar-sync-input"
                                                data-name="real_estate_catalogue_id[]" value="{{ $type->id }}"
                                                @if (is_array(request('real_estate_catalogue_id')) && in_array($type->id, request('real_estate_catalogue_id'))) checked @endif>
                                        </label>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                        <div class="hp-dropdown-footer">
                            <button class="uk-button hp-btn-apply-bar">Áp dụng</button>
                        </div>
                    </div>
                </div>

                <!-- Price Range Dropdown -->
                <div class="hp-filter-dropdown" data-uk-dropdown="{mode:'click', pos:'bottom-left'}">
                    <button class="hp-filter-btn">
                        <i class="fa fa-tag"></i>
                        <span id="bar-selected-price">Khoảng giá</span>
                        <i class="fa fa-chevron-down uk-margin-small-left"></i>
                    </button>
                    <div class="uk-dropdown hp-dropdown-panel">
                        <div class="hp-dropdown-header">Khoảng giá</div>
                        <div class="hp-dropdown-body">
                            <div class="hp-custom-range-mini">
                                <div class="uk-grid uk-grid-small">
                                    <div class="uk-width-2-5"><input type="number" placeholder="Từ"
                                            class="hp-mini-input bar-sync-input" data-name="price_min"
                                            value="{{ request('price_min') }}"></div>
                                    <div class="uk-width-1-5 uk-text-center uk-flex uk-flex-middle uk-flex-center">→
                                    </div>
                                    <div class="uk-width-2-5"><input type="number" placeholder="Đến"
                                            class="hp-mini-input bar-sync-input" data-name="price_max"
                                            value="{{ request('price_max') }}"></div>
                                </div>
                            </div>
                            @include('frontend.component.filter_range_list', [
                                'name' => 'price',
                                'options' => $priceOptions,
                                'isBar' => true,
                            ])
                        </div>
                        <div class="hp-dropdown-footer">
                            <button class="uk-button hp-btn-apply-bar">Áp dụng</button>
                        </div>
                    </div>
                </div>

                <!-- Area Dropdown -->
                <div class="hp-filter-dropdown" data-uk-dropdown="{mode:'click', pos:'bottom-left'}">
                    <button class="hp-filter-btn">
                        <i class="fa fa-expand"></i>
                        <span id="bar-selected-area">Diện tích</span>
                        <i class="fa fa-chevron-down uk-margin-small-left"></i>
                    </button>
                    <div class="uk-dropdown hp-dropdown-panel">
                        <div class="hp-dropdown-header">Diện tích</div>
                        <div class="hp-dropdown-body">
                            <div class="hp-custom-range-mini">
                                <div class="uk-grid uk-grid-small">
                                    <div class="uk-width-2-5"><input type="number" placeholder="Từ"
                                            class="hp-mini-input bar-sync-input" data-name="area_min"
                                            value="{{ request('area_min') }}"></div>
                                    <div class="uk-width-1-5 uk-text-center uk-flex uk-flex-middle uk-flex-center">→
                                    </div>
                                    <div class="uk-width-2-5"><input type="number" placeholder="Đến"
                                            class="hp-mini-input bar-sync-input" data-name="area_max"
                                            value="{{ request('area_max') }}"></div>
                                </div>
                            </div>
                            @include('frontend.component.filter_range_list', [
                                'name' => 'area',
                                'options' => $areaOptions,
                                'isBar' => true,
                            ])
                        </div>
                        <div class="hp-dropdown-footer">
                            <button class="uk-button hp-btn-apply-bar">Áp dụng</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="hp-filter-reset">
                <a href="{{ request()->url() }}" class="hp-filter-link"><i class="fa fa-undo"></i> Đặt lại</a>
            </div>
        </div>
    </div>
    </div>

    <!-- Modal All Filters -->
    <div id="modal-all-filters" class="uk-modal hp-modal-filter">
        <div class="uk-modal-dialog">
            <!-- Main View -->
            <div id="hp-view-main" class="hp-modal-view active">
                <div class="uk-modal-header uk-flex uk-flex-middle uk-flex-space-between">
                    <h3 class="uk-modal-title uk-margin-remove">Bộ lọc</h3>
                    <a class="uk-modal-close"><i class="fa fa-times" style="font-size: 20px; color: #999;"></i></a>
                </div>
                <div class="uk-modal-body">
                    <form action="{{ request()->url() }}" method="GET" id="filter-form">
                        <div class="uk-grid uk-grid-medium" data-uk-grid-margin>
                            <!-- Transaction Type -->
                            <div class="uk-width-1-1">
                                <label class="group-label">Hình thức</label>
                                <div class="hp-btn-toggle-group uk-flex">
                                    <label class="uk-width-1-2">
                                        <input type="radio" name="transaction_type" value="74"
                                            class="transaction-radio" {{ $transactionType != '75' ? 'checked' : '' }}
                                            style="display:none">
                                        <span
                                            class="uk-button uk-width-1-1 hp-toggle-btn {{ $transactionType != '75' ? 'active' : '' }}">Tìm
                                            mua</span>
                                    </label>
                                    <label class="uk-width-1-2">
                                        <input type="radio" name="transaction_type" value="75"
                                            class="transaction-radio" {{ $transactionType == '75' ? 'checked' : '' }}
                                            style="display:none">
                                        <span
                                            class="uk-button uk-width-1-1 hp-toggle-btn {{ $transactionType == '75' ? 'active' : '' }}">Tìm
                                            thuê</span>
                                    </label>
                                </div>
                            </div>

                            <!-- Property Type Trigger -->
                            <div class="uk-width-1-1 uk-margin-top">
                                <label class="group-label">Loại bất động sản</label>
                                <div class="hp-input-box uk-flex uk-flex-middle uk-flex-space-between"
                                    onclick="showSubView('hp-view-property')">
                                    <span class="uk-text-muted" id="selected-property-text">Tất cả loại hình</span>
                                    <span class="uk-text-primary uk-text-small" style="font-weight:600">+ Thêm</span>
                                </div>
                            </div>

                            <!-- Location Trigger -->
                            <div class="uk-width-1-1 uk-margin-top">
                                <label class="group-label">Khu vực & Dự án</label>
                                <div class="hp-input-box uk-flex uk-flex-middle uk-flex-space-between"
                                    onclick="showSubView('hp-view-location')">
                                    <div class="uk-flex uk-flex-middle">
                                        <i class="fa fa-map-marker-alt uk-margin-small-right"
                                            style="color:var(--main-color)"></i>
                                        <span class="uk-text-muted" id="selected-location-text">Trên toàn quốc</span>
                                    </div>
                                    <i class="fa fa-chevron-right uk-text-muted"></i>
                                </div>
                            </div>

                            <!-- Price & Area Triggers -->
                            <div class="uk-width-1-1 uk-margin-top">
                                <div class="uk-grid uk-grid-small" data-uk-grid-margin>
                                    <div class="uk-width-1-2">
                                        <label class="group-label">Khoảng giá</label>
                                        <div class="hp-input-box uk-flex uk-flex-middle uk-flex-space-between"
                                            onclick="showSubView('hp-view-price')">
                                            <div class="uk-flex uk-flex-middle">
                                                <i class="fa fa-money-bill-wave uk-margin-small-right"
                                                    style="color:var(--main-color)"></i>
                                                <span class="uk-text-muted" id="selected-price-text">Tất cả</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="uk-width-1-2">
                                        <label class="group-label">Diện tích</label>
                                        <div class="hp-input-box uk-flex uk-flex-middle uk-flex-space-between"
                                            onclick="showSubView('hp-view-area')">
                                            <div class="uk-flex uk-flex-middle">
                                                <i class="fa fa-expand uk-margin-small-right"
                                                    style="color:var(--main-color)"></i>
                                                <span class="uk-text-muted" id="selected-area-text">Tất cả</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Beds & Baths -->
                            <div class="uk-width-1-1 uk-margin-top">
                                <label class="group-label">Số phòng ngủ</label>
                                <div class="hp-spec-selector uk-flex uk-flex-middle">
                                    <label><input type="radio" name="bedrooms" value="" style="display:none" @if (!request('bedrooms')) checked @endif><span class="spec-item @if (!request('bedrooms')) active @endif">Tất cả</span></label>
                                    @foreach (['1', '2', '3', '4', '5+'] as $val)
                                        @php $cleanVal = str_replace('+', '', $val); @endphp
                                        <label><input type="radio" name="bedrooms" value="{{ $cleanVal }}"
                                                style="display:none"
                                                @if (request('bedrooms') == $cleanVal) checked @endif><span
                                                class="spec-item @if (request('bedrooms') == $cleanVal) active @endif">{{ $val }}</span></label>
                                    @endforeach
                                </div>
                            </div>

                            <div class="uk-width-1-1 uk-margin-top">
                                <label class="group-label">Số phòng tắm, vệ sinh</label>
                                <div class="hp-spec-selector uk-flex uk-flex-middle">
                                    <label><input type="radio" name="bathrooms" value="" style="display:none" @if (!request('bathrooms')) checked @endif><span class="spec-item @if (!request('bathrooms')) active @endif">Tất cả</span></label>
                                    @foreach (['1', '2', '3', '4', '5+'] as $val)
                                        @php $cleanVal = str_replace('+', '', $val); @endphp
                                        <label><input type="radio" name="bathrooms" value="{{ $cleanVal }}"
                                                style="display:none"
                                                @if (request('bathrooms') == $cleanVal) checked @endif><span
                                                class="spec-item @if (request('bathrooms') == $cleanVal) active @endif">{{ $val }}</span></label>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Directions Triggers -->
                            <div class="uk-width-1-1 uk-margin-top">
                                <div class="uk-grid uk-grid-small" data-uk-grid-margin>
                                    <div class="uk-width-1-2">
                                        <label class="group-label">Hướng nhà</label>
                                        <div class="hp-input-box uk-flex uk-flex-middle uk-flex-space-between"
                                            onclick="showSubView('hp-view-direction')">
                                            <span class="uk-text-muted" id="selected-house-direction-text">Tất cả
                                                hướng</span>
                                            <i class="fa fa-chevron-right uk-text-muted"></i>
                                        </div>
                                    </div>
                                    <div class="uk-width-1-2">
                                        <label class="group-label">Hướng ban công</label>
                                        <div class="hp-input-box uk-flex uk-flex-middle uk-flex-space-between"
                                            onclick="showSubView('hp-view-balcony')">
                                            <span class="uk-text-muted" id="selected-balcony-direction-text">Tất cả
                                                hướng</span>
                                            <i class="fa fa-chevron-right uk-text-muted"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Furniture & Amenities -->
                            <div class="uk-width-1-1 uk-margin-top">
                                <label class="group-label">Nội thất</label>
                                <div class="hp-pill-container uk-flex uk-flex-middle uk-flex-wrap">
                                    @if (isset($furnitures))
                                        @foreach ($furnitures as $item)
                                            <label class="hp-pill-label">
                                                <input type="checkbox" name="furniture[]"
                                                    value="{{ $item->id }}" style="display:none"
                                                    @if (is_array(request('furniture')) && in_array($item->id, request('furniture'))) checked @endif>
                                                <span
                                                    class="hp-pill">{{ $item->languages->first()->pivot->name ?? '' }}</span>
                                            </label>
                                        @endforeach
                                    @endif
                                </div>
                            </div>

                            <div class="uk-width-1-1 uk-margin-top">
                                <label class="group-label">Tiện ích</label>
                                <div class="hp-pill-container uk-flex uk-flex-middle uk-flex-wrap">
                                    @if (isset($amenities))
                                        @foreach ($amenities as $item)
                                            <label class="hp-pill-label">
                                                <input type="checkbox" name="amenity[]" value="{{ $item->id }}"
                                                    style="display:none"
                                                    @if (is_array(request('amenity')) && in_array($item->id, request('amenity'))) checked @endif>
                                                <span class="hp-pill">
                                                    @if ($item->icon)
                                                        <i class="{{ $item->icon }} uk-margin-small-right"></i>
                                                    @endif
                                                    {{ $item->languages->first()->pivot->name ?? '' }}
                                                </span>
                                            </label>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="uk-modal-footer uk-text-right">
                    <button class="uk-button uk-modal-close hp-btn-modal-cancel">Hủy</button>
                    <button type="submit" form="filter-form" class="uk-button hp-btn-modal-apply">Xem kết
                        quả</button>
                </div>
            </div>

            <!-- Sub-View: Property Types -->
            <div id="hp-view-property" class="hp-modal-view">
                <div class="uk-modal-header uk-flex uk-flex-middle">
                    <a onclick="hideSubView()" class="uk-margin-right"><i class="fa fa-chevron-left"
                            style="color:#333"></i></a>
                    <h3 class="uk-modal-title uk-margin-remove">Loại bất động sản</h3>
                </div>
                <div class="uk-modal-body">
                    <div class="hp-selection-list">
                        @if (isset($propertyTypes))
                            @foreach ($propertyTypes as $type)
                                <label class="hp-selection-item uk-flex uk-flex-middle uk-flex-space-between">
                                    <span>{{ $type->languages->first()->pivot->name ?? '' }}</span>
                                    <input type="checkbox" class="uk-checkbox" name="real_estate_catalogue_id[]"
                                        value="{{ $type->id }}" form="filter-form"
                                        @if (is_array(request('real_estate_catalogue_id')) && in_array($type->id, request('real_estate_catalogue_id'))) checked @endif>
                                </label>
                            @endforeach
                        @endif
                    </div>
                </div>
                <div class="uk-modal-footer">
                    <button class="uk-button hp-btn-main uk-width-1-1" onclick="hideSubView()">Xác nhận</button>
                </div>
            </div>

            <!-- Sub-View: Directions -->
            <div id="hp-view-direction" class="hp-modal-view">
                <div class="uk-modal-header uk-flex uk-flex-middle">
                    <a onclick="hideSubView()" class="uk-margin-right"><i class="fa fa-chevron-left"
                            style="color:#333"></i></a>
                    <h3 class="uk-modal-title uk-margin-remove">Hướng nhà</h3>
                </div>
                <div class="uk-modal-body">
                    <div class="hp-selection-list">
                        @php $hDirs = ['' => 'Tất cả hướng', 12=>'Bắc', 13=>'Đông Bắc', 9=>'Đông', 15=>'Đông Nam', 11=>'Nam', 16=>'Tây Nam', 10=>'Tây', 14=>'Tây Bắc']; @endphp
                        @foreach ($hDirs as $id => $name)
                            <label class="hp-selection-item uk-flex uk-flex-middle uk-flex-space-between">
                                <span>{{ $name }}</span>
                                <input type="radio" class="uk-radio" name="house_direction"
                                    value="{{ $id }}" form="filter-form"
                                    @if (request('house_direction', '') == $id) checked @endif>
                            </label>
                        @endforeach
                    </div>
                </div>
                <div class="uk-modal-footer">
                    <button class="uk-button hp-btn-main uk-width-1-1" onclick="hideSubView()">Xác nhận</button>
                </div>
            </div>

            <div id="hp-view-balcony" class="hp-modal-view">
                <div class="uk-modal-header uk-flex uk-flex-middle">
                    <a onclick="hideSubView()" class="uk-margin-right"><i class="fa fa-chevron-left"
                            style="color:#333"></i></a>
                    <h3 class="uk-modal-title uk-margin-remove">Hướng ban công</h3>
                </div>
                <div class="uk-modal-body">
                    <div class="hp-selection-list">
                        @php $bDirs = ['' => 'Tất cả hướng', 20=>'Bắc', 21=>'Đông Bắc', 17=>'Đông', 23=>'Đông Nam', 19=>'Nam', 24=>'Tây Nam', 18=>'Tây', 22=>'Tây Bắc']; @endphp
                        @foreach ($bDirs as $id => $name)
                            <label class="hp-selection-item uk-flex uk-flex-middle uk-flex-space-between">
                                <span>{{ $name }}</span>
                                <input type="radio" class="uk-radio" name="balcony_direction"
                                    value="{{ $id }}" form="filter-form"
                                    @if (request('balcony_direction', '') == $id) checked @endif>
                            </label>
                        @endforeach
                    </div>
                </div>
                <div class="uk-modal-footer">
                    <button class="uk-button hp-btn-main uk-width-1-1" onclick="hideSubView()">Xác nhận</button>
                </div>
            </div>

            <div id="hp-view-location" class="hp-modal-view">
                <div class="uk-modal-header uk-flex uk-flex-middle">
                    <a onclick="hideSubView()" class="uk-margin-right"><i class="fa fa-chevron-left"
                            style="color:#333"></i></a>
                    <h3 class="uk-modal-title uk-margin-remove">Khu vực & Loại hình</h3>
                </div>
                <div class="uk-modal-body">
                    <div class="gl-switch-container">
                        <span class="gl-switch-text">Tìm theo địa chỉ mới sau sáp nhập</span>
                        <label class="gl-switch">
                            <input type="checkbox" id="switch-location-mode-filter" name="is_after_merger"
                                value="1" {{ request('is_after_merger') ? 'checked' : '' }}>
                            <span class="gl-slider"></span>
                        </label>
                    </div>

                    {{-- After Merger (2 Levels) --}}
                    <div id="gl-location-after-filter"
                        style="{{ request('is_after_merger') ? '' : 'display: none;' }}">
                        <div class="gl-form-group">
                            <label class="gl-form-label">Thành Phố</label>
                            <select name="province_code" class="gl-select location province setupSelect2Filter"
                                data-target="wards" data-source="after" form="filter-form">
                                <option value="0">[Chọn Thành Phố]</option>
                                @if (isset($provinces))
                                    @foreach ($provinces as $key => $val)
                                        <option value="{{ $key }}"
                                            {{ request('province_code') == $key ? 'selected' : '' }}>
                                            {{ $val }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>

                        <div class="gl-form-group">
                            <label class="gl-form-label">Phường/Xã</label>
                            <select name="ward_code" class="gl-select wards setupSelect2Filter" data-source="after"
                                form="filter-form">
                                <option value="0">[Chọn Phường/Xã]</option>
                            </select>
                        </div>
                    </div>

                    {{-- Before Merger (3 Levels) --}}
                    <div id="gl-location-before-filter"
                        style="{{ request('is_after_merger') ? 'display: none;' : '' }}">
                        <div class="gl-form-group">
                            <label class="gl-form-label">Thành Phố</label>
                            <select name="old_province_code" class="gl-select location province setupSelect2Filter"
                                data-target="old_districts" data-source="before" form="filter-form">
                                <option value="0">[Chọn Thành Phố]</option>
                                @if (isset($old_provinces))
                                    @foreach ($old_provinces as $key => $val)
                                        <option value="{{ $key }}"
                                            {{ request('old_province_code') == $key ? 'selected' : '' }}>
                                            {{ $val }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>

                        <div class="gl-form-group">
                            <label class="gl-form-label">Quận/Huyện</label>
                            <select name="old_district_code"
                                class="gl-select location old_districts setupSelect2Filter" data-target="old_wards"
                                data-source="before" form="filter-form">
                                <option value="0">[Chọn Quận/Huyện]</option>
                            </select>
                        </div>

                        <div class="gl-form-group">
                            <label class="gl-form-label">Phường/Xã</label>
                            <select name="old_ward_code" class="gl-select old_wards setupSelect2Filter"
                                data-source="before" form="filter-form">
                                <option value="0">[Chọn Phường/Xã]</option>
                            </select>
                        </div>
                    </div>

                </div>
                <div class="uk-modal-footer">
                    <button class="uk-button hp-btn-main uk-width-1-1" onclick="hideSubView()">Xác nhận</button>
                </div>
            </div>

            <div id="hp-view-price" class="hp-modal-view">
                <div class="uk-modal-header uk-flex uk-flex-middle">
                    <a onclick="hideSubView()" class="uk-margin-right"><i class="fa fa-chevron-left"
                            style="color:#333"></i></a>
                    <h3 class="uk-modal-title uk-margin-remove">Khoảng giá</h3>
                </div>
                <div class="uk-modal-body">
                    <div class="hp-custom-range-wrapper uk-margin-bottom">
                        <label class="group-label">Nhập khoảng giá tùy chỉnh
                            ({{ $transactionType == '75' ? 'triệu' : 'tỷ' }})</label>
                        <div class="uk-grid uk-grid-small" data-uk-grid-margin>
                            <div class="uk-width-1-2">
                                <input type="number" name="price_min" placeholder="Từ"
                                    class="uk-width-1-1 hp-custom-input" form="filter-form"
                                    value="{{ request('price_min') }}">
                            </div>
                            <div class="uk-width-1-2">
                                <input type="number" name="price_max" placeholder="Đến"
                                    class="uk-width-1-1 hp-custom-input" form="filter-form"
                                    value="{{ request('price_max') }}">
                            </div>
                        </div>
                    </div>
                    @include('frontend.component.filter_range_list', [
                        'name' => 'price',
                        'options' => $priceOptions,
                    ])
                </div>
                <div class="uk-modal-footer"><button class="uk-button hp-btn-main uk-width-1-1"
                        onclick="hideSubView()">Xác nhận</button></div>
            </div>

            <div id="hp-view-area" class="hp-modal-view">
                <div class="uk-modal-header uk-flex uk-flex-middle">
                    <a onclick="hideSubView()" class="uk-margin-right"><i class="fa fa-chevron-left"
                            style="color:#333"></i></a>
                    <h3 class="uk-modal-title uk-margin-remove">Diện tích</h3>
                </div>
                <div class="uk-modal-body">
                    <div class="hp-custom-range-wrapper uk-margin-bottom">
                        <label class="group-label">Nhập diện tích tùy chỉnh (m²)</label>
                        <div class="uk-grid uk-grid-small" data-uk-grid-margin>
                            <div class="uk-width-1-2">
                                <input type="number" name="area_min" placeholder="Từ"
                                    class="uk-width-1-1 hp-custom-input" form="filter-form"
                                    value="{{ request('area_min') }}">
                            </div>
                            <div class="uk-width-1-2">
                                <input type="number" name="area_max" placeholder="Đến"
                                    class="uk-width-1-1 hp-custom-input" form="filter-form"
                                    value="{{ request('area_max') }}">
                            </div>
                        </div>
                    </div>
                    @include('frontend.component.filter_range_list', [
                        'name' => 'area',
                        'options' => $areaOptions,
                    ])
                </div>
                <div class="uk-modal-footer"><button class="uk-button hp-btn-main uk-width-1-1"
                        onclick="hideSubView()">Xác nhận</button></div>
            </div>
        </div>
    </div>

    <style>
        /* New Filter Bar Styles */
        .hp-filter-horizontal, 
        .hp-filter-horizontal *:not(.fa),
        .hp-modal-filter,
        .hp-modal-filter *:not(.fa),
        .select2-container *,
        .select2-dropdown * {
            font-family: inherit !important;
        }

        .hp-filter-horizontal {
            background: #fff;
            padding: 10px 0;
            border-bottom: 1px solid #eee;
            position: sticky;
            top: 0;
            z-index: 1000;
            height: 65px;
            display: flex;
            align-items: center;
        }

        .hp-filter-horizontal .uk-container {
            padding: 0 15px;
            width: 100%;
        }

        .hp-filter-wrapper {
            gap: 15px;
        }

        .hp-filter-btn {
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 6px;
            padding: 8px 16px;
            font-size: 14px;
            font-weight: 500;
            color: #888;
            cursor: pointer;
            transition: 0.3s;
            display: flex;
            align-items: center;
            gap: 8px;
            white-space: nowrap;
            height: 40px;
        }

        .hp-filter-btn:hover,
        .hp-filter-btn.uk-active {
            border-color: var(--main-color);
            background: #fff;
            color: var(--main-color);
            box-shadow: 0 2px 8px rgba(249, 196, 64, 0.15);
        }

        .hp-filter-btn-all {
            background: #fff;
            color: #333;
            border: 1px solid #ddd;
        }

        .hp-filter-btn-all:hover {
            background: var(--main-color) !important;
            color: #fff !important;
            border-color: var(--main-color);
        }

        .hp-filter-link {
            font-size: 14px;
            color: #666;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .hp-filter-link:hover {
            color: var(--main-color);
        }

        /* Dropdown Panel */
        .hp-dropdown-panel {
            width: 320px !important;
            padding: 0 !important;
            border-radius: 15px !important;
            border: 1px solid #eee !important;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1) !important;
            background: #fff !important;
            overflow: hidden !important;
            margin-top: 10px !important;
        }

        .hp-dropdown-header {
            padding: 15px 20px;
            font-weight: 700;
            font-size: 16px;
            color: #111;
            border-bottom: 1px solid #f6f6f6;
        }

        .hp-dropdown-body {
            padding: 0 20px;
            max-height: 400px;
            overflow-y: auto;
        }

        .hp-dropdown-footer {
            padding: 15px 20px;
            border-top: 1px solid #f6f6f6;
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            background: #fafafa;
        }

        .hp-btn-apply-bar {
            background: var(--main-color) !important;
            color: #fff !important;
            border-radius: 8px !important;
            font-weight: 600 !important;
            padding: 8px 20px !important;
            border: none !important;
            height: auto !important;
            line-height: 1.5 !important;
        }

        .hp-mini-input {
            width: 100%;
            border: 1px solid #eee;
            border-radius: 8px;
            padding: 8px 12px;
            font-size: 14px;
            outline: none;
            transition: 0.2s;
        }

        .hp-mini-input:focus {
            border-color: var(--main-color);
        }

        /* Modal Styling Adjustments */
        html.uk-modal-page,
        html.uk-modal-page body {
            overflow: hidden !important;
            height: 100vh !important;
            position: fixed !important;
            width: 100% !important;
        }

        .hp-modal-filter .uk-modal-dialog {
            border-radius: 20px;
            padding: 0;
        }

        .hp-modal-view {
            display: none;
            background: #fff;
            min-height: 400px;
            padding: 10px;
        }

        .hp-modal-view.active {
            display: block;
        }

        .hp-modal-filter .uk-modal-header {
            padding: 24px 32px 16px;
            border-bottom: none;
        }

        .hp-modal-filter .uk-modal-body {
            padding: 0 10px 10px;
            max-height: 70vh;
            overflow-y: auto;
        }

        .hp-modal-filter .uk-modal-footer {
            padding: 24px 32px;
            border-top: 1px solid #f2f2f2;
            background: #fff;
        }

        .group-label {
            display: block;
            font-weight: 700;
            font-size: 15px;
            color: #111;
            margin-bottom: 12px;
        }

        .hp-input-box {
            background: #fff;
            border: 1px solid #eee;
            border-radius: 12px;
            padding: 14px 20px;
            cursor: pointer;
            transition: 0.2s;
        }

        .hp-input-box:hover {
            border-color: var(--main-color);
            background: #fdfdfd;
        }

        .hp-toggle-btn {
            background: #f5f5f5;
            border: 1px solid #eee;
            color: #666;
            font-weight: 600;
            transition: 0.3s;
            border-radius: 8px;
        }

        .hp-toggle-btn.active {
            background: var(--main-color) !important;
            color: #fff !important;
            border-color: var(--main-color);
        }

        .hp-toggle-btn:hover {
            background: #eee;
        }

        .hp-pill {
            display: inline-flex;
            align-items: center;
            padding: 5px 10px;
            background: #e8e8e8;
            border: 1px solid transparent;
            border-radius: 5px;
            margin-right: 10px;
            margin-bottom: 10px;
            cursor: pointer;
            transition: all 0.2s;
            color: #555;
            font-size: 14px;
        }

        .hp-pill:hover {
            background: #e0e0e0;
            color: #333;
        }

        .hp-pill-label input:checked+.hp-pill {
            background: var(--main-light);
            color: var(--main-color);
            border-color: var(--main-color);
            font-weight: 400;
        }

        .spec-item {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            background: #f5f5f5;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 14px;
            cursor: pointer;
            transition: 0.3s;
            color: #555;
            border: 1px solid transparent;
            font-size: 12px;
            line-height: 1.1;
            text-align: center;
            padding: 2px;
        }

        .hp-spec-selector input:checked+.spec-item {
            background: var(--main-light);
            color: var(--main-color);
            border-color: var(--main-color);
            font-weight: 500;
        }

        .spec-item:hover {
            background: #e1f5fe !important;
            /* light blue */
            color: #0288d1 !important;
            border-color: #0288d1 !important;
        }

        .hp-btn-modal-apply,
        .hp-btn-main {
            background: var(--main-color) !important;
            color: #fff !important;
            border-radius: 8px;
            font-weight: 600;
            padding: 12px 20px;
            border: none;
            transition: 0.3s;
        }

        .hp-btn-modal-apply:hover,
        .hp-btn-main:hover {
            background: var(--main-dark);
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(249, 196, 64, 0.2);
        }

        .hp-btn-modal-cancel {
            background: #f5f5f5;
            color: #666;
            border-radius: 10px;
            font-weight: 600;
            padding: 14px 28px;
            border: none;
            margin-right: 12px;
        }

        .hp-listing-top {
            border-bottom: 1px solid #f0f0f0;
            margin-bottom: 30px !important;
        }

        .hp-category-name {
            font-size: 26px;
            font-weight: 700;
            color: #222;
            margin-bottom: 10px;
            line-height: 1.2;
        }

        .hp-listing-count {
            font-size: 14px;
            color: #666;
            display: flex;
            align-items: center;
        }

        .hp-listing-count i {
            color: var(--main-color);
        }

        .hp-listing-count strong {
            color: var(--main-color);
            margin: 0 4px;
        }

        .hp-sort-dropdown {
            position: relative;
        }

        .hp-sort-btn {
            background: #fdfdfd;
            border: 1px solid #eee;
            border-radius: 6px;
            padding: 7px 14px;
            font-size: 14px;
            font-weight: 500;
            color: #444;
            transition: all 0.2s;
            cursor: pointer;
        }

        .hp-sort-btn:hover {
            border-color: var(--main-color);
            color: var(--main-color);
            background: #fff;
        }

        .hp-sort-dropdown .uk-dropdown {
            padding: 0 !important;
            border: none !important;
            border-radius: 12px !important;
            box-shadow: 0 5px 25px rgba(0, 0, 0, 0.08) !important;
            margin-top: 5px !important;
            overflow: hidden;
        }

        .hp-sort-list {
            margin: 0;
            padding: 5px 0;
            list-style: none;
        }

        .hp-sort-list li a {
            display: block;
            padding: 10px 18px !important;
            font-size: 14px;
            color: #444;
            transition: 0.2s;
            text-decoration: none !important;
        }

        .hp-sort-list li.uk-active a,
        .hp-sort-list li a:hover {
            background: var(--main-light) !important;
            color: var(--main-color) !important;
            font-weight: 500;
        }

        .hp-selection-list {
            padding-top: 10px;
        }

        .hp-selection-item {
            padding: 10px 0;
            border-bottom: 1px solid #f6f6f6;
            cursor: pointer;
            font-size: 15px;
            color: #333;
        }

        .hp-selection-item:hover {
            color: var(--main-color);
        }

        .hp-selection-item input:checked+span,
        .hp-selection-item:has(input:checked) {
            /* color: var(--main-color); */
            font-weight: 500;
        }

        .hp-selection-item input[type="radio"],
        .hp-selection-item input[type="checkbox"] {
            appearance: none;
            width: 18px;
            height: 18px;
            border: 1px solid #ddd;
            border-radius: 4px;
            outline: none;
            cursor: pointer;
            position: relative;
            transition: 0.2s;
            margin-left: 10px;
        }

        .hp-selection-item input[type="checkbox"]:checked {
            background-color: var(--main-color);
            border-color: var(--main-color);
        }

        .hp-selection-item input[type="checkbox"]:checked::after {
            content: '';
            position: absolute;
            top: 40%;
            left: 50%;
            width: 4px;
            height: 8px;
            border: solid #fff;
            border-width: 0 2px 2px 0;
            transform: translate(-50%, -50%) rotate(45deg);
            display: block;
        }

        .hp-selection-item input[type="radio"] {
            border-radius: 50%;
        }

        .hp-selection-item input[type="radio"]:checked {
            border-color: var(--main-color);
            background: #fff;
        }

        .hp-selection-item input[type="radio"]:checked::after {
            content: '';
            width: 10px;
            height: 10px;
            background: var(--main-color);
            border-radius: 50%;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            display: block;
        }

        .hp-location-select,
        .hp-custom-input {
            background: #fff;
            border: 1px solid #eee;
            border-radius: 8px;
            padding: 12px 15px;
            font-size: 14px;
            transition: 0.2s;
        }

        .hp-location-select:focus,
        .hp-custom-input:focus {
            border-color: var(--main-color);
            outline: none;
        }

        /* Select2 Premium Styling */
        .select2-container--default .select2-selection--single {
            height: 45px !important;
            border: 1px solid #ddd !important;
            border-radius: 8px !important;
            padding: 8px 15px !important;
            display: flex !important;
            align-items: center !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #333 !important;
            padding-left: 0 !important;
            font-size: 14px !important;
            line-height: normal !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 45px !important;
            right: 15px !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow b {
            border-color: #999 transparent transparent transparent !important;
        }

        .select2-container--default.select2-container--open .select2-selection--single {
            border-color: var(--main-color) !important;
            box-shadow: 0 0 0 2px rgba(249, 196, 64, 0.1) !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__clear {
            position: absolute !important;
            right: 35px !important;
            height: 45px !important;
            line-height: 45px !important;
            margin: 0 !important;
            color: #999 !important;
        }

        .select2-dropdown {
            border: 1px solid #eee !important;
            border-radius: 8px !important;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15) !important;
            z-index: 1000000 !important;
            background: #fff !important;
        }

        .select2-search--dropdown {
            padding: 10px !important;
        }

        .select2-search--dropdown .select2-search__field {
            border: 1px solid #eee !important;
            border-radius: 6px !important;
            padding: 8px !important;
        }

        .select2-results__option {
            padding: 10px 15px !important;
            font-size: 14px !important;
        }

        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: var(--main-color) !important;
            color: #fff !important;
        }

        .select2-container--default .select2-results__option[aria-selected=true] {
            background-color: var(--main-light) !important;
            color: var(--main-color) !important;
        }

        /* Responsive Filter Bar */
        @media (max-width: 959px) {
            .hp-filter-horizontal {
                height: auto;
                padding: 10px 0;
            }

            .hp-filter-wrapper {
                gap: 10px;
                overflow: hidden;
            }

            .hp-filter-main {
                width: 100%;
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
                padding-bottom: 5px;
                scrollbar-width: none;
            }

            .hp-filter-main::-webkit-scrollbar {
                display: none;
            }

            .hp-filter-btn {
                flex-shrink: 0;
                padding: 8px 12px;
                font-size: 13px;
            }

            .hp-filter-reset {
                display: none;
            }

            /* Dropdown as pseudo-modal on mobile to avoid clipping */
            .hp-dropdown-panel {
                position: fixed !important;
                top: 50% !important;
                left: 50% !important;
                transform: translate(-50%, -50%) !important;
                width: 90% !important;
                max-width: 350px !important;
                margin: 0 !important;
                z-index: 2000 !important;
            }

            .uk-dropdown-shown .hp-dropdown-panel {
                display: block !important;
            }

            /* Modal UI Refinements */
            .hp-modal-filter .uk-modal-header {
                padding: 15px 20px;
            }
            .hp-modal-filter .uk-modal-body {
                padding: 0 15px 15px;
            }
            .hp-spec-selector {
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
                padding-bottom: 5px;
                scrollbar-width: none;
                display: flex;
            }
            .hp-spec-selector::-webkit-scrollbar {
                display: none;
            }
            .spec-item {
                width: 42px;
                height: 42px;
                flex-shrink: 0;
                margin-right: 10px;
                font-size: 11px;
                line-height: 1.1;
                text-align: center;
                padding: 2px;
            }
            .hp-input-box {
                padding: 10px 15px;
                border-radius: 8px;
            }
            .group-label {
                font-size: 14px;
                margin-bottom: 8px;
                margin-top: 15px;
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            window.province_code = '{{ request('province_code', 0) }}';
            window.ward_code = '{{ request('ward_code', 0) }}';
            window.old_province_code = '{{ request('old_province_code', 0) }}';
            window.old_district_code = '{{ request('old_district_code', 0) }}';
            window.old_ward_code = '{{ request('old_ward_code', 0) }}';

            initializeSelect2();
            updateSummaryTexts();
            updateBarSummaries();

            const switchMode = document.getElementById('switch-location-mode-filter');
            if (switchMode) {
                switchMode.addEventListener('change', function() {
                    if (this.checked) {
                        document.getElementById('gl-location-after-filter').style.display = 'block';
                        document.getElementById('gl-location-before-filter').style.display = 'none';
                    } else {
                        document.getElementById('gl-location-after-filter').style.display = 'none';
                        document.getElementById('gl-location-before-filter').style.display = 'block';
                    }
                    initializeSelect2(); // Re-init for visibility
                });
            }

            $(document).on('change', '.transaction-radio', function() {
                const val = $(this).val();
                let targetUrl = val == '75' ? '/cho-thue.html' : '/mua-ban.html';
                window.location.href = targetUrl;
            });

            $(document).on('change', '.hp-spec-selector input', function() {
                $(this).closest('.hp-spec-selector').find('.spec-item').removeClass('active');
                $(this).next('.spec-item').addClass('active');
            });

            $(document).on('change input', '.bar-sync-input', function() {
                const name = $(this).data('name');
                const val = $(this).val();
                const $modal = $('#modal-all-filters');

                if ($(this).is(':checkbox')) {
                    const $target = $modal.find(`input[name="${name}"][value="${val}"]`);
                    $target.prop('checked', $(this).is(':checked')).trigger('change');
                } else if ($(this).is(':radio')) {
                    const $target = $modal.find(`input[name="${name}"][value="${val}"]`);
                    $target.prop('checked', true).trigger('change');
                } else {
                    const $target = $modal.find(`input[name="${name}"]`);
                    $target.val(val).trigger('change');
                }
                updateBarSummaries();
                updateSummaryTexts();
            });

            $(document).on('change input', '#modal-all-filters input', function() {
                const name = $(this).attr('name');
                if (!name || name === 'is_after_merger') return;
                const val = $(this).val();
                const $bar = $('.hp-filter-main');

                if ($(this).is(':checkbox')) {
                    const $target = $bar.find(`.bar-sync-input[data-name="${name}"][value="${val}"]`);
                    $target.prop('checked', $(this).is(':checked'));
                } else if ($(this).is(':radio')) {
                    const $target = $bar.find(`.bar-sync-input[data-name="${name}"][value="${val}"]`);
                    $target.prop('checked', true);
                } else {
                    const $target = $bar.find(`.bar-sync-input[data-name="${name}"]`);
                    $target.val(val);
                }
                updateBarSummaries();
            });

            function initializeSelect2() {
                if ($.fn.select2) {
                    $('.setupSelect2Filter').each(function() {
                        if ($(this).data('select2')) {
                            $(this).select2('destroy');
                        }
                        $(this).select2({
                            placeholder: "[Chọn]",
                            allowClear: false,
                            width: '100%',
                            dropdownParent: $('body')
                        });
                    });
                }
            }

            $(document).on('mousedown', '.select2-container', function(e) {
                e.stopPropagation();
            });

            $('.setupSelect2Filter').on('change change.select2', function() {
                updateSummaryTexts();
                updateBarSummaries();
            });

            UIkit.on('show.uk.modal', function(e) {
                if (e.target.id === 'modal-all-filters') {
                    const scrollTop = $(window).scrollTop();
                    $('html').addClass('uk-modal-page').attr('data-scroll', scrollTop);
                    $('body').css({
                        position: 'fixed',
                        top: -scrollTop + 'px',
                        width: '100%',
                        overflow: 'hidden'
                    });
                }
            });
            UIkit.on('hide.uk.modal', function(e) {
                if (e.target.id === 'modal-all-filters') {
                    const scrollTop = $('html').attr('data-scroll');
                    $('html').removeClass('uk-modal-page');
                    $('body').css({
                        position: '',
                        top: '',
                        width: '',
                        overflow: ''
                    });
                    $(window).scrollTop(scrollTop);
                }
            });
        });

        function showSubView(viewId) {
            $('.hp-modal-view').removeClass('active');
            $(`#${viewId}`).addClass('active');
        }

        function hideSubView() {
            $('.hp-modal-view').removeClass('active');
            $('#hp-view-main').addClass('active');
            updateSummaryTexts();
            updateBarSummaries();
        }

        function updateBarSummaries() {
            const modal = document.getElementById('modal-all-filters');
            if (!modal) return;

            // Property Type
            const barProp = document.getElementById('bar-selected-property');
            if (barProp) {
                const checkedProps = modal.querySelectorAll(
                    '#hp-view-property input[name="real_estate_catalogue_id[]"]:checked');
                if (checkedProps.length > 0) {
                    const first = checkedProps[0].closest('label').querySelector('span').innerText;
                    barProp.innerText = checkedProps.length > 1 ? `${first} (+${checkedProps.length - 1})` : first;
                } else {
                    barProp.innerText = 'Loại hình';
                }
            }

            // Price
            const pMin = modal.querySelector('input[name="price_min"]').value;
            const pMax = modal.querySelector('input[name="price_max"]').value;
            const pRadio = modal.querySelector('input[name="price"]:checked');
            const barPrice = document.getElementById('bar-selected-price');
            const transType = modal.querySelector('input[name="transaction_type"]:checked')?.value || '74';
            const unit = transType == '75' ? 'triệu' : 'tỷ';

            if (barPrice) {
                if (pRadio && pRadio.value == '0-0') {
                    barPrice.innerText = 'Thỏa thuận';
                } else if (pMin || pMax) {
                    barPrice.innerText = `${pMin || 0}-${pMax || '∞'} ${unit}`;
                } else if (pRadio && pRadio.value != "") {
                    barPrice.innerText = pRadio.closest('label').querySelector('span').innerText;
                } else {
                    barPrice.innerText = 'Khoảng giá';
                }
            }

            // Area
            const aMin = modal.querySelector('input[name="area_min"]').value;
            const aMax = modal.querySelector('input[name="area_max"]').value;
            const aRadio = modal.querySelector('input[name="area"]:checked');
            const barArea = document.getElementById('bar-selected-area');
            if (barArea) {
                if (aMin || aMax) {
                    barArea.innerText = `${aMin || 0}-${aMax || '∞'} m²`;
                } else if (aRadio && aRadio.value != "") {
                    barArea.innerText = aRadio.closest('label').querySelector('span').innerText;
                } else {
                    barArea.innerText = 'Diện tích';
                }
            }
        }

        function updateSummaryTexts() {
            const modal = document.getElementById('modal-all-filters');
            if (!modal) return;

            // Update Property Type Summary (Catalgoue)
            const propEl = document.getElementById('selected-property-text');

            if (propEl) {
                const checkedProps = modal.querySelectorAll(
                    '#hp-view-property input[name="real_estate_catalogue_id[]"]:checked');
                if (checkedProps.length > 0) {
                    const first = checkedProps[0].closest('label').querySelector('span').innerText;
                    propEl.innerText = checkedProps.length > 1 ? `${first} (+${checkedProps.length - 1})` : first;
                    propEl.classList.remove('uk-text-muted');
                } else {
                    propEl.innerText = 'Tất cả loại hình';
                    propEl.classList.add('uk-text-muted');
                }
            }

            // Update Location Summary
            const isAfter = modal.querySelector('#switch-location-mode-filter')?.checked;
            const locEl = document.getElementById('selected-location-text');
            if (locEl) {
                let label = 'Trên toàn quốc';
                if (isAfter) {
                    const p = modal.querySelector('select[name="province_code"]');
                    const w = modal.querySelector('select[name="ward_code"]');
                    if (p && p.value != 0 && p.value != "") {
                        label = p.options[p.selectedIndex].text;
                        if (w && w.value != 0 && w.value != "") label += ' - ' + w.options[w.selectedIndex].text;
                    }
                } else {
                    const p = modal.querySelector('select[name="old_province_code"]');
                    const d = modal.querySelector('select[name="old_district_code"]');
                    const w = modal.querySelector('select[name="old_ward_code"]');
                    if (p && p.value != 0 && p.value != "") {
                        label = p.options[p.selectedIndex].text;
                        if (d && d.value != 0 && d.value != "") label += ' - ' + d.options[d.selectedIndex].text;
                        if (w && w.value != 0 && w.value != "") label += ' - ' + w.options[w.selectedIndex].text;
                    }
                }
                locEl.innerText = label;
                if (label !== 'Trên toàn quốc') {
                    locEl.classList.remove('uk-text-muted');
                } else {
                    locEl.classList.add('uk-text-muted');
                }
            }

            // Update Price Summary
            const priceInput = modal.querySelector('input[name="price"]:checked');
            const priceMinInput = modal.querySelector('input[name="price_min"]');
            const priceMaxInput = modal.querySelector('input[name="price_max"]');
            const pEl = document.getElementById('selected-price-text');
            const transType = modal.querySelector('input[name="transaction_type"]:checked')?.value || '74';
            const unit = transType == '75' ? 'triệu' : 'tỷ';

            if (pEl) {
                const priceMin = priceMinInput ? priceMinInput.value : '';
                const priceMax = priceMaxInput ? priceMaxInput.value : '';
                if (priceInput && priceInput.value == '0-0') {
                    pEl.innerText = 'Thỏa thuận';
                    pEl.classList.remove('uk-text-muted');
                } else if (priceMin || priceMax) {
                    pEl.innerText = `${priceMin || 0} - ${priceMax || '∞'} ${unit}`;
                    pEl.classList.remove('uk-text-muted');
                } else if (priceInput) {
                    pEl.innerText = priceInput.closest('label').querySelector('span').innerText;
                    pEl.classList.remove('uk-text-muted');
                } else {
                    pEl.innerText = 'Tất cả';
                    pEl.classList.add('uk-text-muted');
                }
            }

            // Update Area Summary
            const areaInput = modal.querySelector('input[name="area"]:checked');
            const areaMinInput = modal.querySelector('input[name="area_min"]');
            const areaMaxInput = modal.querySelector('input[name="area_max"]');
            const aEl = document.getElementById('selected-area-text');
            if (aEl) {
                const areaMin = areaMinInput ? areaMinInput.value : '';
                const areaMax = areaMaxInput ? areaMaxInput.value : '';
                if (areaMin || areaMax) {
                    aEl.innerText = `${areaMin || 0} - ${areaMax || '∞'} m²`;
                    aEl.classList.remove('uk-text-muted');
                } else if (areaInput) {
                    aEl.innerText = areaInput.closest('label').querySelector('span').innerText;
                    aEl.classList.remove('uk-text-muted');
                } else {
                    aEl.innerText = 'Tất cả';
                    aEl.classList.add('uk-text-muted');
                }
            }

            // Update Direction Summary
            const houseDir = modal.querySelector('input[name="house_direction"]:checked');
            const hEl = document.getElementById('selected-house-direction-text');
            if (hEl) {
                if (houseDir) {
                    hEl.innerText = houseDir.closest('label').querySelector('span').innerText;
                    hEl.classList.remove('uk-text-muted');
                } else {
                    hEl.innerText = 'Tất cả hướng';
                    hEl.classList.add('uk-text-muted');
                }
            }

            const balconyDir = modal.querySelector('input[name="balcony_direction"]:checked');
            const bEl = document.getElementById('selected-balcony-direction-text');
            if (bEl) {
                if (balconyDir) {
                    bEl.innerText = balconyDir.closest('label').querySelector('span').innerText;
                    bEl.classList.remove('uk-text-muted');
                } else {
                    bEl.innerText = 'Tất cả hướng';
                    bEl.classList.add('uk-text-muted');
                }
            }
        }
    </script>
