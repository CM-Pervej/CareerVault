@extends('layouts.app')

@section('title', 'Industries | CareerVault')

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
                        <span>General</span> /
                    </p>
                    <div class="flex items-center gap-2">
                        <h1 class="text-2xl sm:text-3xl font-bold text-white">Industries</h1>
                        <sup id="industryTotal" class="rounded-full bg-white/20 text-white text-xs font-bold px-2 py-1 leading-none border border-white/30"> {{ $industries->total() }} </sup>
                    </div>
                    <p class="text-indigo-100/80 text-sm max-w-2xl mt-1">Organize companies by industry and keep your industry directory structured and easy to manage</p>

                    {{-- Quick stats. id="industryPagination" already carries data-total / data-per-page below,
                         and industryStatShowing / industryStatPage are kept in sync with pagination via
                         a small observer script at the bottom of this file (see notes there). --}}
                    <div class="flex flex-wrap items-center gap-2 mt-5">
                        <div class="flex items-center gap-2 rounded-lg bg-white/10 border border-white/15 px-3 py-2">
                            <i class="fa-solid fa-building text-indigo-200 text-xs"></i>
                            <span class="text-white text-sm font-semibold">{{ $totalIndustry }}</span>
                            <span class="text-indigo-200 text-xs">total</span>
                        </div>
                        <div class="flex items-center gap-2 rounded-lg bg-white/10 border border-white/15 px-3 py-2">
                            <i class="fa-solid fa-list-ol text-indigo-200 text-xs"></i>
                            <span id="industryStatShowing" class="text-white text-sm font-semibold">{{ $industries->total() ? "{$industries->firstItem()}–{$industries->lastItem()}" : '0' }}</span>
                            <span class="text-indigo-200 text-xs">showing</span>
                        </div>
                        <div class="flex items-center gap-2 rounded-lg bg-white/10 border border-white/15 px-3 py-2">
                            <i class="fa-solid fa-layer-group text-indigo-200 text-xs"></i>
                            <span id="industryStatPage" class="text-white text-sm font-semibold">{{ $industries->currentPage() }}/{{ max($industries->lastPage(), 1) }}</span>
                            <span class="text-indigo-200 text-xs">page</span>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row sm:items-center gap-3 w-full lg:w-auto">
                    {{-- Search (ids / data-url / value unchanged for existing JS) --}}
                    <div>
                        @include('modular.database_filter', [
                            'id' => 'industrySearch',
                            'url' => route('industries.index'),
                            'placeholder' => 'Search industry...',
                            'loadingId' => 'industrySearchLoading',
                            'shortcutId' => 'industrySearchShortcut',
                        ])
                    </div>

                    @auth
                        {{-- Add Industry --}}
                        <button onclick="industryModal.showModal()" class="inline-flex items-center justify-center gap-2 rounded-sm bg-white hover:bg-indigo-50 text-indigo-700 text-sm font-medium px-4 py-2.5 shadow-sm transition shrink-0 cursor-pointer">
                            <i class="fa-solid fa-plus"></i> Add Industry
                        </button>
                    @endauth
                </div>
            </div>
        </div>
    </section>
        
    {{-- Table --}}
    <div class="card bg-transparent sm:bg-base-100 sm:shadow sm:border sm:border-base-300 overflow-hidden p-2 sm:p-0">
        <div class="p-2">
            <div class="overflow-x-auto">
                <table class="table w-full !block md:!table">
                    <thead class="hidden md:table-header-group">
                        <tr class="cv-eyebrow border-b border-base-300">
                            <th class="w-10">#</th>
                            <th>Industry</th>
                            <th>Slug</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
    
                    <tbody id="industryContainer" class="block md:table-row-group">
                        @forelse($industries as $i => $item)
                            <tr class="crud-row industry-card block md:table-row rounded-xl md:rounded-none border md:border-0 md:border-b border-slate-200 border-b-base-300 last:border-0 mb-3 md:mb-0 p-3 md:p-0 bg-white md:bg-transparent shadow-sm md:shadow-none hover:bg-slate-50">
                                {{-- Number --}}
                                <td class="hidden md:table-cell cv-mono text-xs opacity-40 py-2.5 text-center">
                                    {{ str_pad($loop->iteration + (($industries->currentPage() - 1) * $industries->perPage()), 2, '0', STR_PAD_LEFT) }}
                                </td>
    
                                {{-- Industry --}}
                                <td class="block md:table-cell px-0 md:px-2 py-1 md:py-2.5">
                                    <div class="flex items-center gap-3">
                                        <div class="cv-avatar bg-primary text-primary-content"> {{ strtoupper(substr($item->name, 0, 1)) }} </div>
                                        <span class="font-medium text-slate-800"> {{ $item->name }} </span>
                                    </div>
                                </td>
    
                                {{-- Slug --}}
                                <td class="px-4 py-3">
                                    <span class="inline-flex items-center rounded-md bg-slate-100 text-slate-500 text-xs font-mono px-2 py-1"> {{ $item->slug }} </span>
                                </td>
    
                                {{-- Actions --}}
                                <td class="px-4 py-3">
                                    <div class="flex gap-2 justify-center">
                                        {{-- Edit --}}
                                        <a href="{{ route('industries.edit', $item) }}" class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-100 text-xs font-medium px-3 py-1.5 transition hover:text-indigo-600">
                                            <i class="fa-solid fa-pen"></i> Edit
                                        </a>
    
                                        {{-- Delete --}}
                                        <button type="button" class="delete-item inline-flex items-center gap-1.5 rounded-lg border border-rose-100 bg-rose-50 text-rose-600 hover:bg-rose-100 text-xs font-medium px-3 py-1.5 transition cursor-pointer" 
                                            data-url="{{ route('industries.destroy', $item) }}" 
                                            data-name="{{ $item->name }}"
                                            data-title="Delete Industry">
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
                                    <p class="cv-title text-lg">No industries found</p>
                                    <p class="text-sm opacity-50 mb-4">
                                        @if(request('search'))
                                            No industries match "{{ request('search') }}".
                                        @else
                                            Add a industries to start building your record.
                                        @endif
                                    </p>
    
                                    @if(request('search'))
                                        <a href="{{ route('industries.index') }}" class="btn btn-sm btn-outline">
                                            <i class="fa-solid fa-rotate-left"></i> Clear search
                                        </a>
                                    @elseif(auth()->check())
                                        <a href="{{ route('industries.create') }}" class="btn btn-sm btn-primary">
                                            <i class="fa-solid fa-plus"></i> Add Industry
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
    <div id="industryPagination" class="mt-8 pb-20 md:pb-0" data-total="{{ $industries->total() }}" data-per-page="{{ $industries->perPage() }}">
        {{ $industries->links() }}
    </div>
