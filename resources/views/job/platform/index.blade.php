@extends('layouts.app')

@section('title','Platforms | CareerVault')

@section('content')

<div>

    {{-- Header --}}
    <section class="sm:mb-2">
        <div class="relative overflow-hidden sm:rounded-lg bg-gradient-to-br from-indigo-600 via-indigo-600 to-violet-700 px-4 py-5 sm:px-8 sm:py-8 shadow-lg shadow-indigo-900/10">

            {{-- Decorative pattern --}}
            <svg
                class="absolute -top-10 -right-10 w-48 h-48 sm:w-64 sm:h-64 opacity-10 pointer-events-none"
                viewBox="0 0 200 200"
                fill="none"
                xmlns="http://www.w3.org/2000/svg"
            >
                <circle cx="100" cy="100" r="99" stroke="white" stroke-width="1.5"/>
                <circle cx="100" cy="100" r="72" stroke="white" stroke-width="1.5"/>
                <circle cx="100" cy="100" r="45" stroke="white" stroke-width="1.5"/>
            </svg>

            <div class="relative flex flex-col lg:flex-row lg:items-end lg:justify-between gap-5 sm:gap-6">

                {{-- Header Information --}}
                <div class="min-w-0">

                    <p class="text-xs font-semibold tracking-widest uppercase text-indigo-200 mb-2 truncate">
                        <i class="fa-solid fa-earth-americas mr-1"></i>
                        <a href="{{ route('dashboard') }}">
                            CareerVault
                        </a>
                        /
                    </p>

                    <div class="flex items-center gap-2 flex-wrap">
                        <h1 class="text-xl sm:text-2xl md:text-3xl font-bold text-white">
                            Platforms
                        </h1>

                        <sup
                            id="platformTotal"
                            class="rounded-full bg-white/20 text-white text-xs font-bold px-2 py-1 leading-none border border-white/30"
                        >
                            {{ $platforms->total() }}
                        </sup>
                        <span>Add link with platforms to platforms</span>
                    </div>

                    <p class="text-indigo-100/80 text-sm max-w-2xl mt-1">
                        Manage job platforms and keep your job sources organized across your directory.
                    </p>

                    {{-- Quick Stats --}}
                    <div class="flex flex-wrap items-center gap-2 mt-4 sm:mt-5">

                        {{-- Total --}}
                        <div class="flex items-center gap-2 rounded-lg bg-white/10 border border-white/15 px-3 py-2">
                            <i class="fa-solid fa-earth-americas text-indigo-200 text-xs"></i>

                            <span class="text-white text-sm font-semibold">
                                {{ $totalPlatform }}
                            </span>

                            <span class="text-indigo-200 text-xs">
                                total
                            </span>
                        </div>

                        {{-- Showing --}}
                        <div class="flex items-center gap-2 rounded-lg bg-white/10 border border-white/15 px-3 py-2">
                            <i class="fa-solid fa-list-ol text-indigo-200 text-xs"></i>

                            <span
                                id="platformStatShowing"
                                class="text-white text-sm font-semibold"
                            >
                                {{ $platforms->total() ? "{$platforms->firstItem()}–{$platforms->lastItem()}" : '0' }}
                            </span>

                            <span class="text-indigo-200 text-xs">
                                showing
                            </span>
                        </div>

                        {{-- Page --}}
                        <div class="flex items-center gap-2 rounded-lg bg-white/10 border border-white/15 px-3 py-2">
                            <i class="fa-solid fa-layer-group text-indigo-200 text-xs"></i>

                            <span
                                id="platformStatPage"
                                class="text-white text-sm font-semibold"
                            >
                                {{ $platforms->currentPage() }}/{{ max($platforms->lastPage(), 1) }}
                            </span>

                            <span class="text-indigo-200 text-xs">
                                page
                            </span>
                        </div>

                    </div>
                </div>

                {{-- Search + Add --}}
                <div class="flex flex-col sm:flex-row sm:items-center gap-3 w-full lg:w-auto">

                    {{-- Search --}}
                    <div class="w-full sm:w-auto">
                        @include('modular.database_filter', [
                            'id' => 'platformSearch',
                            'url' => route('platforms.index'),
                            'placeholder' => 'Search platform...',
                            'loadingId' => 'platformSearchLoading',
                            'shortcutId' => 'platformSearchShortcut',
                        ])
                    </div>

                    {{-- Add Platform --}}
                    @auth
                        <a
                            href="{{ route('platforms.create') }}"
                            class="hidden sm:inline-flex items-center justify-center gap-2 rounded-sm bg-white hover:bg-indigo-50 text-indigo-700 text-sm font-medium px-4 py-2.5 shadow-sm transition shrink-0 cursor-pointer"
                        >
                            <i class="fa-solid fa-plus"></i>
                            Add Platform
                        </a>
                    @endauth

                </div>

            </div>
        </div>
    </section>


    {{-- Platform Table --}}
    <div class="card bg-transparent sm:bg-base-100 sm:shadow sm:border sm:border-base-300 overflow-hidden p-2 sm:p-0">

        <div class="p-2">

            <div class="overflow-x-auto">

                <table class="table table-zebra w-full">

                    {{-- Table Header --}}
                    <thead class="hidden md:table-header-group">

                        <tr class="cv-eyebrow border-b border-base-300">

                            <th class="w-10">
                                #
                            </th>

                            <th>
                                Platform
                            </th>

                            <th>
                                Type
                            </th>

                            <th>
                                Business
                            </th>

                            <th>
                                Website
                            </th>

                            <th>
                                Status
                            </th>

                            <th class="text-center">
                                Actions
                            </th>

                        </tr>

                    </thead>


                    {{-- Table Body --}}
                    <tbody
                        id="platformContainer"
                        class="block md:table-row-group"
                    >

                        @forelse($platforms as $item)

                            <tr
                                class="crud-row platform-card block md:table-row rounded-xl md:rounded-none border md:border-0 md:border-b border-slate-200 border-b-base-300 last:border-0 mb-3 md:mb-0 py-3 px-1.5 md:p-0 bg-white md:bg-transparent shadow-sm md:shadow-none hover:bg-slate-50"
                            >

                                {{-- Number --}}
                                <td class="hidden md:table-cell cv-mono text-xs opacity-40 py-2.5 text-center">

                                    {{ str_pad(
                                        $loop->iteration + (($platforms->currentPage() - 1) * $platforms->perPage()),
                                        2,
                                        '0',
                                        STR_PAD_LEFT
                                    ) }}

                                </td>


                                {{-- Platform --}}
                                <td class="block md:table-cell px-0 md:px-4 py-2 md:py-3">

                                    <div class="flex items-center gap-3 min-w-0">

                                        {{-- Logo --}}
                                        <div
                                            class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 border border-base-300 bg-base-200 overflow-hidden"
                                            style="{{ $item->color ? 'background-color:'.$item->color.'12' : '' }}"
                                        >

                                            @if($item->logo)

                                                <img
                                                    src="{{ asset('storage/'.$item->logo) }}"
                                                    alt="{{ $item->name }}"
                                                    class="w-full h-full object-contain p-1.5"
                                                    loading="lazy"
                                                >

                                            @elseif($item->icon)

                                                <i
                                                    class="{{ $item->icon }} text-lg font"
                                                    style="{{ $item->color ? 'color:'.$item->color : '' }}"
                                                ></i>

                                            @else

                                                <i class="fa-solid fa-globe text-base-content/40"></i>

                                            @endif

                                        </div>


                                        {{-- Platform Information --}}
                                        <div class="min-w-0 flex-1">

                                            {{-- Name + Badges --}}
                                            <div class="flex items-center gap-2 flex-wrap">

                                                <a
                                                    href="{{ route('platforms.show',$item) }}"
                                                    class="font-semibold truncate hover:text-indigo-600 transition"
                                                >
                                                    {{ $item->name }}
                                                </a>


                                                {{-- Bangladesh --}}
                                                @if($item->is_bangladesh_focused)

                                                    <span
                                                        class="badge badge-xs badge-success shrink-0"
                                                        title="Bangladesh focused"
                                                    >
                                                        BD
                                                    </span>

                                                @else

                                                    <span
                                                        class="badge badge-xs badge-ghost shrink-0 hidden lg:inline-flex"
                                                        title="International platform"
                                                    >
                                                        Global
                                                    </span>

                                                @endif

                                            </div>


                                            {{-- Short Description --}}
                                            {{-- @if($item->short_desc)

                                                <p class="text-xs text-base-content/50 truncate max-w-[280px] xl:max-w-[360px]">
                                                    {{ $item->short_desc }}
                                                </p>

                                            @endif --}}


                                            {{-- Account Requirement --}}
                                            @if($item->account_required)

                                                <div class="mt-1 hidden sm:flex items-center gap-1 text-[10px] text-base-content/45">

                                                    <i class="fa-solid fa-lock text-[9px]"></i>

                                                    <span>
                                                        Account required
                                                    </span>

                                                </div>

                                            @endif

                                        </div>

                                    </div>

                                </td>


                                {{-- Job Type --}}
                                <td
                                    class="flex md:table-cell items-center justify-between md:justify-start gap-2 px-0 md:px-4 py-1.5 md:py-3 before:content-['Type'] before:text-[10px] before:font-semibold before:uppercase before:text-slate-400 before:tracking-wide md:before:content-none md:before:hidden"
                                >

                                    @if($item->job_type === 'Remote')

                                        <span class="badge badge-info badge-outline text-xs">
                                            <i class="fa-solid fa-house text-[9px] mr-1"></i>
                                            Remote
                                        </span>

                                    @elseif($item->job_type === 'Onsite')

                                        <span class="badge badge-warning badge-outline text-xs">
                                            <i class="fa-solid fa-building text-[9px] mr-1"></i>
                                            Onsite
                                        </span>

                                    @else

                                        <span class="badge badge-ghost text-xs">
                                            <i class="fa-solid fa-arrows-left-right text-[9px] mr-1"></i>
                                            Both
                                        </span>

                                    @endif

                                </td>


                                {{-- Business Model --}}
                                <td
                                    class="flex md:table-cell items-center justify-between md:justify-start gap-2 px-0 md:px-4 py-1.5 md:py-3 before:content-['Business'] before:text-[10px] before:font-semibold before:uppercase before:text-slate-400 before:tracking-wide md:before:content-none md:before:hidden"
                                >

                                    @if($item->business_model === 'Free')

                                        <span class="badge badge-success badge-outline text-xs">
                                            Free
                                        </span>

                                    @elseif($item->business_model === 'Freemium')

                                        <span class="badge badge-warning badge-outline text-xs">
                                            Freemium
                                        </span>

                                    @else

                                        <span class="badge badge-secondary badge-outline text-xs">
                                            Paid
                                        </span>

                                    @endif

                                </td>


                                {{-- Website --}}
                                <td
                                    class="flex md:table-cell items-center justify-between md:justify-start gap-2 px-0 md:px-4 py-1.5 md:py-3 before:content-['Website'] before:text-[10px] before:font-semibold before:uppercase before:text-slate-400 before:tracking-wide md:before:content-none md:before:hidden"
                                >

                                    @if($item->base_url)

                                        @php
                                            $host = parse_url($item->base_url, PHP_URL_HOST);
                                            $host = preg_replace('/^www\./', '', $host ?? '');
                                        @endphp

                                        <a
                                            href="{{ $item->base_url }}"
                                            target="_blank"
                                            rel="noopener noreferrer"
                                            class="link link-primary text-sm truncate max-w-[60%] md:max-w-[180px]"
                                            title="{{ $item->base_url }}"
                                        >
                                            {{ $host ?: $item->base_url }}
                                        </a>

                                    @else

                                        <span class="text-base-content/40">
                                            —
                                        </span>

                                    @endif

                                </td>


                                {{-- Status --}}
                                <td
                                    class="flex md:table-cell items-center justify-between md:justify-start gap-2 px-0 md:px-4 py-1.5 md:py-3 before:content-['Status'] before:text-[10px] before:font-semibold before:uppercase before:text-slate-400 before:tracking-wide md:before:content-none md:before:hidden"
                                >

                                    @if($item->is_active)

                                        <span class="badge badge-success badge-outline text-xs gap-1">

                                            <span class="w-1.5 h-1.5 rounded-full bg-current"></span>

                                            Active

                                        </span>

                                    @else

                                        <span class="badge badge-error badge-outline text-xs">
                                            Inactive
                                        </span>

                                    @endif

                                </td>


                                {{-- Actions --}}
                                <td
                                    class="block md:table-cell text-right pl-0 md:pl-2 pr-0 md:pr-4 py-2 md:py-2.5 mt-2 md:mt-0 pt-3 md:pt-2.5 border-t md:border-t-0 border-dashed border-slate-200"
                                >

                                    <div class="flex justify-end flex-wrap gap-1">

                                        {{-- View --}}
                                        <a
                                            href="{{ route('platforms.show',$item) }}"
                                            class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-100 text-xs font-medium px-3 py-1.5 transition hover:text-indigo-600"
                                        >
                                            <i class="fa-solid fa-eye"></i>
                                            View
                                        </a>


                                        {{-- Website --}}
                                        @if($item->base_url)

                                            <a
                                                href="{{ $item->base_url }}"
                                                target="_blank"
                                                rel="noopener noreferrer"
                                                class="hidden sm:inline-flex items-center gap-1.5 rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-100 text-xs font-medium px-3 py-1.5 transition hover:text-indigo-600"
                                            >
                                                <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                                Visit
                                            </a>

                                        @endif


                                        {{-- Edit --}}
                                        @auth

                                            <a
                                                href="{{ route('platforms.edit',$item) }}"
                                                class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-100 text-xs font-medium px-3 py-1.5 transition hover:text-indigo-600"
                                            >
                                                <i class="fa-solid fa-pen"></i>
                                                Edit
                                            </a>


                                            {{-- Delete --}}
                                            <button
                                                type="button"
                                                class="delete-item inline-flex items-center justify-center gap-1.5 rounded-lg border border-rose-100 bg-rose-50 text-rose-600 hover:bg-rose-100 text-xs font-medium px-3 py-1.5 transition cursor-pointer"
                                                data-url="{{ route('platforms.destroy',$item) }}"
                                                data-name="{{ $item->name }}"
                                                data-title="Delete Platform"
                                            >
                                                <i class="fa-solid fa-trash"></i>
                                                Delete
                                            </button>

                                        @endauth

                                    </div>

                                </td>

                            </tr>

                        @empty

                            {{-- No Results --}}
                            <tr class="block md:table-row">

                                <td
                                    colspan="7"
                                    class="block md:table-cell text-center py-16"
                                >

                                    <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-indigo-50 text-indigo-400 mb-4">
                                        <i class="fa-solid fa-layer-group text-2xl"></i>
                                    </div>

                                    <p class="cv-title text-lg">
                                        No platforms found
                                    </p>

                                    <p class="text-sm opacity-50 mb-4">

                                        @if(request('search'))

                                            No platforms match "{{ request('search') }}".

                                        @else

                                            Add a platform to start building your directory.

                                        @endif

                                    </p>


                                    @if(request('search'))

                                        <a
                                            href="{{ route('platforms.index') }}"
                                            class="btn btn-sm btn-outline"
                                        >
                                            <i class="fa-solid fa-rotate-left"></i>
                                            Clear search
                                        </a>

                                    @elseif(auth()->check())

                                        <a
                                            href="{{ route('platforms.create') }}"
                                            class="btn btn-sm btn-primary"
                                        >
                                            <i class="fa-solid fa-plus"></i>
                                            Add Platform
                                        </a>

                                    @endif

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>


    {{-- Pagination --}}
    <div
        id="platformPagination"
        class="mt-8 pb-24 sm:pb-20 md:pb-0 overflow-x-auto"
        data-total="{{ $platforms->total() }}"
        data-per-page="{{ $platforms->perPage() }}"
    >

        {{ $platforms->links() }}

    </div>

</div>


{{-- Mobile Add Platform --}}
@auth

    <a
        href="{{ route('platforms.create') }}"
        class="fixed bottom-5 right-5 z-40 inline-flex items-center justify-center w-14 h-14 rounded-full bg-indigo-600 text-white shadow-lg shadow-indigo-900/30 hover:bg-indigo-700 cursor-pointer"
        aria-label="Add Platform"
    >
        <i class="fa-solid fa-plus text-lg"></i>
    </a>

@endauth


{{-- DELETE CONFIRMATION MODAL --}}
<div>
    @include('modular.delete_modal')
</div>

@endsection