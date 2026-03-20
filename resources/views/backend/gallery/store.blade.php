@include('backend.dashboard.component.breadcrumb', ['title' => $config['seo'][$config['method']]['title']])
@include('backend.dashboard.component.formError')
@php
    $url = $config['method'] == 'create' ? route('gallery.store') : route('gallery.update', $record->id);
@endphp
<form action="{{ $url }}" method="post" class="box">
    @csrf
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-9">
                @include('backend.dashboard.component.album', ['model' => $record ?? null])
            </div>

            <div class="col-lg-3">
                <div class="ibox w">
                    <div class="ibox-title">
                        <h5>Bất động sản</h5>
                    </div>
                    <div class="ibox-content">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-row">
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
                            <div class="col-lg-12 mt15">
                                <div class="form-row">
                                    <select name="gallery_catalogue_id" class="form-control setupSelect2">
                                        <option value="">[Chọn Nhóm thư viện]</option>
                                        @foreach ($galleryCatalogues as $catalogue)
                                            <option
                                                {{ $catalogue->id == old('gallery_catalogue_id', isset($record->gallery_catalogue_id) ? $record->gallery_catalogue_id : '') ? 'selected' : '' }}
                                                value="{{ $catalogue->id }}">
                                                {{ $catalogue->languages->first()->pivot->name ?? 'Unknown' }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @include('backend.dashboard.component.publish', [
                    'model' => $record ?? null,
                    'hideImage' => true,
                ])
            </div>
        </div>
        <div class="text-right mb15" style="position: fixed; bottom: 20px; right: 20px; z-index: 99;">
            <button class="btn btn-primary" type="submit" name="send" value="send"
                style="box-shadow: 0 4px 12px rgba(0,0,0,0.15); border-radius: 8px; padding: 10px 25px;">Lưu
                lại</button>
        </div>
    </div>
</form>
