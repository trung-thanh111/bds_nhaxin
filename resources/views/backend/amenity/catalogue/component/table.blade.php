<table class="table table-striped table-bordered">
    <thead>
    <tr>
        <th style="width:50px;">
            <input type="checkbox" value="" id="checkAll" class="input-checkbox">
        </th>
        <th>{{ __('messages.tableName') }}</th>
        @include('backend.dashboard.component.languageTh')
        <th class="text-center" style="width:100px;">{{ __('messages.tableStatus') }}</th>
        <th class="text-center" style="width:100px;">{{ __('messages.tableAction') }}</th>
    </tr>
    </thead>
    <tbody>
        @if(isset($amenityCatalogues) && is_object($amenityCatalogues))
            @foreach($amenityCatalogues as $amenityCatalogue)
            <tr id="{{ $amenityCatalogue->id }}">
                <td>
                    <input type="checkbox" value="{{ $amenityCatalogue->id }}" class="input-checkbox checkBoxItem">
                </td>
                <td>
                    {{ str_repeat('|-----', (($amenityCatalogue->level > 0)?($amenityCatalogue->level - 1):0)).$amenityCatalogue->name }}
                </td>
                @include('backend.dashboard.component.languageTd', ['model' => $amenityCatalogue, 'modeling' => 'AmenityCatalogue'])
                <td class="text-center js-switch-{{ $amenityCatalogue->id }}"> 
                    <input type="checkbox" value="{{ $amenityCatalogue->publish }}" class="js-switch status " data-field="publish" data-model="AmenityCatalogue" {{ ($amenityCatalogue->publish == 2) ? 'checked' : '' }} data-modelId="{{ $amenityCatalogue->id }}" />
                </td>
                <td class="text-center"> 
                    <a href="{{ route('amenity.catalogue.edit', $amenityCatalogue->id) }}" class="btn btn-success"><i class="fa fa-edit"></i></a>
                    <a href="{{ route('amenity.catalogue.delete', $amenityCatalogue->id) }}" class="btn btn-danger"><i class="fa fa-trash"></i></a>
                </td>
            </tr>
            @endforeach
        @endif
    </tbody>
</table>
{{  $amenityCatalogues->links('pagination::bootstrap-4') }}
