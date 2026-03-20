<form action="{{ route('project.index') }}" method="get">
    <div class="filter-wrapper">
        <div class="uk-flex uk-flex-middle uk-flex-space-between filter-index">
            @include('backend.dashboard.component.perpage')
            <div class="action">
                <div class="uk-flex uk-flex-middle">
                    <div class="mr15">
                        <select name="publish" class="form-control setupSelect2">
                            @foreach (__('messages.publish') as $key => $val)
                                <option {{ request('publish') == $key ? 'selected' : '' }} value="{{ $key }}">
                                    {{ $val }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="uk-search uk-flex uk-flex-middle mr15">
                        <div class="input-group">
                            <input type="text" name="keyword" value="{{ request('keyword') ?: old('keyword') }}"
                                placeholder="Nhập từ khóa tìm kiếm..." class="form-control">
                            <span class="input-group-btn">
                                <button type="submit" name="search" value="search"
                                    class="btn btn-primary mb0 btn-sm">Tìm Kiếm
                                </button>
                            </span>
                        </div>
                    </div>
                    <a href="{{ route('project.create') }}" class="btn btn-danger btn-sm ml10"><i
                            class="fa fa-plus mr5"></i>Thêm mới</a>
                </div>
            </div>
        </div>
    </div>
</form>
