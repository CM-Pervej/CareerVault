@extends('layouts.app')

@section('title', $company->name)

@section('content')
@php
    // --- Profile completeness (every row is computed from real fields) --------
    $fieldMeta = [
        'website'      => ['label' => 'Website',       'filled' => !empty($company->website),           'icon' => 'fa-globe'],
        'career_page'  => ['label' => 'Career page',    'filled' => !empty($company->career_page),       'icon' => 'fa-briefcase'],
        'emails'       => ['label' => 'Email address',  'filled' => !empty($company->emails),            'icon' => 'fa-envelope'],
        'phones'       => ['label' => 'Phone number',   'filled' => !empty($company->phones),            'icon' => 'fa-phone'],
        'address'      => ['label' => 'Office address', 'filled' => !empty($company->address),           'icon' => 'fa-location-dot'],
        'industries'   => ['label' => 'Industry tags',  'filled' => $company->industries->isNotEmpty(),  'icon' => 'fa-industry'],
        'countries'    => ['label' => 'Countries',      'filled' => $company->countries->isNotEmpty(),   'icon' => 'fa-earth-americas'],
        'cities'       => ['label' => 'Cities',         'filled' => $company->cities->isNotEmpty(),      'icon' => 'fa-city'],
        'social_links' => ['label' => 'Social links',   'filled' => !empty($company->social_links),      'icon' => 'fa-share-nodes'],
    ];
    $filledCount  = collect($fieldMeta)->filter(fn ($f) => $f['filled'])->count();
    $totalCount   = count($fieldMeta);
    $completeness = $totalCount > 0 ? (int) round(($filledCount / $totalCount) * 100) : 0;

    $ringRadius        = 46;
    $ringCircumference  = 2 * M_PI * $ringRadius;
    $ringOffset         = $ringCircumference * (1 - $completeness / 100);

    // --- Deterministic color rotation for "type" badges -----------------------
    $badgeColors = ['badge-primary', 'badge-secondary', 'badge-accent', 'badge-info', 'badge-success', 'badge-warning'];
    $colorFor = fn (string $seed) => $badgeColors[crc32(strtolower($seed)) % count($badgeColors)];

    $websiteHost = $company->website ? (parse_url($company->website, PHP_URL_HOST) ?: $company->website) : null;
    $careerHost  = $company->career_page ? (parse_url($company->career_page, PHP_URL_HOST) ?: $company->career_page) : null;

    // --- Primary contact points, used for the one-tap action bar --------------
    $emailsCollection = collect($company->emails ?? []);
    $phonesCollection = collect($company->phones ?? []);

    $primaryEmail = $emailsCollection->first(fn ($e) => str_contains(strtolower($e['email_type'] ?? ''), 'primary'))
        ?? $emailsCollection->first();
    $primaryPhone = $phonesCollection->first(fn ($p) => str_contains(strtolower($p['phone_type'] ?? ''), 'primary'))
        ?? $phonesCollection->first();

    // --- Data quality checks: only shown when something is actually wrong -----
    $duplicateEmails = $emailsCollection->pluck('email')
        ->filter()->map(fn ($e) => strtolower(trim($e)))
        ->duplicates()->unique()->values();
    $duplicatePhones = $phonesCollection->pluck('phone')
        ->filter()->map(fn ($p) => preg_replace('/\D+/', '', $p))
        ->duplicates()->unique()->values();
    $invalidEmails = $emailsCollection->pluck('email')
        ->filter(fn ($e) => $e && !filter_var($e, FILTER_VALIDATE_EMAIL))
        ->values();
    $invalidPhones = $phonesCollection->pluck('phone')
        ->filter(fn ($p) => $p && strlen(preg_replace('/\D+/', '', $p)) < 7)
        ->values();
    $brokenLinks = collect([
            'Website'     => $company->website,
            'Career page' => $company->career_page,
        ])
        ->filter(fn ($url) => $url && !filter_var($url, FILTER_VALIDATE_URL))
        ->keys();
    $hasDataIssues = $duplicateEmails->isNotEmpty() || $duplicatePhones->isNotEmpty()
        || $invalidEmails->isNotEmpty() || $invalidPhones->isNotEmpty() || $brokenLinks->isNotEmpty();

    // --- Hero industry teaser: capped so hero height never depends on how
    //     many industries this particular company happens to have ----------
    $heroIndustryLimit = 5;
    $visibleIndustries = $company->industries->take($heroIndustryLimit);
    $extraIndustriesCount = max(0, $company->industries->count() - $heroIndustryLimit);

    // --- Top KPI strip: literal Tailwind classes (not concatenated) so the
    //     build's CSS purge always picks them up -----------------------------
    $statStyles = [
        'Industries'   => ['bg' => 'bg-primary/10',   'text' => 'text-primary'],
        'Countries'    => ['bg' => 'bg-secondary/10', 'text' => 'text-secondary'],
        'Cities'       => ['bg' => 'bg-accent/10',    'text' => 'text-accent'],
        'Emails'       => ['bg' => 'bg-info/10',      'text' => 'text-info'],
        'Phones'       => ['bg' => 'bg-success/10',   'text' => 'text-success'],
        'Offices'      => ['bg' => 'bg-warning/10',   'text' => 'text-warning'],
        'Social Links' => ['bg' => 'bg-neutral/10',   'text' => 'text-neutral'],
    ];
    $stats = collect([
        ['label' => 'Industries',   'value' => $company->industries?->count(), 'icon' => 'fa-industry'],
        ['label' => 'Countries',    'value' => $company->countries?->count(), 'icon' => 'fa-earth-americas'],
        ['label' => 'Cities',       'value' => $company->cities?->count(), 'icon' => 'fa-city'],
        ['label' => 'Emails',       'value' => !empty($company->emails) ? count($company->emails) : null, 'icon' => 'fa-envelope'],
        ['label' => 'Phones',       'value' => !empty($company->phones) ? count($company->phones) : null, 'icon' => 'fa-phone'],
        ['label' => 'Offices',      'value' => !empty($company->address) ? count($company->address) : null, 'icon' => 'fa-location-dot'],
        ['label' => 'Social Links', 'value' => !empty($company->social_links) ? count($company->social_links) : null, 'icon' => 'fa-share-nodes'],
    ])->filter(fn ($s) => !is_null($s['value']))->values();

    // --- Payload for client-side export (vCard / JSON / CSV / summary).
    //     Rendered into a JSON <script> tag below and read by company.js —
    //     no PHP touches the JS file itself. -------------------------------
    $companyExportData = [
        'id'           => $company->id,
        'name'         => $company->name,
        'website'      => $company->website,
        'career_page'  => $company->career_page,
        'emails'       => $company->emails,
        'phones'       => $company->phones,
        'address'      => $company->address,
        'industries'   => $company->industries->pluck('name'),
        'countries'    => $company->countries->pluck('name'),
        'cities'       => $company->cities->pluck('name'),
        'social_links' => $company->social_links,
    ];
