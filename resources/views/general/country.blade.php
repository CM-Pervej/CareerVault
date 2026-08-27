@extends('layouts.app')

@section('title', 'Countries | CareerVault')

@section('content')
<div>
    @php
        // $totalCountries = $countries->count();
        $regionsCount = $countries->pluck('region')->filter()->unique()->count();
        $currenciesCount = $countries->map(fn($c) => $c->currency_code ?? $c->currency)->filter()->unique()->count();
    @endphp

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
                        <i class="fa-solid fa-earth-americas mr-1"></i>
                        <a href="{{ route('dashboard') }}">CareerVault</a> /
                        <span>General</span> /
                    </p>
                    <div class="flex items-center gap-2">
                        <h1 class="text-2xl sm:text-3xl font-bold text-white">Countries</h1>
                        <sup id="countryTotal" class="rounded-full bg-white/20 text-white text-xs font-bold px-2 py-1 leading-none border border-white/30"> {{ $countries->total() }} </sup>
                    </div>
                    <p class="text-indigo-100/80 text-sm max-w-2xl mt-1">Manage countries and keep your company locations organized across your directory.</p>

                    {{-- Quick stats, computed from the full country list --}}
                    <div class="flex flex-wrap items-center gap-2 mt-5">
                        <div class="flex items-center gap-2 rounded-lg bg-white/10 border border-white/15 px-3 py-2">
                            <i class="fa-solid fa-earth-americas text-indigo-200 text-xs"></i>
                            <span class="text-white text-sm font-semibold">{{ $totalCountry }}</span>
                            <span class="text-indigo-200 text-xs">total</span>
                        </div>
                        <div class="flex items-center gap-2 rounded-lg bg-white/10 border border-white/15 px-3 py-2">
                            <i class="fa-solid fa-list-ol text-indigo-200 text-xs"></i>
                            <span id="countryStatShowing" class="text-white text-sm font-semibold">{{ $countries->total() ? "{$countries->firstItem()}–{$countries->lastItem()}" : '0' }}</span>
                            <span class="text-indigo-200 text-xs">showing</span>
                        </div>
                        <div class="flex items-center gap-2 rounded-lg bg-white/10 border border-white/15 px-3 py-2">
                            <i class="fa-solid fa-map text-indigo-200 text-xs"></i>
                            <span class="text-white text-sm font-semibold">{{ $regionsCount }}</span>
                            <span class="text-indigo-200 text-xs">regions</span>
                        </div>
                        <div class="flex items-center gap-2 rounded-lg bg-white/10 border border-white/15 px-3 py-2">
                            <i class="fa-solid fa-coins text-indigo-200 text-xs"></i>
                            <span class="text-white text-sm font-semibold">{{ $currenciesCount }}</span>
                            <span class="text-indigo-200 text-xs">currencies</span>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row sm:items-center gap-3 w-full lg:w-auto">
                    {{-- Search (ids / data-url / value unchanged for existing JS) --}}
                    <div>
                        @include('modular.database_filter', [
                            'id' => 'countrySearch',
                            'url' => route('countries.index'),
                            'placeholder' => 'Search country...',
                            'loadingId' => 'countrySearchLoading',
                            'shortcutId' => 'countrySearchShortcut',
                        ])
                    </div>

                    <button onclick="countryModal.showModal()" class="inline-flex items-center justify-center gap-2 rounded-sm bg-white hover:bg-indigo-50 text-indigo-700 text-sm font-medium px-4 py-2.5 shadow-sm transition shrink-0 cursor-pointer">
                        <i class="fa-solid fa-plus"></i> Add Country
                    </button>
                </div>
            </div>
        </div>
    </section>

    {{-- Countries List --}}
    <div class="card bg-transparent sm:bg-base-100 sm:shadow sm:border sm:border-base-300 overflow-hidden p-2 sm:p-0">
        <div class="p-2">
            {{-- Table --}}
            <div class="overflow-x-auto">
                <table class="table w-full !block md:!table">
                    <thead class="hidden md:table-header-group">
                        <tr class="cv-eyebrow border-b border-base-300">
                            <th class="w-10 text-center">#</th> <th>Country</th> <th>ISO</th> <th>Capital</th>
                            <th>Region</th> <th>Currency</th> <th>Phone</th> <th class="text-center">Action</th>
                        </tr>
                    </thead>

                    <tbody id="countryContainer" class="block md:table-row-group">
                        @forelse($countries as $item)
                            @php
                                $isComplete = $item->capital && $item->region && ($item->currency_code ?? $item->currency) && $item->phone_code;
                            @endphp

                            <tr class="crud-row country-card block md:table-row rounded-xl md:rounded-none border md:border-0 md:border-b border-slate-200 border-b-base-300 last:border-0 mb-3 md:mb-0 p-3 md:p-0 bg-white md:bg-transparent shadow-sm md:shadow-none hover:bg-slate-50">
                                {{-- Number --}}
                                <td class="hidden md:table-cell cv-mono text-xs opacity-40 py-2.5 text-center">
                                    {{ str_pad($loop->iteration + (($countries->currentPage() - 1) * $countries->perPage()), 2, '0', STR_PAD_LEFT) }}
                                </td>

                                {{-- Country --}}
                                <td class="block md:table-cell px-0 md:px-4 py-1 md:py-3">
                                    <div class="flex items-center gap-3">
                                        <div class="relative w-14 h-14 shrink-0">
                                            <svg class="absolute inset-0 w-full h-full -rotate-90" viewBox="0 0 100 100">
                                                <circle cx="50" cy="50" r="46" fill="none" stroke="currentColor" stroke-width="5" class="text-base-300"/>
                                                <circle cx="50" cy="50" r="46" fill="none" stroke="currentColor" stroke-width="5" stroke-linecap="round" class="text-primary transition-all duration-700" stroke-dasharray="289" stroke-dashoffset="1"/>
                                            </svg>

                                            <div class="overflow-hidden shrink-0 absolute inset-2 rounded-full text-primary-content flex items-center justify-center text-2xl sm:text-3xl md:text-4xl font-bold ring-4 ring-base-100">
                                                <img src="https://flagcdn.com/w80/{{ strtolower($item->iso_code) }}.png"
                                                alt="{{ $item->name }} flag" class="w-full h-full object-cover">
                                            </div>

                                            @if($isComplete)
                                                <div class="tooltip absolute -bottom-1 -right-1">
                                                    <div class="w-6 h-6 rounded-full bg-success text-success-content flex items-center justify-center text-[10px] font-bold shadow">
                                                        <i class="fa-solid fa-check"></i>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>

                                        <div class="min-w-0">
                                            <p class="font-medium text-slate-800 truncate">{{ $item->name }}</p>
                                            <p class="text-xs text-slate-400 truncate">{{ $item->slug }}</p>
                                        </div>
                                    </div>
                                </td>

                                {{-- ISO --}}
                                <td class="flex md:table-cell items-center justify-between md:justify-start gap-2 px-0 md:px-4 py-1.5 md:py-3 before:content-['ISO'] before:text-[10px] before:font-semibold before:uppercase before:text-slate-400 before:tracking-wide md:before:content-none md:before:hidden">
                                    <span class="badge badge-ghost">{{ $item->iso_code ?? '-' }}</span>
                                </td>

                                {{-- Capital --}}
                                <td class="flex md:table-cell items-center justify-between md:justify-start gap-2 px-0 md:px-4 py-1.5 md:py-3 before:content-['Capital'] before:text-[10px] before:font-semibold before:uppercase before:text-slate-400 before:tracking-wide md:before:content-none md:before:hidden">
                                    {{ $item->capital ?? '-' }}
                                </td>

                                {{-- Region --}}
                                <td class="flex md:table-cell items-center justify-between md:justify-start gap-2 px-0 md:px-4 py-1.5 md:py-3 before:content-['Region'] before:text-[10px] before:font-semibold before:uppercase before:text-slate-400 before:tracking-wide md:before:content-none md:before:hidden">
                                    @if($item->region)
                                        <span class="badge badge-outline badge-sm cv-tag">{{ $item->region }}</span>
                                    @else
                                        -
                                    @endif
                                </td>

                                {{-- Currency --}}
                                <td class="flex md:table-cell items-center justify-between md:justify-start gap-2 px-0 md:px-4 py-1.5 md:py-3 before:content-['Currency'] before:text-[10px] before:font-semibold before:uppercase before:text-slate-400 before:tracking-wide md:before:content-none md:before:hidden">
                                    {{ $item->currency_code ?? $item->currency ?? '-' }}
                                </td>

                                {{-- Phone --}}
                                <td class="flex md:table-cell items-center justify-between md:justify-start gap-2 px-0 md:px-4 py-1.5 md:py-3 before:content-['Phone'] before:text-[10px] before:font-semibold before:uppercase before:text-slate-400 before:tracking-wide md:before:content-none md:before:hidden">
                                    {{ $item->phone_code ?? '-' }}
                                </td>

                                {{-- Actions --}}
                                <td class="block md:table-cell text-right md:text-center px-0 md:px-4 py-2 md:py-3 mt-2 md:mt-0 pt-3 md:pt-3 border-t md:border-t-0 border-dashed border-slate-200">
                                    <div class="flex justify-end md:justify-center gap-2">
                                        {{-- Edit --}}
                                        <a href="{{ route('countries.edit',$item) }}" class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-100 text-xs font-medium px-3 py-1.5 transition hover:text-indigo-600">
                                            <i class="fa-solid fa-pen"></i> Edit
                                        </a>

                                        {{-- Delete --}}
                                        <button type="button" class="delete-item inline-flex items-center gap-1.5 rounded-lg border border-rose-100 bg-rose-50 text-rose-600 hover:bg-rose-100 text-xs font-medium px-3 py-1.5 transition cursor-pointer"
                                            data-url="{{ route('countries.destroy', $item) }}" 
                                            data-name="{{ $item->name }}"
                                            data-title="Delete Country">
                                            <i class="fa-solid fa-trash"></i> Delete
                                        </button>
                                    </div>
                                </td>
                            </tr>

                        @empty

                            {{-- No Results --}}
                            <tr class="block md:table-row">
                                <td colspan="{{ auth()->check() ? 7 : 6 }}" class="block md:table-cell text-center py-16">
                                    <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-indigo-50 text-indigo-400 mb-4">
                                        <i class="fa-solid fa-box-archive text-2xl"></i>
                                    </div>
                                    <p class="cv-title text-lg">No countries found</p>
                                    <p class="text-sm opacity-50 mb-4">
                                        @if(request('search'))
                                            No countries match "{{ request('search') }}".
                                        @else
                                            Add a countries to start building your record.
                                        @endif
                                    </p>
    
                                    @if(request('search'))
                                        <a href="{{ route('countries.index') }}" class="btn btn-sm btn-outline">
                                            <i class="fa-solid fa-rotate-left"></i> Clear search
                                        </a>
                                    @elseif(auth()->check())
                                        <a href="{{ route('countries.create') }}" class="btn btn-sm btn-primary">
                                            <i class="fa-solid fa-plus"></i> Add Country
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
    
    {{-- Pagination --}}
    <div id="countryPagination" class="mt-8 pb-20 md:pb-0" data-total="{{ $countries->total() }}" data-per-page="{{ $countries->perPage() }}">
        {{ $countries->links() }}
    </div>
