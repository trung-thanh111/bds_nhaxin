<div class="ibox">
    <div class="ibox-title">
        <h5>Danh sách khách hàng liên hệ mới</h5>
    </div>
    <div class="ibox-content">
        <table class="table table-striped table-bordered order-table">
            <thead>
                <tr>
                    <th>Khách hàng</th>
                    <th>Dự án</th>
                    <th class="text-center">Trạng thái</th>
                </tr>
            </thead>
            <tbody>
                @if(isset($recentContactRequests) && is_object($recentContactRequests))
                @foreach($recentContactRequests as $cr)
                <tr>
                    <td>
                        <b>{{ $cr->full_name }}</b><br>
                        <small>{{ $cr->phone }}</small>
                    </td>
                    <td>
                        {{ $cr->projects?->languages->first()->pivot->name ?? ($cr->projects?->name ?? 'N/A') }}
                    </td>
                    <td class="text-center">
                        @switch($cr->status)
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
                        {{ $cr->status }}
                        @endswitch
                    </td>
                </tr>
                @endforeach
                @else
                <tr>
                    <td colspan="3" class="text-center">Không có dữ liệu liên hệ mới</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>