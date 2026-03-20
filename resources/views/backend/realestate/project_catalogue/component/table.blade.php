<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th style="width:50px;">
                <input type="checkbox" value="" id="checkAll" class="input-checkbox">
            </th>
            <th>Tên Nhóm dự án</th>
            @include('backend.dashboard.component.languageTh')
            <th class="text-center" style="width:100px;">Thứ tự</th>
            <th class="text-center" style="width:100px;">Tình trạng</th>
            <th class="text-center" style="width:100px;">Thao tác</th>
        </tr>
    </thead>
    <tbody>
        @if (isset($projectCatalogues) && is_object($projectCatalogues))
            @foreach ($projectCatalogues as $projectCatalogue)
                <tr>
                    <td>
                        <input type="checkbox" value="{{ $projectCatalogue->id }}" class="input-checkbox checkBoxItem">
                    </td>
                    <td>
                        {{ str_repeat('|----', $projectCatalogue->level > 0 ? $projectCatalogue->level - 1 : 0) . $projectCatalogue->name }}
                    </td>
                    @include('backend.dashboard.component.languageTd', [
                        'model' => $projectCatalogue,
                        'modeling' => 'ProjectCatalogue',
                    ])
                    <td>
                        <input type="text" name="order" value="{{ $projectCatalogue->order }}"
                            class="form-control sort-order text-right" data-id="{{ $projectCatalogue->id }}"
                            data-model="ProjectCatalogue">
                    </td>
                    <td class="text-center js-switch-{{ $projectCatalogue->id }}">
                        <input type="checkbox" value="{{ $projectCatalogue->publish }}" class="js-switch"
                            data-field="publish" data-model="ProjectCatalogue" data-value="{{ $projectCatalogue->publish }}"
                            data-id="{{ $projectCatalogue->id }}" {{ $projectCatalogue->publish == 2 ? 'checked' : '' }} />
                    </td>
                    <td class="text-center">
                        <a href="{{ route('project.catalogue.edit', $projectCatalogue->id) }}" class="btn btn-success"><i
                                class="fa fa-edit"></i></a>
                        <a href="{{ route('project.catalogue.delete', $projectCatalogue->id) }}" class="btn btn-danger"><i
                                class="fa fa-trash"></i></a>
                    </td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>
{{ $projectCatalogues->links('pagination::bootstrap-4') }}