</div>

{{-- Mobile floating "Add Country" button — same trigger as the desktop button above --}}
<button onclick="countryModal.showModal()" class="fixed bottom-5 right-5 z-40 inline-flex items-center justify-center w-14 h-14 rounded-full bg-indigo-600 text-white shadow-lg shadow-indigo-900/30 hover:bg-indigo-700 cursor-pointer" aria-label="Add Country">
    <i class="fa-solid fa-plus text-lg"></i>
</button>

{{-- Delete Confirmation Modal --}}
<div>
    @include('modular.delete_modal')
</div>

{{-- Add / Edit Country Modal --}}
<dialog id="countryModal" class="modal">
    <div class="modal-box max-w-3xl rounded-2xl">
        {{-- Modal Header --}}
        <h3 class="font-bold text-xl mb-5">
            <i class="fa-solid fa-globe text-indigo-600 mr-2"></i>
            {{ isset($country) ? 'Edit Country' : 'Add Country' }}
        </h3>

        <form action="{{ isset($country) ? route('countries.update', $country) : route('countries.store') }}" method="POST">
            @csrf
            
            @if(isset($country))
                @method('PUT')
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                {{-- Country Name --}}
                <div>
                    <label class="text-sm font-medium">Country Name</label>
                    <input type="text" name="name" value="{{ old('name', $country->name ?? '') }}" placeholder="Bangladesh" class="input input-bordered w-full mt-1 @error('name') input-error @enderror">
                </div>

                {{-- ISO Code --}}
                <div>
                    <label class="text-sm font-medium">ISO Code</label>
                    <input type="text" name="iso_code" value="{{ old('iso_code', $country->iso_code ?? '') }}" placeholder="BD" class="input input-bordered w-full mt-1">
                </div>

                {{-- Phone Code --}}
                <div>
                    <label class="text-sm font-medium">Phone Code</label>
                    <input type="text" name="phone_code" value="{{ old('phone_code', $country->phone_code ?? '') }}" placeholder="+880" class="input input-bordered w-full mt-1">
                </div>

                {{-- Currency Code --}}
                <div>
                    <label class="text-sm font-medium">Currency Code</label>
                    <input type="text" name="currency" value="{{ old('currency', $country->currency ?? '') }}" placeholder="BDT" class="input input-bordered w-full mt-1">
                </div>

                {{-- Capital --}}
                <div>
                    <label class="text-sm font-medium">Capital</label>
                    <input type="text" name="capital" value="{{ old('capital', $country->capital ?? '') }}" placeholder="Dhaka" class="input input-bordered w-full mt-1">
                </div>

                {{-- Region --}}
                <div>
                    <label class="text-sm font-medium">Region</label>
                    <select name="region" class="select select-bordered w-full mt-1">
                        <option value="">Select Region</option>
                        @foreach(['Asia', 'Europe', 'Africa', 'North America', 'South America', 'Oceania'] as $region)
                            <option value="{{ $region }}" {{ old('region', $country->region ?? '') == $region ? 'selected' : '' }}>
                                {{ $region }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Flag --}}
                <div>
                    <label class="text-sm font-medium">Flag Emoji</label>
                    <input type="text" name="flag" value="{{ old('flag', $country->flag ?? '') }}" placeholder="🇧🇩" class="input input-bordered w-full mt-1">
                </div>
            </div>

            {{-- Actions --}}
            <div class="modal-action">
                <button type="button" onclick="countryModal.close()" class="btn btn-ghost">Cancel</button>

                <button type="submit" class="btn bg-indigo-600 hover:bg-indigo-700 text-white">
                    <i class="fa-solid fa-save"></i>
                    {{ isset($country) ? 'Update Country' : 'Save Country' }}
                </button>
            </div>
        </form>
    </div>

    {{-- Close modal by clicking outside --}}
    <form method="dialog" class="modal-backdrop">
        <button type="submit">close</button>
    </form>
</dialog>

{{-- Open Edit / Validation Modal --}}
@if(isset($country))
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            countryModal.showModal();
        });
    </script>
@endif

@endsection