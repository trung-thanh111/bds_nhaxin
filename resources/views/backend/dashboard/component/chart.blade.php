<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>Biểu đồ liên hệ xem nhà năm {{ date('Y') }}</h5>
                <div class="pull-right">
                    <div class="btn-group">
                        <button type="button" class="btn btn-xs btn-white chartButton active" data-chart="1">Biểu đồ năm</button>
                    </div>
                </div>
            </div>
            <div class="ibox-content">
                <div class="row">
                    <div class="col-lg-9">
                        <div class="chartContainer">
                            <canvas id="barChart" height="100"></canvas>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <ul class="stat-list">
                            <li>
                                <h2 class="no-margins">{{ $stats['contactRequestCount'] }}</h2>
                                <small>Tổng số lượt liên hệ</small>
                                <div class="progress progress-mini">
                                    <div style="width: 100%;" class="progress-bar"></div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>


@php
$data = json_encode($stats['crChart']['data']);
$label = json_encode($stats['crChart']['label']);
@endphp

<script>
    var data = JSON.parse('{!! $data !!}')
    var label = JSON.parse('{!! $label !!}')
</script>