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
                    createRoute="agent.create"
                    submitRoute="agent.index" />
                @php
                $columns = [
                'avatar' => ['label' => 'Ảnh', 'render' => fn($item) => '<img src="'.asset($item->avatar ?? 'backend/img/img-not-found.jpg').'" style="width:50px;height:50px;border-radius:50%;object-fit:cover" />'],
                'full_name' => ['label' => 'Họ tên', 'render' => fn($item) => e($item->full_name)],
                'title' => ['label' => 'Chức danh', 'render' => fn($item) => e($item->title)],
                'contact' => ['label' => 'Liên hệ', 'render' => fn($item) => '<small>ĐT: ' . e($item->phone) . '<br>Email: ' . e($item->email) . '</small>'],
                ];
                @endphp
                <x-backend.customtable
                    :records="$records->getCollection()"
                    :columns="$columns"
                    :actions="[
                        ['route' => 'agent.edit', 'class' => 'btn-success', 'icon' => 'fa-edit'],
                        ['route' => 'agent.delete', 'class' => 'btn-danger', 'icon' => 'fa-trash'],
                    ]"
                    :model="$config['model']" />
                {{ $records->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
</div>