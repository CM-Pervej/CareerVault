@extends('layouts.app')

@section('title',$platform->name.' - Platform Details')

@section('content')
@php
    $host=$platform->base_url?parse_url($platform->base_url,PHP_URL_HOST):null;
    $accent=$platform->color ?: '#4f46e5';
    $accentTint=$accent.'12';   // ~7% alpha, for backgrounds
    $initials=collect(preg_split('/\s+/',$platform->name))->filter()->take(2)->map(fn($word)=>mb_strtoupper(mb_substr($word,0,1)))->implode('');
    $initials=$initials ?: 'P';

    $foundedLabel='Not on record';
    if($platform->founded_year){
        $monthName=$platform->founded_month?date('F',mktime(0,0,0,$platform->founded_month,1)):'';
        $foundedLabel=trim(($monthName?$monthName.' ':'').$platform->founded_year);
    }

    $marketAge=null;
    if($platform->founded_year){
        $marketAge=max(0,now()->year-$platform->founded_year);
    }

    $verifiedAt=$platform->last_verified_at? \Illuminate\Support\Carbon::parse($platform->last_verified_at):null;
    $verificationLabel='Not yet verified';
    $verificationTone='neutral';

    if($verifiedAt){
        $daysSinceVerification=$verifiedAt->diffInDays(now());

        if($daysSinceVerification<=30){
            $verificationLabel='Verified recently';
            $verificationTone='success';
        }elseif($daysSinceVerification<=90){
            $verificationLabel='Verified '.$daysSinceVerification.' days ago';
            $verificationTone='warning';
        }else{
            $verificationLabel='Verification is due';
            $verificationTone='danger';
        }
    }

    $verificationBadgeClass=match($verificationTone){
        'success'=>'badge-success',
        'warning'=>'badge-warning',
        'danger'=>'badge-error',
        default=>'badge-ghost',
    };

    // Replaces "similar platforms" — other entries sharing the
    // Bangladesh-focus flag with this one.
    $bangladeshPlatforms=($platforms ?? collect())
        ->where('is_bangladesh_focused',true)
        ->where('id','!=',$platform->id)
        ->take(6);

    $quickNav=collect([
        'section-about'=>'About',
        // 'section-coverage'=>$hasCoverageTags || $hasCommunityStats?'Coverage':null,
        'section-details'=>'Details',
        'section-links'=>'Links',
        'section-timeline'=>'Timeline',
        'section-related'=>$bangladeshPlatforms->count()?'Bangladesh focused':null,
        'section-metadata'=>'Record',
    ])->filter();
@endphp

