@extends('layouts.app')

@section('title', $company->name)

@section('content')
@php
    // --- Profile completeness (every row is computed from real fields) --------
    $fieldMeta = [
        'website'         => ['label' => 'Website',          'filled'  => !empty($company->website),              'icon' => 'fa-globe'],
        'career_page'     => ['label' => 'Career page',      'filled' => !empty($company->career_page),           'icon' => 'fa-briefcase'],
        'emails'          => ['label' => 'Email address',    'filled' => !empty($company->emails),                'icon' => 'fa-envelope'],
        'phones'          => ['label' => 'Phone number',     'filled' => !empty($company->phones),                'icon' => 'fa-phone'],
        'address'         => ['label' => 'Office address',   'filled' => !empty($company->address),               'icon' => 'fa-location-dot'],
        'industries'      => ['label' => 'Industry tags',    'filled' => $company->industries->isNotEmpty(),      'icon' => 'fa-industry'],
        'countries'       => ['label' => 'Countries',        'filled' => $company->countries->isNotEmpty(),       'icon' => 'fa-earth-americas'],
        'cities'          => ['label' => 'Cities',           'filled' => $company->cities->isNotEmpty(),          'icon' => 'fa-city'],
        'platforms'       => ['label' => 'Platforms', 'filled' => $company->platforms->isNotEmpty(), 'icon' => 'fa-share-nodes'],
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

    $websiteHost = $company->website ? preg_replace('#^https?://#', '', rtrim($company->website, '/')) : null;
    $careerHost  = $company->career_page ? preg_replace('#^https?://#', '', rtrim($company->career_page, '/')) : null;

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
        'Industries'       => ['bg' => 'bg-primary/10',   'text' => 'text-primary'],
        'Countries'        => ['bg' => 'bg-secondary/10', 'text' => 'text-secondary'],
        'Cities'           => ['bg' => 'bg-accent/10',    'text' => 'text-accent'],
        'Emails'           => ['bg' => 'bg-info/10',      'text' => 'text-info'],
        'Phones'           => ['bg' => 'bg-success/10',   'text' => 'text-success'],
        'Offices'          => ['bg' => 'bg-warning/10',   'text' => 'text-warning'],
        'Social Platforms' => ['bg' => 'bg-neutral/10',   'text' => 'text-neutral'],
    ];
    $stats = collect([
        ['label' => 'Industries',       'value' => $company->industries?->count(), 'icon' => 'fa-industry'],
        ['label' => 'Countries',        'value' => $company->countries?->count(), 'icon' => 'fa-earth-americas'],
        ['label' => 'Cities',           'value' => $company->cities?->count(), 'icon' => 'fa-city'],
        ['label' => 'Emails',           'value' => !empty($company->emails) ? count($company->emails) : null, 'icon' => 'fa-envelope'],
        ['label' => 'Phones',           'value' => !empty($company->phones) ? count($company->phones) : null, 'icon' => 'fa-phone'],
        ['label' => 'Offices',          'value' => !empty($company->address) ? count($company->address) : null, 'icon' => 'fa-location-dot'],
        ['label' => 'Platforms', 'value' => !empty($company->platforms) ? count($company->platforms) : null, 'icon' => 'fa-share-nodes'],
    ])->filter(fn ($s) => !is_null($s['value']))->values();

    // --- Payload for client-side export (vCard / JSON / CSV / summary).
    //     Rendered into a JSON <script> tag below and read by company.js —
    //     no PHP touches the JS file itself. -------------------------------
    $companyExportData = [
        'id'              => $company->id,
        'name'            => $company->name,
        'website'         => $company->website,
        'career_page'     => $company->career_page,
        'emails'          => $company->emails,
        'phones'          => $company->phones,
        'address'         => $company->address,
        'industries'      => $company->industries->pluck('name'),
        'countries'       => $company->countries->pluck('name'),
        'cities'          => $company->cities->pluck('name'),
        'platforms' => $company->platforms->pluck('name'),
    ];
@endphp

<div class="cv-company-show max-w-7xl mx-auto" id="cv-company-root" data-company-id="{{ $company->id }}">
    {{-- ============================== HERO ============================== --}}
    <div class="cv-hero relative  rounded-none sm:rounded-2xl overflow-visible border border-base-300 shadow-sm mb-4">
        @include('job.company.partials.hero')
    </div>

    {{-- Sticky navigation --}}
    <div id="cv-quick-nav" class="hidden sm:block no-print sticky top-16 z-30 -mx-4 sm:mx-0 px-4 sm:px-0 py-2 mb-6 bg-base-100/95 supports-[backdrop-filter]:bg-base-100/80 backdrop-blur border-b border-base-300 transition-shadow">
        @include('job.company.partials.navbar')
    </div>

    {{-- ======================= TOP KPI STRIP (full width) ======================= --}}
    <div class="hidden sm:block">
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
    </div>

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
    <main class="grid lg:grid-cols-3 sm:gap-6 -mt-3 sm:mt-0">
        {{-- ----------------------------- LEFT COLUMN (wide) ----------------------------- --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Overview --}}
            <div id="section-overview" class="card bg-base-100 shadow-sm border border-base-300 scroll-mt-20">
                @include('job.company.partials.overview')
            </div>

            {{-- Emails + Phones --}}
            <div id="section-contacts" class="grid grid-cols-1 md:grid-cols-2 gap-6 scroll-mt-20 -mt-5 sm:mt-0">
                @include('job.company.partials.contact')
            </div>

            {{-- Office Locations --}}
            <div id="section-locations" class="card bg-base-100 shadow-sm border border-base-300 scroll-mt-20 hidden sm:block -mt-5 sm:mt-0">
                @include('job.company.partials.location')
            </div>

            {{-- social platforms --}}
            <div class="hidden sm:block -mt-5 sm:mt-0">
                @include('job.company.partials.social_platforms')
            </div>
        </div>

        {{-- ----------------------------- RIGHT SIDEBAR (light) ----------------------------- --}}
        <div class="space-y-6 -mt-5 sm:mt-0">
            {{-- Profile Completeness checklist — every row is live, computed from the record --}}
            <div id="section-completeness" class="card bg-base-100 shadow-sm border border-base-300 scroll-mt-20 hidden sm:block">
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
            <div class="-mt-5 sm:mt-0">
                @include('job.company.partials.industry')
            </div>
            
            {{-- Countries --}}
            <div class="-mt-5 sm:mt-0">
                @include('job.company.partials.country')
            </div>
            
            {{-- Cities --}}
            <div class="-mt-5 sm:mt-0">
                @include('job.company.partials.city')
            </div>

            {{-- Office Locations --}}
            <div class="card bg-base-100 shadow-sm border border-base-300 scroll-mt-20 block md:hidden -mt-5 sm:mt-0">
                @include('job.company.partials.location')
            </div>

            {{-- social platforms --}}
            <div class="block md:hidden -mt-5 sm:mt-0">
                @include('job.company.partials.social_platforms')
            </div>
        </div>
    </main>
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