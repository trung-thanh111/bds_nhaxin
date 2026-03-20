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
    $url = ($config['method'] == 'create') ? route('project.catalogue.store') : route('project.catalogue.update', $projectCatalogue->id);
@endphp
<form action="{{ $url }}" method="post" class="box">
    @csrf
    <div class="wrapper wrapper-content animated fadeInRight">
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
                                    <label for="" class="control-label text-left">Tên Nhóm dự án <span class="text-danger">(*)</span></label>
                                    <input type="text" name="name" value="{{ old('name', ($projectCatalogue->name) ?? '' ) }}" class="form-control" autocomplete="off">
                                </div>
                            </div>
                        </div>
                        <div class="row mb15">
                            <div class="col-lg-12">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">Mô tả ngắn</label>
                                    <textarea name="description" class="form-control ck-editor" id="description" data-height="150">{{ old('description', ($projectCatalogue->description) ?? '') }}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row mb15">
                            <div class="col-lg-12">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">Nội dung</label>
                                    <textarea name="content" class="form-control ck-editor" id="content" data-height="300">{{ old('content', ($projectCatalogue->content) ?? '') }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @include('backend.dashboard.component.album', ['model' => ($projectCatalogue) ?? null])
                @include('backend.dashboard.component.seo', ['model' => ($projectCatalogue) ?? null])
            </div>
            <div class="col-lg-3">
                <div class="ibox">
                    <div class="ibox-title">
                        <h5>Danh mục cha</h5>
                    </div>
                    <div class="ibox-content">
                        <div class="form-row">
                            <select name="parent_id" class="form-control setupSelect2">
                                <option value="0">[Chọn danh mục cha]</option>
                                @foreach($dropdown as $key => $val)
                                    <option {{ $key == old('parent_id', (isset($projectCatalogue->parent_id)) ? $projectCatalogue->parent_id : '') ? 'selected' : '' }} value="{{ $key }}">{{ $val }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="ibox">
                    <div class="ibox-title">
                        <h5>Ảnh đại diện</h5>
                    </div>
                    <div class="ibox-content">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-row">
                                    <span class="image img-cover image-target"><img src="{{ (old('image', ($projectCatalogue->image) ?? '' )) ? asset(old('image', ($projectCatalogue->image) ?? '' )) : asset('backend/img/not-found.jpg') }}" alt=""></span>
                                    <input type="hidden" name="image" value="{{ old('image', ($projectCatalogue->image) ?? '' ) }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="ibox">
                    <div class="ibox-title">
                        <h5>Cấu hình nâng cao</h5>
                    </div>
                    <div class="ibox-content">
                        <div class="row mb15">
                            <div class="col-lg-12">
                                <div class="form-row">
                                    <label for="">Tình trạng</label>
                                    <select name="publish" class="form-control setupSelect2">
                                        @foreach(config('apps.general.publish') as $key => $val)
                                            <option {{ $key == old('publish', (isset($projectCatalogue->publish)) ? $projectCatalogue->publish : '') ? 'selected' : '' }} value="{{ $key }}">{{ $val }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row mb15">
                            <div class="col-lg-12">
                                <div class="form-row">
                                    <label for="">Thứ tự</label>
                                    <input type="text" name="order" value="{{ old('order', ($projectCatalogue->order) ?? 0 ) }}" class="form-control" autocomplete="off">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="text-right mb20">
            <button class="btn btn-primary" type="submit" name="send" value="send">Lưu lại</button>
        </div>
    </div>
</form>
