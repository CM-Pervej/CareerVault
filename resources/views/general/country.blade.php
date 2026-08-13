@extends('layouts.app')

@section('title', 'Countries | CareerVault')

@section('content')
<div>
    {{-- Breadcrumb --}}
    <div class="breadcrumbs text-sm mb-2">
        <ul>
            <li><a href="{{ route('dashboard') }}"><i class="fa-solid fa-house mr-1"></i>Dashboard</a></li>
            <li class="font-semibold">Countries</li>
        </ul>
    </div>

    {{-- Header --}}
    <section class="flex flex-col md:flex-row md:items-end md:justify-between gap-4 mb-8">
        <div>
            <h1 class="cv-title text-3xl">Countries</h1>

            <p class="text-base-content/60 text-sm">
                {{ $countries->count() }} countries on record
            </p>
        </div>

        <button onclick="countryModal.showModal()" class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-4 py-2.5">
            <i class="fa-solid fa-plus"></i>
            Add Country
        </button>
    </section>

    {{-- Countries List --}}
    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm">
        <div class="p-6">
            {{-- Search --}}
            <div class="flex flex-col sm:flex-row justify-between gap-4 mb-5">
                <h2 class="text-sm font-semibold text-slate-700">All Countries</h2>

                <div class="relative w-full sm:w-72">
                    <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400"></i>

                    <input id="searchInput" type="text" placeholder="Search country..."
                        class="w-full rounded-xl border border-slate-300 pl-10 pr-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                </div>
            </div>

            <p id="resultCount" class="text-xs text-slate-400 mb-3"></p>

            <div class="overflow-x-auto">
                <table class="table">
                    <thead>
                        <tr class="cv-eyebrow border-b">
                            <th>#</th> <th>Country</th> <th>ISO</th> <th>Capital</th> <th>Region</th> <th>Currency</th> <th>Phone</th> <th class="text-center">Action</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y" id="countryTable">
                        @forelse($countries as $item)
                        <tr class="crud-row country-card" data-name="{{ strtolower($item->name) }}">
                            <td class="cv-mono text-xs opacity-50">{{ str_pad($loop->iteration,2,'0',STR_PAD_LEFT) }}</td>

                            {{-- Country --}}
                            <td>
                                <div class="flex items-center gap-3">
                                    <div class="size-10 rounded-full overflow-hidden border border-slate-200 shadow-sm">
                                        <img src="https://flagcdn.com/w80/{{ strtolower($item->iso_code) }}.png"
                                            alt="{{ $item->name }} flag" class="w-full h-full object-cover">
                                    </div>

                                    <div>
                                        <p class="font-medium text-slate-800">{{ $item->name }}</p>
                                        <p class="text-xs text-slate-400">{{ $item->slug }}</p>
                                    </div>
                                </div>
                            </td>

                            {{-- ISO --}}
                            <td><span class="badge badge-ghost">{{ $item->iso_code ?? '-' }}</span></td>

                            {{-- Capital --}}
                            <td>{{ $item->capital ?? '-' }}</td>

                            {{-- Region --}}
                            <td>{{ $item->region ?? '-' }}</td>

                            {{-- Currency --}}
                            <td>{{ $item->currency_code ?? $item->currency ?? '-' }}</td>

                            {{-- Phone --}}
                            <td>{{ $item->phone_code ?? '-' }}</td>

                            {{-- Actions --}}
                            <td>
                                <div class="flex justify-center gap-2">
                                    <a href="{{ route('countries.edit',$item) }}" class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-100 text-xs font-medium px-3 py-1.5 transition hover:text-indigo-600">
                                        <i class="fa-solid fa-pen"></i>
                                        Edit
                                    </a>

                                    <button type="button" class="open-delete-modal inline-flex items-center gap-1.5 rounded-lg border border-rose-100 bg-rose-50 text-rose-600 hover:bg-rose-100 text-xs font-medium px-3 py-1.5 transition"
                                        data-action="{{ route('countries.destroy',$item) }}" data-name="{{ $item->name }}">
                                        <i class="fa-solid fa-trash"></i>
                                        Delete
                                    </button>
                                </div>
                            </td>
                        </tr>

                        @empty

                        <tr>
                            <td colspan="8" class="text-center py-12 text-slate-400">No countries found.</td>
                        </tr>

                        @endforelse

                        <tr id="noResultsRow" class="hidden">
                            <td colspan="8" class="text-center py-12 text-slate-400">No matching countries found.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Delete Modal --}}
<dialog id="deleteModal" class="modal">
    <div class="modal-box rounded-2xl">
        <h3 class="font-bold text-lg">Delete Country</h3>

        <p class="py-4">
            Are you sure you want to delete
            <strong id="deleteItemName"></strong>?
        </p>

        <div class="modal-action">
            <button type="button" onclick="deleteModal.close()" class="btn">Cancel</button>

            <form id="deleteForm" method="POST">
                @csrf
                @method('DELETE')
                <button class="btn bg-rose-600 text-white">Delete</button>
            </form>
        </div>
    </div>

    <form method="dialog" class="modal-backdrop">
        <button>close</button>
    </form>
</dialog>

<dialog id="countryModal" class="modal">
    <div class="modal-box max-w-3xl rounded-2xl">
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

                {{-- Currency --}}
                {{-- <div>
                    <label class="text-sm font-medium">Currency</label>
                    <input type="text" name="currency" value="{{ old('currency', $country->currency ?? '') }}" placeholder="Bangladeshi Taka" class="input input-bordered w-full mt-1">
                </div> --}}

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
        <button>close</button>
    </form>
</dialog>

@if(isset($country))

<script>
    document.addEventListener('DOMContentLoaded', function () {
        countryModal.showModal();
    });
</script>

@endif
@endsection