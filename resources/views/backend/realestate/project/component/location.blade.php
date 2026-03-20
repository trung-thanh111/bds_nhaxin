{{-- <div class="ibox">
    <div class="ibox-title">
        <h5>Địa chỉ dự án</h5>
    </div>
    <div class="ibox-content">
        <div class="row mb15">
            <div class="col-lg-4">
                <div class="form-row">
                    <label class="control-label text-left">Tỉnh/Thành phố</label>
                    <select name="province_code" class="form-control setupSelect2 province location" data-target="districts">
                        <option value="0">[Chọn Tỉnh/Thành phố]</option>
                        @if (isset($provinces))
                            @foreach ($provinces as $province)
                                <option value="{{ $province->code }}" {{ old('province_code', $model->province_code ?? '') == $province->code ? 'selected' : '' }}>{{ $province->name }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="form-row">
                    <label class="control-label text-left">Quận/Huyện</label>
                    <select name="district_code" class="form-control setupSelect2 districts location" data-target="wards">
                        <option value="0">[Chọn Quận/Huyện]</option>
                    </select>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="form-row">
                    <label class="control-label text-left">Phường/Xã</label>
                    <select name="ward_code" class="form-control setupSelect2 wards">
                        <option value="0">[Chọn Phường/Xã]</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="row mb15">
            <div class="col-lg-12">
                <div class="form-row">
                    <label class="control-label text-left">Địa chỉ chi tiết</label>
                    <input type="text" name="address" value="{{ old('address', $model->address ?? '') }}" class="form-control" autocomplete="off">
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    var province_code = '{{ (old('province_code', $model->province_code ?? '')) }}';
    var district_code = '{{ (old('district_code', $model->district_code ?? '')) }}';
    var ward_code = '{{ (old('ward_code', $model->ward_code ?? '')) }}';
</script> --}}
