@extends('layouts.admin.app')

@section('title', 'Industries Through The World')

@section('content')
<div class="space-y-3 p-4 sm:p-6">
    {{-- ========================= HEADER ========================= --}}
    <x-admin.ui.page-header
        icon="fa-solid fa-industry"    
        title="Industries"
        title-color="text-primary"
        title-bg-color="bg-primary/10 text-primary"
        description="Manage industries and their sub-industries."
        :create-route="route('admin.industries.create')"
        create-text="Add Industry"
        :trash-route="route('admin.industries.trash')"
        :trash-count="$trashedIndustries"
    />

    {{-- ========================= STATISTICS ========================= --}}
    <details class="cv-section overflow-hidden sm:rounded-lg border border-base-300 bg-base-100 shadow-sm" data-section="industryStates" open>
        <x-admin.ui.disclosure-summary
            title="Statistics"
            icon="fa-solid fa-chart-simple"
            description="Overview of industries, their hierarchy, activity, and recent changes."
        />

        {{-- ===================== OVERVIEW STATS ====================== --}}
        <div class="grid grid-cols-2 sm:grid-cols-2 xl:grid-cols-4 gap-1 sm:p-1 bg-[radial-gradient(circle_at_center,_theme(colors.gray.200),_theme(colors.gray.100))]">
            {{-- Total --}}
            <x-admin.ui.statistic
                title="Industries"
                title-icon="fa-solid fa-industry"
                main-icon="fa-solid fa-industry"
                main-icon-color="bg-info/10 text-info"
                :total="$totalIndustries"
                total-description="Total industries"
                last-icon="fa-solid fa-database"
            />

            {{-- Active --}}
            <x-admin.ui.statistic
                title="Active"
                title-icon="fa-solid fa-circle-check"
                main-icon="fa-solid fa-toggle-on"
                main-icon-color="bg-success/10 text-success"
                :total="$activeIndustries"
                total-description="Currently active industries"
                last-icon="fa-solid fa-eye"
            />

            {{-- Inactive --}}
            <x-admin.ui.statistic
                title="Inactive"
                title-icon="fa-solid fa-circle-xmark"
                main-icon="fa-solid fa-toggle-off"
                main-icon-color="bg-warning/10 text-warning"
                :total="$inactiveIndustries"
                total-description="Currently inactive industries"
                last-icon="fa-solid fa-eye-slash"
            />

            {{-- Parent Industries --}}
            <x-admin.ui.statistic
                title="Parent Industries"
                title-icon="fa-solid fa-sitemap"
                main-icon="fa-solid fa-folder-tree"
                main-icon-color="bg-primary/10 text-primary"
                :total="$parentIndustries"
                total-description="Top-level industries who has no parent industries"
                last-icon="fa-solid fa-layer-group"
            />

            {{-- Sub-industries --}}
            <x-admin.ui.statistic
                title="Sub-industries"
                title-icon="fa-solid fa-diagram-project"
                main-icon="fa-solid fa-code-branch"
                main-icon-color="bg-secondary/10 text-secondary"
                :total="$subIndustries"
                total-description="Industries with a parent industry"
                last-icon="fa-solid fa-sitemap"
            />

            {{-- Industries With Children --}}
            <x-admin.ui.statistic
                title="With Children"
                title-icon="fa-solid fa-folder-tree"
                main-icon="fa-solid fa-folder-open"
                main-icon-color="bg-accent/10 text-accent"
                :total="$industriesWithChildren"
                total-description="Industries who can have parent and sub-industries both"
                last-icon="fa-solid fa-children"
            />

            {{-- Leaf Industries --}}
            <x-admin.ui.statistic
                title="Leaf Industries"
                title-icon="fa-solid fa-leaf"
                main-icon="fa-solid fa-diagram-next"
                main-icon-color="bg-success/10 text-success"
                :total="$leafIndustries"
                total-description="Industries without children"
                last-icon="fa-solid fa-circle-nodes"
            />

            {{-- Recently Added --}}
            <x-admin.ui.statistic
                title="Recently Added"
                title-icon="fa-solid fa-calendar-plus"
                main-icon="fa-solid fa-clock"
                main-icon-color="bg-info/10 text-info"
                :total="$recentlyAdded"
                total-description="Added in the last 30 days"
                last-icon="fa-solid fa-plus"
            />

            {{-- Recently Updated --}}
            <x-admin.ui.statistic
                title="Recently Updated"
                title-icon="fa-solid fa-calendar-check"
                main-icon="fa-solid fa-rotate"
                main-icon-color="bg-warning/10 text-warning"
                :total="$recentlyUpdated"
                total-description="Updated in the last 30 days"
                last-icon="fa-solid fa-pen-to-square"
            />

            {{-- Trash --}}
            <x-admin.ui.statistic
                title="Trash"
                title-icon="fa-solid fa-trash-can"
                main-icon="fa-solid fa-recycle"
                main-icon-color="bg-error/10 text-error"
                :total="$trashedIndustries"
                total-description="Soft-deleted industries"
                last-icon="fa-solid fa-clock-rotate-left"
            />
        </div>
    </details>

    {{-- ========================= FILTERS ========================= --}}
    <x-admin.filters.panel
        title="Filter Industries"
        description="Narrow the list by name, slug, parent industry, or status"
        section="industryFilters"
        action="{{ route('admin.industries.index') }}"
        clear-url="{{ route('admin.industries.index') }}"
        result-label="industry"
        result-label-plural="industries"
        summary="Industry directory and hierarchy"
        gridClass="grid grid-cols-2 gap-4 sm:grid-cols-4"

        :count="$industries->count()"
        :total="$totalIndustries"

        :fields="[
            [
                'type' => 'search',
                'name' => 'search',
                'label' => 'Search',
                'icon' => 'fa-brands fa-searchengin',
                'placeholder' => 'Search industries...',
                'value' => $search,
                'span' => 'col-span-2',
            ],
            [
                'type' => 'select',
                'name' => 'parent',
                'label' => 'Parent Industry',
                'icon' => 'fa-solid fa-sitemap',
                'value' => $parent,
                'options' => $parents->mapWithKeys(
                    fn ($item) => [$item->id => $item->name]
                )->toArray(),
                'empty_label' => 'Select',
            ],
            [
                'type' => 'select',
                'name' => 'status',
                'label' => 'Status',
                'icon' => 'fa-solid fa-power-off',
                'value' => $status,
                'options' => [
                    'active' => 'Active',
                    'inactive' => 'Inactive',
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
                'show' => filled($parent),
                'icon' => 'fa-solid fa-sitemap',
                'label' => 'Parent Industry',
                'value' => $parents->firstWhere('id', $parent)?->name ?? $parent,
                'color' => 'info',
            ],
            [
                'show' => $status !== null && $status !== '',
                'icon' => 'fa-solid fa-power-off',
                'label' => 'Status',
                'value' => ucfirst($status),
                'color' => 'warning',
            ],
        ]"
    />

    <div class="hidden md:block overflow-x-auto bg-base-100 border border-base-300 rounded-2xl shadow-sm">
        <table class="table">
            <thead>
                <tr>
                    <th>Industry</th> <th>Parent</th> <th>Children</th> <th>Status</th> <th>Order</th> <th class="text-right">Actions</th>
                </tr>
            </thead>

            <tbody>
                @forelse($industries as $industry)
                    <tr class="hover">
                        <td>
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-10 h-10 rounded-xl flex items-center justify-center bg-base-200"
                                    @if($industry->color)
                                        style="color: {{ $industry->color }}"
                                    @endif
                                >
                                    <i class="{{ $industry->icon ?: 'fa-solid fa-layer-group' }}"></i>
                                </div>

                                <div>
                                    <a href="{{ route('admin.industries.show', $industry) }}" class="font-semibold hover:text-primary"> {{ $industry->name }} </a>
                                    <div class="text-xs opacity-50"> {{ $industry->slug }} </div>
                                </div>
                            </div>
                        </td>

                        <td>
                            @if($industry->parent)
                                <span class="badge badge-ghost"> {{ $industry->parent->name }} </span>
                            @else
                                <span class="text-xs opacity-50">Top-level</span>
                            @endif
                        </td>

                        <td>
                            <span class="badge badge-outline"> {{ $industry->children_count }} </span>
                        </td>

                        <td>
                            @if($industry->is_active)
                                <span class="badge badge-success badge-sm">Active</span>
                            @else
                                <span class="badge badge-ghost badge-sm">Inactive</span>
                            @endif
                        </td>

                        <td> {{ $industry->sort_order }} </td>

                        {{-- Actions --}}
                        <td>
                            <div class="flex items-center gap-2 xl:justify-end">
                                <a href="{{ route('admin.industries.show',$industry) }}" class="btn btn-sm btn-outline gap-2" title="View">
                                    <i class="fa-regular fa-eye"></i> View
                                </a>

                                <div class="dropdown dropdown-center dropdown-left">
                                    <button tabindex="0" class="btn btn-sm btn-ghost btn-square" aria-label="Page actions">
                                        <i class="fa-solid fa-ellipsis-vertical"></i>
                                    </button>

                                    <ul tabindex="0" class="dropdown-content menu z-30 mt-2 w-56 rounded-box border border-base-300 bg-base-100 p-2 shadow-xl">
                                        <li>
                                            <a href="{{ route('admin.industries.show',$industry) }}">
                                                <i class="fa-solid fa-eye"></i> View industry
                                            </a>
                                        </li>

                                        <li>
                                            <a href="{{ route('admin.industries.edit',$industry) }}">
                                                <i class="fa-solid fa-pen-to-square"></i> Edit industry
                                            </a>
                                        </li>

                                        <li class="menu-title mt-1 px-3 py-1 text-[10px] uppercase tracking-wider">
                                            <span>Danger zone</span>
                                        </li>

                                        <li>
                                            <div>
                                                <button
                                                    type="button"
                                                    class="text-error gap-2"
                                                    title="Move industry to trash"
                                                    data-admin-action
                                                    data-action-url="{{ route('admin.industries.destroy',$industry->slug) }}"
                                                    data-action-method="DELETE"
                                                    data-action-type="danger"
                                                    data-action-title="Move Platform to Trash"
                                                    data-action-description="Move “{{ $industry->name }}” to trash. You can restore it later from the Trash page."
                                                    data-action-icon="fa-solid fa-trash-can"
                                                    data-action-confirm-icon="fa-solid fa-trash-can"
                                                    data-action-confirm-text="Move to Trash"
                                                >
                                                    <i class="fa-solid fa-trash-can"></i>
                                                    <span class="hidden lg:inline">Move to Trash</span>
                                                </button>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-12">
                            <div class="opacity-50">
                                <i class="fa-solid fa-layer-group text-3xl mb-3"></i>
                                <p>No industries found.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="md:hidden space-y-3">
        @forelse($industries as $industry)
            <div class="card bg-base-100 border border-base-300 shadow-sm">
                <div class="card-body p-4">

                    <div class="flex items-start justify-between gap-3">
                        <div class="flex items-center gap-3 min-w-0">
                            <div
                                class="w-11 h-11 shrink-0 rounded-xl flex items-center justify-center bg-base-200"
                                @if($industry->color)
                                    style="color: {{ $industry->color }}"
                                @endif
                            >
                                <i class="{{ $industry->icon ?: 'fa-solid fa-layer-group' }}"></i>
                            </div>

                            <div class="min-w-0">
                                <a href="{{ route('admin.industries.show', $industry) }}" class="font-bold hover:text-primary"> {{ $industry->name }} </a>
                                <div class="text-xs opacity-50 truncate"> {{ $industry->slug }} </div>
                            </div>
                        </div>

                        @if($industry->is_active)
                            <span class="badge badge-success badge-sm">Active</span>
                        @else
                            <span class="badge badge-ghost badge-sm">Inactive</span>
                        @endif
                    </div>

                    <div class="grid grid-cols-2 gap-2 mt-3 text-sm">
                        <div class="bg-base-200 rounded-lg p-3">
                            <div class="text-xs opacity-50">Parent</div>
                            <div class="font-medium mt-1"> {{ $industry->parent?->name ?? 'Top-level' }} </div>
                        </div>

                        <div class="bg-base-200 rounded-lg p-3">
                            <div class="text-xs opacity-50">Children</div>
                            <div class="font-medium mt-1"> {{ $industry->children_count }} </div>
                        </div>
                    </div>

                    <div class="sm:hidden flex items-center justify-between">
                        <a href="{{ route('admin.industries.show', $industry) }}" class="btn btn-sm btn-outline">
                            <i class="fa-solid fa-eye"></i> View
                        </a>

                        <a href="{{ route('admin.industries.edit', $industry) }}" class="btn btn-sm btn-outline">
                            <i class="fa-solid fa-pen-to-square"></i> Edit
                        </a>

                        <button
                            type="button"
                            class="text-error flex items-center hover:bg-error/10 btn btn-sm btn-outline border-error"
                            title="Move industry to trash"
                            data-admin-action
                            data-action-url="{{ route('admin.industries.destroy',$industry->slug) }}"
                            data-action-method="DELETE"
                            data-action-type="danger"
                            data-action-title="Move Platform to Trash"
                            data-action-description="Move “{{ $industry->name }}” to trash. You can restore it later from the Trash page."
                            data-action-icon="fa-solid fa-trash-can"
                            data-action-confirm-icon="fa-solid fa-trash-can"
                            data-action-confirm-text="Move to Trash"
                        >
                            <i class="fa-solid fa-trash-can"></i> Trash
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="card bg-base-100 border border-base-300">
                <div class="card-body text-center py-12 opacity-50">
                    <i class="fa-solid fa-layer-group text-3xl mb-3"></i>
                    <p>No industries found.</p>
                </div>
            </div>
        @endforelse
    </div>

    <div class="mt-5">
        {{ $industries->links() }}
    </div>
</div>
@endsection