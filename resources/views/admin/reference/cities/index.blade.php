@extends('layouts.admin.app')

@section('content')
<div class="space-y-3 p-4 sm:p-6">
    {{-- Header --}}
    <x-admin.ui.page-header
        title="Cities"
        title-color="text-primary"
        title-bg-color="bg-primary/10 text-primary"
        description="Manage cities and their geographic relationships."
        icon="fa-solid fa-map"
        :create-route="route('admin.cities.create')"
        create-text="Add City"
        :trash-route="route('admin.cities.trash')"
        {{-- :trash-count="$trashedStatesCount" --}}
    />

    <div class="card border border-base-300 bg-base-100 shadow-sm">
        <div class="card-body p-4">
            <form method="GET" class="grid grid-cols-1 gap-3 md:grid-cols-2 xl:grid-cols-5">

                <input type="text"
                       name="search"
                       value="{{ request('search') }}"
                       placeholder="Search city..."
                       class="input input-bordered w-full">

                <select name="country" class="select select-bordered w-full">
                    <option value="">All countries</option>
                    @foreach($countries as $country)
                        <option value="{{ $country->slug }}"
                            @selected(request('country') === $country->slug)>
                            {{ $country->name }}
                        </option>
                    @endforeach
                </select>

                <select name="state" class="select select-bordered w-full">
                    <option value="">All states / divisions</option>
                    @foreach($states as $state)
                        <option value="{{ $state->slug }}"
                            @selected(request('state') === $state->slug)>
                            {{ $state->name }} — {{ $state->country->name }}
                        </option>
                    @endforeach
                </select>

                <select name="capital" class="select select-bordered w-full">
                    <option value="">All cities</option>
                    <option value="1" @selected(request('capital') === '1')>
                        Capitals
                    </option>
                    <option value="0" @selected(request('capital') === '0')>
                        Non-capitals
                    </option>
                </select>

                <select name="status" class="select select-bordered w-full">
                    <option value="">All status</option>
                    <option value="1" @selected(request('status') === '1')>
                        Active
                    </option>
                    <option value="0" @selected(request('status') === '0')>
                        Inactive
                    </option>
                </select>

                <div class="flex gap-2 md:col-span-2 xl:col-span-5">
                    <button class="btn btn-primary btn-sm">
                        <i class="fa-solid fa-filter"></i>
                        Filter
                    </button>

                    @if(request()->hasAny(['search', 'country', 'state', 'capital', 'status']))
                        <a href="{{ route('admin.cities.index') }}"
                           class="btn btn-ghost btn-sm">
                            Clear
                        </a>
                    @endif
                </div>

            </form>

        </div>
    </div>

    <div class="hidden overflow-x-auto rounded-box border border-base-300 bg-base-100 md:block">
        <table class="table">
            <thead>
                <tr>
                    <th>City</th> <th>Country</th> <th>State / Division</th> <th>Type</th> <th>Status</th> <th class="text-right">Actions</th>
                </tr>
            </thead>

            <tbody>
                @forelse($cities as $city)
                    <tr>
                        <td>
                            <div class="font-semibold">{{ $city->name }}</div>
                            <div class="text-xs text-base-content/50">
                                {{ $city->slug }}
                                @if($city->code)
                                    · {{ $city->code }}
                                @endif
                            </div>
                        </td>

                        <td> {{ $city->country?->name ?? '—' }} </td>

                        <td> {{ $city->state?->name ?? '—' }} </td>

                        <td>
                            @if($city->is_capital)
                                <span class="badge badge-warning badge-sm">
                                    <i class="fa-solid fa-star mr-1 text-[9px]"></i> Capital
                                </span>
                            @else
                                <span class="text-sm text-base-content/60">City</span>
                            @endif
                        </td>

                        <td>
                            @if($city->is_active)
                                <span class="badge badge-success badge-sm">Active</span>
                            @else
                                <span class="badge badge-ghost badge-sm">Inactive</span>
                            @endif
                        </td>

                        <td class="text-right">
                            <div class="hidden sm:flex shrink-0 items-center gap-2 xl:justify-end">
                                <a href="{{ route('admin.cities.show', $city) }}" class="btn btn-sm btn-outline gap-2">
                                    <i class="fa-solid fa-eye"></i> View
                                </a>

                                <div class="dropdown dropdown-center dropdown-left">
                                    <button tabindex="0" class="btn btn-sm btn-ghost btn-square" aria-label="Page actions">
                                        <i class="fa-solid fa-ellipsis-vertical"></i>
                                    </button>

                                    <ul tabindex="0" class="dropdown-content menu z-30 mt-2 w-56 rounded-box border border-base-300 bg-base-100 p-2 shadow-xl">
                                        <li>
                                            <a href="{{ route('admin.cities.show', $city) }}">
                                                <i class="fa-solid fa-eye"></i> View city
                                            </a>
                                        </li>

                                        <li>
                                            <a href="{{ route('admin.cities.edit', $city) }}">
                                                <i class="fa-solid fa-pen-to-square"></i> Edit city
                                            </a>
                                        </li>

                                        <li class="menu-title mt-1 px-3 py-1 text-[10px] uppercase tracking-wider">
                                            <span>Danger zone</span>
                                        </li>

                                        <li>
                                            <button
                                                type="button"
                                                class="text-error flex items-center gap-2 hover:bg-error/10"
                                                title="Move city to trash"
                                                data-admin-action
                                                data-action-url="{{ route('admin.cities.destroy',$city->slug) }}"
                                                data-action-method="DELETE"
                                                data-action-type="danger"
                                                data-action-title="Move City to Trash"
                                                data-action-description="Move <strong>“{{ $city->name }}”</strong> to trash. You can restore it later from the Trash state."
                                                data-action-icon="fa-solid fa-trash-can"
                                                data-action-confirm-icon="fa-solid fa-trash-can"
                                                data-action-confirm-text="Move to Trash"
                                            >
                                                <i class="fa-solid fa-trash-can"></i>
                                                <span> Move to Trash</span>
                                            </button>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="py-12 text-center">
                            <i class="fa-solid fa-city mb-3 text-3xl text-base-content/30"></i>
                            <div class="font-semibold">No cities found</div>
                            <div class="text-sm text-base-content/50">Try changing your filters or add a new city.</div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="grid gap-3 md:hidden">
        @forelse($cities as $city)
            <div class="card border border-base-300 bg-base-100 shadow-sm">
                <div class="card-body p-4">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <h3 class="font-bold"> {{ $city->name }} </h3>
                            <p class="text-xs text-base-content/50"> {{ $city->slug }} </p>
                        </div>

                        @if($city->is_active)
                            <span class="badge badge-success badge-sm">Active</span>
                        @else
                            <span class="badge badge-ghost badge-sm">Inactive</span>
                        @endif
                    </div>

                    <div class="mt-2 space-y-1 text-sm">
                        <div>
                            <span class="text-base-content/50">Country:</span> {{ $city->country?->name ?? '—' }}
                        </div>

                        <div>
                            <span class="text-base-content/50">State:</span> {{ $city->state?->name ?? '—' }}
                        </div>

                        @if($city->is_capital)
                            <div>
                                <span class="badge badge-warning badge-sm">
                                    <i class="fa-solid fa-star mr-1 text-[9px]"></i> Capital
                                </span>
                            </div>
                        @endif
                    </div>

                    <div class="mt-3 flex justify-around">
                        <a href="{{ route('admin.cities.show', $city) }}">
                            <i class="fa-solid fa-eye mr-2"></i> View city
                        </a>

                        <a href="{{ route('admin.cities.edit', $city) }}">
                            <i class="fa-solid fa-pen-to-square mr-2"></i> Edit city
                        </a>

                        <button
                            type="button"
                            class="text-error flex items-center gap-2 hover:bg-error/10"
                            title="Move city to trash"
                            data-admin-action
                            data-action-url="{{ route('admin.cities.destroy',$city->slug) }}"
                            data-action-method="DELETE"
                            data-action-type="danger"
                            data-action-title="Move City to Trash"
                            data-action-description="Move <strong>“{{ $city->name }}”</strong> to trash. You can restore it later from the Trash state."
                            data-action-icon="fa-solid fa-trash-can"
                            data-action-confirm-icon="fa-solid fa-trash-can"
                            data-action-confirm-text="Move to Trash"
                        >
                            <i class="fa-solid fa-trash-can"></i> Trash
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="rounded-box border border-base-300 p-10 text-center">
                <i class="fa-solid fa-city mb-3 text-3xl text-base-content/30"></i>
                <div class="font-semibold">No cities found</div>
            </div>
        @endforelse
    </div>

    @if($cities->hasPages())
        <div>
            {{ $cities->links() }}
        </div>
    @endif
</div>
@endsection