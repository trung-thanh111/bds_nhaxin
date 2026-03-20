<table class="table table-striped table-bordered">
    <thead>
    <tr>
        <th style="width:50px;">
            <input type="checkbox" value="" id="checkAll" class="input-checkbox">
        </th>
        <th>{{ __('messages.tableName') }}</th>
        <th>Mã</th>
        @include('backend.dashboard.component.languageTh')
        <th class="text-center" style="width:100px;">{{ __('messages.tableStatus') }}</th>
        <th class="text-center" style="width:100px;">{{ __('messages.tableAction') }}</th>
    </tr>
    </thead>
    <tbody>
        @if(isset($amenities) && is_object($amenities))
            @foreach($amenities as $amenity)
            <tr id="{{ $amenity->id }}">
                <td>
                    <input type="checkbox" value="{{ $amenity->id }}" class="input-checkbox checkBoxItem">
                </td>
                <td>
                    <div class="uk-flex uk-flex-middle" style="gap: 15px;">
                        <div class="image img-cover" style="width: 80px; height: auto;">
                            <img src="{{ $amenity->image ? asset($amenity->image) : asset('backend/img/not-found.jpg') }}" alt="" style="width: 100%; height: auto; object-fit: cover;">
                        </div>
                        <div class="info-item">
                            <div class="name"><strong>{{ $amenity->name }}</strong></div>
                            <div class="catalogue-name small text-navy">Nhóm: {{ $amenity->catalogue_name ?? 'N/A' }}</div>
                        </div>
                    </div>
                </td>
                <td>
                    {{ $amenity->code ?? 'N/A' }}
                </td>
                @include('backend.dashboard.component.languageTd', ['model' => $amenity, 'modeling' => 'Amenity'])
                <td class="text-center js-switch-{{ $amenity->id }}"> 
                    <input type="checkbox" value="{{ $amenity->publish }}" class="js-switch status " data-field="publish" data-model="Amenity" {{ ($amenity->publish == 2) ? 'checked' : '' }} data-modelId="{{ $amenity->id }}" />
                </td>
                <td class="text-center"> 
                    <a href="{{ route('amenity.edit', $amenity->id) }}" class="btn btn-success"><i class="fa fa-edit"></i></a>
                    <a href="{{ route('amenity.delete', $amenity->id) }}" class="btn btn-danger"><i class="fa fa-trash"></i></a>
                </td>
            </tr>
            @endforeach
        @endif
    </tbody>
</table>
{{  $amenities->links('pagination::bootstrap-4') }}
