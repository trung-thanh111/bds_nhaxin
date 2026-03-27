@include('backend.dashboard.component.breadcrumb', ['title' => $config['seo']['index']['title']])
<div class="row mt20">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>{{ $config['seo']['index']['table'] }} </h5>
                @include('backend.dashboard.component.toolbox', ['model' => $config['model']])
            </div>
            <div class="ibox-content">
                <x-backend.filter createRoute="contact_request.create" submitRoute="contact_request.index" />
                @php
                    $columns = [
                        'customer' => [
                            'label' => 'Khách hàng',
                            'render' => fn($item) => '<b>' .
                                e($item->full_name) .
                                '</b><br><small>' .
                                e($item->phone) .
                                ' - ' .
                                e($item->email) .
                                '</small>',
                        ],
                        'subject' => [
                            'label' => 'Tiêu đề',
                            'render' => fn($item) => e($item->subject) ?: '<i class="text-muted">Không có tiêu đề</i>',
                        ],
                        'status' => [
                            'label' => 'Trạng thái',
                            'render' => fn($item) => match ($item->status) {
                                'pending' => '<span class="label label-warning">Chờ xử lý</span>',
                                'confirmed' => '<span class="label label-info">Đã xác nhận</span>',
                                'completed' => '<span class="label label-primary">Hoàn thành</span>',
                                'cancelled' => '<span class="label label-default">Hủy bỏ</span>',
                                default => $item->status,
                            },
                        ],
                    ];
                @endphp
                <x-backend.customtable :records="$records->getCollection()" :columns="$columns" :actions="[
                    ['route' => 'contact_request.edit', 'class' => 'btn-success', 'icon' => 'fa-edit'],
                    ['route' => 'contact_request.delete', 'class' => 'btn-danger', 'icon' => 'fa-trash'],
                ]" :model="$config['model']" />
                {{ $records->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
</div>
