@extends('layouts.admin.app')

@section('title', 'Platform Groups')

@section('content')
<div class="space-y-3 p-4 sm:p-6">
    {{-- ========================= HEADER ========================= --}}
    <x-admin.ui.page-header
        icon="fa-solid fa-users text-lg"    
        title="Platform Groups"
        title-color="text-primary"
        title-bg-color="bg-primary/10 text-primary"
        description="Manage groups and communities associated with job platforms."
        :create-route="route('admin.platform-groups.create')"
        create-text="Add Group"
        :trash-route="route('admin.platform-groups.trash')"
        :trash-count="$trashedGroupsCount"
    />

    {{-- ========================= STATISTICS ========================= --}}
    <details class="cv-section overflow-hidden sm:rounded-lg border border-base-300 bg-base-100 shadow-sm" data-section="groupStates" open>
        <x-admin.ui.disclosure-summary 
            title="Statistics"
            icon="fa-solid fa-chart-simple"
            description="Overview of platform groups and their current status."
        />

        {{-- ===================== OVERVIEW STATS ====================== --}}
        <div class="grid grid-cols-2 sm:grid-cols-2 xl:grid-cols-4 gap-1 sm:p-1 bg-[radial-gradient(circle_at_center,_theme(colors.gray.200),_theme(colors.gray.100))]">
            {{-- Total --}}
            <x-admin.ui.statistic
                title="Groups"
                title-icon="fa-solid fa-users"
                main-icon="fa-solid fa-users"
                main-icon-color="bg-info/10 text-info"
                :total="$totalGroups"
                total-description="Total platform groups"
                last-icon="fa-solid fa-database"
            />

            {{-- Active --}}
            <x-admin.ui.statistic
                title="Active"
                title-icon="fa-solid fa-circle-check"
                main-icon="fa-solid fa-toggle-on"
                main-icon-color="bg-success/10 text-success"
                :total="$activeGroups"
                total-description="Currently active groups"
                last-icon="fa-solid fa-eye"
            />

            {{-- Inactive --}}
            <x-admin.ui.statistic
                title="Inactive"
                title-icon="fa-solid fa-circle-xmark"
                main-icon="fa-solid fa-toggle-off"
                main-icon-color="bg-warning/10 text-warning"
                :total="$inactiveGroups"
                total-description="Hidden or inactive groups"
                last-icon="fa-solid fa-eye-slash"
            />

            {{-- Trash --}}
            <x-admin.ui.statistic
                title="Trash"
                title-icon="fa-solid fa-trash-can"
                main-icon="fa-solid fa-recycle"
                main-icon-color="bg-error/10 text-error"
                :total="$trashedGroupsCount"
                total-description="Soft-deleted groups"
                last-icon="fa-solid fa-clock-rotate-left"
            />
        </div>
    </details>

    {{-- ========================= FILTERS ========================= --}}
    <x-admin.filters.panel
        title="Filter Groups"
        description="Narrow the list by platform, type, access, or status"
        section="groupFilters"
        action="{{ route('admin.platform-groups.index') }}"
        clear-url="{{ route('admin.platform-groups.index') }}"
        result-label="group"
        result-label-plural="groups"
        summary="Platform groups and destinations"
        gridClass="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-7"

        :count="$groups->count()"
        :total="$totalGroups"

        :fields="[
            [
                'type' => 'search',
                'name' => 'search',
                'label' => 'Search',
                'icon' => 'fa-brands fa-searchengin',
                'placeholder' => 'Search pages...',
                'value' => $search,
                'span' => 'col-span-2',
            ],
            [
                'type' => 'select',
                'name' => 'platform',
                'label' => 'Platform',
                'icon' => 'fa-solid fa-layer-group',
                'value' => $platformSlug,
                'options' => $platforms->mapWithKeys(
                    fn ($item) => [$item->slug => $item->name]
                )->toArray(),
            ],
            [
                'type' => 'select',
                'name' => 'group_type',
                'label' => 'Group Type',
                'icon' => 'fa-solid fa-shapes',
                'value' => $groupType,
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
                'name' => 'status',
                'label' => 'Status',
                'icon' => 'fa-solid fa-power-off',
                'value' => $status,
                'options' => [
                    'active' => 'Active',
                    'inactive' => 'Inactive',
                ],
            ],
            [
                'type' => 'select',
                'name' => 'bangladesh_focus',
                'label' => 'Bangladesh Focus',
                'icon' => 'fa-solid fa-flag',
                'value' => $bangladeshFocus,
                'options' => [
                    'focused' => 'Yes',
                    'general' => 'No',
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
                'show' => filled($platformSlug),
                'icon' => 'fa-solid fa-layer-group',
                'label' => 'Platform',
                'value' => $platform->name ?? $platformSlug,
                'color' => 'info',
            ],
            [
                'show' => filled($groupType),
                'icon' => 'fa-solid fa-shapes',
                'label' => 'Group Type',
                'value' => $groupType,
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
                'show' => $status !== null,
                'icon' => 'fa-solid fa-power-off',
                'label' => 'Status',
                'value' => ucfirst($status),
                'color' => 'warning',
            ],
            [
                'show' => filled($bangladeshFocus),
                'icon' => 'fa-solid fa-flag',
                'label' => 'Bangladesh Focus',
                'value' => $bangladeshFocus === 'focused'
                    ? 'Bangladesh Focused'
                    : 'General',
                'color' => 'success',
            ],
        ]"
    />

    {{-- ======================= GROUP DIRECTORY ===================== --}}
    <div class="rounded-lg sm:border sm:border-gray-200 sm:shadow-sm mt-10 sm:mt-5">
        {{-- Directory Header --}}
        <x-admin.ui.platform.heading
            title="Platform Group Directory"
            icon="fa-solid fa-users"
            :file-name="$groups"
            singularFile="group"
            pluralFile="groups"
            description="Grups and destinations associated with job platforms."
        />

        {{-- ======================= DESKTOP TABLE ======================= --}}
        <div class="divide-y divide-base-300">
            @forelse($groups as $group)
                <div class="group relative p-4 transition hover:bg-base-200/30 sm:p-5 border border-gray-300 sm:border-none shadow-sm sm:shadow-none my-2 sm:my-0">
                    <div class="flex flex-col gap-5 xl:flex-row xl:items-center">
                        {{-- ================= PAGE IDENTITY ================= --}}
                        <x-admin.ui.platform.identity
                            :logo="$group->logo"
                            :parent-logo="$group->platform?->logo"
                            :icon="$group->platform->icon"
                            :color="$group->platform->color"
                            :name="$group->name"
                            :parent-name="$group->platform->name"
                            :active="$group->is_active"
                            :file-show="route('admin.platform-groups.show',$group)"
                            :short-desc="$group->short_desc"
                        />

                        {{-- ================= Group DETAILS ================= --}}
                        <x-admin.ui.platform.details
                            title="Group Details"
                            :type="$group->group_type"
                            :color="$group->platform->color"
                            :icon="$group->platform->icon"
                            :parent-name="$group->platform->name"
                            :active="$group->is_active"
                            :access="$group->access_type"
                            :bangla="$group->is_bangladesh_focused"
                        />

                        {{-- ======================= ACTIONS ======================= --}}
                        <x-admin.ui.platform.action
                            :view-url="route('admin.platform-groups.show', $group)"
                            :edit-url="route('admin.platform-groups.edit', $group)"
                            item-name="group"
                            view-title="View"
                            :parent-url="$group->platform ? route('admin.platforms.show', $group->platform) : null"
                            parent-label="View platform"
                            :delete-url="route('admin.platform-groups.destroy', $group->slug)"
                            delete-title="Move Platform Group to Trash"
                            :delete-description="'Move <strong>“' . e($group->name) . '”</strong> to trash. You can restore it later from the Trash Pages.'"
                            :can-delete="auth()->user()->can('delete', $group)"
                        />
                    </div>
                </div>

            @empty
                {{-- ======================= EMPTY STATE ======================= --}}
                <div class="px-6 py-20 text-center">
                    <div class="mx-auto flex size-16 items-center justify-center rounded-2xl bg-base-200 text-base-content/30">
                        <i class="fa-solid fa-file-circle-xmark text-2xl"></i>
                    </div>

                    @if($search || $platformSlug || $groupType || $status)
                        <h3 class="mt-5 text-lg font-black">No matching groups</h3>
                        <p class="mx-auto mt-1 max-w-md text-sm text-base-content/50">No platform groups match your current search and filter combination. Try changing your filters or clearing them.</p>
                        <a href="{{ route('admin.platform-groups.index') }}" class="btn btn-outline mt-5 gap-2">
                            <i class="fa-solid fa-rotate-left"></i> Clear Filters
                        </a>
                    @else
                        <x-admin.ui.empty-state
                            title="No platform groups available"
                            description="Create the first official groups or resource for your platform directory."
                            :action-route="route('admin.platform-groups.create')"
                            action-text="Add Platform Group"
                        />
                    @endif
                </div>
            @endforelse
        </div>

        {{-- ======================== PAGINATION ======================== --}}
        @if($groups->hasPages())
            <div class="border-t border-base-300 px-5 py-4"> {{ $groups->withQueryString()->links() }} </div>
        @endif
    </div>

    {{-- ====================== FOOTER INFORMATION ===================== --}}
    <x-admin.ui.footer-info
        :file-name="$groups"
        description="Platform groups represent <strong>official destinations and resources</strong> maintained in CareerVault."
    />
</div>
@endsection