@props([
    'action',
    'title' => 'Filters',
    'description' => null,
    'section' => 'filters',
    'fields' => [],
    'activeFilters' => [],
    'count' => 0,
    'total' => 0,
    'resultLabel' => 'result',
    'resultLabelPlural' => null,
    'summary' => null,
    'clearUrl' => null,
    'gridClass' => null,
])

@php
    $resultLabelPlural = $resultLabelPlural ?: $resultLabel . 's';

    $hasFilters = collect($activeFilters)->contains(
        fn ($filter) => !empty($filter['show'])
    );

    $clearUrl = $clearUrl ?: $action;
@endphp

{{-- ========================= FILTERS ========================= --}}
<details class="cv-section overflow-hidden sm:rounded-sm border border-base-300 bg-base-100 shadow-sm" data-section="{{ $section }}" open>
    <summary class="flex items-center justify-between gap-3 p-4 hover:bg-base-200/40">
        <span class="flex min-w-0 items-center gap-2.5">
            <span class="grid size-8 shrink-0 place-items-center rounded-lg bg-primary/10 text-primary">
                <i class="fa-solid fa-sliders text-xs"></i>
            </span>

            <span class="min-w-0">
                <span class="block text-sm font-semibold"> {{ $title }} </span>

                @if($description)
                    <span class="block truncate text-xs text-base-content/50"> {{ $description }} </span>
                @endif
            </span>
        </span>

        <i class="fa-solid fa-chevron-down cv-chevron text-xs text-base-content/50"></i>
    </summary>

    <div class="rounded-b-sm border border-base-300 bg-base-100 shadow-sm">
        <form method="GET" action="{{ $action }}">
            {{-- ========================= FILTER FIELDS ========================= --}}
            <div class="border-b border-base-300 p-4 sm:p-5">
                <div class="{{ $gridClass }}">
                    @foreach($fields as $field)
                        <x-admin.filters.field :field="$field" />
                    @endforeach
                </div>

                {{-- ========================= FILTER ACTIONS ========================= --}}
                <div class="mt-5 flex flex-col gap-4 border-t border-base-200 pt-4 sm:flex-row sm:items-center sm:justify-between">
                    {{-- Applied Filters --}}
                    <div class="flex min-w-0 flex-wrap items-center gap-2 text-xs text-base-content/50">
                        <span class="flex items-center gap-1.5">
                            <i class="fa-solid fa-filter"></i>
                            <span>Filters applied:</span>
                        </span>

                        @if($hasFilters)
                            @foreach($activeFilters as $filter)
                                @if(!empty($filter['show']))
                                    <span class="badge badge-sm badge-{{ $filter['color'] ?? 'neutral' }} gap-1.5">
                                        @if(!empty($filter['icon']))
                                            <i class="{{ $filter['icon'] }}"></i>
                                        @endif

                                        {{ $filter['value'] }}
                                    </span>
                                @endif
                            @endforeach

                            <a href="{{ $clearUrl }}" class="link link-error font-semibold">Clear all</a>
                        @else
                            <span class="text-base-content/30">None</span>
                        @endif
                    </div>

                    {{-- Apply --}}
                    <button type="submit" class="btn btn-sm btn-primary w-full shrink-0 gap-2 sm:w-auto">
                        <i class="fa-solid fa-filter"></i> Apply Filters
                    </button>
                </div>
            </div>

            {{-- ========================= RESULT SUMMARY ========================= --}}
            <div class="flex flex-col gap-2 px-5 py-3 text-sm sm:flex-row sm:items-center sm:justify-between">
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-list text-base-content/40"></i>

                    <span>
                        Showing <strong>{{ $count }}</strong> {{ $count === 1 ? $resultLabel : $resultLabelPlural }}
                    </span>

                    @if($hasFilters)
                        <span class="text-base-content/35">of</span>
                        <span class="font-semibold text-base-content/60"> {{ number_format($total) }} </span>
                    @endif
                </div>

                @if($summary)
                    <div class="text-xs text-base-content/40">
                        <i class="fa-solid fa-circle-info mr-1"></i> {{ $summary }}
                    </div>
                @endif
            </div>
        </form>
    </div>
</details>

{{-- ======================= ACTIVE FILTERS ===================== --}}
<x-admin.filters.active
    :filters="$activeFilters"
    :clear-url="$clearUrl"
/>