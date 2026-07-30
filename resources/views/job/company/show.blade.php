@extends('layouts.app')

@section('title', $company->name)

@section('content')
<div>
    {{-- Breadcrumb --}}
    <div class="breadcrumbs text-sm mb-2">
        <ul>
            <li> <a href="{{ route('dashboard') }}"> <i class="fa-solid fa-house mr-1"></i> Dashboard </a> </li> <li> <a href="{{ route('companies.index') }}"> Companies </a> </li> <li class="font-semibold">{{ $company->name }}</li>
        </ul>
    </div>

    <div class="flex flex-col lg:flex-row lg:justify-between lg:items-start gap-6">
        <div>
            <div class="flex items-center gap-4">
                <div class="cv-avatar bg-primary text-primary-content">{{ strtoupper(substr($company->name,0,1)) }}</div>
                <h1 class="cv-title text-4xl">{{ $company->name }}</h1>
            </div>
        </div>

        <div class="flex gap-2">
            <a href="{{ route('companies.index') }}" class="btn btn-outline"><i class="fa-solid fa-arrow-left"></i>Back</a>
            @auth
            <a href="{{ route('companies.edit', $company) }}" class="btn btn-warning"><i class="fa-solid fa-pen"></i>Edit</a>
            @endauth
        </div>
    </div>

    <div class="mb-8 mt-4">
        <div class="text-gray-600">
            <i class="fa-solid fa-globe"></i> &nbsp;Operating in {{ $company->countries->count() }} 
            @if ( $company->countries->count() > 1) 
                countries
            @else
                country
            @endif
        </div>

        <div class="flex flex-wrap justify-between gap-2 mt-4 mb-2">
            @foreach($company->industries as $industry)
            <span class="badge badge-secondary badge-outline cv-tag">{{ $industry->name }}</span>
            @endforeach
        </div>
    </div>

    <div class="grid lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="card bg-base-100 shadow-sm border border-base-300">
                <div class="card-body">
                    <h2 class="cv-title text-lg font-semibold mb-6"> Overview</h2>

                    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-6">
                        {{-- Company Name --}}
                        <div class="flex items-start gap-3">
                            <div class="mt-0.5 shrink-0 w-9 h-9 rounded-lg bg-primary/10 text-primary flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 21h18M5 21V6a1 1 0 011-1h6a1 1 0 011 1v15M13 21V10a1 1 0 011-1h5a1 1 0 011 1v11M9 8h.01M9 12h.01M9 16h.01"/>
                                </svg>
                            </div>
                            <div class="min-w-0">
                                <dt class="text-xs font-medium uppercase tracking-wide text-base-content/50">Company Name</dt>
                                <dd class="mt-0.5 text-sm font-medium truncate">{{ $company->name }}</dd>
                            </div>
                        </div>

                        {{-- Website --}}
                        <div class="flex items-start gap-3">
                            <div class="mt-0.5 shrink-0 w-9 h-9 rounded-lg bg-primary/10 text-primary flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9 9 0 100-18 9 9 0 000 18zM3.6 9h16.8M3.6 15h16.8M12 3a15 15 0 010 18 15 15 0 010-18z"/>
                                </svg>
                            </div>
                            <div class="min-w-0">
                                <dt class="text-xs font-medium uppercase tracking-wide text-base-content/50">Website</dt>
                                <dd class="mt-0.5 text-sm">
                                    @if($company->website)
                                        <a href="{{ $company->website }}" target="_blank" rel="noopener" class="link link-primary no-underline hover:underline cv-mono truncate inline-block max-w-full align-bottom">
                                            {{ $company->website }}
                                        </a>
                                    @else
                                        <span class="text-base-content/30">—</span>
                                    @endif
                                </dd>
                            </div>
                        </div>

                        {{-- Career Page --}}
                        <div class="flex items-start gap-3">
                            <div class="mt-0.5 shrink-0 w-9 h-9 rounded-lg bg-primary/10 text-primary flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 7a2 2 0 012-2h14a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V7zM8 5V4a2 2 0 012-2h4a2 2 0 012 2v1M3 11h18"/>
                                </svg>
                            </div>
                            <div class="min-w-0">
                                <dt class="text-xs font-medium uppercase tracking-wide text-base-content/50">Career Page</dt>
                                <dd class="mt-0.5 text-sm">
                                    @if($company->career_page)
                                        <a href="{{ $company->career_page }}" target="_blank" rel="noopener" class="link link-primary no-underline hover:underline cv-mono truncate inline-block max-w-full align-bottom">
                                            {{ $company->career_page }}
                                        </a>
                                    @else
                                        <span class="text-base-content/30">—</span>
                                    @endif
                                </dd>
                            </div>
                        </div>

                        {{-- Created At --}}
                        <div class="flex items-start gap-3">
                            <div class="mt-0.5 shrink-0 w-9 h-9 rounded-lg bg-primary/10 text-primary flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 2v3M16 2v3M3.5 9h17M4 5h16a1 1 0 011 1v13a1 1 0 01-1 1H4a1 1 0 01-1-1V6a1 1 0 011-1z"/>
                                </svg>
                            </div>
                            <div class="min-w-0">
                                <dt class="text-xs font-medium uppercase tracking-wide text-base-content/50">Created At</dt>
                                <dd class="mt-0.5 text-sm font-medium">{{ $company->created_at->format('d M Y, h:i A') }}</dd>
                            </div>
                        </div>

                        {{-- Updated At --}}
                        <div class="flex items-start gap-3">
                            <div class="mt-0.5 shrink-0 w-9 h-9 rounded-lg bg-primary/10 text-primary flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div class="min-w-0">
                                <dt class="text-xs font-medium uppercase tracking-wide text-base-content/50">Updated At</dt>
                                <dd class="mt-0.5 text-sm font-medium" title="{{ $company->updated_at->format('d M Y, h:i A') }}">
                                    {{ $company->updated_at->diffForHumans() }}
                                </dd>
                            </div>
                        </div>
                    </dl>
                </div>
            </div>

            <div class="grid grid-cols-1 md:flex gap-6 w-full">
                @if(!empty($company->emails))
                <div class="card bg-base-100 shadow-sm border border-base-300 md:flex-1">
                    <div class="card-body">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-lg bg-primary/10 text-primary flex items-center justify-center shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 7a2 2 0 012-2h14a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V7z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 7l9 6 9-6"/>
                                    </svg>
                                </div>
                                <h2 class="cv-title text-lg">Email Directory</h2>
                            </div>
                            <span class="badge badge-ghost badge-sm font-medium">{{ count($company->emails) }}</span>
                        </div>

                        <div class="overflow-x-auto -mx-6">
                            <table class="table">
                                <thead>
                                    <tr class="text-xs uppercase tracking-wide text-base-content/50">
                                        <th class="font-medium">Type</th> <th class="font-medium">Email</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($company->emails as $email)
                                    <tr class="hover:bg-base-200/50">
                                        <td>
                                            <span class="badge badge-ghost badge-sm capitalize font-normal">{{ $email['email_type'] }}</span>
                                        </td>
                                        <td>
                                            <a href="mailto:{{ $email['email'] }}" class="link link-primary no-underline hover:underline cv-mono text-sm">
                                                {{ $email['email'] }}
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @endif

                @if(!empty($company->phones))
                <div class="card bg-base-100 shadow-sm border border-base-300 md:flex-1">
                    <div class="card-body">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-lg bg-primary/10 text-primary flex items-center justify-center shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.6 6.6c0-1.1.9-2.1 2-2.2h1.3c.5 0 .9.3 1 .8l.9 3.1c.1.4 0 .9-.3 1.2l-1.1 1.1a11 11 0 005 5l1.1-1.1c.3-.3.8-.4 1.2-.3l3.1.9c.5.1.8.5.8 1v1.3c-.1 1.1-1.1 2-2.2 2C9.7 20 4 14.3 3.6 6.6z"/>
                                    </svg>
                                </div>
                                <h2 class="cv-title text-lg">Phone Directory</h2>
                            </div>
                            <span class="badge badge-ghost badge-sm font-medium">{{ count($company->phones) }}</span>
                        </div>

                        <div class="overflow-x-auto -mx-6">
                            <table class="table">
                                <thead>
                                    <tr class="text-xs uppercase tracking-wide text-base-content/50">
                                        <th class="font-medium">Type</th> <th class="font-medium">Phone</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($company->phones as $phone)
                                    <tr class="hover:bg-base-200/50">
                                        <td>
                                            <span class="badge badge-ghost badge-sm capitalize font-normal">{{ $phone['phone_type'] }}</span>
                                        </td>
                                        <td class="cv-mono text-sm">{{ $phone['phone'] }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            @if(!empty($company->address))
            <div class="card bg-base-100 shadow-sm border border-base-300">
                <div class="card-body">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-lg bg-primary/10 text-primary flex items-center justify-center shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 21s7-6.5 7-11.5A7 7 0 105 9.5C5 14.5 12 21 12 21z"/>
                                    <circle cx="12" cy="9.5" r="2.3" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </div>
                            <h2 class="cv-title text-lg">Office Locations</h2>
                        </div>
                        <span class="badge badge-ghost badge-sm font-medium">{{ count($company->address) }}</span>
                    </div>

                    <div class="space-y-1">
                        @foreach($company->address as $address)
                        <div class="rounded-lg border border-base-300 bg-base-200/40 p-4">
                            <div class="flex items-center justify-between mb-2">
                                <span class="badge badge-ghost badge-sm capitalize font-normal">{{ $address['address_type'] }}</span>
                            </div>
                            <div class="text-sm text-base-content/80 leading-relaxed whitespace-pre-line">{{ $address['address'] }}</div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </div>

        <div class="space-y-6">
            @php
                $stats = collect([
                    [
                        'label' => 'Industries', 'value' => $company->industries?->count(), 
                        'icon'  => 'M3 21h18M5 21V7l6-4 6 4v14M9 9h.01M9 13h.01M9 17h.01M15 9h.01M15 13h.01M15 17h.01',
                    ], [
                        'label' => 'Countries', 'value' => $company->countries?->count(),
                        'icon'  => 'M12 21a9 9 0 100-18 9 9 0 000 18zM3.6 9h16.8M3.6 15h16.8M12 3a15 15 0 010 18 15 15 0 010-18z',
                    ], [
                        'label' => 'Emails', 'value' => !empty($company->emails) ? count($company->emails) : null,
                        'icon'  => 'M3 7a2 2 0 012-2h14a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V7zM3 7l9 6 9-6',
                    ], [
                        'label' => 'Phone Numbers', 'value' => !empty($company->phones) ? count($company->phones) : null,
                        'icon'  => 'M3.6 6.6c0-1.1.9-2.1 2-2.2h1.3c.5 0 .9.3 1 .8l.9 3.1c.1.4 0 .9-.3 1.2l-1.1 1.1a11 11 0 005 5l1.1-1.1c.3-.3.8-.4 1.2-.3l3.1.9c.5.1.8.5.8 1v1.3c-.1 1.1-1.1 2-2.2 2C9.7 20 4 14.3 3.6 6.6z',
                    ], [
                        'label' => 'Offices', 'value' => !empty($company->address) ? count($company->address) : null,
                        'icon'  => 'M12 21s7-6.5 7-11.5A7 7 0 105 9.5C5 14.5 12 21 12 21z',
                    ], [
                        'label' => 'Social Links', 'value' => !empty($company->social_links) ? count($company->social_links) : null,
                        'icon'  => 'M18 8a3 3 0 10-2.8-4.2M18 8a3 3 0 01-2.8-1.8M18 8l-7.2 4.2M18 16a3 3 0 10-2.8 4.2M18 16a3 3 0 01-2.8 1.8M18 16l-7.2-4.2M6 12a3 3 0 100 0z',
                    ],
                ])->filter(fn ($s) => !is_null($s['value']))->values();
            @endphp

            @if($stats->isNotEmpty())
            <div class="card bg-base-100 shadow-sm border border-base-300">
                <div class="card-body">
                    <h2 class="cv-title text-lg mb-2">Quick Stats</h2>

                    <div class="flex flex-col gap-1">
                        @foreach($stats as $stat)
                        <div class="flex gap-3 justify-start items-center py-1.5">
                            <div class="size-7 rounded-lg bg-primary/10 text-primary flex items-center justify-center shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $stat['icon'] }}"/>
                                </svg>
                            </div>
                            <div class="flex gap-3 justify-center items-center">
                                <div class="text-xl font-semibold leading-none">{{ $stat['value'] }}</div>
                                <div class="uppercase tracking-wide text-base-content/75">{{ $stat['label'] }}</div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            @if($company->countries->isNotEmpty())
            <div class="card bg-base-100 shadow-sm border border-base-300">
                <div class="card-body">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-lg bg-primary/10 text-primary flex items-center justify-center shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9 9 0 100-18 9 9 0 000 18zM3.6 9h16.8M3.6 15h16.8M12 3a15 15 0 010 18 15 15 0 010-18z"/>
                                </svg>
                            </div>
                            <h2 class="cv-title text-lg">Countries</h2>
                        </div>
                        <span class="badge badge-ghost badge-sm font-medium">{{ $company->countries->count() }}</span>
                    </div>

                    <div class="flex flex-wrap gap-2">
                        @foreach($company->countries as $country)
                        <span class="badge badge-primary badge-outline cv-tag">{{ $country->name }}</span>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            @if($company->industries->isNotEmpty())
            <div class="card bg-base-100 shadow-sm border border-base-300">
                <div class="card-body">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-lg bg-primary/10 text-primary flex items-center justify-center shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 21h18M5 21V7l6-4 6 4v14M9 9h.01M9 13h.01M9 17h.01M15 9h.01M15 13h.01M15 17h.01"/>
                                </svg>
                            </div>
                            <h2 class="cv-title text-lg">Industries</h2>
                        </div>
                        <span class="badge badge-ghost badge-sm font-medium">{{ $company->industries->count() }}</span>
                    </div>

                    <div class="flex flex-wrap gap-2">
                        @foreach($company->industries as $industry)
                        <span class="badge badge-secondary badge-outline cv-tag">{{ $industry->name }}</span>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            @php
            $icons = [
                'LinkedIn' => 'fa-linkedin', 'Facebook' => 'fa-facebook', 'X (Twitter)' => 'fa-x-twitter', 'GitHub' => 'fa-github',
                'Instagram' => 'fa-instagram', 'YouTube' => 'fa-youtube', 'TikTok' => 'fa-tiktok', 'Discord' => 'fa-discord',
                'Telegram' => 'fa-telegram', 'Pinterest' => 'fa-pinterest', 'Reddit' => 'fa-reddit', 'Medium' => 'fa-medium',
                'Behance' => 'fa-behance', 'Dribbble' => 'fa-dribbble', 'Website' => 'fa-globe', 'Other' => 'fa-link',
            ];
            @endphp

            @if(!empty($company->social_links))
            <div class="card bg-base-100 shadow border border-base-300">
                <div class="card-body">
                    <h2 class="cv-title text-lg">Digital Presence</h2>

                    <div class="flex gap-3 flex-wrap">
                        @foreach($company->social_links as $social)
                            <a href="{{ $social['url'] }}" target="_blank" class="btn btn-outline btn-circle tooltip" data-tip="{{ $social['platform'] }}">
                                <i class="fa-brands {{ $icons[$social['platform']] ?? 'fa-link' }}"></i>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection