@extends('layouts.app')

@section('title', 'Countries | CareerVault')

@section('content')
<div>
    {{-- Breadcrumb --}}
    <div class="breadcrumbs text-sm mb-2">
        <ul>
            <li> <a href="{{ route('dashboard') }}"> <i class="fa-solid fa-house mr-1"></i> Dashboard </a> </li> <li> Countries </li>
        </ul>
    </div>

    <section class="flex flex-col md:flex-row md:items-end md:justify-between gap-4 mb-8">
        <div>
            <h1 class="cv-title text-3xl">Countries</h1>
            <p class="text-base-content/60 text-sm">
                {{ $countries->total() ?? $countries->count() }} countries on record
            </p>
        </div>

        {{-- Form Card --}}
        <div class="w-full">
            <div class="w-full">
                {{-- <h2 class="text-sm font-semibold text-slate-700 mb-4 flex items-center gap-2">
                    <i class="fa-solid {{ isset($country) ? 'fa-pen' : 'fa-plus' }} text-indigo-500"></i>
                    {{ isset($country) ? 'Edit Country' : 'Add New Country' }}
                </h2> --}}

                <form action="{{ isset($country) ? route('countries.update', $country) : route('countries.store') }}" method="POST" autocomplete="off" class="flex flex-col sm:flex-row sm:items-start gap-3">
                    @csrf
                    @isset($country)
                        @method('PUT')
                    @endisset

                    <fieldset class="flex-1">
                        <input type="text" name="name" class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm text-slate-800 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition @error('name') border-rose-400 focus:ring-rose-400 @enderror" value="{{ old('name', $country->name ?? '') }}" placeholder="e.g. Bangladesh, Canada, Japan">

                        @error('name')
                            <p class="text-xs text-rose-600 mt-1.5 flex items-center gap-1">
                                <i class="fa-solid fa-circle-exclamation"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </fieldset>

                    <div class="flex gap-2 shrink-0">
                        <button class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-4 py-2.5 shadow-sm transition">
                            <i class="fa-solid {{ isset($country) ? 'fa-check' : 'fa-plus' }}"></i>
                            {{ isset($country) ? 'Update' : 'Add Country' }}
                        </button>

                        @isset($country)
                            <a href="{{ route('countries.index') }}" class="inline-flex items-center rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 text-sm font-medium px-4 py-2.5 transition">
                                Cancel
                            </a>
                        @endisset
                    </div>
                </form>
            </div>
        </div>
    </section>

    {{-- List Card --}}
    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm">
        <div class="p-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-5">
                <h2 class="text-sm font-semibold text-slate-700">All Countries</h2>

                <div class="relative w-full sm:w-72">
                    <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                    <input id="searchInput" type="text" class="w-full rounded-xl border border-slate-300 pl-9 pr-4 py-2.5 text-sm text-slate-800 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition" placeholder="Search country...">
                </div>
            </div>

            <p id="resultCount" class="text-xs text-slate-400 mb-3"> </p>

            <div class="card bg-base-100 shadow border border-base-300 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="table">
                        <thead>
                            <tr class="cv-eyebrow border-b border-base-300">
                                <th class="w-10">#</th> <th>Country</th> <th>Slug</th> <th class="text-center">Action</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-100">
                            @forelse($countries as $item)
                            <tr class="crud-row cv-row company-card border-b border-base-300"
                                data-name="{{ strtolower($item->name) }}">

                                <td class="cv-mono text-xs opacity-40">
                                    {{ str_pad($loop->iteration + ($countries->currentPage() - 1) * $countries->perPage(), 2, '0', STR_PAD_LEFT) }}
                                </td>

                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-3">
                                        <div class="cv-avatar bg-primary text-primary-content">
                                            {{ strtoupper(substr($item->name, 0, 1)) }}
                                        </div>
                                        <span class="font-medium text-slate-800">{{ $item->name }}</span>
                                    </div>
                                </td>

                                <td class="px-4 py-3">
                                    <span class="inline-flex items-center rounded-md bg-slate-100 text-slate-500 text-xs font-mono px-2 py-1">
                                        {{ $item->slug }}
                                    </span>
                                </td>

                                <td class="px-4 py-3">
                                    <div class="flex gap-2 justify-center">

                                        <a href="{{ route('countries.edit', $item) }}" class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-50 text-xs font-medium px-3 py-1.5 transition">
                                            <i class="fa-solid fa-pen text-[11px]"></i>
                                            Edit
                                        </a>

                                        <button type="button" class="open-delete-modal inline-flex items-center gap-1.5 rounded-lg border border-rose-100 bg-rose-50 text-rose-600 hover:bg-rose-100 text-xs font-medium px-3 py-1.5 transition" data-action="{{ route('countries.destroy', $item) }}" data-name="{{ $item->name }}">
                                            <i class="fa-solid fa-trash text-[11px]"></i>
                                            Delete
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-12">
                                    <div class="flex flex-col items-center gap-2 text-slate-400">
                                        <i class="fa-solid fa-inbox text-2xl"></i>
                                        <p class="text-sm">No countries found.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse

                            <tr id="noResultsRow" class="hidden">
                                <td colspan="4" class="text-center py-12">
                                    <div class="flex flex-col items-center gap-2 text-slate-400">
                                        <i class="fa-solid fa-magnifying-glass text-2xl"></i>
                                        <p class="text-sm">No countries match your search.</p>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-5">
                {{ $countries->links() }}
            </div>
        </div>
    </div>
</div>

{{-- Delete Confirmation Modal --}}
<dialog id="deleteModal" class="modal">
    <div class="modal-box max-w-md rounded-2xl">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-rose-100 flex items-center justify-center shrink-0">
                <i class="fa-solid fa-trash text-rose-600"></i>
            </div>

            <div>
                <h3 class="font-bold text-lg text-slate-900">Delete Country</h3>
                <p class="text-sm text-slate-500">This action cannot be undone.</p>
            </div>
        </div>

        <div class="mt-6 text-slate-600">
            Are you sure you want to delete
            <span id="deleteItemName" class="font-semibold text-rose-600"></span>?
        </div>

        <div class="modal-action">
            <form method="dialog">
                <button class="btn btn-ghost">Cancel</button>
            </form>

            <form id="deleteForm" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn bg-rose-600 hover:bg-rose-700 text-white border-none">
                    <i class="fa-solid fa-trash"></i>
                    Delete
                </button>
            </form>
        </div>
    </div>

    <form method="dialog" class="modal-backdrop">
        <button>close</button>
    </form>
</dialog>
@endsection