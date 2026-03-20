<div class="ibox w">
    <div class="ibox-title">
        <h5>Mã nhóm thuộc tính</h5>
    </div>
    <div class="ibox-content">
        <div class="row mb15">
            <div class="col-lg-12">
                <div class="form-row">
                    <input type="text" name="code" value="{{ old('code', $attributeCatalogue->code ?? '') }}"
                        class="form-control" placeholder="Có thể để trống để tự động sinh từ tên" autocomplete="off"
                        disabled>
                    <small class="text-warning">
                        Lưu ý*:Mã sẽ được tự động sinh từ tên!
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="ibox w">
    <div class="ibox-title">
        <h5>{{ __('messages.parent') }}</h5>
    </div>
    <div class="ibox-content">
        <div class="row">
            <div class="col-lg-12">
                <div class="form-row">
                    <select name="parent_id" class="form-control setupSelect2" id="">
                        @foreach ($dropdown as $key => $val)
                            <option
                                {{ $key == old('parent_id', isset($attributeCatalogue->parent_id) ? $attributeCatalogue->parent_id : '') ? 'selected' : '' }}
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
                            src="{{ old('image', $attributeCatalogue->image ?? '') ? old('image', $attributeCatalogue->image ?? '') : 'backend/img/not-found.jpg' }}"
                            alt=""></span>
                    <input type="hidden" name="image" value="{{ old('image', $attributeCatalogue->image ?? '') }}">
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
                                    {{ $key == old('publish', isset($attributeCatalogue->publish) ? $attributeCatalogue->publish : '2') ? 'selected' : '' }}
                                    value="{{ $key }}">{{ $val }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb15">
                        <select name="follow" class="form-control setupSelect2" id="">
                            @foreach (__('messages.follow') as $key => $val)
                                <option
                                    {{ $key == old('follow', isset($attributeCatalogue->follow) ? $attributeCatalogue->follow : '') ? 'selected' : '' }}
                                    value="{{ $key }}">{{ $val }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
