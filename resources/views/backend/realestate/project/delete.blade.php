@include('backend.dashboard.component.breadcrumb', ['title' => $config['seo'][$config['method']]['title']])
<div class="wrapper wrapper-content animated fadeInRight">
    <form action="{{ route('project.destroy', $project->id) }}" method="post">
        @csrf
        @method('DELETE')
        <div class="ibox">
            <div class="ibox-title">
                <h5>Xác nhận xóa dự án</h5>
            </div>
            <div class="ibox-content">
                <div class="alert alert-danger">
                    Lưu ý: Bạn đang muốn xóa dự án có tên là: <strong>{{ $project->name }}</strong>.
                    Hành động này không thể khôi phục. Các bất động sản thuộc dự án này sẽ mất liên kết dự án.
                </div>
                <div class="text-right">
                    <a href="{{ route('project.index') }}" class="btn btn-white">Hủy bỏ</a>
                    <button type="submit" class="btn btn-danger">Xác nhận xóa</button>
                </div>
            </div>
        </div>
    </form>
</div>
