<div class="row mb10">
    <div class="col-lg-12">
        <small class="text-navy"><strong>1. THÔNG SỐ CHUNG</strong></small>
    </div>
</div>
<div class="row mb15">
    {{-- <div class="col-lg-4"> --}}
    {{-- <div class="form-row">
            <label class="control-label text-left">Loại sở hữu</label>
            <input name="ownership_type" value="{{ old('ownership_type', $model->ownership_type ?? '') }}"
                class="form-control" placeholder="VD: Sổ hồng lâu dài">
        </div>
    </div> --}}
    <div class="col-lg-4">
        <div class="form-row">
            <label class="control-label text-left">Pháp lý</label>
            <select name="ownership_type" class="form-control setupSelect2">
                <option value="0">[Chọn pháp lý]</option>
                @if (isset($dropdowns['phap_ly']))
                    @foreach ($dropdowns['phap_ly'] as $key => $val)
                        <option value="{{ $key }}" @if (old('ownership_type', $model->ownership_type ?? '') == $key) selected @endif>
                            {{ $val }}</option>
                    @endforeach
                @endif
            </select>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="form-row">
            <label class="control-label text-left">Hướng nhà / Đất</label>
            <select name="house_direction" class="form-control setupSelect2">
                <option value="0">[Chọn hướng nhà / Đất]</option>
                @if (isset($dropdowns['huong_nha']))
                    @foreach ($dropdowns['huong_nha'] as $key => $val)
                        <option value="{{ $key }}" @if (old('house_direction', $model->house_direction ?? '') == $key) selected @endif>
                            {{ $val }}</option>
                    @endforeach
                @endif
            </select>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="form-row">
            <label class="control-label text-left">View (Tầm nhìn)</label>
            <input name="view" value="{{ old('view', $model->view ?? '') }}" class="form-control"
                placeholder="VD: View sông Sài Gòn">
        </div>
    </div>
</div>

<div class="row mb10">
    <div class="col-lg-12">
        <small class="text-navy"><strong>2. CHO NHÀ PHỐ / CĂN HỘ</strong></small>
    </div>
</div>
<div class="row mb15">
    <div class="col-lg-3">
        <div class="form-row">
            <label class="control-label text-left">Năm xây dựng</label>
            <input name="year_built" value="{{ old('year_built', $model->year_built ?? '') }}" class="form-control"
                placeholder="YYYY">
        </div>
    </div>
    <div class="col-lg-3">
        <div class="form-row">
            <label class="control-label text-left">Phòng ngủ</label>
            <select name="bedrooms" class="form-control setupSelect2">
                <option value="0">[Chọn số phòng ngủ]</option>
                @for ($i = 1; $i <= 10; $i++)
                    <option value="{{ $i }}" @if (old('bedrooms', $model->bedrooms ?? '') == $i) selected @endif>
                        {{ $i }} phòng ngủ</option>
                @endfor
            </select>
        </div>
    </div>
    <div class="col-lg-3">
        <div class="form-row">
            <label class="control-label text-left">Phòng tắm</label>
            <select name="bathrooms" class="form-control setupSelect2">
                <option value="0">[Chọn số phòng tắm]</option>
                @for ($i = 1; $i <= 10; $i++)
                    <option value="{{ $i }}" @if (old('bathrooms', $model->bathrooms ?? '') == $i) selected @endif>
                        {{ $i }} phòng tắm</option>
                @endfor
            </select>
        </div>
    </div>
    <div class="col-lg-3">
        <div class="form-row">
            <label class="control-label text-left">Hướng ban công</label>
            <select name="balcony_direction" class="form-control setupSelect2">
                <option value="0">[Chọn hướng BC]</option>
                @if (isset($dropdowns['huong_ban_cong']))
                    @foreach ($dropdowns['huong_ban_cong'] as $key => $val)
                        <option value="{{ $key }}" @if (old('balcony_direction', $model->balcony_direction ?? '') == $key) selected @endif>
                            {{ $val }}</option>
                    @endforeach
                @endif
            </select>
        </div>
    </div>
