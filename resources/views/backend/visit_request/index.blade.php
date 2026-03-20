@include('backend.dashboard.component.breadcrumb', ['title' => $config['seo']['index']['title']])
<div class="row mt20">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>{{ $config['seo']['index']['table'] }} </h5>
                @include('backend.dashboard.component.toolbox', ['model' => $config['model']])
            </div>
            <div class="ibox-content">
                <x-backend.filter createRoute="visit_request.create" submitRoute="visit_request.index" />
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
                        'property' => [
                            'label' => 'Bất động sản',
                            'render' => fn($item) => $item->properties?->title ?? 'N/A',
                        ],
                        'status' => [
                            'label' => 'Trang thái',
                            'render' => fn($item) => match ($item->status) {
                                'pending' => '<span class="label label-warning">Pending</span>',
                                'confirmed' => '<span class="label label-info">Confirmed</span>',
                                'completed' => '<span class="label label-primary">Completed</span>',
                                'cancelled' => '<span class="label label-default">Cancelled</span>',
                                default => $item->status,
                            },
                        ],
                    ];
                @endphp
                <x-backend.customtable :records="$records->getCollection()" :columns="$columns" :actions="[
                    ['route' => 'visit_request.edit', 'class' => 'btn-success', 'icon' => 'fa-edit'],
                    ['route' => 'visit_request.delete', 'class' => 'btn-danger', 'icon' => 'fa-trash'],
                ]" :model="$config['model']" />
                {{ $records->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
</div>
