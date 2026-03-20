@php
$sort = request('sort') ?: old('sort');
@endphp
<select name="sort" class="form-control setupSelect2 ml10">
    <option value="">[Sắp xếp]</option>
    @foreach(__('messages.sort') as $key => $val)
    <option {{ ($sort == $key)  ? 'selected' : '' }} value="{{ $key }}">{{ $val }}</option>
    @endforeach
</select>