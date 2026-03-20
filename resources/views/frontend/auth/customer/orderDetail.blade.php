@extends('frontend.homepage.layout')

@section('content')
    <div class="profile-wrapper cat-bg">
        <div class="uk-container uk-container-center">

            {{-- HEADER PROFILE --}}
            @include('frontend.auth.customer.components.header')

            <div class="uk-grid uk-grid-medium mt30">

                {{-- SIDEBAR --}}
                <div class="uk-width-large-1-4">
                    @include('frontend.auth.customer.components.sidebar')
                </div>

                {{-- MAIN CONTENT --}}
                <div class="uk-width-large-3-4">
                    <div class="panel-profile">
                        <div class="panel-head">
                            <h2 class="heading-2"><span>Chi tiết đơn hàng #{{ $order->code }}</span></h2>
                            <div class="description">
                                Thông tin chi tiết về đơn hàng của bạn
                            </div>
                        </div>

                        <div class="panel-body">
                            @if (session('success'))
                                <div class="uk-alert uk-alert-success" data-uk-alert>
                                    <a href="" class="uk-alert-close uk-close"></a>
                                    <p>{{ session('success') }}</p>
                                </div>
                            @endif

                            @if (session('error'))
                                <div class="uk-alert uk-alert-danger" data-uk-alert>
                                    <a href="" class="uk-alert-close uk-close"></a>
                                    <p>{{ session('error') }}</p>
                                </div>
                            @endif

                            <div class="order-detail-wrapper">
                                {{-- Thông tin đơn hàng --}}
                                <div class="uk-panel uk-margin-bottom detail-panel">
                                    <h3 class="uk-panel-title uk-text-bold uk-margin-bottom detail-panel-title">
                                        Thông tin đơn hàng</h3>
                                    <div class="uk-grid uk-grid-small detail-panel-content">
                                        <div class="uk-width-medium-1-2">
                                            <div class="uk-margin-small detail-row">
                                                <span class="uk-text-bold uk-display-inline-block detail-label">Mã đơn
                                                    hàng:</span>
                                                <span
                                                    class="uk-text-primary detail-value"><strong>#{{ $order->code }}</strong></span>
                                            </div>
                                            <div class="uk-margin-small detail-row">
                                                <span class="uk-text-bold uk-display-inline-block detail-label">Ngày
                                                    đặt:</span>
                                                <span
                                                    class="detail-value">{{ date('d/m/Y H:i', strtotime($order->created_at)) }}</span>
                                            </div>
                                            <div class="uk-margin-small detail-row">
                                                <span class="uk-text-bold uk-display-inline-block detail-label">Cập
                                                    nhật:</span>
                                                <span
                                                    class="detail-value">{{ date('d/m/Y H:i', strtotime($order->updated_at)) }}</span>
                                            </div>
                                        </div>
                                        <div class="uk-width-medium-1-2">
                                            <div class="uk-margin-small detail-row">
                                                <span class="uk-text-bold uk-display-inline-block detail-label">Trạng
                                                    thái:</span>
                                                <span
                                                    class="uk-badge uk-badge-{{ $order->confirm == 'confirm' ? 'success' : ($order->confirm == 'cancle' ? 'danger' : 'warning') }} detail-badge">
                                                    {{ __('order.confirm')[$order->confirm] ?? $order->confirm }}
                                                </span>
                                            </div>
                                            <div class="uk-margin-small detail-row">
                                                <span class="uk-text-bold uk-display-inline-block detail-label">Thanh
                                                    toán:</span>
                                                <span
                                                    class="uk-badge uk-badge-{{ $order->payment == 'paid' ? 'success' : 'warning' }} detail-badge">
                                                    {{ $order->payment == 'paid' ? 'Đã thanh toán' : 'Chưa thanh toán' }}
                                                </span>
                                            </div>
                                            <div class="uk-margin-small detail-row">
                                                <span class="uk-text-bold uk-display-inline-block detail-label">Giao
                                                    hàng:</span>
                                                <span
                                                    class="uk-badge uk-badge-{{ $order->delivery == 'success' ? 'success' : ($order->delivery == 'processing' ? 'primary' : 'warning') }} detail-badge">
                                                    {{ $order->delivery == 'pending'
                                                        ? 'Chờ giao hàng'
                                                        : ($order->delivery == 'processing'
                                                            ? 'Đang giao hàng'
                                                            : ($order->delivery == 'success'
                                                                ? 'Đã giao hàng'
                                                                : '-')) }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Thông tin người nhận --}}
                                <div class="uk-panel uk-margin-bottom detail-panel">
                                    <h3 class="uk-panel-title uk-text-bold uk-margin-bottom detail-panel-title">
                                        Thông tin người nhận</h3>
                                    <div class="uk-grid uk-grid-small detail-panel-content">
                                        <div class="uk-width-medium-1-2">
                                            <div class="uk-margin-small detail-row">
                                                <span class="uk-text-bold uk-display-inline-block detail-label">Họ
                                                    tên:</span>
                                                <span class="detail-value">{{ $order->fullname }}</span>
                                            </div>
                                            <div class="uk-margin-small detail-row">
                                                <span class="uk-text-bold uk-display-inline-block detail-label">Số điện
                                                    thoại:</span>
                                                <span class="detail-value">{{ $order->phone }}</span>
                                            </div>
                                            @if ($order->email)
                                                <div class="uk-margin-small detail-row">
                                                    <span
                                                        class="uk-text-bold uk-display-inline-block detail-label">Email:</span>
                                                    <span class="detail-value">{{ $order->email }}</span>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="uk-width-medium-1-2">
                                            <div class="uk-margin-small detail-row">
                                                <span class="uk-text-bold uk-display-inline-block detail-label">Địa
                                                    chỉ:</span>
                                                <span class="detail-value">{{ $order->address }}</span>
                                            </div>
                                            @if ($order->province_name || $order->district_name || $order->ward_name)
                                                <div class="uk-margin-small detail-row">
                                                    <span class="uk-text-bold uk-display-inline-block detail-label">Tỉnh/TP
                                                        - Quận/Huyện -
                                                        Phường/Xã:</span>
                                                    <span class="detail-value">
                                                        {{ $order->ward_name ?? '' }}{{ $order->ward_name && ($order->district_name || $order->province_name) ? ', ' : '' }}
                                                        {{ $order->district_name ?? '' }}{{ $order->district_name && $order->province_name ? ', ' : '' }}
                                                        {{ $order->province_name ?? '' }}
                                                    </span>
                                                </div>
                                            @endif
                                            @if ($order->description)
                                                <div class="uk-margin-small detail-row">
                                                    <span class="uk-text-bold uk-display-inline-block detail-label">Ghi
                                                        chú:</span>
                                                    <span class="detail-value">{{ $order->description }}</span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                {{-- Phương thức thanh toán --}}
                                <div class="uk-panel uk-margin-bottom detail-panel">
                                    <h3 class="uk-panel-title uk-text-bold uk-margin-bottom uk-margin-2 detail-panel-title">
                                        Phương thức thanh toán</h3>
                                    <div class="uk-flex uk-flex-middle detail-panel-content">
                                        @php
                                            $paymentMethods = __('payment.method');
                                            $methodInfo = collect($paymentMethods)->firstWhere('name', $order->method);
                                        @endphp
                                        @if ($methodInfo && isset($methodInfo['image']))
                                            <img src="{{ asset($methodInfo['image']) }}" alt="{{ $methodInfo['title'] }}"
                                                class="payment-method-img">
                                            <span class="detail-value">{{ $methodInfo['title'] }}</span>
                                        @else
                                            <span class="detail-value">{{ $order->method }}</span>
                                        @endif
                                    </div>
                                </div>

                                {{-- Danh sách sản phẩm --}}
                                <div class="uk-panel uk-margin-bottom detail-panel">
                                    <h3 class="uk-panel-title uk-text-bold uk-margin-bottom detail-panel-title">
                                        Sản phẩm đã mua</h3>
                                    <div class="uk-overflow-container detail-panel-content">
                                        <table class="uk-table uk-table-striped uk-table-hover">
                                            <thead>
                                                <tr>
                                                    <th class="uk-text-center">STT</th>
                                                    <th>Sản phẩm</th>
                                                    <th class="uk-text-center">Số lượng</th>
                                                    <th class="uk-text-right">Đơn giá</th>
                                                    <th class="uk-text-right">Thành tiền</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($order->order_products as $index => $item)
                                                    @php
                                                        $option = null;
                                                        if ($item->option) {
                                                            if (is_string($item->option)) {
                                                                $option = json_decode($item->option, true);
                                                            } elseif (is_array($item->option)) {
                                                                $option = $item->option;
                                                            }
                                                        }
                                                        $image = $option['image'] ?? null;
                                                    @endphp
                                                    <tr>
                                                        <td class="uk-text-center table-cell">
                                                            {{ $index + 1 }}</td>
                                                        <td>
                                                            <div class="uk-flex uk-flex-middle">
                                                                @if ($image)
                                                                    <img src="{{ asset($image) }}"
                                                                        alt="{{ $item->name }}" class="product-img">
                                                                @endif
                                                                <div>
                                                                    <div class="uk-text-bold product-name">
                                                                        {{ $item->name ?? 'N/A' }}</div>
                                                                    @if ($item->uuid)
                                                                        <div
                                                                            class="uk-text-small uk-text-muted product-code">
                                                                            Mã:
                                                                            {{ $item->uuid }}</div>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td class="uk-text-center table-cell">
                                                            {{ $item->qty ?? 0 }}</td>
                                                        <td class="uk-text-right">
                                                            <span
                                                                class="uk-text-bold uk-text-primary table-cell">{{ convert_price($item->price ?? 0, true) }}₫</span>
                                                        </td>
                                                        <td class="uk-text-right">
                                                            <strong
                                                                class="uk-text-bold uk-text-primary table-cell">{{ convert_price(($item->price ?? 0) * ($item->qty ?? 0), true) }}₫</strong>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                {{-- Tổng tiền --}}
                                <div class="uk-panel uk-margin-bottom total-panel">
                                    @php
                                        $cart = is_array($order->cart) ? $order->cart : [];
                                        $promotion = is_array($order->promotion) ? $order->promotion : [];
                                        $cartTotal = $cart['cartTotal'] ?? 0;
                                        $promotionDiscount = $promotion['discount'] ?? 0;
                                        $shipping = $order->shipping ?? 0;
                                        $subTotal = $cartTotal - $promotionDiscount;
                                        $finalTotal = $subTotal + $shipping;
                                    @endphp

                                    <div class="uk-flex uk-flex-middle uk-flex-space-between total-header">

                                        <h3 class="uk-panel-title uk-text-bold total-title">
                                            Tổng tiền
                                        </h3>

                                        {{-- GIÁ TỔNG CỘNG ĐƯA LÊN NGANG --}}
                                        <span class="uk-text-bold total-amount">
                                            {{ convert_price($finalTotal, true) }}₫
                                        </span>
                                    </div>

                                    <div class="uk-grid uk-grid-small total-content">
                                        <div class="uk-width-1-1">

                                            @if ($promotionDiscount > 0)
                                                <div class="uk-margin-small total-row">
                                                    <div class="uk-flex uk-flex-space-between">
                                                        <span class="uk-text-bold detail-value">Giảm giá:</span>
                                                        <span class="uk-text-bold uk-text-danger detail-value">
                                                            -{{ convert_price($promotionDiscount, true) }}₫
                                                        </span>
                                                    </div>
                                                </div>
                                            @endif

                                            @if ($shipping > 0)
                                                <div class="uk-margin-small total-row">
                                                    <div class="uk-flex uk-flex-space-between">
                                                        <span class="uk-text-bold detail-value">Phí vận chuyển:</span>
                                                        <span class="uk-text-bold detail-value">
                                                            {{ convert_price($shipping, true) }}₫
                                                        </span>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>


                                {{-- Điểm tích lũy --}}
                                @if ($order->point_added || $order->point_used)
                                    <div class="uk-panel uk-margin-bottom detail-panel">
                                        <h3 class="uk-panel-title uk-text-bold uk-margin-bottom detail-panel-title">
                                            Điểm tích lũy</h3>
                                        <div class="uk-grid uk-grid-small detail-panel-content">
                                            <div class="uk-width-1-1">
                                                @if ($order->point_used > 0)
                                                    <div class="uk-margin-small detail-row">
                                                        <span class="uk-text-bold uk-display-inline-block point-label">Điểm
                                                            đã sử dụng:</span>
                                                        <span
                                                            class="uk-text-danger detail-value">-{{ number_format($order->point_used) }}
                                                            điểm</span>
                                                    </div>
                                                @endif
                                                @if ($order->point_added && $order->point_value > 0)
                                                    <div class="uk-margin-small detail-row">
                                                        <span class="uk-text-bold uk-display-inline-block point-label">Điểm
                                                            được cộng:</span>
                                                        <span
                                                            class="uk-text-success detail-value">+{{ number_format($order->point_value) }}
                                                            điểm</span>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                {{-- Actions --}}
                                <div class="uk-margin-top uk-text-right">
                                    <a href="{{ route('customer.order') }}" class="uk-button uk-button-large">
                                        <i class="uk-icon-arrow-left"></i> Quay lại danh sách
                                    </a>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        document.querySelector('.logout-btn')?.addEventListener('click', function() {
            if (confirm('Bạn có chắc chắn muốn đăng xuất không?')) {
                window.location.href = "{{ route('customer.logout') }}";
            }
        });
    </script>
@endsection
