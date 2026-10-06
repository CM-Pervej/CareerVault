@extends('layouts.admin.app')

@section('title',$country->name)
@section('page_title','Country / '.$country->name)

@section('content')
<div class="space-y-3 p-4 sm:p-6">
    {{-- ========================= HEADER ========================= --}}
    <header class="relative overflow-hidden rounded-sm border border-base-300 bg-base-100 shadow-sm">
        <div class="absolute inset-x-0 top-0 h-1 bg-primary"></div>
        <div class="flex flex-col gap-5 p-5 sm:p-6 lg:flex-row lg:items-center lg:justify-between">
            <div class="flex min-w-0 items-center gap-4">
                {{-- Flag --}}
                <div class="relative shrink-0">
                    <div class="tooltip" data-tip="{{ $country->name }}">
                        <img src="https://flagcdn.com/w80/{{ strtolower($country->iso2) }}.png" alt="{{ $country->name }} flag" loading="lazy" class="size-max object-cover rounded border border-base-300 hover:scale-110 transition-transform">
                    </div>
                    <span class="absolute -bottom-1.5 -right-1.5 flex h-6 w-6 items-center justify-center rounded-full border-2 border-base-100 {{ $country->is_active ? 'bg-success text-success-content' : 'bg-error text-error-content' }}" title="{{ $country->is_active ? 'Active' : 'Inactive' }}">
                        <i class="fa-solid {{ $country->is_active ? 'fa-check' : 'fa-xmark' }} text-[9px]"></i>
                    </span>
                </div>

                {{-- Identity --}}
                <div class="min-w-0">
                    <div class="flex flex-wrap items-center gap-2">
                        <h1 class="truncate text-xl font-bold tracking-tight text-base-content sm:text-2xl"> {{ $country->name }} </h1>

                        @if($country->is_active)
                            <span class="badge badge-success badge-sm gap-1">
                                <i class="fa-solid fa-circle text-[5px]"></i> Active
                            </span>
                        @else
                            <span class="badge badge-error badge-sm gap-1">
                                <i class="fa-solid fa-circle text-[5px]"></i> Inactive
                            </span>
                        @endif
                    </div>

                    <div class="mt-1 flex flex-wrap items-center gap-x-3 gap-y-1 text-xs text-base-content/50">
                        <span class="font-mono font-semibold text-base-content/70"> {{ $country->iso2 }} </span>
                        <span class="font-mono"> {{ $country->iso3 }} </span>
                        <span class="hidden text-base-content/30 sm:inline">•</span>
                        <span class="truncate"> {{ $country->slug }} </span>
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.countries.index') }}" class="btn btn-sm btn-ghost gap-2">
                    <i class="fa-solid fa-arrow-left"></i>
                    <span>Back</span>
                </a>
                <a href="{{ route('admin.countries.edit',$country) }}" class="btn btn-sm btn-primary gap-2">
                    <i class="fa-solid fa-pen-to-square"></i>
                    <span>Edit Country</span>
                </a>
            </div>
        </div>
    </header>

    {{-- ========================= OVERVIEW ========================= --}}
    <section class="grid grid-cols-1 gap-3 sm:grid-cols-3">
        {{-- States --}}
        <div class="group rounded-sm border border-base-300 bg-base-100 p-4 shadow-sm transition hover:border-primary/30 hover:shadow-md">
            <div class="flex items-center justify-between gap-3">
                <div>
                    <p class="text-[10px] font-semibold uppercase tracking-wider text-base-content/40">States / Divisions</p>
                    <p class="mt-1 text-2xl font-bold tracking-tight text-base-content"> {{ number_format($country->states_count ?? 0) }} </p>
                </div>

                <div class="flex h-10 w-10 items-center justify-center rounded-sm bg-primary/10 text-primary">
                    <i class="fa-solid fa-map"></i>
                </div>
            </div>
        </div>

        {{-- Cities --}}
        <div class="group rounded-sm border border-base-300 bg-base-100 p-4 shadow-sm transition hover:border-secondary/30 hover:shadow-md">
            <div class="flex items-center justify-between gap-3">
                <div>
                    <p class="text-[10px] font-semibold uppercase tracking-wider text-base-content/40">Cities</p>
                    <p class="mt-1 text-2xl font-bold tracking-tight text-base-content"> {{ number_format($country->cities_count ?? 0) }} </p>
                </div>

                <div class="flex h-10 w-10 items-center justify-center rounded-sm bg-secondary/10 text-secondary">
                    <i class="fa-solid fa-city"></i>
                </div>
            </div>
        </div>

        {{-- Companies --}}
        <div class="group rounded-sm border border-base-300 bg-base-100 p-4 shadow-sm transition hover:border-accent/30 hover:shadow-md">
            <div class="flex items-center justify-between gap-3">
                <div>
                    <p class="text-[10px] font-semibold uppercase tracking-wider text-base-content/40">Companies</p>
                    <p class="mt-1 text-2xl font-bold tracking-tight text-base-content"> {{ number_format($country->companies_count ?? 0) }} </p>
                </div>

                <div class="flex h-10 w-10 items-center justify-center rounded-sm bg-accent/10 text-accent">
                    <i class="fa-solid fa-building"></i>
                </div>
            </div>
        </div>
    </section>

    {{-- ========================= MAIN INFORMATION ========================= --}}
    <div class="grid gap-4 lg:grid-cols-5">
        {{-- Country Identity --}}
        <section class="rounded-sm border border-base-300 bg-base-100 shadow-sm lg:col-span-3">
            <div class="flex items-center gap-3 border-b border-base-200 px-5 py-4">
                <div class="flex h-9 w-9 items-center justify-center rounded-sm bg-primary/10 text-primary">
                    <i class="fa-solid fa-earth-americas"></i>
                </div>

                <div>
                    <h2 class="text-sm font-bold text-base-content">Country Information</h2>
                    <p class="text-[11px] text-base-content/40">Core identification and contact information</p>
                </div>
            </div>

            <div class="grid gap-px bg-base-200 sm:grid-cols-2">
                {{-- Name --}}
                <div class="bg-base-100 p-4">
                    <p class="text-[10px] font-semibold uppercase tracking-wider text-base-content/40">Country Name</p>
                    <p class="mt-1 text-sm font-semibold text-base-content"> {{ $country->name }} </p>
                </div>

                {{-- Slug --}}
                <div class="bg-base-100 p-4">
                    <p class="text-[10px] font-semibold uppercase tracking-wider text-base-content/40">Slug</p>
                    <p class="mt-1 truncate font-mono text-xs text-base-content/70"> {{ $country->slug }} </p>
                </div>

                {{-- ISO2 --}}
                <div class="bg-base-100 p-4">
                    <p class="text-[10px] font-semibold uppercase tracking-wider text-base-content/40">ISO 3166-1 Alpha-2</p>
                    <div class="mt-1">
                        <span class="badge badge-outline font-mono font-bold"> {{ $country->iso2 }} </span>
                    </div>
                </div>

                {{-- ISO3 --}}
                <div class="bg-base-100 p-4">
                    <p class="text-[10px] font-semibold uppercase tracking-wider text-base-content/40">ISO 3166-1 Alpha-3</p>
                    <div class="mt-1">
                        <span class="badge badge-outline font-mono font-bold"> {{ $country->iso3 }} </span>
                    </div>
                </div>

                {{-- Phone --}}
                <div class="bg-base-100 p-4">
                    <p class="text-[10px] font-semibold uppercase tracking-wider text-base-content/40">International Calling Code</p>
                    <p class="mt-1 font-mono text-sm font-semibold text-base-content"> {{ $country->phone_code ?: '—' }} </p>
                </div>

                {{-- Capital --}}
                <div class="bg-base-100 p-4">
                    <p class="text-[10px] font-semibold uppercase tracking-wider text-base-content/40">Capital</p>
                    <p class="mt-1 text-sm font-semibold text-base-content"> {{ $country->capital ?: '—' }} </p>
                </div>
            </div>
        </section>

        {{-- Geography / Currency --}}
        <section class="rounded-sm border border-base-300 bg-base-100 shadow-sm lg:col-span-2">
            <div class="flex items-center gap-3 border-b border-base-200 px-5 py-4">
                <div class="flex h-9 w-9 items-center justify-center rounded-sm bg-secondary/10 text-secondary">
                    <i class="fa-solid fa-globe"></i>
                </div>

                <div>
                    <h2 class="text-sm font-bold text-base-content">Geography & Currency</h2>
                    <p class="text-[11px] text-base-content/40">Regional and monetary information</p>
                </div>
            </div>

            <div class="p-5">
                {{-- Currency Highlight --}}
                <div class="rounded-sm border border-base-200 bg-base-200/40 p-4">
                    <div class="flex items-center gap-3">
                        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-sm bg-base-100 text-lg font-bold shadow-sm"> {{ $country->currency_code ?: '—' }} </div>

                        <div class="min-w-0">
                            <p class="text-[10px] font-semibold uppercase tracking-wider text-base-content/40"> Currency</p>
                            <p class="truncate text-sm font-bold text-base-content"> {{ $country->currency_name ?: 'Not specified' }} </p>
                        </div>
                    </div>
                </div>

                {{-- Geography --}}
                <div class="mt-4 space-y-4">
                    <div class="flex items-start gap-3">
                        <div class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-sm bg-base-200 text-base-content/60">
                            <i class="fa-solid fa-earth-asia text-xs"></i>
                        </div>

                        <div class="min-w-0">
                            <p class="text-[10px] font-semibold uppercase tracking-wider text-base-content/40">Region</p>
                            <p class="mt-0.5 text-sm font-semibold text-base-content"> {{ $country->region ?: '—' }} </p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <div class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-sm bg-base-200 text-base-content/60">
                            <i class="fa-solid fa-location-dot text-xs"></i>
                        </div>

                        <div class="min-w-0">
                            <p class="text-[10px] font-semibold uppercase tracking-wider text-base-content/40">Subregion</p>
                            <p class="mt-0.5 text-sm font-semibold text-base-content"> {{ $country->subregion ?: '—' }} </p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <div class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-sm bg-base-200 text-base-content/60">
                            <i class="fa-solid fa-arrow-down-1-9 text-xs"></i>
                        </div>

                        <div class="min-w-0">
                            <p class="text-[10px] font-semibold uppercase tracking-wider text-base-content/40">Directory Order</p>
                            <p class="mt-0.5 font-mono text-sm font-semibold text-base-content"> {{ $country->sort_order }} </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    {{-- ========================= RECORD METADATA ========================= --}}
    <section class="rounded-sm border border-base-300 bg-base-100 shadow-sm">
        <div class="flex flex-col gap-4 p-5 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center gap-3">
                <div class="flex h-9 w-9 items-center justify-center rounded-sm bg-base-200 text-base-content/60">
                    <i class="fa-solid fa-clock-rotate-left"></i>
                </div>

                <div>
                    <h2 class="text-sm font-bold text-base-content">Record Information</h2>
                    <p class="text-[11px] text-base-content/40">System metadata for this record</p>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3 sm:gap-8">
                <div>
                    <p class="text-[10px] font-semibold uppercase tracking-wider text-base-content/40">Created</p>
                    <p class="mt-1 text-xs font-medium text-base-content"> {{ $country->created_at?->format('d M Y, h:i A') ?: '—' }} </p>
                </div>

                <div>
                    <p class="text-[10px] font-semibold uppercase tracking-wider text-base-content/40">Last Updated</p>
                    <p class="mt-1 text-xs font-medium text-base-content"> {{ $country->updated_at?->format('d M Y, h:i A') ?: '—' }} </p>
                </div>

                <div>
                    <p class="text-[10px] font-semibold uppercase tracking-wider text-base-content/40">Created By</p>
                    <p class="mt-1 text-xs font-medium text-base-content"> {{ $country->createdBy?->name ?: 'System' }} </p>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection