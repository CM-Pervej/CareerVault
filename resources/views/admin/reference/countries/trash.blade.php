@extends('layouts.admin.app')

@section('title','Country Trash')
@section('page_title', 'Country / Trash')

@section('content')
<div class="space-y-3 p-4 sm:p-6">
    {{-- Header --}}
    <header class="relative overflow-hidden sm:mb-6">
        <div class="relative flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
            <div class="flex items-start gap-4">                
                <div>
                    <div class="flex gap-3 size-11 shrink-0 items-center rounded-lg bg-error/10">
                        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-error/10 text-error">
                            <i class="fa-solid fa-trash-can"></i>
                        </div>
                        <h1 class="cv-admin-title text-3xl sm:text-4xl whitespace-nowrap text-error">Country Trash</h1>
                    </div>

                    <p class="mt-2 max-w-2xl text-sm leading-6 text-base-content/65 text-center sm:text-left">Deleted countries remain safely stored here until they are restored or permanently removed. Only authorized administrators can manage deleted records.</p>

                    <div class="mt-4 flex flex-wrap justify-center sm:justify-start gap-2">
                        <span class="badge badge-error badge-outline gap-1">
                            <i class="fa-solid fa-trash text-[10px]"></i> Soft Deleted
                        </span>

                        <span class="badge badge-warning badge-outline gap-1">
                            <i class="fa-solid fa-lock text-[10px]"></i> Super Admin Access
                        </span>
                    </div>
                </div>
            </div>

            <a href="{{ route('admin.countries.index') }}" class="btn btn-outline gap-2">
                <i class="fa-solid fa-arrow-left"></i> Back to Countries
            </a>
        </div>
    </header>

    <div class="card border border-base-300 bg-base-100 shadow-sm">
        <div class="card-body p-4">
            <form method="GET" action="{{ route('admin.countries.trash') }}" class="flex gap-2">
                <label class="input input-bordered input-sm flex flex-1 items-center gap-2">
                    <i class="fa-solid fa-magnifying-glass text-base-content/50"></i>
                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Search deleted countries..."
                        class="grow"
                    >
                </label>

                <button type="submit" class="btn btn-neutral btn-sm">
                    Search
                </button>

                @if(request()->filled('search'))
                    <a href="{{ route('admin.countries.trash') }}" class="btn btn-ghost btn-sm">
                        Clear
                    </a>
                @endif
            </form>
        </div>
    </div>

        {{-- Trash List --}}
    <section class="rounded-lg sm:border sm:border-gray-200 sm:shadow-sm mt-10 sm:mt-5">
        {{-- Toolbar --}}
        <div class="px-5 py-4">
            <div class="flex justify-between items-center">
                <div class="flex items-center gap-2 font-black">
                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-error/10 text-error">
                        <i class="fa-solid fa-trash-can"></i>
                    </div>
                    Deleted Countries
                </div>
                <span class="badge badge-error badge-outline gap-1">
                    <i class="fa-solid fa-box-archive text-[10px]"></i> Trash
                </span>
            </div>

            <p class="mt-3 text-center sm:text-start text-xs text-base-content/50">
                {{ number_format($countries->count()) }} country(s) shown
                •
                {{ number_format($countries->total()) }} total in current result
            </p>
        </div>

        @if($countries->count())
            {{-- ========================= Desktop Table ====================== --}}
            <div class="hidden overflow-x-auto md:block bg-base-100">
                <table class="table w-full table-zebra table-compact">
                    <thead>
                        <tr class="bg-base-200/50 text-xs uppercase tracking-wider text-base-content/50">
                            <th>Country</th> <th>ISO</th> <th>Region</th> <th>Deleted At</th> <th>Deleted By</th> <th class="text-right">Actions</th>
                        </tr>
                    </thead>
    
                    <tbody>
                        @foreach($countries as $country)
                            <tr class="hover:bg-base-200/40">
                                <td>
                                    <div class="flex items-center gap-3">
                                        <div class="relative w-14 h-14 shrink-0">
                                            <svg class="absolute inset-0 w-full h-full -rotate-90" viewBox="0 0 100 100">
                                                <circle cx="50" cy="50" r="46" fill="none" stroke="currentColor" stroke-width="5" class="text-base-300"/>
                                                <circle cx="50" cy="50" r="46" fill="none" stroke="currentColor" stroke-width="5" stroke-linecap="round" class="text-primary transition-all duration-700" stroke-dasharray="289" stroke-dashoffset="1"/>
                                            </svg>
    
                                            <div class="overflow-hidden shrink-0 absolute inset-2 rounded-full text-primary-content flex items-center justify-center text-2xl sm:text-3xl md:text-4xl font-bold ring-4 ring-base-100">
                                                <img src="https://flagcdn.com/w80/{{ strtolower($country->iso2) }}.png"
                                                alt="{{ $country->name }} flag" class="w-full h-full object-cover">
                                            </div>
    
                                            @if($country->is_active)
                                                <div class="tooltip absolute -bottom-1 -right-1">
                                                    <div class="w-6 h-6 rounded-full bg-success text-success-content flex items-center justify-center text-[10px] font-bold shadow">
                                                        <i class="fa-solid fa-check"></i>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="tooltip absolute -bottom-1 -right-1">
                                                    <div class="w-6 h-6 rounded-full bg-error text-error-content flex items-center justify-center text-[10px] font-bold shadow">
                                                        <i class="fa-solid fa-xmark"></i>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
    
                                        <div class="min-w-0">
                                            <p class="font-medium text-slate-800 truncate">{{ $country->name }}</p>
                                            <p class="text-xs text-slate-400 truncate">{{ $country->slug }}</p>
                                        </div>
                                    </div>
                                </td>
    
                                <td>
                                    <div class="flex gap-1">
                                        <span class="badge badge-outline badge-sm">{{ $country->iso2 }}</span>
                                        <span class="badge badge-outline badge-sm">{{ $country->iso3 }}</span>
                                    </div>
                                </td>
    
                                <td>
                                    <div>{{ $country->region ?: '—' }}</div>
                                    @if($country->subregion)
                                        <div class="text-xs text-base-content/50 whitespace-nowrap">{{ $country->subregion }}</div>
                                    @endif
                                </td>
    
                                {{-- Deleted --}}
                                <td>
                                    <div class="flex items-start gap-2">
                                        <div class="mt-1 flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-error/10 text-error">
                                            <i class="fa-solid fa-clock text-xs"></i>
                                        </div>
    
                                        <div class="whitespace-nowrap">
                                            <div class="text-sm font-bold">
                                                {{ $country->deleted_at?->format('d M Y, h:i A') }}
                                            </div>
    
                                            <div class="text-xs text-base-content/50">
                                                {{ $country->deleted_at?->diffForHumans() }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
    
                                {{-- Deleted By --}}
                                <td class="py-4">
                                    @if($country->deletedBy)
                                        <div class="flex items-center gap-2.5">
                                            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-primary/10 text-primary">
                                                <i class="fa-solid fa-user-shield text-xs"></i>
                                            </div>
    
                                            <div class="min-w-0">
                                                <div class="max-w-[150px] truncate whitespace-nowrap text-sm font-bold" title="{{ $country->deletedBy->name }}">
                                                    {{ $country->deletedBy->name }}
                                                </div>
    
                                                <div class="mt-0.5 whitespace-nowrap text-[10px] font-semibold uppercase tracking-wider text-base-content/40">
                                                    {{ str_replace('_', ' ', $country->deletedBy->role) }}
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <div class="flex items-center gap-2 text-xs text-base-content/35">
                                            <i class="fa-solid fa-user-slash"></i>
                                            <span>Unknown user</span>
                                        </div>
                                    @endif
                                </td>
    
                                {{-- Actions --}}
                                <td>
                                    <div class="flex justify-end gap-2">
                                        {{-- @can('restore', $page) --}}
                                            <button
                                                type="button"
                                                class="btn btn-success btn-sm btn-outline gap-2"
                                                title="Restore page"
                                                data-admin-action
                                                data-action-url="{{ route('admin.countries.restore', $country->slug) }}"
                                                data-action-method="PATCH"
                                                data-action-type="success"
                                                data-action-title="Restore Country"
                                                data-action-description="Restore “{{ $country->name }}” and return it to the active countries."
                                                data-action-icon="fa-solid fa-rotate-left"
                                                data-action-confirm-icon="fa-solid fa-rotate-left"
                                                data-action-confirm-text="Restore Page"
                                            >
                                                <i class="fa-solid fa-rotate-left"></i>
    
                                                <span class="hidden lg:inline">Restore</span>
                                            </button>
                                        {{-- @endcan --}}
    
                                        {{-- @can('forceDelete', $page) --}}
                                            <button
                                                type="button"
                                                class="btn btn-error btn-sm btn-outline gap-2"
                                                title="Delete permanently"
                                                data-admin-action
                                                data-action-url="{{ route('admin.countries.force-delete', $country->slug) }}"
                                                data-action-method="DELETE"
                                                data-action-type="danger"
                                                data-action-title="Delete Country Permanently"
                                                data-action-description="Permanently delete “{{ $country->name }}”. This action cannot be undone."
                                                data-action-icon="fa-solid fa-triangle-exclamation"
                                                data-action-confirm-icon="fa-solid fa-trash-can"
                                                data-action-confirm-text="Delete Forever"
                                            >
                                                <i class="fa-solid fa-trash-can"></i>
    
                                                <span class="hidden lg:inline whitespace-nowrap">Delete Forever</span>
                                            </button>
                                        {{-- @endcan --}}
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            {{-- ========================= Mobile Table ====================== --}}
            <div class="divide-y divide-base-200 md:hidden">
                @foreach ($countries as $country)
                    <div class="p-4 transition bg-base-100 border border-gray-300 shadow-sm mt-3">
                        {{-- Card Header --}}
                        <div class="flex items-start gap-3">
                            <div class="relative w-14 h-14 shrink-0">
                                <svg class="absolute inset-0 w-full h-full -rotate-90" viewBox="0 0 100 100">
                                    <circle cx="50" cy="50" r="46" fill="none" stroke="currentColor" stroke-width="5" class="text-base-300"/>
                                    <circle cx="50" cy="50" r="46" fill="none" stroke="currentColor" stroke-width="5" stroke-linecap="round" class="text-primary transition-all duration-700" stroke-dasharray="289" stroke-dashoffset="1"/>
                                </svg>

                                <div class="overflow-hidden shrink-0 absolute inset-2 rounded-full text-primary-content flex items-center justify-center text-2xl sm:text-3xl md:text-4xl font-bold ring-4 ring-base-100">
                                    <img src="https://flagcdn.com/w80/{{ strtolower($country->iso2) }}.png"
                                    alt="{{ $country->name }} flag" class="w-full h-full object-cover">
                                </div>

                                @if($country->is_active)
                                    <div class="tooltip absolute -bottom-1 -right-1">
                                        <div class="w-6 h-6 rounded-full bg-success text-success-content flex items-center justify-center text-[10px] font-bold shadow">
                                            <i class="fa-solid fa-check"></i>
                                        </div>
                                    </div>
                                @else
                                    <div class="tooltip absolute -bottom-1 -right-1">
                                        <div class="w-6 h-6 rounded-full bg-error text-error-content flex items-center justify-center text-[10px] font-bold shadow">
                                            <i class="fa-solid fa-xmark"></i>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <div class="min-w-0">
                                <p class="font-medium text-slate-800 truncate">{{ $country->name }}</p>
                                <p class="text-xs text-slate-400 truncate">{{ $country->iso2 }} / {{ $country->iso3 }}</p>
                            </div>
                        </div>

                        {{-- Metadata --}}
                        <div class="grid grid-cols-1 gap-2 my-2">
                            {{-- Platform --}}
                            <div class="flex items-center gap-3">
                                {{ $country->region }}

                                <div class="h-px flex-1 bg-gray-300"></div>

                                {{ $country->subregion }}
                            </div>

                            {{-- Deleted --}}
                            <div class="flex flex-row-reverse items-center gap-3">
                                <div class="flex items-center gap-1.5 text-[10px] font-bold uppercase tracking-wider text-base-content/40">
                                    <i class="fa-solid fa-clock"></i> Deleted At
                                </div>

                                <div class="h-px flex-1 bg-gray-300"></div>

                                <div>
                                    <div class="text-sm font-bold">
                                        {{ $country->deleted_at?->format('d M, Y') }}
                                    </div>
    
                                    <div class="mt-0.5 text-[10px] text-base-content/50">
                                        {{ $country->deleted_at?->diffForHumans() }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Deleted By --}}
                        <div class="flex flex-row-reverse items-center gap-3">
                            <div class="flex min-w-0 items-center gap-2.5">
                                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-primary/10 text-primary">
                                    <i class="fa-solid fa-user-shield text-xs"></i>
                                </div>

                                @if($country->deletedBy)
                                    <div class="min-w-0">
                                        <div class="truncate text-xs font-bold" title="{{ $country->deletedBy->name }}">
                                            {{ $country->deletedBy->name }}
                                        </div>

                                        <div class="mt-0.5 text-[9px] font-semibold uppercase tracking-wider text-base-content/40">
                                            {{ str_replace('_', ' ', $country->deletedBy->role) }}
                                        </div>
                                    </div>
                                @else
                                    <div class="text-xs text-base-content/40">Unknown user</div>
                                @endif
                            </div>

                            <div class="h-px flex-1 bg-gray-300"></div>

                            <span class="text-[10px] font-semibold uppercase tracking-wider text-base-content/35">Deleted By</span>
                        </div>

                        {{-- Actions --}}
                        <div class="mt-4 grid grid-cols-2 gap-2">
                            {{-- @can('restore', $page) --}}
                                <button
                                    type="button"
                                    class="btn btn-success btn-sm btn-outline gap-2"
                                    title="Restore country"
                                    data-admin-action
                                    data-action-url="{{ route('admin.countries.restore', $country->slug) }}"
                                    data-action-method="PATCH"
                                    data-action-type="success"
                                    data-action-title="Restore Country"
                                    data-action-description="Restore “{{ $country->name }}” and return it to the active countries."
                                    data-action-icon="fa-solid fa-rotate-left"
                                    data-action-confirm-icon="fa-solid fa-rotate-left"
                                    data-action-confirm-text="Restore Country"
                                >
                                    <i class="fa-solid fa-rotate-left"></i> Restore
                                </button>
                            {{-- @endcan --}}

                            {{-- @can('forceDelete', $page) --}}
                                <button
                                    type="button"
                                    class="btn btn-error btn-sm btn-outline gap-2"
                                    title="Delete permanently"
                                    data-admin-action
                                    data-action-url="{{ route('admin.countries.force-delete', $country->slug) }}"
                                    data-action-method="DELETE"
                                    data-action-type="danger"
                                    data-action-title="Delete Country Permanently"
                                    data-action-description="Permanently delete “{{ $country->name }}”. This action cannot be undone."
                                    data-action-icon="fa-solid fa-triangle-exclamation"
                                    data-action-confirm-icon="fa-solid fa-trash-can"
                                    data-action-confirm-text="Delete Forever"
                                >
                                    <i class="fa-solid fa-trash-can"></i> Delete Forever
                                </button>
                            {{-- @endcan --}}
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            @if($countries->hasPages())
                <div class="border-t border-base-300 p-4">
                    {{ $countries->links() }}
                </div>
            @endif
        @else
            {{-- Empty State --}}
            <div class="px-8 py-20 text-center">
                <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-3xl bg-base-200 text-base-content/35">
                    <i class="fa-solid fa-trash-can text-3xl"></i>
                </div>

                <h3 class="mt-6 text-xl font-black">No deleted countries</h3>
                <p class="mx-auto mt-2 max-w-lg text-sm leading-6 text-base-content/55">The trash is currently empty. Deleted countries will appear here and can be restored while they remain in the trash.</p>

                <div class="mt-6 flex justify-center">
                    <a href="{{ route('admin.countries.index') }}" class="btn btn-primary gap-2">
                        <i class="fa-solid fa-file-lines"></i> View Countries
                    </a>
                </div>
            </div>
        @endif
    </section>

    {{-- ====================== FOOTER INFORMATION ===================== --}}
    @if($countries->isNotEmpty())
        <div class="flex flex-col gap-3 py-4 sm:py-2 text-xs text-base-content/50 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-shield-halved text-success"></i>
                <span>
                    Countries represent <strong class="text-base-content/70">official platform destinations</strong> maintained in CareerVault.
                </span>
            </div>

            <div class="flex items-center gap-2">
                <i class="fa-solid fa-circle-info"></i> CareerVault Country Directory
            </div>
        </div>
    @endif
</div>
@endsection