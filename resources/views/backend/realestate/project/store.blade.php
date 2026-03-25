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
                        <h5>Quy mô dự án</h5>
                    </div>
                    <div class="ibox-content">
                        <div class="row mb15">
                            <div class="col-lg-3">
                                <div class="form-row">
                                    <label class="control-label text-left">Số căn hộ</label>
                                    <input type="text" name="apartment_count"
                                        value="{{ old('apartment_count', $project->apartment_count ?? 0) }}"
                                        class="form-control int" placeholder="0">
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-row">
                                    <label class="control-label text-left">Số tòa</label>
                                    <input type="text" name="block_count"
                                        value="{{ old('block_count', $project->block_count ?? 0) }}"
                                        class="form-control int" placeholder="0">
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-row">
                                    <label class="control-label text-left">Diện tích</label>
                                    <input type="text" name="area" value="{{ old('area', $project->area ?? '') }}"
                                        class="form-control" placeholder="VD: 12ha, 5000m2">
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-row">
                                    <label class="control-label text-left">Pháp lý</label>
                                    <select name="legal_status" class="form-control setupSelect2">
                                        <option value="0">[Chọn pháp lý]</option>
                                        @if (isset($dropdowns['phap_ly']))
                                            @foreach ($dropdowns['phap_ly'] as $key => $val)
                                                <option value="{{ $key }}"
                                                    {{ old('legal_status', $project->legal_status ?? '') == $key ? 'selected' : '' }}>
                                                    {{ $val }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @include('backend.realestate.project.component.location', [
                    'model' => $project ?? null,
                ])

                @include('backend.realestate.realestate.component.amenity', [
                    'model' => $project ?? null,
                    'amenityCatalogues' => $amenityCatalogues ?? null,
                ])

                <div class="ibox">
                    <div class="ibox-title">
                        <h5>Mặt bằng dự án</h5>
                    </div>
                    <div class="ibox-content">
                        <select name="floorplans[]" class="form-control setupSelect2" multiple>
                            @foreach ($floorplans as $item)
                                <option value="{{ $item->id }}"
                                    {{ isset($project) && $project->floorplans->contains($item->id) ? 'selected' : '' }}>
                                    {{ $item->languages->first()->pivot->name ?? 'N/A' }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="ibox">
                    <div class="ibox-title">
                        <h5>Dự án liên quan</h5>
                    </div>
                    <div class="ibox-content">
                        <select name="related_projects[]" class="form-control setupSelect2" multiple>
                            @foreach ($allProjects as $item)
                                <option value="{{ $item->id }}"
                                    {{ isset($project) && $project->related_projects->contains($item->id) ? 'selected' : '' }}>
                                    {{ $item->languages->first()->pivot->name ?? '' }}</option>
                            @endforeach
                        </select>
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
