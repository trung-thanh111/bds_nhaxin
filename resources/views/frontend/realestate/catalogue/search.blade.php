@extends('frontend.homepage.layout')

<style>
    .search-tabs-control {
        display: inline-flex;
        background: #f1f5f9;
        border-radius: 50px;
        padding: 6px;
        box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.05);
    }

    .search-tab {
        padding: 6px 25px;
        border-radius: 50px;
        font-weight: 600;
        font-size: 15px;
        color: #64748b;
        text-decoration: none !important;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .search-tab.active {
        background: var(--main-color);
        color: #ffffff;
        box-shadow: 0 2px 6px rgba(3, 72, 51, 0.3);
    }

    .search-tab:not(.active):hover {
        background: rgba(0, 0, 0, 0.05);
        color: #334155;
    }
</style>

@php
    $realEstates = $realEstates ?? new \Illuminate\Pagination\LengthAwarePaginator([], 0, 8);
    $projects = $projects ?? new \Illuminate\Pagination\LengthAwarePaginator([], 0, 8);
    $isProject = $isProject ?? false;
@endphp

@section('header-class', 'header-inner')
@section('content')
    <div id="scroll-progress"></div>
    <section class="hp-detail-header">
        <div class="uk-container uk-container-center">
            <ul class="uk-breadcrumb uk-flex uk-flex-middle">
                <li><a href="{{ url('/') }}">Trang chủ</a></li>
                <li class="uk-active"><span>Kết quả tìm kiếm</span></li>
            </ul>
        </div>
    </section>

    <div class="hp-full-promo-section uk-margin-bottom">
        <div class="uk-container uk-container-center">
            <div class="hp-promo-inner">
                <h2 class="hp-promo-title">TÌM KIẾM THEO NHU CẦU CỦA BẠN</h2>
                <p class="hp-promo-desc">
                    Kết quả tìm kiếm cho: <strong>{{ request('keyword') ?: 'Tất cả' }}</strong>
                    @if (request('province_name') || request('old_province_name'))
                        tại <strong>{{ request('province_name') ?: request('old_province_name') }}</strong>
                    @endif
                </p>
                <div class="hp-promo-actions uk-flex uk-flex-center">
                    <div class="search-tabs-control">
                        <a href="{{ route('search.index', array_merge(request()->all(), ['type' => 'real_estate'])) }}"
                            class="search-tab {{ !$isProject ? 'active' : '' }}">
                            Bất động sản
                        </a>
                        <a href="{{ route('search.index', array_merge(request()->all(), ['type' => 'project'])) }}"
                            class="search-tab {{ $isProject ? 'active' : '' }}">
                            Dự án
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="uk-container uk-container-center">
        <div data-uk-sticky="{offset: 85, media: 960}">
            @if ($isProject)
                @include('frontend.component.filter_horizontal_project')
            @else
                @include('frontend.component.filter_horizontal')
            @endif
        </div>

        <section class="hp-section bg-white">
            <div class="uk-container uk-container-center">
                <div class="uk-grid uk-grid-medium mt20" data-uk-grid-margin id="main-listing-grid">
                    <div class="uk-width-large-7-10">
                        <div class="hp-listing-top uk-flex uk-flex-middle uk-flex-space-between uk-margin-large-bottom">
                            <div class="hp-listing-title">
                                <h1 class="hp-category-name">
                                    {{ $isProject ?? 'Kết quả tìm kiếm' }}
                                </h1>
                                <div class="hp-listing-count">
                                    <i class="fas fa-search uk-margin-small-right"></i>
                                    Tìm thấy
                                    <strong>{{ number_format($isProject ? $projects->total() : $realEstates->total(), 0, ',', '.') }}</strong>
                                    kết quả
                                </div>
                            </div>

                            <div class="hp-listing-sort uk-flex uk-flex-middle">
                                <div class="hp-sort-dropdown" data-uk-dropdown="{mode:'click', pos:'bottom-right'}">
                                    <button class="hp-sort-btn uk-flex uk-flex-middle">
                                        <span id="sort-label">{{ $sorts[$currentSort] ?? 'Mặc định' }}</span>
                                        <i class="fas fa-chevron-down uk-margin-small-left"></i>
                                    </button>
                                    <div class="uk-dropdown uk-dropdown-small">
                                        <ul class="hp-sort-list">
                                            @foreach ($sorts as $key => $label)
                                                <li class="{{ $currentSort == $key ? 'uk-active' : '' }}">
                                                    <a href="#" class="ajax-sort"
                                                        data-sort="{{ $key }}">{{ $label }}</a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="ajax-listing-container">
                            @if ($isProject)
                                @include('frontend.component.project_list', ['projects' => $projects])
                            @else
                                @include('frontend.realestate.catalogue.listing_results', [
                                    'realEstates' => $realEstates,
                                    'attributeMap' => $attributeMap,
                                ])
                            @endif
                        </div>
                    </div>

                    <!-- Sidebar -->
                    <div class="uk-width-large-3-10">
                        <aside class="hp-sidebar hp-sidebar-sticky">
                            @include('frontend.component.sidebar_filters')
                            @if (isset($widgets['featured-projects']))
                                <div class="hp-sidebar-widget">
                                    <h4 class="hp-sidebar-title">Dự án tiêu biểu</h4>
                                    <div class="hp-sidebar-projects">
                                        @foreach ($widgets['featured-projects']->items as $p)
                                            <a href="{{ url($p->canonical . '.html') }}" class="hp-mini-item uk-flex">
                                                <div class="img">
                                                    <img src="{{ image($p->image) }}" alt="{{ $p->name }}">
                                                </div>
                                                <div class="info">
                                                    <h5 class="title">{{ $p->name }}</h5>
                                                    <div class="meta">{{ $p->area }} m²</div>
                                                </div>
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </aside>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
