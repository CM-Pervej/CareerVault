@extends('layouts.admin.app')

@section('title', 'Company Sizes')
@section('page_title', 'Company Sizes')

@section('content')
<div class="space-y-3 p-4 sm:p-6">
    {{-- ========================= HEADER ========================= --}}
    <x-admin.ui.page-header
        icon="fa-solid fa-building text-lg"    
        title="Company Sizes"
        title-color="text-primary"
        title-bg-color="bg-primary/10 text-primary"
        description="Manage employee ranges used to classify companies."
        :create-route="route('admin.company-sizes.create')"
        create-text="Add Company Size"
        :trash-route="route('admin.company-sizes.trash')"
        :trash-count="$trashedCompanySizes"
    />
 
    {{-- ========================= STATISTICS ========================= --}}
    <details class="cv-section overflow-hidden sm:rounded-lg border border-base-300 bg-base-100 shadow-sm"
        data-section="companySizeStates" open>
        <x-admin.ui.disclosure-summary
            title="Statistics"
            icon="fa-solid fa-chart-simple"
            description="Overview of company sizes and their current status."
        />

        {{-- ===================== OVERVIEW STATS ====================== --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-1 sm:p-1 bg-[radial-gradient(circle_at_center,_theme(colors.gray.200),_theme(colors.gray.100))]">
            {{-- Total --}}
            <x-admin.ui.statistic
                title="Company Sizes"
                title-icon="fa-solid fa-users"
                main-icon="fa-solid fa-layer-group"
                main-icon-color="bg-info/10 text-info"
                :total="$totalCompanySizes"
                total-description="Total company size categories"
                last-icon="fa-solid fa-database"
            />

            {{-- Active --}}
            <x-admin.ui.statistic
                title="Active"
                title-icon="fa-solid fa-circle-check"
                main-icon="fa-solid fa-toggle-on"
                main-icon-color="bg-success/10 text-success"
                :total="$activeCompanySizes"
                total-description="Currently active company sizes"
                last-icon="fa-solid fa-eye"
            />

            {{-- Inactive --}}
            <x-admin.ui.statistic
                title="Inactive"
                title-icon="fa-solid fa-circle-xmark"
                main-icon="fa-solid fa-toggle-off"
                main-icon-color="bg-warning/10 text-warning"
                :total="$inactiveCompanySizes"
                total-description="Currently inactive company sizes"
                last-icon="fa-solid fa-eye-slash"
            />

            {{-- Trash --}}
            <x-admin.ui.statistic
                title="Trash"
                title-icon="fa-solid fa-trash-can"
                main-icon="fa-solid fa-recycle"
                main-icon-color="bg-error/10 text-error"
                :total="$trashedCompanySizes"
                total-description="Soft-deleted company sizes"
                last-icon="fa-solid fa-clock-rotate-left"
            />
        </div>
    </details>

    {{-- ========================= FILTERS ========================= --}}
    <x-admin.filters.panel
        title="Filter Company Sizes"
        description="Narrow the list by name, slug, description, or status."
        section="companySizeFilters"
        action="{{ route('admin.company-sizes.index') }}"
        clear-url="{{ route('admin.company-sizes.index') }}"
        result-label="company size"
        result-label-plural="company sizes"
        summary="Company size categories and employee ranges"
        gridClass="grid grid-cols-1 gap-4 sm:grid-cols-4"

        :count="$companySizes->count()"
        :total="$totalCompanySizes"

        :fields="[
            [
                'type' => 'search',
                'name' => 'search',
                'label' => 'Search',
                'icon' => 'fa-brands fa-searchengin',
                'placeholder' => 'Search company sizes...',
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

    {{-- ======================= COMPANY SIZE DIRECTORY ======================= --}}
    <div class="rounded-lg sm:border sm:border-gray-200 sm:shadow-sm mt-5">
        {{-- Directory Header --}}
        <x-admin.ui.platform.heading
            title="Company Size Directory"
            icon="fa-solid fa-users"
            :file-name="$companySizes"
            singularFile="Size"
            pluralFile="Sizes"
            description="Employee ranges used to classify companies."
        />

        {{-- ======================= DESKTOP LIST ======================= --}}
        <div class="hidden overflow-x-auto sm:block">
            <table class="table table-zebra">
                <thead>
                    <tr>
                        <th class="text-center">S.O.</th> <th>Name</th> <th>Employee</th> <th>Companies</th> <th>Action By</th> <th>Action Time</th> <th class="text-right">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($companySizes as $companySize)
                        <tr>
                            <td class="text-center font-semibold">
                                {{ str_pad($companySize->sort_order, 2, '0', STR_PAD_LEFT) }}
                            </td>

                            <td class="flex min-w-0 flex-1 items-start gap-3">
                                <div class="min-w-0 flex-1">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <a href="{{ route('admin.company-sizes.show', $companySize) }}" class="font-semibold text-base-content transition hover:text-primary"> {{ $companySize->name }} </a>

                                        @if ($companySize->is_active)
                                            <span class="badge badge-success badge-sm badge-outline">Active</span>
                                        @else
                                            <span class="badge badge-ghost badge-sm">Inactive</span>
                                        @endif
                                    </div>

                                    @if ($companySize->description)
                                        <p class="mt-0.5 line-clamp-2 max-w-xl text-sm leading-relaxed text-base-content/65"> {{ $companySize->description }} </p>
                                    @endif
                                </div>
                            </td>

                            <td>
                                {{ number_format($companySize->min_employees) }}
                                <span class="font-normal text-base-content/50">–</span>
                                {{ $companySize->max_employees === null ? 'Unlimited' : number_format($companySize->max_employees) }}
                            </td>

                            <td>
                                {{ number_format($companySize->companies_count ?? 0) }}
                            </td>

                            <td class="whitespace-nowrap">
                                <div>
                                    @if ($companySize->updatedBy?->name)
                                        <span class="text-sm font-semibold text-green-500 font-serif">
                                            {{ $companySize->updatedBy->name }}
                                        </span>
                                    @elseif ($companySize->createdBy?->name)
                                        <span class="text-sm font-semibold text-blue-500 font-serif">
                                            {{ $companySize->createdBy->name }}
                                        </span>
                                    @else
                                        <span class="text-xs text-center text-red-600">
                                            Not Defined
                                        </span>
                                    @endif
                                </div>

                                <div>
                                    @if ($companySize->updatedBy?->role)
                                        <span class="text-sm">
                                            {{ $companySize->updatedBy->role }}
                                        </span>
                                    @elseif ($companySize->createdBy?->role)
                                        <span class="text-sm">
                                            {{ $companySize->createdBy->role }}
                                        </span>
                                    @endif
                                </div>
                            </td>

                            {{-- Deletion Date --}}
                            <td>
                                <div class="flex items-start gap-2">
                                    <div class="mt-1 flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-error/10 text-error">
                                        <i class="fa-solid fa-clock text-xs"></i>
                                    </div>

                                    <div class="whitespace-nowrap">
                                        <div class="text-sm font-semibold leading-tight tracking-tighter">
                                            {{ $companySize->updated_at?->format('d M Y, h:i A') }}
                                        </div>

                                        <div class="text-xs text-base-content/50">
                                            {{ $companySize->updated_at?->diffForHumans() }}
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <td>
                                <div class="hidden sm:flex sm:items-center gap-2 xl:justify-end">
                                    <a href="{{ route('admin.company-sizes.show', $companySize) }}" class="btn btn-sm btn-outline gap-2" title="View">
                                        <i class="fa-regular fa-eye"></i> View
                                    </a>

                                    <div class="dropdown dropdown-center dropdown-left">
                                        <button tabindex="0" class="btn btn-sm btn-ghost btn-square" aria-label="Page actions">
                                            <i class="fa-solid fa-ellipsis-vertical"></i>
                                        </button>

                                        <ul tabindex="0" class="dropdown-content menu z-30 mt-2 w-56 rounded-box border border-base-300 bg-base-100 p-2 shadow-xl">
                                            <li>
                                                <a href="{{ route('admin.company-sizes.show', $companySize) }}">
                                                    <i class="fa-solid fa-eye"></i> View Company Size
                                                </a>
                                            </li>

                                            <li>
                                                <a href="{{ route('admin.company-sizes.edit', $companySize) }}">
                                                    <i class="fa-solid fa-pen-to-square"></i> Edit Company Size
                                                </a>
                                            </li>

                                            <li class="menu-title mt-1 px-3 py-1 text-[10px] uppercase tracking-wider">
                                                <span>Danger zone</span>
                                            </li>

                                            <li>
                                                <button
                                                    type="button"
                                                    class="!flex !w-full !max-w-full items-center gap-2 !justify-start rounded-lg px-3 py-2 text-error"
                                                    title="Move Company Size to trash"
                                                    data-admin-action
                                                    data-action-url="{{ route('admin.company-sizes.destroy', $companySize) }}"
                                                    data-action-method="DELETE"
                                                    data-action-type="danger"
                                                    data-action-title="Move Company Size to Trash"
                                                    data-action-description="Move “{{ $companySize->name }}” to trash. You can restore it later from the Trash page."
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
                            <td colspan="6" class="py-12 text-center text-base-content/60">No company sizes found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- ======================= MOBILE CARDS ======================= --}}
        <div class="sm:hidden space-y-3">
            @forelse ($companySizes as $companySize)
                <div class="rounded-lg border border-base-300 bg-base-100 p-4 shadow-sm">
                    {{-- Card Header --}}
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex min-w-0 items-start gap-2">
                            <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-primary/10 text-xs font-bold text-primary">
                                {{ str_pad($companySize->sort_order, 2, '0', STR_PAD_LEFT) }}
                            </span>

                            <div class="min-w-0">
                                <a href="{{ route('admin.company-sizes.show', $companySize) }}" class="font-semibold hover:text-primary"> {{ $companySize->name }} </a>
                                <p class="mt-0.5 break-all text-xs text-base-content/50"> {{ $companySize->slug }} </p>
                            </div>
                        </div>

                        <span class="badge shrink-0 {{ $companySize->is_active ? 'badge-success' : 'badge-ghost' }}"> {{ $companySize->is_active ? 'Active' : 'Inactive' }} </span>
                    </div>

                    {{-- Description --}}
                    @if ($companySize->description)
                        <p class="mt-3 text-sm text-base-content/70"> {{ $companySize->description }} </p>
                    @endif

                    <div class="my-3 border-t border-base-300"></div>

                    {{-- Action By --}}
                    <div class="flex items-center justify-between gap-2 text-xs">
                        <span class="text-base-content/50">Last Action By</span>

                        <div class="text-right">
                            <div>
                                @if ($companySize->updatedBy?->name)
                                    <span class="text-sm font-semibold text-green-500 font-serif"> {{ $companySize->updatedBy->name }} </span>
                                @elseif ($companySize->createdBy?->name)
                                    <span class="text-sm font-semibold text-blue-500 font-serif"> {{ $companySize->createdBy->name }} </span>
                                @else
                                    <span class="text-xs text-center text-red-600">Not Defined</span>
                                @endif
                            </div>

                            <div>
                                @if ($companySize->updatedBy?->role)
                                    <span class="text-sm"> {{ $companySize->updatedBy->role }} </span>
                                @elseif ($companySize->createdBy?->role)
                                    <span class="text-sm"> {{ $companySize->createdBy->role }} </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Action At --}}
                    <div class="flex items-center justify-between gap-2 text-xs my-3">
                        <span class="text-base-content/50">Last Action Time</span>

                        <div class="text-right">
                            <div class="whitespace-nowrap">
                                <div class="text-sm font-semibold leading-tight tracking-tighter"> {{ $companySize->updated_at?->format('d M Y, h:i A') }} </div>
                                <div class="text-xs text-base-content/50"> {{ $companySize->updated_at?->diffForHumans() }} </div>
                            </div>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="flex items-center gap-2 border-t border-base-300 pt-3">
                        <a href="{{ route('admin.company-sizes.show', $companySize) }}" class="btn btn-sm btn-outline flex-1">
                            <i class="fa-regular fa-eye"></i> View
                        </a>

                        <a href="{{ route('admin.company-sizes.edit', $companySize) }}" class="btn btn-sm btn-outline flex-1">
                            <i class="fa-solid fa-pen-to-square"></i> Edit
                        </a>

                        <button
                            type="button"
                            class="flex-1 btn btn-sm btn-outline btn-error text-error"
                            title="Move to Trash"
                            aria-label="Move {{ $companySize->name }} to trash"
                            data-admin-action
                            data-action-url="{{ route('admin.company-sizes.destroy', $companySize) }}"
                            data-action-method="DELETE"
                            data-action-type="danger"
                            data-action-title="Move Company Sizes to Trash"
                            data-action-description="Move “{{ $companySize->name }}” to trash. You can restore it later from the Trash page."
                            data-action-icon="fa-solid fa-trash-can"
                            data-action-confirm-icon="fa-solid fa-trash-can"
                            data-action-confirm-text="Move to Trash"
                        >
                            <i class="fa-solid fa-trash-can"></i>
                            Trash
                        </button>
                    </div>
                </div>
            @empty
                <div class="py-12 text-center text-sm text-base-content/60">No company sizes found.</div>
            @endforelse
        </div>

        {{-- ======================== PAGINATION ======================== --}}
        @if ($companySizes->hasPages())
            <div class="border-t border-base-300 px-5 py-4">
                {{ $companySizes->links() }}
            </div>
        @endif
    </div>
</div>
@endsection