</div>

{{-- Mobile Floating Add Industry Button --}}
<button type="button" onclick="industryModal.showModal()" class="fixed bottom-5 right-5 z-40 inline-flex items-center justify-center w-14 h-14 rounded-full bg-indigo-600 text-white shadow-lg shadow-indigo-900/30 hover:bg-indigo-700 cursor-pointer" aria-label="Add Industry">
    <i class="fa-solid fa-plus text-lg"></i>
</button>

{{-- Delete Confirmation Modal --}}
<div>
    @include('modular.delete_modal')
</div>

{{-- Add / Edit Industry Modal --}}
<dialog id="industryModal" class="modal">
    <div class="modal-box max-w-3xl rounded-2xl">
        {{-- Modal Header --}}
        <h3 class="font-bold text-xl mb-5 text-slate-900">
            <i class="fa-solid fa-layer-group text-indigo-600 mr-2"></i>
            {{ isset($industry) ? 'Edit Industry' : 'Add Industry' }}
        </h3>

        {{-- Form --}}
        <form action="{{ isset($industry) ? route('industries.update', $industry) : route('industries.store') }}" method="POST">
            @csrf

            @if(isset($industry))
                @method('PUT')
            @endif

            {{-- Industry Name --}}
            <div>
                <label class="text-sm font-medium text-slate-700">Industry Name</label>

                <div class="relative mt-1.5">
                    <i class="fa-solid fa-layer-group absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm pointer-events-none"></i>

                    <input
                        type="text"
                        name="name"
                        value="{{ old('name', $industry->name ?? '') }}"
                        placeholder="e.g. Healthcare, Fintech, Retail..."
                        class="w-full rounded-xl border border-slate-300 bg-slate-50/60 pl-9 pr-10 py-2.5 text-sm text-slate-800 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-500 focus:bg-white transition @error('name') border-rose-400 focus:ring-rose-400 @enderror"
                        autocomplete="off">

                    <kbd class="hidden md:inline-flex absolute right-3 top-1/2 -translate-y-1/2 text-[10px] font-medium text-slate-400 border border-slate-200 rounded px-1.5 py-0.5">/</kbd>
                </div>

                {{-- Validation Error --}}
                @error('name')
                    <p class="mt-1.5 text-xs text-rose-500"> {{ $message }} </p>
                @enderror
            </div>

            {{-- Actions --}}
            <div class="modal-action">
                <button type="button" onclick="industryModal.close()" class="btn btn-ghost">Cancel</button>

                <button type="submit" class="btn bg-indigo-600 hover:bg-indigo-700 text-white border-none">
                    <i class="fa-solid fa-save"></i>
                    {{ isset($industry) ? 'Update Industry' : 'Save Industry' }}
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
@if(isset($industry))
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            industryModal.showModal();
        });
    </script>
@endif

@endsection