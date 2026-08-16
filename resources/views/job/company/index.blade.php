@extends('layouts.app')

@section('title', 'Companies | CareerVault')

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

                <li class="font-semibold">Companies</li>
            </ul>
        </div>

        {{-- Header --}}
        <header class="flex flex-col md:flex-row md:items-end md:justify-between gap-4">
            <div>
                <p class="cv-eyebrow text-xs font-semibold tracking-widest uppercase text-indigo-500/70 mb-1">Directory</p>
                <div class="flex items-center gap-2">
                    <h1 class="cv-title text-3xl font-bold text-slate-900">Companies</h1>
                    <sup id="companyTotal" class="rounded-full bg-indigo-500 text-white text-xs font-bold px-2 py-1 leading-none"> {{ $companies->total() }} </sup>
                </div>
                <p class="text-base-content/60 text-sm max-w-2xl">Explore companies, discover their industries and locations, and keep your company directory organized</p>
            </div>

            <div class="flex flex-col md:flex-row md:items-center gap-3 w-full md:w-auto">
                {{-- Search --}}
                <div class="relative w-full md:w-72">
                    <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm pointer-events-none"></i>
                    <input type="text" id="companySearch" data-url="{{ route('companies.index') }}" value="{{ request('search', '') }}" autocomplete="off" class="w-full rounded-xl border border-slate-300 bg-slate-50/60 pl-9 pr-9 py-2.5 text-sm text-slate-800 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-500 focus:bg-white transition" placeholder="Search name, city, country..." aria-label="Search companies">
                    {{-- Loading indicator --}}
                    <span id="companySearchLoading" class="hidden absolute right-3 top-1/2 -translate-y-1/2">
                        <i class="fa-solid fa-spinner fa-spin text-indigo-500 text-sm"></i>
                    </span>

                    <kbd id="companySearchShortcut" class="hidden md:inline-flex absolute right-3 top-1/2 -translate-y-1/2 text-[10px] font-medium text-slate-400 border border-slate-200 rounded px-1.5 py-0.5">/</kbd>
                </div>

                @auth
                    <a href="{{ route('companies.create') }}" class="btn btn-primary shrink-0">
                        <i class="fa-solid fa-plus"></i> Add Company
                    </a>
                @endauth
            </div>
        </header>
    </section>

    {{-- Companies Table --}}
    <div class="card bg-base-100 shadow border border-base-300 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="table">
                <thead>
                    <tr class="cv-eyebrow border-b border-base-300">
                        <th class="w-10 py-3 text-center">#</th>  <th class="px-2 py-3">Company</th>  <th class="text-center px-2 py-3">Career</th>
                        <th class="px-2 py-3">City</th>  <th class="px-2 py-3">Country</th>  <th class="px-2 py-3">Industry</th>

                        @auth
                            <th class="text-right pl-2 pr-4 py-3">Actions</th>
                        @endauth
                    </tr>
                </thead>

                <tbody id="companyContainer">
                    @forelse($companies as $i => $company)
                        <tr class="cv-row company-card border-b border-base-300 last:border-0">
                            {{-- Number --}}
                            <td class="cv-mono text-xs opacity-40 py-2.5 text-center"> {{ str_pad(($companies->firstItem() ?? 1) + $i, 2, '0', STR_PAD_LEFT) }} </td>

                            {{-- Company --}}
                            <td class="px-2 py-2.5">
                                <div class="flex items-center gap-2 min-w-0">
                                    <div class="cv-avatar bg-primary text-primary-content shrink-0"> {{ strtoupper(substr($company->name, 0, 1)) }} </div>
                                    <div class="flex items-center min-w-0">
                                        <a href="{{ route('companies.show', $company) }}" class="cv-name-link block max-w-[180px] truncate" title="{{ $company->name }}">
                                            {{ $company->name }}
                                        </a>

                                        @if($company->website)
                                            <a href="{{ $company->website }}" target="_blank" rel="noopener noreferrer" class="ml-2 shrink-0 text-xs opacity-40 hover:opacity-100" title="Visit website">
                                                <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            {{-- Career --}}
                            <td class="text-center px-2 py-2.5">
                                @if($company->career_page)
                                    <a href="{{ $company->career_page }}" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-outline" title="Visit career page">
                                        <i class="fa-solid fa-globe"></i>
                                    </a>
                                @else
                                    <span class="text-xs opacity-30">—</span>
                                @endif
                            </td>

                            {{-- City --}}
                            <td class="px-2 py-2.5">
                                @if($company->cities->count())
                                    <div class="flex flex-wrap gap-1">
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
                            <td class="px-2 py-2.5">
                                @if($company->countries->count())
                                    <div class="flex flex-wrap gap-1">
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
                            <td class="px-2 py-2.5">
                                @if($company->industries->count())
                                    <div class="flex flex-wrap gap-1">
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
                                <td class="text-right pl-2 pr-4 py-2.5">
                                    <div class="flex justify-end gap-1">
                                        <a href="{{ route('companies.show', $company) }}" class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-100 text-xs font-medium px-3 py-1.5 transition hover:text-indigo-600">
                                            <i class="fa-solid fa-eye"></i> Visit
                                        </a>

                                        <a href="{{ route('companies.edit', $company) }}" class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-100 text-xs font-medium px-3 py-1.5 transition hover:text-indigo-600">
                                            <i class="fa-solid fa-pen"></i> Edit
                                        </a>

                                        <button type="button" class="delete-company inline-flex items-center gap-1.5 rounded-lg border border-rose-100 bg-rose-50 text-rose-600 hover:bg-rose-100 text-xs font-medium px-3 py-1.5 transition" data-url="{{ route('companies.destroy', $company) }}" data-name="{{ $company->name }}">
                                            <i class="fa-solid fa-trash"></i> Delete
                                        </button>
                                    </div>
                                </td>
                            @endauth
                        </tr>

                    @empty

                        <tr>
                            <td colspan="{{ auth()->check() ? 7 : 6 }}" class="text-center py-14">
                                <i class="fa-solid fa-box-archive text-2xl opacity-30 mb-3 block"></i>
                                <p class="cv-title text-lg">No companies found</p>
                                <p class="text-sm opacity-50">
                                    @if(request('search'))
                                        No companies match "{{ request('search') }}".
                                    @else
                                        Add a company to start building your record.
                                    @endif
                                </p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Pagination --}}
    <div id="companyPagination" class="mt-8"> {{ $companies->links() }} </div>
</div>

{{-- Delete Modal --}}
<dialog id="deleteModal" class="modal">
    <div class="modal-box">
        <h3 class="font-bold text-lg">Delete Company</h3>
        <p class="py-4">
            Are you sure you want to delete  <span id="deleteCompanyName" class="font-bold"></span>?
        </p>

        <div class="modal-action">
            <form method="dialog">
                <button class="btn">Cancel</button>
            </form>
            <form id="deleteForm" method="POST">
                @csrf
                @method('DELETE')

                <button type="submit" class="btn btn-error">Delete</button>
            </form>
        </div>
    </div>
</dialog>
@endsection