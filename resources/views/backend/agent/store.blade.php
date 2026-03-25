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
@php
$url = ($config['method'] == 'create') ? route('agent.store') : route('agent.update', $record->id);
@endphp
<form action="{{ $url }}" method="post" class="box">
    @csrf
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-8">
                <div class="ibox">
                    <div class="ibox-title">
                        <h5>Thông tin cá nhân</h5>
                    </div>
                    <div class="ibox-content">
                        <div class="row mb15">
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">Họ và tên <span class="text-danger">(*)</span></label>
                                    <input
                                        type="text"
                                        name="full_name"
                                        value="{{ old('full_name', ($record->full_name) ?? '' ) }}"
                                        class="form-control"
                                        placeholder=""
                                        autocomplete="off">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">Chức danh</label>
                                    <input
                                        type="text"
                                        name="title"
                                        value="{{ old('title', ($record->title) ?? '' ) }}"
                                        class="form-control"
                                        placeholder="Ví dụ: Chuyên viên tư vấn cấp cao">
                                </div>
                            </div>
                        </div>
                        <div class="row mb15">
                            <div class="col-lg-4">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">Số điện thoại <span class="text-danger">(*)</span></label>
                                    <input
                                        type="text"
                                        name="phone"
                                        value="{{ old('phone', ($record->phone) ?? '' ) }}"
                                        class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">Email <span class="text-danger">(*)</span></label>
                                    <input
                                        type="email"
                                        name="email"
                                        value="{{ old('email', ($record->email) ?? '' ) }}"
                                        class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">Zalo</label>
                                    <input
                                        type="text"
                                        name="zalo"
                                        value="{{ old('zalo', ($record->zalo) ?? '' ) }}"
                                        class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="row mb15">
                            <div class="col-lg-12">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">Tiểu sử / Giới thiệu</label>
                                    <textarea name="bio" class="ck-editor" id="ckBio">{{ old('bio', $record->bio ?? '') }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="ibox">
                    <div class="ibox-title">
                        <h5>Ảnh đại diện</h5>
                    </div>
                    <div class="ibox-content text-center">
                        <div class="form-row">
                            <span class="image img-cover image-target"><img src="{{ old('avatar', ($record->avatar) ?? '' ) ? old('avatar', ($record->avatar) ?? '') :  'backend/img/not-found.jpg' }}" alt=""></span>
                            <input type="hidden" name="avatar" value="{{ old('avatar', $record->avatar ?? '') }}">
                        </div>
                        <small class="text-muted mt10 block">Click để chọn ảnh</small>
                    </div>
                </div>
                <div class="ibox">
                    <div class="ibox-title">
                        <h5>Cấu hình</h5>
                    </div>
                    <div class="ibox-content">
                        <div class="row mb15">
                            <div class="col-lg-12">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">Trạng thái</label>
                                    <select name="publish" class="form-control setupSelect2">
                                        @foreach(__('messages.publish') as $key => $val)
                                        <option {{ $key == old('publish', (isset($record->publish)) ? $record->publish : '2') ? 'selected' : '' }} value="{{ $key }}">{{ $val }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row mb15">
                            <div class="col-lg-12">
                                <div class="form-row">
                                    <div class="gl-checkbox-wrapper">
                                        <input id="is_primary" type="checkbox" name="is_primary" value="1" {{ old('is_primary', $record->is_primary ?? 0) ? 'checked' : '' }}>
                                        <label for="is_primary">Nhân viên chính (Hotline)</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="text-right mb15">
            <button class="btn btn-primary" type="submit" name="send" value="send">Lưu lại</button>
        </div>
    </div>
</form>