<table class="table table-striped table-bordered">
    <thead>
    <tr>
        <th style="width:50px;">
            <input type="checkbox" value="" id="checkAll" class="input-checkbox">
        </th>
        <th>{{ __('messages.tableName') }}</th>
        @include('backend.dashboard.component.languageTh')
        <th class="text-right">Sắp xếp</th>
        <th class="text-center" style="width:100px;">{{ __('messages.tableStatus') }} </th>
        <th class="text-center" style="width:100px;">{{ __('messages.tableAction') }} </th>
    </tr>
    </thead>
    <tbody>
        @if(isset($realEstateCatalogues) && is_object($realEstateCatalogues))
            @foreach($realEstateCatalogues as $realEstateCatalogue)
            <tr >
                <td>
                    <input type="checkbox" value="{{ $realEstateCatalogue->id }}" class="input-checkbox checkBoxItem">
                </td>
               
                <td>
                    {{ str_repeat('|----', (($realEstateCatalogue->level > 0)?($realEstateCatalogue->level - 1):0)).$realEstateCatalogue->name }}
                </td>
                @include('backend.dashboard.component.languageTd', ['model' => $realEstateCatalogue, 'modeling' => 'RealEstateCatalogue'])
                <td class="sort">
                    <input type="text" name="order" value="{{ $realEstateCatalogue->order }}" class="form-control sort-order text-right" data-id="{{ $realEstateCatalogue->id }}" data-model="{{ $config['model'] }}">
                </td>
                <td class="text-center js-switch-{{ $realEstateCatalogue->id }}"> 
                    <input type="checkbox" value="{{ $realEstateCatalogue->publish }}" class="js-switch status " data-field="publish" data-model="{{ $config['model'] }}" {{ ($realEstateCatalogue->publish == 2) ? 'checked' : '' }} data-modelId="{{ $realEstateCatalogue->id }}" />
                </td>
                <td class="text-center"> 
                    <a href="{{ route('real_estate.catalogue.edit', $realEstateCatalogue->id) }}" class="btn btn-success"><i class="fa fa-edit"></i></a>
                    <a href="{{ route('real_estate.catalogue.delete', $realEstateCatalogue->id) }}" class="btn btn-danger"><i class="fa fa-trash"></i></a>
                </td>
            </tr>
            @endforeach
        @endif
    </tbody>
</table>
{{  $realEstateCatalogues->links('pagination::bootstrap-4') }}
