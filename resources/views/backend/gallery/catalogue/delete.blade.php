@include('backend.dashboard.component.breadcrumb', ['title' => $config['seo']['delete']['title']])

<form action="{{ route('gallery.catalogue.destroy', $record->id) }}" method="post" class="box">
    @csrf
    @method('DELETE')
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-5">
                <div class="panel-head">
                    <div class="panel-title">Thông tin chung</div>
                    <div class="panel-description">
                        <p>Bạn đang muốn xóa nhóm thư viện ảnh có tên là: <span
                                class="text-danger">{{ $record->name ?? ($record->languages->first()->pivot->name ?? '') }}</span>
                        </p>
                        <p>Lưu ý: Không thể khôi phục nhóm thư viện ảnh sau khi xóa. Hãy chắc chắn bạn muốn thực hiện
                            chức năng này.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-7">
                <div class="ibox">
                    <div class="ibox-content">
                        <div class="row mb15">
                            <div class="col-lg-12">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">Tên Bất Động Sản <span
                                            class="text-danger">(*)</span></label>
                                    <input type="text" name="name"
                                        value="{{ old('name', $record->name ?? ($record->languages->first()->pivot->name ?? '')) }}"
                                        class="form-control" placeholder="" autocomplete="off" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="text-right mb15">
            <button class="btn btn-danger" type="submit" name="send" value="send">Xóa bản ghi</button>
        </div>
    </div>
</form>
