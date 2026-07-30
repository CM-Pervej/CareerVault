@extends('layouts.app')

@section('title', 'Companies | CareerVault')

@section('content')
<div>
    <div class="mb-8">
        {{-- Breadcrumb --}}
        <div class="breadcrumbs text-sm mb-2">
            <ul>
                <li> <a href="{{ route('dashboard') }}"> <i class="fa-solid fa-house mr-1"></i> Dashboard </a> </li> <li class="font-semibold"> Companies </li>
            </ul>
        </div>

        <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4">
            <div>
                <h1 class="cv-title text-3xl">Companies</h1>
                <p class="text-base-content/60 text-sm">
                    {{ $companies->total() ?? $companies->count() }} companies on record
                </p>
            </div>
            
            <div class="relative w-full">
                <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                <input type="text" id="companySearch" class="w-full rounded-xl border border-slate-300 pl-9 pr-4 py-2.5 text-sm text-slate-800 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition" placeholder="Search companies...">
            </div>

            @auth
            <a href="{{ route('companies.create') }}" class="btn btn-primary">
                <i class="fa-solid fa-plus"></i>
                Add Company
            </a>
            @endauth
        </div>
    </div>

    <div class="card bg-base-100 shadow border border-base-300 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="table">
                <thead>
                    <tr class="cv-eyebrow border-b border-base-300">
                        <th class="w-10">#</th> <th>Company</th> <th>Career</th> <th>Location</th> <th>Industry</th> <th>Updated</th> <th class="text-right">Actions</th>
                    </tr>
                </thead>

                <tbody id="companyContainer">
                    @forelse($companies as $i => $company)
                    <tr class="cv-row company-card border-b border-base-300 last:border-0" data-search="{{ strtolower($company->name.' '.$company->countries->pluck('name')->implode(' ').' '.$company->industries->pluck('name')->implode(' ')) }}">
                        <td class="cv-mono text-xs opacity-40">
                            {{ str_pad(($companies->firstItem() ?? 1) + $i, 2, '0', STR_PAD_LEFT) }}
                        </td>
                        
                        <td>
                            <div class="flex items-center gap-3">
                                <div class="cv-avatar bg-primary text-primary-content">
                                    {{ strtoupper(substr($company->name,0,1)) }}
                                </div>
                                <div>
                                    <a href="{{ route('companies.show', $company) }}" class="cv-name-link">
                                        {{ $company->name }}
                                    </a>

                                    @if($company->website)
                                    <a href="{{ $company->website }}" target="_blank" class="ml-2 text-xs opacity-40 hover:opacity-100">
                                        <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                    </a>
                                    @endif
                                </div>
                            </div>
                        </td>

                        <td>
                            @if($company->career_page)
                            <a href="{{ $company->career_page }}" target="_blank" class="btn btn-sm btn-outline">
                                <i class="fa-solid fa-globe"></i>
                            </a>
                            @endif
                        </td>

                        <td>
                            @if($company->countries->count())
                            <div class="flex flex-wrap gap-1">
                                <span class="badge badge-outline badge-sm cv-tag">
                                    {{ $company->countries->first()->name }}
                                </span>

                                @if($company->countries->count() > 1)
                                <span class="badge badge-ghost badge-sm cv-tag">
                                    +{{ $company->countries->count() - 1 }}
                                </span>
                                @endif
                            </div>

                            @else
                            <span class="text-xs opacity-30">—</span>
                            @endif
                        </td>

                        <td>
                            @if($company->industries->count())
                            <div class="flex flex-wrap gap-1">
                                <span class="badge badge-outline badge-sm cv-tag">{{ $company->industries->first()->name }}</span>
                                @if($company->industries->count() > 1)
                                    <span class="badge badge-ghost badge-sm cv-tag">+{{ $company->industries->count() - 1 }}</span>
                                @endif
                            </div>

                            @else
                            <span class="text-xs opacity-30">—</span>
                            @endif
                        </td>

                        <td class="cv-mono text-xs opacity-60 whitespace-nowrap">
                            {{ $company->updated_at->diffForHumans() }}
                        </td>

                        <td>
                            <div class="flex justify-end gap-1">
                                <a href="{{ route('companies.show', $company) }}" class="btn btn-ghost btn-xs btn-square">
                                    <i class="fa-solid fa-eye"></i>
                                </a>

                                @auth
                                <a href="{{ route('companies.edit', $company) }}" class="btn btn-ghost btn-xs btn-square">
                                    <i class="fa-solid fa-pen"></i>
                                </a>

                                <button class="btn btn-ghost btn-xs btn-square text-error delete-company" data-url="{{ route('companies.destroy', $company) }}" data-name="{{ $company->name }}">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                                @endauth
                            </div>
                        </td>
                    </tr>

                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-14">
                            <i class="fa-solid fa-box-archive text-2xl opacity-30 mb-3 block"></i>
                            <p class="cv-title text-lg">No companies found</p>
                            <p class="text-sm opacity-50">Add a company to start building your record.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-8">
        {{ $companies->links() }}
    </div>
</div>

<dialog id="deleteModal" class="modal">
    <div class="modal-box">
        <h3 class="font-bold text-lg">Delete Company</h3>
        <p class="py-4">Are you sure you want to delete <span id="deleteCompanyName" class="font-bold"></span>?</p>
        <div class="modal-action">
            <form method="dialog">
                <button class="btn">Cancel</button>
            </form>
            <form id="deleteForm" method="POST">
                @csrf 
                @method('DELETE')
                <button type="submit" class="btn btn-error">Delete</button></form>
        </div>
    </div>
</dialog>
@endsection