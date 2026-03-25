<table class="table table-striped table-bordered text-center">
    <thead>
        <tr>
            <th class="text-center" style="width: 50px;">
                <input type="checkbox" value="" id="checkAll" class="input-checkbox">
            </th>
            <th class="text-left">Thông tin Tin đăng</th>
            <th style="width: 100px;">Hiển thị</th>
            <th style="width: 100px;">Thao tác</th>
        </tr>
    </thead>
    <tbody>
        @if (isset($projects) && is_object($projects))
            @foreach ($projects as $project)
                <tr>
                    <td class="text-center">
                        <input type="checkbox" value="{{ $project->id }}" class="input-checkbox checkBoxItem">
                    </td>
                    <td class="text-left">
                        <div class="uk-flex uk-flex-middle" style="gap: 15px;">
                            <span class="image img-cover" style="width: 80px; height: auto;"><img
                                    src="{{ $project->cover_image ? asset($project->cover_image) : asset('backend/img/not-found.jpg') }}"
                                    alt="" style="width: 100%; height: auto; object-fit: cover;"></span>
                            <div class="info">
                                <div class="name"><strong>{{ $project->name }}</strong></div>
                                <div class="canonical text-navy small">
                                    Nhóm: {{ $project->catalogue_name ?? 'N/A' }}
                                </div>
                            </div>
                        </div>
                    </td>
                    <td class="text-center js-switch-{{ $project->id }}">
                        <input type="checkbox" value="{{ $project->publish }}" class="js-switch status "
                            data-field="publish" data-model="Project" data-modelId="{{ $project->id }}"
                            {{ $project->publish == 2 ? 'checked' : '' }} />
                    </td>
                    <td class="text-center">
                        <a href="{{ route('project.edit', $project->id) }}" class="btn btn-success"><i
                                class="fa fa-edit"></i></a>
                        <a href="{{ route('project.delete', $project->id) }}" class="btn btn-danger"><i
                                class="fa fa-trash"></i></a>
                    </td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>
<div class="text-center">
    {{ $projects->links('pagination::bootstrap-4') }}
</div>