@endphp

<div class="cv-company-show max-w-7xl mx-auto" id="cv-company-root" data-company-id="{{ $company->id }}">
    {{-- Breadcrumb --}}
    <div class="breadcrumbs text-sm mb-4 no-print">
        <ul>
            <li><a href="{{ route('dashboard') }}"><i class="fa-solid fa-house mr-1"></i> Dashboard</a></li>
            <li><a href="{{ route('companies.index') }}">Companies</a></li>
            <li class="font-semibold">{{ $company->name }}</li>
        </ul>
    </div>

    {{-- ============================== HERO ============================== --}}
    <div class="cv-hero relative rounded-2xl overflow-visible border border-base-300 shadow-sm mb-4">
        <div class="h-28 sm:h-32 md:h-40 w-full bg-gradient-to-br from-primary via-primary/85 to-secondary relative overflow-hidden rounded-t-2xl">
            <div class="absolute inset-0 opacity-[0.15]" style="background-image: radial-gradient(circle at 1px 1px, white 1.5px, transparent 1.5px); background-size: 26px 26px;"></div>
            <div class="absolute -right-10 -top-10 w-56 h-56 rounded-full bg-white/10 blur-2xl"></div>
            <a href="{{ route('companies.index') }}" aria-label="Back to companies" class="no-print absolute top-3 left-3 btn btn-sm btn-circle bg-base-100/90 hover:bg-base-100 border-none shadow-sm backdrop-blur">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
        </div>

        {{-- Row 1: identity --}}
        <div class="bg-base-100 px-4 sm:px-6 md:px-8 pt-0 pb-4">
            <div class="flex flex-col sm:flex-row sm:items-end gap-4 -mt-10 sm:-mt-12">
                <div class="relative w-20 h-20 sm:w-24 sm:h-24 md:w-28 md:h-28 shrink-0">
                    <svg class="absolute inset-0 w-full h-full -rotate-90" viewBox="0 0 100 100">
                        <circle cx="50" cy="50" r="{{ $ringRadius }}" fill="none" stroke="currentColor" stroke-width="5" class="text-base-300"/>
                        <circle cx="50" cy="50" r="{{ $ringRadius }}" fill="none" stroke="currentColor" stroke-width="5" stroke-linecap="round" class="text-primary transition-all duration-700" stroke-dasharray="{{ $ringCircumference }}" stroke-dashoffset="{{ $ringOffset }}"/>
                    </svg>

                    <div class="absolute inset-2 rounded-full bg-gradient-to-br from-primary to-secondary text-primary-content flex items-center justify-center text-2xl sm:text-3xl md:text-4xl font-bold shadow-lg ring-4 ring-base-100">
                        {{ strtoupper(substr($company->name, 0, 1)) }}
                    </div>

                    <div class="tooltip absolute -bottom-1 -right-1" data-tip="{{ $completeness }}% profile complete">
                        <div class="w-6 h-6 sm:w-7 sm:h-7 rounded-full {{ $completeness === 100 ? 'bg-success text-success-content' : 'bg-base-100 text-primary border border-base-300' }} flex items-center justify-center text-[10px] font-bold shadow">
                            @if($completeness === 100)
                                <i class="fa-solid fa-check"></i>
                            @else
                                {{ $completeness }}
                            @endif
                        </div>
                    </div>
                </div>

                <div class="min-w-0 flex-1">
                    <div class="text-[11px] font-semibold uppercase tracking-wider text-primary/70 mb-0.5">Company Profile</div>
                    <h1 class="text-xl sm:text-2xl md:text-3xl font-bold leading-tight truncate"> {{ $company->name }} </h1>

                    <div class="flex flex-wrap items-center gap-1.5 mt-2">
                        @forelse($visibleIndustries as $industry)
                            <span class="badge badge-secondary badge-outline badge-sm">
                                {{ $industry->name }}
                            </span>
                        @empty
                            <span class="text-xs text-base-content/40 italic">
                                No industries tagged yet
                            </span>
                        @endforelse

                        @if($extraIndustriesCount > 0)
                            <a href="#section-industries" class="badge badge-ghost badge-sm no-print hover:badge-secondary" title="See all industries">
                                +{{ $extraIndustriesCount }} more
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Row 2: action bar --}}
        <div class="no-print px-4 sm:px-6 md:px-8 pb-5 pt-4 border-t border-base-300 flex flex-wrap items-center gap-2 bg-base-100 rounded-b-2xl">
            @if($primaryPhone)
                <a href="tel:{{ $primaryPhone['phone'] }}" class="btn btn-sm btn-outline" title="Call {{ $primaryPhone['phone'] }}">
                    <i class="fa-solid fa-phone"></i>
                    <span class="hidden lg:inline">Call</span>
                </a>
            @endif

            @if($primaryEmail)
                <a href="mailto:{{ $primaryEmail['email'] }}" class="btn btn-sm btn-outline" title="Email {{ $primaryEmail['email'] }}">
                    <i class="fa-solid fa-envelope"></i>
                    <span class="hidden lg:inline">Email</span>
                </a>
            @endif

            @if($company->website)
                <a href="{{ $company->website }}" target="_blank" rel="noopener" class="btn btn-sm btn-outline" title="Visit website">
                    <i class="fa-solid fa-globe"></i>
                    <span class="hidden lg:inline">Visit</span>
                </a>
            @endif

            <button type="button" class="btn btn-sm btn-outline" data-action="share" title="Share this profile">
                <i class="fa-solid fa-share-nodes"></i>
                <span class="hidden lg:inline">Share</span>
            </button>

            {{-- Export --}}
            <details class="dropdown dropdown-end relative">
                <summary class="btn btn-sm btn-outline list-none">
                    <i class="fa-solid fa-download"></i>
                    <span class="hidden lg:inline">Export</span>
                </summary>

                <ul class="menu menu-sm dropdown-content z-[100] mt-2 w-56 rounded-box bg-base-100 border border-base-300 shadow-xl">
                    <li>
                        <button type="button" data-action="download-vcard">
                            <i class="fa-solid fa-address-card w-4"></i> Save as vCard (.vcf)
                        </button>
                    </li>

                    <li>
                        <button type="button" data-action="download-json">
                            <i class="fa-solid fa-file-code w-4"></i> Export as JSON
                        </button>
                    </li>

                    <li>
                        <button type="button" data-action="download-csv">
                            <i class="fa-solid fa-file-csv w-4"></i> Export contacts as CSV
                        </button>
                    </li>

                    <li>
                        <button type="button" data-action="copy-summary">
                            <i class="fa-solid fa-copy w-4"></i> Copy summary
                        </button>
                    </li>

                    <li>
                        <button type="button" data-action="show-qr">
                            <i class="fa-solid fa-qrcode w-4"></i> Show QR code
                        </button>
                    </li>

                    <li>
                        <button type="button" data-action="print">
                            <i class="fa-solid fa-print w-4"></i> Print profile
                        </button>
                    </li>
                </ul>
            </details>

            @auth
                <a href="{{ route('companies.edit', $company) }}" class="btn btn-sm btn-primary sm:ml-auto" title="Edit company">
                    <i class="fa-solid fa-pen"></i>
                    <span class="hidden lg:inline">Edit</span>
                </a>
            @endauth
        </div>
    </div>

    {{-- Sticky, scroll-spied quick navigation. Adjust the top offset below
         if your app shell has its own fixed header. --}}
    <div id="cv-quick-nav" class="no-print sticky top-16 z-30 -mx-4 sm:mx-0 px-4 sm:px-0 py-2 mb-6 bg-base-100/95 supports-[backdrop-filter]:bg-base-100/80 backdrop-blur border-b border-base-300 transition-shadow">
        <div class="flex items-center gap-2 overflow-x-auto no-scrollbar">
            <a href="#section-overview" data-nav="overview" class="cv-nav-link btn btn-xs btn-ghost border border-base-300 shrink-0">Overview</a>
            <a href="#section-contacts" data-nav="contacts" class="cv-nav-link btn btn-xs btn-ghost border border-base-300 shrink-0">Contacts</a>
            <a href="#section-locations" data-nav="locations" class="cv-nav-link btn btn-xs btn-ghost border border-base-300 shrink-0">Locations</a>
            @if($company->countries->isNotEmpty())
                <a href="#section-countries" data-nav="countries" class="cv-nav-link btn btn-xs btn-ghost border border-base-300 shrink-0">Countries</a>
            @endif
            @if($company->cities->isNotEmpty())
                <a href="#section-cities" data-nav="cities" class="cv-nav-link btn btn-xs btn-ghost border border-base-300 shrink-0">Cities</a>
            @endif
            <a href="#section-completeness" data-nav="completeness" class="cv-nav-link btn btn-xs btn-ghost border border-base-300 shrink-0">Completeness</a>
            @if($company->industries->isNotEmpty())
                <a href="#section-industries" data-nav="industries" class="cv-nav-link btn btn-xs btn-ghost border border-base-300 shrink-0">Industries</a>
            @endif
            @if(!empty($company->social_links))
                <a href="#section-social" data-nav="social" class="cv-nav-link btn btn-xs btn-ghost border border-base-300 shrink-0">Digital Presence</a>
            @endif
        </div>
    </div>

    {{-- ======================= TOP KPI STRIP (full width) ======================= --}}
    @if($stats->isNotEmpty())
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-7 gap-3 mb-6">
        @foreach($stats as $stat)
        @php $style = $statStyles[$stat['label']] ?? ['bg' => 'bg-primary/10', 'text' => 'text-primary']; @endphp
        <div class="cv-stat-card card bg-base-100 border border-base-300 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all">
            <div class="card-body p-4 flex-row items-center gap-3">
                <div class="w-10 h-10 rounded-lg {{ $style['bg'] }} {{ $style['text'] }} flex items-center justify-center shrink-0">
                    <i class="fa-solid {{ $stat['icon'] }}"></i>
                </div>
                <div class="min-w-0">
                    <div class="text-xl font-bold leading-none">{{ $stat['value'] }}</div>
                    <div class="text-[11px] uppercase tracking-wide text-base-content/50 truncate">{{ $stat['label'] }}</div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif

    {{-- Data-quality banner — only appears when there's something real to flag --}}
    @if($hasDataIssues)
    <div class="alert alert-warning mb-6 no-print items-start">
        <i class="fa-solid fa-triangle-exclamation mt-0.5"></i>
        <div class="text-sm space-y-0.5">
            <div class="font-semibold">Data quality issues found on this record:</div>
            <ul class="list-disc list-inside text-warning-content/90">
                @if($duplicateEmails->isNotEmpty())
                    <li>{{ $duplicateEmails->count() }} repeated {{ Str::plural('email', $duplicateEmails->count()) }} ({{ $duplicateEmails->join(', ') }})</li>
                @endif
                @if($duplicatePhones->isNotEmpty())
                    <li>{{ $duplicatePhones->count() }} repeated {{ Str::plural('phone number', $duplicatePhones->count()) }}</li>
                @endif
                @if($invalidEmails->isNotEmpty())
                    <li>{{ $invalidEmails->count() }} malformed {{ Str::plural('email', $invalidEmails->count()) }} ({{ $invalidEmails->join(', ') }})</li>
                @endif
                @if($invalidPhones->isNotEmpty())
                    <li>{{ $invalidPhones->count() }} {{ Str::plural('phone number', $invalidPhones->count()) }} too short to be valid</li>
                @endif
                @if($brokenLinks->isNotEmpty())
                    <li>{{ $brokenLinks->join(' and ') }} {{ $brokenLinks->count() > 1 ? "don't" : "doesn't" }} look like a valid URL</li>
                @endif
            </ul>
        </div>
        @auth
            <a href="{{ route('companies.edit', $company) }}" class="btn btn-xs btn-warning btn-outline shrink-0">Review</a>
        @endauth
    </div>
    @endif

    {{-- ============================== BODY ============================== --}}
    <div class="grid lg:grid-cols-3 gap-6">
        {{-- ----------------------------- LEFT COLUMN (wide) ----------------------------- --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Overview --}}
            <div id="section-overview" class="card bg-base-100 shadow-sm border border-base-300 scroll-mt-20">
                <div class="card-body">
                    <div class="flex items-center gap-2 mb-5">
                        <div class="w-8 h-8 rounded-lg bg-primary/10 text-primary flex items-center justify-center shrink-0">
                            <i class="fa-solid fa-building text-sm"></i>
                        </div>
                        <h2 class="text-lg font-semibold">Overview</h2>
                    </div>

                    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-5">
                        {{-- Website --}}
                        <div class="flex items-start gap-3">
                            <div class="mt-0.5 shrink-0 w-9 h-9 rounded-lg bg-primary/10 text-primary flex items-center justify-center">
                                <i class="fa-solid fa-globe"></i>
                            </div>
                            <div class="min-w-0 flex-1">
                                <dt class="text-xs font-medium uppercase tracking-wide text-base-content/50">Website</dt>
                                <dd class="mt-0.5 text-sm">
                                    @if($company->website)
                                        <div class="flex items-center gap-1.5 group">
                                            <a href="{{ $company->website }}" target="_blank" rel="noopener" title="{{ $company->website }}" class="link link-primary no-underline hover:underline font-mono truncate">
                                                {{ $websiteHost }}
                                            </a>
                                            <i class="fa-solid fa-arrow-up-right-from-square text-[10px] text-base-content/30 group-hover:text-primary transition-colors"></i>
                                            <button type="button" class="copy-btn btn btn-ghost btn-xs btn-circle no-print" data-copy="{{ $company->website }}" title="Copy website" aria-label="Copy website URL">
                                                <i class="fa-solid fa-copy text-xs"></i>
                                            </button>
                                        </div>
                                    @else
                                        <span class="text-base-content/30">Not provided</span>
                                    @endif
                                </dd>
                            </div>
                        </div>

                        {{-- Career Page --}}
                        <div class="flex items-start gap-3">
                            <div class="mt-0.5 shrink-0 w-9 h-9 rounded-lg bg-primary/10 text-primary flex items-center justify-center">
                                <i class="fa-solid fa-briefcase"></i>
                            </div>
                            <div class="min-w-0 flex-1">
                                <dt class="text-xs font-medium uppercase tracking-wide text-base-content/50">Career Page</dt>
                                <dd class="mt-0.5 text-sm">
                                    @if($company->career_page)
                                        <div class="flex items-center gap-1.5 group">
                                            <a href="{{ $company->career_page }}" target="_blank" rel="noopener" title="{{ $company->career_page }}" class="link link-primary no-underline hover:underline font-mono truncate">
                                                {{ $careerHost }}
                                            </a>
                                            <i class="fa-solid fa-arrow-up-right-from-square text-[10px] text-base-content/30 group-hover:text-primary transition-colors"></i>
                                            <button type="button" class="copy-btn btn btn-ghost btn-xs btn-circle no-print" data-copy="{{ $company->career_page }}" title="Copy career page" aria-label="Copy career page URL">
                                                <i class="fa-solid fa-copy text-xs"></i>
                                            </button>
                                        </div>
                                    @else
                                        <span class="text-base-content/30">Not provided</span>
                                    @endif
                                </dd>
                            </div>
                        </div>

                        {{-- Company Name --}}
                        <div class="flex items-start gap-3">
                            <div class="mt-0.5 shrink-0 w-9 h-9 rounded-lg bg-primary/10 text-primary flex items-center justify-center">
                                <i class="fa-solid fa-signature"></i>
                            </div>
                            <div class="min-w-0">
                                <dt class="text-xs font-medium uppercase tracking-wide text-base-content/50">Legal Name</dt>
                                <dd class="mt-0.5 text-sm font-medium truncate">{{ $company->name }}</dd>
                            </div>
                        </div>

                        {{-- Record created --}}
                        <div class="flex items-start gap-3">
                            <div class="mt-0.5 shrink-0 w-9 h-9 rounded-lg bg-primary/10 text-primary flex items-center justify-center">
                                <i class="fa-solid fa-calendar-days"></i>
                            </div>
                            <div class="min-w-0">
                                <dt class="text-xs font-medium uppercase tracking-wide text-base-content/50">Record Created</dt>
                                <dd class="mt-0.5 text-sm font-medium" title="Last updated {{ $company->updated_at->format('d M Y, h:i A') }}">
                                    {{ $company->created_at->format('d M Y, h:i A') }}
                                </dd>
                            </div>
                        </div>
                    </dl>
                </div>
            </div>

            {{-- Emails + Phones --}}
            <div id="section-contacts" class="grid grid-cols-1 md:grid-cols-2 gap-6 scroll-mt-20">
                {{-- Email Directory --}}
                <div class="card bg-base-100 shadow-sm border border-base-300">
                    <div class="card-body">
                        <div class="flex items-center justify-between mb-3 gap-2">
                            <div class="flex items-center gap-2 min-w-0">
                                <div class="w-8 h-8 rounded-lg bg-primary/10 text-primary flex items-center justify-center shrink-0">
                                    <i class="fa-solid fa-envelope text-sm"></i>
                                </div>
                                <h2 class="text-lg font-semibold truncate">Email Directory</h2>
                            </div>
                            <div class="flex items-center gap-1.5 shrink-0">
                                @if(!empty($company->emails))
                                    <span class="badge badge-ghost badge-sm font-medium">{{ count($company->emails) }}</span>
                                    <button type="button" class="btn btn-ghost btn-xs no-print" data-action="copy-all-emails" title="Copy all emails">
                                        <i class="fa-solid fa-copy text-xs"></i> <span class="hidden sm:inline">Copy all</span>
                                    </button>
                                @endif
                            </div>
                        </div>

                        @if(!empty($company->emails))
                            @if(count($company->emails) > 4)
                                <label class="input input-sm input-bordered flex items-center gap-2 mb-3 no-print">
                                    <i class="fa-solid fa-magnifying-glass text-xs text-base-content/40"></i>
                                    <input type="text" class="cv-filter-input grow" data-target="cv-email-rows" placeholder="Filter emails...">
                                </label>
                            @endif
                            <div class="space-y-1.5" id="cv-email-rows">
                                @foreach($company->emails as $email)
                                @php $isDuplicate = $duplicateEmails->contains(strtolower(trim($email['email']))); @endphp
                                <div class="cv-filter-row flex items-center justify-between gap-2 rounded-lg px-2.5 py-2 hover:bg-base-200/60 transition-colors {{ $isDuplicate ? 'ring-1 ring-warning/50 bg-warning/5' : '' }}"
                                     data-search="{{ strtolower($email['email'].' '.$email['email_type']) }}">
                                    <div class="min-w-0 flex items-center gap-2">
                                        <span class="badge {{ $colorFor($email['email_type']) }} badge-outline badge-sm capitalize shrink-0">{{ $email['email_type'] }}</span>
                                        <a href="mailto:{{ $email['email'] }}" class="link link-primary no-underline hover:underline font-mono text-sm truncate">
                                            {{ $email['email'] }}
                                        </a>
                                        @if($isDuplicate)
                                            <span class="tooltip" data-tip="Appears more than once">
                                                <i class="fa-solid fa-triangle-exclamation text-warning text-xs"></i>
                                            </span>
                                        @endif
                                    </div>
                                    <button type="button" class="copy-btn btn btn-ghost btn-xs btn-circle shrink-0 no-print" data-copy="{{ $email['email'] }}" title="Copy email" aria-label="Copy email address">
                                        <i class="fa-solid fa-copy text-xs"></i>
                                    </button>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <div class="flex flex-col items-center justify-center text-center py-8 text-base-content/40">
                                <i class="fa-solid fa-envelope-open text-2xl mb-2"></i>
                                <p class="text-sm">No emails on file</p>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Phone Directory --}}
                <div class="card bg-base-100 shadow-sm border border-base-300">
                    <div class="card-body">
                        <div class="flex items-center justify-between mb-3 gap-2">
                            <div class="flex items-center gap-2 min-w-0">
                                <div class="w-8 h-8 rounded-lg bg-primary/10 text-primary flex items-center justify-center shrink-0">
                                    <i class="fa-solid fa-phone text-sm"></i>
                                </div>
                                <h2 class="text-lg font-semibold truncate">Phone Directory</h2>
                            </div>
                            <div class="flex items-center gap-1.5 shrink-0">
                                @if(!empty($company->phones))
                                    <span class="badge badge-ghost badge-sm font-medium">{{ count($company->phones) }}</span>
                                    <button type="button" class="btn btn-ghost btn-xs no-print" data-action="copy-all-phones" title="Copy all phone numbers">
                                        <i class="fa-solid fa-copy text-xs"></i> <span class="hidden sm:inline">Copy all</span>
                                    </button>
                                @endif
                            </div>
                        </div>

                        @if(!empty($company->phones))
                            @if(count($company->phones) > 4)
                                <label class="input input-sm input-bordered flex items-center gap-2 mb-3 no-print">
                                    <i class="fa-solid fa-magnifying-glass text-xs text-base-content/40"></i>
                                    <input type="text" class="cv-filter-input grow" data-target="cv-phone-rows" placeholder="Filter phone numbers...">
                                </label>
                            @endif
                            <div class="space-y-1.5" id="cv-phone-rows">
                                @foreach($company->phones as $phone)
                                @php $isDuplicate = $duplicatePhones->contains(preg_replace('/\D+/', '', $phone['phone'])); @endphp
                                <div class="cv-filter-row flex items-center justify-between gap-2 rounded-lg px-2.5 py-2 hover:bg-base-200/60 transition-colors {{ $isDuplicate ? 'ring-1 ring-warning/50 bg-warning/5' : '' }}"
                                     data-search="{{ strtolower($phone['phone'].' '.$phone['phone_type']) }}">
                                    <div class="min-w-0 flex items-center gap-2">
                                        <span class="badge {{ $colorFor($phone['phone_type']) }} badge-outline badge-sm capitalize shrink-0">{{ $phone['phone_type'] }}</span>
                                        <a href="tel:{{ $phone['phone'] }}" class="link link-primary no-underline hover:underline font-mono text-sm truncate">
                                            {{ $phone['phone'] }}
                                        </a>
                                        @if($isDuplicate)
                                            <span class="tooltip" data-tip="Appears more than once">
                                                <i class="fa-solid fa-triangle-exclamation text-warning text-xs"></i>
                                            </span>
                                        @endif
                                    </div>
                                    <button type="button" class="copy-btn btn btn-ghost btn-xs btn-circle shrink-0 no-print" data-copy="{{ $phone['phone'] }}" title="Copy phone" aria-label="Copy phone number">
                                        <i class="fa-solid fa-copy text-xs"></i>
                                    </button>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <div class="flex flex-col items-center justify-center text-center py-8 text-base-content/40">
                                <i class="fa-solid fa-phone-slash text-2xl mb-2"></i>
                                <p class="text-sm">No phone numbers on file</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Office Locations --}}
            <div id="section-locations" class="card bg-base-100 shadow-sm border border-base-300 scroll-mt-20">
                <div class="card-body">
                    <div class="flex items-center justify-between mb-4 gap-2">
                        <div class="flex items-center gap-2 min-w-0">
                            <div class="w-8 h-8 rounded-lg bg-primary/10 text-primary flex items-center justify-center shrink-0">
                                <i class="fa-solid fa-location-dot text-sm"></i>
                            </div>
                            <h2 class="text-lg font-semibold truncate">Office Locations</h2>
                        </div>
                        <div class="flex items-center gap-1.5 shrink-0">
                            @if(!empty($company->address))
                                <span class="badge badge-ghost badge-sm font-medium">{{ count($company->address) }}</span>
                                <button type="button" class="btn btn-ghost btn-xs no-print" data-action="copy-all-addresses" title="Copy all addresses">
                                    <i class="fa-solid fa-copy text-xs"></i> <span class="hidden sm:inline">Copy all</span>
                                </button>
                            @endif
                        </div>
                    </div>

                    @if(!empty($company->address))
                        <div class="grid sm:grid-cols-2 gap-3">
                            @foreach($company->address as $address)
                            <div class="rounded-lg border border-base-300 bg-base-200/40 p-4 hover:border-primary/40 transition-colors">
                                <div class="flex items-start justify-between gap-2 mb-2">
                                    <span class="badge {{ $colorFor($address['address_type']) }} badge-outline badge-sm capitalize">{{ $address['address_type'] }}</span>
                                    <div class="flex items-center gap-1 no-print">
                                        <button type="button" class="copy-btn btn btn-ghost btn-xs btn-circle" data-copy="{{ $address['address'] }}" title="Copy address" aria-label="Copy address">
                                            <i class="fa-solid fa-copy text-xs"></i>
                                        </button>
                                        <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($address['address']) }}" target="_blank" rel="noopener" class="btn btn-ghost btn-xs btn-circle" title="View on map" aria-label="View address on map">
                                            <i class="fa-solid fa-map-location-dot text-xs"></i>
                                        </a>
                                    </div>
                                </div>
                                <div class="text-sm text-base-content/80 leading-relaxed whitespace-pre-line">{{ $address['address'] }}</div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="flex flex-col items-center justify-center text-center py-8 text-base-content/40">
                            <i class="fa-solid fa-map-location-dot text-2xl mb-2"></i>
                            <p class="text-sm">No office locations on file</p>
                        </div>
                    @endif
                </div>
            </div>
            
            @php
            $icons = [
                'LinkedIn'     => ['icon' => 'fa-linkedin', 'color' => 'text-[#0A66C2]'],
                'Facebook'     => ['icon' => 'fa-facebook', 'color' => 'text-[#1877F2]'],
                'X (Twitter)'  => ['icon' => 'fa-x-twitter', 'color' => 'text-base-content'],
                'GitHub'       => ['icon' => 'fa-github', 'color' => 'text-base-content'],
                'Instagram'    => ['icon' => 'fa-instagram', 'color' => 'text-[#E4405F]'],
                'YouTube'      => ['icon' => 'fa-youtube', 'color' => 'text-[#FF0000]'],
                'TikTok'       => ['icon' => 'fa-tiktok', 'color' => 'text-base-content'],
                'Discord'      => ['icon' => 'fa-discord', 'color' => 'text-[#5865F2]'],
                'Telegram'     => ['icon' => 'fa-telegram', 'color' => 'text-[#26A5E4]'],
                'Pinterest'    => ['icon' => 'fa-pinterest', 'color' => 'text-[#BD081C]'],
                'Reddit'       => ['icon' => 'fa-reddit', 'color' => 'text-[#FF4500]'],
                'Medium'       => ['icon' => 'fa-medium', 'color' => 'text-base-content'],
                'Behance'      => ['icon' => 'fa-behance', 'color' => 'text-[#1769FF]'],
                'Dribbble'     => ['icon' => 'fa-dribbble', 'color' => 'text-[#EA4C89]'],
                'Website'      => ['icon' => 'fa-globe', 'color' => 'text-[#10B981]'],
                'Other'        => ['icon' => 'fa-link', 'color' => 'text-base-content/60'],
            ];
            @endphp

            @if(!empty($company->social_links))
            <div id="section-social" class="card bg-base-100 shadow-sm border border-base-300 scroll-mt-20">
                <div class="card-body">
                    <h2 class="text-lg font-semibold mb-4 text-center">Digital Presence</h2>
                    <div class="flex gap-3 flex-wrap justify-center">
                        @foreach($company->social_links as $social)
                            @php
                                $platform = $social['platform'];
                                $socialIcon = $icons[$platform] ?? ['icon' => 'fa-link', 'color' => 'text-base-content/60'];
                            @endphp
                            <a href="{{ $social['url'] }}" target="_blank" class="tooltip" data-tip="{{ $platform }}">
                                <i class="fa-brands {{ $socialIcon['icon'] }} {{ $socialIcon['color'] }} text-3xl"></i>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </div>

        {{-- ----------------------------- RIGHT SIDEBAR (light) ----------------------------- --}}
        <div class="space-y-6">
            {{-- Profile Completeness checklist — every row is live, computed from the record --}}
            <div id="section-completeness" class="card bg-base-100 shadow-sm border border-base-300 scroll-mt-20">
                <div class="card-body">
                    <div class="flex items-center justify-between mb-1">
                        <h2 class="text-lg font-semibold">Profile Completeness</h2>
                        <span class="text-sm font-bold {{ $completeness === 100 ? 'text-success' : 'text-primary' }}">{{ $completeness }}%</span>
                    </div>
                    <progress class="progress {{ $completeness === 100 ? 'progress-success' : 'progress-primary' }} w-full mb-3" value="{{ $completeness }}" max="100"></progress>

                    <ul class="space-y-1">
                        @foreach($fieldMeta as $key => $field)
                        <li class="flex items-center justify-between gap-2 py-1">
                            <div class="flex items-center gap-2 min-w-0">
                                <i class="fa-solid {{ $field['filled'] ? 'fa-circle-check text-success' : 'fa-circle text-base-300' }} text-sm shrink-0"></i>
                                <span class="text-sm {{ $field['filled'] ? 'text-base-content' : 'text-base-content/50' }} truncate">{{ $field['label'] }}</span>
                            </div>
                            @if(!$field['filled'])
                                @auth
                                    <a href="{{ route('companies.edit', $company) }}" class="text-xs link link-primary no-underline hover:underline shrink-0 no-print">Add</a>
                                @endauth
                            @endif
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            {{-- Industries --}}
            @if($company->industries->isNotEmpty())
            <div id="section-industries" class="card bg-base-100 shadow-sm border border-base-300 scroll-mt-20">
                <div class="card-body">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-lg bg-primary/10 text-primary flex items-center justify-center shrink-0">
                                <i class="fa-solid fa-industry text-sm"></i>
                            </div>
                            <h2 class="text-lg font-semibold">Industries</h2>
                        </div>
                        <span class="badge badge-ghost badge-sm font-medium">{{ $company->industries->count() }}</span>
                    </div>

                    <div class="flex flex-wrap gap-2">
                        @foreach($company->industries as $industry)
                        <span class="badge badge-secondary badge-outline">{{ $industry->name }}</span>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

                        {{-- Countries --}}
            @if($company->countries->isNotEmpty())
            <div id="section-countries" class="card bg-base-100 shadow-sm border border-base-300 scroll-mt-20">
                <div class="card-body">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-lg bg-primary/10 text-primary flex items-center justify-center shrink-0">
                                <i class="fa-solid fa-globe text-sm"></i>
                            </div>
                            <h2 class="text-lg font-semibold">Countries</h2>
                        </div>
                        <span class="badge badge-ghost badge-sm font-medium">{{ $company->countries->count() }}</span>
                    </div>

                    <div class="flex flex-wrap gap-2">
                        @foreach($company->countries as $country)
                        <div class="tooltip" data-tip="{{ $country->name }}">
                            <img src="https://flagcdn.com/w80/{{ strtolower($country->iso_code) }}.png" alt="{{ $country->name }} flag" loading="lazy" class="size-max object-cover rounded border border-base-300 hover:scale-110 transition-transform">
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            {{-- Cities --}}
            @if($company->cities->isNotEmpty())
            <div id="section-cities" class="card bg-base-100 shadow-sm border border-base-300 scroll-mt-20">
                <div class="card-body">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-lg bg-primary/10 text-primary flex items-center justify-center shrink-0">
                                <i class="fa-solid fa-city text-sm"></i>
                            </div>
                            <h2 class="text-lg font-semibold">Cities</h2>
                        </div>
                        <span class="badge badge-ghost badge-sm font-medium">{{ $company->cities->count() }}</span>
                    </div>

                    <div class="grid grid-cols-1 gap-4">
                        @foreach($company->countries as $country)
                            @php $countryCities = $citiesByCountry->get($country->id, collect()); @endphp
                            @if($countryCities->isNotEmpty())
                            <div>
                                <div class="flex items-center gap-2 mb-2">
                                    @if($country->iso_code)
                                        <img src="https://flagcdn.com/w40/{{ strtolower($country->iso_code) }}.png" alt="{{ $country->name }} flag" loading="lazy" class="w-5 h-3.5 object-cover rounded-sm border border-base-300">
                                    @endif
                                    <span class="text-sm font-semibold text-base-content/80">{{ $country->name }}</span>
                                    <span class="badge badge-ghost badge-xs">{{ $countryCities->count() }}</span>
                                </div>
                                <div class="flex flex-wrap gap-1.5">
                                    @foreach($countryCities as $city)
                                        <span class="badge badge-primary badge-outline">
                                            <i class="fa-solid fa-location-dot text-[10px] mr-1"></i>{{ $city->name }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

{{-- Scroll to top --}}
<button type="button" id="cv-scroll-top" data-action="scroll-top" class="no-print fixed bottom-6 right-6 z-40 btn btn-circle btn-primary shadow-lg opacity-0 pointer-events-none transition-opacity duration-300" aria-label="Back to top" title="Back to top">
    <i class="fa-solid fa-arrow-up"></i>
</button>

{{-- Toast --}}
<div id="cv-toast" class="toast toast-bottom toast-end hidden no-print" role="status" aria-live="polite">
    <div id="cv-toast-alert" class="alert alert-success shadow-lg py-2">
        <i id="cv-toast-icon" class="fa-solid fa-circle-check"></i>
        <span id="cv-toast-message" class="text-sm">Copied to clipboard</span>
    </div>
</div>

{{-- QR modal --}}
<dialog id="cv-qr-modal" class="modal no-print">
    <div class="modal-box max-w-xs text-center">
        <h3 class="font-semibold text-lg mb-3">Scan to open this profile</h3>
        <img id="cv-qr-image" src="" alt="QR code linking to this profile" loading="lazy" class="mx-auto rounded-lg border border-base-300" width="200" height="200">
        <p class="text-xs text-base-content/50 mt-3 break-all" id="cv-qr-url"></p>
        <div class="modal-action">
            <button type="button" class="btn btn-sm btn-outline" data-action="download-qr"><i class="fa-solid fa-download"></i> Download</button>
            <form method="dialog"><button class="btn btn-sm">Close</button></form>
        </div>
    </div>
    <form method="dialog" class="modal-backdrop"><button>close</button></form>
</dialog>

{{-- Export payload for company.js — no inline logic, just data. --}}
<script type="application/json" id="cv-company-data">@json($companyExportData)</script>

@push('styles')
<style>
    #cv-quick-nav.cv-scrolled { box-shadow: 0 2px 8px -2px rgb(0 0 0 / 0.12); }
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { scrollbar-width: none; -ms-overflow-style: none; }

    .cv-hero-gradient {
        background: linear-gradient(135deg, oklch(var(--p)) 0%, color-mix(in oklch, oklch(var(--p)) 78%, oklch(var(--s))) 55%, oklch(var(--s)) 100%);
    }
    .cv-avatar {
        background: linear-gradient(135deg, oklch(var(--p)) 0%, oklch(var(--s)) 100%);
    }
    .cv-stat-card:hover { border-color: color-mix(in oklch, oklch(var(--p)) 35%, transparent); }

    @media (prefers-reduced-motion: reduce) {
        .cv-stat-card, .cv-avatar, [class*="transition-"] { transition: none !important; }
    }

    @media print {
        .no-print { display: none !important; }
        #cv-quick-nav { position: static !important; }
    }
</style>
@endpush

@endsection