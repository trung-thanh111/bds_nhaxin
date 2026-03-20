<form action="{{ route('real.estate.index') }}">
    <div class="filter-wrapper">
        <div class="uk-flex uk-flex-middle uk-flex-space-between filter-index">
            @include('backend.dashboard.component.perpage')
            <div class="action">
                <div class="uk-flex uk-flex-middle">
                    @php
                        $publish = request('publish') ?: old('publish');
                        $realEstateCatalogueId = request('real_estate_catalogue_id') ?: old('real_estate_catalogue_id');
                    @endphp
                    <div class="mr15">
                        <select name="publish" class="form-control setupSelect2">
                            @foreach (config('apps.general.publish') as $key => $val)
                                <option {{ $publish == $key ? 'selected' : '' }} value="{{ $key }}">
                                    {{ $val }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mr15">
                        <select name="real_estate_catalogue_id" class="form-control setupSelect2">
                            <option value="0">Chọn Nhóm BĐS</option>
                            @if (isset($dropdown))
                                @foreach ($dropdown as $key => $val)
                                    <option {{ $realEstateCatalogueId == $key ? 'selected' : '' }}
                                        value="{{ $key }}">{{ $val }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    @include('backend.dashboard.component.keyword')
                    <a href="{{ route('real.estate.create') }}" class="btn btn-danger"><i
                            class="fa fa-plus mr5"></i>Thêm mới</a>
                </div>
            </div>
        </div>
    </div>
</form>
