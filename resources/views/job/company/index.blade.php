@extends('layouts.app')

@section('title', 'Companies | CareerVault')

@section('content')
<div>
    <section class="sm:mb-2">
        {{-- Hero --}}
        <div class="relative overflow-hidden sm:rounded-lg bg-gradient-to-br from-indigo-600 via-indigo-600 to-violet-700 px-5 py-6 sm:px-8 sm:py-8 shadow-lg shadow-indigo-900/10">
            {{-- Decorative pattern (inline SVG, no CSS) --}}
            <svg class="absolute -top-10 -right-10 w-64 h-64 opacity-10 pointer-events-none" viewBox="0 0 200 200" fill="none" xmlns="http://www.w3.org/2000/svg">
                <circle cx="100" cy="100" r="99" stroke="white" stroke-width="1.5"/>
                <circle cx="100" cy="100" r="72" stroke="white" stroke-width="1.5"/>
                <circle cx="100" cy="100" r="45" stroke="white" stroke-width="1.5"/>
            </svg>

            <div class="relative flex flex-col lg:flex-row lg:items-end lg:justify-between gap-6">
                <div>
                    <p class="text-xs font-semibold tracking-widest uppercase text-indigo-200 mb-2">
                        <i class="fa-solid fa-building mr-1"></i> 
                        <a href="{{ route('dashboard') }}">CareerVault</a> /
                    </p>
                    <div class="flex items-center gap-2">
                        <h1 class="text-2xl sm:text-3xl font-bold text-white">Companies</h1>
                        <sup id="companyTotal" class="rounded-full bg-white/20 text-white text-xs font-bold px-2 py-1 leading-none border border-white/30"> {{ $companies->total() }} </sup>
                    </div>
                    <p class="text-indigo-100/80 text-sm max-w-2xl mt-1">Explore companies, discover their industries and locations, and keep your company directory organized.</p>

                    {{-- Quick stats. id="companyPagination" already carries data-total / data-per-page below,
                         and companyStatShowing / companyStatPage are kept in sync with pagination via
                         a small observer script at the bottom of this file (see notes there). --}}
                    <div class="flex flex-wrap items-center gap-2 mt-5">
                        <div class="flex items-center gap-2 rounded-lg bg-white/10 border border-white/15 px-3 py-2">
                            <i class="fa-solid fa-building text-indigo-200 text-xs"></i>
                            <span class="text-white text-sm font-semibold">{{ $totalCompanies }}</span>
                            <span class="text-indigo-200 text-xs">total</span>
                        </div>
                        <div class="flex items-center gap-2 rounded-lg bg-white/10 border border-white/15 px-3 py-2">
                            <i class="fa-solid fa-list-ol text-indigo-200 text-xs"></i>
                            <span id="companyStatShowing" class="text-white text-sm font-semibold">{{ $companies->total() ? "{$companies->firstItem()}–{$companies->lastItem()}" : '0' }}</span>
                            <span class="text-indigo-200 text-xs">showing</span>
                        </div>
                        <div class="flex items-center gap-2 rounded-lg bg-white/10 border border-white/15 px-3 py-2">
                            <i class="fa-solid fa-layer-group text-indigo-200 text-xs"></i>
                            <span id="companyStatPage" class="text-white text-sm font-semibold">{{ $companies->currentPage() }}/{{ max($companies->lastPage(), 1) }}</span>
                            <span class="text-indigo-200 text-xs">page</span>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row sm:items-center gap-3 w-full lg:w-auto">
                    {{-- Search (ids / data-url / value unchanged for existing JS) --}}
                    <div>
                        @include('modular.database_filter', [
                            'id' => 'companySearch',
                            'url' => route('companies.index'),
                            'placeholder' => 'Search name, city, country...',
                            'loadingId' => 'companySearchLoading',
                            'shortcutId' => 'companySearchShortcut',
                        ])
                    </div>

                    @auth
                        <a href="{{ route('companies.create') }}" class="btn bg-white text-indigo-700 hover:bg-indigo-50 border-0 shrink-0 shadow-sm">
                            <i class="fa-solid fa-plus"></i> Add Company
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </section>

    {{-- Companies Table (collapses into cards on small screens via responsive Tailwind classes only) --}}
    <div class="card bg-transparent sm:bg-base-100 sm:shadow sm:border sm:border-base-300 overflow-hidden p-2 sm:p-0">
        <div class="p-2">
            <div class="overflow-x-auto">
                <table class="table w-full !block md:!table">
                    <thead class="hidden md:table-header-group">
                        <tr class="cv-eyebrow border-b border-base-300">
                            <th class="w-10 py-3 text-center">#</th> <th class="px-2 py-3">Company</th> <th class="text-center px-2 py-3">Career</th>
                            <th class="px-2 py-3">City</th> <th class="px-2 py-3">Country</th> <th class="px-2 py-3">Industry</th>
                            @auth
                                <th class="text-right pl-2 pr-4 py-3">Actions</th>
                            @endauth
                        </tr>
                    </thead>
    
                    <tbody id="companyContainer" class="block md:table-row-group">
                        @forelse($companies as $i => $company)
                            @php
                                $avatarColors = ['bg-indigo-500','bg-teal-500','bg-rose-500','bg-amber-500','bg-blue-500','bg-violet-500','bg-emerald-500','bg-fuchsia-500'];
                                $avatarColor = $avatarColors[crc32($company->name) % count($avatarColors)];
                                $isVerified = $company->website && $company->career_page && $company->cities->isNotEmpty() && $company->countries->isNotEmpty() && $company->industries->isNotEmpty();
                            @endphp
                            <tr class="cv-row company-card block md:table-row rounded-xl md:rounded-none border md:border-0 md:border-b border-slate-200 border-b-base-300 last:border-0 mb-3 md:mb-0 p-3 md:p-0 bg-white md:bg-transparent shadow-sm md:shadow-none hover:bg-slate-50">
                                {{-- Number (hidden on mobile cards) --}}
                                <td class="hidden md:table-cell cv-mono text-xs opacity-40 py-2.5 text-center"> {{ str_pad(($companies->firstItem() ?? 1) + $i, 2, '0', STR_PAD_LEFT) }} </td>
    
                                {{-- Company --}}
                                <td class="block md:table-cell px-0 md:px-2 py-1 md:py-2.5">
                                    <div class="flex items-center gap-1 min-w-0">
                                        <div class="relative w-14 h-14 shrink-0">
                                            <svg class="absolute inset-0 w-full h-full -rotate-90" viewBox="0 0 100 100">
                                                <circle cx="50" cy="50" r="46" fill="none" stroke="currentColor" stroke-width="5" class="text-base-300"/>
                                                <circle cx="50" cy="50" r="46" fill="none" stroke="currentColor" stroke-width="5" stroke-linecap="round" class="text-primary transition-all duration-700" stroke-dasharray="289" stroke-dashoffset="1"/>
                                            </svg>
    
                                            <div class="absolute inset-2 rounded-full {{$avatarColor}} text-primary-content flex items-center justify-center text-2xl sm:text-3xl md:text-4xl font-bold shadow-lg ring-4 ring-base-100">
                                                {{ strtoupper(substr($company->name, 0, 1)) }}
                                            </div>
    
                                            @if($isVerified)
                                                <div class="tooltip absolute -bottom-1 -right-1">
                                                    <div class="w-6 h-6 rounded-full bg-success text-success-content flex items-center justify-center text-[10px] font-bold shadow">
                                                        <i class="fa-solid fa-check"></i>
                                                    </div>
                                                </div>
                                             @endif
                                        </div>
    
                                        <div class="min-w-0 flex-1">
                                            <div class="flex items-center justify-between min-w-0 gap-2">
                                                <div class="flex items-center min-w-0">
                                                    <a href="{{ route('companies.show', $company) }}" class="cv-name-link block max-w-[225px] sm:max-w-[160px] truncate font-medium text-slate-800 hover:text-indigo-600" title="{{ $company->name }}">
                                                        {{ $company->name }}
                                                    </a>
    
                                                    @if($company->website)
                                                        <a href="{{ $company->website }}" target="_blank" rel="noopener noreferrer" class="ml-2 shrink-0 text-xs opacity-40 hover:opacity-100" title="Visit website">
                                                            <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                                        </a>
                                                    @endif
                                                </div>
    
                                                {{-- Copy profile link (mobile-friendly quick action) --}}
                                                <button type="button" class="cv-copy-link md:hidden shrink-0 text-slate-400 hover:text-indigo-600 text-xs px-1" data-copy="{{ route('companies.show', $company) }}" title="Copy link">
                                                    <i class="fa-solid fa-link"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </td>
    
                                {{-- Career --}}
                                <td class="flex md:table-cell items-center justify-between md:justify-center gap-2 px-0 md:px-2 py-1.5 md:py-2.5 before:content-['Career'] before:text-[10px] before:font-semibold before:uppercase before:text-slate-400 before:tracking-wide md:before:content-none md:before:hidden">
                                    @if($company->career_page)
                                        <a href="{{ $company->career_page }}" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-outline" title="Visit career page">
                                            <i class="fa-solid fa-globe"></i>
                                        </a>
                                    @else
                                        <span class="text-xs opacity-30">—</span>
                                    @endif
                                </td>
    
                                {{-- City --}}
                                <td class="flex md:table-cell items-center justify-between md:justify-start gap-2 px-0 md:px-2 py-1.5 md:py-2.5 before:content-['City'] before:text-[10px] before:font-semibold before:uppercase before:text-slate-400 before:tracking-wide md:before:content-none md:before:hidden">
                                    @if($company->cities->count())
                                        <div class="flex flex-wrap gap-1 justify-end md:justify-start">
                                            <span class="badge badge-outline badge-sm cv-tag block max-w-[100px] truncate" title="{{ $company->cities->first()->name }}"> {{ $company->cities->first()->name }} </span>
    
                                            @if($company->cities->count() > 1)
                                                <span class="badge badge-ghost badge-sm cv-tag"> +{{ $company->cities->count() - 1 }} </span>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-xs opacity-30">—</span>
                                    @endif
                                </td>
    
                                {{-- Country --}}
                                <td class="flex md:table-cell items-center justify-between md:justify-start gap-2 px-0 md:px-2 py-1.5 md:py-2.5 before:content-['Country'] before:text-[10px] before:font-semibold before:uppercase before:text-slate-400 before:tracking-wide md:before:content-none md:before:hidden">
                                    @if($company->countries->count())
                                        <div class="flex flex-wrap gap-1 justify-end md:justify-start">
                                            <span class="badge badge-outline badge-sm cv-tag block max-w-[120px] truncate" title="{{ $company->countries->first()->name }}"> {{ $company->countries->first()->name }} </span>
    
                                            @if($company->countries->count() > 1)
                                                <span class="badge badge-ghost badge-sm cv-tag"> +{{ $company->countries->count() - 1 }} </span>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-xs opacity-30">—</span>
                                    @endif
                                </td>
    
                                {{-- Industry --}}
                                <td class="flex md:table-cell items-center justify-between md:justify-start gap-2 px-0 md:px-2 py-1.5 md:py-2.5 before:content-['Industry'] before:text-[10px] before:font-semibold before:uppercase before:text-slate-400 before:tracking-wide md:before:content-none md:before:hidden whitespace-nowrap">
                                    @if($company->industries->count())
                                        <div class="flex flex-wrap gap-1 justify-end md:justify-start">
                                            @foreach($company->industries->take(2) as $industry)
                                                <span class="badge badge-outline badge-sm cv-tag"> {{ $industry->name }} </span>
                                            @endforeach
    
                                            @if($company->industries->count() > 2)
                                                <span class="badge badge-ghost badge-sm cv-tag"> +{{ $company->industries->count() - 2 }} </span>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-xs opacity-30">—</span>
                                    @endif
                                </td>
    
                                {{-- Actions --}}
                                @auth
                                    <td class="block md:table-cell text-right pl-0 md:pl-2 pr-0 md:pr-4 py-2 md:py-2.5 mt-2 md:mt-0 pt-3 md:pt-2.5 border-t md:border-t-0 border-dashed border-slate-200">
                                        <div class="flex justify-end flex-wrap gap-1">
                                            <p class="flex justify-end gap-1">
                                                <a href="{{ route('companies.show', $company) }}" class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-100 text-xs font-medium px-3 py-1.5 transition hover:text-indigo-600">
                                                    <i class="fa-solid fa-eye"></i> Visit
                                                </a>
        
                                                <a href="{{ route('companies.edit', $company) }}" class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-100 text-xs font-medium px-3 py-1.5 transition hover:text-indigo-600">
                                                    <i class="fa-solid fa-pen"></i> Edit
                                                </a>
                                            </p>
    
                                            <button type="button" class="delete-item inline-flex items-center gap-1.5 rounded-lg border border-rose-100 bg-rose-50 text-rose-600 hover:bg-rose-100 text-xs font-medium px-3 py-1.5 transition" 
                                                data-url="{{ route('companies.destroy', $company) }}" 
                                                data-name="{{ $company->name }}"
                                                data-title="Delete Company">
                                                <i class="fa-solid fa-trash"></i> Delete
                                            </button>
                                        </div>
                                    </td>
                                @endauth
                            </tr>
    
                        @empty
    
                            <tr class="block md:table-row">
                                <td colspan="{{ auth()->check() ? 7 : 6 }}" class="block md:table-cell text-center py-16">
                                    <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-indigo-50 text-indigo-400 mb-4">
                                        <i class="fa-solid fa-box-archive text-2xl"></i>
                                    </div>
                                    <p class="cv-title text-lg">No companies found</p>
                                    <p class="text-sm opacity-50 mb-4">
                                        @if(request('search'))
                                            No companies match "{{ request('search') }}".
                                        @else
                                            Add a company to start building your record.
                                        @endif
                                    </p>
    
                                    @if(request('search'))
                                        <a href="{{ route('companies.index') }}" class="btn btn-sm btn-outline">
                                            <i class="fa-solid fa-rotate-left"></i> Clear search
                                        </a>
                                    @elseif(auth()->check())
                                        <a href="{{ route('companies.create') }}" class="btn btn-sm btn-primary">
                                            <i class="fa-solid fa-plus"></i> Add Company
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

    {{-- Pagination. data-total / data-per-page feed the stats-sync script below. --}}
    <div id="companyPagination" class="mt-8 pb-20 md:pb-0" data-total="{{ $companies->total() }}" data-per-page="{{ $companies->perPage() }}">
        {{ $companies->links() }}
    </div>
</div>

{{-- Mobile floating "Add Company" button --}}
@auth
    <a href="{{ route('companies.create') }}" class="fixed bottom-5 right-5 z-40 inline-flex items-center justify-center w-14 h-14 rounded-full bg-indigo-600 text-white shadow-lg shadow-indigo-900/30 hover:bg-indigo-700" aria-label="Add Company">
        <i class="fa-solid fa-plus text-lg"></i>
    </a>
@endauth

{{-- Delete Modal --}}
<div>
    @include('modular.delete_modal')
</div>
@endsection