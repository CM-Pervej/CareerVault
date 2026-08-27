@extends('layouts.app')

@section('title', 'Cities | CareerVault')

@section('content')

    {{-- Prepare countries and grouped cities --}}
    @php
        $countriesWithCities = $cities
        ->filter(fn ($city) => $city->country)
        ->pluck('country')
        ->unique('id')
        ->sortBy('name')
        ->values();

        $groupedCities = $cities
            ->filter(fn ($city) => $city->country)
            ->groupBy(fn ($city) => $city->country->id)
            ->sortKeys();

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
                    <i class="fa-solid fa-city text-sm"></i>
                    <a href="{{ route('dashboard') }}">CareerVault</a> /
                    <span>General</span> /
                </p>
                <div class="flex items-center gap-2">
                    <h1 class="text-2xl sm:text-3xl font-bold text-white">Cities</h1>
                    <sup id="cityTotal" class="rounded-full bg-white/20 text-white text-xs font-bold px-2 py-1 leading-none border border-white/30"> {{ $cities->count() }} </sup>
                </div>
                <p class="text-indigo-100/80 text-sm max-w-2xl mt-1">Manage cities and keep your company locations organized across your directory</p>

                {{-- Quick stats, computed from the full country list --}}
                <div class="flex flex-wrap items-center gap-2 mt-5">
                    <div class="flex items-center gap-2 rounded-lg bg-white/10 border border-white/15 px-3 py-2">
                        <i class="fa-solid fa-city text-sm text-indigo-200"></i>
                        <span class="text-white text-sm font-semibold">{{ $totalCity }}</span>
                        <span class="text-indigo-200 text-xs">City</span>
                    </div>
                    <div class="flex items-center gap-2 rounded-lg bg-white/10 border border-white/15 px-3 py-2">
                        <i class="fa-solid fa-earth-americas text-indigo-200 text-xs"></i>
                        <span id="countryStatShowing" class="text-white text-sm font-semibold"> {{ $countriesWithCities->count() }} </span>
                        <span class="text-indigo-200 text-xs">Country</span>
                    </div>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row sm:items-center gap-3 w-full lg:w-auto">
                {{-- Search (ids / data-url / value unchanged for existing JS) --}}
                <div class="relative w-full sm:w-64">
                    <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm pointer-events-none"></i>
                    <input type="text" class="city-search-input w-full rounded-sm border-0 bg-white pl-9 pr-9 py-2.5 text-sm text-slate-800 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-white/70 shadow-sm focus:border-indigo-500 transition disabled:bg-slate-100 disabled:cursor-not-allowed" placeholder="Search city..." disabled aria-label="Search cities">
                        <kbd class="hidden md:inline-flex absolute right-3 top-1/2 -translate-y-1/2 text-[10px] font-medium text-slate-400 border border-slate-200 rounded px-1.5 py-0.5">/</kbd>
                    <button id="clearCitySearch" type="button" class="hidden absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition" aria-label="Clear search">
                        <i class="fa-solid fa-circle-xmark text-sm"></i>
                    </button>
                </div>

                <button onclick="cityModal.showModal()" class="inline-flex items-center justify-center gap-2 rounded-sm bg-white hover:bg-indigo-50 text-indigo-700 text-sm font-medium px-4 py-2.5 shadow-sm transition shrink-0 cursor-pointer">
                    <i class="fa-solid fa-plus"></i> Add City
                </button>
            </div>
        </div>
    </div>
</section>

