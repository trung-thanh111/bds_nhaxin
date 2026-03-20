@include('backend.dashboard.component.breadcrumb', ['title' => $config['seo']['index']['title']])
<div class="row mt20">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>{{ $config['seo']['index']['table']; }} </h5>
                @include('backend.dashboard.component.toolbox', ['model' => $config['model']])
            </div>
            <div class="ibox-content">
                <x-backend.filter
                    createRoute="location_highlight.create"
                    submitRoute="location_highlight.index" />
                @php
                $columns = [
                'category' => ['label' => 'Danh mục', 'render' => fn($item) => match($item->category) {
                'education' => 'Giáo dục',
                'health' => 'Y tế',
                'shopping' => 'Mua sắm',
                'transport' => 'Giao thông',
                'entertainment' => 'Giải trí',
                default => $item->category
                }],
                'name' => ['label' => 'Tên địa điểm', 'render' => fn($item) => e($item->name)],
                'distance_text' => ['label' => 'Khoảng cách', 'render' => fn($item) => e($item->distance_text)],
                'created_at' => ['label' => 'Ngày tạo', 'render' => fn($item) => $item->created_at?->format('d-m-Y') ?? 'N/A'],
                ];
                @endphp
                <x-backend.customtable
                    :records="$records->getCollection()"
                    :columns="$columns"
                    :actions="[
                        ['route' => 'location_highlight.edit', 'class' => 'btn-success', 'icon' => 'fa-edit'],
                        ['route' => 'location_highlight.delete', 'class' => 'btn-danger', 'icon' => 'fa-trash'],
                    ]"
                    :model="$config['model']" />
                {{ $records->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
</div>