<div id="cv-top" class="min-h-screen bg-base-200/40 cv-page">
    <div class="mx-auto max-w-[1700px] sm:px-5 lg:px-6 lg:py-2">
        <div class="grid grid-cols-1 gap-5 lg:grid-cols-12">
            {{-- MAIN CONTENT --}}
            <main class="min-w-0 lg:col-span-9">
                {{-- MASTHEAD --}}
                <section class="overflow-hidden sm:rounded-2xl border border-base-300/70 bg-base-100 shadow-sm">
                    <div>
                        {{-- Cover --}}
                        @if($platform->cover_image)
                            <div class="group relative">
                                <button type="button" data-cover-trigger class="relative block h-40 w-full cursor-zoom-in overflow-hidden sm:h-52 lg:h-60" aria-label="View {{ $platform->name }} cover image">
                                    <img src="{{ asset('storage/'.$platform->cover_image) }}" alt="{{ $platform->name }} cover" class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-[1.025]">

                                    <div class="absolute inset-0 bg-gradient-to-t from-black/30 via-transparent to-black/10"></div>

                                    <span class="absolute bottom-4 right-4 inline-flex items-center gap-1.5 rounded-lg bg-black/50 px-3 py-1.5 text-[11px] font-semibold text-white opacity-0 shadow-sm backdrop-blur-md transition-all duration-300 group-hover:opacity-100 sm:bottom-5 sm:right-5">
                                        <i class="fa-solid fa-expand text-[9px]"></i> View cover
                                    </span>
                                </button>

                                {{-- Back to directory --}}
                                <div class="absolute left-3 top-3 sm:left-4 sm:top-4">
                                    <a href="{{ route('platforms.index') }}" class="group/back inline-flex items-center gap-2 rounded-lg border border-white/20 bg-black/40 px-3 py-1.5 text-xs font-semibold text-white shadow-sm backdrop-blur-md transition-all hover:bg-black/55">
                                        <i class="fa-solid fa-arrow-left text-[10px] transition-transform group-hover/back:-translate-x-0.5"></i>
                                        <span>Platform directory</span>
                                    </a>
                                </div>

                                {{-- Overlapping Logo --}}
                                <div class="absolute bottom-0 z-10 translate-y-1/2 left-6 translate-x-0">
                                    <div class="relative flex h-24 w-24 items-center justify-center overflow-hidden rounded-2xl border-4 border-base-100 bg-base-100 shadow-lg sm:h-28 sm:w-28" style="background:{{ $platform->logo ? 'white' : $accentTint }};">
                                        @if($platform->logo)
                                            <img src="{{ asset('storage/'.$platform->logo) }}" alt="{{ $platform->name }} logo" class="h-full w-full object-contain p-3">
                                        @elseif($platform->icon)
                                            <i class="{{ $platform->icon }} text-4xl" style="color:{{ $accent }};"></i>
                                        @else
                                            <span class="text-3xl font-bold" style="color:{{ $accent }};"> {{ $initials }} </span>
                                        @endif

                                        @if($platform->is_active)
                                            <span class="absolute bottom-1 right-1 flex h-6 w-6 items-center justify-center rounded-full border-2 border-base-100 bg-success text-success-content shadow-sm" title="Currently active">
                                                <i class="fa-solid fa-check text-[9px]"></i>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @else
                            {{-- No cover --}}
                            <div class="relative h-24 bg-base-200 sm:h-32">
                                <div class="p-3 sm:p-4">
                                    <a href="{{ route('platforms.index') }}" class="group inline-flex items-center gap-2 rounded-lg px-2 py-1.5 text-xs font-semibold text-base-content/55 transition hover:bg-base-300/50 hover:text-base-content">
                                        <i class="fa-solid fa-arrow-left text-[10px] transition-transform group-hover:-translate-x-0.5"></i> Platform directory
                                    </a>
                                </div>

                                <div class="absolute bottom-0 left-1/2 z-10 -translate-x-1/2 translate-y-1/2 sm:left-6 sm:translate-x-0">
                                    <div class="relative flex h-24 w-24 items-center justify-center overflow-hidden rounded-2xl border-4 border-base-100 shadow-lg sm:h-28 sm:w-28" style="background:{{ $platform->logo ? 'white' : $accentTint }};">
                                        @if($platform->logo)
                                            <img src="{{ asset('storage/'.$platform->logo) }}" alt="{{ $platform->name }} logo" class="h-full w-full object-contain p-3">
                                        @elseif($platform->icon)
                                            <i class="{{ $platform->icon }} text-4xl" style="color:{{ $accent }};"></i>
                                        @else
                                            <span class="text-3xl font-bold" style="color:{{ $accent }};"> {{ $initials }} </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif

                        {{-- Platform information --}}
                        <div class="px-4 pb-6 pt-16 sm:px-6 sm:pt-16">
                            <div class="min-w-0">
                                <div class="flex flex-wrap items-center gap-2">
                                    <h1 class="text-2xl font-bold tracking-tight text-base-content sm:text-[28px]"> {{ $platform->name }} </h1>

                                    @if($platform->is_active)
                                        <span class="badge badge-success badge-sm font-semibold">Active</span>
                                    @else
                                        <span class="badge badge-ghost badge-sm font-semibold">Inactive</span>
                                    @endif

                                    <span class="badge {{ $verificationBadgeClass }} badge-sm gap-1 font-semibold" title="{{ $verifiedAt?->format('d M Y') }}">
                                        <i class="fa-solid fa-shield-check text-[9px]"></i> {{ $verificationLabel }}
                                    </span>
                                </div>

                                @if($platform->official_name)
                                    <p class="mt-0.5 text-sm text-base-content/55"> {{ $platform->official_name }} </p>
                                @endif

                                <div class="mt-2.5 flex flex-wrap items-center gap-x-4 gap-y-1.5 text-xs text-base-content/55">
                                    @if($host)
                                        <a href="{{ $platform->base_url }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1.5 transition hover:text-base-content">
                                            <i class="fa-solid fa-globe text-[11px]"></i> {{ preg_replace('/^www\./','',$host) }}
                                        </a>
                                    @endif

                                    @if($platform->job_type)
                                        <span class="inline-flex items-center gap-1.5">
                                            <i class="fa-solid fa-briefcase text-[11px]"></i> {{ $platform->job_type }}
                                        </span>
                                    @endif

                                    @if($platform->business_model)
                                        <span class="inline-flex items-center gap-1.5">
                                            <i class="fa-solid fa-wallet text-[11px]"></i> {{ $platform->business_model }}
                                        </span>
                                    @endif

                                    @if($platform->is_bangladesh_focused)
                                        <span class="inline-flex items-center gap-1.5 font-semibold" style="color:{{ $accent }};">
                                            <i class="fa-solid fa-flag text-[11px]"></i> Bangladesh focused
                                        </span>
                                    @endif
                                </div>

                                @if($platform->short_desc)
                                    <p class="mt-4 max-w-3xl text-sm leading-6 text-base-content/70 sm:text-[15px]"> {{ $platform->short_desc }} </p>
                                @endif
                            </div>
                        </div>
                    </div>
                </section>

                {{-- On-this-page quick nav --}}
                @if($quickNav->count() > 1)
                    <nav class="mt-4 flex gap-1.5 overflow-x-auto pb-1" aria-label="On this page">
                        @foreach($quickNav as $anchor => $label)
                            <a href="#{{ $anchor }}" class="shrink-0 rounded-full border border-base-300/70 bg-base-100 px-3 py-1.5 text-xs font-semibold text-base-content/60 transition hover:border-base-content/20 hover:text-base-content">
                                {{ $label }}
                            </a>
                        @endforeach
                    </nav>
                @endif

                {{-- TWO-COLUMN INFORMATION AREA --}}
                <div class="mt-5 grid grid-cols-1 gap-5 xl:grid-cols-5">
                    {{-- LEFT: PROFILE --}}
                    <section class="min-w-0 space-y-5 xl:col-span-2 order-2 sm:order-1">
                        {{-- Bangladesh-focused platforms --}}
                        @if($bangladeshPlatforms->count())
                            <article id="section-related" class="scroll-mt-4 rounded-2xl border border-base-300/70 bg-base-100 p-5 shadow-sm sm:p-6">
                                <div class="flex items-center justify-between gap-3">
                                    <h2 class="text-lg font-bold tracking-tight">Bangladesh focused</h2>
                                    <span class="inline-flex items-center gap-1 text-xs font-semibold" style="color:{{ $accent }};">
                                        <i class="fa-solid fa-flag text-[10px]"></i> {{ $bangladeshPlatforms->count() }}
                                    </span>
                                </div>

                                <div class="mt-3 divide-y divide-base-300/70">
                                    @foreach($bangladeshPlatforms as $related)
                                        @php
                                            $relatedInitials=collect(preg_split('/\s+/',$related->name))->filter()->take(2)->map(fn($word)=>mb_strtoupper(mb_substr($word,0,1)))->implode('');
                                            $relatedInitials=$relatedInitials?:'P';
                                            $relatedAccent=$related->color?:'#4f46e5';
                                        @endphp

                                        <a href="{{ route('platforms.show',$related) }}" class="group flex items-center gap-3 py-2.5 transition hover:opacity-80">
                                            <div class="flex h-9 w-9 shrink-0 items-center justify-center overflow-hidden rounded-lg text-[10px] font-bold text-white" style="{{ $related->color ? 'background-color:'.$related->color.'12' : '' }}">
                                                @if($related->logo)
                                                    <img src="{{ asset('storage/'.$related->logo) }}" alt="{{ $related->name }}" class="h-full w-full object-contain bg-white p-1">
                                                @elseif($related->icon)
                                                    <i class="{{ $related->icon }} text-lg" style="{{ $related->color ? 'color:'.$related->color : '' }}"></i>
                                                @else
                                                    <span style="color:{{ $relatedAccent }}; background:transparent;">{{ $relatedInitials }}</span>
                                                @endif
                                            </div>

                                            <div class="min-w-0 flex-1">
                                                <p class="truncate text-sm font-semibold group-hover:underline"> {{ $related->name }} </p>
                                                <p class="text-xs text-base-content/45">
                                                    {{ $related->job_type }}
                                                    @if($related->business_model)
                                                        · {{ $related->business_model }}
                                                    @endif
                                                </p>
                                            </div>

                                            <i class="fa-solid fa-chevron-right text-[10px] text-base-content/25"></i>
                                        </a>
                                    @endforeach
                                </div>
                            </article>
                        @endif
                    </section>

                    {{-- RIGHT (within main): PLATFORM RECORD --}}
                    <section class="min-w-0 space-y-5 xl:col-span-3 order-1 sm:order-2">
                        {{-- About + signals --}}
                        <article id="section-about" class="scroll-mt-4 sm:rounded-2xl border border-base-300/70 bg-base-100 p-5 shadow-sm sm:p-6">
                            <div class="flex items-center justify-between border-b border-base-300/70">
                                <h2 class="border-l-4 pl-3 text-lg font-bold tracking-tight" style="border-color:{{ $accent }};"> About {{ $platform->name }} </h2>
                                <span class="rounded-md bg-base-200 px-2.5 py-1 text-[10px] font-semibold text-base-content/50"> CareerVault </span>
                            </div>

                            @if($platform->description)
                                <div class="mt-4 max-w-none text-sm leading-6 text-base-content/70"> {!! nl2br(e($platform->description)) !!} </div>
                            @else
                                <p class="mt-4 text-sm text-base-content/45"> No detailed description has been added yet. </p>
                            @endif

                            <dl class="mt-6 divide-y divide-base-300/70 border-t border-base-300/70">
                                <div class="flex items-center justify-between gap-4 py-3">
                                    <dt class="text-sm text-base-content/55">Job environment</dt>
                                    <dd class="text-sm font-semibold text-base-content">{{ $platform->job_type ?: 'Not set' }}</dd>
                                </div>
                                <div class="flex items-center justify-between gap-4 py-3">
                                    <dt class="text-sm text-base-content/55">Pricing model</dt>
                                    <dd class="text-sm font-semibold text-base-content">{{ $platform->business_model ?: 'Not set' }}</dd>
                                </div>
                                <div class="flex items-center justify-between gap-4 py-3">
                                    <dt class="text-sm text-base-content/55">Account</dt>
                                    <dd class="text-sm font-semibold text-base-content">{{ $platform->account_required?'Required':'Not required' }}</dd>
                                </div>
                                <div class="flex items-center justify-between gap-4 py-3">
                                    <dt class="text-sm text-base-content/55">Bangladesh focus</dt>
                                    <dd>
                                        <span class="badge {{ $platform->is_bangladesh_focused?'badge-success':'badge-ghost' }} badge-sm"> {{ $platform->is_bangladesh_focused?'Yes':'No' }} </span>
                                    </dd>
                                </div>
                                <div class="flex items-center justify-between gap-4 py-3">
                                    <dt class="text-sm text-base-content/55">Founded</dt>
                                    <dd class="text-sm font-semibold text-base-content">{{ $foundedLabel }}</dd>
                                </div>
                            </dl>
                        </article>

                        {{-- Links --}}
                        <article id="section-links" class="scroll-mt-4 sm:rounded-2xl border border-base-300/70 bg-base-100 p-5 shadow-sm sm:p-6">
                            <h2 class="border-l-4 pl-3 text-lg font-bold tracking-tight" style="border-color:{{ $accent }};">Platform links</h2>
                            <div class="mt-3 divide-y divide-base-300/70">
                                @if($platform->base_url)
                                    <div class="flex items-center gap-3 py-3">
                                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-base-200 text-base-content/50">
                                            <i class="fa-solid fa-globe text-xs"></i>
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <p class="text-xs text-base-content/45">Official website</p>
                                            <a href="{{ $platform->base_url }}" target="_blank" rel="noopener noreferrer" class="block truncate text-sm font-semibold hover:underline" style="color:{{ $accent }};">
                                                {{ $platform->base_url }}
                                            </a>
                                        </div>
                                        <button type="button" data-copy="{{ $platform->base_url }}" class="btn btn-ghost btn-sm btn-square rounded-lg" title="Copy website URL">
                                            <i class="fa-regular fa-copy text-xs"></i>
                                        </button>
                                    </div>
                                @endif

                                @if($platform->job_url)
                                    <div class="flex items-center gap-3 py-3">
                                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-base-200 text-base-content/50">
                                            <i class="fa-solid fa-magnifying-glass text-xs"></i>
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <p class="text-xs text-base-content/45">Job portal</p>
                                            <a href="{{ $platform->job_url }}" target="_blank" rel="noopener noreferrer" class="block truncate text-sm font-semibold hover:underline" style="color:{{ $accent }};">
                                                {{ $platform->job_url }}
                                            </a>
                                        </div>
                                        <button type="button" data-copy="{{ $platform->job_url }}" class="btn btn-ghost btn-sm btn-square rounded-lg" title="Copy job URL">
                                            <i class="fa-regular fa-copy text-xs"></i>
                                        </button>
                                    </div>
                                @endif

                                @if(!$platform->base_url && !$platform->job_url)
                                    <p class="py-4 text-sm text-base-content/45">No platform links have been added.</p>
                                @endif
                            </div>
                        </article>

                        {{-- Timeline --}}
                        <article id="section-timeline" class="scroll-mt-4 sm:rounded-2xl border border-base-300/70 bg-base-100 p-5 shadow-sm sm:p-6">
                            <h2 class="border-l-4 pl-3 text-lg font-bold tracking-tight" style="border-color:{{ $accent }};">Platform timeline</h2>
                            <div class="relative mt-5 space-y-6 pl-1">
                                <div class="pointer-events-none absolute bottom-3 left-[15px] top-3 w-px bg-base-300"></div>

                                <div class="relative flex gap-4">
                                    <div class="relative z-10 flex h-8 w-8 shrink-0 items-center justify-center rounded-full border-2 border-base-100" style="background:{{ $accentTint }}; color:{{ $accent }};">
                                        <i class="fa-solid fa-flag text-[11px]"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold">Platform established</p>
                                        <p class="mt-0.5 text-xs text-base-content/50">
                                            {{ $foundedLabel }}
                                            @if($marketAge!==null)
                                                · {{ $marketAge }}{{ $marketAge===1?' year':' years' }} in operation
                                            @endif
                                        </p>
                                    </div>
                                </div>

                                <div class="relative flex gap-4">
                                    <div class="relative z-10 flex h-8 w-8 shrink-0 items-center justify-center rounded-full border-2 border-base-100 bg-success/10 text-success">
                                        <i class="fa-solid fa-shield-check text-[11px]"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold">Verification status</p>
                                        <p class="mt-0.5 text-xs text-base-content/50">
                                            {{ $verificationLabel }}
                                            @if($verifiedAt)
                                                · {{ $verifiedAt->format('d M, Y') }}
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </article>

                        {{-- Metadata --}}
                        <article id="section-metadata" class="scroll-mt-4 sm:rounded-2xl border border-base-300/70 bg-base-100 p-5 shadow-sm sm:p-6">
                            <h2 class="border-l-4 pl-3 text-lg font-bold tracking-tight" style="border-color:{{ $accent }};">Record information</h2>
                            <dl class="mt-3 grid grid-cols-1 gap-x-6 divide-y divide-base-300/70 sm:divide-y-0">
                                <div class="flex items-center justify-between gap-4 py-2.5 sm:border-b sm:border-base-300/70">
                                    <dt class="text-sm text-base-content/55">Slug</dt>
                                    <dd class="cv-mono truncate text-xs font-medium text-base-content/70">{{ $platform->slug }}</dd>
                                </div>
                                <div class="flex items-center justify-between gap-4 py-2.5 sm:border-b sm:border-base-300/70">
                                    <dt class="text-sm text-base-content/55">Sort order</dt>
                                    <dd class="text-sm font-semibold">{{ $platform->sort_order }}</dd>
                                </div>
                                @if($platform->created_at)
                                    <div class="flex items-center justify-between gap-4 py-2.5">
                                        <dt class="text-sm text-base-content/55">Added to CareerVault</dt>
                                        <dd class="text-sm font-semibold">{{ $platform->created_at->format('d M, Y') }}</dd>
                                    </div>
                                @endif
                                @if($platform->updated_at)
                                    <div class="flex items-center justify-between gap-4 py-2.5">
                                        <dt class="text-sm text-base-content/55">Last updated</dt>
                                        <dd class="text-sm font-semibold">{{ $platform->updated_at->format('d M, Y') }}</dd>
                                    </div>
                                @endif
                            </dl>
                        </article>
                    </section>
                </div>
            </main>

            {{-- RIGHT: PLATFORM DIRECTORY — sticky, fixed to the top of the viewport as the page scrolls. --}}
            <aside class="min-w-0 lg:col-span-3">
                <div class="lg:sticky lg:top-16 lg:z-20">
                    <section class="overflow-hidden rounded-2xl border border-base-300/70 bg-base-100 shadow-sm">
                        <div class="border-b border-base-300/70 p-4 sm:p-5">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <h2 class="text-base font-bold tracking-tight">Platform directory</h2>
                                    <p class="mt-0.5 text-xs text-base-content/45"> {{ $platforms->count() }} platforms on record </p>
                                </div>

                                <a href="{{ route('platforms.index') }}" class="btn btn-ghost btn-sm btn-square rounded-lg" title="View all platforms">
                                    <i class="fa-solid fa-arrow-up-right-from-square text-xs"></i>
                                </a>
                            </div>

                            <label class="input input-sm mt-4 flex h-9 w-full items-center gap-2 rounded-lg border-base-300 bg-base-200/40">
                                <i class="fa-solid fa-magnifying-glass text-xs text-base-content/35"></i>
                                <input type="search" id="platformDirectorySearch" placeholder="Search platforms..." class="grow text-xs font-medium" autocomplete="off">
                            </label>
                        </div>

                        <div id="platformDirectoryList" class="max-h-[calc(100vh-11rem)] overflow-y-auto p-2">
                            @forelse($platforms as $directoryPlatform)
                                @php
                                    $directoryInitials=collect(preg_split('/\s+/',$directoryPlatform->name))->filter()->take(2)->map(fn($word)=>mb_strtoupper(mb_substr($word,0,1)))->implode('');
                                    $directoryInitials=$directoryInitials?:'P';
                                    $directoryAccent=$directoryPlatform->color?:'#4f46e5';
                                    $isCurrent=$directoryPlatform->id===$platform->id;
                                @endphp

                                <a href="{{ route('platforms.show',$directoryPlatform) }}" class="group relative mb-1 flex items-center gap-3 rounded-lg p-2.5 transition {{ $isCurrent?'bg-base-200':'hover:bg-base-200/60' }}"
                                  data-platform-directory-item data-platform-name="{{ strtolower($directoryPlatform->name) }}" @if($isCurrent) style="box-shadow:inset 2px 0 0 {{ $directoryAccent }};" @endif>
                                    <div class="flex h-9 w-9 shrink-0 items-center justify-center overflow-hidden rounded-lg text-[10px] font-bold text-white" style="{{ $directoryPlatform->color ? 'background-color:'.$directoryPlatform->color.'12' : '' }}">
                                        @if($directoryPlatform->logo)
                                            <img src="{{ asset('storage/'.$directoryPlatform->logo) }}" alt="{{ $directoryPlatform->name }}" class="h-full w-full bg-white object-contain p-1">
                                        @elseif($directoryPlatform->icon)
                                            <i class="{{ $directoryPlatform->icon }} text-lg" style="{{ $directoryPlatform->color ? 'color:'.$directoryPlatform->color : '' }}"></i>
                                        @else
                                            <span style="color:{{ $directoryAccent }};">{{ $directoryInitials }}</span>
                                        @endif
                                    </div>

                                    <div class="min-w-0 flex-1">
                                        <div class="flex items-center gap-2">
                                            <p class="truncate text-sm font-semibold {{ $isCurrent?'':'text-base-content' }}"> {{ $directoryPlatform->name }} </p>
                                            @if($isCurrent)
                                                <span class="shrink-0 text-[10px] font-medium text-base-content/40">current</span>
                                            @endif
                                        </div>

                                        <div class="mt-0.5 flex items-center gap-1.5 text-[11px] text-base-content/40">
                                            @if($directoryPlatform->job_type)
                                                <span>{{ $directoryPlatform->job_type }}</span>
                                            @endif

                                            @if($directoryPlatform->job_type && $directoryPlatform->is_active)
                                                <span>·</span>
                                            @endif

                                            @if($directoryPlatform->is_active)
                                                <span class="inline-flex items-center gap-1">
                                                    <span class="h-1.5 w-1.5 rounded-full bg-success"></span> Active
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <i class="fa-solid fa-chevron-right shrink-0 text-[9px] text-base-content/20 transition group-hover:translate-x-0.5"></i>
                                </a>
                            @empty
                                <div class="p-8 text-center">
                                    <i class="fa-solid fa-layer-group mb-3 text-2xl text-base-content/20"></i>
                                    <p class="text-sm font-semibold text-base-content/50">No platforms found.</p>
                                </div>
                            @endforelse

                            <div id="platformDirectoryEmpty" class="hidden p-8 text-center">
                                <i class="fa-solid fa-magnifying-glass mb-3 text-2xl text-base-content/20"></i>
                                <p class="text-sm font-semibold text-base-content/50">No matching platforms.</p>
                            </div>
                        </div>

                        <div class="border-t border-base-300/70 bg-base-200/30 p-3">
                            <a href="{{ route('platforms.index') }}" class="flex items-center justify-center gap-2 rounded-lg py-2.5 text-xs font-semibold transition hover:bg-base-200" style="color:{{ $accent }};">
                                Browse all platforms <i class="fa-solid fa-arrow-right text-[10px]"></i>
                            </a>
                        </div>
                    </section>
                </div>
            </aside>
        </div>
    </div>
