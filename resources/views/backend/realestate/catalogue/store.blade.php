@include('backend.dashboard.component.breadcrumb', ['title' => $config['seo'][$config['method']]['title']])
@include('backend.dashboard.component.formError')
@php
    $url = ($config['method'] == 'create') ? route('real_estate.catalogue.store') : route('real_estate.catalogue.update', $realEstateCatalogue->id);
@endphp
<form action="{{ $url }}" method="post" class="box">
    @csrf
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-9">
                <div class="ibox">
                    <div class="ibox-title">
                        <h5>{{ __('messages.tableHeading') }}</h5>
                    </div>
                    <div class="ibox-content">
                        @include('backend.dashboard.component.content', ['model' => ($realEstateCatalogue) ?? null])
                    </div>
                </div>
               @include('backend.dashboard.component.album', ['model' => ($realEstateCatalogue) ?? null])
               @include('backend.dashboard.component.seo', ['model' => ($realEstateCatalogue) ?? null])
            </div>
            <div class="col-lg-3">
                @include('backend.realestate.catalogue.component.aside')
            </div>
        </div>
        <div class="text-right mb15 fixed-bottom">
            <button class="btn btn-primary" type="submit" name="send" value="send_and_stay">{{ __('messages.save') }}</button>
            <button class="btn btn-success" type="submit" name="send" value="send_and_exit">Đóng</button>
        </div>
    </div>
</form>
