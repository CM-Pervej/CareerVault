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
    .nav-item:hover:not(.active){background:hsl(var(--b2)/.6)}
        [x-cloak]{display:none!important}
        @media(max-width:1023px){
            .section{scroll-margin-top:16px}
        }
    .card-soft{transition:.18s}
    .card-soft:hover{transform:translateY(-2px)}
    @media (min-width: 640px) {
        .cv-presence-tile:hover > div,
        .cv-presence-tile:focus-within > div {
            min-width: 0;
        }
    }
</style>
@endpush

@push('scripts')
<script>
function platformNavigation(){
    return{
        active:'overview',
        moreOpen:false,
        sections:['overview','presence','companies','pages','groups','professionals','counts'],
        init(){
            const params=new URLSearchParams(window.location.search);
            const section=params.get('section');
            const hash=window.location.hash.replace('#','');
            const requested=section||hash;
            if(this.sections.includes(requested)){
                this.active=requested;
            }
            this.syncUrl();
        },
        navigate(section){
            if(!this.sections.includes(section))return;
            this.active=section;
            this.moreOpen=false;
            this.syncUrl();
            window.scrollTo({top:0,behavior:'smooth'});
        },
        syncUrl(){
            const url=new URL(window.location.href);
            url.searchParams.set('section',this.active);
            url.hash='';
            window.history.replaceState({},'',url);
        },
        reloadSection(){
            const url=new URL(window.location.href);
            url.searchParams.set('section',this.active);
            window.location.href=url.toString();
        }
    }
}
</script>
@endpush

