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

    @if (!empty($system['social_zalo']))
        <a href="{{ $system['social_zalo'] }}" target="_blank" class="hp-float-item hp-float-zalo" title="Zalo">
            <img src="{{ asset('frontend/resources/img/icon_zalo.png') }}" alt="Zalo" style="width: 25px; filter: brightness(0) invert(1);">
        </a>
    @endif

    @if (!empty($system['contact_hotline']))
        <a href="tel:{{ $system['contact_hotline'] }}" target="_blank" class="hp-float-item hp-float-hotline" title="hotline">
            <img src="{{ asset('frontend/resources/img/icon_call.png') }}" alt="hotline" style="width: 25px; filter: brightness(0) invert(1);">
        </a>
    @endif

    <div class="hp-float-item hp-back-to-top" id="hp-back-to-top" title="Lên đầu trang">
        <i class="fa fa-angle-up"></i>
    </div>
</div>
