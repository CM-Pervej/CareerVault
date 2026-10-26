@extends('layouts.admin.app')

@section('title', 'Platform Pages')

@section('content')
<div class="space-y-3 p-4 sm:p-6">
    {{-- ========================= HEADER ========================= --}}
    <x-admin.ui.page-header
        icon="fa-solid fa-file-lines text-lg"    
        title="Platform Pages"
        title-color="text-primary"
        title-bg-color="bg-primary/10 text-primary"
        description="Manage pages and destinations associated with job platforms."
        :create-route="route('admin.platform-pages.create')"
        create-text="Add Page"
        :trash-route="route('admin.platform-pages.trash')"
        :trash-count="$trashedPagesCount"
    />

    {{-- ========================= STATISTICS ========================= --}}
    <details class="cv-section overflow-hidden sm:rounded-lg border border-base-300 bg-base-100 shadow-sm" data-section="pageStates" open>
        <x-admin.ui.disclosure-summary 
            title="Statistics"
            icon="fa-solid fa-chart-simple"
            description="Overview of platform pages and their current status."
        />

        {{-- ===================== OVERVIEW STATS ====================== --}}
        <div class="grid grid-cols-2 sm:grid-cols-2 xl:grid-cols-4 gap-1 sm:p-1 bg-[radial-gradient(circle_at_center,_theme(colors.gray.200),_theme(colors.gray.100))]">
            {{-- Total --}}
            <x-admin.ui.statistic
                title="Pages"
                title-icon="fa-solid fa-file-lines"
                main-icon="fa-solid fa-file-lines"
                main-icon-color="bg-info/10 text-info"
                :total="$totalPages"
                total-description="Total platform pages"
                last-icon="fa-solid fa-database"
            />

            {{-- Active --}}
            <x-admin.ui.statistic
                title="Active"
                title-icon="fa-solid fa-circle-check"
                main-icon="fa-solid fa-toggle-on"
                main-icon-color="bg-success/10 text-success"
                :total="$activePages"
                total-description="Currently active pages"
                last-icon="fa-solid fa-eye"
            />

            {{-- Inactive --}}
            <x-admin.ui.statistic
                title="Inactive"
                title-icon="fa-solid fa-circle-xmark"
                main-icon="fa-solid fa-toggle-off"
                main-icon-color="bg-warning/10 text-warning"
                :total="$inactivePages"
                total-description="Currently inactive pages"
                last-icon="fa-solid fa-eye-slash"
            />

            {{-- Trash --}}
            <x-admin.ui.statistic
                title="Trash"
                title-icon="fa-solid fa-trash-can"
                main-icon="fa-solid fa-recycle"
                main-icon-color="bg-error/10 text-error"
                :total="$trashedPagesCount"
                total-description="Soft-deleted pages"
                last-icon="fa-solid fa-clock-rotate-left"
            />
        </div>
    </details>

    {{-- ========================= FILTERS ========================= --}}
    <x-admin.filters.panel
        title="Filter Pages"
        description="Narrow the list by platform, type, access, or status"
        section="pageFilters"
        action="{{ route('admin.platform-pages.index') }}"
        clear-url="{{ route('admin.platform-pages.index') }}"
        result-label="page"
        result-label-plural="pages"
        summary="Platform pages and destinations"
        gridClass="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-7"

        :count="$pages->count()"
        :total="$totalPages"

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
                'name' => 'page_type',
                'label' => 'Page Type',
                'icon' => 'fa-solid fa-shapes',
                'value' => $pageType,
                'options' => collect($pageTypes)->mapWithKeys(
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
                'show' => filled($pageType),
                'icon' => 'fa-solid fa-shapes',
                'label' => 'Page Type',
                'value' => $pageType,
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

    {{-- ======================= PAGE DIRECTORY ===================== --}}
    <div class="rounded-lg sm:border sm:border-gray-200 sm:shadow-sm mt-10 sm:mt-5">
        {{-- Directory Header --}}
        <x-admin.ui.platform.heading
            title="Platform Page Directory"
            icon="fa-solid fa-file-lines"
            :file-name="$pages"
            singularFile="page"
            pluralFile="pages"
            description="Pages and destinations associated with job platforms."
        />

        {{-- ======================= TABLE ======================= --}}
        <div class="divide-y divide-base-300">
            @forelse($pages as $page)
                <div class="group relative p-4 transition hover:bg-base-200/30 sm:p-5 border border-gray-300 sm:border-none shadow-sm sm:shadow-none my-2 sm:my-0">
                    <div class="flex flex-col gap-5 xl:flex-row xl:items-center">
                        {{-- ================= PAGE IDENTITY ================= --}}
                        <x-admin.ui.platform.identity
                            :logo="$page->logo"
                            :parent-logo="$page->platform?->logo"
                            :icon="$page->platform->icon"
                            :color="$page->platform->color"
                            :name="$page->name"
                            :parent-name="$page->platform->name"
                            :active="$page->is_active"
                            :file-show="route('admin.platform-pages.show',$page)"
                            :short-desc="$page->short_desc"
                        />

                        {{-- ================= PAGE DETAILS ================= --}}
                        <x-admin.ui.platform.details
                            title="Page Details"
                            :type="$page->page_type"
                            :color="$page->platform->color"
                            :icon="$page->platform->icon"
                            :parent-name="$page->platform->name"
                            :active="$page->is_active"
                            :access="$page->access_type"
                            :bangla="$page->is_bangladesh_focused"
                        />

                        {{-- ======================= ACTIONS ======================= --}}
                        <x-admin.ui.platform.action
                            :view-url="route('admin.platform-pages.show', $page)"
                            :edit-url="route('admin.platform-pages.edit', $page)"
                            item-name="page"
                            view-title="View"
                            :parent-url="$page->platform ? route('admin.platforms.show', $page->platform) : null"
                            parent-label="View platform"
                            :delete-url="route('admin.platform-pages.destroy', $page->slug)"
                            delete-title="Move Platform Page to Trash"
                            :delete-description="'Move <strong>“' . e($page->name) . '”</strong> to trash. You can restore it later from the Trash Pages.'"
                            :can-delete="auth()->user()->can('delete', $page)"
                        />
                    </div>
                </div>

            @empty
                {{-- ======================= EMPTY STATE ======================= --}}
                <div class="px-6 py-20 text-center">
                    <div class="mx-auto flex size-16 items-center justify-center rounded-2xl bg-base-200 text-base-content/30">
                        <i class="fa-solid fa-file-circle-xmark text-2xl"></i>
                    </div>

                    @if($search || $platformSlug || $pageType || $accessType || $status !== null || $bangladeshFocus)
                        <h3 class="mt-5 text-lg font-black">No matching pages</h3>
                        <p class="mx-auto mt-1 max-w-md text-sm text-base-content/50">No platform pages match your current search and filter combination. Try changing your filters or clearing them.</p>
                        <a href="{{ route('admin.platform-pages.index') }}" class="btn btn-outline mt-5 gap-2">
                            <i class="fa-solid fa-rotate-left"></i> Clear Filters
                        </a>
                    @else
                        <x-admin.ui.empty-state
                            title="No platform pages available"
                            description="Create the first official page or resource for your platform directory."
                            :action-route="route('admin.platform-pages.create')"
                            action-text="Add Platform Page"
                        />
                    @endif
                </div>
            @endforelse
        </div>

        {{-- ======================== PAGINATION ======================== --}}
        @if($pages->hasPages())
            <div class="border-t border-base-300 px-5 py-4"> {{ $pages->withQueryString()->links() }} </div>
        @endif
    </div>

    {{-- ====================== FOOTER INFORMATION ===================== --}}
    <x-admin.ui.footer-info
        :file-name="$pages"
        description="Platform pages represent <strong>official destinations and resources</strong> maintained in CareerVault."
    />
</div>
@endsection