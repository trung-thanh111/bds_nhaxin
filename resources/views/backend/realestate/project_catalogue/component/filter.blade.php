<form action="" method="">
    <div class="uk-flex uk-flex-middle uk-flex-space-between">
        <div class="perpage">
            @php
                $perpage = request('perpage') ?: old('perpage');
            @endphp
            <div class="uk-flex uk-flex-middle uk-flex-gap-10">
                <select name="perpage" class="form-control input-sm setupSelect2 ml10">
                    @for ($i = 20; $i <= 200; $i += 20)
                        <option {{ $perpage == $i ? 'selected' : '' }} value="{{ $i }}">{{ $i }}
                            bản ghi</option>
                    @endfor
                </select>
            </div>
        </div>
        <div class="action">
            <div class="uk-flex uk-flex-middle">
                @php
                    $publish = request('publish') ?: old('publish');
                @endphp
                <div class="mr15">
                    <select name="publish" class="form-control setupSelect2" style="width: 150px;">
                        @foreach (config('apps.general.publish') as $key => $val)
                            <option {{ $publish == $key ? 'selected' : '' }} value="{{ $key }}">{{ $val }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="uk-search uk-flex uk-flex-middle mr15" style="width: 300px;">
                    <div class="input-group">
                        <input type="text" name="keyword" value="{{ request('keyword') ?: old('keyword') }}"
                            placeholder="Nhập từ khóa tìm kiếm..." class="form-control" style="height: 34px;">
                        <span class="input-group-btn">
                            <button type="submit" name="search" value="search" class="btn btn-primary mb0 btn-sm" style="height: 34px;">Tìm
                                kiếm
                            </button>
                        </span>
                    </div>
                </div>
                <a href="{{ route('project.catalogue.create') }}" class="btn btn-danger btn-sm" style="height: 34px; line-height: 24px;"><i class="fa fa-plus mr5"></i>Thêm
                    mới</a>
            </div>
        </div>
    </div>
</form>
