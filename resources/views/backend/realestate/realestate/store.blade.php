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
    $url = $config['method'] == 'create' ? route('real.estate.store') : route('real.estate.update', $realEstate->id);
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
                        @include('backend.dashboard.component.content', ['model' => $realEstate ?? null])
                    </div>
                </div>

                @include('backend.realestate.realestate.component.location', [
                    'model' => $realEstate ?? null,
                ])

                <div class="ibox">
                    <div class="ibox-title">
                        <h5>Thông số chi tiết</h5>
                    </div>
                    <div class="ibox-content">
                        <div class="mb15">
                            <span class="text-warning"><strong>Lưu ý*:</strong></span>
                            <span class="text-muted">Mỗi dự án/loại hình có đặc thù riêng, bạn <strong>không nhất
                                    thiết</strong> phải điền đầy đủ tất cả các thông số bên dưới. Chỉ tập trung vào
                                các thông tin trong trong bất động sản đang cần thêm mới/cập nhật.</span>
                        </div>
                        @include('backend.realestate.realestate.component.specs', [
                            'model' => $realEstate ?? null,
                        ])
                    </div>
                </div>

                @include('backend.realestate.realestate.component.amenity', [
                    'model' => $realEstate ?? null,
                    'amenityCatalogues' => $amenityCatalogues ?? null,
                ])

                @include('backend.dashboard.component.seo', ['model' => $realEstate ?? null])
            </div>
            <div class="col-lg-3">
                @include('backend.realestate.realestate.component.aside', ['model' => $realEstate ?? null])
            </div>
        </div>
        <div class="text-right mb15 fixed-bottom">
            <button class="btn btn-primary" type="submit" name="send"
                value="send_and_stay">{{ __('messages.save') }}</button>
            <button class="btn btn-success" type="submit" name="send" value="send_and_exit">Đóng</button>
        </div>
    </div>
</form>
