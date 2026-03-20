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
    $url =
        $config['method'] == 'create'
            ? route('location_highlight.store')
            : route('location_highlight.update', $record->id);
@endphp
<form action="{{ $url }}" method="post" class="box">
    @csrf
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-8">
                <div class="ibox">
                    <div class="ibox-title">
                        <h5>Thông tin vị trí nổi bật</h5>
                    </div>
                    <div class="ibox-content">
                        <div class="row mb15">
                            <div class="col-lg-12">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">Bất động sản <span
                                            class="text-danger">(*)</span></label>
                                    <select name="property_id" class="form-control setupSelect2">
                                        <option value="">[Chọn Bất động sản]</option>
                                        @foreach ($properties as $property)
                                            <option
                                                {{ $property->id == old('property_id', isset($record->property_id) ? $record->property_id : '') ? 'selected' : '' }}
                                                value="{{ $property->id }}">{{ $property->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row mb15">
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">Danh mục <span
                                            class="text-danger">(*)</span></label>
                                    <select name="category" class="form-control setupSelect2">
                                        <option value="">[Chọn Danh mục]</option>
                                        <option
                                            {{ old('category', $record->category ?? '') == 'education' ? 'selected' : '' }}
                                            value="education">Giáo dục (Trường học)</option>
                                        <option
                                            {{ old('category', $record->category ?? '') == 'health' ? 'selected' : '' }}
                                            value="health">Y tế (Bệnh viện)</option>
                                        <option
                                            {{ old('category', $record->category ?? '') == 'shopping' ? 'selected' : '' }}
                                            value="shopping">Mua sắm (TMTM, Chợ)</option>
                                        <option
                                            {{ old('category', $record->category ?? '') == 'transport' ? 'selected' : '' }}
                                            value="transport">Giao thông (Sân bay, Bến xe)</option>
                                        <option
                                            {{ old('category', $record->category ?? '') == 'entertainment' ? 'selected' : '' }}
                                            value="entertainment">Giải trí (Công viên, Rạp phim)</option>
                                        <option
                                            {{ old('category', $record->category ?? '') == 'other' ? 'selected' : '' }}
                                            value="other">Khác</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">Tên địa điểm <span
                                            class="text-danger">(*)</span></label>
                                    <input type="text" name="name"
                                        value="{{ old('name', $record->name ?? '') }}" class="form-control"
                                        placeholder="Ví dụ: ĐH Quốc Gia, BV Bạch Mai..." autocomplete="off">
                                </div>
                            </div>
                        </div>
                        <div class="row mb15">
                            <div class="col-lg-12">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">Khoảng cách / Thời gian <span
                                            class="text-danger">(*)</span></label>
                                    <input type="text" name="distance_text"
                                        value="{{ old('distance_text', $record->distance_text ?? '') }}"
                                        class="form-control" placeholder="Ví dụ: 500m, 5 phút lái xe, 10 phút đi bộ...">
                                </div>
                            </div>
                        </div>
                        <div class="row mb15">
                            <div class="col-lg-12">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">Mô tả thêm</label>
                                    <textarea name="description" class="form-control" rows="4">{{ old('description', $record->description ?? '') }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="ibox">
                    <div class="ibox-title">
                        <h5>Cấu hình</h5>
                    </div>
                    <div class="ibox-content">
                        <div class="row mb15">
                            <div class="col-lg-12">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">Thứ tự</label>
                                    <input type="number" name="sort_order"
                                        value="{{ old('sort_order', $record->sort_order ?? 0) }}" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="row mb15">
                            <div class="col-lg-12">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">Trạng thái</label>
                                    <select name="publish" class="form-control setupSelect2">
                                        @foreach (__('messages.publish') as $key => $val)
                                            <option
                                                {{ $key == old('publish', isset($record->publish) ? $record->publish : '2') ? 'selected' : '' }}
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
        <div class="text-right mb15">
            <button class="btn btn-primary" type="submit" name="send" value="send">Lưu lại</button>
        </div>
    </div>
</form>
