@extends('layouts.app')

@section('title', 'Companies')

@section('content')

<div class="min-h-screen bg-slate-50">
    <div class="max-w-7xl mx-auto px-6 py-10">
        {{-- Header --}}
        <div class="flex flex-col gap-6 sm:flex-row sm:items-end sm:justify-between mb-8">
            <div>
                <div class="inline-flex items-center gap-2 text-xs font-medium text-indigo-600 bg-indigo-50 px-2.5 py-1 rounded-full mb-3">
                    <span class="w-1.5 h-1.5 rounded-full bg-indigo-500"></span>
                    {{ $companies->total() ?? $companies->count() }} tracked
                </div>
                <h1 class="text-3xl font-bold text-slate-900 tracking-tight"> Companies </h1>
                <p class="text-slate-500 mt-1"> Every company you're applying to, in one place. </p>
            </div>

            <a href="{{ route('companies.create') }}"
            class="btn btn-primary gap-2 shadow-sm shadow-indigo-200">
                <i class="fa-solid fa-plus text-xs"></i> Add company
            </a>
        </div>

        {{-- Success Message --}}
        @if(session('success'))
            <div class="alert bg-emerald-50 border border-emerald-200 text-emerald-700 mb-6">
                <i class="fa-solid fa-circle-check text-emerald-500"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        {{-- Toolbar --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-3 mb-4 flex flex-col sm:flex-row gap-3 sm:items-center">
            <label class="input input-bordered flex items-center gap-2 flex-1 bg-slate-50 border-slate-200 focus-within:border-indigo-400">
                <i class="fa-solid fa-magnifying-glass text-slate-400 text-sm"></i>
                <input id="companySearch" type="text" class="grow bg-transparent" placeholder="Search by name or industry..." autocomplete="off">
            </label>

            <div class="text-xs text-slate-400 px-1 whitespace-nowrap" id="resultCount"></div>
        </div>

        {{-- Table --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="overflow-x-auto">
                
                <table class="table" id="companiesTable">
                    <thead>
                        <tr class="border-b border-slate-200">
                            <th class="text-slate-400 font-medium text-xs uppercase tracking-wide">Company</th>
                            <th class="text-slate-400 font-medium text-xs uppercase tracking-wide">Industry</th>
                            <th class="text-slate-400 font-medium text-xs uppercase tracking-wide">Website</th>
                            <th class="text-slate-400 font-medium text-xs uppercase tracking-wide">Country</th>
                            <th class="text-right text-slate-400 font-medium text-xs uppercase tracking-wide">Actions</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($companies as $company)
                            @php
                                $palettes = [
                                    ['bg' => 'bg-indigo-100', 'text' => 'text-indigo-600'],
                                    ['bg' => 'bg-violet-100', 'text' => 'text-violet-600'],
                                    ['bg' => 'bg-sky-100',    'text' => 'text-sky-600'],
                                    ['bg' => 'bg-amber-100',  'text' => 'text-amber-600'],
                                    ['bg' => 'bg-rose-100',   'text' => 'text-rose-600'],
                                    ['bg' => 'bg-emerald-100','text' => 'text-emerald-600'],
                                ];
                                $palette = $palettes[crc32($company->name) % count($palettes)];
                                $initials = collect(explode(' ', $company->name))
                                    ->map(fn($w) => mb_substr($w, 0, 1))
                                    ->take(2)
                                    ->implode('');
                            @endphp

                            <tr class="company-row hover:bg-slate-50/80 transition-colors" data-name="{{ strtolower($company->name) }}" data-industry="{{ strtolower($company->industry ?? '') }}">

                                <td>
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-xl {{ $palette['bg'] }} {{ $palette['text'] }} flex items-center justify-center font-semibold text-sm shrink-0">
                                            {{ strtoupper($initials) }}
                                        </div>
                                        <div>
                                            <div class="font-semibold text-slate-800"> {{ $company->name }} </div>
                                            @if($company->hr_email)
                                                <div class="text-xs text-slate-400">
                                                    {{ $company->hr_email }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                <td>
                                    @if($company->industry)
                                        <span class="badge badge-ghost bg-slate-100 border-none text-slate-600 font-medium"> {{ $company->industry }} </span>
                                    @else
                                        <span class="text-slate-300">—</span>
                                    @endif
                                </td>

                                <td>
                                    @if($company->website)
                                        <a href="{{ $company->website }}" target="_blank" rel="noopener" class="inline-flex items-center gap-1.5 text-indigo-600 hover:text-indigo-700 font-medium text-sm">
                                            Visit <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                                        </a>
                                    @else
                                        <span class="text-slate-300">—</span>
                                    @endif
                                </td>

                                <td>
                                    <span class="text-slate-600"> {{ $company->country ?? '—' }} </span>
                                </td>

                                <td>
                                    <div class="flex justify-end gap-1.5">
                                        <a href="{{ route('companies.show',$company) }}" class="btn btn-sm btn-ghost text-slate-500 hover:bg-slate-100" title="View">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>

                                        @auth
                                            <a href="{{ route('companies.edit',$company) }}" class="btn btn-sm btn-ghost text-slate-500 hover:bg-slate-100" title="Edit">
                                                <i class="fa-solid fa-pen"></i>
                                            </a>

                                            <button type="button" class="btn btn-sm btn-ghost text-rose-500 hover:bg-rose-50 open-delete-modal" title="Delete" data-action="{{ route('companies.destroy', $company) }}" data-name="{{ $company->name }}">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        @endauth

                                    </div>
                                </td>
                            </tr>

                        @empty

                            <tr>
                                <td colspan="5" class="text-center py-16">
                                    <div class="flex flex-col items-center gap-3">
                                        <div class="w-14 h-14 rounded-2xl bg-slate-100 flex items-center justify-center">
                                            <i class="fa-solid fa-building text-slate-300 text-xl"></i>
                                        </div>
                                        <div>
                                            <p class="font-semibold text-slate-600">No companies yet</p>
                                            <p class="text-sm text-slate-400">Add the first one you're applying to.</p>
                                        </div>
                                        <a href="{{ route('companies.create') }}" class="btn btn-sm btn-primary mt-2">
                                            <i class="fa-solid fa-plus text-xs mr-1"></i> Add company
                                        </a>
                                    </div>
                                </td>
                            </tr>

                        @endforelse

                        <tr id="noResultsRow" class="hidden">
                            <td colspan="5" class="text-center py-16">
                                <div class="flex flex-col items-center gap-2">
                                    <i class="fa-solid fa-magnifying-glass text-slate-300 text-xl"></i>
                                    <p class="text-sm text-slate-400">No companies match your search.</p>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Pagination --}}
        @if(method_exists($companies, 'links'))
            <div class="mt-6">
                {{ $companies->links() }}
            </div>
        @endif
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
                <h3 class="font-bold text-lg text-slate-900">Delete company</h3>
                <p class="text-sm text-slate-500">This action cannot be undone.</p>
            </div>
        </div>

        <div class="mt-6 text-slate-600">
            Are you sure you want to delete
            <span id="deleteCompanyName" class="font-semibold text-rose-600"></span>?
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