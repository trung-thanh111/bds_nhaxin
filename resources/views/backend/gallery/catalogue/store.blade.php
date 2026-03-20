@include('backend.dashboard.component.breadcrumb', ['title' => $config['seo'][$config['method']]['title']])
@include('backend.dashboard.component.formError')
@php
    $url =
        $config['method'] == 'create'
            ? route('gallery.catalogue.store')
            : route('gallery.catalogue.update', $record->id);
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
                        @include('backend.dashboard.component.content', ['model' => $record ?? null])
                    </div>
                </div>
                @include('backend.dashboard.component.album', ['model' => $record ?? null])
                @include('backend.dashboard.component.seo', ['model' => $record ?? null])
            </div>
            <div class="col-lg-3">
                @include('backend.gallery.catalogue.component.aside')
            </div>
        </div>
        <div class="text-right mb15 fixed-bottom">
            <button class="btn btn-primary" type="submit" name="send" value="send_and_stay">Lưu lại</button>
        </div>
    </div>
</form>
