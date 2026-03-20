@php
    $url = ($config['method'] == 'create') ? route('floorplan.store') : route('floorplan.update', $floorplan->id);
@endphp
@include('backend.dashboard.component.breadcrumb', ['title' => $config['seo'][$config['method']]['title']])
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
<div class="wrapper wrapper-content animated fadeInRight">
    <form action="{{ $url }}{{ isset($queryUrl) ? '?'.$queryUrl : '' }}" method="post" class="uk-form uk-form-stacked">
        @csrf
        <div class="row">
            <div class="col-lg-9">
                <div class="ibox">
                    <div class="ibox-title">
                        <h5>Thông tin chung</h5>
                    </div>
                    <div class="ibox-content">
                        <div class="row mb15">
                            <div class="col-lg-12">
                                <div class="form-row">
                                    <label class="control-label text-left">Tên mặt bằng <span class="text-danger">(*)</span></label>
                                    <input 
                                        type="text" 
                                        name="name" 
                                        value="{{ old('name', $floorplan->name ?? '') }}" 
                                        class="form-control"
                                        placeholder=""
                                        autocomplete="off"
                                    >
                                </div>
                            </div>
                        </div>
                        <div class="row mb15">
                            <div class="col-lg-12">
                                <div class="form-row">
                                    <label class="control-label text-left">Mô tả</label>
                                    <textarea name="description" class="form-control ck-editor" id="description" data-height="200">{{ old('description', $floorplan->description ?? '') }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="ibox">
                    <div class="ibox-title">
                        <h5>Bất động sản</h5>
                    </div>
                    <div class="ibox-content">
                        <select name="real_estate_id" class="form-control setupSelect2">
                            @foreach($realEstates as $key => $val)
                            <option {{ 
                                $key == old('real_estate_id', (isset($floorplan->real_estate_id)) ? $floorplan->real_estate_id : '') ? 'selected' : '' 
                            }} value="{{ $key }}">{{ $val }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="ibox">
                    <div class="ibox-title">
                        <h5>Ảnh mặt bằng</h5>
                    </div>
                    <div class="ibox-content text-center">
                        <div class="form-row">
                            <span class="image img-cover image-target"><img src="{{ (old('image', ($floorplan->image) ?? '' ) ? asset(old('image', ($floorplan->image) ?? '')) : asset('backend/img/not-found.jpg')) }}" alt=""></span>
                            <input type="hidden" name="image" value="{{ old('image', $floorplan->image ?? '') }}">
                        </div>
                    </div>
                </div>
                <div class="ibox">
                    <div class="ibox-title">
                        <h5>Cấu hình nâng cao</h5>
                    </div>
                    <div class="ibox-content">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-row">
                                    <label class="control-label text-left">Tình trạng</label>
                                    <select name="publish" class="form-control setupSelect2">
                                        @foreach(__('messages.publish') as $key => $val)
                                        <option {{ 
                                            $key == old('publish', (isset($floorplan->publish)) ? $floorplan->publish : '') ? 'selected' : '' 
                                        }} value="{{ $key }}">{{ $val }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="text-right mb15 pt15 border-top">
            <button class="btn btn-primary" type="submit" name="send" value="send">Lưu lại</button>
        </div>
    </form>
</div>
