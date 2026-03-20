<div class="row">
    <div class="col-lg-3">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <span class="label label-success pull-right">Tháng</span>
                <h5>Liên hệ trong tháng</h5>
            </div>
            <a href="{{ route('contact_request.index') }}">
                <div class="ibox-content">
                    <h1 class="no-margins">{{ $stats['currentMonthCR'] }}</h1>
                    <div class="stat-percent font-bold text-success">{{ $stats['growth'] }}% <i
                            class="fa fa-level-up"></i>
                    </div>
                    <small>Tăng trưởng so với tháng trước</small>
                </div>
            </a>
        </div>
    </div>
    <div class="col-lg-3">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <span class="label label-info pull-right">Tổng số</span>
                <h5>Nhân viên môi giới</h5>
            </div>
            <a href="{{ route('agent.index') }}">
                <div class="ibox-content">
                    <h1 class="no-margins">{{ $stats['agentCount'] }}</h1>
                    <small>Nhân viên đang quản lý</small>
                </div>
            </a>
        </div>
    </div>
    <div class="col-lg-3">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <span class="label label-primary pull-right">Tổng số</span>
                <h5>Bất động sản</h5>
            </div>
            <a href="{{ route('real.estate.index') }}">
                <div class="ibox-content">
                    <h1 class="no-margins">{{ $stats['realEstateCount'] }}</h1>
                    <small>Tổng số BĐS trên hệ thống</small>
                </div>
            </a>
        </div>
    </div>
    <div class="col-lg-3">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <span class="label label-danger pull-right">Tổng số</span>
                <h5>Dự án</h5>
            </div>
            <a href="{{ route('project.index') }}">
                <div class="ibox-content">
                    <h1 class="no-margins">{{ $stats['projectCount'] }}</h1>
                    <small>Tổng số dự án / tin đăng</small>
                </div>
            </a>
        </div>
    </div>
</div>
