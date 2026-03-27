<div class="hp-filter-horizontal" data-reveal="down">
    <div class="uk-container uk-container-center">
        <div class="hp-filter-wrapper uk-flex uk-flex-middle uk-flex-space-between">
            <div class="hp-filter-main uk-flex uk-flex-middle">
                <!-- All Filters Button -->
                <button class="hp-filter-btn hp-filter-btn-all uk-margin-right"
                    data-uk-modal="{target:'#modal-all-filters'}">
                    <i class="fa fa-filter"></i> <span>Lọc</span>
                </button>

                <!-- Project Type Dropdown -->
                <div class="hp-filter-dropdown" data-uk-dropdown="{mode:'click', pos:'bottom-left'}">
                    <button class="hp-filter-btn">
                        <i class="fa fa-building"></i>
                        <span id="bar-selected-property">Loại hình dự án</span>
                        <i class="fa fa-chevron-down uk-margin-small-left"></i>
                    </button>
                    <div class="uk-dropdown hp-dropdown-panel">
                        <div class="hp-dropdown-header">Loại hình dự án</div>
                        <div class="hp-dropdown-body">
                            <div class="hp-selection-list">
                                @if (isset($projectCatalogues))
                                    @foreach ($projectCatalogues as $cat)
                                        @php $catName = $cat->languages->first()->pivot->name ?? ''; @endphp
                                        <label class="hp-selection-item uk-flex uk-flex-middle uk-flex-space-between">
                                            <span>{{ $catName }}</span>
                                            <input type="checkbox" class="bar-sync-input" data-name="project_catalogue_id[]"
                                                value="{{ $cat->id }}" form="filter-form"
                                                @if (is_array(request('project_catalogue_id')) && in_array($cat->id, request('project_catalogue_id'))) checked @endif>
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

                <div class="hp-filter-dropdown" data-uk-dropdown="{mode:'click', pos:'bottom-left'}">
                    <button class="hp-filter-btn">
                        <i class="fa fa-expand"></i>
                        <span id="bar-selected-area">Diện tích</span>
                        <i class="fa fa-chevron-down uk-margin-small-left"></i>
                    </button>
                    <div class="uk-dropdown hp-dropdown-panel">
                        <div class="hp-dropdown-header">Diện tích (m²)</div>
                        <div class="hp-dropdown-body">
                            <div class="hp-custom-range-mini">
                                <div class="uk-grid uk-grid-small">
                                    <div class="uk-width-2-5"><input type="number" placeholder="Từ"
                                            class="hp-mini-input bar-sync-input" data-name="area_min"
                                            value="{{ request('area_min') }}" form="filter-form"></div>
                                    <div class="uk-width-1-5 uk-text-center uk-flex uk-flex-middle uk-flex-center">→
                                    </div>
                                    <div class="uk-width-2-5"><input type="number" placeholder="Đến"
                                            class="hp-mini-input bar-sync-input" data-name="area_max"
                                            value="{{ request('area_max') }}" form="filter-form"></div>
                                </div>
                            </div>
                            @php
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
</div>