</div>

{{-- Back to top --}}
<a href="#cv-top" class="fixed bottom-6 right-6 z-30 flex h-11 w-11 items-center justify-center rounded-full border border-base-300 bg-blue-200 text-base-content/60 shadow-lg transition hover:text-base-content" title="Back to top" aria-label="Back to top">
    <i class="fa-solid fa-arrow-up text-sm"></i>
</a>

{{-- =============================================================
     COVER LIGHTBOX
============================================================= --}}
@if($platform->cover_image)
    <dialog id="coverLightbox" class="modal">
        <div class="modal-box max-w-6xl overflow-hidden rounded-2xl bg-black p-2">
            <button type="button" data-cover-close class="btn btn-circle btn-sm absolute right-4 top-4 z-20 border-white/10 bg-black/50 text-white hover:bg-black/70">
                <i class="fa-solid fa-xmark"></i>
            </button>

            <img src="{{ asset('storage/'.$platform->cover_image) }}" alt="{{ $platform->name }} cover image" class="max-h-[80vh] w-full rounded-xl object-contain">
        </div>

        <form method="dialog" class="modal-backdrop">
            <button data-cover-close>close</button>
        </form>
    </dialog>
@endif

{{-- =============================================================
     COPY TOAST
============================================================= --}}
<div id="copyToast" class="pointer-events-none fixed bottom-5 left-1/2 z-[100] -translate-x-1/2 translate-y-3 rounded-xl border border-base-300 bg-base-100 px-4 py-3 text-sm font-semibold opacity-0 shadow-xl transition-all duration-300">
    <span class="flex items-center gap-2">
        <i class="fa-solid fa-check text-success"></i>
        <span id="copyToastLabel">URL copied</span>
    </span>
