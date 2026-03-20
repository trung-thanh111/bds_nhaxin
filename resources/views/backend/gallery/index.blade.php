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
                    createRoute="gallery.create"
                    submitRoute="gallery.index" />
                @php
                $columns = [
                'image' => ['label' => 'Hình ảnh đại diện', 'render' => fn($item) => '<img src="'.($item->image ? asset($item->image) : asset('backend/img/img-not-found.jpg')).'" style="width:100px;height:60px;object-fit:cover;border-radius:4px;" />'],
                'real_estate' => ['label' => 'Bất động sản', 'render' => fn($item) => $item->real_estates?->languages->first()->pivot->name ?? ($item->real_estates?->title ?? 'N/A')],
                'album_count' => ['label' => 'Số lượng ảnh', 'render' => fn($item) => is_array($item->album) ? count($item->album) : 0],
                ];
                @endphp
                <x-backend.customtable
                    :records="$records->getCollection()"
                    :columns="$columns"
                    :actions="[
                        ['route' => 'gallery.edit', 'class' => 'btn-success', 'icon' => 'fa-edit'],
                        ['route' => 'gallery.delete', 'class' => 'btn-danger', 'icon' => 'fa-trash'],
                    ]"
                    :model="$config['model']" />
                {{ $records->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
</div>