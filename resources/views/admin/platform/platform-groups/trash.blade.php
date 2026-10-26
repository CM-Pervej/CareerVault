@extends('layouts.admin.app')

@section('title', 'Platfrom Groups Trash | CareerVault')
@section('page_title', 'Groups / Trash')

@section('content')
<div class="space-y-3 p-4 sm:p-6">
    {{-- =========================== Header =========================== --}}
    <header>
        <x-admin.ui.page-header
            icon="fa-solid fa-trash-can text-lg"    
            title="Platform Groups Trash"
            title-bg-color="bg-error/10 text-error"
            title-color="text-error"
            description="Deleted platform groups remain safely stored here until they are restored or permanently removed. Only authorized administrators can manage deleted records."
            :back-route="route('admin.platform-groups.index')"
            backText="Back to Groups"
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

    {{-- =========================== Statistics =========================== --}}
    <details class="cv-section overflow-hidden border border-base-300 bg-base-100 shadow-sm sm:rounded-lg" data-section="groupTrashStates" open>
        <x-admin.ui.disclosure-summary 
            title="Statistics"
            icon="fa-solid fa-chart-simple"
            description="Overview of deleted platform groups and their current status." 
        />

        {{-- Summary Cards --}}
        <div class="grid grid-cols-1 gap-0 sm:gap-1 md:grid-cols-3 p-1 bg-[radial-gradient(circle_at_center,_theme(colors.gray.200),_theme(colors.gray.100))]">
            {{-- Deleted Groups --}}
            <x-admin.ui.statistic
                title="Deleted Groups"
                title-icon="fa-solid fa-trash-can"
                main-icon="fa-solid fa-trash-can"
                main-icon-color="bg-error/10 text-error"
                :total="$groups->total()"
                total-description="Deleted groups matching the current filters"
                last-icon="fa-solid fa-database"
            />

            {{-- Filtered Results --}}
            <x-admin.ui.statistic
                title="Filtered Results"
                title-icon="fa-solid fa-sliders"
                main-icon="fa-solid fa-list-check"
                main-icon-color="bg-primary/10 text-primary"
                :total="$groups->count()"
                total-description="Results currently displayed on this group"
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
        title="Filter Trashed Groups"
        description="Narrow the trash by platform, type, access, deletion user, or deletion period"
        section="groupFilters"
        action="{{ route('admin.platform-groups.trash') }}"
        clear-url="{{ route('admin.platform-groups.trash') }}"
        result-label="group"
        result-label-plural="groups"
        summary="Deleted platform groups and destinations"
        gridClass="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-7"

        :count="$groups->count()"
        :total="$groups->total()"

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
                'options' => collect($groupTypes)->mapWithKeys(
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

    {{-- =========================== Trash List =========================== --}}
    <div class="mt-10 rounded-lg sm:mt-5 sm:border sm:border-gray-200 sm:shadow-sm">
        {{-- Toolbar --}}
        <div class="px-5 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2 font-black">
                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-error/10 text-error">
                        <i class="fa-solid fa-users"></i>
                    </div>
                    Deleted Platform Groups
                </div>

                <span class="badge badge-error badge-outline gap-1">
                    <i class="fa-solid fa-box-archive text-[10px]"></i> Trash
                </span>
            </div>

            <p class="mt-3 text-center text-xs text-base-content/50 sm:text-start">
                {{ number_format($groups->count()) }} group(s) shown
                •
                {{ number_format($groups->total()) }} total in current result
            </p>
        </div>

        @if($groups->count())

            {{-- ======================= Desktop Table ======================= --}}
            <div class="hidden overflow-x-auto bg-base-100 md:block">
                <table class="table w-full">
                    <thead>
                        <tr class="bg-base-200/50 text-xs uppercase tracking-wider text-base-content/50">
                            <th>Group</th> <th>Platform</th> <th>Type</th> <th>Access</th> <th>Deleted At</th> <th>Deleted By</th> <th class="text-right">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($groups as $group)
                            @php
                                $accessLabel = match($group->access_type) {
                                    'members_only' => 'Members Only',
                                    'private' => 'Private',
                                    default => 'Public',
                                };
                            @endphp

                            <tr class="hover:bg-base-200/40">
                                {{-- Group --}}
                                <td>
                                    <div class="flex min-w-[280px] items-start gap-3">
                                        <div class="relative flex size-14 shrink-0 items-center justify-center overflow-hidden rounded-lg border border-base-300 bg-base-200 shadow-sm">
                                            @if($group->logo)
                                                <img src="{{ Storage::url($group->logo) }}" alt="{{ $group->name }}" class="size-full object-contain">
                                            @else
                                                <i class="fa-solid fa-users text-xl text-primary"></i>
                                            @endif
                                        </div>

                                        <div class="min-w-0">
                                            <a href="{{ route('admin.platform-groups.show', $group) }}"
                                            class="font-bold transition hover:text-primary">
                                                {{ $group->name }}
                                            </a>

                                            @if($group->short_desc)
                                                <p class="mt-1 line-clamp-2 max-w-[220px] text-xs leading-5 text-base-content/55">
                                                    {{ $group->short_desc }}
                                                </p>
                                            @endif

                                            @if($group->url)
                                                <div class="mt-2 flex max-w-[220px] items-center gap-1.5 text-[11px] text-base-content/45">
                                                    <i class="fa-solid fa-link shrink-0"></i>

                                                    <span class="truncate" title="{{ $group->url }}">
                                                        {{ parse_url($group->url, PHP_URL_HOST) }}{{ parse_url($group->url, PHP_URL_PATH) ?: '/' }}
                                                    </span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                {{-- Platform --}}
                                <td>
                                    @if($group->platform)
                                        <a href="{{ route('admin.platforms.show', $group->platform) }}" class="inline-flex items-center gap-2 w-max rounded-xl border border-base-300 bg-base-200 px-2.5 py-1.5 text-xs font-bold transition hover:border-base-content/20 hover:bg-base-300">
                                            @if($group->platform->logo)
                                                <img src="{{ Storage::url($group->platform->logo) }}" alt="{{ $group->platform->name }}" class="size-6 rounded-md object-contain">
                                            @else
                                                <span class="flex size-6 items-center justify-center rounded-md bg-base-300">
                                                    @if($group->platform->icon)
                                                        <i class="{{ $group->platform->icon }}" style="{{ $group->platform->color ? 'color: '.$group->platform->color : '' }}"></i>
                                                    @else
                                                        <i class="fa-solid fa-globe" style="{{ $group->platform->color ? 'color: '.$group->platform->color : '' }}"></i>
                                                    @endif
                                                </span>
                                            @endif

                                            <span class="whitespace-nowrap">{{ $group->platform->name }}</span>
                                        </a>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 text-xs text-base-content/40">
                                            <i class="fa-solid fa-circle-exclamation"></i> Platform unavailable
                                        </span>
                                    @endif
                                </td>

                                {{-- Type --}}
                                <td>
                                    @if($group->group_type)
                                        <span class="badge badge-ghost badge-sm whitespace-nowrap"> {{ $group->group_type }} </span>
                                    @else
                                        <span class="text-sm text-base-content/40">—</span>
                                    @endif
                                </td>

                                {{-- Access --}}
                                <td>
                                    <span class="badge badge-outline badge-sm whitespace-nowrap">
                                        <i class="fa-solid fa-lock-open text-[10px]"></i> {{ $accessLabel }}
                                    </span>
                                </td>

                                {{-- Deleted At --}}
                                <td>
                                    <div class="flex items-start gap-2">
                                        <div class="mt-1 flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-error/10 text-error">
                                            <i class="fa-solid fa-clock text-xs"></i>
                                        </div>

                                        <div class="whitespace-nowrap">
                                            <div class="text-sm font-bold">
                                                {{ $group->deleted_at?->format('d M Y') }}
                                            </div>

                                            <div class="text-xs text-base-content/50">
                                                {{ $group->deleted_at?->diffForHumans() }}
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                {{-- Deleted By --}}
                                <td>
                                    @if($group->deletedBy)
                                        <div class="flex items-center gap-2.5">
                                            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-primary/10 text-primary">
                                                <i class="fa-solid fa-user-shield text-xs"></i>
                                            </div>

                                            <div class="min-w-0">
                                                <a href="{{ route('admin.users.show', $group->deletedBy) }}" class="max-w-[150px] truncate whitespace-nowrap text-sm font-bold hover:text-primary">
                                                    {{ $group->deletedBy->name }}
                                                </a>

                                                @if($group->deletedBy->role)
                                                    <div class="mt-0.5 whitespace-nowrap text-[10px] font-semibold uppercase tracking-wider text-base-content/40">
                                                        {{ \Illuminate\Support\Str::headline($group->deletedBy->role) }}
                                                    </div>
                                                @endif
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
                                <td>
                                    <div class="flex justify-end gap-2">
                                        @can('restore', $group)
                                            <button
                                                type="button"
                                                class="btn btn-success btn-sm btn-outline gap-2"
                                                title="Restore group"
                                                data-admin-action
                                                data-action-url="{{ route('admin.platform-groups.restore', $group->slug) }}"
                                                data-action-method="PATCH"
                                                data-action-type="success"
                                                data-action-title="Restore Platform Group"
                                                data-action-description="Restore “{{ $group->name }}” and return it to the active platform groups."
                                                data-action-icon="fa-solid fa-rotate-left"
                                                data-action-confirm-icon="fa-solid fa-rotate-left"
                                                data-action-confirm-text="Restore Group"
                                            >
                                                <i class="fa-solid fa-rotate-left"></i>

                                                <span>Restore</span>
                                            </button>
                                        @endcan

                                        @can('forceDelete', $group)
                                            <button
                                                type="button"
                                                class="btn btn-error btn-sm btn-outline gap-2"
                                                title="Delete permanently"
                                                data-admin-action
                                                data-action-url="{{ route('admin.platform-groups.force-delete', $group->slug) }}"
                                                data-action-method="DELETE"
                                                data-action-type="danger"
                                                data-action-title="Delete Platform Group Permanently"
                                                data-action-description="Permanently delete “{{ $group->name }}”. This action cannot be undone."
                                                data-action-icon="fa-solid fa-triangle-exclamation"
                                                data-action-confirm-icon="fa-solid fa-trash-can"
                                                data-action-confirm-text="Delete Forever"
                                            >
                                                <i class="fa-solid fa-trash-can"></i>

                                                <span class="whitespace-nowrap">Delete Forever</span>
                                            </button>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- ======================= Mobile Cards ======================= --}}
            <div class="divide-y divide-base-200 md:hidden">
                @foreach($groups as $group)
                    @php
                        $accessLabel = match($group->access_type) {
                            'members_only' => 'Members Only',
                            'private' => 'Private',
                            default => 'Public',
                        };
                    @endphp

                    <article class="mt-3 border border-gray-300 bg-base-100 p-4 shadow-sm">
                        {{-- Card Header --}}
                        <div class="flex items-start gap-3">
                            {{-- Group Logo --}}
                            <div class="relative flex size-14 shrink-0 items-center justify-center overflow-hidden rounded-lg border border-base-300 bg-base-200 shadow-sm">
                                @if($group->logo)
                                    <img src="{{ Storage::url($group->logo) }}" alt="{{ $group->name }}" class="size-full object-cover">
                                @else
                                    <i class="fa-solid fa-users text-xl text-primary"></i>
                                @endif
                            </div>

                            {{-- Group Identity --}}
                            <div class="min-w-0 flex-1">
                                <div class="flex items-start justify-between gap-3">
                                    <a href="{{ route('admin.platform-groups.show', $group) }}" class="line-clamp-2 font-bold leading-5 transition hover:text-primary">
                                        {{ $group->name }}
                                    </a>
                                </div>

                                <div class="mt-1 flex flex-wrap items-center gap-2">
                                    <span class="badge badge-error badge-outline shrink-0 gap-1 text-[10px]">
                                        <i class="fa-solid fa-trash-can"></i> Deleted
                                    </span>

                                    @if($group->group_type)
                                        <span class="badge badge-ghost badge-sm shrink-0"> {{ $group->group_type }} </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        @if($group->short_desc)
                            <p class="mt-2 line-clamp-2 text-xs leading-5 text-base-content/55"> {{ $group->short_desc }} </p>
                        @endif

                        {{-- URL --}}
                        @if($group->url)
                            <div class="mt-3 flex items-center gap-2 rounded-xl bg-base-200/60 px-3 py-2 text-xs text-base-content/55">
                                <i class="fa-solid fa-link shrink-0 text-[10px]"></i>
                                <span class="truncate" title="{{ $group->url }}"> {{ parse_url($group->url, PHP_URL_HOST) }}{{ parse_url($group->url, PHP_URL_PATH) ?: '/' }} </span>
                            </div>
                        @endif

                        {{-- Metadata --}}
                        <div class="my-2 grid grid-cols-1 gap-2">
                            {{-- Platform --}}
                            <div class="flex items-center gap-3">
                                <div class="flex items-center gap-1.5 text-[10px] font-bold uppercase tracking-wider text-base-content/40">
                                    <i class="fa-solid fa-layer-group"></i> Platform
                                </div>
                                <div class="h-px flex-1 bg-gray-300"></div>

                                @if($group->platform)
                                    <a href="{{ route('admin.platforms.show', $group->platform) }}" class="flex min-w-0 items-center gap-2 text-sm font-bold hover:text-primary">
                                        @if($group->platform->logo)
                                            <img src="{{ Storage::url($group->platform->logo) }}" alt="{{ $group->platform->name }}" class="size-5 shrink-0 rounded object-contain">
                                        @elseif($group->platform->icon)
                                            <i class="{{ $group->platform->icon }} shrink-0" style="{{ $group->platform->color ? 'color: '.$group->platform->color : '' }}"></i>
                                        @else
                                            <i class="fa-solid fa-globe shrink-0" style="{{ $group->platform->color ? 'color: '.$group->platform->color : '' }}"></i>
                                        @endif

                                        <span class="truncate"> {{ $group->platform->name }} </span>
                                    </a>
                                @else
                                    <span class="text-xs text-base-content/40">Platform unavailable</span>
                                @endif
                            </div>

                            {{-- Access --}}
                            <div class="flex flex-row-reverse items-center gap-3">
                                <div class="flex items-center gap-1.5 text-[10px] font-bold uppercase tracking-wider text-base-content/40">
                                    <i class="fa-solid fa-lock-open"></i> Access
                                </div>
                                <div class="h-px flex-1 bg-gray-300"></div>
                                <span class="text-sm font-semibold"> {{ $accessLabel }} </span>
                            </div>

                            {{-- Deleted At --}}
                            <div class="flex flex-row-reverse items-center gap-3">
                                <div class="flex items-center gap-1.5 text-[10px] font-bold uppercase tracking-wider text-base-content/40">
                                    <i class="fa-solid fa-clock"></i> Deleted At
                                </div>
                                <div class="h-px flex-1 bg-gray-300"></div>

                                <div class="text-right">
                                    <div class="text-sm font-bold"> {{ $group->deleted_at?->format('d M, Y') ?? '—' }} </div>

                                    @if($group->deleted_at)
                                        <div class="mt-0.5 text-[10px] text-base-content/50"> {{ $group->deleted_at->diffForHumans() }} </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- Deleted By --}}
                        <div class="flex flex-row-reverse items-center gap-3">
                            <div class="flex min-w-0 items-center gap-2.5">
                                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-primary/10 text-primary">
                                    <i class="fa-solid fa-user-shield text-xs"></i>
                                </div>

                                @if($group->deletedBy)
                                    <div class="min-w-0">
                                        <a href="{{ route('admin.users.show', $group->deletedBy) }}" class="truncate text-xs font-bold hover:text-primary" title="{{ $group->deletedBy->name }}"> {{ $group->deletedBy->name }} </a>

                                        @if($group->deletedBy->role)
                                            <div class="mt-0.5 text-[9px] font-semibold uppercase tracking-wider text-base-content/40"> {{ \Illuminate\Support\Str::headline($group->deletedBy->role) }} </div>
                                        @endif
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
                            @can('restore', $group)
                                <button
                                    type="button"
                                    class="btn btn-success btn-sm btn-outline gap-2"
                                    title="Restore group"
                                    data-admin-action
                                    data-action-url="{{ route('admin.platform-groups.restore', $group->slug) }}"
                                    data-action-method="PATCH"
                                    data-action-type="success"
                                    data-action-title="Restore Platform Group"
                                    data-action-description="Restore “{{ $group->name }}” and return it to the active platform groups."
                                    data-action-icon="fa-solid fa-rotate-left"
                                    data-action-confirm-icon="fa-solid fa-rotate-left"
                                    data-action-confirm-text="Restore Group"
                                >
                                    <i class="fa-solid fa-rotate-left"></i>
                                    <span>Restore</span>
                                </button>
                            @endcan

                            @can('forceDelete', $group)
                                <button
                                    type="button"
                                    class="btn btn-error btn-sm btn-outline gap-2"
                                    title="Delete permanently"
                                    data-admin-action
                                    data-action-url="{{ route('admin.platform-groups.force-delete', $group->slug) }}"
                                    data-action-method="DELETE"
                                    data-action-type="danger"
                                    data-action-title="Delete Platform Group Permanently"
                                    data-action-description="Permanently delete “{{ $group->name }}”. This action cannot be undone."
                                    data-action-icon="fa-solid fa-triangle-exclamation"
                                    data-action-confirm-icon="fa-solid fa-trash-can"
                                    data-action-confirm-text="Delete Forever"
                                >
                                    <i class="fa-solid fa-trash-can"></i>
                                    <span>Delete Forever</span>
                                </button>
                            @endcan
                        </div>
                    </article>
                @endforeach
            </div>

            {{-- Pagination --}}
            @if($groups->hasPages())
                <div class="border-t border-base-300 px-5 py-4"> {{ $groups->links() }} </div>
            @endif

        @else
            {{-- ======================= Empty State ======================= --}}
            <div class="px-8 py-20 text-center">
                <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-3xl bg-base-200 text-base-content/35">
                    <i class="fa-solid fa-users text-3xl"></i>
                </div>

                <h3 class="mt-6 text-xl font-black">Trash is empty</h3>
                <p class="mx-auto mt-2 max-w-lg text-sm leading-6 text-base-content/55">There are no deleted groups matching your current filters.</p>

                @if(request()->hasAny(['platform', 'type', 'access_type']))
                    <div class="mt-6 flex justify-center">
                        <a href="{{ route('admin.platform-groups.trash') }}"
                        class="btn btn-primary gap-2">
                            <i class="fa-solid fa-rotate-left"></i> Clear Filters
                        </a>
                    </div>
                @endif
            </div>
        @endif
    </div>

    {{-- ====================== Footer Information ====================== --}}
    @if($groups->isNotEmpty())
        <div class="flex flex-col gap-3 py-4 text-xs text-base-content/50 sm:flex-row sm:items-center sm:justify-between sm:py-2">
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-shield-halved text-success"></i>
                <span>
                    Platform groups represent <strong class="text-base-content/70">community destinations</strong> maintained in CareerVault.
                </span>
            </div>

            <div class="flex items-center gap-2">
                <i class="fa-solid fa-circle-info"></i> CareerVault Platform Directory
            </div>
        </div>
    @endif
</div>
@endsection