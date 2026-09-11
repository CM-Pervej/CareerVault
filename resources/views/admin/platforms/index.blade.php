@extends('layouts.admin.app')

@section('title','Platforms | CareerVault')
@section('page_title','Platforms')

@section('content')
<div class="mx-auto max-w-7xl space-y-5">

    {{-- Header --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <div class="cv-admin-label mb-2 text-primary">
                Directory / Platforms
            </div>

            <h1 class="cv-admin-title text-3xl sm:text-4xl">
                Platforms
            </h1>

            <p class="mt-1 text-sm text-base-content/60">
                Manage job platforms, career sources, and their availability.
            </p>
        </div>

        <div class="flex flex-wrap gap-2 self-start sm:self-auto">
            <a
                href="{{ route('admin.platforms.trash') }}"
                class="btn btn-ghost gap-2"
            >
                <i class="fa-solid fa-trash-can"></i>
                Trash

                @php
                    $trashedPlatformsCount=\App\Models\Platform::onlyTrashed()->count();
                @endphp

                @if($trashedPlatformsCount>0)
                    <span class="badge badge-error badge-sm">
                        {{ $trashedPlatformsCount }}
                    </span>
                @endif
            </a>

            <a
                href="{{ route('admin.platforms.create') }}"
                class="btn btn-primary gap-2"
            >
                <i class="fa-solid fa-plus"></i>
                Add Platform
            </a>
        </div>
    </div>


    {{-- Statistics --}}
    <div class="grid grid-cols-2 gap-3 lg:grid-cols-5">

        {{-- Total --}}
        <div class="rounded-2xl border border-base-300 bg-base-100 p-4">
            <div class="flex items-center justify-between">
                <span class="cv-admin-label opacity-50">Total</span>
                <i class="fa-solid fa-layer-group text-primary"></i>
            </div>

            <div class="mt-2 text-2xl font-bold">
                {{ number_format(\App\Models\Platform::count()) }}
            </div>

            <div class="mt-1 text-xs text-base-content/50">
                Registered platforms
            </div>
        </div>


        {{-- Active --}}
        <div class="rounded-2xl border border-base-300 bg-base-100 p-4">
            <div class="flex items-center justify-between">
                <span class="cv-admin-label opacity-50">Active</span>
                <i class="fa-solid fa-circle-check text-success"></i>
            </div>

            <div class="mt-2 text-2xl font-bold">
                {{ number_format(\App\Models\Platform::where('is_active',true)->count()) }}
            </div>

            <div class="mt-1 text-xs text-base-content/50">
                Currently available
            </div>
        </div>


        {{-- Remote --}}
        <div class="rounded-2xl border border-base-300 bg-base-100 p-4">
            <div class="flex items-center justify-between">
                <span class="cv-admin-label opacity-50">Remote</span>
                <i class="fa-solid fa-globe text-info"></i>
            </div>

            <div class="mt-2 text-2xl font-bold">
                {{ number_format(\App\Models\Platform::whereIn('job_type',['Remote','Both'])->count()) }}
            </div>

            <div class="mt-1 text-xs text-base-content/50">
                Remote-friendly
            </div>
        </div>


        {{-- Bangladesh --}}
        <div class="rounded-2xl border border-base-300 bg-base-100 p-4">
            <div class="flex items-center justify-between">
                <span class="cv-admin-label opacity-50">Bangladesh</span>
                <i class="fa-solid fa-flag text-success"></i>
            </div>

            <div class="mt-2 text-2xl font-bold">
                {{ number_format(\App\Models\Platform::where('is_bangladesh_focused',true)->count()) }}
            </div>

            <div class="mt-1 text-xs text-base-content/50">
                Bangladesh focused
            </div>
        </div>


        {{-- Trash --}}
        <a
            href="{{ route('admin.platforms.trash') }}"
            class="group rounded-2xl border border-base-300 bg-base-100 p-4 transition hover:border-error/30 hover:bg-error/[0.03]"
        >
            <div class="flex items-center justify-between">
                <span class="cv-admin-label opacity-50 group-hover:text-error group-hover:opacity-100">
                    Trash
                </span>

                <i class="fa-solid fa-trash-can text-error"></i>
            </div>

            <div class="mt-2 text-2xl font-bold">
                {{ number_format(\App\Models\Platform::onlyTrashed()->count()) }}
            </div>

            <div class="mt-1 text-xs text-base-content/50">
                Deleted platforms
            </div>
        </a>

    </div>


    {{-- Filters --}}
    <div class="rounded-2xl border border-base-300 bg-base-100 p-4 shadow-sm">

        <form
            method="GET"
            action="{{ route('admin.platforms.index') }}"
            class="grid gap-3 lg:grid-cols-[1fr_180px_180px_160px_auto]"
        >

            {{-- Search --}}
            <label class="input input-bordered flex items-center gap-2">
                <i class="fa-solid fa-magnifying-glass text-base-content/40"></i>

                <input
                    type="text"
                    name="search"
                    value="{{ $search }}"
                    placeholder="Search platforms..."
                    class="grow"
                />

                @if($search)
                    <a
                        href="{{ route('admin.platforms.index',request()->except('search')) }}"
                        class="text-base-content/40 hover:text-base-content"
                        title="Clear search"
                    >
                        <i class="fa-solid fa-xmark"></i>
                    </a>
                @endif
            </label>


            {{-- Job Type --}}
            <select
                name="job_type"
                class="select select-bordered w-full"
            >
                <option value="">All job types</option>
                <option value="Onsite" @selected($jobType==='Onsite')>
                    Onsite
                </option>
                <option value="Remote" @selected($jobType==='Remote')>
                    Remote
                </option>
                <option value="Both" @selected($jobType==='Both')>
                    Both
                </option>
            </select>


            {{-- Business --}}
            <select
                name="business_model"
                class="select select-bordered w-full"
            >
                <option value="">All business models</option>
                <option value="Free" @selected($businessModel==='Free')>
                    Free
                </option>
                <option value="Freemium" @selected($businessModel==='Freemium')>
                    Freemium
                </option>
                <option value="Paid" @selected($businessModel==='Paid')>
                    Paid
                </option>
            </select>


            {{-- Status --}}
            <select
                name="status"
                class="select select-bordered w-full"
            >
                <option value="">All statuses</option>
                <option value="active" @selected($status==='active')>
                    Active
                </option>
                <option value="inactive" @selected($status==='inactive')>
                    Inactive
                </option>
            </select>


            <button
                type="submit"
                class="btn btn-primary gap-2"
            >
                <i class="fa-solid fa-filter"></i>
                Filter
            </button>

        </form>


        {{-- Active filters --}}
        @if($search || $jobType || $businessModel || $status)

            <div class="mt-3 flex flex-wrap items-center gap-2 border-t border-base-300 pt-3">

                <span class="text-xs opacity-50">
                    Active filters:
                </span>

                @if($search)
                    <span class="badge badge-primary badge-outline gap-1">
                        Search: {{ $search }}
                    </span>
                @endif

                @if($jobType)
                    <span class="badge badge-info badge-outline">
                        {{ $jobType }}
                    </span>
                @endif

                @if($businessModel)
                    <span class="badge badge-secondary badge-outline">
                        {{ $businessModel }}
                    </span>
                @endif

                @if($status)
                    <span class="badge badge-outline">
                        {{ ucfirst($status) }}
                    </span>
                @endif

                <a
                    href="{{ route('admin.platforms.index') }}"
                    class="link link-error text-xs"
                >
                    Clear all
                </a>

            </div>

        @endif

    </div>


    {{-- Result information --}}
    <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">

        <div class="text-sm text-base-content/60">
            Showing
            <span class="font-semibold text-base-content">
                {{ $platforms->firstItem() ?? 0 }}
            </span>
            –
            <span class="font-semibold text-base-content">
                {{ $platforms->lastItem() ?? 0 }}
            </span>
            of
            <span class="font-semibold text-base-content">
                {{ number_format($platforms->total()) }}
            </span>
            platforms
        </div>

        <div class="text-xs opacity-50">
            Sorted by display order
        </div>

    </div>


    {{-- Desktop Table --}}
    <div class="hidden overflow-hidden rounded-2xl border border-base-300 bg-base-100 shadow-sm lg:block">

        <div class="overflow-x-auto">

            <table class="table">

                <thead>
                    <tr>
                        <th>Platform</th>
                        <th>Job Type</th>
                        <th>Business</th>
                        <th>Location</th>
                        <th>Status</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($platforms as $platform)

                        <tr class="hover">

                            {{-- Platform --}}
                            <td>

                                <div class="flex items-center gap-3">

                                    <div
                                        class="grid h-11 w-11 shrink-0 place-items-center overflow-hidden rounded-xl border border-base-300 bg-base-200"
                                        @if($platform->color)
                                            style="color:{{ $platform->color }}"
                                        @endif
                                    >

                                        @if($platform->logo)
                                            <img
                                                src="{{ $platform->logo }}"
                                                alt="{{ $platform->name }}"
                                                class="h-full w-full object-cover"
                                            >
                                        @elseif($platform->icon)
                                            <i class="{{ $platform->icon }} text-lg"></i>
                                        @else
                                            <span class="text-sm font-bold">
                                                {{ strtoupper(substr($platform->name,0,1)) }}
                                            </span>
                                        @endif

                                    </div>

                                    <div class="min-w-0">

                                        <div class="flex items-center gap-2">

                                            <a
                                                href="{{ route('admin.platforms.show',$platform) }}"
                                                class="font-semibold hover:text-primary"
                                            >
                                                {{ $platform->name }}
                                            </a>

                                            @if($platform->is_bangladesh_focused)
                                                <span
                                                    class="badge badge-success badge-xs"
                                                    title="Bangladesh focused"
                                                >
                                                    BD
                                                </span>
                                            @endif

                                        </div>

                                        @if($platform->official_name)
                                            <div class="max-w-xs truncate text-xs opacity-50">
                                                {{ $platform->official_name }}
                                            </div>
                                        @elseif($platform->short_desc)
                                            <div class="max-w-xs truncate text-xs opacity-50">
                                                {{ $platform->short_desc }}
                                            </div>
                                        @endif

                                    </div>

                                </div>

                            </td>


                            {{-- Job Type --}}
                            <td>

                                @if($platform->job_type==='Remote')

                                    <span class="badge badge-info badge-outline gap-1">
                                        <i class="fa-solid fa-house-laptop text-[10px]"></i>
                                        Remote
                                    </span>

                                @elseif($platform->job_type==='Onsite')

                                    <span class="badge badge-warning badge-outline gap-1">
                                        <i class="fa-solid fa-building text-[10px]"></i>
                                        Onsite
                                    </span>

                                @else

                                    <span class="badge badge-primary badge-outline gap-1">
                                        <i class="fa-solid fa-arrows-left-right text-[10px]"></i>
                                        Both
                                    </span>

                                @endif

                            </td>


                            {{-- Business --}}
                            <td>

                                @if($platform->business_model==='Free')

                                    <span class="badge badge-success badge-outline">
                                        Free
                                    </span>

                                @elseif($platform->business_model==='Freemium')

                                    <span class="badge badge-warning badge-outline">
                                        Freemium
                                    </span>

                                @else

                                    <span class="badge badge-error badge-outline">
                                        Paid
                                    </span>

                                @endif

                            </td>


                            {{-- Location --}}
                            <td>

                                @if($platform->is_bangladesh_focused)

                                    <span class="text-sm font-medium">
                                        Bangladesh
                                    </span>

                                @else

                                    <span class="text-sm opacity-50">
                                        International
                                    </span>

                                @endif

                            </td>


                            {{-- Status --}}
                            <td>

                                @if($platform->is_active)

                                    <span class="badge badge-success gap-1">
                                        <span class="h-1.5 w-1.5 rounded-full bg-current"></span>
                                        Active
                                    </span>

                                @else

                                    <span class="badge badge-ghost gap-1">
                                        <span class="h-1.5 w-1.5 rounded-full bg-current"></span>
                                        Inactive
                                    </span>

                                @endif

                            </td>


                            {{-- Actions --}}
                            <td>

                                <div class="flex justify-end gap-1">

                                    @can('view',$platform)

                                        <a
                                            href="{{ route('admin.platforms.show',$platform) }}"
                                            class="btn btn-ghost btn-sm btn-square"
                                            title="View"
                                        >
                                            <i class="fa-regular fa-eye"></i>
                                        </a>

                                    @endcan


                                    @can('update',$platform)

                                        <a
                                            href="{{ route('admin.platforms.edit',$platform) }}"
                                            class="btn btn-ghost btn-sm btn-square"
                                            title="Edit"
                                        >
                                            <i class="fa-solid fa-pen"></i>
                                        </a>

                                    @endcan


                                    @can('delete',$platform)

                                        <form
                                            method="POST"
                                            action="{{ route('admin.platforms.destroy',$platform) }}"
                                            onsubmit="return confirm('Move {{ addslashes($platform->name) }} to trash?')"
                                        >
                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="btn btn-ghost btn-sm btn-square text-error"
                                                title="Move to Trash"
                                            >
                                                <i class="fa-solid fa-trash-can"></i>
                                            </button>
                                        </form>

                                    @endcan

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="6">

                                <div class="flex flex-col items-center justify-center py-16 text-center">

                                    <div class="grid h-16 w-16 place-items-center rounded-2xl bg-primary/10 text-2xl text-primary">
                                        <i class="fa-solid fa-layer-group"></i>
                                    </div>

                                    <h3 class="mt-4 text-lg font-bold">
                                        No platforms found
                                    </h3>

                                    <p class="mt-1 max-w-md text-sm opacity-50">
                                        Try changing your search or filters, or create
                                        your first platform.
                                    </p>

                                    <div class="mt-5 flex gap-2">

                                        <a
                                            href="{{ route('admin.platforms.index') }}"
                                            class="btn btn-ghost"
                                        >
                                            Clear Filters
                                        </a>

                                        <a
                                            href="{{ route('admin.platforms.create') }}"
                                            class="btn btn-primary gap-2"
                                        >
                                            <i class="fa-solid fa-plus"></i>
                                            Add Platform
                                        </a>

                                    </div>

                                </div>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>


    {{-- Mobile Cards --}}
    <div class="space-y-3 lg:hidden">

        @forelse($platforms as $platform)

            <div class="rounded-2xl border border-base-300 bg-base-100 p-4 shadow-sm">

                <div class="flex items-start gap-3">

                    <div
                        class="grid h-12 w-12 shrink-0 place-items-center overflow-hidden rounded-xl border border-base-300 bg-base-200"
                        @if($platform->color)
                            style="color:{{ $platform->color }}"
                        @endif
                    >

                        @if($platform->logo)

                            <img
                                src="{{ $platform->logo }}"
                                alt="{{ $platform->name }}"
                                class="h-full w-full object-cover"
                            >

                        @elseif($platform->icon)

                            <i class="{{ $platform->icon }} text-lg"></i>

                        @else

                            <span class="font-bold">
                                {{ strtoupper(substr($platform->name,0,1)) }}
                            </span>

                        @endif

                    </div>


                    <div class="min-w-0 flex-1">

                        <div class="flex items-center gap-2">

                            <a
                                href="{{ route('admin.platforms.show',$platform) }}"
                                class="truncate font-semibold hover:text-primary"
                            >
                                {{ $platform->name }}
                            </a>

                            @if($platform->is_bangladesh_focused)
                                <span class="badge badge-success badge-xs shrink-0">
                                    BD
                                </span>
                            @endif

                        </div>

                        <p class="mt-0.5 truncate text-xs opacity-50">
                            {{ $platform->official_name ?: $platform->short_desc ?: 'No description available' }}
                        </p>

                    </div>


                    @if($platform->is_active)

                        <span class="badge badge-success badge-xs shrink-0">
                            Active
                        </span>

                    @else

                        <span class="badge badge-ghost badge-xs shrink-0">
                            Inactive
                        </span>

                    @endif

                </div>


                <div class="mt-4 flex flex-wrap gap-2">

                    @if($platform->job_type==='Remote')

                        <span class="badge badge-info badge-outline gap-1">
                            <i class="fa-solid fa-house-laptop text-[9px]"></i>
                            Remote
                        </span>

                    @elseif($platform->job_type==='Onsite')

                        <span class="badge badge-warning badge-outline gap-1">
                            <i class="fa-solid fa-building text-[9px]"></i>
                            Onsite
                        </span>

                    @else

                        <span class="badge badge-primary badge-outline gap-1">
                            <i class="fa-solid fa-arrows-left-right text-[9px]"></i>
                            Both
                        </span>

                    @endif


                    <span class="badge badge-outline">
                        {{ $platform->business_model }}
                    </span>

                </div>


                <div class="mt-4 grid grid-cols-3 gap-2 border-t border-base-300 pt-3">

                    <a
                        href="{{ route('admin.platforms.show',$platform) }}"
                        class="btn btn-ghost btn-sm gap-1"
                    >
                        <i class="fa-regular fa-eye"></i>
                        View
                    </a>


                    @can('update',$platform)

                        <a
                            href="{{ route('admin.platforms.edit',$platform) }}"
                            class="btn btn-ghost btn-sm gap-1"
                        >
                            <i class="fa-solid fa-pen"></i>
                            Edit
                        </a>

                    @endcan


                    @can('delete',$platform)

                        <form
                            method="POST"
                            action="{{ route('admin.platforms.destroy',$platform) }}"
                            onsubmit="return confirm('Move {{ addslashes($platform->name) }} to trash?')"
                        >
                            @csrf
                            @method('DELETE')

                            <button
                                type="submit"
                                class="btn btn-ghost btn-sm w-full gap-1 text-error"
                            >
                                <i class="fa-solid fa-trash-can"></i>
                                Delete
                            </button>

                        </form>

                    @endcan

                </div>

            </div>

        @empty

            <div class="rounded-2xl border border-base-300 bg-base-100 px-5 py-16 text-center shadow-sm">

                <div class="mx-auto grid h-16 w-16 place-items-center rounded-2xl bg-primary/10 text-2xl text-primary">
                    <i class="fa-solid fa-layer-group"></i>
                </div>

                <h3 class="mt-4 text-lg font-bold">
                    No platforms found
                </h3>

                <p class="mt-1 text-sm opacity-50">
                    Try changing your filters or create a new platform.
                </p>

                <a
                    href="{{ route('admin.platforms.create') }}"
                    class="btn btn-primary mt-5 gap-2"
                >
                    <i class="fa-solid fa-plus"></i>
                    Add Platform
                </a>

            </div>

        @endforelse

    </div>


    {{-- Pagination --}}
    @if($platforms->hasPages())

        <div class="pt-1">
            {{ $platforms->links() }}
        </div>

    @endif

</div>
@endsection