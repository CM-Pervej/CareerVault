
@extends('layouts.admin.app')

@section('title','Technology Trash | CareerVault')
@section('page_title','Technologies / Trash')

@section('content')
<div class="space-y-3 p-4 sm:p-6">
    {{-- ========================= HEADER ========================= --}}
    <header>
        <x-admin.ui.page-header
            icon="fa-solid fa-trash-can text-lg"
            title="Technology Trash"
            title-color="text-error"
            title-bg-color="bg-error/10 text-error"
            description="Deleted technologies remain safely stored here until they are restored or permanently removed. Only authorized administrators can manage deleted records."
            :back-route="route('admin.technology.index')"
            backText="Back to Technologies"
        />
        <div class="my-4 flex flex-wrap justify-center gap-2 sm:justify-start">
            <span class="badge badge-error badge-outline gap-1">
                <i class="fa-solid fa-trash text-[10px]"></i> Soft Deleted
            </span>
            <span class="badge badge-warning badge-outline gap-1">
                <i class="fa-solid fa-lock text-[10px]"></i> Authorized Access
            </span>
        </div>
    </header>

    {{-- ========================= STATISTICS ========================= --}}
    <details class="cv-section overflow-hidden rounded-lg border border-base-300 bg-base-100 shadow-sm" data-section="technologyTrashStats" open>
        <x-admin.ui.disclosure-summary
            title="Trash Statistics"
            icon="fa-solid fa-chart-simple"
            description="Overview of deleted technologies and deletion activity."
        />
        <div class="grid grid-cols-1 gap-1 bg-[radial-gradient(circle_at_center,_theme(colors.gray.200),_theme(colors.gray.100))] p-1 sm:grid-cols-2 xl:grid-cols-4">
            <x-admin.ui.statistic
                title="Total in Trash"
                title-icon="fa-solid fa-trash-can"
                main-icon="fa-solid fa-recycle"
                main-icon-color="bg-error/10 text-error"
                :total="$trashedTechnologiesCount"
                total-description="Deleted technologies"
                last-icon="fa-solid fa-database"
            />
            <x-admin.ui.statistic
                title="Deleted Today"
                title-icon="fa-solid fa-calendar-day"
                main-icon="fa-solid fa-calendar-check"
                main-icon-color="bg-warning/10 text-warning"
                :total="$deletedTodayTechnologies"
                total-description="Records deleted today"
                last-icon="fa-solid fa-clock"
            />
            <x-admin.ui.statistic
                title="Deleted This Week"
                title-icon="fa-solid fa-calendar-week"
                main-icon="fa-solid fa-clock-rotate-left"
                main-icon-color="bg-info/10 text-info"
                :total="$deletedThisWeekTechnologies"
                total-description="Records deleted in the last 7 days"
                last-icon="fa-solid fa-calendar"
            />
            <x-admin.ui.statistic
                title="Access Control"
                title-icon="fa-solid fa-shield-halved"
                main-icon="fa-solid fa-shield-halved"
                main-icon-color="bg-warning/10 text-warning"
                sp="Authorized Users"
                total-description="Restore and permanent deletion permissions"
                last-icon="fa-solid fa-user-shield"
            />
        </div>
    </details>

    {{-- ========================= FILTERS ========================= --}}
    <x-admin.filters.panel
        title="Filter Deleted Technologies"
        description="Narrow deleted records by name, category, deleting user, or deletion period."
        section="technologyTrashFilters"
        action="{{ route('admin.technology.trash') }}"
        clear-url="{{ route('admin.technology.trash') }}"
        result-label="deleted technology"
        result-label-plural="deleted technologies"
        summary="Deleted technologies"
        gridClass="grid grid-cols-1 gap-4 sm:grid-cols-5"
        :count="$technologies->count()"
        :total="$trashedTechnologiesCount"
        :fields="[
            [
                'type' => 'search',
                'name' => 'search',
                'label' => 'Search',
                'icon' => 'fa-brands fa-searchengin',
                'placeholder' => 'Search technologies...',
                'value' => $search,
                'span' => 'sm:col-span-2',
            ],
            [
                'type' => 'select',
                'name' => 'category',
                'label' => 'Category',
                'icon' => 'fa-solid fa-layer-group',
                'value' => $category,
                'options' => $categories->pluck('name','id')->toArray(),
            ],
            [
                'type' => 'select',
                'name' => 'deleted_by',
                'label' => 'Deleted By',
                'icon' => 'fa-solid fa-user-shield',
                'value' => $deletedBy,
                'options' => $users->mapWithKeys(
                    fn ($user) => [
                        $user->id => $user->name . ($user->role ? ' (' . $user->role . ')' : '')
                    ]
                )->toArray(),
            ],
            [
                'type' => 'select',
                'name' => 'deleted_period',
                'label' => 'Deletion Period',
                'icon' => 'fa-solid fa-calendar-days',
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
                'show' => filled($category),
                'icon' => 'fa-solid fa-layer-group',
                'label' => 'Category',
                'value' => $categories->firstWhere('id',(int)$category)?->name ?? $category,
                'color' => 'info',
            ],
            [
                'show' => filled($deletedBy),
                'icon' => 'fa-solid fa-user-shield',
                'label' => 'Deleted By',
                'value' => $users->firstWhere('id',(int)$deletedBy)?->name ?? $deletedBy,
                'color' => 'info',
            ],
            [
                'show' => filled($deletedPeriod),
                'icon' => 'fa-solid fa-calendar-days',
                'label' => 'Deletion Period',
                'value' => [
                    'today' => 'Today',
                    'yesterday' => 'Yesterday',
                    'last_7_days' => 'Last 7 Days',
                    'last_30_days' => 'Last 30 Days',
                    'last_3_months' => 'Last 3 Months',
                    'this_year' => 'This Year',
                ][$deletedPeriod] ?? $deletedPeriod,
                'color' => 'warning',
            ],
        ]"
    />

    {{-- ======================= TRASH DIRECTORY ======================= --}}
    <div class="mt-5 overflow-hidden rounded-xl border border-base-300 bg-base-100 shadow-sm">
        {{-- Directory Header --}}
        <div class="flex flex-col gap-3 border-b border-base-300 px-4 py-4 sm:flex-row sm:items-center sm:justify-between sm:px-5">
            <div class="flex min-w-0 items-center gap-3">
                <div class="flex size-10 shrink-0 items-center justify-center rounded-xl bg-error/10 text-error">
                    <i class="fa-solid fa-trash-can text-lg"></i>
                </div>
                <div class="min-w-0">
                    <h2 class="font-semibold text-base-content">Deleted Technologies</h2>
                    <p class="mt-1 text-xs text-base-content/55">
                        Showing {{ $technologies->firstItem() ?? 0 }}–{{ $technologies->lastItem() ?? 0 }}
                        of {{ number_format($technologies->total()) }} matching records
                    </p>
                </div>
            </div>
            <span class="badge badge-error badge-outline gap-1.5 self-start sm:self-center">
                <i class="fa-solid fa-trash-can"></i> Trash
            </span>
        </div>

        {{-- ======================= DESKTOP TABLE ======================= --}}
        <div class="hidden overflow-x-auto md:block">
            <table class="table w-full">
                <thead>
                    <tr class="bg-base-200/60 text-xs uppercase tracking-wide text-base-content/60">
                        <th class="py-3 pl-5">Technology</th>
                        <th>Category</th>
                        <th>Deleted At</th>
                        <th>Deleted By</th>
                        <th class="pr-5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-base-300">
                    @forelse ($technologies as $technology)
                        <tr class="transition-colors hover:bg-base-200/30">
                            {{-- Technology --}}
                            <td class="max-w-xs py-4 pl-5">
                                <div class="flex min-w-0 items-center gap-3">
                                    <div
                                        class="flex size-10 shrink-0 items-center justify-center rounded-lg text-white"
                                        style="background-color: {{ $technology->color ?: '#6366F1' }}"
                                    >
                                        <i class="{{ $technology->icon ?: 'fa-solid fa-code' }}"></i>
                                    </div>
                                    <div class="w-48 min-w-0">
                                        <a href="{{ route('admin.technology.show',$technology) }}" class="font-semibold text-base-content transition hover:text-primary">
                                            {{ $technology->name }}
                                        </a>
                                        <p class="mt-1 break-all text-xs text-base-content/50">
                                            {{ $technology->slug }}
                                        </p>
                                        @if ($technology->description)
                                            <p class="mt-1 line-clamp-2 w-full text-sm leading-relaxed text-base-content/65">
                                                {{ $technology->description }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            {{-- Category --}}
                            <td>
                                @if ($technology->category)
                                    <span class="badge badge-ghost">
                                        {{ $technology->category->name }}
                                    </span>
                                @else
                                    <span class="text-base-content/40">Uncategorized</span>
                                @endif
                            </td>

                            {{-- Deletion Date --}}
                            <td>
                                <div class="flex items-start gap-2">
                                    <div class="mt-1 flex size-8 shrink-0 items-center justify-center rounded-lg bg-error/10 text-error">
                                        <i class="fa-solid fa-clock text-xs"></i>
                                    </div>
                                    <div class="whitespace-nowrap">
                                        <div class="text-sm font-bold">
                                            {{ $technology->deleted_at?->format('d M Y, h:i A') }}
                                        </div>
                                        <div class="text-xs text-base-content/50">
                                            {{ $technology->deleted_at?->diffForHumans() }}
                                        </div>
                                    </div>
                                </div>
                            </td>

                            {{-- Deleted By --}}
                            <td class="py-4">
                                @if ($technology->deleter)
                                    <div class="flex items-center gap-2.5">
                                        <div class="flex size-9 shrink-0 items-center justify-center rounded-xl bg-primary/10 text-primary">
                                            <i class="fa-solid fa-user-shield text-xs"></i>
                                        </div>
                                        <div class="min-w-0">
                                            <div class="max-w-[150px] truncate whitespace-nowrap text-sm font-bold" title="{{ $technology->deleter->name }}">
                                                {{ $technology->deleter->name }}
                                            </div>
                                            @if ($technology->deleter->role)
                                                <div class="mt-0.5 whitespace-nowrap text-[10px] font-semibold uppercase tracking-wider text-base-content/40">
                                                    {{ str_replace('_',' ',$technology->deleter->role) }}
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
                            <td class="pr-5">
                                <div class="flex items-center justify-between gap-2 xl:justify-end">
                                    @can('restore',$technology)
                                        <button
                                            type="button"
                                            class="btn btn-sm btn-success btn-outline flex-1 gap-2 sm:flex-none"
                                            title="Restore technology"
                                            data-admin-action
                                            data-action-url="{{ route('admin.technology.restore',$technology->slug) }}"
                                            data-action-method="PATCH"
                                            data-action-type="success"
                                            data-action-title="Restore Technology"
                                            data-action-description="Restore “{{ $technology->name }}” and return it to the active technologies."
                                            data-action-icon="fa-solid fa-rotate-left"
                                            data-action-confirm-icon="fa-solid fa-rotate-left"
                                            data-action-confirm-text="Restore Technology"
                                        >
                                            <i class="fa-solid fa-rotate-left"></i>
                                            <span>Restore</span>
                                        </button>
                                    @endcan
                                    @can('forceDelete',$technology)
                                        <button
                                            type="button"
                                            class="btn btn-error btn-sm btn-outline flex-1 gap-2 sm:flex-none"
                                            title="Delete permanently"
                                            data-admin-action
                                            data-action-url="{{ route('admin.technology.force-delete',$technology->slug) }}"
                                            data-action-method="DELETE"
                                            data-action-type="danger"
                                            data-action-title="Delete Technology Permanently"
                                            data-action-description="Permanently delete “{{ $technology->name }}”. This action cannot be undone."
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
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-16 text-center">
                                <div class="mx-auto flex size-14 items-center justify-center rounded-2xl bg-base-200 text-base-content/30">
                                    <i class="fa-solid fa-trash-can text-xl"></i>
                                </div>
                                <h3 class="mt-4 font-semibold text-base-content">
                                    {{ filled($search) || filled($category) || filled($deletedBy) || filled($deletedPeriod) ? 'No matching deleted technologies' : 'Technology Trash is empty' }}
                                </h3>
                                <p class="mx-auto mt-1 max-w-md text-sm text-base-content/50">
                                    {{ filled($search) || filled($category) || filled($deletedBy) || filled($deletedPeriod) ? 'Try adjusting your filters or clearing them to see more records.' : 'Deleted technologies will appear here when moved to trash.' }}
                                </p>
                                @if (filled($search) || filled($category) || filled($deletedBy) || filled($deletedPeriod))
                                    <a href="{{ route('admin.technology.trash') }}" class="btn btn-sm btn-outline mt-4 gap-2">
                                        <i class="fa-solid fa-rotate-left"></i> Clear Filters
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- ======================= MOBILE CARDS ======================= --}}
        <div class="divide-y divide-base-300 md:hidden">
            @forelse ($technologies as $technology)
                <article class="p-4 transition-colors hover:bg-base-200/20">
                    {{-- Identity --}}
                    <div class="flex items-start gap-3">
                        <div
                            class="flex size-11 shrink-0 items-center justify-center rounded-xl text-white"
                            style="background-color: {{ $technology->color ?: '#6366F1' }}"
                        >
                            <i class="{{ $technology->icon ?: 'fa-solid fa-code' }} text-lg"></i>
                        </div>
                        <div class="min-w-0 flex-1">
                            <div class="flex flex-wrap items-center gap-2">
                                <a href="{{ route('admin.technology.show',$technology) }}" class="font-semibold text-base-content hover:text-primary">
                                    {{ $technology->name }}
                                </a>
                                <span class="badge badge-error badge-outline badge-sm">Deleted</span>
                            </div>
                            <p class="mt-1 break-all text-xs text-base-content/50">
                                {{ $technology->slug }}
                            </p>
                            @if ($technology->category)
                                <span class="badge badge-ghost badge-sm mt-2">
                                    {{ $technology->category->name }}
                                </span>
                            @else
                                <span class="mt-2 inline-block text-xs text-base-content/40">Uncategorized</span>
                            @endif
                            @if ($technology->description)
                                <p class="mt-2 line-clamp-2 text-sm leading-relaxed text-base-content/60">
                                    {{ $technology->description }}
                                </p>
                            @endif
                        </div>
                    </div>

                    {{-- Deletion Information --}}
                    <div class="mt-4 flex items-start gap-3 rounded-lg border border-base-300/70 px-3 py-2.5">
                        <div class="flex size-8 shrink-0 items-center justify-center rounded-lg bg-base-200 text-base-content/55">
                            <i class="fa-solid fa-user-shield text-sm"></i>
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-xs text-base-content/50">Deleted by</p>
                            <p class="mt-0.5 break-words text-sm font-medium text-base-content">
                                {{ $technology->deleter?->name ?? 'Unknown user' }}
                                @if ($technology->deleter?->role)
                                    <span class="font-normal text-base-content/50">
                                        · {{ $technology->deleter->role }}
                                    </span>
                                @endif
                            </p>
                        </div>
                        <div class="shrink-0 text-right">
                            <p class="text-xs text-base-content/50">Deleted at</p>
                            <p class="mt-0.5 text-sm font-medium text-base-content">
                                {{ $technology->deleted_at?->format('M d, Y') ?? '—' }}
                            </p>
                            <p class="mt-0.5 text-xs text-base-content/50">
                                {{ $technology->deleted_at?->format('h:i A') }}
                            </p>
                        </div>
                    </div>

                    {{-- Mobile Actions --}}
                    <div class="mt-4 flex items-center gap-2 border-t border-base-300/70 pt-3">
                        @can('restore',$technology)
                            <button
                                type="button"
                                class="btn btn-sm btn-success btn-outline min-w-0 flex-1 gap-2"
                                data-admin-action
                                data-action-url="{{ route('admin.technology.restore',$technology->slug) }}"
                                data-action-method="PATCH"
                                data-action-type="success"
                                data-action-title="Restore Technology"
                                data-action-description="Restore “{{ $technology->name }}” and return it to the active technologies."
                                data-action-icon="fa-solid fa-rotate-left"
                                data-action-confirm-icon="fa-solid fa-rotate-left"
                                data-action-confirm-text="Restore Technology"
                            >
                                <i class="fa-solid fa-rotate-left"></i>
                                <span>Restore</span>
                            </button>
                        @endcan
                        @can('forceDelete',$technology)
                            <button
                                type="button"
                                class="btn btn-sm btn-error btn-outline min-w-0 flex-1 gap-2"
                                data-admin-action
                                data-action-url="{{ route('admin.technology.force-delete',$technology->slug) }}"
                                data-action-method="DELETE"
                                data-action-type="danger"
                                data-action-title="Delete Technology Permanently"
                                data-action-description="Permanently delete “{{ $technology->name }}”. This action cannot be undone."
                                data-action-icon="fa-solid fa-triangle-exclamation"
                                data-action-confirm-icon="fa-solid fa-trash-can"
                                data-action-confirm-text="Delete Forever"
                            >
                                <i class="fa-solid fa-trash-can"></i>
                                <span class="whitespace-nowrap">Delete Forever</span>
                            </button>
                        @endcan
                    </div>
                </article>
            @empty
                <div class="px-5 py-14 text-center">
                    <div class="mx-auto flex size-14 items-center justify-center rounded-2xl bg-base-200 text-base-content/30">
                        <i class="fa-solid fa-trash-can text-xl"></i>
                    </div>
                    <h3 class="mt-4 font-semibold text-base-content">
                        {{ filled($search) || filled($category) || filled($deletedBy) || filled($deletedPeriod) ? 'No matching deleted technologies' : 'Technology Trash is empty' }}
                    </h3>
                    <p class="mx-auto mt-1 max-w-sm text-sm text-base-content/50">
                        {{ filled($search) || filled($category) || filled($deletedBy) || filled($deletedPeriod) ? 'Try adjusting your filters or clearing them to see more records.' : 'Deleted technologies will appear here when moved to trash.' }}
                    </p>
                    @if (filled($search) || filled($category) || filled($deletedBy) || filled($deletedPeriod))
                        <a href="{{ route('admin.technology.trash') }}" class="btn btn-sm btn-outline mt-4 gap-2">
                            <i class="fa-solid fa-rotate-left"></i> Clear Filters
                        </a>
                    @endif
                </div>
            @endforelse
        </div>

        {{-- ======================== PAGINATION ======================== --}}
        @if ($technologies->hasPages())
            <div class="border-t border-base-300 px-4 py-4 sm:px-5">
                {{ $technologies->links() }}
            </div>
        @endif
    </div>
</div>
@endsection