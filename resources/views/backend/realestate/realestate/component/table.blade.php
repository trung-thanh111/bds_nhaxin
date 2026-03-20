<table class="table table-striped table-bordered text-center">
    <thead>
        <tr>
            <th style="width:50px;">
                <input type="checkbox" value="" id="checkAll" class="input-checkbox">
            </th>
            <th class="text-left">Thông tin Tài sản</th>
            <th style="width:150px;">Diện tích</th>
            <th style="width:100px;">Sắp xếp</th>
            <th style="width:100px;">Trạng thái</th>
            <th style="width:100px;">Thao tác</th>
        </tr>
    </thead>
    <tbody>
        @if (isset($realEstates) && is_object($realEstates))
            @foreach ($realEstates as $realEstate)
                <tr>
                    <td>
                        <input type="checkbox" value="{{ $realEstate->id }}" class="input-checkbox checkBoxItem">
                    </td>
                    <td class="text-left">
                        <div class="uk-flex uk-flex-middle" style="gap: 15px;">
                            <div class="image img-cover" style="width: 80px; height: auto;">
                                <img src="{{ $realEstate->image ? asset($realEstate->image) : asset('backend/img/not-found.jpg') }}" alt="" style="width: 100%; height: auto; object-fit: cover;">
                            </div>
                            <div class="info-item-realestate">
                                <div class="name-realestate">
                                    <a href="{{ route('real.estate.edit', $realEstate->id) }}"><strong>{{ $realEstate->name }}</strong></a>
                                </div>
                                <div class="code-realestate small">
                                    <span class="text-navy">Mã: </span>{{ $realEstate->code ?? 'N/A' }} | 
                                    <span class="text-navy">Nhóm: </span> {{ $realEstate->catalogue_name ?? 'Bổ sung nhóm' }}
                                </div>
                            </div>
                        </div>
                    </td>
                    <td>
                        {{ $realEstate->area }} m²
                    </td>
                    <td>
                        <input type="text" name="order" value="{{ $realEstate->order }}"
                            class="form-control sort-order text-right" data-id="{{ $realEstate->id }}"
                            data-model="RealEstate">
                    </td>
                    <td class="text-center js-switch-{{ $realEstate->id }}">
                        <input type="checkbox" value="{{ $realEstate->publish }}" class="js-switch status "
                            data-field="publish" data-model="RealEstate"
                            {{ $realEstate->publish == 2 ? 'checked' : '' }} data-modelId="{{ $realEstate->id }}" />
                    </td>
                    <td class="text-center">
                        <a href="{{ route('real.estate.edit', $realEstate->id) }}" class="btn btn-success"><i
                                class="fa fa-edit"></i></a>
                        <a href="{{ route('real.estate.delete', $realEstate->id) }}" class="btn btn-danger"><i
                                class="fa fa-trash"></i></a>
                    </td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>
<div class="text-center">
    {{ $realEstates->links('pagination::bootstrap-4') }}
</div>