</div>
<div class="row mb15">
    {{-- <div class="col-lg-3">
        <div class="form-row">
            <label class="control-label text-left">Số tầng xây dựng</label>
            <input name="floor_count" value="{{ old('floor_count', $model->floor_count ?? '') }}" class="form-control"
                placeholder="0">
        </div>
    </div> --}}
    <div class="col-lg-3">
        <div class="form-row">
            <label class="control-label text-left">Tầng số (Căn hộ)</label>
            <select name="floor" class="form-control setupSelect2">
                <option value="0">[Chọn tầng]</option>
                @if (isset($dropdowns['lau']))
                    @foreach ($dropdowns['lau'] as $key => $val)
                        <option value="{{ $key }}" @if (old('floor', $model->floor ?? '') == $key) selected @endif>
                            {{ $val }}</option>
                    @endforeach
                @endif
            </select>
        </div>
    </div>
    <div class="col-lg-3">
        <div class="form-row">
            <label class="control-label text-left">Tổng số tầng tòa</label>
            <input name="total_floors" value="{{ old('total_floors', $model->total_floors ?? '') }}"
                class="form-control" placeholder="0">
        </div>
    </div>
    <div class="col-lg-3">
        <div class="form-row">
            <label class="control-label text-left">Mã căn/Block</label>
            <input name="apartment_code" value="{{ old('apartment_code', $model->apartment_code ?? '') }}"
                class="form-control" placeholder="VD: A1-20.05">
        </div>
    </div>
    <div class="col-lg-3">
        <div class="form-row">
            <label class="control-label text-left">Nội thất</label>
            <select name="interior" class="form-control setupSelect2">
                <option value="0">[Chọn nội thất]</option>
                @if (isset($dropdowns['noi_that']))
                    @foreach ($dropdowns['noi_that'] as $key => $val)
                        <option value="{{ $key }}" @if (old('interior', $model->interior ?? '') == $key) selected @endif>
                            {{ $val }}</option>
                    @endforeach
                @endif
            </select>
        </div>
    </div>
</div>

<div class="row mb10">
    <div class="col-lg-12">
        <small class="text-navy"><strong>3. THÔNG SỐ ĐẤT / MẶT BẰNG</strong></small>
    </div>
</div>
<div class="row mb15">
    <div class="col-lg-3">
        <div class="form-row">
            <label class="control-label text-left">Loại đất</label>
            <select name="land_type" class="form-control setupSelect2">
                <option value="0">[Chọn loại đất]</option>
                @if (isset($dropdowns['loai_dat']))
                    @foreach ($dropdowns['loai_dat'] as $key => $val)
                        <option value="{{ $key }}" @if (old('land_type', $model->land_type ?? '') == $key) selected @endif>
                            {{ $val }}</option>
                    @endforeach
                @endif
            </select>
        </div>
    </div>
    <div class="col-lg-2">
        <div class="form-row">
            <label class="control-label text-left">Ngang (m)</label>
            <input name="land_width" value="{{ old('land_width', $model->land_width ?? '') }}" class="form-control"
                placeholder="0">
        </div>
    </div>
    <div class="col-lg-2">
        <div class="form-row">
            <label class="control-label text-left">Dài (m)</label>
            <input name="land_length" value="{{ old('land_length', $model->land_length ?? '') }}"
                class="form-control" placeholder="0">
        </div>
    </div>
    <div class="col-lg-2">
        <div class="form-row">
            <label class="control-label text-left">Mặt tiền (m)</label>
            <input name="road_frontage" value="{{ old('road_frontage', $model->road_frontage ?? '') }}"
                class="form-control" placeholder="0">
        </div>
    </div>
    <div class="col-lg-3">
        <div class="form-row">
            <label class="control-label text-left">Đường rộng (m)</label>
            <input name="road_width" value="{{ old('road_width', $model->road_width ?? '') }}" class="form-control"
                placeholder="0">
        </div>
    </div>
</div>

