@extends('layouts.admin.app')

@section('title','Countries / CareerVault Admin')

@section('content')
<div class="space-y-3 p-4 sm:p-6">
    {{-- ========================= HEADER ========================= --}}
    <header class="relative overflow-hidden bg-white">
        <div class="relative flex flex-col gap-6 lg:flex-row justify-center items-center lg:justify-between">
            <div class="flex flex-col">
                <div class="flex justify-center sm:justify-start gap-3 shrink-0 items-center rounded-lg">
                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-primary/10 text-primary">
                        <i class="fa-solid fa-earth-americas text-lg"></i>
                    </div>
                    <h1 class="cv-admin-title text-3xl sm:text-4xl whitespace-nowrap text-primary">Countries</h1>
                </div>

                <p class="mt-2 text-sm leading-6 text-base-content/65 text-center sm:text-left">Manage country reference data used throughout CareerVault.</p>
            </div>

            <div class="flex w-full gap-2 sm:w-auto">
                <a href="{{ route('admin.countries.trash') }}" class="btn btn-ghost min-w-0 flex-1 gap-2 border border-base-300 sm:flex-none sm:border-transparent">
                    <i class="fa-solid fa-trash-can"></i> Trash

                    @php
                        $trashedCountryCount=\App\Models\Country::onlyTrashed()->count();
                    @endphp

                    @if($trashedCountryCount>0)
                        <span class="badge badge-error badge-sm"> {{ $trashedCountryCount }} </span>
                    @endif
                </a>

                <a href="{{ route('admin.countries.create') }}" class="btn btn-primary min-w-0 flex-1 gap-2 whitespace-nowrap sm:flex-none">
                    <i class="fa-solid fa-plus"></i> Add Country
                </a>
            </div>
        </div>
    </header>

    <section class="card border border-base-300 bg-base-100 shadow-sm">
        <div class="card-body p-4">
            <form method="GET" action="{{ route('admin.countries.index') }}" class="flex flex-col gap-3 md:flex-row">
                <div class="form-control flex-1">
                    <label class="input input-bordered input-sm flex items-center gap-2">
                        <i class="fa-solid fa-magnifying-glass text-base-content/50"></i>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search name, ISO, capital or region..." class="grow">
                    </label>
                </div>

                <select name="status" class="select select-bordered select-sm w-full md:w-40">
                    <option value="">All Status</option>
                    <option value="active" @selected(request('status') === 'active')>Active</option>
                    <option value="inactive" @selected(request('status') === 'inactive')>Inactive</option>
                </select>

                <button type="submit" class="btn btn-neutral btn-sm">
                    <i class="fa-solid fa-filter"></i> Filter
                </button>

                @if(request()->filled('search') || request()->filled('status'))
                    <a href="{{ route('admin.countries.index') }}" class="btn btn-ghost btn-sm"> Clear
                    </a>
                @endif
            </form>
        </div>
    </section>

    {{-- ====================== COUNTRY DIRECTORY ==================== --}}
    <section class="mt-10 sm:mt-5">
        {{-- Directory Header --}}
        <div class="px-5 sm:px-0 py-4">
            <div class="flex justify-between items-center">
                <div class="flex items-center gap-2">
                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-primary/10 text-primary">
                        <i class="fa-solid fa-earth-americas text-lg"></i>
                    </div>

                    <span class="whitespace-nowrap">Country Directory</span>
                </div>

                <div class="badge badge-outline gap-1.5">
                    <i class="fa-solid fa-earth-americas text-lg"></i>
                    {{ number_format($countries->total()) }}
                    {{ $countries->total() === 1 ? 'country' : 'countries' }}
                </div>
            </div>

            <p class="mt-3 text-center sm:text-start text-xs text-base-content/50">Countries associated with this career tracking system.</p>
        </div>

        {{-- Desktop Country Directory --}}
        <div class="card border border-base-300 bg-base-100 shadow-sm hidden sm:block">
            <div class="overflow-x-auto">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th class="px-0 md:px-4">Country</th> <th>ISO</th> <th>Capital</th> <th>Region</th> <th>Currency</th> <th>Phone</th> <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($countries as $country)
                            <tr class="hover">
                                <td class="block md:table-cell px-0 md:px-4 py-1 md:py-3">
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
    
                                <td> {{ $country->capital ?: '—' }} </td>
    
                                <td>
                                    <div>{{ $country->region ?: '—' }}</div>
                                    @if($country->subregion)
                                        <div class="text-xs text-base-content/50">{{ $country->subregion }}</div>
                                    @endif
                                </td>
    
                                <td>
                                    <div>{{ $country->currency_code }}</div>
                                    @if($country->currency_name)
                                        <span class="text-xs text-base-content/50">{{ $country->currency_name }}</span>
                                    @endif
                                </td>
    
                                <td> {{ $country->phone_code ?: '—' }} </td>

                                <td>
                                    {{-- ======================= Desktop ACTIONS ======================= --}}
                                    <div class="hidden sm:flex shrink-0 items-center gap-2 xl:w-36 xl:justify-end">
                                        <a href="{{ route('admin.countries.show',$country) }}" class="btn btn-sm btn-outline gap-2">
                                            <i class="fa-solid fa-eye"></i> View
                                        </a>

                                        <div class="dropdown dropdown-center dropdown-left">
                                            <button tabindex="0" class="btn btn-sm btn-ghost btn-square" aria-label="Page actions">
                                                <i class="fa-solid fa-ellipsis-vertical"></i>
                                            </button>

                                            <ul tabindex="0" class="dropdown-content menu z-30 mt-2 w-56 rounded-box border border-base-300 bg-base-100 p-2 shadow-xl">
                                                <li>
                                                    <a href="{{ route('admin.countries.show',$country) }}">
                                                        <i class="fa-solid fa-eye"></i> View country
                                                    </a>
                                                </li>

                                                <li>
                                                    <a href="{{ route('admin.countries.edit',$country) }}">
                                                        <i class="fa-solid fa-pen-to-square"></i> Edit country
                                                    </a>
                                                </li>

                                                <li class="menu-title mt-1 px-3 py-1 text-[10px] uppercase tracking-wider">
                                                    <span>Danger zone</span>
                                                </li>

                                                <li>
                                                    {{-- @can('delete',$country) --}}
                                                        <button
                                                            type="button"
                                                            class="text-error flex items-center gap-2 hover:bg-error/10"
                                                            title="Move country to trash"
                                                            data-admin-action
                                                            data-action-url="{{ route('admin.countries.destroy',$country->slug) }}"
                                                            data-action-method="DELETE"
                                                            data-action-type="danger"
                                                            data-action-title="Move Country to Trash"
                                                            data-action-description="Move <strong>“{{ $country->name }}”</strong> to trash. You can restore it later from the Trash group."
                                                            data-action-icon="fa-solid fa-trash-can"
                                                            data-action-confirm-icon="fa-solid fa-trash-can"
                                                            data-action-confirm-text="Move to Trash"
                                                        >
                                                            <i class="fa-solid fa-trash-can"></i>
                                                            <span class="hidden sm:inline"> Move to Trash</span>
                                                        </button>
                                                    {{-- @endcan --}}
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="py-12 text-center text-base-content/50">
                                    <i class="fa-solid fa-earth-americas mb-3 text-3xl"></i>
                                    <div class="font-medium">No countries found.</div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Mobile Cards --}}
        <div class="flex flex-col gap-2 sm:hidden">
            @forelse($countries as $country)
                <div class="rounded-xl border border-base-300 bg-base-100 shadow-sm">
                    {{-- Main Row --}}
                    <div class="flex items-center gap-3 p-3">
                        {{-- Flag --}}
                        <div class="relative h-12 w-12 shrink-0">
                            <div class="h-12 w-12 overflow-hidden rounded-full ring-2 ring-base-200">
                                <img src="https://flagcdn.com/w80/{{ strtolower($country->iso2) }}.png" alt="{{ $country->name }} flag" class="h-full w-full object-cover">
                            </div>

                            <span class="absolute -bottom-0.5 -right-0.5 flex h-5 w-5 items-center justify-center rounded-full border-2 border-base-100 {{ $country->is_active ? 'bg-success text-success-content' : 'bg-error text-error-content' }}">
                                <i class="fa-solid {{ $country->is_active ? 'fa-check' : 'fa-xmark' }} text-[8px]"></i>
                            </span>
                        </div>

                        {{-- Identity --}}
                        <div class="min-w-0 flex-1">
                            <div class="flex items-center gap-2">
                                <h3 class="truncate text-sm font-semibold text-base-content"> {{ $country->name }} </h3>
                                <span class="shrink-0 font-mono text-[10px] font-semibold text-base-content/40"> #{{ $country->sort_order }} </span>
                            </div>

                            <div class="mt-0.5 flex min-w-0 items-center gap-2">
                                <span class="shrink-0 rounded bg-base-200 px-1.5 py-0.5 font-mono text-[10px] font-bold text-base-content/70"> {{ $country->iso2 }} </span>
                                <span class="shrink-0 font-mono text-[10px] text-base-content/50"> {{ $country->iso3 }} </span>
                                <span class="truncate text-[10px] text-base-content/40"> {{ $country->slug }} </span>
                            </div>
                        </div>

                        {{-- Quick Action --}}
                        <a href="{{ route('admin.countries.show',$country) }}" class="btn btn-sm btn-square btn-ghost shrink-0" aria-label="View {{ $country->name }}">
                            <i class="fa-solid fa-chevron-right text-xs"></i>
                        </a>
                    </div>

                    {{-- Quick Information --}}
                    <div class="grid grid-cols-3 divide-x border-t border-base-200 bg-base-200/30">
                        {{-- Capital --}}
                        <div class="min-w-0 px-3 py-2.5">
                            <div class="text-[9px] font-semibold uppercase tracking-wider text-base-content/40">Capital</div>
                            <div class="mt-0.5 truncate text-xs font-medium text-base-content"> {{ $country->capital ?: '—' }} </div>
                        </div>

                        {{-- Region --}}
                        <div class="min-w-0 px-3 py-2.5">
                            <div class="text-[9px] font-semibold uppercase tracking-wider text-base-content/40">Region</div>
                            <div class="mt-0.5 truncate text-xs font-medium text-base-content"> {{ $country->region ?: '—' }} </div>
                        </div>

                        {{-- Currency --}}
                        <div class="min-w-0 px-3 py-2.5">
                            <div class="text-[9px] font-semibold uppercase tracking-wider text-base-content/40">Currency</div>
                            <div class="mt-0.5 truncate font-mono text-xs font-semibold text-base-content"> {{ $country->currency_code ?: '—' }} </div>
                        </div>
                    </div>

                    {{-- Secondary Information + Actions --}}
                    <div class="flex items-center justify-between gap-3 border-t border-base-200 px-3 py-2">
                        <div class="flex min-w-0 items-center gap-3 text-[10px] text-base-content/50">
                            @if($country->phone_code)
                                <span class="flex items-center gap-1 whitespace-nowrap">
                                    <i class="fa-solid fa-phone text-[9px]"></i> {{ $country->phone_code }}
                                </span>
                            @endif

                            @if($country->subregion)
                                <span class="flex min-w-0 items-center gap-1">
                                    <i class="fa-solid fa-location-dot text-[9px]"></i>
                                    <span class="truncate">{{ $country->subregion }}</span>
                                </span>
                            @endif

                        </div>

                        <div class="flex shrink-0 items-center gap-1">
                            <a href="{{ route('admin.countries.edit',$country) }}" class="btn btn-xs btn-ghost gap-1.5">
                                <i class="fa-solid fa-pen-to-square"></i> Edit
                            </a>

                            <div class="dropdown dropdown-end">
                                <button type="button" tabindex="0" class="btn btn-xs btn-square btn-ghost" aria-label="More actions">
                                    <i class="fa-solid fa-ellipsis-vertical"></i>
                                </button>

                                <ul tabindex="0" class="dropdown-content menu z-30 mt-2 w-52 rounded-box border border-base-300 bg-base-100 p-2 shadow-xl">
                                    <li>
                                        <a href="{{ route('admin.countries.show',$country) }}">
                                            <i class="fa-solid fa-eye"></i> View country
                                        </a>
                                    </li>

                                    <li>
                                        <a href="{{ route('admin.countries.edit',$country) }}">
                                            <i class="fa-solid fa-pen-to-square"></i> Edit country
                                        </a>
                                    </li>

                                    <li class="menu-title mt-1 px-3 py-1 text-[10px] uppercase tracking-wider">
                                        <span>Danger zone</span>
                                    </li>

                                    <li>
                                        <button
                                            type="button"
                                            class="flex items-center gap-2 text-error hover:bg-error/10"
                                            data-admin-action
                                            data-action-url="{{ route('admin.countries.destroy',$country) }}"
                                            data-action-method="DELETE"
                                            data-action-type="danger"
                                            data-action-title="Move Country to Trash"
                                            data-action-description="Move <strong>“{{ $country->name }}”</strong> to trash. You can restore it later from the Trash group."
                                            data-action-icon="fa-solid fa-trash-can"
                                            data-action-confirm-icon="fa-solid fa-trash-can"
                                            data-action-confirm-text="Move to Trash"
                                        >
                                            <i class="fa-solid fa-trash-can"></i> Move to Trash
                                        </button>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="rounded-xl border border-dashed border-base-300 px-4 py-12 text-center text-base-content/50">
                    <i class="fa-solid fa-earth-americas mb-3 text-3xl"></i>
                    <div class="font-medium">No countries found.</div>

                    @if(request()->filled('search') || request()->filled('status'))
                        <div class="mt-1 text-xs">Try adjusting your search or filter.</div>
                    @endif
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        @if($countries->hasPages())
            <div class="border-t border-base-300 p-4"> {{ $countries->links() }} </div>
        @endif
    </section>
</div>
@endsection