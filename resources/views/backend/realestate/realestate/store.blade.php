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
<style>
    .cursor-pointer {
        cursor: pointer !important;
    }

    .price-suggestions {
        position: absolute;
        z-index: 1000;
        background: #fff;
        width: 100%;
        border: 1px solid #d3dbe2;
        max-height: 200px;
        overflow-y: auto;
        display: none;
    }

    .suggestion-item:hover {
        background: #f3f3f4;
        color: #1ab394;
    }

    .price-readable {
        font-weight: bold;
        color: #1ab394;
        margin-left: 10px;
    }
</style>
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

                @include('backend.dashboard.component.album', ['model' => $realEstate ?? null])

                <div class="ibox">
                    <div class="ibox-title">
                        <h5>Thông tin Giá & Giao dịch</h5>
                    </div>
                    <div class="ibox-content">
                        <div class="row mb15">
                            <div class="col-lg-12">
                                <div class="form-row">
                                    <label class="control-label text-left">Loại giao dịch <span
                                            class="text-danger">(*)</span></label>
                                    <select name="transaction_type" class="form-control setupSelect2">
                                        <option value="0">[Chọn loại giao dịch]</option>
                                        @if (isset($dropdowns['loai_giao_dich']))
                                            @foreach ($dropdowns['loai_giao_dich'] as $key => $val)
                                                <option value="{{ $key }}"
                                                    {{ old('transaction_type', $realEstate->transaction_type ?? '') == $key ? 'selected' : '' }}>
                                                    {{ $val }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row mb15">
                            <div class="col-lg-4">
                                <div class="form-row position-relative">
                                    <label class="control-label text-left">Giá bán <span class="price-readable"></span></label>
                                    <input type="text" name="price_sale"
                                        value="{{ old('price_sale', isset($realEstate) ? number_format($realEstate->price_sale, 0, ',', '.') : '') }}"
                                        class="form-control int price-input" autocomplete="off" placeholder="0">
                                    <div class="price-suggestions"></div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-row position-relative">
                                    <label class="control-label text-left">Giá thuê <span class="price-readable"></span></label>
                                    <input type="text" name="price_rent"
                                        value="{{ old('price_rent', isset($realEstate) ? number_format($realEstate->price_rent, 0, ',', '.') : '') }}"
                                        class="form-control int price-input" autocomplete="off" placeholder="0">
                                    <div class="price-suggestions"></div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-row">
                                    <label class="control-label text-left">Đơn vị giá</label>
                                    <select name="price_unit" class="form-control setupSelect2">
                                        <option value="0">[Chọn đơn vị]</option>
                                        @if (isset($dropdowns['loai_gia']))
                                            @foreach ($dropdowns['loai_gia'] as $key => $val)
                                                <option value="{{ $key }}"
                                                    {{ old('price_unit', $realEstate->price_unit ?? '') == $key ? 'selected' : '' }}>
                                                    {{ $val }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                        </div>
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
