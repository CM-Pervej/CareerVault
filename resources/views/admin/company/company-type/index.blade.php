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

        <section class="overflow-hidden rounded-xl border border-base-300 bg-base-100 shadow-sm">
            <div class="overflow-x-auto">
                <table class="table table-zebra">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Slug</th>
                            <th>Status</th>
                            <th>Sort Order</th>
                            <th>Updated By</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($companyTypes as $item)
                            <tr>
                                <td class="min-w-48">
                                    <a href="{{ route('admin.company-types.show', $item) }}"
                                        class="font-semibold hover:text-primary">
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
                                <td>{{ $item->sort_order }}</td>
                                <td>{{ $item->updatedBy?->name ?? '—' }}</td>
                                <td>
                                    <div class="flex justify-end gap-1">
                                        <a href="{{ route('admin.company-types.show', $item) }}"
                                            class="btn btn-ghost btn-xs">View</a>
                                        <a href="{{ route('admin.company-types.edit', $item) }}"
                                            class="btn btn-ghost btn-xs">Edit</a>

                                        <form method="POST"
                                            action="{{ route('admin.company-types.destroy', $item) }}"
                                            onsubmit="return confirm('Move this company type to trash?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-ghost btn-xs text-error">
                                                Delete
                                            </button>
                                        </form>
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

            <div class="border-t border-base-300 p-4">
                {{ $companyTypes->links() }}
            </div>
        </section>
    </div>
@endsection