</div>

{{-- Inlined directly (not @push('styles')) so this always renders
     even if the layout doesn't declare @stack('styles'). --}}
<style>
    @import url('https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@400;500;600;700&family=IBM+Plex+Mono:wght@500&display=swap');

    .cv-page{
        font-family:'IBM Plex Sans',ui-sans-serif,system-ui,sans-serif;
    }
    .cv-mono{
        font-family:'IBM Plex Mono',ui-monospace,monospace;
    }

    .cv-page a:focus-visible,
    .cv-page button:focus-visible,
    .cv-page input:focus-visible{
        outline:2px solid currentColor;
        outline-offset:2px;
    }

    @media (prefers-reduced-motion: no-preference){
        html{
            scroll-behavior:smooth;
        }
    }

    @media (prefers-reduced-motion: reduce){
        .cv-page *{
            transition-duration:0.01ms !important;
        }
    }

    #platformDirectoryList::-webkit-scrollbar{
        width:5px;
    }
    #platformDirectoryList::-webkit-scrollbar-track{
        background:transparent;
    }
    #platformDirectoryList::-webkit-scrollbar-thumb{
        background:oklch(var(--bc)/.12);
        border-radius:999px;
    }
    #platformDirectoryList::-webkit-scrollbar-thumb:hover{
        background:oklch(var(--bc)/.22);
    }
