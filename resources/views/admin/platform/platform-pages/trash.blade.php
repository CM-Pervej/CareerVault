@extends('layouts.admin.app')

@section('title', 'Platform Pages Trash | CareerVault')
@section('page_title', 'Pages / Trash')

@section('content')
<div class="space-y-3 p-4 sm:p-6">
    {{-- Header --}}
    <header>
        <x-admin.ui.page-header
            icon="fa-solid fa-trash-can text-lg"    
            title="Platform Pages Trash"
            title-bg-color="bg-error/10 text-error"
            title-color="text-error"
            description="Deleted platform pages remain safely stored here until they are restored or permanently removed. Only authorized administrators can manage deleted records."
            :back-route="route('admin.platform-pages.index')"
            backText="Back to Pages"
        />
    
        <div class="my-4 flex flex-wrap justify-center sm:justify-start gap-2">
            <span class="badge badge-error badge-outline gap-1">
                <i class="fa-solid fa-trash text-[10px]"></i> Soft Deleted
            </span>
    
            <span class="badge badge-warning badge-outline gap-1">
                <i class="fa-solid fa-lock text-[10px]"></i> Super Admin Access
            </span>
        </div>
    </header>

    {{-- Statistics --}}
    <details class="cv-section overflow-hidden sm:rounded-lg border border-base-300 bg-base-100 shadow-sm" data-section="pageTrashStates" open>
        <x-admin.ui.disclosure-summary 
            title="Statistics"
            icon="fa-solid fa-chart-simple"
            description="Overview of deleted platform pages and their current status." 
        />

        {{-- Summary Cards --}}
        <div class="grid grid-cols-1 gap-0 sm:gap-1 md:grid-cols-3 p-1 bg-[radial-gradient(circle_at_center,_theme(colors.gray.200),_theme(colors.gray.100))]">
            {{-- Deleted Pages --}}
            <x-admin.ui.statistic
                title="Deleted Pages"
                title-icon="fa-solid fa-trash-can"
                main-icon="fa-solid fa-trash-can"
                main-icon-color="bg-error/10 text-error"
                :total="$pages->total()"
                total-description="Deleted pages matching the current filters"
                last-icon="fa-solid fa-database"
            />
    
            {{-- Filtered Results --}}
            <x-admin.ui.statistic
                title="Filtered Results"
                title-icon="fa-solid fa-sliders"
                main-icon="fa-solid fa-list-check"
                main-icon-color="bg-primary/10 text-primary"
                :total="$pages->count()"
                total-description="Results currently displayed on this page"
                last-icon="fa-solid fa-database"
            />
    
            {{-- Access Control --}}
            <x-admin.ui.statistic
                title="Access Control"
                title-icon="fa-solid fa-shield-halved"
                main-icon="fa-solid fa-shield-halved"
                main-icon-color="bg-warning/10 text-warning"
                sp="Super Admin"
                total-description="Restore and permanent deletion permissions"
                last-icon="fa-solid fa-arrow-trend-up"
            />
        </div>
    </details>

    {{-- =========================== Filters =========================== --}}
    <x-admin.filters.panel
        title="Filter Trashed Pages"
        description="Narrow the trash by platform, type, access, deletion user, or deletion period"
        section="pageFilters"
        action="{{ route('admin.platform-pages.trash') }}"
        clear-url="{{ route('admin.platform-pages.trash') }}"
        result-label="page"
        result-label-plural="pages"
        summary="Deleted platform pages and destinations"
        gridClass="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-7"

        :count="$pages->count()"
        :total="$pages->total()"

        :fields="[
            [
                'type' => 'search',
                'name' => 'search',
                'label' => 'Search',
                'icon' => 'fa-brands fa-searchengin',
                'placeholder' => 'Search deleted pages...',
                'value' => $search,
                'span' => 'col-span-2',
            ],
            [
                'type' => 'select',
                'name' => 'platform',
                'label' => 'Platform',
                'icon' => 'fa-solid fa-layer-group',
                'value' => $platform,
                'options' => $platforms->mapWithKeys(
                    fn ($item) => [$item->id => $item->name]
                )->toArray(),
            ],
            [
                'type' => 'select',
                'name' => 'type',
                'label' => 'Page Type',
                'icon' => 'fa-solid fa-shapes',
                'value' => $type,
                'options' => collect($pageTypes)->mapWithKeys(
                    fn ($type) => [$type => $type]
                )->toArray(),
            ],
            [
                'type' => 'select',
                'name' => 'access_type',
                'label' => 'Access',
                'icon' => 'fa-solid fa-lock',
                'value' => $accessType,
                'options' => [
                    'public' => 'Public',
                    'members_only' => 'Members Only',
                    'private' => 'Private',
                ],
            ],
            [
                'type' => 'select',
                'name' => 'deleted_by',
                'label' => 'Deleted By',
                'icon' => 'fa-solid fa-user-xmark',
                'value' => $deletedBy,
                'options' => $deletedByUsers->mapWithKeys(
                    fn ($user) => [
                        $user->id => $user->name . ($user->role
                            ? ' (' . ucfirst(str_replace('_', ' ', $user->role)) . ')'
                            : ''),
                    ]
                )->toArray(),
            ],
            [
                'type' => 'select',
                'name' => 'deleted_period',
                'label' => 'Deleted Period',
                'icon' => 'fa-solid fa-calendar-xmark',
                'value' => $deletedPeriod,
                'options' => [
                    'today' => 'Today',
                    'yesterday' => 'Yesterday',
                    'last_7_days' => 'Last 7 Days',
                    'last_30_days' => 'Last 30 Days',
                    'last_3_months' => 'Last 3 Months',
                    'this_year' => 'This Year',
                ],
            ],
        ]"

        :active-filters="[
            [
                'show' => filled($search),
                'icon' => 'fa-solid fa-magnifying-glass',
                'label' => 'Search',
                'value' => $search,
                'color' => 'primary',
            ],
            [
                'show' => filled($platform),
                'icon' => 'fa-solid fa-layer-group',
                'label' => 'Platform',
                'value' => optional(
                    $platforms->firstWhere('id', $platform)
                )->name ?? $platform,
                'color' => 'info',
            ],
            [
                'show' => filled($type),
                'icon' => 'fa-solid fa-shapes',
                'label' => 'Page Type',
                'value' => $type,
                'color' => 'secondary',
            ],
            [
                'show' => filled($accessType),
                'icon' => 'fa-solid fa-lock',
                'label' => 'Access',
                'value' => str_replace('_', ' ', ucfirst($accessType)),
                'color' => 'accent',
            ],
            [
                'show' => filled($deletedBy),
                'icon' => 'fa-solid fa-user-xmark',
                'label' => 'Deleted By',
                'value' => ($user = $deletedByUsers->firstWhere('id', $deletedBy))
                    ? $user->name . ($user->role
                        ? ' (' . ucfirst(str_replace('_', ' ', $user->role)) . ')'
                        : '')
                    : $deletedBy,
                'color' => 'warning',
            ],
            [
                'show' => filled($deletedPeriod),
                'icon' => 'fa-solid fa-calendar-xmark',
                'label' => 'Deleted Period',
                'value' => match ($deletedPeriod) {
                    'today' => 'Today',
                    'yesterday' => 'Yesterday',
                    'last_7_days' => 'Last 7 Days',
                    'last_30_days' => 'Last 30 Days',
                    'last_3_months' => 'Last 3 Months',
                    'this_year' => 'This Year',
                    default => $deletedPeriod,
                },
                'color' => 'success',
            ],
        ]"
    />

    {{-- Trash List --}}
    <div class="rounded-lg sm:border sm:border-gray-200 sm:shadow-sm mt-10 sm:mt-5">
        {{-- Toolbar --}}
        <div class="px-5 py-4">
            <div class="flex justify-between items-center">
                <div class="flex items-center gap-2 font-black">
                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-error/10 text-error">
                        <i class="fa-solid fa-trash-can"></i>
                    </div>
                    Deleted Platform Pages
                </div>

                @if($pages->total())
                    <span class="badge badge-error badge-outline gap-1.5">
                        <i class="fa-solid fa-trash-can text-[10px]"></i>
                        {{ $pages->total() }} {{ Str::plural('item', $pages->total()) }}
                    </span>
                @endif
            </div>

            <p class="mt-3 text-xs text-base-content/50 text-center sm:text-start">
                Deleted pages remain recoverable until permanently removed.
                <span class="font-bold text-gray-500">
                    {{ number_format($pages->count()) }} page(s) shown
                    •
                    {{ number_format($pages->total()) }} total in current result
                </span>
            </p>
        </div>

        @if($pages->count())
            {{-- ====================== Desktop Table ====================== --}}
            <div class="hidden overflow-x-auto md:block bg-base-100">
                <table class="table w-full">
                    <thead>
                        <tr class="bg-base-200/50 text-xs uppercase tracking-wider text-base-content/50">
                            <th>Page</th> <th>Platform</th> <th>Type</th> <th>Deleted</th> <th>Deleted By</th> <th class="text-right whitespace-nowrap">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($pages as $page)
                            <tr class="hover:bg-base-200/40">
                                {{-- Page --}}
                                <td>
                                    <div class="flex min-w-[280px] items-start gap-3">
                                        {{-- Page Icon --}}
                                        <div class="relative flex size-14 shrink-0 items-center justify-center overflow-hidden rounded-lg border border-base-300 bg-base-200 shadow-sm">
                                            @if($page->platform?->logo)
                                                <img src="{{ Storage::url($page->platform->logo) }}" alt="{{ $page->platform->name }}" class="size-full object-contain p-2">
                                            @elseif($page->platform?->icon)
                                                <i class="{{ $page->platform->icon }} text-5xl"
                                                @if($page->platform->color)
                                                    style="color: {{ $page->platform->color }}"
                                                @endif>
                                                </i>
                                            @else
                                                <i class="fa-solid fa-file-lines text-xl text-primary"></i>
                                            @endif
                                        </div>

                                        <div class="min-w-0">
                                            <a href="{{ route('admin.platform-pages.show', $page) }}" class="font-bold transition hover:text-primary">
                                                {{ $page->name }}
                                            </a>

                                            @if($page->short_desc)
                                                <p class="mt-1 line-clamp-2 max-w-[200px] text-xs leading-5 text-base-content/55">
                                                    {{ $page->short_desc }}
                                                </p>
                                            @endif

                                            @if($page->url)
                                                <div class="mt-2 flex max-w-[200px] items-center gap-1.5 text-[11px] text-base-content/45">
                                                    <i class="fa-solid fa-link shrink-0"></i>

                                                    <span class="truncate" title="{{ $page->url }}">
                                                        {{ parse_url($page->url, PHP_URL_HOST) }}{{ parse_url($page->url, PHP_URL_PATH) ?: '/' }}
                                                    </span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                {{-- Platform --}}
                                <td>
                                    @if($page->platform)
                                        <a href="{{ route('admin.platforms.show', $page->platform) }}" class="inline-flex items-center gap-2 rounded-xl border border-base-300 bg-base-200 px-2.5 py-1.5 text-xs font-bold transition hover:border-base-content/20 hover:bg-base-300">
                                            @if($page->platform->icon)
                                                <i class="{{ $page->platform->icon }}" style="{{ $page->platform->color ? 'color: '.$page->platform->color : '' }}"></i>
                                            @else
                                                <i class="fa-solid fa-globe" style="{{ $page->platform->color ? 'color: '.$page->platform->color : '' }}"></i>
                                            @endif

                                            {{ $page->platform->name }}
                                        </a>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 text-xs text-base-content/40">
                                            <i class="fa-solid fa-circle-exclamation"></i> Platform unavailable
                                        </span>
                                    @endif
                                </td>

                                {{-- Type --}}
                                <td>
                                    @php
                                        $typeColor = match(strtolower($page->page_type ?? '')) {
                                            'jobs' => 'badge-success',
                                            'company' => 'badge-info',
                                            'network' => 'badge-primary',
                                            'career' => 'badge-secondary',
                                            default => 'badge-ghost',
                                        };
                                    @endphp

                                    <span class="badge {{ $typeColor }} badge-sm whitespace-nowrap">
                                        {{ $page->page_type ?: 'Unknown' }}
                                    </span>
                                </td>

                                {{-- Deleted --}}
                                <td>
                                    <div class="flex items-start gap-2">
                                        <div class="mt-1 flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-error/10 text-error">
                                            <i class="fa-solid fa-clock text-xs"></i>
                                        </div>

                                        <div class="whitespace-nowrap">
                                            <div class="text-sm font-bold">
                                                {{ $page->deleted_at?->format('d M Y, h:i A') }}
                                            </div>

                                            <div class="text-xs text-base-content/50">
                                                {{ $page->deleted_at?->diffForHumans() }}
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                {{-- Deleted By --}}
                                <td class="py-4">
                                    @if($page->deletedBy)
                                        <div class="flex items-center gap-2.5">
                                            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-primary/10 text-primary">
                                                <i class="fa-solid fa-user-shield text-xs"></i>
                                            </div>

                                            <div class="min-w-0">
                                                <div class="max-w-[150px] truncate whitespace-nowrap text-sm font-bold" title="{{ $page->deletedBy->name }}">
                                                    {{ $page->deletedBy->name }}
                                                </div>

                                                <div class="mt-0.5 whitespace-nowrap text-[10px] font-semibold uppercase tracking-wider text-base-content/40">
                                                    {{ str_replace('_', ' ', $page->deletedBy->role) }}
                                                </div>
                                            </div>
                                        </div>

                                    @else
                                        <div class="flex items-center gap-2 text-xs text-base-content/35">
                                            <i class="fa-solid fa-user-slash"></i>
                                            <span>Unknown user</span>
                                        </div>
                                    @endif
                                </td>

                                {{-- Actions --}}
                                <td class="whitespace-nowrap">
                                    <div class="flex justify-end gap-2">
                                        @can('restore', $page)
                                            <button
                                                type="button"
                                                class="btn btn-success btn-sm btn-outline gap-2"
                                                title="Restore page"
                                                data-admin-action
                                                data-action-url="{{ route('admin.platform-pages.restore', $page->slug) }}"
                                                data-action-method="PATCH"
                                                data-action-type="success"
                                                data-action-title="Restore Platform Page"
                                                data-action-description="Restore “{{ $page->name }}” and return it to the active platform pages."
                                                data-action-icon="fa-solid fa-rotate-left"
                                                data-action-confirm-icon="fa-solid fa-rotate-left"
                                                data-action-confirm-text="Restore Page"
                                            >
                                                <i class="fa-solid fa-rotate-left"></i>

                                                <span class="hidden lg:inline">Restore</span>
                                            </button>
                                        @endcan

                                        @can('forceDelete', $page)
                                            <button
                                                type="button"
                                                class="btn btn-error btn-sm btn-outline gap-2"
                                                title="Delete permanently"
                                                data-admin-action
                                                data-action-url="{{ route('admin.platform-pages.force-delete', $page->slug) }}"
                                                data-action-method="DELETE"
                                                data-action-type="danger"
                                                data-action-title="Delete Platform Page Permanently"
                                                data-action-description="Permanently delete “{{ $page->name }}”. This action cannot be undone."
                                                data-action-icon="fa-solid fa-triangle-exclamation"
                                                data-action-confirm-icon="fa-solid fa-trash-can"
                                                data-action-confirm-text="Delete Forever"
                                            >
                                                <i class="fa-solid fa-trash-can"></i>

                                                <span class="hidden lg:inline whitespace-nowrap">Delete Forever</span>
                                            </button>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- ======================= Mobile Cards ======================== --}}
            <div class="divide-y divide-base-200 md:hidden">
                @foreach($pages as $page)
                    @php
                        $typeColor = match(strtolower($page->page_type ?? '')) {
                            'jobs' => 'badge-success',
                            'company' => 'badge-info',
                            'network' => 'badge-primary',
                            'career' => 'badge-secondary',
                            default => 'badge-ghost',
                        };
                    @endphp

                    <div class="p-4 transition bg-base-100 border border-gray-300 shadow-sm mt-3">
                        {{-- Card Header --}}
                        <div class="flex items-start gap-3">
                            {{-- Platform Logo --}}
                            <div class="relative flex size-14 shrink-0 items-center justify-center overflow-hidden rounded-lg border border-base-300 bg-base-200 shadow-sm">
                                @if($page->platform?->logo)
                                    <img src="{{ Storage::url($page->platform->logo) }}" alt="{{ $page->platform->name }}" class="size-full object-contain p-2">
                                @elseif($page->platform?->icon)
                                    <i class="{{ $page->platform->icon }} text-5xl"
                                       @if($page->platform->color)
                                           style="color: {{ $page->platform->color }}"
                                       @endif>
                                    </i>
                                @else
                                    <i class="fa-solid fa-file-lines text-xl text-primary"></i>
                                @endif
                            </div>

                            {{-- Page Identity --}}
                            <div class="min-w-0 flex-1">
                                <div class="flex items-start justify-between gap-3">
                                    <a href="{{ route('admin.platform-pages.show', $page) }}" class="line-clamp-2 font-bold leading-5 transition hover:text-primary">
                                        {{ $page->name }}
                                    </a>
                                </div>

                                <span class="badge {{ $typeColor }} badge-sm shrink-0 whitespace-nowrap">
                                    {{ $page->page_type ?: 'Unknown' }}
                                </span>
                            </div>
                        </div>

                        @if($page->short_desc)
                            <p class="mt-1 line-clamp-2 text-xs leading-5 text-base-content/55">
                                {{ $page->short_desc }}
                            </p>
                        @endif

                        {{-- URL --}}
                        @if($page->url)
                            <div class="mt-3 flex items-center gap-2 rounded-xl bg-base-200/60 px-3 py-2 text-xs text-base-content/55">
                                <i class="fa-solid fa-link shrink-0 text-[10px]"></i>

                                <span class="truncate" title="{{ $page->url }}">
                                    {{ parse_url($page->url, PHP_URL_HOST) }}{{ parse_url($page->url, PHP_URL_PATH) ?: '/' }}
                                </span>
                            </div>
                        @endif

                        {{-- Metadata --}}
                        <div class="grid grid-cols-1 gap-2 my-2">
                            {{-- Platform --}}
                            <div class="flex items-center gap-3">
                                <div class="flex items-center gap-1.5 text-[10px] font-bold uppercase tracking-wider text-base-content/40">
                                    <i class="fa-solid fa-layer-group"></i> Platform
                                </div>

                                <div class="h-px flex-1 bg-gray-300"></div>

                                @if($page->platform)
                                    <a href="{{ route('admin.platforms.show', $page->platform) }}" class="flex min-w-0 items-center gap-2 text-sm font-bold hover:text-primary">
                                        @if($page->platform->icon)
                                            <i class="{{ $page->platform->icon }} shrink-0" style="{{ $page->platform->color ? 'color: '.$page->platform->color : '' }}"></i>
                                        @else
                                            <i class="fa-solid fa-globe shrink-0" style="{{ $page->platform->color ? 'color: '.$page->platform->color : '' }}"></i>
                                        @endif

                                        <span class="truncate">
                                            {{ $page->platform->name }}
                                        </span>
                                    </a>
                                @else
                                    <span class="text-xs text-base-content/40">Platform unavailable</span>
                                @endif
                            </div>

                            {{-- Deleted --}}
                            <div class="flex flex-row-reverse items-center gap-3">
                                <div class="flex items-center gap-1.5 text-[10px] font-bold uppercase tracking-wider text-base-content/40">
                                    <i class="fa-solid fa-clock"></i> Deleted At
                                </div>

                                <div class="h-px flex-1 bg-gray-300"></div>

                                <div>
                                    <div class="text-sm font-bold">
                                        {{ $page->deleted_at?->format('d M, Y') }}
                                    </div>
    
                                    <div class="mt-0.5 text-[10px] text-base-content/50">
                                        {{ $page->deleted_at?->diffForHumans() }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Deleted By --}}
                        <div class="flex flex-row-reverse items-center gap-3">
                            <div class="flex min-w-0 items-center gap-2.5">
                                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-primary/10 text-primary">
                                    <i class="fa-solid fa-user-shield text-xs"></i>
                                </div>

                                @if($page->deletedBy)
                                    <div class="min-w-0">
                                        <div class="truncate text-xs font-bold" title="{{ $page->deletedBy->name }}">
                                            {{ $page->deletedBy->name }}
                                        </div>

                                        <div class="mt-0.5 text-[9px] font-semibold uppercase tracking-wider text-base-content/40">
                                            {{ str_replace('_', ' ', $page->deletedBy->role) }}
                                        </div>
                                    </div>
                                @else
                                    <div class="text-xs text-base-content/40">Unknown user</div>
                                @endif
                            </div>

                            <div class="h-px flex-1 bg-gray-300"></div>

                            <span class="text-[10px] font-semibold uppercase tracking-wider text-base-content/35">Deleted By</span>
                        </div>

                        {{-- Actions --}}
                        <div class="mt-4 grid grid-cols-2 gap-2">
                            @can('restore', $page)
                                <button
                                    type="button"
                                    class="btn btn-success btn-sm btn-outline gap-2"
                                    title="Restore page"
                                    data-admin-action
                                    data-action-url="{{ route('admin.platform-pages.restore', $page->slug) }}"
                                    data-action-method="PATCH"
                                    data-action-type="success"
                                    data-action-title="Restore Platform Page"
                                    data-action-description="Restore “{{ $page->name }}” and return it to the active platform pages."
                                    data-action-icon="fa-solid fa-rotate-left"
                                    data-action-confirm-icon="fa-solid fa-rotate-left"
                                    data-action-confirm-text="Restore Page"
                                >
                                    <i class="fa-solid fa-rotate-left"></i> Restore
                                </button>
                            @endcan

                            @can('forceDelete', $page)
                                <button
                                    type="button"
                                    class="btn btn-error btn-sm btn-outline gap-2"
                                    title="Delete permanently"
                                    data-admin-action
                                    data-action-url="{{ route('admin.platform-pages.force-delete', $page->slug) }}"
                                    data-action-method="DELETE"
                                    data-action-type="danger"
                                    data-action-title="Delete Platform Page Permanently"
                                    data-action-description="Permanently delete “{{ $page->name }}”. This action cannot be undone."
                                    data-action-icon="fa-solid fa-triangle-exclamation"
                                    data-action-confirm-icon="fa-solid fa-trash-can"
                                    data-action-confirm-text="Delete Forever"
                                >
                                    <i class="fa-solid fa-trash-can"></i> Delete Forever
                                </button>
                            @endcan
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            @if($pages->hasPages())
                <div class="border-t border-base-300 px-5 py-4">
                    {{ $pages->links() }}
                </div>
            @endif
            
        @else
            {{-- Empty State --}}
            <div class="px-8 py-20 text-center">
                <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-3xl bg-base-200 text-base-content/35">
                    <i class="fa-solid fa-trash-can text-3xl"></i>
                </div>

                <x-admin.ui.empty-state
                    title="No deleted platform pages"
                    description="The trash is currently empty. Deleted platform pages will appear here and can be restored while they remain in the trash."
                    :action-route="route('admin.platform-pages.index')"
                    action-text="View Platform Pages"
                />
            </div>
        @endif
    </div>

    {{-- ====================== FOOTER INFORMATION ===================== --}}
    <x-admin.ui.footer-info
        :file-name="$pages"
        description="Platform pages represent <strong>official destinations and resources</strong> maintained in CareerVault."
    />
</div>
@endsection