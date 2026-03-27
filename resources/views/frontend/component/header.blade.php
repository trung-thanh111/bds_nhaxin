<header class="hp-header @yield('header-class')" id="hp-header">
    <style>
        .hp-header-top {
            transition: all 0.3s ease;
            background: #fff;
            width: 100%;
            z-index: 1010;
        }

        .hp-header-top.hp-header--sticky {
            position: fixed;
            top: 0;
            left: 0;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            padding: 15px 0;
        }

        .gl-modal-location .gl-modal-body {
            max-height: 400px;
            overflow-y: auto;
        }

        .logo img {
            max-height: 50px !important;
        }
    </style>
    <div class="hp-header-top">
        <div class="uk-container uk-container-center">
            <div class="uk-grid uk-grid-small uk-flex-middle uk-flex-between">
                <div class="uk-width-large-1-4 uk-width-1-2">
                    <div class="logo">
                        <a href="/" title="logo">
                            <img src="{{ $system['homepage_logo'] ?? asset('frontend/resources/img/homely/logo.webp') }}"
                                alt="logo" style="max-height: 40px;">
                        </a>
                    </div>
                </div>

                <div class="uk-width-large-2-4 uk-visible-large">
                    <div class="gl-search-bar" style="border: 1px solid #e3e3e3;">
                        <button class="gl-search-trigger" data-uk-modal="{target:'#modal-location'}">
                            <span id="label-location">Toàn quốc</span>
                            <i class="fa fa-chevron-down"></i>
                        </button>
                        <input type="text" class="gl-search-input" placeholder="Tìm kiếm bất động sản...">
                        <button class="gl-search-btn"><i class="fa fa-search uk-margin-small-right"></i></button>
                    </div>
                </div>

                <div class="uk-width-large-1-4 uk-width-1-2">
                    <div class="uk-flex uk-flex-middle uk-flex-right">
                        <a class="hp-hamburger uk-flex uk-flex-middle" href="#offcanvas-desktop"
                            data-uk-offcanvas="{target:'#offcanvas-desktop'}">
                            <span class="uk-margin-small-right uk-visible-large"
                                style="font-weight: 600; font-size: 15px;">Danh mục</span>
                            <i class="fa fa-bars" style="font-size: 24px; color: var(--text-main);"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>

<script>
    window.addEventListener('scroll', function() {
        const headerTop = document.querySelector('.hp-header-top');
        if (window.scrollY > 100) {
            headerTop.classList.add('hp-header--sticky');
        } else {
            headerTop.classList.remove('hp-header--sticky');
        }
    });
</script>

@include('frontend.component.sidebar')

