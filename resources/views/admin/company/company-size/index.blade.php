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

        {{-- ======================= DIRECTORY LIST ======================= --}}
        <div class="divide-y divide-base-300">
            @forelse ($companySizes as $companySize)
                <div class="group relative my-2 border border-base-300 bg-base-100 p-4 shadow-sm transition hover:bg-base-200/30 sm:my-0 sm:border-0 sm:shadow-none sm:p-5">
                    <div class="flex flex-col gap-5 xl:flex-row xl:items-center">
                        {{-- ================= COMPANY SIZE IDENTITY ================= --}}
                        <div class="flex min-w-0 flex-1 items-start gap-3">
                            <div class="flex size-12 shrink-0 items-center justify-center rounded-xl bg-primary/10 text-primary">
                                <i class="fa-solid fa-users text-xl"></i>
                            </div>

                            <div class="min-w-0 flex-1">
                                <div class="flex flex-wrap items-center gap-2">
                                    <a href="{{ route('admin.company-sizes.show', $companySize) }}" class="font-semibold text-base-content transition hover:text-primary"> {{ $companySize->name }} </a>

                                    @if ($companySize->is_active)
                                        <span class="badge badge-success badge-sm badge-outline">Active</span>
                                    @else
                                        <span class="badge badge-ghost badge-sm">Inactive</span>
                                    @endif
                                </div>

                                <p class="mt-1 break-all text-xs text-base-content/50"> {{ $companySize->slug }} </p>

                                @if ($companySize->description)
                                    <p class="mt-2 line-clamp-2 max-w-xl text-sm leading-relaxed text-base-content/65"> {{ $companySize->description }} </p>
                                @endif
                            </div>
                        </div>

                        {{-- ================= COMPANY SIZE DETAILS ================= --}}
                        <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 xl:w-[470px] xl:shrink-0">
                            {{-- Employee Range --}}
                            <div class="rounded-lg border border-base-300/70 bg-base-200/40 p-3">
                                <div class="flex items-center gap-2 text-xs text-base-content/55">
                                    <i class="fa-solid fa-user-group"></i>
                                    <span>Employees</span>
                                </div>
                                <p class="mt-2 font-semibold text-base-content">
                                    {{ number_format($companySize->min_employees) }}
                                    <span class="font-normal text-base-content/50">–</span>
                                    {{ $companySize->max_employees === null ? 'Unlimited' : number_format($companySize->max_employees) }}
                                </p>
                                <p class="mt-1 text-xs text-base-content/50">Employee range</p>
                            </div>

                            {{-- Companies --}}
                            <div class="rounded-lg border border-base-300/70 bg-base-200/40 p-3">
                                <div class="flex items-center gap-2 text-xs text-base-content/55">
                                    <i class="fa-solid fa-building"></i>
                                    <span>Companies</span>
                                </div>
                                <p class="mt-2 font-semibold text-base-content"> {{ number_format($companySize->companies_count ?? 0) }} </p>
                                <p class="mt-1 text-xs text-base-content/50">Assigned companies</p>
                            </div>

                            {{-- Sort Order --}}
                            <div class="col-span-2 rounded-lg border border-base-300/70 bg-base-200/40 p-3 sm:col-span-1">
                                <div class="flex items-center gap-2 text-xs text-base-content/55">
                                    <i class="fa-solid fa-arrow-down-short-wide"></i>
                                    <span>Sort Order</span>
                                </div>

                                <p class="mt-2 font-semibold text-base-content"> {{ $companySize->sort_order }} </p>
                                <p class="mt-1 text-xs text-base-content/50">Display position</p>
                            </div>
                        </div>

                        {{-- ======================= Desktop ACTIONS ======================= --}}
                        <div class="hidden sm:flex sm:items-center gap-2 xl:justify-end">
                            <a href="{{ route('admin.company-sizes.show',$companySize) }}" class="btn btn-sm btn-outline gap-2" title="View">
                                <i class="fa-regular fa-eye"></i> View
                            </a>

                            <div class="dropdown dropdown-center dropdown-left">
                                <button tabindex="0" class="btn btn-sm btn-ghost btn-square" aria-label="Page actions">
                                    <i class="fa-solid fa-ellipsis-vertical"></i>
                                </button>

                                <ul tabindex="0" class="dropdown-content menu z-30 mt-2 w-56 rounded-box border border-base-300 bg-base-100 p-2 shadow-xl">
                                    <li>
                                        <a href="{{ route('admin.company-sizes.show',$companySize) }}">
                                            <i class="fa-solid fa-eye"></i> View Company Size
                                        </a>
                                    </li>

                                    <li>
                                        <a href="{{ route('admin.company-sizes.edit',$companySize) }}">
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
                                            title="Move company size to trash"
                                            data-admin-action
                                            data-action-url="{{ route('admin.company-sizes.destroy', $companySize->slug) }}"
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

                        {{-- ======================= Mobile ACTIONS ======================= --}}
                        <div class="flex justify-between items-center gap-2 sm:hidden">
                            <a href="{{ route('admin.company-sizes.show',$companySize) }}" class="btn btn-sm btn-outline gap-2" title="View">
                                <i class="fa-regular fa-eye"></i> View
                            </a>

                            <a href="{{ route('admin.company-sizes.edit',$companySize) }}" class="btn btn-sm btn-outline gap-2" title="Edit">
                                <i class="fa-solid fa-pen-to-square"></i> Edit
                            </a>

                            <button
                                type="button"
                                class="text-error gap-2"
                                title="Move company size to trash"
                                data-admin-action
                                data-action-url="{{ route('admin.company-sizes.destroy', $companySize->slug) }}"
                                data-action-method="DELETE"
                                data-action-type="danger"
                                data-action-title="Move Company Size to Trash"
                                data-action-description="Move “{{ $companySize->name }}” to trash. You can restore it later from the Trash page."
                                data-action-icon="fa-solid fa-trash-can"
                                data-action-confirm-icon="fa-solid fa-trash-can"
                                data-action-confirm-text="Move to Trash"
                            >
                                <i class="fa-solid fa-trash-can shrink-0"></i> Trash
                            </button>
                        </div>
                    </div>
                </div>

            @empty
                {{-- ======================= EMPTY STATE ======================= --}}
                <div class="px-6 py-20 text-center">
                    <div class="mx-auto flex size-16 items-center justify-center rounded-2xl bg-base-200 text-base-content/30">
                        <i class="fa-solid fa-users-slash text-2xl"></i>
                    </div>

                    @if (filled($search) || filled($status))
                        <h3 class="mt-5 text-lg font-black">No matching company sizes</h3>
                        <p class="mx-auto mt-1 max-w-md text-sm text-base-content/50">No company sizes match your current search and filter combination. Try changing your filters or clearing them.</p>

                        <a href="{{ route('admin.company-sizes.index') }}"
                            class="btn btn-outline mt-5 gap-2">
                            <i class="fa-solid fa-rotate-left"></i> Clear Filters
                        </a>
                    @else
                        <x-admin.ui.empty-state
                            title="No company sizes available"
                            description="Create your first company size category to classify companies by employee range."
                            :action-route="route('admin.company-sizes.create')"
                            action-text="Add Company Size"
                        />
                    @endif
                </div>
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