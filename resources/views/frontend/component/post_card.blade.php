<div class="gl-post-card">
    <a href="{{ url($post->canonical . '.html') }}" class="gl-post-img-wrapper">
        <img src="{{ asset($post->image) }}" alt="{{ $post->name }}">
    </a>
    <div class="gl-post-body">
        @if ($post->post_catalogues->first())
            <span class="gl-post-cat-tag">
                {{ $post->post_catalogues->first()->languages->first()->pivot->name ?? '' }}
            </span>
        @endif
        <h3 class="gl-post-title">
            <a href="{{ url($post->canonical . '.html') }}">{{ $post->name }}</a>
        </h3>
        <div class="gl-post-desc">
            {!! Str::limit(strip_tags($post->languages->first()->pivot->description ?? ''), 240) !!}
        </div>
        <div class="gl-post-footer">
            <span class="gl-post-time">
                <i class="fa fa-clock-o"></i>
                {{ diff_for_humans($post->released_at ?? $post->created_at) }}
            </span>
        </div>
    </div>
</div>
