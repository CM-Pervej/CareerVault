@extends('layouts.admin.app')

@section('title', 'Business Models | CareerVault')
@section('page_title', 'Business Models')

@section('content')
<div class="space-y-3 p-4 sm:p-6">
    {{-- ========================= HEADER ========================= --}}
    <x-admin.ui.page-header
        icon="fa-solid fa-layer-group text-lg"
        title="Business Models"
        title-color="text-primary"
        title-bg-color="bg-primary/10 text-primary"
        description="Manage the business models used in your company directory."
        :create-route="route('admin.business-models.create')"
        create-text="Add Business Model"
        :trash-route="route('admin.business-models.trash')"
        :trash-count="$trashedBusinessModelsCount"
    />
    
    {{-- ========================= STATISTICS ========================= --}}
    <details class="cv-section overflow-hidden sm:rounded-lg border border-base-300 bg-base-100 shadow-sm"
        data-section="businessModelStates" open>
        <x-admin.ui.disclosure-summary
            title="Statistics"
            icon="fa-solid fa-chart-simple"
            description="Overview of business models and their current status."
        />
        {{-- ===================== OVERVIEW STATS ====================== --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-1 sm:p-1 bg-[radial-gradient(circle_at_center,_theme(colors.gray.200),_theme(colors.gray.100))]">
            {{-- Total --}}
            <x-admin.ui.statistic
                title="Business Models"
                title-icon="fa-solid fa-layer-group"
                main-icon="fa-solid fa-database"
                main-icon-color="bg-info/10 text-info"
                :total="$totalBusinessModels"
                total-description="Total business models"
                last-icon="fa-solid fa-database"
            />
            {{-- Active --}}
            <x-admin.ui.statistic
                title="Active"
                title-icon="fa-solid fa-circle-check"
                main-icon="fa-solid fa-toggle-on"
                main-icon-color="bg-success/10 text-success"
                :total="$activeBusinessModels"
                total-description="Currently active business models"
                last-icon="fa-solid fa-eye"
            />
            {{-- Inactive --}}
            <x-admin.ui.statistic
                title="Inactive"
                title-icon="fa-solid fa-circle-xmark"
                main-icon="fa-solid fa-toggle-off"
                main-icon-color="bg-warning/10 text-warning"
                :total="$inactiveBusinessModels"
                total-description="Currently inactive business models"
                last-icon="fa-solid fa-eye-slash"
            />
            {{-- Trash --}}
            <x-admin.ui.statistic
                title="Trash"
                title-icon="fa-solid fa-trash-can"
                main-icon="fa-solid fa-recycle"
                main-icon-color="bg-error/10 text-error"
                :total="$trashedBusinessModelsCount"
                total-description="Soft-deleted business models"
                last-icon="fa-solid fa-clock-rotate-left"
            />
        </div>
    </details>
    {{-- ========================= FILTERS ========================= --}}
    <x-admin.filters.panel
        title="Filter Business Models"
        description="Narrow the list by name, slug, description, or status."
        section="businessModelFilters"
        action="{{ route('admin.business-models.index') }}"
        clear-url="{{ route('admin.business-models.index') }}"
        result-label="business model"
        result-label-plural="business models"
        summary="Business models used in your company directory"
        gridClass="grid grid-cols-1 gap-4 sm:grid-cols-4"
        :count="$businessModels->count()"
        :total="$totalBusinessModels"
        :fields="[
            [
                'type' => 'search',
                'name' => 'search',
                'label' => 'Search',
                'icon' => 'fa-brands fa-searchengin',
                'placeholder' => 'Search business models...',
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
    {{-- ======================= BUSINESS MODEL DIRECTORY ======================= --}}
    <div class="rounded-lg sm:border sm:border-gray-200 sm:shadow-sm mt-5">
        {{-- Directory Header --}}
        <x-admin.ui.platform.heading
            title="Business Model Directory"
            icon="fa-solid fa-layer-group"
            :file-name="$businessModels"
            singularFile="Model"
            pluralFile="Models"
            description="Business models used to classify companies in your directory."
        />
        {{-- ======================= DESKTOP LIST ======================= --}}
        <div class="hidden overflow-x-auto sm:block">
            <table class="table table-zebra">
                <thead>
                    <tr>
                        <th class="text-center">S.O.</th>
                        <th>Name</th>
                        <th>Action By</th>
                        <th>Action Time</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($businessModels as $businessModel)
                        <tr>
                            <td class="text-center font-semibold">
                                {{ str_pad($businessModel->sort_order, 2, '0', STR_PAD_LEFT) }}
                            </td>
                            <td class="flex min-w-0 flex-1 items-start gap-3">
                                <div class="min-w-0 flex-1">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <a href="{{ route('admin.business-models.show', $businessModel) }}" class="font-semibold text-base-content transition hover:text-primary"> {{ $businessModel->name }} </a>
                                        @if ($businessModel->is_active)
                                            <span class="badge badge-success badge-sm badge-outline">Active</span>
                                        @else
                                            <span class="badge badge-ghost badge-sm">Inactive</span>
                                        @endif
                                    </div>
                                    @if ($businessModel->description)
                                        <p class="mt-0.5 line-clamp-2 max-w-xl text-sm leading-relaxed text-base-content/65"> {{ $businessModel->description }} </p>
                                    @endif
                                </div>
                            </td>
                            {{-- Action By --}}
                            <td class="whitespace-nowrap">
                                <div>
                                    @if ($businessModel->updater?->name)
                                        <span class="text-sm font-semibold text-green-500 font-serif"> {{ $businessModel->updater->name }} </span>
                                    @elseif ($businessModel->creator?->name)
                                        <span class="text-sm font-semibold text-blue-500 font-serif"> {{ $businessModel->creator->name }} </span>
                                    @else
                                        <span class="text-xs text-center text-red-600">Not Defined</span>
                                    @endif
                                </div>
                                <div>
                                    @if ($businessModel->updater?->role)
                                        <span class="text-sm"> {{ $businessModel->updater->role }} </span>
                                    @elseif ($businessModel->creator?->role)
                                        <span class="text-sm"> {{ $businessModel->creator->role }} </span>
                                    @endif
                                </div>
                            </td>
                            {{-- Action Time --}}
                            <td>
                                <div class="flex items-start gap-2">
                                    <div class="mt-1 flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-error/10 text-error">
                                        <i class="fa-solid fa-clock text-xs"></i>
                                    </div>
                                    <div class="whitespace-nowrap">
                                        <div class="text-sm font-semibold leading-tight tracking-tighter"> {{ $businessModel->updated_at?->format('d M Y, h:i A') }} </div>
                                        <div class="text-xs text-base-content/50"> {{ $businessModel->updated_at?->diffForHumans() }} </div>
                                    </div>
                                </div>
                            </td>
                            {{-- Actions --}}
                            <td>
                                <div class="hidden sm:flex sm:items-center gap-2 xl:justify-end">
                                    <a href="{{ route('admin.business-models.show', $businessModel) }}" class="btn btn-sm btn-outline gap-2" title="View">
                                        <i class="fa-regular fa-eye"></i> View
                                    </a>
                                    <div class="dropdown dropdown-center dropdown-left">
                                        <button tabindex="0" class="btn btn-sm btn-ghost btn-square" aria-label="Page actions">
                                            <i class="fa-solid fa-ellipsis-vertical"></i>
                                        </button>
                                        <ul tabindex="0" class="dropdown-content menu z-30 mt-2 w-56 rounded-box border border-base-300 bg-base-100 p-2 shadow-xl">
                                            <li>
                                                <a href="{{ route('admin.business-models.show', $businessModel) }}">
                                                    <i class="fa-solid fa-eye"></i> View Business Model
                                                </a>
                                            </li>
                                            <li>
                                                <a href="{{ route('admin.business-models.edit', $businessModel) }}">
                                                    <i class="fa-solid fa-pen-to-square"></i> Edit Business Model
                                                </a>
                                            </li>
                                            <li class="menu-title mt-1 px-3 py-1 text-[10px] uppercase tracking-wider">
                                                <span>Danger zone</span>
                                            </li>
                                            <li>
                                                <button
                                                    type="button"
                                                    class="!flex !w-full !max-w-full items-center gap-2 !justify-start rounded-lg px-3 py-2 text-error"
                                                    title="Move Business Model to trash"
                                                    data-admin-action
                                                    data-action-url="{{ route('admin.business-models.destroy', $businessModel) }}"
                                                    data-action-method="DELETE"
                                                    data-action-type="danger"
                                                    data-action-title="Move Business Model to Trash"
                                                    data-action-description="Move “{{ $businessModel->name }}” to trash. You can restore it later from the Trash page."
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
                            <td colspan="7" class="py-12 text-center text-base-content/60">No business models found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{-- ======================= MOBILE CARDS ======================= --}}
        <div class="sm:hidden space-y-3">
            @forelse ($businessModels as $businessModel)
                <div class="rounded-lg border border-base-300 bg-base-100 p-4 shadow-sm">
                    {{-- Card Header --}}
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex min-w-0 items-start gap-2">
                            <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-primary/10 text-xs font-bold text-primary"> {{ str_pad($businessModel->sort_order, 2, '0', STR_PAD_LEFT) }} </span>
                            <div class="min-w-0">
                                <a href="{{ route('admin.business-models.show', $businessModel) }}" class="font-semibold hover:text-primary"> {{ $businessModel->name }} </a>
                                <p class="mt-0.5 break-all text-xs text-base-content/50"> {{ $businessModel->slug }} </p>
                            </div>
                        </div>
                        <span class="badge shrink-0 {{ $businessModel->is_active ? 'badge-success' : 'badge-ghost' }}"> {{ $businessModel->is_active ? 'Active' : 'Inactive' }} </span>
                    </div>
                    {{-- Description --}}
                    @if ($businessModel->description)
                        <p class="mt-3 text-sm text-base-content/70"> {{ $businessModel->description }} </p>
                    @endif
                    <div class="my-3 border-t border-base-300"></div>
                    {{-- Action By --}}
                    <div class="flex items-center justify-between gap-2 text-xs">
                        <span class="text-base-content/50">Last Action By</span>
                        <div class="text-right">
                            <div>
                                @if ($businessModel->updater?->name)
                                    <span class="text-sm font-semibold text-green-500 font-serif"> {{ $businessModel->updater->name }} </span>
                                @elseif ($businessModel->creator?->name)
                                    <span class="text-sm font-semibold text-blue-500 font-serif"> {{ $businessModel->creator->name }} </span>
                                @else
                                    <span class="text-xs text-center text-red-600">Not Defined</span>
                                @endif
                            </div>
                            <div>
                                @if ($businessModel->updater?->role)
                                    <span class="text-sm"> {{ $businessModel->updater->role }} </span>
                                @elseif ($businessModel->creator?->role)
                                    <span class="text-sm"> {{ $businessModel->creator->role }} </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    {{-- Action At --}}
                    <div class="flex items-center justify-between gap-2 text-xs my-3">
                        <span class="text-base-content/50">Last Action Time</span>
                        <div class="text-right">
                            <div class="whitespace-nowrap">
                                <div class="text-sm font-semibold leading-tight tracking-tighter"> {{ $businessModel->updated_at?->format('d M Y, h:i A') }} </div>
                                <div class="text-xs text-base-content/50"> {{ $businessModel->updated_at?->diffForHumans() }} </div>
                            </div>
                        </div>
                    </div>
                    {{-- Actions --}}
                    <div class="flex items-center gap-2 border-t border-base-300 pt-3">
                        <a href="{{ route('admin.business-models.show', $businessModel) }}" class="btn btn-sm btn-outline flex-1">
                            <i class="fa-regular fa-eye"></i> View
                        </a>
                        <a href="{{ route('admin.business-models.edit', $businessModel) }}" class="btn btn-sm btn-outline flex-1">
                            <i class="fa-solid fa-pen-to-square"></i> Edit
                        </a>
                        <button
                            type="button"
                            class="flex-1 btn btn-sm btn-outline btn-error text-error"
                            title="Move to Trash"
                            aria-label="Move {{ $businessModel->name }} to trash"
                            data-admin-action
                            data-action-url="{{ route('admin.business-models.destroy', $businessModel) }}"
                            data-action-method="DELETE"
                            data-action-type="danger"
                            data-action-title="Move Business Model to Trash"
                            data-action-description="Move “{{ $businessModel->name }}” to trash. You can restore it later from the Trash page."
                            data-action-icon="fa-solid fa-trash-can"
                            data-action-confirm-icon="fa-solid fa-trash-can"
                            data-action-confirm-text="Move to Trash"
                        >
                            <i class="fa-solid fa-trash-can"></i> Trash
                        </button>
                    </div>
                </div>
            @empty
                <div class="py-12 text-center text-sm text-base-content/60">No business models found.</div>
            @endforelse
        </div>
        {{-- ======================== PAGINATION ======================== --}}
        @if ($businessModels->hasPages())
            <div class="border-t border-base-300 px-5 py-4"> {{ $businessModels->links() }} </div>
        @endif
    </div>
</div>
@endsection