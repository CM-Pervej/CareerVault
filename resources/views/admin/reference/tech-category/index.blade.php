
@extends('layouts.admin.app')

@section('title', 'Technology Categories | CareerVault')
@section('page_title', 'Technology Categories')

@section('content')
<div class="space-y-3 p-4 sm:p-6">
    {{-- Header --}}
    <x-admin.ui.page-header
        icon="fa-solid fa-layer-group text-lg"
        title="Technology Categories"
        title-color="text-primary"
        title-bg-color="bg-primary/10 text-primary"
        description="Manage technology categories used to organize technologies across CareerVault."
        :create-route="route('admin.tech-categories.create')"
        create-text="Add Category"
        :trash-route="route('admin.tech-categories.trash')"
        :trash-count="$trashedTechCategories"
    />

    {{-- Statistics --}}
    <details class="cv-section overflow-hidden sm:rounded-lg border border-base-300 bg-base-100 shadow-sm" data-section="techCategoryStates" open>
        <x-admin.ui.disclosure-summary
            title="Statistics"
            icon="fa-solid fa-chart-simple"
            description="Overview of technology categories and their current status."
        />
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-1 sm:p-1 bg-[radial-gradient(circle_at_center,_theme(colors.gray.200),_theme(colors.gray.100))]">
            <x-admin.ui.statistic
                title="Categories"
                title-icon="fa-solid fa-layer-group"
                main-icon="fa-solid fa-database"
                main-icon-color="bg-info/10 text-info"
                :total="$totalTechCategories"
                total-description="Total technology categories"
                last-icon="fa-solid fa-list"
            />
            <x-admin.ui.statistic
                title="Active"
                title-icon="fa-solid fa-circle-check"
                main-icon="fa-solid fa-toggle-on"
                main-icon-color="bg-success/10 text-success"
                :total="$activeTechCategories"
                total-description="Currently active categories"
                last-icon="fa-solid fa-eye"
            />
            <x-admin.ui.statistic
                title="Inactive"
                title-icon="fa-solid fa-circle-xmark"
                main-icon="fa-solid fa-toggle-off"
                main-icon-color="bg-warning/10 text-warning"
                :total="$inactiveTechCategories"
                total-description="Currently inactive categories"
                last-icon="fa-solid fa-eye-slash"
            />
            <x-admin.ui.statistic
                title="Trash"
                title-icon="fa-solid fa-trash-can"
                main-icon="fa-solid fa-recycle"
                main-icon-color="bg-error/10 text-error"
                :total="$trashedTechCategories"
                total-description="Soft-deleted categories"
                last-icon="fa-solid fa-clock-rotate-left"
            />
        </div>
    </details>

    {{-- Filters --}}
    <x-admin.filters.panel
        title="Filter Technology Categories"
        description="Narrow the list by category name, slug, description, or status."
        section="techCategoryFilters"
        action="{{ route('admin.tech-categories.index') }}"
        clear-url="{{ route('admin.tech-categories.index') }}"
        result-label="technology category"
        result-label-plural="technology categories"
        summary="Technology categories and their associated technologies"
        gridClass="grid grid-cols-1 gap-4 sm:grid-cols-4"
        :count="$techCategories->count()"
        :total="$totalTechCategories"
        :fields="[
            [
                'type' => 'search',
                'name' => 'search',
                'label' => 'Search',
                'icon' => 'fa-brands fa-searchengin',
                'placeholder' => 'Search categories...',
                'value' => $search,
                'span' => 'sm:col-span-3',
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
                'show' => filled($status),
                'icon' => 'fa-solid fa-power-off',
                'label' => 'Status',
                'value' => ucfirst($status ?? ''),
                'color' => 'warning',
            ],
        ]"
    />

    {{-- Directory --}}
    <div class="rounded-lg sm:border sm:border-gray-200 sm:shadow-sm mt-5">
        <x-admin.ui.platform.heading
            title="Technology Category Directory"
            icon="fa-solid fa-layer-group"
            :file-name="$techCategories"
            singularFile="Category"
            pluralFile="Categories"
            description="Technology categories and their associated technologies."
        />

        {{-- Desktop --}}
        <div class="hidden overflow-x-auto sm:block">
            <table class="table table-zebra">
                <thead>
                    <tr>
                        <th class="text-center">S.O.</th>
                        <th>Category</th>
                        <th>Technologies</th>
                        <th>Action By</th>
                        <th>Action Time</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($techCategories as $techCategory)
                        <tr>
                            <td class="text-center font-semibold">
                                {{ str_pad($techCategory->sort_order, 2, '0', STR_PAD_LEFT) }}
                            </td>
                            <td class="min-w-0">
                                <div class="flex flex-wrap items-center gap-2">
                                    <a href="{{ route('admin.tech-categories.show', $techCategory) }}" class="font-semibold text-base-content transition hover:text-primary">
                                        {{ $techCategory->name }}
                                    </a>
                                    @if ($techCategory->is_active)
                                        <span class="badge badge-success badge-sm badge-outline">Active</span>
                                    @else
                                        <span class="badge badge-ghost badge-sm">Inactive</span>
                                    @endif
                                </div>
                                <p class="mt-0.5 text-xs text-base-content/45">{{ $techCategory->slug }}</p>
                                @if ($techCategory->description)
                                    <p class="mt-1 line-clamp-2 max-w-xl text-sm leading-relaxed text-base-content/65">{{ $techCategory->description }}</p>
                                @endif
                            </td>
                            <td>
                                <span class="badge badge-info badge-outline gap-1">
                                    <i class="fa-solid fa-code"></i>
                                    {{ number_format($techCategory->technologies_count) }}
                                </span>
                            </td>
                            <td class="whitespace-nowrap">
                                @if ($techCategory->updatedBy?->name)
                                    <span class="text-sm font-semibold text-green-500 font-serif">{{ $techCategory->updatedBy->name }}</span>
                                @elseif ($techCategory->createdBy?->name)
                                    <span class="text-sm font-semibold text-blue-500 font-serif">{{ $techCategory->createdBy->name }}</span>
                                @else
                                    <span class="text-xs text-red-600">Not Defined</span>
                                @endif
                                <div class="text-sm">
                                    {{ $techCategory->updatedBy?->role ?? $techCategory->createdBy?->role ?? '' }}
                                </div>
                            </td>
                            <td>
                                <div class="flex items-start gap-2">
                                    <div class="mt-1 flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-primary/10 text-primary">
                                        <i class="fa-solid fa-clock text-xs"></i>
                                    </div>
                                    <div class="whitespace-nowrap">
                                        <div class="text-sm font-semibold leading-tight tracking-tighter">
                                            {{ $techCategory->updated_at?->format('d M Y, h:i A') }}
                                        </div>
                                        <div class="text-xs text-base-content/50">
                                            {{ $techCategory->updated_at?->diffForHumans() }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="hidden sm:flex sm:items-center gap-2 xl:justify-end">
                                    <a href="{{ route('admin.tech-categories.show', $techCategory) }}" class="btn btn-sm btn-outline gap-2" title="View">
                                        <i class="fa-regular fa-eye"></i> View
                                    </a>
                                    <div class="dropdown dropdown-center dropdown-left">
                                        <button tabindex="0" class="btn btn-sm btn-ghost btn-square" aria-label="Page actions">
                                            <i class="fa-solid fa-ellipsis-vertical"></i>
                                        </button>
                                        <ul tabindex="0" class="dropdown-content menu z-30 mt-2 w-56 rounded-box border border-base-300 bg-base-100 p-2 shadow-xl">
                                            <li>
                                                <a href="{{ route('admin.tech-categories.show', $techCategory) }}">
                                                    <i class="fa-solid fa-eye"></i> View Category
                                                </a>
                                            </li>
                                            <li>
                                                <a href="{{ route('admin.tech-categories.edit', $techCategory) }}">
                                                    <i class="fa-solid fa-pen-to-square"></i> Edit Category
                                                </a>
                                            </li>
                                            <li class="menu-title mt-1 px-3 py-1 text-[10px] uppercase tracking-wider">
                                                <span>Danger zone</span>
                                            </li>
                                            <li>
                                                <button
                                                    type="button"
                                                    class="!flex !w-full !max-w-full items-center gap-2 !justify-start rounded-lg px-3 py-2 text-error"
                                                    data-admin-action
                                                    data-action-url="{{ route('admin.tech-categories.destroy', $techCategory) }}"
                                                    data-action-method="DELETE"
                                                    data-action-type="danger"
                                                    data-action-title="Move Technology Category to Trash"
                                                    data-action-description="Move “{{ $techCategory->name }}” to trash. You can restore it later."
                                                    data-action-icon="fa-solid fa-trash-can"
                                                    data-action-confirm-icon="fa-solid fa-trash-can"
                                                    data-action-confirm-text="Move to Trash"
                                                >
                                                    <i class="fa-solid fa-trash-can shrink-0"></i>
                                                    <span>Move to Trash</span>
                                                </button>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-base-content/60">No technology categories found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Mobile --}}
        <div class="sm:hidden space-y-3">
            @forelse ($techCategories as $techCategory)
                <div class="rounded-lg border border-base-300 bg-base-100 p-4 shadow-sm">
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex min-w-0 items-start gap-2">
                            <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-primary/10 text-xs font-bold text-primary">
                                {{ str_pad($techCategory->sort_order, 2, '0', STR_PAD_LEFT) }}
                            </span>
                            <div class="min-w-0">
                                <a href="{{ route('admin.tech-categories.show', $techCategory) }}" class="font-semibold hover:text-primary">{{ $techCategory->name }}</a>
                                <p class="mt-0.5 break-all text-xs text-base-content/50">{{ $techCategory->slug }}</p>
                            </div>
                        </div>
                        <span class="badge shrink-0 {{ $techCategory->is_active ? 'badge-success' : 'badge-ghost' }}">
                            {{ $techCategory->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>

                    @if ($techCategory->description)
                        <p class="mt-3 text-sm text-base-content/70">{{ $techCategory->description }}</p>
                    @endif

                    <div class="my-3 border-t border-base-300"></div>

                    <div class="flex items-center justify-between gap-2 text-xs">
                        <span class="text-base-content/50">Technologies</span>
                        <span class="font-semibold">{{ number_format($techCategory->technologies_count) }}</span>
                    </div>

                    <div class="flex items-center justify-between gap-2 text-xs mt-3">
                        <span class="text-base-content/50">Last Action By</span>
                        <div class="text-right">
                            <div class="text-sm font-semibold">
                                {{ $techCategory->updatedBy?->name ?? $techCategory->createdBy?->name ?? 'Not Defined' }}
                            </div>
                            <div class="text-xs text-base-content/50">
                                {{ $techCategory->updatedBy?->role ?? $techCategory->createdBy?->role ?? '' }}
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-between gap-2 text-xs my-3">
                        <span class="text-base-content/50">Last Action Time</span>
                        <div class="text-right">
                            <div class="text-sm font-semibold">{{ $techCategory->updated_at?->format('d M Y, h:i A') }}</div>
                            <div class="text-xs text-base-content/50">{{ $techCategory->updated_at?->diffForHumans() }}</div>
                        </div>
                    </div>

                    <div class="flex items-center gap-2 border-t border-base-300 pt-3">
                        <a href="{{ route('admin.tech-categories.show', $techCategory) }}" class="btn btn-sm btn-outline flex-1">
                            <i class="fa-regular fa-eye"></i> View
                        </a>
                        <a href="{{ route('admin.tech-categories.edit', $techCategory) }}" class="btn btn-sm btn-outline flex-1">
                            <i class="fa-solid fa-pen-to-square"></i> Edit
                        </a>
                        <button
                            type="button"
                            class="flex-1 btn btn-sm btn-outline btn-error text-error"
                            data-admin-action
                            data-action-url="{{ route('admin.tech-categories.destroy', $techCategory) }}"
                            data-action-method="DELETE"
                            data-action-type="danger"
                            data-action-title="Move Technology Category to Trash"
                            data-action-description="Move “{{ $techCategory->name }}” to trash. You can restore it later."
                            data-action-icon="fa-solid fa-trash-can"
                            data-action-confirm-icon="fa-solid fa-trash-can"
                            data-action-confirm-text="Move to Trash"
                        >
                            <i class="fa-solid fa-trash-can"></i> Trash
                        </button>
                    </div>
                </div>
            @empty
                <div class="py-12 text-center text-sm text-base-content/60">No technology categories found.</div>
            @endforelse
        </div>

        {{-- Pagination --}}
        @if ($techCategories->hasPages())
            <div class="border-t border-base-300 px-5 py-4">
                {{ $techCategories->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
