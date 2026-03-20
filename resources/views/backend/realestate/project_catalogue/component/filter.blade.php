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
                            <option {{ $publish == $key ? 'selected' : '' }} value="{{ $key }}">
                                {{ $val }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @include('backend.dashboard.component.keyword')
                <a href="{{ route('project.catalogue.create') }}" class="btn btn-danger"><i
                        class="fa fa-plus mr5"></i>Thêm
                    mới</a>
            </div>
        </div>
    </div>
</form>
