<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th class="text-center" style="width: 50px;">
                <input type="checkbox" value="" id="checkAll" class="input-checkbox">
            </th>
            <th>Thông tin </th>
            <th>Bất động sản</th>
            <th class="text-center" style="width: 100px;">Tình trạng</th>
            <th class="text-center" style="width: 100px;">Thao tác</th>
        </tr>
    </thead>
    <tbody>
        @if (isset($floorplans) && is_object($floorplans))
            @foreach ($floorplans as $floorplan)
                <tr>
                    <td class="text-center">
                        <input type="checkbox" value="{{ $floorplan->id }}" class="input-checkbox checkBoxItem">
                    </td>
                    <td>
                        <div style="display: flex; align-items: center; gap: 15px;">
                            <span class="image img-cover" style="width: 80px; flex-shrink: 0; height: auto;">
                                <img src="{{ asset($floorplan->image) }}" alt=""
                                    style="width: 100%; height: auto; object-fit: cover;">
                            </span>
                            <span>{{ $floorplan->name }}</span>
                        </div>
                    </td>
                    <td>
                        {{ $floorplan->real_estate_name ?? 'Bổ sung BĐS' }}
                    </td>
                    <td class="text-center js-switch-{{ $floorplan->id }}">
                        <input type="checkbox" value="{{ $floorplan->publish }}" class="js-switch status "
                            data-field="publish" data-model="Floorplan" data-modelId="{{ $floorplan->id }}"
                            {{ $floorplan->publish == 2 ? 'checked' : '' }} />
                    </td>
                    <td class="text-center">
                        <a href="{{ route('floorplan.edit', $floorplan->id) }}" class="btn btn-success"><i
                                class="fa fa-edit"></i></a>
                        <a href="{{ route('floorplan.delete', $floorplan->id) }}" class="btn btn-danger"><i
                                class="fa fa-trash"></i></a>
                    </td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>
{{ $floorplans->links('pagination::bootstrap-4') }}
