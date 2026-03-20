@php
    $url = $config['method'] == 'create' ? route('project.store') : route('project.update', $project->id);
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
    <form action="{{ $url }}" method="post" class="uk-form uk-form-stacked">
        @csrf
        <div class="row">
            <div class="col-lg-9">
                <div class="ibox">
                    <div class="ibox-title">
                        <h5>Thông tin chung</h5>
                    </div>
                    <div class="ibox-content">
                        @include('backend.dashboard.component.content', ['model' => $project ?? null])
                    </div>
                </div>

                <div class="ibox">
                    <div class="ibox-title">
                        <h5>Thông tin Tài sản & Giao dịch</h5>
                    </div>
                    <div class="ibox-content">
                        <div class="row mb15">
                            <div class="col-lg-12">
                                <div class="form-row">
                                    <label class="control-label text-left">Chọn Tài sản (BĐS) <span
                                            class="text-danger">(*)</span></label>
                                    <select name="real_estate_id" class="form-control setupSelect2">
                                        <option value="0">[Chọn tài sản]</option>
                                        @foreach ($realEstates as $item)
                                            <option value="{{ $item->id }}"
                                                {{ old('real_estate_id', $project->real_estate_id ?? '') == $item->id ? 'selected' : '' }}>
                                                {{ $item->code }} - {{ $item->languages->first()->pivot->name ?? '' }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row mb15">
                            <div class="col-lg-8">
                                <div class="form-row">
                                    <label class="control-label text-left">Giá niêm yết</label>
                                    <input type="text" name="price"
                                        value="{{ old('price', isset($project) ? number_format($project->price, 0, ',', '.') : '') }}"
                                        class="form-control int" autocomplete="off">
                                    <small class="text-primary">Nếu giá bằng 0 hoặc để trống thì hệ thống sẽ hiển thị là
                                        <strong>Thỏa thuận</strong></small>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-row">
                                    <label class="control-label text-left">Đơn vị</label>
                                    <select name="price_unit" class="form-control setupSelect2">
                                        @foreach ($priceUnits as $key => $val)
                                            <option value="{{ $key }}"
                                                {{ old('price_unit', $project->price_unit ?? '') == $key ? 'selected' : '' }}>
                                                {{ $val }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row mb15">
                            <div class="col-lg-12">
                                <div class="form-row">
                                    <label class="control-label text-left">Loại giao dịch</label>
                                    <select name="transaction_type" class="form-control setupSelect2">
                                        <option value="sale"
                                            {{ old('transaction_type', $project->transaction_type ?? '') == 'sale' ? 'selected' : '' }}>
                                            Bán</option>
                                        <option value="rent"
                                            {{ old('transaction_type', $project->transaction_type ?? '') == 'rent' ? 'selected' : '' }}>
                                            Cho thuê</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                @include('backend.dashboard.component.album', ['model' => $project ?? null])
                @include('backend.dashboard.component.seo', ['model' => $project ?? null])
            </div>
            <div class="col-lg-3">
                <div class="ibox">
                    <div class="ibox-title">
                        <h5>Nhóm dự án <span class="text-danger">(*)</span></h5>
                    </div>
                    <div class="ibox-content">
                        <select name="project_catalogue_id" class="form-control setupSelect2">
                            <option value="0">[Chọn nhóm tin]</option>
                            @foreach ($dropdown as $key => $val)
                                <option value="{{ $key }}"
                                    {{ old('project_catalogue_id', $project->project_catalogue_id ?? '') == $key ? 'selected' : '' }}>
                                    {{ $val }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="ibox">
                    <div class="ibox-title">
                        <h5>Chọn Nhân viên <span class="text-danger">(*)</span></h5>
                    </div>
                    <div class="ibox-content">
                        <select name="agent_id" class="form-control setupSelect2">
                            @foreach ($agents as $key => $val)
                                <option value="{{ $key }}"
                                    {{ old('agent_id', $project->agent_id ?? '') == $key ? 'selected' : '' }}>
                                    {{ $val }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="ibox">
                    <div class="ibox-title">
                        <h5>Ảnh đại diện</h5>
                    </div>
                    <div class="ibox-content text-center">
                        <div class="form-row">
                            <span class="image img-cover image-target"><img
                                    src="{{ old('cover_image', $project->cover_image ?? '') != '' ? asset(old('cover_image', $project->cover_image ?? '')) : asset('backend/img/not-found.jpg') }}"
                                    alt=""></span>
                            <input type="hidden" name="cover_image"
                                value="{{ old('cover_image', $project->cover_image ?? '') }}">
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
                                    <label class="control-label text-left">Trạng thái</label>
                                    <select name="publish" class="form-control setupSelect2">
                                        @foreach (__('messages.publish') as $key => $val)
                                            <option
                                                {{ $key == old('publish', $project->publish ?? '') ? 'selected' : '' }}
                                                value="{{ $key }}">{{ $val }}</option>
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
