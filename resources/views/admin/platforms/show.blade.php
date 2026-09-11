@extends('layouts.admin.app')

@section('title', $platform->name . ' | Platforms | CareerVault')

@section('content')
<div
    x-data="{ activeTab: 'overview' }"
    class="space-y-6"
>

    {{-- =========================================================
        PLATFORM HEADER
    ========================================================== --}}
    <section class="overflow-hidden rounded-2xl border border-base-300 bg-base-100 shadow-sm">

        {{-- Cover --}}
        <div
            class="relative h-36 sm:h-44"
            style="background: linear-gradient(135deg, {{ $platform->color ?: '#4f46e5' }} 0%, #111827 100%);"
        >
            @if($platform->cover_image)
                <img
                    src="{{ Storage::url($platform->cover_image) }}"
                    alt="{{ $platform->name }}"
                    class="absolute inset-0 size-full object-cover opacity-45"
                >
                <div class="absolute inset-0 bg-black/30"></div>
            @endif

            <div class="absolute inset-x-0 top-0 flex items-center justify-between p-4 sm:p-5">
                <div class="breadcrumbs text-sm text-white/80">
                    <ul>
                        <li>
                            <a
                                href="{{ route('admin.platforms.index') }}"
                                class="hover:text-white"
                            >
                                Platforms
                            </a>
                        </li>
                        <li class="text-white">
                            {{ $platform->name }}
                        </li>
                    </ul>
                </div>

                <div class="flex items-center gap-2">
                    <a
                        href="{{ route('admin.platforms.edit', $platform) }}"
                        class="btn btn-sm border-white/20 bg-white/10 text-white backdrop-blur hover:bg-white/20"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 3.487a2.25 2.25 0 013.182 3.182L8.25 18.464 4 19.5l1.036-4.25L16.862 3.487z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 5l3 3"/>
                        </svg>
                        <span class="hidden sm:inline">Edit</span>
                    </a>

                    <a
                        href="{{ route('admin.platforms.index') }}"
                        class="btn btn-sm border-white/20 bg-white/10 text-white backdrop-blur hover:bg-white/20"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                        </svg>
                        <span class="hidden sm:inline">Back</span>
                    </a>
                </div>
            </div>
        </div>


        {{-- Platform identity --}}
        <div class="relative px-5 pb-5 sm:px-7 sm:pb-6">

            <div class="-mt-12 flex flex-col gap-5 sm:-mt-14 sm:flex-row sm:items-end">

                {{-- Logo --}}
                <div class="relative shrink-0">
                    <div class="flex size-24 items-center justify-center overflow-hidden rounded-2xl border-4 border-base-100 bg-base-200 shadow-xl sm:size-28">

                        @if($platform->logo)
                            <img
                                src="{{ Storage::url($platform->logo) }}"
                                alt="{{ $platform->name }}"
                                class="size-full object-contain"
                            >
                        @elseif($platform->icon)
                            <span
                                class="text-4xl"
                                style="color: {{ $platform->color ?: 'currentColor' }}"
                            >
                                {!! $platform->icon !!}
                            </span>
                        @else
                            <span
                                class="text-4xl font-black"
                                style="color: {{ $platform->color ?: '#4f46e5' }}"
                            >
                                {{ strtoupper(substr($platform->name, 0, 1)) }}
                            </span>
                        @endif

                    </div>

                    @if($platform->is_active)
                        <span class="absolute -bottom-1 -right-1 flex size-7 items-center justify-center rounded-full border-4 border-base-100 bg-success">
                            <span class="size-2 rounded-full bg-success-content"></span>
                        </span>
                    @endif
                </div>


                {{-- Identity --}}
                <div class="min-w-0 flex-1 pb-1">

                    <div class="flex flex-wrap items-center gap-2">
                        <h1 class="text-2xl font-black tracking-tight sm:text-3xl">
                            {{ $platform->name }}
                        </h1>

                        @if($platform->is_active)
                            <span class="badge badge-success badge-sm font-semibold">
                                Active
                            </span>
                        @else
                            <span class="badge badge-error badge-sm font-semibold">
                                Inactive
                            </span>
                        @endif

                        @if($platform->is_bangladesh_focused)
                            <span class="badge badge-info badge-sm font-semibold">
                                Bangladesh Focused
                            </span>
                        @endif
                    </div>

                    @if($platform->official_name)
                        <p class="mt-1 text-sm font-medium text-base-content/55">
                            {{ $platform->official_name }}
                        </p>
                    @endif

                    @if($platform->short_desc)
                        <p class="mt-2 max-w-3xl text-sm leading-6 text-base-content/65">
                            {{ $platform->short_desc }}
                        </p>
                    @endif

                </div>

            </div>


            {{-- =================================================
                SECTION NAVIGATION
            ================================================== --}}
            <div class="mt-6 border-t border-base-200 pt-4">

                <div class="flex overflow-x-auto rounded-xl bg-base-200/60 p-1">

                    {{-- Overview --}}
                    <button
                        type="button"
                        @click="activeTab = 'overview'"
                        :class="activeTab === 'overview'
                            ? 'bg-base-100 text-primary shadow-sm'
                            : 'text-base-content/60 hover:bg-base-100/60 hover:text-base-content'"
                        class="flex shrink-0 items-center gap-2 rounded-lg px-4 py-2.5 text-sm font-bold transition"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h10"/>
                        </svg>
                        Overview
                    </button>


                    {{-- Official Presence --}}
                    <button
                        type="button"
                        @click="activeTab = 'presence'"
                        :class="activeTab === 'presence'
                            ? 'bg-base-100 text-primary shadow-sm'
                            : 'text-base-content/60 hover:bg-base-100/60 hover:text-base-content'"
                        class="flex shrink-0 items-center gap-2 rounded-lg px-4 py-2.5 text-sm font-bold transition"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6.5a4 4 0 11-5.657 5.657L4 16v4h4l3.843-3.843a4 4 0 005.657-5.657z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14 10l4 4"/>
                        </svg>
                        Official Presence
                        @if($platform->connectedPlatforms->count())
                            <span class="badge badge-primary badge-xs">
                                {{ $platform->connectedPlatforms->count() }}
                            </span>
                        @endif
                    </button>


                    {{-- Companies --}}
                    <button
                        type="button"
                        @click="activeTab = 'companies'"
                        :class="activeTab === 'companies'
                            ? 'bg-base-100 text-primary shadow-sm'
                            : 'text-base-content/60 hover:bg-base-100/60 hover:text-base-content'"
                        class="flex shrink-0 items-center gap-2 rounded-lg px-4 py-2.5 text-sm font-bold transition"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 21h18M5 21V9l7-4 7 4v12M9 21v-4h6v4"/>
                        </svg>
                        Linked Companies
                        @if($platform->companies->count())
                            <span class="badge badge-neutral badge-xs">
                                {{ $platform->companies->count() }}
                            </span>
                        @endif
                    </button>


                    {{-- Platform Pages --}}
                    <button
                        type="button"
                        @click="activeTab = 'pages'"
                        :class="activeTab === 'pages'
                            ? 'bg-base-100 text-primary shadow-sm'
                            : 'text-base-content/60 hover:bg-base-100/60 hover:text-base-content'"
                        class="flex shrink-0 items-center gap-2 rounded-lg px-4 py-2.5 text-sm font-bold transition"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 4.5A1.5 1.5 0 017.5 3h9A1.5 1.5 0 0118 4.5v15a1.5 1.5 0 01-1.5 1.5h-9A1.5 1.5 0 016 19.5v-15z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 7h6M9 11h6M9 15h4"/>
                        </svg>
                        Platform Pages
                        @if($platform->platformPages->count())
                            <span class="badge badge-neutral badge-xs">
                                {{ $platform->platformPages->count() }}
                            </span>
                        @endif
                    </button>

                </div>
            </div>

        </div>
    </section>


    {{-- =========================================================
        CONTENT
    ========================================================== --}}
    <div class="min-h-[500px]">

        {{-- =====================================================
            OVERVIEW
        ====================================================== --}}
        <section
            x-show="activeTab === 'overview'"
            x-cloak
            x-transition.opacity.duration.150ms
        >
            <div class="grid gap-6 lg:grid-cols-[minmax(0,1fr)_340px]">

                {{-- Main overview --}}
                <div class="space-y-6">

                    {{-- Description --}}
                    <div class="rounded-2xl border border-base-300 bg-base-100 shadow-sm">

                        <div class="border-b border-base-200 px-5 py-4 sm:px-6">
                            <p class="text-xs font-bold uppercase tracking-wider text-primary">
                                Platform overview
                            </p>

                            <h2 class="mt-1 text-xl font-black tracking-tight">
                                About {{ $platform->name }}
                            </h2>
                        </div>

                        <div class="p-5 sm:p-6">

                            @if($platform->description)
                                <div class="max-w-none text-sm leading-7 text-base-content/75">
                                    {!! nl2br(e($platform->description)) !!}
                                </div>
                            @else
                                <div class="rounded-xl border border-dashed border-base-300 bg-base-200/30 px-5 py-8 text-center">
                                    <p class="text-sm text-base-content/50">
                                        No detailed description has been added.
                                    </p>
                                </div>
                            @endif

                        </div>
                    </div>


                    {{-- Every platform field --}}
                    <div class="rounded-2xl border border-base-300 bg-base-100 shadow-sm">

                        <div class="border-b border-base-200 px-5 py-4 sm:px-6">
                            <p class="text-xs font-bold uppercase tracking-wider text-primary">
                                Complete record
                            </p>

                            <h2 class="mt-1 text-xl font-black tracking-tight">
                                Platform information
                            </h2>
                        </div>

                        <div class="grid sm:grid-cols-2">

                            {{-- Name --}}
                            <div class="border-b border-base-200 p-5 sm:border-r sm:px-6">
                                <p class="text-xs font-semibold text-base-content/45">
                                    Name
                                </p>
                                <p class="mt-1 font-bold">
                                    {{ $platform->name }}
                                </p>
                            </div>

                            {{-- Official Name --}}
                            <div class="border-b border-base-200 p-5 sm:px-6">
                                <p class="text-xs font-semibold text-base-content/45">
                                    Official Name
                                </p>
                                <p class="mt-1 font-semibold">
                                    {{ $platform->official_name ?: '—' }}
                                </p>
                            </div>

                            {{-- Slug --}}
                            <div class="border-b border-base-200 p-5 sm:border-r sm:px-6">
                                <p class="text-xs font-semibold text-base-content/45">
                                    Slug
                                </p>
                                <p class="mt-1 break-all font-mono text-sm font-semibold">
                                    {{ $platform->slug }}
                                </p>
                            </div>

                            {{-- ID --}}
                            <div class="border-b border-base-200 p-5 sm:px-6">
                                <p class="text-xs font-semibold text-base-content/45">
                                    Database ID
                                </p>
                                <p class="mt-1 font-mono font-semibold">
                                    #{{ $platform->id }}
                                </p>
                            </div>

                            {{-- Job Type --}}
                            <div class="border-b border-base-200 p-5 sm:border-r sm:px-6">
                                <p class="text-xs font-semibold text-base-content/45">
                                    Job Type
                                </p>
                                <p class="mt-1 font-semibold">
                                    {{ $platform->job_type }}
                                </p>
                            </div>

                            {{-- Business Model --}}
                            <div class="border-b border-base-200 p-5 sm:px-6">
                                <p class="text-xs font-semibold text-base-content/45">
                                    Business Model
                                </p>
                                <p class="mt-1 font-semibold">
                                    {{ $platform->business_model }}
                                </p>
                            </div>

                            {{-- Account --}}
                            <div class="border-b border-base-200 p-5 sm:border-r sm:px-6">
                                <p class="text-xs font-semibold text-base-content/45">
                                    Account Required
                                </p>
                                <p class="mt-1 font-semibold">
                                    {{ $platform->account_required ? 'Yes' : 'No' }}
                                </p>
                            </div>

                            {{-- Bangladesh --}}
                            <div class="border-b border-base-200 p-5 sm:px-6">
                                <p class="text-xs font-semibold text-base-content/45">
                                    Bangladesh Focused
                                </p>
                                <p class="mt-1 font-semibold">
                                    {{ $platform->is_bangladesh_focused ? 'Yes' : 'No' }}
                                </p>
                            </div>

                            {{-- Founded --}}
                            <div class="border-b border-base-200 p-5 sm:border-r sm:px-6">
                                <p class="text-xs font-semibold text-base-content/45">
                                    Founded
                                </p>
                                <p class="mt-1 font-semibold">
                                    @if($platform->founded_year)
                                        {{ $platform->founded_month ? \Carbon\Carbon::create()->month($platform->founded_month)->format('F') . ' ' : '' }}{{ $platform->founded_year }}
                                    @else
                                        —
                                    @endif
                                </p>
                            </div>

                            {{-- Sort --}}
                            <div class="border-b border-base-200 p-5 sm:px-6">
                                <p class="text-xs font-semibold text-base-content/45">
                                    Sort Order
                                </p>
                                <p class="mt-1 font-mono font-semibold">
                                    {{ $platform->sort_order }}
                                </p>
                            </div>

                            {{-- Status --}}
                            <div class="border-b border-base-200 p-5 sm:border-r sm:px-6">
                                <p class="text-xs font-semibold text-base-content/45">
                                    Status
                                </p>
                                <div class="mt-1">
                                    @if($platform->is_active)
                                        <span class="badge badge-success badge-sm font-semibold">
                                            Active
                                        </span>
                                    @else
                                        <span class="badge badge-error badge-sm font-semibold">
                                            Inactive
                                        </span>
                                    @endif
                                </div>
                            </div>

                            {{-- Last Verified --}}
                            <div class="border-b border-base-200 p-5 sm:px-6">
                                <p class="text-xs font-semibold text-base-content/45">
                                    Last Verified
                                </p>
                                <p class="mt-1 font-semibold">
                                    {{ $platform->last_verified_at?->format('M d, Y h:i A') ?: 'Not verified' }}
                                </p>
                            </div>

                            {{-- Created --}}
                            <div class="p-5 sm:border-r sm:px-6">
                                <p class="text-xs font-semibold text-base-content/45">
                                    Created
                                </p>
                                <p class="mt-1 font-semibold">
                                    {{ $platform->created_at?->format('M d, Y h:i A') }}
                                </p>
                            </div>

                            {{-- Updated --}}
                            <div class="p-5 sm:px-6">
                                <p class="text-xs font-semibold text-base-content/45">
                                    Last Updated
                                </p>
                                <p class="mt-1 font-semibold">
                                    {{ $platform->updated_at?->format('M d, Y h:i A') }}
                                </p>
                            </div>

                        </div>
                    </div>

                </div>


                {{-- Overview sidebar --}}
                <aside class="space-y-6">

                    {{-- Platform links --}}
                    <div class="rounded-2xl border border-base-300 bg-base-100 p-5 shadow-sm">

                        <div class="mb-4">
                            <p class="text-xs font-bold uppercase tracking-wider text-primary">
                                External resources
                            </p>

                            <h2 class="mt-1 text-lg font-black">
                                Platform links
                            </h2>
                        </div>

                        <div class="space-y-2">

                            {{-- Base URL --}}
                            @if($platform->base_url)
                                <a
                                    href="{{ $platform->base_url }}"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="group flex items-center gap-3 rounded-xl border border-base-200 p-3 transition hover:border-primary/30 hover:bg-primary/5"
                                >
                                    <div class="flex size-10 shrink-0 items-center justify-center rounded-lg bg-primary/10 text-primary">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <circle cx="12" cy="12" r="9"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 12h18M12 3a14 14 0 010 18M12 3a14 14 0 000 18"/>
                                        </svg>
                                    </div>

                                    <div class="min-w-0 flex-1">
                                        <p class="font-bold">
                                            Official Website
                                        </p>
                                        <p class="truncate text-xs text-base-content/45">
                                            {{ $platform->base_url }}
                                        </p>
                                    </div>

                                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4 shrink-0 opacity-30 group-hover:text-primary group-hover:opacity-100" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M14 5h5v5M19 5l-9 9"/>
                                    </svg>
                                </a>
                            @endif


                            {{-- Job URL --}}
                            @if($platform->job_url)
                                <a
                                    href="{{ $platform->job_url }}"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="group flex items-center gap-3 rounded-xl border border-base-200 p-3 transition hover:border-success/30 hover:bg-success/5"
                                >
                                    <div class="flex size-10 shrink-0 items-center justify-center rounded-lg bg-success/10 text-success">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2h8"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 17l3 3m0 0l3-3m-3 3v-7"/>
                                        </svg>
                                    </div>

                                    <div class="min-w-0 flex-1">
                                        <p class="font-bold">
                                            Career / Job Listings
                                        </p>
                                        <p class="truncate text-xs text-base-content/45">
                                            {{ $platform->job_url }}
                                        </p>
                                    </div>

                                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4 shrink-0 opacity-30 group-hover:text-success group-hover:opacity-100" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M14 5h5v5M19 5l-9 9"/>
                                    </svg>
                                </a>
                            @endif


                            @if(!$platform->base_url && !$platform->job_url)
                                <div class="rounded-xl border border-dashed border-base-300 px-4 py-7 text-center">
                                    <p class="text-sm text-base-content/50">
                                        No platform links recorded.
                                    </p>
                                </div>
                            @endif

                        </div>
                    </div>


                    {{-- Brand --}}
                    <div class="rounded-2xl border border-base-300 bg-base-100 p-5 shadow-sm">

                        <p class="text-xs font-bold uppercase tracking-wider text-primary">
                            Brand
                        </p>

                        <h2 class="mt-1 text-lg font-black">
                            Visual identity
                        </h2>

                        <div class="mt-4 flex items-center gap-4">

                            <div
                                class="size-14 rounded-xl border border-base-300 shadow-inner"
                                style="background-color: {{ $platform->color ?: '#4f46e5' }};"
                            ></div>

                            <div>
                                <p class="font-mono text-sm font-bold">
                                    {{ $platform->color ?: 'Default' }}
                                </p>

                                <p class="mt-1 text-xs text-base-content/45">
                                    Platform color
                                </p>
                            </div>

                        </div>
                    </div>

                </aside>

            </div>
        </section>


        {{-- =====================================================
            OFFICIAL PRESENCE
        ====================================================== --}}
        <section
            x-show="activeTab === 'presence'"
            x-cloak
            x-transition.opacity.duration.150ms
        >
            <div class="rounded-2xl border border-base-300 bg-base-100 shadow-sm">

                <div class="border-b border-base-200 px-5 py-5 sm:px-7">
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

                        <div>
                            <p class="text-xs font-bold uppercase tracking-wider text-primary">
                                Cross-platform identity
                            </p>

                            <h2 class="mt-1 text-2xl font-black tracking-tight">
                                Official presence
                            </h2>

                            <p class="mt-1 text-sm text-base-content/55">
                                Official accounts and profiles belonging to this platform across other platforms.
                            </p>
                        </div>

                        <a
                            href="{{ route('admin.platform-connections.edit', $platform) }}"
                            class="btn btn-primary"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 3.487a2.25 2.25 0 013.182 3.182L8.25 18.464 4 19.5l1.036-4.25L16.862 3.487z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 5l3 3"/>
                            </svg>
                            Manage Presence
                        </a>

                    </div>
                </div>


                <div class="p-5 sm:p-7">

                    @if($platform->connectedPlatforms->count())

                        <div class="mb-6 flex items-center gap-2">
                            <span class="badge badge-primary font-bold">
                                {{ $platform->connectedPlatforms->count() }}
                            </span>

                            <h3 class="font-black">
                                Presence on other platforms
                            </h3>
                        </div>

                        <div class="grid gap-3 sm:grid-cols-2">

                            @foreach($platform->connectedPlatforms as $connected)

                                <div class="group rounded-2xl border border-base-200 bg-base-200/20 p-4 transition hover:border-primary/30 hover:bg-primary/5">

                                    <div class="flex items-start gap-4">

                                        <div class="flex size-12 shrink-0 items-center justify-center overflow-hidden rounded-xl bg-base-100 shadow-sm">

                                            @if($connected->logo)
                                                <img
                                                    src="{{ Storage::url($connected->logo) }}"
                                                    alt="{{ $connected->name }}"
                                                    class="size-full object-contain"
                                                >
                                            @elseif($connected->icon)
                                                <span
                                                    class="text-xl"
                                                    style="color: {{ $connected->color ?: 'currentColor' }}"
                                                >
                                                    {!! $connected->icon !!}
                                                </span>
                                            @else
                                                <span class="text-lg font-black">
                                                    {{ strtoupper(substr($connected->name, 0, 1)) }}
                                                </span>
                                            @endif

                                        </div>


                                        <div class="min-w-0 flex-1">

                                            <div class="flex items-start justify-between gap-2">

                                                <div class="min-w-0">
                                                    <a
                                                        href="{{ route('admin.platforms.show', $connected) }}"
                                                        class="font-black hover:text-primary"
                                                    >
                                                        {{ $connected->name }}
                                                    </a>

                                                    <p class="mt-0.5 text-xs text-base-content/45">
                                                        Official platform account
                                                    </p>
                                                </div>

                                                @if($connected->pivot->account_url)
                                                    <a
                                                        href="{{ $connected->pivot->account_url }}"
                                                        target="_blank"
                                                        rel="noopener noreferrer"
                                                        class="btn btn-circle btn-sm btn-ghost"
                                                        title="Open official profile"
                                                    >
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M14 5h5v5M19 5l-9 9"/>
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 13v4a2 2 0 01-2 2H7a2 2 0 01-2-2V7a2 2 0 012-2h4"/>
                                                        </svg>
                                                    </a>
                                                @endif

                                            </div>

                                            @if($connected->pivot->account_url)
                                                <p class="mt-3 truncate rounded-lg bg-base-100 px-3 py-2 font-mono text-xs text-base-content/55">
                                                    {{ $connected->pivot->account_url }}
                                                </p>
                                            @else
                                                <p class="mt-3 text-xs italic text-base-content/40">
                                                    No profile URL recorded.
                                                </p>
                                            @endif

                                        </div>

                                    </div>

                                </div>

                            @endforeach

                        </div>

                    @else

                        <div class="rounded-2xl border border-dashed border-base-300 bg-base-200/20 px-5 py-14 text-center">

                            <div class="mx-auto flex size-14 items-center justify-center rounded-2xl bg-base-200">
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-7 text-base-content/40" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6.5a4 4 0 11-5.657 5.657L4 16v4h4l3.843-3.843a4 4 0 005.657-5.657z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14 10l4 4"/>
                                </svg>
                            </div>

                            <h3 class="mt-4 font-black">
                                No official presence yet
                            </h3>

                            <p class="mx-auto mt-1 max-w-md text-sm text-base-content/50">
                                Record this platform's official accounts or profiles on LinkedIn, Facebook, X, GitHub, or other platforms.
                            </p>

                            <a
                                href="{{ route('admin.platform-connections.edit', $platform) }}"
                                class="btn btn-primary mt-5"
                            >
                                Add Official Presence
                            </a>

                        </div>

                    @endif


                    {{-- Incoming presence --}}
                    @if($platform->connectedFromPlatforms->count())

                        <div class="mt-8 border-t border-base-200 pt-8">

                            <div class="mb-5">
                                <p class="text-xs font-bold uppercase tracking-wider text-secondary">
                                    Reverse relationships
                                </p>

                                <h3 class="mt-1 text-lg font-black">
                                    Other platforms present here
                                </h3>

                                <p class="mt-1 text-sm text-base-content/50">
                                    Platforms that have recorded an official presence on {{ $platform->name }}.
                                </p>
                            </div>

                            <div class="space-y-2">

                                @foreach($platform->connectedFromPlatforms as $connected)

                                    <div class="flex items-center gap-3 rounded-xl border border-base-200 p-3">

                                        <div class="flex size-10 shrink-0 items-center justify-center overflow-hidden rounded-lg bg-base-200">

                                            @if($connected->logo)
                                                <img
                                                    src="{{ Storage::url($connected->logo) }}"
                                                    alt="{{ $connected->name }}"
                                                    class="size-full object-contain"
                                                >
                                            @elseif($connected->icon)
                                                <span
                                                    style="color: {{ $connected->color ?: 'currentColor' }}"
                                                >
                                                    {!! $connected->icon !!}
                                                </span>
                                            @else
                                                <span class="font-black">
                                                    {{ strtoupper(substr($connected->name, 0, 1)) }}
                                                </span>
                                            @endif

                                        </div>

                                        <div class="min-w-0 flex-1">
                                            <a
                                                href="{{ route('admin.platforms.show', $connected) }}"
                                                class="font-bold hover:text-primary"
                                            >
                                                {{ $connected->name }}
                                            </a>

                                            <p class="text-xs text-base-content/45">
                                                Official presence recorded here
                                            </p>
                                        </div>

                                        @if($connected->pivot->account_url)
                                            <a
                                                href="{{ $connected->pivot->account_url }}"
                                                target="_blank"
                                                rel="noopener noreferrer"
                                                class="btn btn-sm btn-ghost"
                                            >
                                                Open
                                            </a>
                                        @endif

                                    </div>

                                @endforeach

                            </div>

                        </div>

                    @endif

                </div>

            </div>
        </section>


        {{-- =====================================================
            LINKED COMPANIES
        ====================================================== --}}
        <section
            x-show="activeTab === 'companies'"
            x-cloak
            x-transition.opacity.duration.150ms
        >
            <div class="rounded-2xl border border-base-300 bg-base-100 shadow-sm">

                <div class="border-b border-base-200 px-5 py-5 sm:px-7">
                    <div class="flex items-center justify-between gap-4">

                        <div>
                            <p class="text-xs font-bold uppercase tracking-wider text-primary">
                                Platform relationships
                            </p>

                            <h2 class="mt-1 text-2xl font-black tracking-tight">
                                Linked companies
                            </h2>

                            <p class="mt-1 text-sm text-base-content/55">
                                Companies associated with {{ $platform->name }}.
                            </p>
                        </div>

                        <span class="badge badge-neutral badge-lg font-bold">
                            {{ $platform->companies->count() }}
                        </span>

                    </div>
                </div>


                <div class="p-5 sm:p-7">

                    @if($platform->companies->count())

                        <div class="grid gap-3 sm:grid-cols-2">

                            @foreach($platform->companies as $company)

                                <div class="rounded-2xl border border-base-200 bg-base-200/20 p-4 transition hover:border-primary/30 hover:bg-primary/5">

                                    <div class="flex items-center gap-4">

                                        <div class="flex size-12 shrink-0 items-center justify-center rounded-xl bg-base-100 font-black shadow-sm">
                                            {{ strtoupper(substr($company->name, 0, 1)) }}
                                        </div>

                                        <div class="min-w-0 flex-1">

                                            <p class="truncate font-black">
                                                {{ $company->name }}
                                            </p>

                                            @if($company->pivot->url)
                                                <p class="mt-1 truncate text-xs text-base-content/45">
                                                    {{ $company->pivot->url }}
                                                </p>
                                            @else
                                                <p class="mt-1 text-xs text-base-content/40">
                                                    No company URL recorded
                                                </p>
                                            @endif

                                        </div>

                                        @if($company->pivot->url)
                                            <a
                                                href="{{ $company->pivot->url }}"
                                                target="_blank"
                                                rel="noopener noreferrer"
                                                class="btn btn-circle btn-sm btn-ghost"
                                                title="Open company link"
                                            >
                                                <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14 5h5v5M19 5l-9 9"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 13v4a2 2 0 01-2 2H7a2 2 0 01-2-2V7a2 2 0 012-2h4"/>
                                                </svg>
                                            </a>
                                        @endif

                                    </div>

                                </div>

                            @endforeach

                        </div>

                    @else

                        <div class="rounded-2xl border border-dashed border-base-300 bg-base-200/20 px-5 py-14 text-center">

                            <div class="mx-auto flex size-14 items-center justify-center rounded-2xl bg-base-200">
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-7 text-base-content/40" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 21h18M5 21V9l7-4 7 4v12M9 21v-4h6v4"/>
                                </svg>
                            </div>

                            <h3 class="mt-4 font-black">
                                No linked companies
                            </h3>

                            <p class="mx-auto mt-1 max-w-md text-sm text-base-content/50">
                                Companies associated with this platform will appear here.
                            </p>

                        </div>

                    @endif

                </div>

            </div>
        </section>


        {{-- =====================================================
            PLATFORM PAGES
        ====================================================== --}}
        <section
            x-show="activeTab === 'pages'"
            x-cloak
            x-transition.opacity.duration.150ms
        >
            <div class="rounded-2xl border border-base-300 bg-base-100 shadow-sm">

                <div class="border-b border-base-200 px-5 py-5 sm:px-7">
                    <div class="flex items-center justify-between gap-4">

                        <div>
                            <p class="text-xs font-bold uppercase tracking-wider text-primary">
                                Content
                            </p>

                            <h2 class="mt-1 text-2xl font-black tracking-tight">
                                Platform pages
                            </h2>

                            <p class="mt-1 text-sm text-base-content/55">
                                Pages and records associated with {{ $platform->name }}.
                            </p>
                        </div>

                        <span class="badge badge-neutral badge-lg font-bold">
                            {{ $platform->platformPages->count() }}
                        </span>

                    </div>
                </div>


                <div class="p-5 sm:p-7">

                    @if($platform->platformPages->count())

                        <div class="space-y-3">

                            @foreach($platform->platformPages as $page)

                                <div class="flex items-center gap-4 rounded-2xl border border-base-200 bg-base-200/20 p-4 transition hover:border-primary/30 hover:bg-primary/5">

                                    <div class="flex size-12 shrink-0 items-center justify-center rounded-xl bg-base-100 shadow-sm">

                                        <svg xmlns="http://www.w3.org/2000/svg" class="size-5 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 4.5A1.5 1.5 0 017.5 3h9A1.5 1.5 0 0118 4.5v15a1.5 1.5 0 01-1.5 1.5h-9A1.5 1.5 0 016 19.5v-15z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 7h6M9 11h6M9 15h4"/>
                                        </svg>

                                    </div>

                                    <div class="min-w-0 flex-1">

                                        <p class="font-black">
                                            Platform Page #{{ $page->id }}
                                        </p>

                                        @if($page->created_at)
                                            <p class="mt-1 text-xs text-base-content/45">
                                                Added {{ $page->created_at->format('M d, Y') }}
                                            </p>
                                        @endif

                                    </div>

                                </div>

                            @endforeach

                        </div>

                    @else

                        <div class="rounded-2xl border border-dashed border-base-300 bg-base-200/20 px-5 py-14 text-center">

                            <div class="mx-auto flex size-14 items-center justify-center rounded-2xl bg-base-200">
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-7 text-base-content/40" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 4.5A1.5 1.5 0 017.5 3h9A1.5 1.5 0 0118 4.5v15a1.5 1.5 0 01-1.5 1.5h-9A1.5 1.5 0 016 19.5v-15z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 7h6M9 11h6M9 15h4"/>
                                </svg>
                            </div>

                            <h3 class="mt-4 font-black">
                                No platform pages
                            </h3>

                            <p class="mx-auto mt-1 max-w-md text-sm text-base-content/50">
                                Additional pages associated with this platform will appear here.
                            </p>

                        </div>

                    @endif

                </div>

            </div>
        </section>

    </div>

</div>
@endsection