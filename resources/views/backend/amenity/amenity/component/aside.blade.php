<div class="ibox w">
    <div class="ibox-title">
        <h5>Mã tiện ích</h5>
    </div>
    <div class="ibox-content">
        <div class="row mb15">
            <div class="col-lg-12">
                <div class="form-row">
                    <input type="text" name="code" value="{{ old('code', $amenity->code ?? '') }}"
                        class="form-control" placeholder="Có thể để trống để tự động sinh từ tên" autocomplete="off">
                    <small class="text-warning">
                        Lưu ý*:Mã sẽ được tự động sinh từ tên nếu để trống!
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="ibox w">
    <div class="ibox-title">
        <h5>Nhóm tiện ích</h5>
    </div>
    <div class="ibox-content">
        <div class="row">
            <div class="col-lg-12">
                <div class="form-row">
                    <select name="amenity_catalogue_id" class="form-control setupSelect2" id="">
                        @foreach ($dropdown as $key => $val)
                            <option
                                {{ $key == old('amenity_catalogue_id', isset($amenity->amenity_catalogue_id) ? $amenity->amenity_catalogue_id : '') ? 'selected' : '' }}
                                value="{{ $key }}">{{ $val }}</option>
                        @endforeach
                    </select>
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
                            src="{{ old('image', $amenity->image ?? '') ? old('image', $amenity->image ?? '') : 'backend/img/not-found.jpg' }}"
                            alt=""></span>
                    <input type="hidden" name="image" value="{{ old('image', $amenity->image ?? '') }}">
                </div>
            </div>
        </div>
    </div>
</div>
<div class="ibox w">
    <div class="ibox-title">
        <h5>Icon (SVG / Image)</h5>
    </div>
    <div class="ibox-content">
        <div class="row">
            <div class="col-lg-12">
                <div class="form-row">
                    <span class="image img-cover image-target"><img
                            src="{{ old('icon', $amenity->icon ?? '') ? old('icon', $amenity->icon ?? '') : 'backend/img/not-found.jpg' }}"
                            alt=""></span>
                    <input type="hidden" name="icon" value="{{ old('icon', $amenity->icon ?? '') }}">
                </div>
            </div>
        </div>
    </div>
</div>
<div class="ibox w">
    <div class="ibox-title">
        <h5>{{ __('messages.advange') }}</h5>
    </div>
    <div class="ibox-content">
        <div class="row">
            <div class="col-lg-12">
                <div class="form-row">
                    <div class="mb15">
                        <select name="publish" class="form-control setupSelect2" id="">
                            @foreach (__('messages.publish') as $key => $val)
                                <option
                                    {{ $key == old('publish', isset($amenity->publish) ? $amenity->publish : '2') ? 'selected' : '' }}
                                    value="{{ $key }}">{{ $val }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
