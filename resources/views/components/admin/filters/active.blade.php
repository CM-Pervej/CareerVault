@props([
    'filters' => [],
    'clearUrl' => null,
])

@php
    $visibleFilters = collect($filters)->filter(
        fn ($filter) => !empty($filter['show'])
    );

    $hasFilters = $visibleFilters->isNotEmpty();
@endphp

@if($hasFilters)
    <div class="flex flex-wrap items-center gap-2 px-1">
        <span class="text-xs font-semibold text-base-content/50">Active filters:</span>

        @foreach($visibleFilters as $filter)
            <span class="badge badge-sm badge-outline gap-1">
                @if(!empty($filter['icon']))
                    <i class="{{ $filter['icon'] }}"></i>
                @endif

                @if(!empty($filter['label']))
                    {{ $filter['label'] }}:
                @endif

                {{ $filter['value'] }}
            </span>
        @endforeach
    </div>
@endif