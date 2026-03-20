@include('backend.dashboard.component.breadcrumb', ['title' => $config['seo'][$config['method']]['title']])
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>{{ $config['seo'][$config['method']]['table'] }} </h5>
                </div>
                <div class="ibox-content">
                    @include('backend.realestate.project.component.filter')
                    @include('backend.realestate.project.component.table')
                </div>
            </div>
        </div>
    </div>
</div>