</style>

{{-- Inlined directly (not @push('scripts')) for the same reason — guarantees this runs even if @stack('scripts') is missing or misplaced in the layout. --}}
<script>
function initPlatformDetailPage(){
    // Guard against double-binding if this script ever runs twice (e.g. a Livewire/Turbo-style partial re-render).
    if(document.body.dataset.platformDetailBound==='1'){
        return;
    }
    document.body.dataset.platformDetailBound='1';

    const searchInput=document.getElementById('platformDirectorySearch');
    const items=[...document.querySelectorAll('[data-platform-directory-item]')];
    const emptyState=document.getElementById('platformDirectoryEmpty');

    if(searchInput){
        searchInput.addEventListener('input',function(){
            const term=this.value.trim().toLowerCase();
            let visible=0;

            items.forEach(item=>{
                const name=item.dataset.platformName||'';
                const match=name.includes(term);

                item.classList.toggle('hidden',!match);

                if(match){
                    visible++;
                }
            });

            if(emptyState){
                emptyState.classList.toggle('hidden',visible!==0);
            }
        });
    }

    document.querySelectorAll('[data-copy]').forEach(button=>{
        button.addEventListener('click',async function(){
            const value=this.dataset.copy;

            try{
                await navigator.clipboard.writeText(value);
                showCopyToast();
            }catch(error){
                const textarea=document.createElement('textarea');
                textarea.value=value;
                textarea.style.position='fixed';
                textarea.style.opacity='0';
                document.body.appendChild(textarea);
                textarea.select();
                document.execCommand('copy');
                textarea.remove();
                showCopyToast();
            }
        });
    });

    // Cover lightbox — wired via addEventListener (not inline onclick) so it keeps working under a strict CSP.
    const coverModal=document.getElementById('coverLightbox');

    if(coverModal){
        document.querySelectorAll('[data-cover-trigger]').forEach(trigger=>{
            trigger.addEventListener('click',function(){
                coverModal.showModal();
            });
        });

        document.querySelectorAll('[data-cover-close]').forEach(closer=>{
            closer.addEventListener('click',function(event){
                event.preventDefault();
                coverModal.close();
            });
        });
    }
}

// Run immediately if the DOM is already parsed (covers client-side navigation / partial swaps where DOMContentLoaded won't fire again), otherwise wait for it as usual.
if(document.readyState==='loading'){
    document.addEventListener('DOMContentLoaded',initPlatformDetailPage);
}else{
    initPlatformDetailPage();
}

function showCopyToast(){
    const toast=document.getElementById('copyToast');

    if(!toast){
        return;
    }

    toast.classList.remove('opacity-0','translate-y-3');
    toast.classList.add('opacity-100','translate-y-0');

    clearTimeout(window.copyToastTimer);

    window.copyToastTimer=setTimeout(()=>{
        toast.classList.add('opacity-0','translate-y-3');
        toast.classList.remove('opacity-100','translate-y-0');
    },1800);
}
</script>
@endsection