<div id="modal-location" class="uk-modal gl-modal-location">
    <div class="uk-modal-dialog">
        <div class="gl-modal-header">
            <div class="gl-modal-title">Chọn khu vực</div>
            <a class="uk-modal-close uk-close" style="font-size: 20px;"></a>
        </div>
        <div class="gl-modal-body">
            <div class="gl-switch-container">
                <span class="gl-switch-text">Tìm theo địa chỉ mới sau sáp nhập</span>
                <label class="gl-switch">
                    <input type="checkbox" id="switch-location-mode">
                    <span class="gl-slider"></span>
                </label>
            </div>

            <div id="gl-location-after" style="display: none;">
                <div class="gl-form-group">
                    <label class="gl-form-label">Thành Phố</label>
                    <select name="province_code" class="gl-select location province setupSelect2" data-target="wards"
                        data-source="after">
                        <option value="0">[Chọn Thành Phố]</option>
                        @foreach ($provinces as $key => $val)
                            <option value="{{ $key }}">{{ $val }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="gl-form-group">
                    <label class="gl-form-label">Phường/Xã</label>
                    <select name="ward_code" class="gl-select wards setupSelect2" data-source="after">
                        <option value="0">[Chọn Phường/Xã]</option>
                    </select>
                </div>
            </div>

            {{-- Địa chỉ cũ (Trước sáp nhập) - 3 Cấp --}}
            <div id="gl-location-before">
                <div class="gl-form-group">
                    <label class="gl-form-label">Thành Phố</label>
                    <select name="old_province_code" class="gl-select location province setupSelect2"
                        data-target="old_districts" data-source="before">
                        <option value="0">[Chọn Thành Phố]</option>
                        @foreach ($old_provinces as $key => $val)
                            <option value="{{ $key }}">{{ $val }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="gl-form-group">
                    <label class="gl-form-label">Quận/Huyện</label>
                    <select name="old_district_code" class="gl-select location old_districts setupSelect2"
                        data-target="old_wards" data-source="before">
                        <option value="0">[Chọn Quận/Huyện]</option>
                    </select>
                </div>

                <div class="gl-form-group">
                    <label class="gl-form-label">Phường/Xã</label>
                    <select name="old_ward_code" class="gl-select old_wards setupSelect2" data-source="before">
                        <option value="0">[Chọn Phường/Xã]</option>
                    </select>
                </div>
            </div>

            <div class="gl-form-group">
                <label class="gl-form-label">Danh mục BĐS</label>
                <select name="real_estate_catalogue_id" class="gl-select setupSelect2">
                    <option value="">Tất cả danh mục BĐS</option>
                    @if (isset($realEstateCatalogues) && count($realEstateCatalogues))
                        @foreach ($realEstateCatalogues as $item)
                            @php
                                $name = $item->languages->first()->pivot->name ?? 'N/A';
                            @endphp
                            <option value="{{ $item->id }}">{{ $name }}</option>
                        @endforeach
                    @endif
                </select>
            </div>

            <div class="gl-form-group">
                <label class="gl-form-label">Danh mục Dự án</label>
                <select name="project_catalogue_id" class="gl-select setupSelect2">
                    <option value="">Tất cả danh mục Dự án</option>
                    @if (isset($projectCatalogues) && count($projectCatalogues))
                        @foreach ($projectCatalogues as $item)
                            @php
                                $name = $item->languages->first()->pivot->name ?? 'N/A';
                            @endphp
                            <option value="{{ $item->id }}">{{ $name }}</option>
                        @endforeach
                    @endif
                </select>
            </div>
        </div>
        <div class="gl-modal-footer">
            <button class="gl-btn-submit uk-modal-close" id="btn-apply-location">Tiếp tục</button>
        </div>
    </div>
</div>

<script src="{{ asset('vendor/backend/library/location.js') }}"></script>
<script>
    $(document).ready(function() {
        // Toggle Location Mode
        $('#switch-location-mode').on('change', function() {
            if ($(this).is(':checked')) {
                $('#gl-location-after').show();
                $('#gl-location-before').hide();
            } else {
                $('#gl-location-after').hide();
                $('#gl-location-before').show();
            }
        });

        // Initialize Select2
        if ($.fn.select2) {
            $('.setupSelect2').select2({
                width: '100%',
                dropdownParent: $('#modal-location')
            });
        }

        // Initialize location logic
        if (typeof HT !== 'undefined' && HT.getLocation) {
            HT.getLocation();
            // Trigger initial load if needed
            $('.location').trigger('change');
        }

        // Apply Selection
        $('#btn-apply-location').on('click', function() {
            let label = 'Toàn quốc';
            const modal = $('#modal-location');
            const isAfter = $('#switch-location-mode').is(':checked');

            if (isAfter) {
                let pCode = modal.find('select[name=province_code]').val();
                if (pCode != '0' && pCode != '') {
                    label = modal.find('select[name=province_code] option:selected').text();
                }
            } else {
                let pCode = modal.find('select[name=old_province_code]').val();
                if (pCode != '0' && pCode != '') {
                    label = modal.find('select[name=old_province_code] option:selected').text();
                }
            }

            $('#label-location').text(label);
        });

        // Search Action
        $('.gl-search-btn').on('click', function() {
            const keyword = $('.gl-search-input').val();
            const modal = $('#modal-location');
            const isAfter = $('#switch-location-mode').is(':checked');

            let params = new URLSearchParams();
            if (keyword) params.append('keyword', keyword);

            if (isAfter) {
                const p = modal.find('select[name=province_code]').val();
                const w = modal.find('select[name=ward_code]').val();
                if (p != '0') params.append('province_code', p);
                if (w != '0') params.append('ward_code', w);
            } else {
                const p = modal.find('select[name=old_province_code]').val();
                const d = modal.find('select[name=old_district_code]').val();
                const w = modal.find('select[name=old_ward_code]').val();
                if (p != '0') params.append('old_province_code', p);
                if (d != '0') params.append('old_district_code', d);
                if (w != '0') params.append('old_ward_code', w);
            }

            const reCat = modal.find('select[name=real_estate_catalogue_id]').val();
            const prCat = modal.find('select[name=project_catalogue_id]').val();

            let targetUrl = '/tim-kiem.html';

            if (prCat) {
                params.append('project_catalogue_id', prCat);
                params.append('type', 'project');
            } else if (reCat) {
                params.append('real_estate_catalogue_id', reCat);
                params.append('type', 'real_estate');
            }

            window.location.href = targetUrl + '?' + params.toString();
        });

        // Enter key to search
        $('.gl-search-input').on('keypress', function(e) {
            if (e.which == 13) {
                $('.gl-search-btn').click();
            }
        });
    });
</script>
