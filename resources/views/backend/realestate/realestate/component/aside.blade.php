<div class="ibox">
    <div class="ibox-title">
        <h5>Mã nội bộ</h5>
    </div>
    <div class="ibox-content">
        @php
            $currentCode = $model->code ?? '';
            if (empty($currentCode) && $config['method'] == 'create') {
                $timePart = strtoupper(base_convert(date('YmdHis'), 10, 36));
                $currentCode = 'BDS-' . $timePart . '-SXG-';
            }
        @endphp
        <input type="text" name="code" value="{{ old('code', $currentCode) }}" class="form-control"
            placeholder="Tự động sinh nếu để trống">
        <small class="text-warning mt5" style="display:block">Lưu ý*: Ưu tiên nhập mã từ Reex/phần mềm quản lý nếu
            có.</small>
    </div>
</div>

<div class="ibox">
    <div class="ibox-title">
        <h5>Nhóm Bất động sản <span class="text-danger">(*)</span></h5>
    </div>
    <div class="ibox-content">
        <select name="real_estate_catalogue_id" class="form-control setupSelect2">
            <option value="0">[Chọn nhóm chính]</option>
            @if (isset($dropdown))
                @foreach ($dropdown as $key => $val)
                    <option value="{{ $key }}" @if (old('real_estate_catalogue_id', $model->real_estate_catalogue_id ?? '') == $key) selected @endif>
                        {{ $val }}</option>
                @endforeach
            @endif
        </select>
    </div>
</div>

<div class="ibox">
    <div class="ibox-title">
        <h5>Thông tin diện tích</h5>
    </div>
    <div class="ibox-content">
        <div class="row mb15">
            <div class="col-lg-12">
                <div class="form-row">
                    <label class="control-label text-left">Diện tích xây dựng (m²)</label>
                    <input type="text" name="area" value="{{ old('area', $model->area ?? '') }}"
                        class="form-control float" placeholder="0" autocomplete="off">
                </div>
            </div>
        </div>
        <div class="row mb15">
            <div class="col-lg-12">
                <div class="form-row">
                    <label class="control-label text-left">Diện tích sử dụng (m²)</label>
                    <input type="text" name="usable_area"
                        value="{{ old('usable_area', $model->usable_area ?? '') }}" class="form-control float"
                        placeholder="0" autocomplete="off">
                </div>
            </div>
        </div>
        <div class="row mb15">
            <div class="col-lg-12">
                <div class="form-row">
                    <label class="control-label text-left">Diện tích đất (m²)</label>
                    <input type="text" name="land_area" value="{{ old('land_area', $model->land_area ?? '') }}"
                        class="form-control float" placeholder="0" autocomplete="off">
                </div>
            </div>
        </div>
    </div>
</div>

<div class="ibox">
    <div class="ibox-title">
        <h5>Media (Video & 3D Tour)</h5>
    </div>
    <div class="ibox-content">
        <div class="row mb15">
            <div class="col-lg-12">
                <div class="form-row">
                    <label class="control-label text-left">Link Video (YouTube/Vimeo)</label>
                    <textarea name="video_url" class="form-control" placeholder="https://youtube.com/watch?v=..." style="height: 100px;">{{ old('video_url', $model->video_url ?? '') }}</textarea>
                </div>
            </div>
        </div>
        <div class="row mb15">
            <div class="col-lg-12">
                <div class="form-row">
                    <label class="control-label text-left">Link 3D Tour / Matterport</label>
                    <textarea name="tour_url" class="form-control" placeholder="https://my.matterport.com/show/?m=..."
                        style="height: 100px;">{{ old('tour_url', $model->tour_url ?? '') }}</textarea>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="ibox w">
    <div class="ibox-title">
        <h5>{{ __('messages.image') }}</h5>
    </div>
    <div class="ibox-content">
        <div class="row">
            <div class="col-lg-12">
                <div class="form-row">
                    <span class="image img-cover image-target"><img
                            src="{{ old('image', $model->image ?? '') ? asset(old('image', $model->image ?? '')) : asset('backend/img/not-found.jpg') }}"
                            alt=""></span>
                    <input type="hidden" name="image" value="{{ old('image', $model->image ?? '') }}">
                </div>
            </div>
        </div>
    </div>
</div>


<div class="ibox">
    <div class="ibox-title">
        <h5>Cài đặt hiển thị</h5>
    </div>
    <div class="ibox-content">
        <div class="row mb15">
            <div class="col-lg-12">
                <div class="form-row">
                    <label class="control-label">Trạng thái</label>
                    <select name="publish" class="form-control setupSelect2">
                        @foreach (config('apps.general.publish') as $key => $val)
                            <option value="{{ $key }}" @if (old('publish', $model->publish ?? '') == $key) selected @endif>
                                {{ $val }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <div class="row mb15">
            <div class="col-lg-12">
                <div class="form-row">
                    <label class="control-label">Thứ tự</label>
                    <input name="order" value="{{ old('order', $model->order ?? '0') }}" class="form-control">
                </div>
            </div>
        </div>
    </div>
</div>
