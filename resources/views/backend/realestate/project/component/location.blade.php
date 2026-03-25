@php
    $provinces = isset($provinces) ? $provinces : [];
    $old_provinces = isset($old_provinces) ? $old_provinces : [];
@endphp
<div class="ibox">
    <div class="ibox-title">
        <h5>Vị trí dự án</h5>
    </div>
    <div class="ibox-content">
        <div class="row mb15">
            <div class="col-lg-12">
                <label class="control-label text-left text-navy mb10">Địa chỉ (mới)</label>
            </div>
            <div class="col-lg-6">
                <div class="form-row">
                    <label class="control-label text-left">Thành Phố</label>
                    <select name="province_code" class="form-control setupSelect2 province location" data-target="wards"
                        data-source="after">
                        <option value="0">[Chọn Thành Phố]</option>
                        @foreach ($provinces as $key => $val)
                            <option @if (old('province_code', $model->province_code ?? '') == $key) selected @endif value="{{ $key }}">
                                {{ $val }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-row">
                    <label class="control-label text-left">Phường/Xã</label>
                    <select name="ward_code" class="form-control setupSelect2 wards" data-source="after">
                        <option value="0">[Chọn Phường/Xã]</option>
                    </select>
                </div>
            </div>
        </div>

        <hr>

        <!-- Old Address Section -->
        <div class="row mb15">
            <div class="col-lg-12">
                <label class="control-label text-left text-danger mb10">Địa chỉ (cũ)</label>
            </div>
            <div class="col-lg-4">
                <div class="form-row">
                    <label class="control-label text-left">Thành Phố</label>
                    <select name="old_province_code" class="form-control setupSelect2 province location"
                        data-target="old_districts" data-source="before">
                        <option value="0">[Chọn Thành Phố]</option>
                        @foreach ($old_provinces as $key => $val)
                            <option @if (old('old_province_code', $model->old_province_code ?? '') == $key) selected @endif value="{{ $key }}">
                                {{ $val }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="form-row">
                    <label class="control-label text-left">Quận/Huyện</label>
                    <select name="old_district_code" class="form-control setupSelect2 old_districts location"
                        data-target="old_wards" data-source="before">
                        <option value="0">[Chọn Quận/Huyện]</option>
                    </select>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="form-row">
                    <label class="control-label text-left">Phường/Xã</label>
                    <select name="old_ward_code" class="form-control setupSelect2 old_wards" data-source="before">
                        <option value="0">[Chọn Phường/Xã]</option>
                    </select>
                </div>
            </div>
        </div>

        <hr>

        <div class="row mb15">
            <div class="col-lg-12">
                <div class="form-row">
                    <label class="control-label text-left">Địa chỉ (Số nhà, tên đường...) <span
                            class="text-danger">(*)</span></label>
                    <input type="text" name="street" value="{{ old('street', $model->street ?? '') }}"
                        class="form-control" placeholder="" autocomplete="off">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="form-row">
                    <label class="control-label text-left">Bản đồ (Iframe Google Maps)</label>
                    <textarea name="iframe_map" class="form-control" style="height: 100px;">{{ old('iframe_map', $model->iframe_map ?? '') }}</textarea>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    window.province_code = '{{ old('province_code', $model->province_code ?? '') }}';
    window.ward_code = '{{ old('ward_code', $model->ward_code ?? '') }}';

    window.old_province_code = '{{ old('old_province_code', $model->old_province_code ?? '') }}';
    window.old_district_code = '{{ old('old_district_code', $model->old_district_code ?? '') }}';
    window.old_ward_code = '{{ old('old_ward_code', $model->old_ward_code ?? '') }}';
</script>
