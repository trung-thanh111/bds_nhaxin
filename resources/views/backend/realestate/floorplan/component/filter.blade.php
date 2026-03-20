<div class="filter-wrapper">
    <form action="{{ route('floorplan.index') }}">
        <div class="uk-flex uk-flex-middle uk-flex-space-between">
            @include('backend.dashboard.component.perpage')
            <div class="action">
                <div class="uk-flex uk-flex-middle">
                    <div class="mr15">
                        <select name="publish" class="form-control setupSelect2" style="width: 150px;">
                            @foreach (__('messages.publish') as $key => $val)
                                <option {{ request('publish') == $key ? 'selected' : '' }} value="{{ $key }}">
                                    {{ $val }}</option>
                            @endforeach
                        </select>
                    </div>
                    @include('backend.dashboard.component.keyword')
                    <a href="{{ route('floorplan.create') }}" class="btn btn-danger"><i class="fa fa-plus mr5"></i>Thêm
                        mới</a>
                </div>
            </div>
        </div>
    </form>
</div>
