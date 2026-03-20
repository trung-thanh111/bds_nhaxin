@include('backend.dashboard.component.breadcrumb', ['title' => $config['seo'][$config['method']]['title']])
@if ($errors->any())
<div class="alert alert-danger">
    <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif
@php
$url = ($config['method'] == 'create') ? route('contact_request.store') : route('contact_request.update', $record->id);
@endphp
<form action="{{ $url }}" method="post" class="box">
    @csrf
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-8">
                <div class="ibox">
                    <div class="ibox-title">
                        <h5>Thông tin khách hàng & Yêu cầu</h5>
                    </div>
                    <div class="ibox-content">
                        <div class="row mb15">
                            <div class="col-lg-12">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">Dự án quan tâm <span class="text-danger">(*)</span></label>
                                    <select name="project_id" class="form-control setupSelect2">
                                        <option value="">[Chọn Dự án]</option>
                                        @foreach($projects as $project)
                                        <option {{ $project->id == old('project_id', (isset($record->project_id)) ? $record->project_id : '') ? 'selected' : '' }} value="{{ $project->id }}">
                                            {{ $project->languages->first()->pivot->name ?? ($project->name ?? 'N/A') }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row mb15">
                            <div class="col-lg-12">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">Họ và tên khách hàng <span class="text-danger">(*)</span></label>
                                    <input
                                        type="text"
                                        name="full_name"
                                        value="{{ old('full_name', ($record->full_name) ?? '' ) }}"
                                        class="form-control"
                                        autocomplete="off">
                                </div>
                            </div>
                        </div>
                        <div class="row mb15">
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">Email <span class="text-danger">(*)</span></label>
                                    <input
                                        type="email"
                                        name="email"
                                        value="{{ old('email', ($record->email) ?? '' ) }}"
                                        class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">Số điện thoại <span class="text-danger">(*)</span></label>
                                    <input
                                        type="text"
                                        name="phone"
                                        value="{{ old('phone', ($record->phone) ?? '' ) }}"
                                        class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="row mb15">
                            <div class="col-lg-12">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">Tiêu đề</label>
                                    <input
                                        type="text"
                                        name="subject"
                                        value="{{ old('subject', ($record->subject) ?? '' ) }}"
                                        class="form-control"
                                        autocomplete="off">
                                </div>
                            </div>
                        </div>
                        <div class="row mb15">
                            <div class="col-lg-12">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">Nội dung yêu cầu</label>
                                    <textarea name="content" class="form-control" rows="6">{{ old('content', $record->content ?? '') }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="ibox">
                    <div class="ibox-title">
                        <h5>Xử lý & Phân công</h5>
                    </div>
                    <div class="ibox-content">
                        <div class="row mb15">
                            <div class="col-lg-12">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">Trạng thái xử lý</label>
                                    <select name="status" class="form-control setupSelect2">
                                        <option {{ old('status', $record->status ?? '') == 'pending' ? 'selected' : '' }} value="pending">Chờ xử lý (Pending)</option>
                                        <option {{ old('status', $record->status ?? '') == 'confirmed' ? 'selected' : '' }} value="confirmed">Đã xác nhận (Confirmed)</option>
                                        <option {{ old('status', $record->status ?? '') == 'completed' ? 'selected' : '' }} value="completed">Đã hoàn thành (Completed)</option>
                                        <option {{ old('status', $record->status ?? '') == 'cancelled' ? 'selected' : '' }} value="cancelled">Đã hủy (Cancelled)</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row mb15">
                            <div class="col-lg-12">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">Nhân viên phụ trách</label>
                                    <select name="assigned_agent_id" class="form-control setupSelect2">
                                        <option value="">[Chọn Nhân viên]</option>
                                        @foreach($agents as $agent)
                                        <option {{ $agent->id == old('assigned_agent_id', (isset($record->assigned_agent_id)) ? $record->assigned_agent_id : '') ? 'selected' : '' }} value="{{ $agent->id }}">{{ $agent->full_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row mb15">
                            <div class="col-lg-12">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">Ghi chú nội bộ (Admin)</label>
                                    <textarea name="admin_notes" class="form-control" rows="5">{{ old('admin_notes', $record->admin_notes ?? '') }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="text-right mb15">
            <button class="btn btn-primary" type="submit" name="send" value="send">Lưu lại</button>
        </div>
    </div>
</form>