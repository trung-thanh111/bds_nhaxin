<div class="ibox">
    <div class="ibox-title">
        <h5>Danh sách khách hàng liên hệ mới</h5>
    </div>
    <div class="ibox-content">
        <table class="table table-striped table-bordered order-table">
            <thead>
                <tr>
                    <th>Khách hàng</th>
                    <th>Bất động sản</th>
                    <th class="text-center">Ngày hẹn</th>
                    <th class="text-center">Trạng thái</th>
                </tr>
            </thead>
            <tbody>
                @if(isset($recentVisitRequests) && is_object($recentVisitRequests))
                @foreach($recentVisitRequests as $vr)
                <tr>
                    <td>
                        <b>{{ $vr->full_name }}</b><br>
                        <small>{{ $vr->phone }}</small>
                    </td>
                    <td>
                        {{ $vr->properties?->title ?? 'N/A' }}
                    </td>
                    <td class="text-center">
                        {{ $vr->preferred_date }}
                    </td>
                    <td class="text-center">
                        @switch($vr->status)
                        @case('pending')
                        <span class="label label-warning">Pending</span>
                        @break
                        @case('confirmed')
                        <span class="label label-info">Confirmed</span>
                        @break
                        @case('completed')
                        <span class="label label-primary">Completed</span>
                        @break
                        @case('cancelled')
                        <span class="label label-default">Cancelled</span>
                        @break
                        @default
                        {{ $vr->status }}
                        @endswitch
                    </td>
                </tr>
                @endforeach
                @else
                <tr>
                    <td colspan="4" class="text-center">Không có dữ liệu contact mới</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>