<form action="{{ route('amenity.index') }}">
    <div class="filter-wrapper">
        <div class="uk-flex uk-flex-middle uk-flex-space-between">
            <div class="perpage">
                @php
                    $perpage = request('perpage') ?: old('perpage');
                @endphp
                <div class="uk-flex uk-flex-middle uk-flex-space-between">
                    <select name="perpage" class="form-control input-sm perpage filter setupSelect2">
                        @for($i = 20; $i<= 200; $i+=20)
                            <option {{ ($perpage == $i)  ? 'selected' : '' }}  value="{{ $i }}">{{ $i }} {{ __('messages.perpage') }}</option>
                        @endfor
                    </select>
                </div>
            </div>
            <div class="action">
                <div class="uk-flex uk-flex-middle">
                    @php
                        $publish = request('publish') ?: old('publish');
                        $amenity_catalogue_id = request('amenity_catalogue_id') ?: old('amenity_catalogue_id');
                    @endphp
                    <select name="amenity_catalogue_id" class="form-control setupSelect2 ml10">
                        @foreach($dropdown as $key => $val)
                            <option {{ ($amenity_catalogue_id == $key)  ? 'selected' : '' }} value="{{ $key }}">{{ $val }}</option>
                        @endforeach
                    </select>
                    <select name="publish" class="form-control setupSelect2 ml10">
                        @foreach(__('messages.publish') as $key => $val)
                            <option {{ ($publish == $key)  ? 'selected' : '' }} value="{{ $key }}">{{ $val }}</option>
                        @endforeach
                    </select>
                    <div class="uk-search uk-flex uk-flex-middle ml10">
                        <div class="input-group">
                            <input 
                                type="text" 
                                name="keyword" 
                                value="{{ request('keyword') ?: old('keyword') }}" 
                                placeholder="{{ __('messages.searchInput') }}" 
                                class="form-control"
                            >
                            <span class="input-group-btn">
                                <button type="submit" name="search" value="search" class="btn btn-primary mb0 btn-sm">{{ __('messages.search') }}
                                </button>
                            </span>
                        </div>
                    </div>
                    <a href="{{ route('amenity.create') }}" class="btn btn-danger ml10"><i class="fa fa-plus mr5"></i>{{ __('messages.amenity.create.title') }}</a>
                </div>
            </div>
        </div>
    </div>
</form>
