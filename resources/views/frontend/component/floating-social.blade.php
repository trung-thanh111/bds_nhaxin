<div class="hp-floating-social">
    @if (!empty($system['social_messenger']))
        <a href="{{ $system['social_messenger'] }}" target="_blank" class="hp-float-item hp-float-messenger"
            title="Messenger">
            <i class="fa fa-commenting"></i>
        </a>
    @elseif(!empty($system['social_facebook']))
        <a href="{{ $system['social_facebook'] }}" target="_blank" class="hp-float-item hp-float-messenger"
            title="Messenger">
            <i class="fa fa-commenting"></i>
        </a>
    @endif

    @php
        $hotline = get_hotline($agent ?? null, $system['contact_hotline'] ?? '');
        $hotlineLink = get_hotline_link($agent ?? null, $system['contact_hotline'] ?? '');
        $zaloHotline = $hotlineLink ?: $system['social_zalo'] ?? '';
    @endphp

    @if (!empty($zaloHotline))
        <a href="https://zalo.me/{{ preg_replace('/\D/', '', $zaloHotline) }}" target="_blank"
            class="hp-float-item hp-float-zalo" title="Zalo">
            <img src="{{ asset('frontend/resources/img/icon_zalo.png') }}" alt="Zalo">
        </a>
    @endif

    @if (!empty($hotlineLink))
        <a href="tel:{{ $hotlineLink }}" target="_blank" class="hp-float-item hp-float-hotline"
            title="hotline: {{ $hotline }}">
            <img src="{{ asset('frontend/resources/img/icon_call.png') }}" alt="hotline">
        </a>
    @endif

    <div class="hp-float-item hp-back-to-top" id="hp-back-to-top" title="Lên đầu trang">
        <i class="fa fa-angle-up"></i>
    </div>
</div>
