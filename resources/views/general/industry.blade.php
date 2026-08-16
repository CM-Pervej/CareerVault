@extends('layouts.app')

@section('title', 'Industries | CareerVault')

@section('content')
<div>
    <section class="mb-8">
        {{-- Breadcrumb --}}
        <div class="breadcrumbs text-sm mb-2">
            <ul>
                <li>
                    <a href="{{ route('dashboard') }}">
                        <i class="fa-solid fa-house mr-1"></i> Dashboard
                    </a>
                </li>
                <li class="font-semibold">Industries</li>
            </ul>
        </div>

        {{-- Header + Form --}}
        <header class="flex flex-col md:flex-row md:items-end md:justify-between gap-4">
            {{-- Page heading --}}
            <div class="min-w-0">
                <p class="cv-eyebrow text-xs font-semibold tracking-widest uppercase text-indigo-500/70 mb-1">Directory</p>
                <div class="flex items-center gap-2">
                    <h1 class="cv-title text-3xl font-bold text-slate-900">Industries</h1>
                    <sup id="industryCount" class="rounded-full bg-indigo-500 text-white text-xs font-bold px-2 py-1 leading-none"> {{ $industries->total() }} </sup>
                </div>
                <p class="text-base-content/60 text-sm max-w-2xl mt-1">Organize companies by industry and keep your industry directory structured and easy to manage</p>
            </div>

            {{-- Create / Edit Form --}}
            <div class="w-full md:w-auto">
                <form action="{{ isset($industry) ? route('industries.update', $industry) : route('industries.store') }}" method="POST" autocomplete="off" class="flex flex-col sm:flex-row sm:items-start gap-3 w-full">
                    @csrf

                    @isset($industry)
                        @method('PUT')
                    @endisset

                    {{-- Input --}}
                    <fieldset class="w-full sm:w-72 md:w-80">
                        <div class="relative">
                            <i class="fa-solid fa-plus absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm pointer-events-none"></i>
                            <input type="text" name="name" class="w-full rounded-xl border border-slate-300 bg-slate-50/60 pl-9 pr-9 py-2.5 text-sm text-slate-800 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-500 focus:bg-white transition @error('name') border-rose-400 focus:ring-rose-400 @enderror" value="{{ old('name', $industry->name ?? '') }}" placeholder="e.g. Healthcare, Fintech, Retail...">
                            <kbd class="hidden md:inline-flex absolute right-3 top-1/2 -translate-y-1/2 text-[10px] font-medium text-slate-400 border border-slate-200 rounded px-1.5 py-0.5">/</kbd>
                        </div>

                        @error('name')
                            <p class="text-xs text-rose-600 mt-1.5 flex items-center gap-1">
                                <i class="fa-solid fa-circle-exclamation"></i> {{ $message }}
                            </p>
                        @enderror
                    </fieldset>

                    {{-- Buttons --}}
                    <div class="flex items-center gap-2 shrink-0 w-full sm:w-auto">
                        <button type="submit" class="flex-1 sm:flex-none inline-flex items-center justify-center gap-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-4 py-2.5 shadow-sm transition cursor-pointer whitespace-nowrap">
                            <i class="fa-solid {{ isset($industry) ? 'fa-check' : 'fa-plus' }}"></i> {{ isset($industry) ? 'Update' : 'Add Industry' }}
                        </button>

                        @isset($industry)
                            <a href="{{ route('industries.index') }}" class="flex-1 sm:flex-none inline-flex items-center justify-center rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 text-sm font-medium px-4 py-2.5 transition whitespace-nowrap">Cancel</a>
                        @endisset
                    </div>
                </form>
            </div>
        </header>
    </section>

    {{-- List Card --}}
    <div class="bg-white border border-slate-200 rounded-lg shadow-sm">
        <div class="p-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-5">
                <h2 class="text-sm font-semibold text-slate-700">All Industries</h2>
                {{-- Database Live Search --}}
                <div class="relative w-full sm:w-72">
                    <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                    <input id="industrySearchInput" type="text" value="{{ request('search') }}" class="w-full rounded-xl border border-slate-300 pl-9 pr-10 py-2.5 text-sm text-slate-800 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition" placeholder="Search industry...">
                    {{-- Loading indicator --}}
                    <span id="industrySearchLoading" class="hidden absolute right-3.5 top-1/2 -translate-y-1/2 loading loading-spinner loading-xs text-indigo-500"></span>
                </div>
            </div>

            <div data-database-search data-input="industrySearchInput" data-container="industryTableContainer" data-loading="industrySearchLoading" data-url="{{ route('industries.index') }}"> {{-- Search input --}} </div>

            <div id="industryTableContainer">
                @include('general.partials.industry-table')
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
                <h3 class="font-bold text-lg text-slate-900">Delete Industry</h3>
                <p class="text-sm text-slate-500">This action cannot be undone.</p>
            </div>
        </div>

        <div class="mt-6 text-slate-600">
            Are you sure you want to delete <span id="deleteItemName" class="font-semibold text-rose-600"></span>?
        </div>

        <div class="modal-action">
            <form method="dialog">
                <button class="btn btn-ghost">Cancel</button>
            </form>

            <form id="deleteForm" method="POST">
                @csrf
                @method('DELETE')

                <button type="submit" class="btn bg-rose-600 hover:bg-rose-700 text-white border-none">
                    <i class="fa-solid fa-trash"></i> Delete
                </button>
            </form>
        </div>
    </div>

    <form method="dialog" class="modal-backdrop">
        <button>close</button>
    </form>
</dialog>
@endsection