@section('content')
<div x-data="platformNavigation()" class="space-y-6 pb-24 lg:pb-0">
    {{-- ===================== PLATFORM HEADER ======================== --}}
    <section class="overflow-hidden border border-base-300 bg-base-100 shadow-sm">
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

                {{-- Actions --}}
                <div class="flex shrink-0 items-center gap-2">
                    @if(!$platform->trashed())
                        <a href="{{ route('admin.platforms.edit', $platform) }}" class="btn btn-sm border-0 bg-white/15 text-white shadow-none backdrop-blur-md hover:bg-white/25">
                            <i class="fa-solid fa-pen-to-square text-xs"></i>
                            <span class="hidden sm:inline">Edit</span>
                        </a>
                    @endif

                    @if($platform->trashed())
                        <a href="{{ route('admin.platforms.trash') }}" class="btn btn-warning btn-outline btn-sm gap-2">
                            <i class="fa-solid fa-trash-can text-xs"></i>
                            <span class="hidden sm:inline">Trash</span>
                        </a>
                    @endif

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

                        <span class="absolute -bottom-1 -right-1 flex items-center justify-center rounded-full border-4 border-white/20 shadow-lg">
                            @if($platform->trashed())
                                <span class="absolute -bottom-1.5 -right-1.5 flex size-6 items-center justify-center rounded-full border-[3px] border-[#101216] bg-warning">
                                    <i class="fa-solid fa-trash-can text-xs"></i>
                                </span>
                            @else
                                <span class="absolute -bottom-1.5 -right-1.5 flex size-6 items-center justify-center rounded-full border-[3px] border-[#101216] {{ $platform->is_active ? 'bg-emerald-500' : 'bg-base-content' }}">
                                    <i class="fa-solid {{ $platform->is_active ? 'fa-check' : 'fa-pause' }} text-[9px] text-white"></i>
                                </span>
                            @endif
                        </span>
                    </div>

                    {{-- Identity text --}}
                    <div class="min-w-0 flex-1 text-white">
                        <div class="flex flex-wrap items-center gap-2">
                            <h1 class="text-3xl font-black tracking-tight sm:text-4xl"> {{ $platform->name }} </h1>

                            @if($platform->trashed())
                                <sup class="badge badge-warning badge-outline ml-1 align-super text-[10px]">
                                    <i class="fa-solid fa-trash-can"></i> Trash
                                </sup>
                            @elseif($platform->is_active)
                                <sup class="badge badge-success badge-outline ml-1 align-super text-[10px]">
                                    <i class="fa-solid fa-circle-check"></i> Active
                                </sup>
                            @else
                                <sup class="badge badge-ghost ml-1 align-super text-[10px]">
                                    <i class="fa-solid fa-circle-pause"></i> Inactive
                                </sup>
                            @endif
                        </div>

                        @if($platform->official_name)
                            <p class="mt-1 text-sm font-medium text-white/60"> {{ $platform->official_name }} </p>
                        @endif

                        @if($platform->short_desc)
                            <p class="mt-4 max-w-3xl text-sm leading-6 text-white/75 sm:text-base text-justify"> {{ $platform->short_desc }} </p>
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
        {{-- Desktop Sidebar --}}
        <aside class="order-2 hidden h-max rounded-lg border border-base-300 bg-base-100 p-3 shadow-sm lg:sticky lg:top-20 lg:block">
            <button @click="navigate('overview')" :class="active==='overview'?'active':''" class="nav-item flex w-full items-center gap-3 rounded-xl border border-transparent px-4 py-3 text-left font-semibold">
                <i class="fa-solid fa-layer-group w-5"></i>Overview
            </button>
            <button @click="navigate('presence')" :class="active==='presence'?'active':''" class="nav-item flex w-full items-center gap-3 rounded-xl border border-transparent px-4 py-3 text-left font-semibold">
                <i class="fa-solid fa-share-nodes w-5"></i>Official Presence
            </button>
            <button @click="navigate('companies')" :class="active==='companies'?'active':''" class="nav-item flex w-full items-center gap-3 rounded-xl border border-transparent px-4 py-3 text-left font-semibold">
                <i class="fa-solid fa-building w-5"></i>Companies
            </button>
            <button @click="navigate('pages')" :class="active==='pages'?'active':''" class="nav-item flex w-full items-center gap-3 rounded-xl border border-transparent px-4 py-3 text-left font-semibold">
                <i class="fa-solid fa-file-lines w-5"></i>Pages
            </button>
            <button @click="navigate('groups')" :class="active==='groups'?'active':''" class="nav-item flex w-full items-center gap-3 rounded-xl border border-transparent px-4 py-3 text-left font-semibold">
                <i class="fa-solid fa-users w-5"></i>Groups
            </button>
            <button @click="navigate('professionals')" :class="active==='professionals'?'active':''" class="nav-item flex w-full items-center gap-3 rounded-xl border border-transparent px-4 py-3 text-left font-semibold">
                <i class="fa-solid fa-user-tie w-5"></i>Professionals
            </button>
            <button @click="navigate('counts')" :class="active==='counts'?'active':''" class="nav-item flex w-full items-center gap-3 rounded-xl border border-transparent px-4 py-3 text-left font-semibold">
                <i class="fa-solid fa-chart-simple w-5"></i>Complete Count
            </button>
        </aside>

        {{-- Main Content --}}
        <main class="order-1 min-w-0 space-y-6">
            {{-- Overview --}}
            <div x-show="active==='overview'" x-cloak class="space-y-6">
                @include('admin.platform.platforms.partials.overview')
            </div>

            {{-- Official Presence --}}
            <div x-show="active==='presence'" x-cloak class="space-y-4">
                @include('admin.platform.platforms.partials.connectedPlatform')
            </div>

            {{-- Companies --}}
            <div x-show="active==='companies'" x-cloak class="space-y-4">
                @include('admin.platform.platforms.partials.companies')
            </div>

            {{-- Pages --}}
            <div x-show="active==='pages'" x-cloak class="space-y-4">
                @include('admin.platform.platforms.partials.pages')
            </div>

            {{-- Groups --}}
            <div x-show="active==='groups'" x-cloak class="space-y-4">
                {{-- Add your Groups partial here --}}
            </div>

            {{-- Professionals --}}
            <div x-show="active==='professionals'" x-cloak class="space-y-4">
                {{-- Add your Professionals partial here --}}
            </div>

            {{-- Complete Count --}}
            <div x-show="active==='counts'" x-cloak class="space-y-4">
                @include('admin.platform.platforms.partials.counts')
            </div>
        </main>
    </div>

    {{-- Mobile Bottom Navigation --}}
    <nav class="fixed inset-x-0 bottom-0 z-50 border-t border-base-300 bg-base-100/95 shadow-[0_-4px_20px_rgba(0,0,0,0.08)] backdrop-blur-xl lg:hidden"
        style="padding-bottom:env(safe-area-inset-bottom)">
        <div class="grid grid-cols-5 gap-1 px-2 pt-2 pb-2">
            {{-- Overview --}}
            <button @click="navigate('overview')"
                    :class="active==='overview'?'text-primary':'text-base-content/60'"
                    class="flex min-w-0 flex-col items-center justify-center gap-1 rounded-xl py-2 transition-colors">
                <i class="fa-solid fa-layer-group text-lg"></i>
                <span class="text-[10px] font-semibold">Overview</span>
                <span x-show="active==='overview'" class="h-1 w-5 rounded-full bg-primary"></span>
            </button>

            {{-- Companies --}}
            <button @click="navigate('companies')"
                    :class="active==='companies'?'text-primary':'text-base-content/60'"
                    class="flex min-w-0 flex-col items-center justify-center gap-1 rounded-xl py-2 transition-colors">
                <i class="fa-solid fa-building text-lg"></i>
                <span class="text-[10px] font-semibold">Companies</span>
                <span x-show="active==='companies'" class="h-1 w-5 rounded-full bg-primary"></span>
            </button>

            {{-- Pages --}}
            <button @click="navigate('pages')"
                    :class="active==='pages'?'text-primary':'text-base-content/60'"
                    class="flex min-w-0 flex-col items-center justify-center gap-1 rounded-xl py-2 transition-colors">
                <i class="fa-solid fa-file-lines w-5"></i>
                <span class="text-[10px] font-semibold">Pages</span>
                <span x-show="active==='pages'" class="h-1 w-5 rounded-full bg-primary"></span>
            </button>

            {{-- Groups --}}
            <button @click="navigate('groups')"
                    :class="active==='groups'?'text-primary':'text-base-content/60'"
                    class="flex min-w-0 flex-col items-center justify-center gap-1 rounded-xl py-2 transition-colors">
                <i class="fa-solid fa-share-nodes text-lg"></i>
                <span class="text-[10px] font-semibold">Groups</span>
                <span x-show="active==='groups'" class="h-1 w-5 rounded-full bg-primary"></span>
            </button>

            {{-- More --}}
            <button @click="moreOpen=!moreOpen"
                    :class="moreOpen||['presence','professionals','counts'].includes(active)?'text-primary':'text-base-content/60'"
                    class="flex min-w-0 flex-col items-center justify-center gap-1 rounded-xl py-2 transition-colors">
                <i class="fa-solid fa-ellipsis text-lg"></i>
                <span class="text-[10px] font-semibold">More</span>
                <span x-show="moreOpen||['presence','professionals','counts'].includes(active)" class="h-1 w-5 rounded-full bg-primary"></span>
            </button>
        </div>

        {{-- Expandable More Menu --}}
        <div x-cloak x-show="moreOpen"
            x-transition.origin.bottom
            @click.outside="moreOpen=false"
            @keydown.escape.window="moreOpen=false"
            class="absolute right-3 bottom-full mb-2 w-60 overflow-hidden rounded-2xl border border-base-300 bg-base-100 p-2 shadow-2xl">

            <div class="px-3 py-2">
                <p class="text-xs font-bold uppercase tracking-wider text-base-content/50">Platform sections</p>
            </div>

            <button @click="navigate('presence')"
                    :class="active==='presence'?'bg-primary/10 text-primary':'hover:bg-base-200'"
                    class="flex w-full items-center gap-3 rounded-xl px-3 py-3 text-sm font-medium transition-colors">
                <i class="fa-solid fa-file-lines w-5"></i>
                <span class="flex-1 text-left">Official Presence</span>
                <i x-show="active==='presence'" class="fa-solid fa-check text-xs"></i>
            </button>

            <button @click="navigate('professionals')"
                    :class="active==='professionals'?'bg-primary/10 text-primary':'hover:bg-base-200'"
                    class="flex w-full items-center gap-3 rounded-xl px-3 py-3 text-sm font-medium transition-colors">
                <i class="fa-solid fa-user-tie w-5"></i>
                <span class="flex-1 text-left">Professionals</span>
                <i x-show="active==='professionals'" class="fa-solid fa-check text-xs"></i>
            </button>

            <button @click="navigate('counts')"
                    :class="active==='counts'?'bg-primary/10 text-primary':'hover:bg-base-200'"
                    class="flex w-full items-center gap-3 rounded-xl px-3 py-3 text-sm font-medium transition-colors">
                <i class="fa-solid fa-chart-simple w-5"></i>
                <span class="flex-1 text-left">Complete Count</span>
                <i x-show="active==='counts'" class="fa-solid fa-check text-xs"></i>
            </button>
        </div>
    </nav>
</div>
@endsection