<style>
    /* New Filter Bar Styles */
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
        font-weight: 600;
    }

    .hp-pill {
        display: inline-flex;
        align-items: center;
        padding: 5px 12px;
        background: #f5f5f5;
        border: 1px solid #eee;
        border-radius: 8px;
        margin-right: 8px;
        margin-bottom: 8px;
        cursor: pointer;
        transition: all 0.2s;
        color: #666;
        font-size: 14px;
    }

    .hp-pill:hover {
        border-color: var(--main-color);
        color: var(--main-color);
    }

    .hp-pill-label input:checked+.hp-pill {
        background: var(--main-light);
        color: var(--main-color);
        border-color: var(--main-color);
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
    }

    .hp-spec-selector input:checked+.spec-item {
        background: var(--main-light);
        color: var(--main-color);
        border-color: var(--main-color);
        font-weight: 500;
    }

    .spec-item:hover {
        background: #e1f5fe !important;
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
        border-radius: 8px;
        font-weight: 600;
        padding: 12px 20px;
        border: none;
        margin-right: 10px;
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
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        window.province_code = '{{ request('province_code', 0) }}';
        window.district_code = '{{ request('district_code', 0) }}';
        window.ward_code = '{{ request('ward_code', 0) }}';

        initializeSelect2();
        updateSummaryTexts();
        updateBarSummaries();

        // Modal body lock logic
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

        $(document).on('change', '.hp-spec-selector input', function() {
            $(this).closest('.hp-spec-selector').find('.spec-item').removeClass('active');
            $(this).next('.spec-item').addClass('active');
        });

        // Sync Bar -> Modal
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

        // Sync Modal -> Bar
        $(document).on('change input', '#modal-all-filters input', function() {
            const name = $(this).attr('name');
            if (!name) return;
            const val = $(this).val();
            const $bar = $('.hp-filter-horizontal');

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

        // Project Type
        const checkedProps = modal.querySelectorAll('input[name="project_catalogue_id[]"]:checked');
        const barProp = document.getElementById('bar-selected-property');
        if (barProp) {
            if (checkedProps.length > 0) {
                const firstLabel = checkedProps[0].closest('label').querySelector('span');
                const first = firstLabel ? firstLabel.innerText : '...';
                barProp.innerText = checkedProps.length > 1 ? `${first} (+${checkedProps.length - 1})` : first;
            } else {
                barProp.innerText = 'Loại hình dự án';
            }
        }

        // Area
        const aMinInput = modal.querySelector('input[name="area_min"]');
        const aMaxInput = modal.querySelector('input[name="area_max"]');
        const aRadio = modal.querySelector('input[name="area"]:checked');
        const barArea = document.getElementById('bar-selected-area');
        if (barArea) {
            const min = aMinInput ? aMinInput.value : '';
            const max = aMaxInput ? aMaxInput.value : '';
            if (min || max) {
                barArea.innerText = `${min || 0}-${max || '∞'} m²`;
            } else if (aRadio && aRadio.value != "") {
                const labelSpan = aRadio.closest('label').querySelector('span');
                barArea.innerText = labelSpan ? labelSpan.innerText : 'Diện tích';
            } else {
                barArea.innerText = 'Diện tích';
            }
        }
    }

    function updateSummaryTexts() {
        const modal = document.getElementById('modal-all-filters');
        if (!modal) return;

        // Type Summary
        const checkedProps = modal.querySelectorAll('input[name="project_catalogue_id[]"]:checked');
        const propEl = document.getElementById('selected-property-text');
        if (propEl) {
            if (checkedProps.length > 0) {
                const first = checkedProps[0].closest('label').querySelector('span').innerText;
                propEl.innerText = checkedProps.length > 1 ? `${first} (+${checkedProps.length - 1})` : first;
                propEl.classList.remove('uk-text-muted');
            } else {
                propEl.innerText = 'Tất cả loại hình';
                propEl.classList.add('uk-text-muted');
            }
        }

        // Location Summary
        const p = modal.querySelector('select[name="province_code"]');
        const d = modal.querySelector('select[name="district_code"]');
        const locEl = document.getElementById('selected-location-text');
        if (locEl) {
            let label = 'Trên toàn quốc';
            if (p && p.value != 0 && p.value != "") {
                label = p.options[p.selectedIndex].text;
                if (d && d.value != 0 && d.value != "") label += ' - ' + d.options[d.selectedIndex].text;
            }
            locEl.innerText = label;
            if (label !== 'Trên toàn quốc') locEl.classList.remove('uk-text-muted');
            else locEl.classList.add('uk-text-muted');
        }

        // Area Summary
        const aMinInput = modal.querySelector('input[name="area_min"]');
        const aMaxInput = modal.querySelector('input[name="area_max"]');
        const aRadio = modal.querySelector('input[name="area"]:checked');
        const aEl = document.getElementById('selected-area-text');
        if (aEl) {
            const min = aMinInput ? aMinInput.value : '';
            const max = aMaxInput ? aMaxInput.value : '';
            if (min || max) {
                aEl.innerText = `${min || 0} - ${max || '∞'} m²`;
                aEl.classList.remove('uk-text-muted');
            } else if (aRadio && aRadio.value != "") {
                aEl.innerText = aRadio.closest('label').querySelector('span').innerText;
                aEl.classList.remove('uk-text-muted');
            } else {
                aEl.innerText = 'Tất cả';
                aEl.classList.add('uk-text-muted');
            }
        }
    }
</script>

@include('frontend.component.filter_modal_project')
