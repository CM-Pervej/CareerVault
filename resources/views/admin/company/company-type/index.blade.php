@extends('layouts.admin.app')

@section('title', 'Company Types | CareerVault')
@section('page_title', 'Company Types')

@section('content')
    <div class="space-y-3 p-4 sm:p-6">
        {{-- ========================= HEADER ========================= --}}
        <x-admin.ui.page-header
            icon="fa-solid fa-building text-lg"    
            title="Company Types"
            title-color="text-primary"
            title-bg-color="bg-primary/10 text-primary"
            description="Manage business organization categories."
            :create-route="route('admin.company-types.create')"
            create-text="Add Company Type"
            :trash-route="route('admin.company-types.trash')"
            :trash-count="$trashedCompanyTypesCount"
        />

        {{-- ========================= STATISTICS ========================= --}}
        <details class="cv-section overflow-hidden sm:rounded-lg border border-base-300 bg-base-100 shadow-sm"
            data-section="companyTypeStates" open>
            <x-admin.ui.disclosure-summary
                title="Statistics"
                icon="fa-solid fa-chart-simple"
                description="Overview of company types and their current status."
            />

            {{-- ===================== OVERVIEW STATS ====================== --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-1 sm:p-1 bg-[radial-gradient(circle_at_center,_theme(colors.gray.200),_theme(colors.gray.100))]">
                {{-- Total --}}
                <x-admin.ui.statistic
                    title="Company Types"
                    title-icon="fa-solid fa-users"
                    main-icon="fa-solid fa-layer-group"
                    main-icon-color="bg-info/10 text-info"
                    :total="$totalCompanyTypes"
                    total-description="Total company types in the system"
                    last-icon="fa-solid fa-database"
                />

                {{-- Active --}}
                <x-admin.ui.statistic
                    title="Active"
                    title-icon="fa-solid fa-circle-check"
                    main-icon="fa-solid fa-toggle-on"
                    main-icon-color="bg-success/10 text-success"
                    :total="$activeCompanyTypes"
                    total-description="Currently active company types"
                    last-icon="fa-solid fa-eye"
                />

                {{-- Inactive --}}
                <x-admin.ui.statistic
                    title="Inactive"
                    title-icon="fa-solid fa-circle-xmark"
                    main-icon="fa-solid fa-toggle-off"
                    main-icon-color="bg-warning/10 text-warning"
                    :total="$inactiveCompanyTypes"
                    total-description="Currently inactive company types"
                    last-icon="fa-solid fa-eye-slash"
                />

                {{-- Trash --}}
                <x-admin.ui.statistic
                    title="Trash"
                    title-icon="fa-solid fa-trash-can"
                    main-icon="fa-solid fa-recycle"
                    main-icon-color="bg-error/10 text-error"
                    :total="$trashedCompanyTypesCount"
                    total-description="Soft-deleted company types"
                    last-icon="fa-solid fa-clock-rotate-left"
                />
            </div>
        </details>

        {{-- ========================= FILTERS ========================= --}}
        <x-admin.filters.panel
            title="Filter Company Sizes"
            description="Narrow the list by name, slug, description, or status."
            section="companyTypeFilters"
            action="{{ route('admin.company-types.index') }}"
            clear-url="{{ route('admin.company-types.index') }}"
            result-label="company type"
            result-label-plural="company types"
            summary="Company type categories and employee ranges"
            gridClass="grid grid-cols-1 gap-4 sm:grid-cols-4"

            :count="$companyTypes->count()"
            :total="$totalCompanyTypes"

            :fields="[
                [
                    'type' => 'search',
                    'name' => 'search',
                    'label' => 'Search',
                    'icon' => 'fa-brands fa-searchengin',
                    'placeholder' => 'Search company types...',
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

        <section class="rounded-lg sm:border sm:border-gray-200 sm:shadow-sm mt-5">
            {{-- Directory Header --}}
            <x-admin.ui.platform.heading
                title="Company Size Directory"
                icon="fa-solid fa-building"
                :file-name="$companyTypes"
                singularFile="Type"
                pluralFile="Types"
                description="Employee ranges used to classify companies."
            />
            
            {{-- ======================= Desktop LIST ======================= --}}
            <div class="hidden overflow-x-auto sm:block">
                <table class="table table-zebra">
                    <thead>
                        <tr>
                            <th class="text-center">S.O.</th> <th>Name</th> <th>Slug</th> <th>Status</th> <th>Action By</th> <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($companyTypes as $item)
                            <tr>
                                <td class="text-center font-semibold">{{ str_pad($item->sort_order, 2, '0', STR_PAD_LEFT) }}</td>
                                <td class="min-w-48">
                                    <a href="{{ route('admin.company-types.show', $item) }}" class="font-semibold hover:text-primary">
                                        {{ $item->name }}
                                    </a>
                                    @if ($item->description)
                                        <p class="mt-1 max-w-sm truncate text-xs text-base-content/60">
                                            {{ $item->description }}
                                        </p>
                                    @endif
                                </td>
                                <td>
                                    <code class="text-xs">{{ $item->slug }}</code>
                                </td>
                                <td>
                                    <span class="badge {{ $item->is_active ? 'badge-success' : 'badge-ghost' }}">
                                        {{ $item->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                
                                <td class="whitespace-nowrap">
                                    <div>
                                        @if ($item->updatedBy?->name)
                                            <span class="text-sm font-semibold text-green-500 font-serif">
                                                {{ $item->updatedBy->name }}
                                            </span>
                                        @elseif ($item->createdBy?->name)
                                            <span class="text-sm font-semibold text-blue-500 font-serif">
                                                {{ $item->createdBy->name }}
                                            </span>
                                        @else
                                            <span class="text-xs text-center text-red-600">Not Defined</span>
                                        @endif
                                    </div>

                                    <div>
                                        @if ($item->updatedBy?->role)
                                            <span class="text-sm">
                                                {{ $item->updatedBy->role }}
                                            </span>
                                        @elseif ($item->createdBy?->role)
                                            <span class="text-sm">
                                                {{ $item->createdBy->role }}
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div class="hidden sm:flex sm:items-center gap-2 xl:justify-end">
                                        <a href="{{ route('admin.company-types.show',$item) }}" class="btn btn-sm btn-outline gap-2" title="View">
                                            <i class="fa-regular fa-eye"></i> View
                                        </a>

                                        <div class="dropdown dropdown-center dropdown-left">
                                            <button tabindex="0" class="btn btn-sm btn-ghost btn-square" aria-label="Page actions">
                                                <i class="fa-solid fa-ellipsis-vertical"></i>
                                            </button>

                                            <ul tabindex="0" class="dropdown-content menu z-30 mt-2 w-56 rounded-box border border-base-300 bg-base-100 p-2 shadow-xl">
                                                <li>
                                                    <a href="{{ route('admin.company-types.show',$item) }}">
                                                        <i class="fa-solid fa-eye"></i> View Company Type
                                                    </a>
                                                </li>

                                                <li>
                                                    <a href="{{ route('admin.company-types.edit',$item) }}">
                                                        <i class="fa-solid fa-pen-to-square"></i> Edit Company Type
                                                    </a>
                                                </li>

                                                <li class="menu-title mt-1 px-3 py-1 text-[10px] uppercase tracking-wider">
                                                    <span>Danger zone</span>
                                                </li>

                                                <li>
                                                    <button
                                                        type="button"
                                                        class="!flex !w-full !max-w-full items-center gap-2 !justify-start rounded-lg px-3 py-2 text-error"
                                                        title="Move company type to trash"
                                                        data-admin-action
                                                        data-action-url="{{ route('admin.company-types.destroy', $item->slug) }}"
                                                        data-action-method="DELETE"
                                                        data-action-type="danger"
                                                        data-action-title="Move Company Type to Trash"
                                                        data-action-description="Move “{{ $item->name }}” to trash. You can restore it later from the Trash page."
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
                                <td colspan="6" class="py-12 text-center text-base-content/60">
                                    No company types found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- ======================= MOBILE CARDS ======================= --}}
            <div class="sm:hidden space-y-3 p-3">
                @forelse ($companyTypes as $item)
                    <div class="rounded-lg border border-base-300 bg-base-100 p-3 shadow-sm">
                        {{-- Card Header --}}
                        <div class="flex items-start justify-between gap-3">
                            <div class="flex min-w-0 items-start gap-2">
                                <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-primary/10 text-xs font-bold text-primary">
                                    {{ str_pad($item->sort_order, 2, '0', STR_PAD_LEFT) }}
                                </span>
                                <div class="min-w-0">
                                    <a href="{{ route('admin.company-types.show', $item) }}" class="font-semibold hover:text-primary">
                                        {{ $item->name }}
                                    </a>
                                    <p class="mt-0.5 break-all text-xs text-base-content/50">
                                        {{ $item->slug }}
                                    </p>
                                </div>
                            </div>

                            <span class="badge shrink-0 {{ $item->is_active ? 'badge-success' : 'badge-ghost' }}">
                                {{ $item->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>

                        {{-- Description --}}
                        @if ($item->description)
                            <p class="mt-3 text-sm text-base-content/70">
                                {{ $item->description }}
                            </p>
                        @endif

                        <div class="my-3 border-t border-base-300"></div>

                        {{-- Action By --}}
                        <div class="flex items-center justify-between gap-2 text-xs">
                            <span class="text-base-content/50">Last Action By</span>
                            <div class="text-right">
                                @if ($item->updatedBy?->name)
                                    <p class="font-semibold text-green-500">{{ $item->updatedBy->name }}</p>
                                    <p class="text-base-content/60">{{ $item->updatedBy->role ?? 'Not Defined' }}</p>
                                @elseif ($item->createdBy?->name)
                                    <p class="font-semibold text-blue-500">{{ $item->createdBy->name }}</p>
                                    <p class="text-base-content/60">{{ $item->createdBy->role ?? 'Not Defined' }}</p>
                                @else
                                    <p class="text-error">Not Defined</p>
                                @endif
                            </div>
                        </div>

                        {{-- Actions --}}
                        <div class="mt-3 flex items-center gap-2 border-t border-base-300 pt-3">
                            <a href="{{ route('admin.company-types.show', $item) }}" class="btn btn-sm btn-outline flex-1">
                                <i class="fa-regular fa-eye"></i>
                                View
                            </a>

                            <a href="{{ route('admin.company-types.edit', $item) }}" class="btn btn-sm btn-outline flex-1">
                                <i class="fa-solid fa-pen-to-square"></i>
                                Edit
                            </a>

                            <button
                                type="button"
                                class="flex-1 btn btn-sm btn-outline btn-error text-error"
                                title="Move to Trash"
                                aria-label="Move {{ $item->name }} to trash"
                                data-admin-action
                                data-action-url="{{ route('admin.company-types.destroy', $item->slug) }}"
                                data-action-method="DELETE"
                                data-action-type="danger"
                                data-action-title="Move Company Type to Trash"
                                data-action-description="Move “{{ $item->name }}” to trash. You can restore it later from the Trash page."
                                data-action-icon="fa-solid fa-trash-can"
                                data-action-confirm-icon="fa-solid fa-trash-can"
                                data-action-confirm-text="Move to Trash"
                            >
                                <i class="fa-solid fa-trash-can"></i>Trash
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="py-12 text-center text-sm text-base-content/60">
                        No company types found.
                    </div>
                @endforelse
            </div>

            {{-- ======================== PAGINATION ======================== --}}
            @if ($companyTypes->hasPages())
                <div class="border-t border-base-300 px-5 py-4">
                    {{ $companyTypes->links() }}
                </div>
            @endif
        </section>
    </div>
@endsection
