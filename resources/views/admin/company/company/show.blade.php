
@extends('layouts.admin.app')

@section('title', $company->name . ' | CareerVault')
@section('page_title', 'Companies / Details')

@section('content')
@php
    $statusLabels = ['active' => 'Active', 'inactive' => 'Inactive', 'draft' => 'Draft', 'archived' => 'Archived'];
    $statusClasses = ['active' => 'badge-success', 'inactive' => 'badge-warning', 'draft' => 'badge-ghost', 'archived' => 'badge-error'];
    $operatingLabels = ['operating' => 'Operating', 'acquired' => 'Acquired', 'merged' => 'Merged', 'closed' => 'Closed', 'dissolved' => 'Dissolved', 'unknown' => 'Unknown'];
@endphp

<div class="space-y-5 p-4 sm:p-6">
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-sm text-base-content/60">Company Directory / Details</p>
            <h1 class="text-2xl font-bold">{{ $company->name }}</h1>
            <div class="mt-2 flex flex-wrap gap-2">
                <span class="badge {{ $statusClasses[$company->status] ?? 'badge-ghost' }}">{{ $statusLabels[$company->status] ?? ucfirst($company->status) }}</span>
                @if($company->is_verified)<span class="badge badge-info">Verified</span>@endif
                @if($company->is_featured)<span class="badge badge-warning">Featured</span>@endif
            </div>
        </div>

        <div class="flex flex-wrap gap-2">
            <a href="{{ route('admin.companies.index') }}" class="btn btn-ghost"><i class="fa-solid fa-arrow-left mr-2"></i> Companies</a>
            <a href="{{ route('admin.companies.edit', $company) }}" class="btn btn-primary"><i class="fa-solid fa-pen mr-2"></i> Edit Company</a>
            <form method="POST" action="{{ route('admin.companies.destroy', $company) }}" onsubmit="return confirm('Move this company to trash?')">
                @csrf
                @method('DELETE')
                <button class="btn btn-outline btn-error"><i class="fa-solid fa-trash-can mr-2"></i> Trash</button>
            </form>
        </div>
    </div>

    @if($company->cover_image)
        <div class="overflow-hidden rounded-xl border border-base-300 bg-base-100">
            <img src="{{ Storage::disk('public')->url($company->cover_image) }}" alt="{{ $company->name }} cover image" class="h-40 w-full object-cover sm:h-56">
        </div>
    @endif

    {{-- Overview --}}
    <section class="rounded-xl border border-base-300 bg-base-100 p-5">
        <div class="flex flex-col gap-4 sm:flex-row">
            @if($company->logo)
                <img src="{{ Storage::disk('public')->url($company->logo) }}" alt="{{ $company->name }} logo" class="h-20 w-20 shrink-0 rounded-xl border border-base-300 bg-base-200 object-contain p-2">
            @else
                <div class="grid h-20 w-20 shrink-0 place-items-center rounded-xl bg-base-200 text-3xl text-base-content/40"><i class="fa-solid fa-building"></i></div>
            @endif

            <div class="min-w-0 flex-1">
                <h2 class="text-xl font-bold">{{ $company->name }}</h2>
                @if($company->legal_name && $company->legal_name !== $company->name)
                    <p class="text-sm text-base-content/60">{{ $company->legal_name }}</p>
                @endif
                @if($company->tagline)
                    <p class="mt-2 font-medium">{{ $company->tagline }}</p>
                @endif
                @if($company->short_desc)
                    <p class="mt-2 text-sm leading-6 text-base-content/75">{{ $company->short_desc }}</p>
                @endif

                <div class="mt-3 flex flex-wrap gap-2">
                    @if($company->website)
                        <a href="{{ $company->website }}" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-outline"><i class="fa-solid fa-globe mr-2"></i> Website</a>
                    @endif
                    @if($company->career_page)
                        <a href="{{ $company->career_page }}" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-outline"><i class="fa-solid fa-briefcase mr-2"></i> Career Page</a>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <div class="grid gap-5 xl:grid-cols-2">
        {{-- Company information --}}
        <section class="rounded-xl border border-base-300 bg-base-100">
            <div class="border-b border-base-300 px-5 py-4"><h2 class="font-semibold">Company Information</h2></div>
            <dl class="grid gap-4 p-5 sm:grid-cols-2">
                <div><dt class="text-sm text-base-content/60">Company Type</dt><dd class="mt-1 font-medium">{{ $company->companyType?->name ?? '—' }}</dd></div>
                <div><dt class="text-sm text-base-content/60">Company Size</dt><dd class="mt-1 font-medium">{{ $company->companySize?->name ?? '—' }}</dd></div>
                <div><dt class="text-sm text-base-content/60">Ownership Type</dt><dd class="mt-1 font-medium">{{ $company->ownershipType?->name ?? '—' }}</dd></div>
                <div><dt class="text-sm text-base-content/60">Primary Industry</dt><dd class="mt-1 font-medium">{{ $company->primaryIndustry?->name ?? '—' }}</dd></div>
                <div><dt class="text-sm text-base-content/60">Parent Company</dt>
                    <dd class="mt-1 font-medium">
                        @if($company->parentCompany)
                            <a href="{{ route('admin.companies.show', $company->parentCompany) }}" class="link link-primary">{{ $company->parentCompany->name }}</a>
                        @else
                            —
                        @endif
                    </dd>
                </div>
                <div><dt class="text-sm text-base-content/60">Operating Status</dt><dd class="mt-1 font-medium">{{ $operatingLabels[$company->operating_status] ?? '—' }}</dd></div>
                <div><dt class="text-sm text-base-content/60">Founded By</dt><dd class="mt-1 font-medium">{{ $company->founded_by ?: '—' }}</dd></div>
                <div><dt class="text-sm text-base-content/60">Founded</dt>
                    <dd class="mt-1 font-medium">
                        @if($company->founded_month && $company->founded_year)
                            {{ \Carbon\Carbon::create()->month($company->founded_month)->format('F') }} {{ $company->founded_year }}
                        @elseif($company->founded_year)
                            {{ $company->founded_year }}
                        @elseif($company->founded_month)
                            {{ \Carbon\Carbon::create()->month($company->founded_month)->format('F') }}
                        @else
                            —
                        @endif
                    </dd>
                </div>
                <div><dt class="text-sm text-base-content/60">Employee Count</dt><dd class="mt-1 font-medium">{{ $company->employee_count !== null ? number_format($company->employee_count) : '—' }}</dd></div>
                <div><dt class="text-sm text-base-content/60">Employee Count Updated</dt><dd class="mt-1 font-medium">{{ $company->employee_count_updated_at?->format('M d, Y') ?? '—' }}</dd></div>
                <div><dt class="text-sm text-base-content/60">Last Verified</dt><dd class="mt-1 font-medium">{{ $company->last_verified_at?->format('M d, Y H:i') ?? '—' }}</dd></div>
            </dl>
        </section>

        {{-- Contacts --}}
        <section class="rounded-xl border border-base-300 bg-base-100">
            <div class="border-b border-base-300 px-5 py-4"><h2 class="font-semibold">Contact Information</h2></div>
            <div class="space-y-5 p-5">
                <div>
                    <h3 class="mb-2 text-sm font-semibold text-base-content/60">Email Addresses</h3>
                    @forelse($company->emails ?? [] as $email)
                        <div class="mb-2 flex items-center gap-2 break-all"><i class="fa-solid fa-envelope w-4 text-base-content/50"></i><a href="mailto:{{ $email }}" class="link link-primary">{{ $email }}</a></div>
                    @empty
                        <p class="text-sm text-base-content/40">No email addresses added.</p>
                    @endforelse
                </div>
                <div>
                    <h3 class="mb-2 text-sm font-semibold text-base-content/60">Phone Numbers</h3>
                    @forelse($company->phones ?? [] as $phone)
                        <div class="mb-2 flex items-center gap-2 break-all"><i class="fa-solid fa-phone w-4 text-base-content/50"></i><a href="tel:{{ $phone }}" class="link link-primary">{{ $phone }}</a></div>
                    @empty
                        <p class="text-sm text-base-content/40">No phone numbers added.</p>
                    @endforelse
                </div>
            </div>
        </section>

        {{-- Business models --}}
        <section class="rounded-xl border border-base-300 bg-base-100">
            <div class="border-b border-base-300 px-5 py-4"><h2 class="font-semibold">Business Models</h2></div>
            <div class="flex flex-wrap gap-2 p-5">
                @forelse($company->businessModels as $model)
                    <span class="badge badge-outline badge-lg">{{ $model->name }}</span>
                @empty
                    <p class="text-sm text-base-content/50">No business models linked.</p>
                @endforelse
            </div>
        </section>

        {{-- Industries --}}
        <section class="rounded-xl border border-base-300 bg-base-100">
            <div class="border-b border-base-300 px-5 py-4"><h2 class="font-semibold">Industries</h2></div>
            <div class="flex flex-wrap gap-2 p-5">
                @forelse($company->industries as $industry)
                    <span class="badge badge-outline badge-lg">{{ $industry->name }}</span>
                @empty
                    <p class="text-sm text-base-content/50">No additional industries linked.</p>
                @endforelse
            </div>
        </section>

        {{-- Technologies --}}
        <section class="rounded-xl border border-base-300 bg-base-100">
            <div class="border-b border-base-300 px-5 py-4"><h2 class="font-semibold">Technologies</h2></div>
            <div class="flex flex-wrap gap-2 p-5">
                @forelse($company->technologies as $technology)
                    <span class="badge badge-outline badge-lg">{{ $technology->name }}</span>
                @empty
                    <p class="text-sm text-base-content/50">No technologies linked.</p>
                @endforelse
            </div>
        </section>

        {{-- Countries and states --}}
        <section class="rounded-xl border border-base-300 bg-base-100">
            <div class="border-b border-base-300 px-5 py-4"><h2 class="font-semibold">Countries and States / Divisions</h2></div>
            <div class="space-y-4 p-5">
                <div>
                    <h3 class="mb-2 text-sm font-semibold text-base-content/60">Countries</h3>
                    <div class="flex flex-wrap gap-2">
                        @forelse($company->countries as $country)
                            <span class="badge badge-outline">{{ $country->name }}</span>
                        @empty
                            <span class="text-sm text-base-content/40">None linked</span>
                        @endforelse
                    </div>
                </div>
                <div>
                    <h3 class="mb-2 text-sm font-semibold text-base-content/60">States / Divisions</h3>
                    <div class="flex flex-wrap gap-2">
                        @forelse($company->states as $state)
                            <span class="badge badge-outline">{{ $state->name }}</span>
                        @empty
                            <span class="text-sm text-base-content/40">None linked</span>
                        @endforelse
                    </div>
                </div>
            </div>
        </section>
    </div>

    {{-- Locations --}}
    <section class="rounded-xl border border-base-300 bg-base-100">
        <div class="border-b border-base-300 px-5 py-4"><h2 class="font-semibold">Company Locations</h2></div>
        <div class="divide-y divide-base-300">
            @forelse($company->companyPlaces as $place)
                <div class="flex items-start gap-3 p-5">
                    <div class="grid h-10 w-10 shrink-0 place-items-center rounded-lg bg-base-200"><i class="fa-solid fa-location-dot"></i></div>
                    <div>
                        <p class="font-medium">{{ $place->city?->name ?? 'Unknown city' }}</p>
                        <p class="text-sm text-base-content/60">
                            {{ $place->city?->state?->name }}
                            @if($place->city?->state?->country)
                                · {{ $place->city->state->country->name }}
                            @endif
                        </p>
                        @if($place->place)<p class="mt-1 text-sm">{{ $place->place }}</p>@endif
                    </div>
                </div>
            @empty
                <p class="p-5 text-sm text-base-content/50">No specific city locations added.</p>
            @endforelse
        </div>
    </section>

    {{-- Platform profiles --}}
    <section class="rounded-xl border border-base-300 bg-base-100">
        <div class="flex items-center justify-between gap-3 border-b border-base-300 px-5 py-4">
            <div>
                <h2 class="font-semibold">Platform Profiles</h2>
                <p class="mt-1 text-sm text-base-content/60">Company profiles linked to career platforms.</p>
            </div>
            <a href="{{ route('admin.companies.edit', $company) }}" class="btn btn-sm btn-outline">Manage Profiles</a>
        </div>

        <div class="divide-y divide-base-300">
            @forelse($company->platforms as $platform)
                <div class="flex flex-col gap-3 p-5 sm:flex-row sm:items-start sm:justify-between">
                    <div class="min-w-0">
                        <p class="font-semibold">{{ $platform->name }}</p>
                        @if($platform->pivot->profile_url)
                            <a href="{{ $platform->pivot->profile_url }}" target="_blank" rel="noopener noreferrer" class="mt-1 block break-all text-sm text-primary hover:underline">
                                {{ $platform->pivot->profile_url }}
                                <i class="fa-solid fa-arrow-up-right-from-square ml-1 text-xs"></i>
                            </a>
                        @else
                            <p class="mt-1 text-sm text-base-content/50">No profile URL provided.</p>
                        @endif
                        @if($platform->pivot->last_verified_at)
                            <p class="mt-2 text-xs text-base-content/60">Last verified: {{ \Illuminate\Support\Carbon::parse($platform->pivot->last_verified_at)->format('M d, Y H:i') }}</p>
                        @endif
                    </div>

                    @if($platform->pivot->is_verified)
                        <span class="badge badge-success badge-outline shrink-0"><i class="fa-solid fa-check mr-1"></i> Verified</span>
                    @else
                        <span class="badge badge-ghost shrink-0">Not verified</span>
                    @endif
                </div>
            @empty
                <p class="p-5 text-sm text-base-content/50">No platform profiles linked. Use Edit Company to add profiles.</p>
            @endforelse
        </div>
    </section>

    {{-- Corporate hierarchy --}}
    @if($company->subsidiaries->isNotEmpty())
        <section class="rounded-xl border border-base-300 bg-base-100">
            <div class="border-b border-base-300 px-5 py-4"><h2 class="font-semibold">Subsidiaries</h2></div>
            <div class="divide-y divide-base-300">
                @foreach($company->subsidiaries as $subsidiary)
                    <a href="{{ route('admin.companies.show', $subsidiary) }}" class="flex items-center justify-between gap-3 p-4 hover:bg-base-200/50">
                        <span class="font-medium">{{ $subsidiary->name }}</span>
                        <i class="fa-solid fa-arrow-up-right-from-square text-sm text-base-content/50"></i>
                    </a>
                @endforeach
            </div>
        </section>
    @endif

    {{-- Description and notes --}}
    @if($company->description || $company->notes)
        <section class="rounded-xl border border-base-300 bg-base-100">
            @if($company->description)
                <div class="p-5">
                    <h2 class="font-semibold">Full Description</h2>
                    <div class="mt-3 whitespace-pre-line text-sm leading-7 text-base-content/80">{{ $company->description }}</div>
                </div>
            @endif
            @if($company->notes)
                <div class="{{ $company->description ? 'border-t border-base-300' : '' }} p-5">
                    <h2 class="font-semibold">Internal Notes</h2>
                    <div class="mt-3 whitespace-pre-line text-sm leading-7 text-base-content/80">{{ $company->notes }}</div>
                </div>
            @endif
        </section>
    @endif

    {{-- Audit --}}
    <section class="rounded-xl border border-base-300 bg-base-100">
        <div class="border-b border-base-300 px-5 py-4"><h2 class="font-semibold">Record History</h2></div>
        <dl class="grid gap-4 p-5 sm:grid-cols-2 lg:grid-cols-4">
            <div><dt class="text-sm text-base-content/60">Created By</dt><dd class="mt-1 font-medium">{{ $company->creator?->name ?? '—' }}</dd></div>
            <div><dt class="text-sm text-base-content/60">Created At</dt><dd class="mt-1 font-medium">{{ $company->created_at?->format('M d, Y H:i') ?? '—' }}</dd></div>
            <div><dt class="text-sm text-base-content/60">Last Updated By</dt><dd class="mt-1 font-medium">{{ $company->updater?->name ?? '—' }}</dd></div>
            <div><dt class="text-sm text-base-content/60">Updated At</dt><dd class="mt-1 font-medium">{{ $company->updated_at?->format('M d, Y H:i') ?? '—' }}</dd></div>
        </dl>
    </section>
</div>
@endsection
