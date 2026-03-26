@if ($agent)
    <div class="gl-agent-card">
        <div class="agent-header-bg"></div>
        <div class="agent-avatar">
            <img src="{{ image($agent->avatar) }}" alt="{{ $agent->name }}">
        </div>
        <div class="agent-info">
            <h4 class="agent-name">{{ $agent->full_name }}</h4>
        </div>
        <div class="agent-actions">
            @php
                $cleanPhone = preg_replace('/\D/', '', $agent->phone);
            @endphp
            <a href="tel:{{ $cleanPhone }}" class="btn-agent-top btn-phone-call">
                <i class="fa fa-phone"></i> {{ $agent->phone }}
            </a>
            <a href="https://zalo.me/{{ $cleanPhone }}" target="_blank" class="btn-agent-top btn-zalo-chat">
                <i class="fa fa-comments"></i> Nhắn Zalo
            </a>
            <a href="{{ route('contact.index') }}" class="btn-consult-request">
                <i class="fa fa-user-check"></i> Yêu cầu tư vấn
            </a>
        </div>
    </div>
@endif