{{-- Country + City Section --}}
<section class="flex flex-col lg:flex-row gap-6 items-start">
    {{-- COUNTRY LIST (sticky on lg+, drill-in on mobile) --}}
    <div id="countryPanel" class="w-full lg:w-[22rem] shrink-0 lg:sticky lg:top-14 lg:self-start lg:block">
        <div class="bg-white border border-slate-200/80 rounded-lg shadow-sm hover:shadow-md transition-shadow duration-300 overflow-hidden">
            {{-- Header --}}
            <div class="relative px-6 pt-6 pb-5 bg-gradient-to-br from-indigo-600 via-indigo-600 to-violet-600">
                <div class="absolute inset-0 opacity-10 [background-image:radial-gradient(circle_at_1px_1px,white_1px,transparent_0)] [background-size:16px_16px]"></div>
                <div class="relative flex items-center justify-between mb-4">
                    <div>
                        <h2 class="text-sm font-semibold text-white tracking-wide">Countries</h2>
                        <p class="text-xs text-indigo-100/80 mt-0.5">Pick a country to browse its cities</p>
                    </div>
                    <span class="inline-flex items-center justify-center rounded-full bg-white/15 backdrop-blur px-2.5 py-1 text-xs font-semibold text-white ring-1 ring-white/20"> {{ $countriesWithCities->count() }} </span>
                </div>

                <div class="relative w-full">
                    <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                    <input id="searchInput" type="text" class="w-full rounded-xl border-0 bg-white/15 backdrop-blur pl-9 pr-9 py-2.5 text-sm text-white placeholder:text-white/60 focus:outline-none focus:ring-2 focus:ring-white/60 transition" placeholder="Search country..." aria-label="Search countries">
                    <kbd class="hidden md:inline-flex absolute right-3 top-1/2 -translate-y-1/2 text-[10px] font-medium text-slate-400 border border-slate-200 rounded px-1.5 py-0.5">/</kbd>
                    <button id="clearCountrySearch" type="button" class="hidden absolute right-3 top-1/2 -translate-y-1/2 text-white/60 hover:text-white transition" aria-label="Clear search">
                        <i class="fa-solid fa-circle-xmark text-sm"></i>
                    </button>
                </div>

                {{-- Sort toggle --}}
                <div class="relative flex items-center gap-1.5 mt-3" role="group" aria-label="Sort countries">
                    <span class="text-[11px] text-indigo-100/70 mr-0.5">Sort:</span>
                    <button type="button" id="sortByName" data-sort="name" class="sort-btn active-sort-btn text-[11px] font-medium px-2.5 py-1 rounded-full bg-white/20 text-white transition">A–Z</button>
                    <button type="button" id="sortByCityCount" data-sort="count" class="sort-btn text-[11px] font-medium px-2.5 py-1 rounded-full text-indigo-100/70 hover:bg-white/10 transition">Most cities</button>
                </div>
            </div>

            <p id="resultCount" class="text-xs text-slate-400 px-6 pt-4" aria-live="polite"></p>

            {{-- Country list --}}
            <div class="max-h-[32rem] lg:max-h-[calc(100vh-20rem)] overflow-y-auto px-3 pb-4 pt-2 [scrollbar-width:thin]" id="countryTable">
                <div id="countryListItems">
                    @forelse($countriesWithCities as $country)
                        @php $countryCityCount = $groupedCities->get($country->id)?->count() ?? 0; @endphp

                        <div class="crud-row country-card group relative flex items-center gap-3 rounded-xl px-3 py-2.5 mx-0.5 my-1 cursor-pointer border border-transparent hover:border-indigo-100 hover:bg-indigo-50/60 focus:outline-none focus:ring-2 focus:ring-indigo-400 transition-all duration-150"
                            data-country-id="{{ $country->id }}"
                            data-name="{{ strtolower($country->name) }}"
                            data-city-count="{{ $countryCityCount }}"
                            tabindex="0" role="button" aria-label="View cities in {{ $country->name }}">

                            {{-- Number --}}
                            <span class="cv-mono text-[10px] font-medium text-slate-300 w-5 shrink-0 group-hover:text-indigo-300 transition-colors">
                                {{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}
                            </span>

                            {{-- Flag --}}
                            <div class="size-10 rounded-full overflow-hidden border-2 border-white ring-1 ring-slate-200 shadow-sm shrink-0 group-hover:ring-indigo-200 transition-all">
                                <img src="https://flagcdn.com/w80/{{ strtolower($country->iso_code) }}.png" alt="{{ $country->name }} flag" class="w-full h-full object-cover" loading="lazy">
                            </div>

                            {{-- Country information --}}
                            <div class="min-w-0 flex-1">
                                <p class="country-name font-medium text-slate-800 text-sm truncate"> {{ $country->name }} </p>
                                <p class="text-xs text-slate-400 truncate"> {{ $country->slug }} </p>
                            </div>

                            {{-- City count badge --}}
                            <span class="cv-mono shrink-0 text-[11px] font-medium text-slate-400 bg-slate-50 group-hover:bg-indigo-100 group-hover:text-indigo-600 rounded-full px-2 py-0.5 transition-colors">
                                {{ $countryCityCount }}
                            </span>

                            {{-- Chevron --}}
                            <i class="fa-solid fa-chevron-right text-[10px] text-slate-300 group-hover:text-indigo-400 transition-colors shrink-0"></i>
                        </div>

                    @empty

                        <div class="flex flex-col items-center gap-2 text-slate-400 py-14">
                            <i class="fa-solid fa-inbox text-2xl"></i>
                            <p class="text-sm">No countries found.</p>
                        </div>
                    @endforelse
                </div>

                {{-- No country search result --}}
                <div id="countryNoResults" class="hidden text-center py-14 text-slate-400">
                    <i class="fa-solid fa-magnifying-glass text-xl"></i>
                    <p class="text-sm mt-2">No countries match your search.</p>
                </div>
            </div>
        </div>
    </div>

    {{-- CITY LIST (hidden on mobile until a country is picked) --}}
    <div id="cityPanel" class="hidden lg:block bg-white border border-slate-200/80 rounded-lg shadow-sm hover:shadow-md transition-shadow duration-300 w-full min-w-0">
        <div class="p-6">
            {{-- Back to countries (mobile only) --}}
            <button id="backToCountries" type="button" class="hidden lg:hidden items-center gap-2 text-xs font-medium text-indigo-600 hover:text-indigo-700 mb-4 -ml-1 px-2 py-1 rounded-lg hover:bg-indigo-50 transition">
                <i class="fa-solid fa-arrow-left text-[11px]"></i> Back to countries
            </button>

            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-5">
                <div class="min-w-0 flex items-center gap-3">
                    <div id="selectedCountryFlag" class="hidden size-max overflow-hidden border-2 border-white ring-1 ring-slate-200 shadow-sm shrink-0"></div>
                    <div class="min-w-0">
                        <h2 id="selectedCountryLabel" class="text-base font-semibold text-slate-800 truncate">All Cities</h2>
                        <p id="selectedCountryHint" class="text-xs text-slate-400 mt-1">Select a country to view its cities.</p>
                    </div>
                </div>
            </div>

            {{-- City result count --}}
            <p id="cityResultCount" class="text-xs text-slate-400 mb-3" aria-live="polite"></p>

            {{-- Initial message --}}
            <div id="selectCountryMessage" class="flex flex-col items-center justify-center py-24 text-slate-400 border border-dashed border-slate-200 rounded-2xl bg-slate-50/60">
                <div class="size-14 rounded-full bg-indigo-50 flex items-center justify-center mb-4">
                    <i class="fa-solid fa-city text-xl text-indigo-400"></i>
                </div>
                <p class="text-sm font-medium text-slate-500">Select a country to view its cities.</p>
                <p class="text-xs text-slate-400 mt-1">Choose from the list on the left to get started.</p>
            </div>

            {{-- City Groups --}}
            <div class="space-y-8">
                @forelse($groupedCities as $countryId => $countryCities)
                    @php
                        $country = $countryCities->first()->country;
                    @endphp

                    {{-- Country city group --}}
                    <div class="country-group hidden" data-country-id="{{ $country->id }}">
                        {{-- City table --}}
                        <div class="card bg-base-100 shadow-sm border border-base-300 overflow-hidden rounded-2xl">
                            <div class="overflow-x-auto">
                                <table class="table">
                                    <thead>
                                        <tr class="cv-eyebrow border-b border-base-300 bg-slate-50/80">
                                            <th class="w-10">#</th> <th>City</th> <th>Slug</th> <th class="text-center"> Action </th>
                                        </tr>
                                    </thead>

                                    <tbody class="divide-y divide-slate-100">
                                        @foreach($countryCities as $item)
                                            <tr class="city-row cv-row company-card border-b border-base-300 hover:bg-indigo-50/40 transition-colors" data-name="{{ strtolower($item->name) }}">
                                                {{-- Number --}}
                                                <td class="cv-mono text-xs opacity-40"> {{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }} </td>

                                                {{-- City --}}
                                                <td class="px-4 py-3">
                                                    <div class="flex items-center gap-3">
                                                        <div class="cv-avatar bg-primary text-primary-content shadow-sm"> {{ strtoupper(substr($item->name, 0, 1)) }} </div>
                                                        <span class="city-name font-medium text-slate-800"> {{ $item->name }} </span>
                                                    </div>
                                                </td>

                                                {{-- Slug --}}
                                                <td class="px-4 py-3">
                                                    <button type="button" class="copy-slug-btn inline-flex items-center gap-1.5 rounded-md bg-slate-100 hover:bg-slate-200 text-slate-500 text-xs font-mono px-2 py-1 transition" data-slug="{{ $item->slug }}" title="Copy slug">
                                                        <span>{{ $item->slug }}</span>
                                                        <i class="fa-regular fa-copy text-[10px] opacity-60"></i>
                                                    </button>
                                                </td>

                                                {{-- Actions --}}
                                                <td class="px-4 py-3">
                                                    <div class="flex gap-2 justify-center">
                                                        {{-- Edit --}}
                                                        <a href="{{ route('cities.edit', ['country' => $item->country->slug, 'city' => $item->slug,]) }}" 
                                                            class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-50 hover:border-indigo-200 hover:text-indigo-600 text-xs font-medium px-3 py-1.5 transition">
                                                            <i class="fa-solid fa-pen text-[11px]"></i> Edit
                                                        </a>

                                                        {{-- Delete --}}
                                                        <button type="button" class="delete-item inline-flex items-center gap-1.5 rounded-lg border border-rose-100 bg-rose-50 text-rose-600 hover:bg-rose-100 text-xs font-medium px-3 py-1.5 transition cursor-pointer"
                                                            data-url="{{ route('cities.destroy', ['country' => $item->country->slug, 'city' => $item->slug,]) }}" 
                                                            data-name="{{ $item->name }}"
                                                            data-title="Delete City">
                                                            <i class="fa-solid fa-trash text-[11px]"></i> Delete
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        {{-- No city search result --}}
                        <div class="city-no-results hidden text-center py-10 text-slate-400">
                            <i class="fa-solid fa-magnifying-glass text-xl"></i>
                            <p class="text-sm mt-2">No cities match your search.</p>
                        </div>
                    </div>

                @empty
                
                    <div class="card bg-base-100 shadow border border-base-300 overflow-hidden">
                        <table class="table">
                            <tbody>
                                <tr>
                                    <td colspan="4" class="text-center py-12">
                                        <div class="flex flex-col items-center gap-2 text-slate-400">
                                            <i class="fa-solid fa-inbox text-2xl"></i>
                                            <p class="text-sm">No cities found.</p>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                @endforelse
            </div>

            {{-- Search Empty State for shared CRUD search --}}
            <div class="hidden">
                <table class="table">
                    <tbody>
                        <tr id="noResultsRow">
                            <td colspan="4">No results.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

{{-- Mobile floating "Add Citry" button — same trigger as the desktop button above --}}
<button onclick="cityModal.showModal()" class="fixed bottom-5 right-5 z-40 inline-flex items-center justify-center w-14 h-14 rounded-full bg-indigo-600 text-white shadow-lg shadow-indigo-900/30 hover:bg-indigo-700 cursor-pointer" aria-label="Add Country">
    <i class="fa-solid fa-plus text-lg"></i>
</button>

{{-- DELETE CONFIRMATION MODAL --}}
<div>
    @include('modular.delete_modal')
</div>

{{-- Add / Edit City Modal --}}
<dialog id="cityModal" class="modal">
    <div class="modal-box max-w-3xl rounded-2xl">
        {{-- Modal Header --}}
        <h3 class="font-bold text-xl mb-5">
            <i class="fa-solid fa-globe text-indigo-600 mr-2"></i>
            {{ isset($city) ? 'Edit City' : 'Add City' }}
        </h3>

        <form action="{{ isset($city) ? route('cities.update', ['country' => $city->country->slug, 'city' => $city->slug,]) : route('cities.store') }}" method="POST" autocomplete="off" class="flex flex-col gap-4 w-full">
            @csrf

            @isset($city)
                @method('PUT')
            @endisset

            <div class="flex gap-4">
                {{-- Country --}}
                <fieldset class="w-max">
                    <select id="countryIdSelect" name="country_id" class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition @error('country_id') border-rose-400 focus:ring-rose-400 @enderror">
                        <option value="">Select country</option>

                        @foreach($countries as $country)
                            <option value="{{ $country->id }}" @selected(old('country_id', $city->country_id ?? '') == $country->id)> {{ $country->name }} </option>
                        @endforeach
                    </select>

                    @error('country_id')
                        <p class="text-xs text-rose-600 mt-1.5 flex items-center gap-1">
                            <i class="fa-solid fa-circle-exclamation"></i> {{ $message }}
                        </p>
                    @enderror
                </fieldset>

                {{-- City Name --}}
                <fieldset class="w-full">
                    <div class="relative">
                        <i class="fa-solid fa-plus absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm pointer-events-none"></i>
                        <input id="cityNameInput" type="text" name="name" class="w-full rounded-xl border border-slate-300 bg-slate-50/60 pl-9 pr-9 py-2.5 text-sm text-slate-800 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-500 focus:bg-white transition @error('name') border-rose-400 focus:ring-rose-400 @enderror" value="{{ old('name', $city->name ?? '') }}" placeholder="e.g. Dhaka, Khulna, Chittagong">
                        <kbd class="hidden md:inline-flex absolute right-3 top-1/2 -translate-y-1/2 text-[10px] font-medium text-slate-400 border border-slate-200 rounded px-1.5 py-0.5">/</kbd>
                    </div>

                    @error('name')
                        <p class="text-xs text-rose-600 mt-1.5 flex items-center gap-1">
                            <i class="fa-solid fa-circle-exclamation"></i>
                            {{ $message }}
                        </p>
                    @enderror
                </fieldset>
            </div>

            {{-- Buttons --}}
            <div class="flex justify-end items-end gap-4">
                <button type="submit" class="flex-1 lg:flex-none inline-flex items-center justify-center gap-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-4 py-2.5 shadow-sm transition cursor-pointer whitespace-nowrap">
                    <i class="fa-solid {{ isset($city) ? 'fa-check' : 'fa-plus' }}"></i> {{ isset($city) ? 'Update' : 'Add City' }}
                </button>

                <a href="{{ route('cities.index') }}" class="flex-1 lg:flex-none inline-flex items-center justify-center rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 text-sm font-medium px-4 py-2.5 transition whitespace-nowrap">Cancel</a>
            </div>
        </form>
    </div>

    {{-- Close modal by clicking outside --}}
    <form method="dialog" class="modal-backdrop">
        <button type="submit">close</button>
    </form>
</dialog>

{{-- Open Edit / Validation Modal --}}
@if(isset($city))
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            cityModal.showModal();
        });
    </script>
@endif

{{-- Tiny toast for copy-to-clipboard feedback --}}
<div id="cvToast" class="hidden fixed bottom-6 left-1/2 -translate-x-1/2 z-[9999] bg-slate-900 text-white text-xs font-medium px-4 py-2.5 rounded-full shadow-lg transition-all duration-200"></div>
@endsection