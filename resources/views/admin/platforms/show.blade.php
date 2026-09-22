@extends('layouts.admin.app')

@section('title', $platform->name.' | Platforms | CareerVault')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
<style>
    [x-cloak]{display:none!important}
    .section{scroll-margin-top:24px}
    .nav-item{transition:.18s}
    .nav-item.active{background:hsl(var(--p)/.12);color:hsl(var(--p));border-color:hsl(var(--p)/.25)}
    .row-hover:hover{background:hsl(var(--b2)/.45)}
    .card-soft{transition:.18s}
    .card-soft:hover{transform:translateY(-2px)}
</style>
@endpush

@section('content')
<div x-data="{active:'overview',links:false}" class="mx-auto max-w-[1500px] space-y-6">
    {{-- ===================== PLATFORM HEADER ======================== --}}
    <section class="overflow-hidden sm:rounded-lg border border-base-300 bg-base-100 shadow-sm">
        {{-- Hero --}}
        <div class="relative overflow-hidden" style="background: radial-gradient(circle at 15% 20%, {{ $platform->color ?: '#6366f1' }} 0%, transparent 34%), radial-gradient(circle at 85% 0%, rgba(255,255,255,.18) 0%, transparent 30%), linear-gradient(135deg, {{ $platform->color ?: '#4f46e5' }} 0%, #111827 100%);">
            @if($platform->cover_image)
                <img src="{{ Storage::url($platform->cover_image) }}" alt="{{ $platform->name }}" class="absolute inset-0 size-full object-cover opacity-30">
            @endif

            <div class="absolute inset-0 bg-black/10"></div>

            {{-- Top navigation --}}
            <div class="relative flex items-center justify-between px-5 py-4 sm:px-7">
                <div class="flex min-w-0 items-center gap-2 text-sm text-white/70">
                    <a href="{{ route('admin.platforms.index') }}" class="transition hover:text-white">Platforms</a>
                    <i class="fa-solid fa-chevron-right text-[10px] text-white/40"></i>
                    <span class="truncate font-semibold text-white"> {{ $platform->name }} </span>
                </div>

                <div class="flex items-center gap-2">
                    <a href="{{ route('admin.platforms.edit', $platform) }}" class="btn btn-sm border-0 bg-white/15 text-white shadow-none backdrop-blur-md hover:bg-white/25">
                        <i class="fa-solid fa-pen-to-square text-xs"></i>
                        <span class="hidden sm:inline">Edit</span>
                    </a>

                    <a href="{{ route('admin.platforms.index') }}" class="btn btn-sm border-0 bg-white/15 text-white shadow-none backdrop-blur-md hover:bg-white/25">
                        <i class="fa-solid fa-arrow-left text-xs"></i>
                        <span class="hidden sm:inline">Back</span>
                    </a>
                </div>
            </div>

            {{-- Identity --}}
            <div class="relative px-5 pb-8 pt-8 sm:px-8 sm:pb-10 sm:pt-10">
                <div class="flex flex-col gap-6 sm:flex-row sm:items-center">
                    {{-- Logo --}}
                    <div class="relative shrink-0">
                        <div class="flex size-24 items-center justify-center overflow-hidden rounded-3xl border border-white/20 bg-white shadow-2xl sm:size-28">
                            @if($platform->logo)
                                <img src="{{ Storage::url($platform->logo) }}" alt="{{ $platform->name }}" class="size-full object-contain p-3">
                            @elseif($platform->icon)
                                    <i class="{{ $platform->icon }} text-7xl" style="{{ $platform->color ? 'color:'.$platform->color : '' }}"></i>
                            @else
                                <span class="text-4xl font-black" style="color: {{ $platform->color ?: '#4f46e5' }}">
                                    {{ strtoupper(substr($platform->name, 0, 1)) }}
                                </span>
                            @endif
                        </div>

                        <span class="absolute -bottom-2 -right-2 flex size-8 items-center justify-center rounded-full border-4 border-white/20 bg-white shadow-lg">
                            @if($platform->is_active)
                                <i class="fa-solid fa-check text-xs text-success"></i>
                            @else
                                <i class="fa-solid fa-pause text-xs text-error"></i>
                            @endif
                        </span>
                    </div>

                    {{-- Identity text --}}
                    <div class="min-w-0 flex-1 text-white">
                        <div class="flex flex-wrap items-center gap-2">
                            <h1 class="text-3xl font-black tracking-tight sm:text-4xl"> {{ $platform->name }} </h1>
                            @if($platform->is_active)
                                <span class="rounded-full bg-emerald-400/15 px-2.5 py-1 text-xs font-bold text-emerald-100 ring-1 ring-inset ring-emerald-300/20">Active</span>
                            @else
                                <span class="rounded-full bg-red-400/15 px-2.5 py-1 text-xs font-bold text-red-100 ring-1 ring-inset ring-red-300/20">Inactive</span>
                            @endif
                        </div>

                        @if($platform->official_name)
                            <p class="mt-1 text-sm font-medium text-white/60"> {{ $platform->official_name }} </p>
                        @endif

                        @if($platform->short_desc)
                            <p class="mt-4 max-w-3xl text-sm leading-6 text-white/75 sm:text-base"> {{ $platform->short_desc }} </p>
                        @endif

                        <div class="mt-5 flex flex-wrap gap-2">
                            <span class="inline-flex items-center gap-2 rounded-sm bg-white/10 px-3 py-1.5 text-xs font-semibold text-white/80 backdrop-blur">
                                <i class="fa-solid fa-briefcase text-[11px]"></i> {{ $platform->job_type }}
                            </span>

                            <span class="inline-flex items-center gap-2 rounded-full bg-white/10 px-3 py-1.5 text-xs font-semibold text-white/80 backdrop-blur">
                                <i class="fa-solid fa-wallet text-[11px]"></i> {{ $platform->business_model }}
                            </span>

                            @if($platform->account_required)
                                <span class="inline-flex items-center gap-2 rounded-sm bg-white/10 px-3 py-1.5 text-xs font-semibold text-white/80 backdrop-blur">
                                    <i class="fa-solid fa-user text-[11px]"></i> Account required
                                </span>
                            @endif

                            @if($platform->is_bangladesh_focused)
                                <span class="inline-flex items-center gap-2 rounded-full bg-white/10 px-3 py-1.5 text-xs font-semibold text-white/80 backdrop-blur">
                                    <i class="fa-solid fa-location-dot text-[11px]"></i> Bangladesh focused
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="grid gap-6 lg:grid-cols-[minmax(0,1fr)_240px]">
        {{-- Sidebar --}}
        <aside class="sm:rounded-lg border border-base-300 bg-base-100 p-3 shadow-sm h-max lg:sticky lg:top-20 order-2">
            <button @click="active='overview'" :class="active==='overview'?'active':''" class="nav-item flex w-full items-center gap-3 rounded-xl border border-transparent px-4 py-3 text-left font-semibold">
                <i class="fa-solid fa-layer-group w-5"></i>Overview
            </button>
            <button @click="active='presence'" :class="active==='presence'?'active':''" class="nav-item flex w-full items-center gap-3 rounded-xl border border-transparent px-4 py-3 text-left font-semibold">
                <i class="fa-solid fa-share-nodes w-5"></i>Official Presence
            </button>
            <button @click="active='companies'" :class="active==='companies'?'active':''" class="nav-item flex w-full items-center gap-3 rounded-xl border border-transparent px-4 py-3 text-left font-semibold">
                <i class="fa-solid fa-building w-5"></i>Companies
            </button>
            <button @click="active='pages'" :class="active==='pages'?'active':''" class="nav-item flex w-full items-center gap-3 rounded-xl border border-transparent px-4 py-3 text-left font-semibold">
                <i class="fa-solid fa-file-lines w-5"></i>Pages
            </button>
            <button @click="active='groups'" :class="active==='groups'?'active':''" class="nav-item flex w-full items-center gap-3 rounded-xl border border-transparent px-4 py-3 text-left font-semibold">
                <i class="fa-solid fa-file-lines w-5"></i>Groups
            </button>
            <button @click="active='professionals'" :class="active==='professionals'?'active':''" class="nav-item flex w-full items-center gap-3 rounded-xl border border-transparent px-4 py-3 text-left font-semibold">
                <i class="fa-solid fa-file-lines w-5"></i>Professionals
            </button>
            <button @click="active='counts'" :class="active==='counts'?'active':''" class="nav-item flex w-full items-center gap-3 rounded-xl border border-transparent px-4 py-3 text-left font-semibold">
                <i class="fa-solid fa-file-lines w-5"></i>Complete Count
            </button>
        </aside>

        <main class="space-y-6 order-1">
            {{-- Overview --}}
            <div x-show="active==='overview'" x-cloak class="space-y-6">
                @include('admin.platforms.partials.overview')
            </div>

            {{-- Presence --}}
            <div x-show="active==='presence'" x-cloak class="space-y-4">
                @include('admin.platforms.partials.connectedPlatform')
            </div>

            {{-- Companies --}}
            <div x-show="active==='companies'" x-cloak class="space-y-4">
                @include('admin.platforms.partials.companies')
            </div>

            {{-- Pages --}}
            <div x-show="active==='pages'" x-cloak class="space-y-4">
                @include('admin.platforms.partials.pages')
            </div>

            {{-- Counts --}}
            <div x-show="active==='counts'" x-cloak class="space-y-4">
                @include('admin.platforms.partials.counts')
            </div>
        </main>
    </div>
</div>
@endsection